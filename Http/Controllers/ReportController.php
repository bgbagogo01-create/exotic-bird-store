<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Transaction;
use App\Models\Setting;
use App\Exports\TransactionsExport;
use App\Exports\StockInExport;
use App\Exports\StockOutExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display reporting dashboard with summary widgets
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // 1. Sales Statistics
        $salesCount = Transaction::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->count();
        $salesRevenue = Transaction::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('total_price');

        // 2. Stock Statistics
        $stockInCount = StockIn::whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->sum('quantity');
        $stockOutCount = StockOut::whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->sum('quantity');

        // 3. Detailed Lists
        $transactions = Transaction::with('user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $stockIns = StockIn::with(['product', 'user'])
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $stockOuts = StockOut::with(['product', 'user'])
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $shopCurrency = Setting::get('shop_currency', 'Rp');

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'salesCount',
            'salesRevenue',
            'stockInCount',
            'stockOutCount',
            'transactions',
            'stockIns',
            'stockOuts',
            'shopCurrency'
        ));
    }

    /**
     * Export reports to PDF
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:sales,stock_in,stock_out',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $type = $request->report_type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $settings = [
            'shop_name' => Setting::get('shop_name', 'EXOTIC BIRD STORE'),
            'shop_address' => Setting::get('shop_address', ''),
            'shop_phone' => Setting::get('shop_phone', ''),
            'shop_currency' => Setting::get('shop_currency', 'Rp'),
        ];

        if ($type === 'sales') {
            $data = Transaction::with(['user', 'details.product'])
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->orderBy('created_at', 'desc')
                ->get();
                
            $pdf = Pdf::loadView('reports.pdf_penjualan', compact('data', 'startDate', 'endDate', 'settings'));
            return $pdf->stream("laporan_penjualan_{$startDate}_to_{$endDate}.pdf");
        } 
        
        if ($type === 'stock_in') {
            $data = StockIn::with(['product', 'user'])
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->orderBy('date', 'desc')
                ->get();
                
            $pdf = Pdf::loadView('reports.pdf_stock', compact('data', 'startDate', 'endDate', 'settings', 'type'));
            return $pdf->stream("laporan_barang_masuk_{$startDate}_to_{$endDate}.pdf");
        }

        if ($type === 'stock_out') {
            $data = StockOut::with(['product', 'user'])
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->orderBy('date', 'desc')
                ->get();
                
            $pdf = Pdf::loadView('reports.pdf_stock', compact('data', 'startDate', 'endDate', 'settings', 'type'));
            return $pdf->stream("laporan_barang_keluar_{$startDate}_to_{$endDate}.pdf");
        }

        return back()->with('toast_error', 'Tipe laporan tidak valid.');
    }

    /**
     * Export reports to Excel (via styled HTML spreadsheet)
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:sales,stock_in,stock_out',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $type = $request->report_type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $filename = "laporan_{$type}_{$startDate}_to_{$endDate}.xls";

        $headers = [
            "Content-type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $shopName = Setting::get('shop_name', 'EXOTIC BIRD STORE');

        if ($type === 'sales') {
            $data = Transaction::with('user')
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->orderBy('created_at', 'desc')
                ->get();

            $html = '
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Laporan Penjualan</x:Name>
    <x:WorksheetOptions>
     <x:DisplayGridlines/>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
  table { border-collapse: collapse; font-family: \'Segoe UI\', Arial, sans-serif; }
  th { background-color: #4f46e5; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 10px; font-size: 11pt; text-align: center; }
  td { border: 1px solid #e2e8f0; padding: 8px; font-size: 10pt; color: #334155; }
  .title { font-size: 16pt; font-weight: bold; color: #1e293b; }
  .subtitle { font-size: 11pt; color: #64748b; }
  .zebra { background-color: #f8fafc; }
  .text-right { text-align: right; }
  .text-center { text-align: center; }
  .bold { font-weight: bold; }
  .footer-total { background-color: #f1f5f9; font-weight: bold; border-top: 2px solid #cbd5e1; border-bottom: 2px double #cbd5e1; }
</style>
</head>
<body>
  <table>
    <tr>
      <td colspan="10" class="title">' . strtoupper($shopName) . '</td>
    </tr>
    <tr>
      <td colspan="10" class="title">LAPORAN TRANSAKSI PENJUALAN</td>
    </tr>
    <tr>
      <td colspan="10" class="subtitle">Periode: ' . Carbon::parse($startDate)->format('d/m/Y') . ' s/d ' . Carbon::parse($endDate)->format('d/m/Y') . '</td>
    </tr>
    <tr>
      <td colspan="10"></td>
    </tr>
    <thead>
      <tr>
        <th>No</th>
        <th>Nomor Invoice</th>
        <th>Tanggal</th>
        <th>Kasir</th>
        <th>Nama Pembeli</th>
        <th>Subtotal (Rp)</th>
        <th>Diskon (Rp)</th>
        <th>Total Belanja (Rp)</th>
        <th>Jumlah Bayar (Rp)</th>
        <th>Kembalian (Rp)</th>
      </tr>
    </thead>
    <tbody>';

            $totalSubtotal = 0;
            $totalDiscount = 0;
            $totalPrice = 0;
            $totalPay = 0;
            $totalChange = 0;

            foreach ($data as $index => $tr) {
                $zebraClass = ($index % 2 === 1) ? 'zebra' : '';
                $totalSubtotal += $tr->subtotal;
                $totalDiscount += $tr->discount;
                $totalPrice += $tr->total_price;
                $totalPay += $tr->pay_amount;
                $totalChange += $tr->change_amount;

                $html .= '
      <tr class="' . $zebraClass . '">
        <td class="text-center">' . ($index + 1) . '</td>
        <td class="bold text-center">' . $tr->invoice_number . '</td>
        <td class="text-center">' . $tr->created_at->format('Y-m-d H:i:s') . '</td>
        <td>' . ($tr->user ? $tr->user->name : '-') . '</td>
        <td>' . ($tr->buyer_name ?: 'Pelanggan Umum') . '</td>
        <td class="text-right">' . number_format($tr->subtotal, 0, ',', '.') . '</td>
        <td class="text-right">' . number_format($tr->discount, 0, ',', '.') . '</td>
        <td class="text-right bold" style="color: #4f46e5;">' . number_format($tr->total_price, 0, ',', '.') . '</td>
        <td class="text-right" style="color: #16a34a;">' . number_format($tr->pay_amount, 0, ',', '.') . '</td>
        <td class="text-right">' . number_format($tr->change_amount, 0, ',', '.') . '</td>
      </tr>';
            }

            $html .= '
      <tr class="footer-total">
        <td colspan="5" class="text-center bold">TOTAL KESELURUHAN</td>
        <td class="text-right">' . number_format($totalSubtotal, 0, ',', '.') . '</td>
        <td class="text-right">' . number_format($totalDiscount, 0, ',', '.') . '</td>
        <td class="text-right" style="color: #4f46e5;">' . number_format($totalPrice, 0, ',', '.') . '</td>
        <td class="text-right" style="color: #16a34a;">' . number_format($totalPay, 0, ',', '.') . '</td>
        <td class="text-right">' . number_format($totalChange, 0, ',', '.') . '</td>
      </tr>
    </tbody>
  </table>
</body>
</html>';

            return response($html, 200, $headers);
        } elseif ($type === 'stock_in') {
            $data = StockIn::with(['product', 'user'])
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->orderBy('date', 'desc')
                ->get();

            $html = '
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Laporan Barang Masuk</x:Name>
    <x:WorksheetOptions>
     <x:DisplayGridlines/>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
  table { border-collapse: collapse; font-family: \'Segoe UI\', Arial, sans-serif; }
  th { background-color: #10b981; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 10px; font-size: 11pt; text-align: center; }
  td { border: 1px solid #e2e8f0; padding: 8px; font-size: 10pt; color: #334155; }
  .title { font-size: 16pt; font-weight: bold; color: #1e293b; }
  .subtitle { font-size: 11pt; color: #64748b; }
  .zebra { background-color: #f8fafc; }
  .text-right { text-align: right; }
  .text-center { text-align: center; }
  .bold { font-weight: bold; }
  .footer-total { background-color: #f1f5f9; font-weight: bold; border-top: 2px solid #cbd5e1; border-bottom: 2px double #cbd5e1; }
</style>
</head>
<body>
  <table>
    <tr>
      <td colspan="8" class="title">' . strtoupper($shopName) . '</td>
    </tr>
    <tr>
      <td colspan="8" class="title">LAPORAN MUTASI BARANG MASUK</td>
    </tr>
    <tr>
      <td colspan="8" class="subtitle">Periode: ' . Carbon::parse($startDate)->format('d/m/Y') . ' s/d ' . Carbon::parse($endDate)->format('d/m/Y') . '</td>
    </tr>
    <tr>
      <td colspan="8"></td>
    </tr>
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal Masuk</th>
        <th>Kode SKU</th>
        <th>Nama Barang</th>
        <th>Jumlah (Pcs)</th>
        <th>Supplier</th>
        <th>Pencatat (Staff)</th>
        <th>Catatan</th>
      </tr>
    </thead>
    <tbody>';

            $totalQty = 0;

            foreach ($data as $index => $row) {
                $zebraClass = ($index % 2 === 1) ? 'zebra' : '';
                $totalQty += $row->quantity;

                $html .= '
      <tr class="' . $zebraClass . '">
        <td class="text-center">' . ($index + 1) . '</td>
        <td class="text-center">' . $row->date . '</td>
        <td class="bold text-center">' . ($row->product ? $row->product->sku : '-') . '</td>
        <td>' . ($row->product ? $row->product->name : '-') . '</td>
        <td class="text-center bold" style="color: #059669;">' . $row->quantity . '</td>
        <td>' . ($row->supplier ?: '-') . '</td>
        <td>' . ($row->user ? $row->user->name : '-') . '</td>
        <td>' . ($row->notes ?: '-') . '</td>
      </tr>';
            }

            $html .= '
      <tr class="footer-total">
        <td colspan="4" class="text-center bold">TOTAL BARANG MASUK</td>
        <td class="text-center bold" style="color: #059669;">' . $totalQty . ' Pcs</td>
        <td colspan="3"></td>
      </tr>
    </tbody>
  </table>
</body>
</html>';

            return response($html, 200, $headers);
        } else { // stock_out
            $data = StockOut::with(['product', 'user'])
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->orderBy('date', 'desc')
                ->get();

            $html = '
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Laporan Barang Keluar</x:Name>
    <x:WorksheetOptions>
     <x:DisplayGridlines/>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
  table { border-collapse: collapse; font-family: \'Segoe UI\', Arial, sans-serif; }
  th { background-color: #f43f5e; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 10px; font-size: 11pt; text-align: center; }
  td { border: 1px solid #e2e8f0; padding: 8px; font-size: 10pt; color: #334155; }
  .title { font-size: 16pt; font-weight: bold; color: #1e293b; }
  .subtitle { font-size: 11pt; color: #64748b; }
  .zebra { background-color: #f8fafc; }
  .text-right { text-align: right; }
  .text-center { text-align: center; }
  .bold { font-weight: bold; }
  .footer-total { background-color: #f1f5f9; font-weight: bold; border-top: 2px solid #cbd5e1; border-bottom: 2px double #cbd5e1; }
</style>
</head>
<body>
  <table>
    <tr>
      <td colspan="8" class="title">' . strtoupper($shopName) . '</td>
    </tr>
    <tr>
      <td colspan="8" class="title">LAPORAN MUTASI BARANG KELUAR</td>
    </tr>
    <tr>
      <td colspan="8" class="subtitle">Periode: ' . Carbon::parse($startDate)->format('d/m/Y') . ' s/d ' . Carbon::parse($endDate)->format('d/m/Y') . '</td>
    </tr>
    <tr>
      <td colspan="8"></td>
    </tr>
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal Keluar</th>
        <th>Kode SKU</th>
        <th>Nama Barang</th>
        <th>Jumlah (Pcs)</th>
        <th>Alasan Keluar</th>
        <th>Pencatat (Staff)</th>
        <th>Catatan</th>
      </tr>
    </thead>
    <tbody>';

            $totalQty = 0;

            foreach ($data as $index => $row) {
                $zebraClass = ($index % 2 === 1) ? 'zebra' : '';
                $totalQty += $row->quantity;

                $html .= '
      <tr class="' . $zebraClass . '">
        <td class="text-center">' . ($index + 1) . '</td>
        <td class="text-center">' . $row->date . '</td>
        <td class="bold text-center">' . ($row->product ? $row->product->sku : '-') . '</td>
        <td>' . ($row->product ? $row->product->name : '-') . '</td>
        <td class="text-center bold" style="color: #e11d48;">' . $row->quantity . '</td>
        <td>' . ($row->reason ?: '-') . '</td>
        <td>' . ($row->user ? $row->user->name : '-') . '</td>
        <td>' . ($row->notes ?: '-') . '</td>
      </tr>';
            }

            $html .= '
      <tr class="footer-total">
        <td colspan="4" class="text-center bold">TOTAL BARANG KELUAR</td>
        <td class="text-center bold" style="color: #e11d48;">' . $totalQty . ' Pcs</td>
        <td colspan="3"></td>
      </tr>
    </tbody>
  </table>
</body>
</html>';

            return response($html, 200, $headers);
        }
    }
}
