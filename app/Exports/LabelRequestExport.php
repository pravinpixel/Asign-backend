<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;



class LabelRequestExport implements FromCollection, WithHeadings, WithStyles
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
            'Quantity' => 'Quantity',
        ];

       
        foreach ($this->label->products as $product) {
            $rows[] = [
                'Product Name' => $product->product->name,
                'Quantity' => $product->qty,
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

