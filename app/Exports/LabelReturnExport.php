<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class LabelReturnExport implements FromCollection, WithHeadings, WithStyles
{
    protected $label;

    public function __construct($data)
    {
        
        $this->label = $data;
    }

    public function collection(): Collection
    {
        
        $rows = [];
        
       
        $rows[] = [
            'Request No.' => $this->label->request_id,
            'Agent Name' => $this->label->agent->name,
        ];

       
        $rows[] = ['', ''];

        
        $rows[] = [
            'Product Name' => 'Product Name',
            'Balance Qty' => 'Balance Qty',
            'Returned Qty' => 'Returned Qty',
        ];

       
        foreach ($this->label->products as $product) {
           
            $balance_qty = $product->balance_qty + $product->returned_qty;
       
            $rows[] = [
                'Product Name' => $product->product->name,
                'Balance Qty' => $balance_qty,
                'Returned Qty' => $product->returned_qty,
            ];
        }

        
        return collect($rows);
    }

    
    public function headings(): array
    {
        return [
            'Request No.',
            'Agent Name',
            
        ];
    }

    public function styles($sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
        ];
    }

}
