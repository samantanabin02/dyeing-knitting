<?php
namespace App\Exports;

use App\User, App\Purchase;
use App\Models\Manufacturing, App\Models\Company, App\Models\ManufacturingQuantity, App\Models\ManufacturingDQuantity, App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
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
        $search_data= $this->search_data;
        $query = Manufacturing::select('manufacturings.id as manufacturing_id','manufacturings.challan_no','manufacturings.entry_date as challan_date','manufacturings.knitting_company','manufacturings.id as item_name','manufacturings.id as item_quantity','manufacturings.id as item_unit','manufacturings.id as item_amount','manufacturings.id as blank_space','manufacturings.serial_no','manufacturings.entry_date','manufacturings.dyeing_company','manufacturings.id as ditem_name','manufacturings.id as ditem_quantity','manufacturings.id as ditem_unit','manufacturings.id as ditem_amount')
        ->where('deleted_at', null);
        if (isset($search_data['search_key']) && $search_data['search_key']!='') {
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

        $items=Item::select('id','item_name')->pluck('item_name','id');
        $companies=Company::select('company_id','company_name')->pluck('company_name','company_id');

        $exports_data=collect(new Manufacturing);
        $exports_data[]=array(''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'');

        foreach($export_data as $key=>$export_data_row){
            $purchase_id=$export_data_row->challan_no;
            $manufacturing_id=$export_data_row->manufacturing_id;
            $purchase_data=Purchase::select('invoice_challan_no','invoice_date')->where('purchase_id',$purchase_id)->first();
            if($purchase_data!=''){
                $export_data[$key]->challan_no=$purchase_data->invoice_challan_no;
                $export_data[$key]->challan_date=$purchase_data->invoice_date;
            }else{
                $export_data[$key]->challan_no='';
                $export_data[$key]->challan_date='';
            }

            if(isset($companies[$export_data_row->knitting_company])){
                $export_data[$key]->knitting_company=$companies[$export_data_row->knitting_company]; 
            }else{
                $export_data[$key]->knitting_company='';
            }
            unset($export_data[$key]->manufacturing_id);
            $export_data[$key]->item_name='';
            $export_data[$key]->item_quantity='';
            $export_data[$key]->item_unit='';
            $export_data[$key]->item_amount='';
            $export_data[$key]->blank_space='';

            $export_data[$key]->ditem_name='';
            $export_data[$key]->ditem_quantity='';
            $export_data[$key]->ditem_unit='';
            $export_data[$key]->ditem_amount='';
            

            if(isset($companies[$export_data_row->dyeing_company])){
                $export_data[$key]->dyeing_company=$companies[$export_data_row->dyeing_company]; 
            }else{
                $export_data[$key]->dyeing_company='';
            }

            $exports_data[]=$export_data_row;
            
            $manufacturing_quantity_data=ManufacturingQuantity::select('manufacturing_id as challan_no','created_at as challan_date','amount as knitting_company','item_id as item_name','quantity as item_quantity','unit as item_unit','amount as item_amount')->where('manufacturing_id',$manufacturing_id)->get();
            if($manufacturing_quantity_data!=''){
                foreach($manufacturing_quantity_data as $item_key=>$manufacturing_quantity_row){
                    $manufacturing_quantity_data[$item_key]->challan_no='';
                    $manufacturing_quantity_data[$item_key]->challan_date='';
                    $manufacturing_quantity_data[$item_key]->knitting_company='';
                    if(isset($items[$manufacturing_quantity_row->item_name])){
                        $manufacturing_quantity_data[$item_key]->item_name=$items[$manufacturing_quantity_row->item_name]; 
                    }else{
                        $manufacturing_quantity_data[$item_key]->item_name='';
                    }
                    $exports_data[]=$manufacturing_quantity_row;
                }
            }

            $manufacturing_dquantity_data=ManufacturingDQuantity::select('id as challan_no','id as challan_date','id as knitting_company','id as item_name','id as item_quantity','id as item_unit','id as item_amount','id as blank_space','id as serial_no','id as entry_date','id as dyeing_company','item_id as ditem_name','quantity as ditem_quantity','unit as ditem_unit','amount as ditem_amount')->where('manufacturing_id',$manufacturing_id)->get();
            if($manufacturing_dquantity_data!=''){
                foreach($manufacturing_dquantity_data as $ditem_key=>$manufacturing_quantity_row){
                    $manufacturing_dquantity_data[$ditem_key]->challan_no='';
                    $manufacturing_dquantity_data[$ditem_key]->challan_date='';
                    $manufacturing_dquantity_data[$ditem_key]->knitting_company='';

                    $manufacturing_dquantity_data[$ditem_key]->item_name='';
                    $manufacturing_dquantity_data[$ditem_key]->item_quantity='';
                    $manufacturing_dquantity_data[$ditem_key]->item_unit='';
                    $manufacturing_dquantity_data[$ditem_key]->item_amount='';
                    $manufacturing_dquantity_data[$ditem_key]->blank_space='';

                    $manufacturing_dquantity_data[$ditem_key]->serial_no='';
                    $manufacturing_dquantity_data[$ditem_key]->entry_date='';
                    $manufacturing_dquantity_data[$ditem_key]->dyeing_company='';

                    if(isset($items[$manufacturing_quantity_row->ditem_name])){
                        $manufacturing_dquantity_data[$ditem_key]->ditem_name=$items[$manufacturing_quantity_row->ditem_name]; 
                    }else{
                        $manufacturing_dquantity_data[$ditem_key]->ditem_name='';
                    }
                    $exports_data[]=$manufacturing_quantity_row;
                }
            }
            $exports_data[]=array(''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'');
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
            'OUTWARD',
            '',
            '',
            '',
            '',
            '',
            ''
        ],[
            'Invoice No.',
            'Invoice Date',
            'Knitting Company',
            'Item Name',
            'Quantity',
            'Unit',
            'Amount',
            '',
            'Serial No',
            'Entry Date',
            'Dyeing Company',
            'Item Name',
            'Quantity',
            'Unit',
            'Amount',
        ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
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
