<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mutasi Stok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .shop-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e293b;
        }
        .shop-address {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #4f46e5;
            text-decoration: underline;
        }
        .report-date {
            font-size: 10px;
            color: #475569;
            margin-bottom: 15px;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-data th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            padding: 7px 5px;
            text-align: left;
            text-transform: uppercase;
            font-size: 9px;
        }
        .table-data td {
            border: 1px solid #e2e8f0;
            padding: 6px 5px;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .summary-box {
            margin-top: 20px;
            width: 250px;
            float: right;
            border-collapse: collapse;
        }
        .summary-box td {
            padding: 4px 8px;
            font-size: 10px;
        }
        .summary-title {
            color: #475569;
        }
        .summary-value {
            font-weight: bold;
            text-align: right;
        }
        .summary-total {
            border-top: 2px solid #1e293b;
            font-size: 12px;
            color: #4f46e5;
            font-weight: bold;
        }
        .footer-date {
            margin-top: 40px;
            font-size: 9px;
            color: #94a3b8;
            clear: both;
        }
    </style>
</head>
<body>

    <!-- Shop Branding Header -->
    <div class="header">
        <span class="shop-name">{{ $settings['shop_name'] }}</span>
        @if($settings['shop_address'])
            <div class="shop-address">{{ $settings['shop_address'] }}</div>
        @endif
        @if($settings['shop_phone'])
            <div class="shop-address">Telp/WA: +{{ $settings['shop_phone'] }}</div>
        @endif
        
        <div class="report-title">
            @if($type === 'stock_in')
                Laporan Barang Masuk Gudang
            @else
                Laporan Barang Keluar Gudang
            @endif
        </div>
        <div class="report-date">Rentang Waktu: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</div>
    </div>

    <!-- Data Table -->
    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">#</th>
                <th style="width: 80px;">Tanggal</th>
                <th style="width: 90px;">Kode SKU</th>
                <th>Nama Barang</th>
                <th class="text-center" style="width: 60px;">Jumlah</th>
                @if($type === 'stock_in')
                    <th>Supplier/Pemasok</th>
                @else
                    <th>Alasan Keluar</th>
                @endif
                <th style="width: 100px;">Pencatat</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php $sumQuantity = 0; @endphp
            @forelse($data as $row)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $row->date }}</td>
                <td class="font-bold">{{ $row->product->sku }}</td>
                <td>{{ $row->product->name }}</td>
                <td class="text-center font-bold" style="color: {{ $type === 'stock_in' ? '#16a34a' : '#dc2626' }};">
                    {{ $type === 'stock_in' ? '+' : '-' }}{{ $row->quantity }} pcs
                </td>
                @if($type === 'stock_in')
                    <td>{{ $row->supplier ?: '-' }}</td>
                @else
                    <td class="font-bold" style="text-transform: uppercase;">
                        @if($row->reason === 'rusak') Rusak
                        @elseif($row->reason === 'hilang') Hilang
                        @elseif($row->reason === 'expired') Expired
                        @else Penyesuaian
                        @endif
                    </td>
                @endif
                <td>{{ $row->user->name }}</td>
                <td style="color: #64748b; font-style: italic;">{{ $row->notes ?: '-' }}</td>
            </tr>
            @php $sumQuantity += $row->quantity; @endphp
            @empty
            <tr>
                <td colspan="8" class="text-center" style="color: #64748b; padding: 15px;">Tidak ada penyesuaian stok mutasi tercatat pada rentang waktu ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Quantity Summary Box -->
    <table class="summary-box">
        <tbody>
            <tr class="summary-total">
                <td>Total Kuantitas Barang</td>
                <td class="summary-value" style="color: {{ $type === 'stock_in' ? '#16a34a' : '#dc2626' }};">
                    {{ $type === 'stock_in' ? '+' : '-' }}{{ $sumQuantity }} Pcs
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer-date">
        Laporan ini dicetak secara otomatis melalui Sistem Inventory EXOTIC BIRD STORE pada tanggal {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y, H:i') }} WIB.
    </div>

</body>
</html>
