<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display stock log and adjustment screen
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('name', 'asc')->get();

        // 1. Fetch Stock In logs with filters
        $stockInQuery = StockIn::with(['product', 'user']);
        if ($request->has('product_id') && !empty($request->product_id)) {
            $stockInQuery->where('product_id', $request->product_id);
        }
        if ($request->has('start_date') && !empty($request->start_date)) {
            $stockInQuery->whereDate('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && !empty($request->end_date)) {
            $stockInQuery->whereDate('date', '<=', $request->end_date);
        }
        $stockIns = $stockInQuery->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'stock_in_page');

        // 2. Fetch Stock Out logs with filters
        $stockOutQuery = StockOut::with(['product', 'user']);
        if ($request->has('product_id') && !empty($request->product_id)) {
            $stockOutQuery->where('product_id', $request->product_id);
        }
        if ($request->has('start_date') && !empty($request->start_date)) {
            $stockOutQuery->whereDate('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && !empty($request->end_date)) {
            $stockOutQuery->whereDate('date', '<=', $request->end_date);
        }
        $stockOuts = $stockOutQuery->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'stock_out_page');

        return view('stock.index', compact('products', 'stockIns', 'stockOuts'));
    }

    /**
     * Store a new Stock In adjustment (Incoming Stock)
     */
    public function storeIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'supplier' => 'nullable|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ], [
            'product_id.required' => 'Barang wajib dipilih.',
            'quantity.required' => 'Kuantitas wajib diisi.',
            'quantity.min' => 'Kuantitas minimal adalah 1.',
            'date.required' => 'Tanggal wajib diisi.',
        ]);

        try {
            DB::beginTransaction();

            // Create Stock In Log
            StockIn::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'quantity' => $request->quantity,
                'supplier' => $request->supplier,
                'date' => $request->date,
                'notes' => $request->notes,
            ]);

            // Update Product Stock
            $product = Product::findOrFail($request->product_id);
            $product->increment('stock', $request->quantity);
            
            // Auto update status
            if ($product->stock > 0) {
                $product->update(['status' => 'tersedia']);
            }

            DB::commit();

            return redirect()->route('stock.index')
                ->with('toast_success', "Berhasil mencatat barang masuk: {$product->name} (+{$request->quantity} pcs).");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('toast_error', 'Gagal memproses data barang masuk: ' . $e->getMessage());
        }
    }

    /**
     * Store a new Stock Out adjustment (Outgoing Stock)
     */
    public function storeOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ], [
            'product_id.required' => 'Barang wajib dipilih.',
            'quantity.required' => 'Kuantitas wajib diisi.',
            'quantity.min' => 'Kuantitas minimal adalah 1.',
            'reason.required' => 'Alasan wajib diisi.',
            'date.required' => 'Tanggal wajib diisi.',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Validation: Outgoing quantity cannot exceed active stock
        if ($request->quantity > $product->stock) {
            return back()->withErrors(['quantity' => "Kuantitas keluar ({$request->quantity} pcs) melebihi stok yang tersedia saat ini ({$product->stock} pcs)."])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create Stock Out Log
            StockOut::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'date' => $request->date,
                'notes' => $request->notes,
            ]);

            // Update Product Stock
            $product->decrement('stock', $request->quantity);

            // Auto update status
            if ($product->stock <= 0) {
                $product->update(['status' => 'habis']);
            }

            DB::commit();

            return redirect()->route('stock.index')
                ->with('toast_success', "Berhasil mencatat barang keluar: {$product->name} (-{$request->quantity} pcs).");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('toast_error', 'Gagal memproses data barang keluar: ' . $e->getMessage());
        }
    }
}
