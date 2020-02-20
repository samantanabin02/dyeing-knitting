<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\UnitType;
use App\Models\Company;
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
        $query = Item::select('items.*')
        ->join('item_type','item_type.item_type_id','=','items.item_type_id')
        ->where('deleted_at', null);
        if ($request->has('search_key') && $req['search_key']!='') {
            $query->where(function ($query) use ($req) {
                $query->where('item_name', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('item_no', 'like', '%' . $req['search_key'] . '%');
            });
        }
        if ($request->has('item_type') && $req['item_type'] != null) {
            $query->where('items.item_type_id', $req['item_type']);
        }
        $datums = $query->orderBy('id', 'desc')->paginate(10);
        $item_types=ItemType::select('item_type_id','item_type_name')->where('status',1)->pluck('item_type_name','item_type_id');
        //echo '<pre>';print_r($item_types);die;
        return view('admin.Items.index', ['datums' => $datums,'item_types' => $item_types]);
    }

    public function create()
    {
        $item_types=ItemType::select('item_type_id','item_type_name')->where('status',1)->get();
        $unit_types=UnitType::select('unit_type_id','unit_type_name','unit_type_price')->where('status',1)->get();
        $companies=Company::select('company_id','company_name')->get();
        return view('admin.Items.create', compact('item_types','unit_types','companies'));
    }
    public function store(Request $request)
    {
        $validator  = $this->validate($request,['item_name'=>'required']);
        $data  = new Item;
        $req=$request->all();
        $req['item_no']=substr(str_shuffle(time().'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),0,15);
        //echo '<pre>';print_r($req);die;
        if ($data->create($req)) {
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
        $item_types=ItemType::select('item_type_id','item_type_name')->where('status',1)->get();
        $unit_types=UnitType::select('unit_type_id','unit_type_name','unit_type_price')->where('status',1)->get();
        $companies=Company::select('company_id','company_name')->get();
        return view('admin.Items.edit', compact('data','item_types','unit_types','companies'));
    }
    public function update(Request $request, $id)
    {
        $validator  = $this->validate($request,['item_name'=>'required']);
        $data  = Item::find($id);
        $req=$request->all();
        //echo '<pre>';print_r($req);die;
        if ($data->update($req)) {
            return redirect()->back()->with('success', 'Item updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
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
