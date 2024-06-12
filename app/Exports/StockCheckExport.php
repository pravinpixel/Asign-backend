<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class StockCheckExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $stock;

    public function __construct($data)
    {
        $this->stock = $data;
    }

    public function collection(): Collection
    {
        $rows = [];
        if($this->stock->type == 'location'){
            $rows[] = [
                'Check Type' => $this->stock->type,
                'Stock Location' => $this->stock->location->name,
            ];
        }else{
            $rows[] = [
                'Check Type' => $this->stock->type,
                'Stock Location' => $this->stock->location->name,
                'Agent' => $this->stock->agent->name,
            ]; 
        }
        
        $rows[] = ['', ''];
        $rows[] = [
            'Product Name' => 'Product Name',
            'Expected Stock' => 'Expected Stock',
            'Actual Stock' => 'Actual Stock',
        ];

        foreach ($this->stock->products as $product) {
            $rows[] = [
                'Product Name' => $product->product->name,
                'Expected Stock' => $product->qty,
                'Actual Stock' => $product->on_hand,
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        if($this->stock->type == 'location'){
            return ['Check Type', 'Stock Location'];
        }else{
            return ['Check Type', 'Stock Location','Agent'];
        }
        
    }

    public function styles($sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, 
            'B' => 25, 
            'C' => 25, 
            'D' => 20, 
            'E' => 20, 
        ];
    }
}
