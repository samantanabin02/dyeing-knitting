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

class ManufacturingEntryOneExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{

    public $search_data;

    public function __construct($search_data)
    {
        $this->search_data = $search_data;
    }

    public function collection()
    {
        $search_data = $this->search_data;
        $query       = Manufacturing::select('manufacturings.id as manufacturing_id', 'manufacturings.challan_no', 'manufacturings.entry_date as challan_date','manufacturings.knitting_company as purchaser_name','manufacturings.id as pitem_name', 'manufacturings.id as pitem_unit', 'manufacturings.id as pitem_quantity','manufacturings.knitting_company', 'manufacturings.id as item_name', 'manufacturings.id as item_quantity', 'manufacturings.id as item_unit', 'manufacturings.id as blank_space', 'manufacturings.serial_no', 'manufacturings.entry_date', 'manufacturings.dyeing_company', 'manufacturings.id as ditem_name', 'manufacturings.id as ditem_quantity', 'manufacturings.id as ditem_unit', 'manufacturings.id as ditem_amount')
            ->where('deleted_at', null);
        if (isset($search_data['search_key']) && $search_data['search_key'] != '') {
            $query->where(function ($query) use ($search_data) {
                $query->where('serial_no', 'like', '%' . $search_data['search_key'] . '%');
                $query->orWhere('entry_date', 'like', '%' . $search_data['search_key'] . '%');
                $query->orWhere('wastage_quantity', 'like', '%' . $search_data['search_key'] . '%');
                $query->orWhere('wastage_amount', 'like', '%' . $search_data['search_key'] . '%');
            });
        }
        if (isset($search_data['knitting_company']) && $search_data['knitting_company'] != null) {
            $query->where('manufacturings.knitting_company', $search_data['knitting_company']);
        }
        if (isset($search_data['dyeing_company']) && $search_data['dyeing_company'] != null) {
            $query->where('manufacturings.dyeing_company', $search_data['dyeing_company']);
        }
        $export_data = $query->orderBy('id', 'desc')->get();

        $items     = Item::select('id', 'item_name')->pluck('item_name', 'id');
        $units     = UnitType::select('unit_type_id','unit_type_name')->pluck('unit_type_name', 'unit_type_id');
        $companies = Company::select('company_id', 'company_name')->pluck('company_name', 'company_id');

        $exports_data   = collect(new Manufacturing);
        $exports_data[] = array(''=>'');

        foreach ($export_data as $key => $export_data_row) {
            $purchase_id      = $export_data_row->challan_no;
            $manufacturing_id = $export_data_row->manufacturing_id;
            $purchase_data    = Purchase::select('purchase_id','invoice_challan_no', 'invoice_date','purchase_company_id')->where('purchase_id', $purchase_id)->first();
            //echo '<pre>';print_r($purchase_data->purchase_company_id);die;
            //echo '<pre>';print_r($purchase_data->purchase_company_id);die;
            if ($purchase_data != '') {
                $export_data[$key]->challan_no   = $purchase_data->invoice_challan_no;
                $export_data[$key]->challan_date = $purchase_data->invoice_date;
            } else {
                $export_data[$key]->challan_no   = '';
                $export_data[$key]->challan_date = '';
            }
            if (isset($companies[$purchase_data->purchase_company_id])) {
                $export_data[$key]->purchaser_name = $companies[$purchase_data->purchase_company_id];
            } else {
                $export_data[$key]->purchaser_name = '';
            }
            $export_data[$key]->pitem_name = '';
            $export_data[$key]->pitem_unit = '';
            $export_data[$key]->pitem_quantity = '';
            if (isset($companies[$export_data_row->knitting_company])) {
                $export_data[$key]->knitting_company = $companies[$export_data_row->knitting_company];
            } else {
                $export_data[$key]->knitting_company = '';
            }
            unset($export_data[$key]->manufacturing_id);
            $export_data[$key]->item_name     = '';
            $export_data[$key]->item_quantity = '';
            $export_data[$key]->item_unit     = '';
            $export_data[$key]->blank_space   = '';
            $export_data[$key]->ditem_name     = '';
            $export_data[$key]->ditem_quantity = '';
            $export_data[$key]->ditem_unit     = '';
            $export_data[$key]->ditem_amount   = '';

            if (isset($companies[$export_data_row->dyeing_company])) {
                $export_data[$key]->dyeing_company = $companies[$export_data_row->dyeing_company];
            } else {
                $export_data[$key]->dyeing_company = '';
            }

            $exports_data[] = $export_data_row;
            $tot_pq=0;
            $purchase_quantity_data = PurchaseItemQuantity::select('purchase_id as challan_no', 'purchase_id as challan_date', 'purchase_id as purchaser_name', 'item_id as pitem_name', 'quantity as pitem_quantity', 'unit_id as pitem_unit')->where('purchase_id', $purchase_data->purchase_id)->get();
            //echo '<pre>';print_r($purchase_quantity_data);die;
            if ($purchase_quantity_data != '') {
                foreach ($purchase_quantity_data as $pitem_key => $purchase_quantity_row) {
                    if($purchase_quantity_row->pitem_quantity!=''){
                      $tot_pq=$tot_pq+$purchase_quantity_row->pitem_quantity;
                    }
                    $purchase_quantity_data[$pitem_key]->challan_no       = '';
                    $purchase_quantity_data[$pitem_key]->challan_date     = '';
                    $purchase_quantity_data[$pitem_key]->purchaser_name = '';
                    if (isset($items[$purchase_quantity_row->pitem_name])) {
                        $purchase_quantity_data[$pitem_key]->pitem_name = $items[$purchase_quantity_row->pitem_name];
                    }else {
                        $purchase_quantity_data[$pitem_key]->pitem_name = '';
                    }
                    if (isset($units[$purchase_quantity_row->pitem_unit])) {
                        $purchase_quantity_data[$pitem_key]->pitem_unit = $units[$purchase_quantity_row->pitem_unit];
                    }else {
                        $purchase_quantity_data[$pitem_key]->pitem_unit = '';
                    }
                    //$purchase_quantity_data[$pitem_key]->pitem_unit = '';
                    $purchase_quantity_data[$pitem_key]->knitting_company = '';
                    $exports_data[] = $purchase_quantity_row;
                }
            }
            $tot_mq=0;
            $manufacturing_quantity_data = ManufacturingQuantity::select('manufacturing_id as challan_no', 'created_at as challan_date' , 'amount as purchaser_name' , 'amount as pitem_name' , 'amount as pitem_unit' , 'amount as pitem_quantity' , 'amount as knitting_company' , 'item_id as item_name', 'quantity as item_quantity', 'unit as item_unit')->where('manufacturing_id', $manufacturing_id)->get();
            if ($manufacturing_quantity_data != '') {
                foreach ($manufacturing_quantity_data as $item_key => $manufacturing_quantity_row) {
                    if($manufacturing_quantity_row->item_quantity!=''){
                      $tot_mq=$tot_mq+$manufacturing_quantity_row->item_quantity;
                    }
                    $manufacturing_quantity_data[$item_key]->challan_no       = '';
                    $manufacturing_quantity_data[$item_key]->challan_date     = '';
                    $manufacturing_quantity_data[$item_key]->purchaser_name = '';
                    $manufacturing_quantity_data[$item_key]->pitem_name = '';
                    $manufacturing_quantity_data[$item_key]->pitem_unit = '';
                    $manufacturing_quantity_data[$item_key]->pitem_quantity = '';
                    $manufacturing_quantity_data[$item_key]->knitting_company = '';
                    if (isset($items[$manufacturing_quantity_row->item_name])) {
                        $manufacturing_quantity_data[$item_key]->item_name = $items[$manufacturing_quantity_row->item_name];
                    } else {
                        $manufacturing_quantity_data[$item_key]->item_name = '';
                    }
                    $exports_data[] = $manufacturing_quantity_row;
                }
            }
            $tot_mdq=0;
            $manufacturing_dquantity_data = ManufacturingDQuantity::select('id as challan_no', 'id as challan_date', 'id as purchaser_name', 'id as pitem_name' , 'id as pitem_unit' , 'id as pitem_quantity' , 'id as knitting_company', 'id as item_name', 'id as item_quantity', 'id as item_unit' , 'id as blank_space', 'id as serial_no', 'id as entry_date', 'id as dyeing_company', 'item_id as ditem_name', 'quantity as ditem_quantity', 'unit as ditem_unit', 'amount as bal_quantity')->where('manufacturing_id', $manufacturing_id)->get();
            if ($manufacturing_dquantity_data != '') {
                $manufacturing_dquantity_count=count($manufacturing_dquantity_data);
                $mdc=0;
                foreach ($manufacturing_dquantity_data as $ditem_key => $manufacturing_dquantity_row) {
                    $mdc++;
                    if($manufacturing_dquantity_row->ditem_quantity!=''){
                      $tot_mdq=$tot_mdq+$manufacturing_dquantity_row->ditem_quantity;
                    }
                    $manufacturing_dquantity_data[$ditem_key]->challan_no   = '';
                    $manufacturing_dquantity_data[$ditem_key]->challan_date = '';
                    $manufacturing_dquantity_data[$ditem_key]->purchaser_name= '';
                    $manufacturing_dquantity_data[$ditem_key]->pitem_name = '';
                    $manufacturing_dquantity_data[$ditem_key]->pitem_unit = '';
                    $manufacturing_dquantity_data[$ditem_key]->pitem_quantity = '';
                    $manufacturing_dquantity_data[$ditem_key]->knitting_company = '';
                    $manufacturing_dquantity_data[$ditem_key]->item_name     = '';
                    $manufacturing_dquantity_data[$ditem_key]->item_quantity = '';
                    $manufacturing_dquantity_data[$ditem_key]->item_unit     = '';
                    $manufacturing_dquantity_data[$ditem_key]->blank_space   = '';
                    $manufacturing_dquantity_data[$ditem_key]->serial_no     = '';
                    $manufacturing_dquantity_data[$ditem_key]->entry_date    = '';
                    $manufacturing_dquantity_data[$ditem_key]->dyeing_company= '';

                    if(isset($items[$manufacturing_dquantity_row->ditem_name])) {
                    $manufacturing_dquantity_data[$ditem_key]->ditem_name = $items[$manufacturing_dquantity_row->ditem_name];
                    }else{
                    $manufacturing_dquantity_data[$ditem_key]->ditem_name = '';
                    }
                    if($mdc==$manufacturing_dquantity_count){
                    $manufacturing_dquantity_data[$ditem_key]->bal_quantity = $tot_pq-$tot_mdq; 
                    }else{
                    $manufacturing_dquantity_data[$ditem_key]->bal_quantity = ''; 
                    }
                    $exports_data[] = $manufacturing_dquantity_row;
                }
            }
            $exports_data[] = array(''=>'');
        }
        return $exports_data;
    }

    public function headings(): array
    {
        return [
            [
                'INWARD',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'OUTWARD',
                '',
                '',
                '',
                '',
                '',
                '',
            ], [
                'Invoice No.',
                'Invoice Date',
                'Name Of Purchaser',
                'Item Name',
                'Quantity',
                'Unit',
                'Knitting Company',
                'Item Name',
                'Quantity',
                'Unit',
                '',
                'Serial No',
                'Entry Date',
                'Dyeing Company',
                'Item Name',
                'Quantity',
                'Unit',
                'Balance Quantity',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Black');
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                $cellRange1 = 'A2:W2'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange1)->getFont()->setName('Arial');
                $event->sheet->getDelegate()->getStyle($cellRange1)->getFont()->setSize(12);
            },
        ];
    }

}
