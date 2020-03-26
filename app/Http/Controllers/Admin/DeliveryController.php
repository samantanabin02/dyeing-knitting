<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Exports\ManufacturingEntryTwoExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Delivery;
use App\Purchase;
use App\Models\Manufacturing;
use App\Models\DeliveryQuantity;
use App\Models\ManufacturingQuantity;
use App\Models\ManufacturingDQuantity;
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
                $query->where('lot_no', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('entry_date', 'like', '%' . $req['search_key'] . '%');
            });
        }
        /*if ($request->has('knitting_company') && $req['knitting_company'] != null) {
            $query->where('deliveries.knitting_company', $req['knitting_company']);
        }*/
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
        $manufacturings=Manufacturing::select('id','serial_no')->pluck('serial_no','id');
        return view('admin.Deliverys.create', compact('items','companies','manufacturings'));
    }
    public function store(Request $request)
    {
        $validator  = $this->validate($request,['entry_date'=>'required']);
        $insert_data  = new Delivery;
        $req=$request->all();
        $insert_data->lot_no=$req['lot_no'];
        $insert_data->entry_date=$req['entry_date'];
        $insert_data->dyeing_company=$req['dyeing_company'];
        $insert_data->serial_no=$req['serial_no'];
        $insert_data->knitting_company=$req['knitting_company'];
        $insert_data->tot_gross_quantity=$req['tot_gross_quantity'];
        $insert_data->tot_finish_quantity=$req['tot_finish_quantity'];
        $insert_data->delivery_date=$req['delivery_date'];
        //echo '<pre>';print_r($insert_data);die;
        if ($insert_data->save()) {

            if(isset($req['item']) && $req['item']!=''){
                $insert_id = $insert_data->id;   
                $item=$req['item'];                
                $quantityone=$req['quantityone'];
                $quantitytwo=$req['quantitytwo'];
                $grossquantity=$req['grossquantity'];
                $finishquantity=$req['finishquantity'];
                $quantity_details_array=array();
                foreach($item as $key=> $item_row){
                    $quantity_details_array[$key]['delivery_id']=$insert_id;
                    $quantity_details_array[$key]['item_id']=$item[$key];
                    $quantity_details_array[$key]['quantity_one']=$quantityone[$key];
                    $quantity_details_array[$key]['quantity_two']=$quantitytwo[$key];
                    $quantity_details_array[$key]['gross_quantity']=$grossquantity[$key];
                    $quantity_details_array[$key]['finish_quantity']=$finishquantity[$key];
                }
                foreach($quantity_details_array as $quantity_details_row){
                   $quantity_data  = new DeliveryQuantity;
                   $quantity_data->create($quantity_details_row);
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
        $manufacturings=Manufacturing::select('id','serial_no')->pluck('serial_no','id');
        $quantity_details=DeliveryQuantity::where('delivery_id',$id)->get();
        return view('admin.Deliverys.edit', compact('data','items','companies','manufacturings','quantity_details','dquantity_details'));
    }
    
    public function update(Request $request, $id)
    {
        $validator  = $this->validate($request,['entry_date'=>'required']);
        $data  = Delivery::find($id);
        $req=$request->all();
        $data->lot_no=$req['lot_no'];
        $data->entry_date=$req['entry_date'];
        $data->dyeing_company=$req['dyeing_company'];
        $data->serial_no=$req['serial_no'];
        $data->knitting_company=$req['knitting_company'];
        $data->tot_gross_quantity=$req['tot_gross_quantity'];
        $data->tot_finish_quantity=$req['tot_finish_quantity'];
        $data->delivery_date=$req['delivery_date'];
        //echo '<pre>';print_r($req);die;
        if ($data->save()) {

            if(isset($req['item']) && $req['item']!=''){
                $insert_id = $id;   
                $item=$req['item'];                
                $quantityone=$req['quantityone'];
                $quantitytwo=$req['quantitytwo'];
                $grossquantity=$req['grossquantity'];
                $finishquantity=$req['finishquantity'];
                $quantity_details_array=array();
                foreach($item as $key=> $item_row){
                    $quantity_details_array[$key]['delivery_id']=$insert_id;
                    $quantity_details_array[$key]['item_id']=$item[$key];
                    $quantity_details_array[$key]['quantity_one']=$quantityone[$key];
                    $quantity_details_array[$key]['quantity_two']=$quantitytwo[$key];
                    $quantity_details_array[$key]['gross_quantity']=$grossquantity[$key];
                    $quantity_details_array[$key]['finish_quantity']=$finishquantity[$key];
                }
                if(count($quantity_details_array)){
                    DeliveryQuantity::where('delivery_id',$id)->delete();
                    foreach($quantity_details_array as $quantity_details_row){
                       $quantity_data  = new DeliveryQuantity;
                       $quantity_data->create($quantity_details_row);
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

    public function get_knitting_company(Request $request)
    {
        $knitting_company_name='';
        $req  = $request->all();
        $manufacturing_id = $req['manufacturing_id'];
        if($manufacturing_id){
            $knitting_company_data=Manufacturing::select('knitting_company')->where('id',$manufacturing_id)->first();
            if($knitting_company_data!=''){
                $knitting_company_id=$knitting_company_data->knitting_company;
                if($knitting_company_id){
                    $knitting_company_name_data=Company::select('company_id','company_name')->where('company_id',$knitting_company_id)->first();
                    if($knitting_company_name_data!=''){
                        $knitting_company_name=$knitting_company_name_data->company_name;
                    }
                }
            }
        }
        return $knitting_company_name;
    }

    public function export_data(Request $request)
    {
        echo phpinfo();die;
        $req  = $request->all();
        $search_data=[];
        $search_data['search_key']=$req['export_search_key'];
        $search_data['dyeing_company']=$req['export_dyeing_company'];
        return Excel::download(new ManufacturingEntryTwoExport($search_data), 'ManufacturingEntryTwo.xlsx');
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
