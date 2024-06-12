<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Carbon\Carbon;

class StockExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            $row->request_id,
            Carbon::parse($row->date)->format('d M, Y'),
            $row->type,
            $row->name,
            $row->status,
        ];
    }
    

    public function headings(): array
    {
        return [
            'Check No',
            'Check Date',
            'Check Type',
            'Check Details',
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
