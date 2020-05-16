<?php
namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesItemExport implements FromCollection, WithHeadings
{

    public function __construct($search_data)
    {
        $this->search_data = $search_data;
    }

    public function collection()
    {
        return $data = DB::table('sales_item_quantity')
            ->join('deliveries', 'deliveries.id', '=', 'sales_item_quantity.lot_no_id')
            ->join('items', 'items.id', '=', 'sales_item_quantity.item_id')
            ->join('unit_type', 'unit_type.unit_type_id', '=', 'sales_item_quantity.unit_id')
            ->select('sales_item_quantity.sales_id', 'deliveries.lot_no', 'items.item_name', 'sales_item_quantity.hsn_code', 'sales_item_quantity.quantity', 'sales_item_quantity.rate', 'unit_type.unit_type_name', 'sales_item_quantity.disc_persentage', 'sales_item_quantity.amount')
            ->get();
    }
    public function headings(): array
    {
        return [
            'Sales ID',
            'Lot No',
            'Item',
            'HSN Code',
            'Quantity',
            'Rate',
            'Unit',
            'Discount',
            'Amount',
        ];
    }
}
