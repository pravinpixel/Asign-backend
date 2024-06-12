<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class PurchaseOrderExport implements FromArray, WithHeadings, WithMapping, WithStyles
{
    protected $orders;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function map($row): array
    {

        return [
            $row->order_date,
            $row->purchase_order_no,
            $row->name,
            $row->location,
            $row->status,
        ];
    }


    public function headings(): array
    {
        return [
            'Order Date',
            'Purchase Order No',
            'Manufacturer Name',
            'Delivery Location',
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
