<?php

namespace App\Exports;

use App\Models\StockIn;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockInExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = StockIn::query()->with(['product', 'user']);

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
            'Tanggal Masuk',
            'Kode SKU',
            'Nama Barang',
            'Jumlah (Pcs)',
            'Supplier',
            'Pencatat',
            'Catatan',
        ];
    }

    public function map($stockIn): array
    {
        return [
            $stockIn->date,
            $stockIn->product->sku,
            $stockIn->product->name,
            $stockIn->quantity,
            $stockIn->supplier ?: '-',
            $stockIn->user->name,
            $stockIn->notes ?: '-',
        ];
    }
}
