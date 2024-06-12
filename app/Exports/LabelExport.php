<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class LabelExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            $row->id,
            $row->request_id,
            $row->request_date,
            $row->name,
            $row->status,
        ];
    }
    

    public function headings(): array
    {
        return [
            'ID',
            'Request ID',
            'Request Date',
            'name',
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
