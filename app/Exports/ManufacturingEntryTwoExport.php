<?php
namespace App\Exports;

use App\User, App\Purchase;
use App\Models\Delivery, App\Models\Company, App\Models\DeliveryQuantity, App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ManufacturingEntryTwoExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public $search_data;
    
    public function __construct($search_data)
    {
        $this->search_data = $search_data;
    }

    public function collection()
    {
        $search_data= $this->search_data;
        $query = Delivery::select('deliveries.id as delivery_id','deliveries.lot_no','deliveries.entry_date as lot_date','deliveries.dyeing_company','deliveries.knitting_company as item_name','deliveries.tot_gross_quantity as item_quantity_one','deliveries.tot_finish_quantity as item_quantity_two','deliveries.delivery_date')
        ->where('deleted_at', null);
        if (isset($search_data['search_key']) && $search_data['search_key']!='') {
            $query->where(function ($query) use ($search_data) {
                $query->where('lot_no', 'like', '%' . $search_data['search_key'] . '%');
                $query->orWhere('entry_date', 'like', '%' . $search_data['search_key'] . '%');
            });
        }
        if (isset($search_data['dyeing_company']) && $search_data['dyeing_company'] != null) {
            $query->where('deliveries.dyeing_company', $search_data['dyeing_company']);
        }
        $export_data = $query->orderBy('id', 'desc')->get();
        $items=Item::select('id','item_name')->pluck('item_name','id');
        $companies=Company::select('company_id','company_name')->pluck('company_name','company_id');

        $exports_data=collect(new Delivery);
        foreach($export_data as $key=>$export_data_row){
            $delivery_id=$export_data_row->delivery_id;
            if(isset($companies[$export_data_row->dyeing_company])){
                $export_data[$key]->dyeing_company=$companies[$export_data_row->dyeing_company]; 
            }else{
                $export_data[$key]->dyeing_company='';
            }
            unset($export_data[$key]->delivery_id);
            $export_data[$key]->item_name='';
            $export_data[$key]->item_quantity_one='';
            $export_data[$key]->item_quantity_two='';
            $exports_data[]=$export_data_row;
            $manufacturing_quantity_data=DeliveryQuantity::select('delivery_id as lot_no','created_at as lot_date','gross_quantity as dyeing_company','item_id as item_name','quantity_one as item_quantity_one','quantity_two as item_quantity_two')->where('delivery_id',$delivery_id)->get();
            if($manufacturing_quantity_data!=''){
                foreach($manufacturing_quantity_data as $item_key=>$manufacturing_quantity_row){
                    $manufacturing_quantity_data[$item_key]->lot_no='';
                    $manufacturing_quantity_data[$item_key]->lot_date='';
                    $manufacturing_quantity_data[$item_key]->dyeing_company='';
                    if(isset($items[$manufacturing_quantity_row->item_name])){
                        $manufacturing_quantity_data[$item_key]->item_name=$items[$manufacturing_quantity_row->item_name]; 
                    }else{
                        $manufacturing_quantity_data[$item_key]->item_name='';
                    }
                    $manufacturing_quantity_data[$item_key]->delivery_date='';
                    $exports_data[]=$manufacturing_quantity_row;
                }
            }
        }
        return $exports_data;
    }

    public function headings(): array
    {
        return [
            'Lot No.',
            'Lot Date',
            'Name of Party',
            'Item Name',
            'Quantity One',
            'Quantity Two',
            'Delivery Date'
        ];
    }

}
