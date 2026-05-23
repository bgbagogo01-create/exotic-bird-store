<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
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
            margin-top: 25px;
            width: 300px;
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
        <span class="shop-name"><?php echo e($settings['shop_name']); ?></span>
        <?php if($settings['shop_address']): ?>
            <div class="shop-address"><?php echo e($settings['shop_address']); ?></div>
        <?php endif; ?>
        <?php if($settings['shop_phone']): ?>
            <div class="shop-address">Telp/WA: +<?php echo e($settings['shop_phone']); ?></div>
        <?php endif; ?>
        
        <div class="report-title">Laporan Penjualan Transaksi</div>
        <div class="report-date">Rentang Waktu: <?php echo e(\Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y')); ?> s/d <?php echo e(\Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y')); ?></div>
    </div>

    <!-- Data Table -->
    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">#</th>
                <th style="width: 100px;">No Invoice</th>
                <th style="width: 90px;">Tanggal</th>
                <th>Nama Kasir</th>
                <th>Nama Pembeli</th>
                <th class="text-right" style="width: 75px;">Subtotal</th>
                <th class="text-right" style="width: 65px;">Diskon</th>
                <th class="text-right" style="width: 80px;">Total Bersih</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $sumSubtotal = 0;
                $sumDiscount = 0;
                $sumTotal = 0;
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                <td class="font-bold" style="color: #4f46e5;"><?php echo e($tr->invoice_number); ?></td>
                <td><?php echo e($tr->created_at->format('d/m/Y H:i')); ?></td>
                <td><?php echo e($tr->user->name); ?></td>
                <td><?php echo e($tr->buyer_name); ?></td>
                <td class="text-right">Rp <?php echo e(number_format($tr->subtotal, 0, ',', '.')); ?></td>
                <td class="text-right" style="color: #dc2626;">Rp <?php echo e(number_format($tr->discount, 0, ',', '.')); ?></td>
                <td class="text-right font-bold">Rp <?php echo e(number_format($tr->total_price, 0, ',', '.')); ?></td>
            </tr>
            <?php 
                $sumSubtotal += $tr->subtotal;
                $sumDiscount += $tr->discount;
                $sumTotal += $tr->total_price;
            ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center" style="color: #64748b; padding: 15px;">Tidak ada transaksi penjualan tercatat pada rentang waktu ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Financial Totals Summary Card -->
    <table class="summary-box">
        <tbody>
            <tr>
                <td class="summary-title">Total Subtotal</td>
                <td class="summary-value">Rp <?php echo e(number_format($sumSubtotal, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td class="summary-title">Total Potongan Diskon</td>
                <td class="summary-value" style="color:#dc2626;">-Rp <?php echo e(number_format($sumDiscount, 0, ',', '.')); ?></td>
            </tr>
            <tr class="summary-total">
                <td>Total Bersih (Omset)</td>
                <td class="summary-value">Rp <?php echo e(number_format($sumTotal, 0, ',', '.')); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="footer-date">
        Laporan ini dicetak secara otomatis melalui Sistem Inventory EXOTIC BIRD STORE pada tanggal <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y, H:i')); ?> WIB.
    </div>

</body>
</html>
<?php /**PATH D:\syarat kp\project kp\resources\views/reports/pdf_penjualan.blade.php ENDPATH**/ ?>