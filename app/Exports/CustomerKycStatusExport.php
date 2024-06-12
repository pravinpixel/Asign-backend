<?php

namespace App\Exports;

use App\CustomerKycStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerKycStatusExport implements FromCollection, WithHeadings
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
    

    public function headings(): array
    {
        return [
            'ID',
            'Customer ID',
            'Name',
            'Email',
            'Mobile',
            'Type',
            'Request',
            'Response',
            'Status',
            'Created At',
        ];
    }
}

