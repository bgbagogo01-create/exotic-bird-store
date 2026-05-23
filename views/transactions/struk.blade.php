<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk #{{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 10px;
            width: 280px; /* Standard 58mm thermal paper width approx */
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .shop-name {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .shop-detail {
            font-size: 10px;
            margin-top: 2px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .meta-info {
            font-size: 10px;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .item-row td {
            padding: 3px 0;
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .summary-table {
            width: 100%;
            font-size: 11px;
            margin-top: 5px;
        }
        .summary-table td {
            padding: 2px 0;
        }
        .total-amount {
            font-weight: bold;
            font-size: 13px;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            margin-top: 15px;
            line-height: 1.3;
        }
        /* Hide from printed sheet controls */
        @media print {
            .no-print {
                display: none;
            }
        }
        .print-btn-panel {
            margin-bottom: 15px;
            text-align: center;
        }
        .btn-print {
            background: #4f46e5;
            color: #fff;
            border: 0;
            padding: 6px 15px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Manual Control panel visible in viewport, hidden during print -->
    <div class="print-btn-panel no-print">
        <button onclick="window.print()" class="btn-print">Cetak Struk</button>
        <button onclick="window.close()" class="btn-print" style="background:#dc2626; margin-left: 5px;">Tutup</button>
        <div style="border-bottom: 2px solid #ccc; margin-top: 10px; padding-bottom: 5px;"></div>
    </div>

    <!-- Header Branding -->
    <div class="header">
        <span class="shop-name">{{ $settings['shop_name'] }}</span>
        @if($settings['shop_address'])
            <div class="shop-detail">{{ $settings['shop_address'] }}</div>
        @endif
        @if($settings['shop_phone'])
            <div class="shop-detail">Telp/WA: +{{ $settings['shop_phone'] }}</div>
        @endif
    </div>

    <div class="divider"></div>

    <!-- Transaction Meta Details -->
    <div class="meta-info">
        <div>Inv   : {{ $transaction->invoice_number }}</div>
        <div>Waktu : {{ $transaction->created_at->format('d/m/Y H:i:s') }}</div>
        <div>Kasir : {{ $transaction->user->name }}</div>
        <div>Beli  : {{ $transaction->buyer_name }}</div>
    </div>

    <div class="divider"></div>

    <!-- Itemized List Table -->
    <table class="item-table">
        <tbody>
            @foreach($transaction->details as $item)
            <tr class="item-row">
                <td colspan="2">
                    {{ $item->product ? $item->product->name : 'Barang Dihapus' }}
                </td>
            </tr>
            <tr class="item-row" style="font-size: 10px; color: #555;">
                <td>
                    &nbsp;&nbsp;{{ $item->quantity }} pcs x {{ number_format($item->price, 0, ',', '.') }}
                </td>
                <td class="text-right">
                    {{ number_format($item->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <!-- Bill Math Summary -->
    <table class="summary-table">
        <tbody>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($transaction->discount > 0)
            <tr>
                <td>Diskon</td>
                <td class="text-right">-{{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-amount">
                <td>Total</td>
                <td class="text-right">{{ $settings['shop_currency'] }} {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr class="divider-row"><td colspan="2"><div class="divider"></div></td></tr>
            <tr>
                <td>Bayar Tunai</td>
                <td class="text-right">{{ number_format($transaction->pay_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="text-right">{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="divider"></div>

    <!-- Footer greetings -->
    <div class="footer">
        ** TERIMA KASIH **<br>
        Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.<br>
        Layanan Pelanggan Hubungi +{{ $settings['shop_phone'] }}
    </div>

    <!-- Auto Print Script -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Short delay to allow fonts to render perfectly, then trigger print!
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
