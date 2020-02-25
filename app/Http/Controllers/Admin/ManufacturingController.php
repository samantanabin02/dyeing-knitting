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
        //echo '<pre>';print_r($req);die;
        $query = Manufacturing::select('manufacturings.*')
       /* ->join('item_type','item_type.item_type_id','=','items.item_type_id')*/
        ->where('deleted_at', null);
        if ($request->has('search_key') && $req['search_key']!='') {
            $query->where(function ($query) use ($req) {
                $query->where('item_name', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('item_no', 'like', '%' . $req['search_key'] . '%');
            });
        }
        /*if ($request->has('item_type') && $req['item_type'] != null) {
            $query->where('items.item_type_id', $req['item_type']);
        }*/
        $datums = $query->orderBy('id', 'desc')->paginate(10);
        $items=Item::select('id','item_name')->pluck('item_name','id');
        //echo '<pre>';print_r($item_types);die;
        return view('admin.Manufacturings.index', compact('datums','items'));
    }

    public function create()
    {
        $items=Item::select('id','item_name')->pluck('item_name','id');
        return view('admin.Manufacturings.create', compact('items'));
    }
    public function store(Request $request)
    {
        $validator  = $this->validate($request,['item_id'=>'required']);
        $insert_data  = new Manufacturing;
        $req=$request->all();
        $insert_data->serial_no=$req['serial_no'];
        $insert_data->entry_date=$req['entry_date'];
        $insert_data->item_id=$req['item_id'];
        $insert_data->gst_applicable=$req['gst_applicable'];
        $insert_data->gst_no=$req['gst_no'];
        $insert_data->gst_percentage=$req['gst_percentage'];
        $insert_data->supply_type=$req['supply_type'];
        //echo '<pre>';print_r($insert_data);die;
        if ($insert_data->save()) {
            if(isset($req['quantity']) && $req['quantity']!=''){
                $insert_id = $insert_data->id;                   
                $quantity=$req['quantity'];
                $unit=$req['unit'];
                $rate=$req['rate'];
                $amount=$req['amount'];
                $quantity_details_array=array();
                foreach($quantity as $key=> $quantity_row){
                    $quantity_details_array[$key]['item_id']=$insert_id;
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
        $quantity_details=ManufacturingQuantity::where('item_id',$id)->get();
        return view('admin.Manufacturings.edit', compact('data','items','quantity_details'));
    }
    public function update(Request $request, $id)
    {
        $validator  = $this->validate($request,['item_id'=>'required']);
        $data  = Manufacturing::find($id);
        $req=$request->all();
        $data->serial_no=$req['serial_no'];
        $data->entry_date=$req['entry_date'];
        $data->item_id=$req['item_id'];
        $data->gst_applicable=$req['gst_applicable'];
        $data->gst_no=$req['gst_no'];
        $data->gst_percentage=$req['gst_percentage'];
        $data->supply_type=$req['supply_type'];
        //echo '<pre>';print_r($req);die;
        if ($data->save()) {
            if(isset($req['quantity']) && $req['quantity']!=''){
                $insert_id = $id;                   
                $quantity=$req['quantity'];
                $unit=$req['unit'];
                $rate=$req['rate'];
                $amount=$req['amount'];
                $quantity_details_array=array();
                foreach($quantity as $key=> $quantity_row){
                    $quantity_details_array[$key]['item_id']=$insert_id;
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
            return redirect()->route('items.index')->with('success', 'Successfully deleted.');
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
            return redirect()->route('items.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('items.index')->with('error', 'Select record(s) form list for delete.');
        }
    }
    protected function validator(array $data)
    {

        return Validator::make(
            $data,
            [
                'name'   => 'required',
                'status' => 'required',
            ],
            [
                'name.required'   => 'Enter name.',
                'status.required' => 'Select status.',
            ]
        );
    }
}
