<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemType;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $req   = $request->all();
        //echo '<pre>';print_r($req);die;
        $query = Item::select('items.*','unit_type.unit_type_id','unit_type.unit_type_name','company.company_name')
        ->join('item_type','item_type.item_type_id','=','items.item_type_id')
        ->leftjoin('unit_type','unit_type.unit_type_id','=','items.unit_type_id')
        ->leftjoin('company','company.company_id','=','items.company_id')
        ->where('deleted_at', null);
        if ($request->has('search_key') && $req['search_key']!='') {
            $query->where(function ($query) use ($req) {
                $query->where('name', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('email', 'like', '%' . $req['search_key'] . '%');
            });
        }
        if ($request->has('item_type') && $req['item_type'] != null) {
            $query->where('items.item_type_id', $req['item_type']);
        }
        $datums = $query->orderBy('id', 'desc')->paginate(10);
        $item_types=ItemType::select('item_type_id','item_type_name')->where('status',1)->get();
        $item_typess=array();
        if($item_types!='' && count($item_types)){
          foreach($item_types as $item_type){
           $item_typess[$item_type->item_type_id]=$item_type->item_type_name;
          }  
        }
        //echo '<pre>';print_r($item_types);die;
        return view('admin.Items.index', ['datums' => $datums,'item_types' => $item_typess]);
    }
    public function create()
    {
        $data = array();
        return view('admin.Items.create', ['data' => $data]);
    }
    public function store(Request $request)
    {
        //$validator    = $this->validator($request->all())->validate();
        $data           = new Item;
        $data->item_no     = substr(str_shuffle(time().'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),0,15);
        $data->item_name     = $request->item_name;
        //echo '<pre>';print_r($data);die;
        if ($data->save()) {
            return redirect()->back()->with('success', 'Item created successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }
    }
    public function show($id)
    {
        $data = Item::find($id);
        return view('admin.Items.show', ['data' => $data]);
    }
    public function edit($id)
    {
        $data = Item::find($id);
        return view('admin.Items.edit', ['data' => $data]);
    }
    public function update(Request $request, $id)
    {
        $validator = $this->validator($request->all())->validate();

        $file_details = $request->file('user_image');
        if ($file_details) {
            $file_original_name = $file_details->getClientOriginalName();
            $file_extension     = pathinfo($file_original_name, PATHINFO_EXTENSION);
            $file_name          = rand() . '.' . $file_extension;
            $uploads_path       = public_path('uploads/user_image/');
            //echo '<pre>';print_r($uploads_path);die;
            $file_details->move($uploads_path, $file_name);
            $user_image = $file_name;
        } else {
            $user_image = $request->pre_user_image;
        }
        $data         = Item::find($id);
        $data->name   = $request->name;
        $data->email  = $request->email;
        $data->image  = $user_image;
        $data->status = $request->status;
        if ($data->save()) {
            return redirect()->back()->with('success', 'Item updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!.');
        }

    }
    public function destroy($id)
    {
        $data = Item::find($id);
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
