<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class LabelDamageExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            $row['ref_no'],
            $row['date'],
            $row['added_by'],
            $row['location'],
            $row['total_damaged_label'],
        ];
    }
    

    public function headings(): array
    {
        return [
            'REF NO',
            'DATE',
            'ADDED BY',
            'LOCATION',
            'TOTAL DAMAGED LABELS',
        ];
    }

    public function styles($sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
