<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = Transaction::query()->with('user');

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Nomor Invoice',
            'Tanggal',
            'Kasir',
            'Nama Pembeli',
            'Subtotal (Rp)',
            'Diskon (Rp)',
            'Total Belanja (Rp)',
            'Jumlah Bayar (Rp)',
            'Kembalian (Rp)',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->invoice_number,
            $transaction->created_at->format('Y-m-d H:i:s'),
            $transaction->user->name,
            $transaction->buyer_name,
            $transaction->subtotal,
            $transaction->discount,
            $transaction->total_price,
            $transaction->pay_amount,
            $transaction->change_amount,
        ];
    }
}
