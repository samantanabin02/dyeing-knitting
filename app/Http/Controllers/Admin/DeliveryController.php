<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Purchase;
use App\Models\DeliveryQuantity;
use App\Models\DeliveryDQuantity;
use App\Models\Item;
use App\Models\UnitType;
use App\Models\Company;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $req   = $request->all();
        $query = Delivery::select('deliveries.*')
        ->where('deleted_at', null);
        if ($request->has('search_key') && $req['search_key']!='') {
            $query->where(function ($query) use ($req) {
                $query->where('serial_no', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('entry_date', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('wastage_quantity', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('wastage_amount', 'like', '%' . $req['search_key'] . '%');
            });
        }
        if ($request->has('knitting_company') && $req['knitting_company'] != null) {
            $query->where('deliveries.knitting_company', $req['knitting_company']);
        }
        if ($request->has('dyeing_company') && $req['dyeing_company'] != null) {
            $query->where('deliveries.dyeing_company', $req['dyeing_company']);
        }
        $datums = $query->orderBy('id', 'desc')->paginate(10);
        $items=Item::select('id','item_name')->pluck('item_name','id');
        $companies=Company::select('company_id','company_name')->pluck('company_name','company_id');
        return view('admin.Deliverys.index', compact('datums','items','companies'));
    }

    public function create()
    {
        $items=Item::select('id','item_name')->pluck('item_name','id');
        $companies=Company::select('company_id','company_name')->pluck('company_name','company_id');
        $purchases=Purchase::select('purchase_id','invoice_challan_no')->pluck('invoice_challan_no','purchase_id');
        return view('admin.Deliverys.create', compact('items','companies','purchases'));
    }
    public function store(Request $request)
    {
        $validator  = $this->validate($request,['entry_date'=>'required']);
        $insert_data  = new Delivery;
        $req=$request->all();
        $insert_data->serial_no=$req['serial_no'];
        $insert_data->entry_date=$req['entry_date'];
        $insert_data->knitting_company=$req['knitting_company'];
        $insert_data->challan_no=$req['challan_no'];
        $insert_data->tot_knit_quan=$req['tot_knit_quan'];
        $insert_data->tot_knit_amount=$req['tot_knit_amount'];
        $insert_data->dyeing_company=$req['dyeing_company'];
        $insert_data->tot_dyeing_quan=$req['tot_dyeing_quan'];
        $insert_data->tot_dyeing_amount=$req['tot_dyeing_amount'];
        $insert_data->wastage_quantity=$req['wastage_quantity'];
        $insert_data->wastage_amount=$req['wastage_amount'];
        //echo '<pre>';print_r($insert_data);die;
        if ($insert_data->save()) {
            if(isset($req['quantity']) && $req['quantity']!=''){
                $insert_id = $insert_data->id;   
                $item=$req['item'];                
                $quantity=$req['quantity'];
                $unit=$req['unit'];
                $rate=$req['rate'];
                $amount=$req['amount'];
                $quantity_details_array=array();
                foreach($quantity as $key=> $quantity_row){
                    $quantity_details_array[$key]['manufacturing_id']=$insert_id;
                    $quantity_details_array[$key]['item_id']=$item[$key];
                    $quantity_details_array[$key]['quantity']=$quantity[$key];
                    $quantity_details_array[$key]['unit']=$unit[$key];
                    $quantity_details_array[$key]['rate']=$rate[$key];
                    $quantity_details_array[$key]['amount']=$amount[$key];
                }
                foreach($quantity_details_array as $quantity_details_row){
                   $quantity_data  = new DeliveryQuantity;
                   $quantity_data->create($quantity_details_row);
                }
            }

             if(isset($req['dquantity']) && $req['dquantity']!=''){
                $dinsert_id = $insert_data->id;   
                $ditem=$req['ditem'];                
                $dquantity=$req['dquantity'];
                $dunit=$req['dunit'];
                $drate=$req['drate'];
                $damount=$req['damount'];
                $dquantity_details_array=array();
                foreach($dquantity as $dkey=> $dquantity_row){
                    $dquantity_details_array[$dkey]['manufacturing_id']=$dinsert_id;
                    $dquantity_details_array[$dkey]['item_id']=$ditem[$dkey];
                    $dquantity_details_array[$dkey]['quantity']=$dquantity[$dkey];
                    $dquantity_details_array[$dkey]['unit']=$dunit[$dkey];
                    $dquantity_details_array[$dkey]['rate']=$drate[$dkey];
                    $dquantity_details_array[$dkey]['amount']=$damount[$dkey];
                }
                foreach($dquantity_details_array as $dquantity_details_row){
                   $dquantity_data  = new DeliveryDQuantity;
                   $dquantity_data->create($dquantity_details_row);
                }
            }
            return redirect()->back()->with('success', 'Delivery created successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }
    }
    public function show($id)
    {
        $data = Delivery::find($id);
        return view('admin.Deliverys.show', ['data' => $data]);
    }
    public function edit($id)
    {
        $data = Delivery::find($id);
        $items=Item::select('id','item_name')->pluck('item_name','id');
        $companies=Company::select('company_id','company_name')->pluck('company_name','company_id');
        $purchases=Purchase::select('purchase_id','invoice_challan_no')->pluck('invoice_challan_no','purchase_id');
        $quantity_details=DeliveryQuantity::where('manufacturing_id',$id)->get();
        $dquantity_details=DeliveryDQuantity::where('manufacturing_id',$id)->get();
        return view('admin.Deliverys.edit', compact('data','items','companies','purchases','quantity_details','dquantity_details'));
    }
    
    public function update(Request $request, $id)
    {
        $validator  = $this->validate($request,['entry_date'=>'required']);
        $data  = Delivery::find($id);
        $req=$request->all();
        $data->serial_no=$req['serial_no'];
        $data->entry_date=$req['entry_date'];
        $data->knitting_company=$req['knitting_company'];
        $data->challan_no=$req['challan_no'];
        $data->tot_knit_quan=$req['tot_knit_quan'];
        $data->tot_knit_amount=$req['tot_knit_amount'];
        $data->dyeing_company=$req['dyeing_company'];
        $data->tot_dyeing_quan=$req['tot_dyeing_quan'];
        $data->tot_dyeing_amount=$req['tot_dyeing_amount'];
        $data->wastage_quantity=$req['wastage_quantity'];
        $data->wastage_amount=$req['wastage_amount'];
        //echo '<pre>';print_r($req);die;
        if ($data->save()) {
            if(isset($req['quantity']) && $req['quantity']!=''){
                $insert_id = $id;
                $item=$req['item'];                   
                $quantity=$req['quantity'];
                $unit=$req['unit'];
                $rate=$req['rate'];
                $amount=$req['amount'];
                $quantity_details_array=array();
                foreach($quantity as $key=> $quantity_row){
                    $quantity_details_array[$key]['manufacturing_id']=$insert_id;
                    $quantity_details_array[$key]['item_id']=$item[$key];
                    $quantity_details_array[$key]['quantity']=$quantity[$key];
                    $quantity_details_array[$key]['unit']=$unit[$key];
                    $quantity_details_array[$key]['rate']=$rate[$key];
                    $quantity_details_array[$key]['amount']=$amount[$key];
                }
                if(count($quantity_details_array)){
                    DeliveryQuantity::where('manufacturing_id',$id)->delete();
                    foreach($quantity_details_array as $quantity_details_row){
                       $quantity_data  = new DeliveryQuantity;
                       $quantity_data->create($quantity_details_row);
                    }
                }
            }


            if(isset($req['dquantity']) && $req['dquantity']!=''){
                $dinsert_id = $id;   
                $ditem=$req['ditem'];                
                $dquantity=$req['dquantity'];
                $dunit=$req['dunit'];
                $drate=$req['drate'];
                $damount=$req['damount'];
                $dquantity_details_array=array();
                foreach($dquantity as $dkey=> $dquantity_row){
                    $dquantity_details_array[$dkey]['manufacturing_id']=$dinsert_id;
                    $dquantity_details_array[$dkey]['item_id']=$ditem[$dkey];
                    $dquantity_details_array[$dkey]['quantity']=$dquantity[$dkey];
                    $dquantity_details_array[$dkey]['unit']=$dunit[$dkey];
                    $dquantity_details_array[$dkey]['rate']=$drate[$dkey];
                    $dquantity_details_array[$dkey]['amount']=$damount[$dkey];
                }
                DeliveryDQuantity::where('manufacturing_id',$id)->delete();
                if(count($dquantity_details_array)){
                    foreach($dquantity_details_array as $dquantity_details_row){
                       $dquantity_data  = new DeliveryDQuantity;
                       $dquantity_data->create($dquantity_details_row);
                    }
                }
            }


            return redirect()->back()->with('success', 'Delivery updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }

    }
    public function destroy($id)
    {
        $data = Delivery::find($id);
        if ($data->update(['deleted_at'=>date('Y-m-d H:i:s')])) {
            return redirect()->route('deliveries.index')->with('success', 'Successfully deleted.');
        }
    }
    public function multi_destroy(Request $request)
    {
        $req           = $request->all();
        $deletable_ids = $req['deletable_ids'];
        //dd($deletable_ids);
        $deletable_ids = explode(',', $deletable_ids);
        if (count($deletable_ids) > 0) {
            foreach ($deletable_ids as $deletable_id) {
                $this->destroy($deletable_id);
            }
            return redirect()->route('deliveries.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('deliveries.index')->with('error', 'Select record(s) form list for delete.');
        }
    }
    protected function validator(array $data)
    {

        return Validator::make(
            $data,
            [
                'entry_date'   => 'required',
                'status' => 'required',
            ],
            [
                'entry_date.required'   => 'Please choose entry date.',
                'status.required' => 'Select status.',
            ]
        );
    }
}