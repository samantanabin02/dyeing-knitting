<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Manufacturing;
use App\Models\ManufacturingQuantity;
use App\Models\Item;
use App\Models\UnitType;
use App\Models\Company;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;

class ManufacturingController extends Controller
{
    public function index(Request $request)
    {
        $req   = $request->all();
        $query = Manufacturing::select('manufacturings.*')
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
            $query->where('manufacturings.knitting_company', $req['knitting_company']);
        }
        if ($request->has('dyeing_company') && $req['dyeing_company'] != null) {
            $query->where('manufacturings.dyeing_company', $req['dyeing_company']);
        }
        $datums = $query->orderBy('id', 'desc')->paginate(10);
        $items=Item::select('id','item_name')->pluck('item_name','id');
        $companies=Company::select('company_id','company_name')->pluck('company_name','company_id');
        return view('admin.Manufacturings.index', compact('datums','items','companies'));
    }

    public function create()
    {
        $items=Item::select('id','item_name')->pluck('item_name','id');
        $companies=Company::select('company_id','company_name')->pluck('company_name','company_id');
        return view('admin.Manufacturings.create', compact('items','companies'));
    }
    public function store(Request $request)
    {
        $validator  = $this->validate($request,['entry_date'=>'required']);
        $insert_data  = new Manufacturing;
        $req=$request->all();
        $insert_data->serial_no=$req['serial_no'];
        $insert_data->entry_date=$req['entry_date'];
        $insert_data->knitting_company=$req['knitting_company'];
        $insert_data->dyeing_company=$req['dyeing_company'];
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
                   $quantity_data  = new ManufacturingQuantity;
                   $quantity_data->create($quantity_details_row);
                }
            }
            return redirect()->back()->with('success', 'Manufacturing created successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }
    }
    public function show($id)
    {
        $data = Manufacturing::find($id);
        return view('admin.Manufacturings.show', ['data' => $data]);
    }
    public function edit($id)
    {
        $data = Manufacturing::find($id);
        $items=Item::select('id','item_name')->pluck('item_name','id');
        $companies=Company::select('company_id','company_name')->pluck('company_name','company_id');
        $quantity_details=ManufacturingQuantity::where('item_id',$id)->get();
        return view('admin.Manufacturings.edit', compact('data','items','companies','quantity_details'));
    }
    public function update(Request $request, $id)
    {
        $validator  = $this->validate($request,['entry_date'=>'required']);
        $data  = Manufacturing::find($id);
        $req=$request->all();
        $data->serial_no=$req['serial_no'];
        $data->entry_date=$req['entry_date'];
        $data->knitting_company=$req['knitting_company'];
        $data->dyeing_company=$req['dyeing_company'];
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
                    ManufacturingQuantity::where('item_id',$id)->delete();
                    foreach($quantity_details_array as $quantity_details_row){
                       $quantity_data  = new ManufacturingQuantity;
                       $quantity_data->create($quantity_details_row);
                    }
                }
            }
            return redirect()->back()->with('success', 'Manufacturing updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }

    }
    public function destroy($id)
    {
        $data = Manufacturing::find($id);
        if ($data->update(['deleted_at'=>date('Y-m-d H:i:s')])) {
            return redirect()->route('manufacturings.index')->with('success', 'Successfully deleted.');
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
            return redirect()->route('manufacturings.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('manufacturings.index')->with('error', 'Select record(s) form list for delete.');
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
