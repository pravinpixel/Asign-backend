<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class BussinessExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $result;
   

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function collection()
    {
        return $this->result;
    }

    public function map($row): array
    {

        return [
            $row->aa_no,
            $row->display_name,
            $row->account_type,
            $row->city,
            $row->mobile,
            $row->email,
            $row->status,
        ];
    }

    public function headings(): array
    {
        return [
            'Code',
            'Name',
            'Type',
            'City',
            'Mobile',
            'Email',
            'Status',
        ];
    }

    public function styles($sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
