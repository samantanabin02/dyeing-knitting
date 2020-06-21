<?php
namespace App\Exports;

use App\Models\Company;
use App\Models\Item;
use App\Models\UnitType;
use App\Models\Manufacturing;
use App\Models\ManufacturingDQuantity;
use App\Models\ManufacturingQuantity;
use App\Purchase;
use App\PurchaseItemQuantity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchaseExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public $search_data;

    public function __construct($search_data)
    {
        $this->search_data = $search_data;
    }

    public function collection()
    {
        $search_data = $this->search_data;
        $query = Purchase::select('purchase_id', 'invoice_challan_no as invoice_no','invoice_date', 'purchase_company_id as purchaser_name', 'purchase_id as pitem_name', 'purchase_id as pitem_quantity', 'purchase_id as pitem_rate', 'purchase_id as pitem_amount','purchase_id as blank_space','material_transfer_company_id as transfer_company','manufacturings.tot_knit_quan','manufacturings.serial_no','manufacturings.tot_dyeing_quan')
            ->leftjoin('manufacturings','manufacturings.challan_no','=','purchase.purchase_id')
            ->where('purchase.deleted_at', 1);
        if(isset($search_data['search_key']) && $search_data['search_key'] != '') {
            $query->where(function ($query) use ($search_data) {
                $query->where('invoice_challan_no', 'like', '%' . $search_data['search_key'] . '%');
                $query->orWhere('invoice_date', 'like', '%' . $search_data['search_key'] . '%');
                $query->orWhere('sgst_persentage', 'like', '%' . $search_data['search_key'] . '%');
                $query->orWhere('cgst_persentage', 'like', '%' . $search_data['search_key'] . '%');
            });
        }

        if(isset($search_data['purchase_company']) && $search_data['purchase_company'] != null) {
            $query->where('purchase.purchase_company_id', $search_data['purchase_company']);
        }

        if(isset($search_data['transfer_company']) && $search_data['transfer_company'] != null) {
            $query->where('purchase.material_transfer_company_id', $search_data['transfer_company']);
        }

        $export_data = $query->orderBy('purchase.purchase_id', 'desc')->get();
        //echo '<pre>';print_r($export_data);die;
        $items     = Item::select('id', 'item_name')->pluck('item_name', 'id');
        $units     = UnitType::select('unit_type_id','unit_type_name')->pluck('unit_type_name', 'unit_type_id');
        $companies = Company::select('company_id', 'company_name')->pluck('company_name', 'company_id');
        $exports_data   = collect(new Purchase);
        $exports_data[] = array(''=>'');
        foreach ($export_data as $key => $export_data_row) {
            $purchase_id      = $export_data_row->purchase_id ;
            unset($export_data[$key]->purchase_id);
            //$export_data[$key]->invoice_no   = $export_data_row->invoice_challan_no;
            //$export_data[$key]->invoice_date   = $export_data_row->invoice_date;
            if(isset($companies[$export_data_row->purchaser_name])){
              $export_data[$key]->purchaser_name = $companies[$export_data_row->purchaser_name];
            }else{
              $export_data[$key]->purchaser_name = '';
            }
            //unset($export_data[$key]->manufacturing_id);
            $export_data[$key]->pitem_name     = '';
            $export_data[$key]->pitem_quantity = '';
            $export_data[$key]->pitem_rate     = '';
            $export_data[$key]->pitem_amount = '';
            $export_data[$key]->blank_space   = '';
            if(isset($companies[$export_data_row->transfer_company])){
              $export_data[$key]->transfer_company = $companies[$export_data_row->transfer_company];
            }else{
              $export_data[$key]->transfer_company = '';
            }
            $tot_knit_quan=$export_data_row->tot_knit_quan;
            $tot_dyeing_quan=$export_data_row->tot_dyeing_quan;
            $balance_quantity=0;
            if (is_numeric($tot_knit_quan) && is_numeric($tot_dyeing_quan)) {
            $balance_quantity=$tot_knit_quan-$tot_dyeing_quan;
            }
            $export_data[$key]->balance_quantity = $balance_quantity;
            $exports_data[] = $export_data_row;
            $purchase_quantity_data = PurchaseItemQuantity::select('purchase_id as invoice_no', 'purchase_id as invoice_date', 'purchase_id as purchaser_name', 'item_id as pitem_name', 'quantity as pitem_quantity', 'rate as pitem_rate', 'amount as pitem_amount')->where('purchase_id', $purchase_id)->get();
            //echo '<pre>';print_r($purchase_quantity_data);die;
            if($purchase_quantity_data != ''){
                foreach ($purchase_quantity_data as $pitem_key => $purchase_quantity_row) {
                    $purchase_quantity_data[$pitem_key]->invoice_no       = '';
                    $purchase_quantity_data[$pitem_key]->invoice_date     = '';
                    $purchase_quantity_data[$pitem_key]->purchaser_name = '';
                    if(isset($items[$purchase_quantity_row->pitem_name])){
                        $purchase_quantity_data[$pitem_key]->pitem_name = $items[$purchase_quantity_row->pitem_name];
                    }else{
                        $purchase_quantity_data[$pitem_key]->pitem_name = '';
                    }
                    $exports_data[] = $purchase_quantity_row;
                }
            }
            $exports_data[] = array(''=>'');
        }
        return $exports_data;
    }

    public function headings(): array
    {
        return [
                'Invoice No.',
                'Invoice Date',
                'Name Of Purchaser',
                'Item Name',
                'Quantity',
                'Rate',
                'Amount',
                '',
                'Knitting Company',
                'Quantity',
                'Challan No',
                'Quantity',
                'Balance Quantity',
            ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial');
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
            },
        ];
    }

}
