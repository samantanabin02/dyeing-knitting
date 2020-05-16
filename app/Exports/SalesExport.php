<?php
namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{

    public function __construct($search_data)
    {
        $this->search_data = $search_data;
    }

    public function collection()
    {
        $data = DB::table('sales')
            ->join('company', 'company.company_id', '=', 'sales.company_id')
            ->select('sales.sales_id', 'sales.invoice_no',
                'sales.invoice_date', 'sales.despatch_doc', 'sales.challan_no', 'sales.despatch_through',
                'company.company_name', 'sales.other_charges', 'sales.sgst_persentage', 'sales.cgst_persentage', 'sales.igst_persentage')
            ->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            'Sales ID',
            'Invoice No',
            'Invoice Date',
            'Despatch Doc',
            'Challan No',
            'Despatch Through',
            'Company ID',
            'Other Charges',
            'Sgst Persentage',
            'Cgst Persentage',
            'Igst Persentage',
        ];
    }
}
