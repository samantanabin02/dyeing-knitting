<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MainExport implements FromArray, WithMultipleSheets
{
    protected $sheets;

    public function __construct(array $sheets, $search_data)
    {
        $this->sheets      = $sheets;
        $this->search_data = $search_data;
    }

    function array(): array
    {
        return $this->sheets;
    }

    public function sheets(): array
    {
        $sheets = [
            new SalesExport('sale',$this->search_data),
            new SalesItemExport('saleItem',$this->search_data),
        ];

        return $sheets;
    }
}
