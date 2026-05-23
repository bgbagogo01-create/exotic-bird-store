<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display the cashier POS screen
     */
    public function pos()
    {
        $products = Product::where('status', 'tersedia')
            ->where('stock', '>', 0)
            ->orderBy('name', 'asc')
            ->get();
            
        $shopCurrency = Setting::get('shop_currency', 'Rp');
        return view('transactions.pos', compact('products', 'shopCurrency'));
    }

    /**
     * Display a listing of transaction history
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'details.product']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%");
        }

        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Store a newly created transaction (POS Checkout)
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'buyer_name' => 'nullable|string|max:255',
            'cart' => 'required|json', // [{"product_id": 1, "quantity": 2}, ...]
            'discount' => 'nullable|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart)) {
            return back()->with('toast_error', 'Keranjang belanja kosong!')->withInput();
        }

        // Generate Invoice Number: INV-YYYYMMDD-0001
        $dateStr = date('Ymd');
        $todayTransactionsCount = Transaction::whereDate('created_at', today())->count();
        $sequence = str_pad($todayTransactionsCount + 1, 4, '0', STR_PAD_LEFT);
        $invoiceNumber = "INV-{$dateStr}-{$sequence}";

        try {
            $subtotal = 0;

            DB::beginTransaction();

            // 1. Validate stocks and calculate subtotal
            $validatedItems = [];
            foreach ($cart as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok untuk produk '{$product->name}' tidak mencukupi (Tersedia: {$product->stock} pcs, Diminta: {$item['quantity']} pcs).");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $validatedItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            // 2. Compute discount & total price
            $discount = $request->discount ?? 0;
            $totalPrice = $subtotal - $discount;
            if ($totalPrice < 0) $totalPrice = 0;

            // 3. Validate payment amount
            $payAmount = $request->pay_amount;
            if ($payAmount < $totalPrice) {
                throw new \Exception("Jumlah pembayaran (Rp " . number_format($payAmount, 0, ',', '.') . ") kurang dari total belanja (Rp " . number_format($totalPrice, 0, ',', '.') . ").");
            }

            $changeAmount = $payAmount - $totalPrice;

            // 4. Create Transaction
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => Auth::id(),
                'buyer_name' => $request->buyer_name ?: 'Pelanggan Umum',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_price' => $totalPrice,
                'pay_amount' => $payAmount,
                'change_amount' => $changeAmount,
            ]);

            // 5. Create Transaction Details and Decrement Stocks
            foreach ($validatedItems as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Decrement stock
                $product = $item['product'];
                $product->decrement('stock', $item['quantity']);

                // Auto update status if out of stock
                if ($product->stock <= 0) {
                    $product->update(['status' => 'habis']);
                }
            }

            DB::commit();

            return redirect()->route('transactions.index')
                ->with('toast_success', "Transaksi checkout berhasil dilakukan! Nomor Invoice: {$invoiceNumber}")
                ->with('print_receipt_id', $transaction->id); // Store ID in session for auto print trigger!

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('toast_error', 'Gagal memproses transaksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display receipt details in standard clean layout for print popup
     */
    public function printReceipt($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);
        
        $settings = [
            'shop_name' => Setting::get('shop_name', 'EXOTIC BIRD STORE'),
            'shop_address' => Setting::get('shop_address', ''),
            'shop_phone' => Setting::get('shop_phone', ''),
            'shop_currency' => Setting::get('shop_currency', 'Rp'),
        ];
        
        return view('transactions.struk', compact('transaction', 'settings'));
    }
}
