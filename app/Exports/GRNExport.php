<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class GRNExport implements FromArray, WithHeadings, WithMapping, WithStyles
{
    protected $grn;

    public function __construct(array $grn)
    {
        $this->grn = $grn;
    }

    public function map($row): array
    {

        return [
            $row->grn_no,
            $row->order_no,
            $row->sender,
            $row->branch_name,
            $row->created_on,
        ];
    }


    public function headings(): array
    {
        return [
            'GRN No',
            'Order No',
            'Sender',
            'GRN Location',
            'Created On',
        ];
    }

    public function array(): array
    {
        return $this->grn;
    }

    public function styles($sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

}
