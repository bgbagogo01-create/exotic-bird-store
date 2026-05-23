<?php

namespace App\Exports;

use App\Models\StockOut;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockOutExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = StockOut::query()->with(['product', 'user']);

        if ($this->startDate) {
            $query->whereDate('date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('date', '<=', $this->endDate);
        }

        return $query->orderBy('date', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal Keluar',
            'Kode SKU',
            'Nama Barang',
            'Jumlah (Pcs)',
            'Alasan Keluar',
            'Pencatat',
            'Catatan',
        ];
    }

    public function map($stockOut): array
    {
        return [
            $stockOut->date,
            $stockOut->product->sku,
            $stockOut->product->name,
            $stockOut->quantity,
            $stockOut->reason,
            $stockOut->user->name,
            $stockOut->notes ?: '-',
        ];
    }
}
