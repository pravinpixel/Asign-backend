<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class StockTransferOrderExport implements FromArray, WithHeadings, WithMapping, WithStyles
{
    protected $orders;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function map($row): array
    {

        return [
            $row->sto_no,
            $row->created_date,
            $row->source_location,
            $row->to_location,
            $row->status,
        ];
    }


    public function headings(): array
    {
        return [
            'Request ID',
            'Request Date',
            'From Location',
            'To Location',
            'Status',
        ];
    }

    public function array(): array
    {
        return $this->orders;
    }

    public function styles($sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
