<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ItemType;
use DB;
use Illuminate\Http\Request;
use Validator;

class ItemTypeController extends Controller
{
    public function index(Request $request)
    {
        $req   = $request->all();
        $query = DB::table("item_type");
        if ($request->has('search_key')) {
            $query->where(function ($query) use ($req) {
                $query->where('item_type_name', 'like', '%' . $req['search_key'] . '%');
            });
        }
        if ($request->has('status') && $req['status'] != null) {
            $query->where('status', $req['status']);
        }
        $datums = $query->orderBy('item_type_id', 'desc')->paginate(10);
        return view('admin.item_type.index', ['datums' => $datums]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function create()
    {
        $data = array();
        return view('admin.item_type.create', ['data' => $data]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function store(Request $request)
    {
        $validator             = $this->validator($request->all())->validate();
        $data                  = new ItemType;
        $data->item_type_name  = $request->item_type_name;
        $data->status          = 1;
        if ($data->save()) {
            return redirect()->back()->with('success', 'Item Type created successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function show($id)
    {
        $data = ItemType::find($id);
        return view('admin.item_type.show', ['data' => $data]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function edit($id)
    {
        $data = ItemType::find($id);
        return view('admin.item_type.edit', ['data' => $data]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function update(Request $request, $id)
    {
        $validator             = $this->validator($request->all())->validate();
        $data                  = ItemType::find($id);
        $data->item_type_name  = $request->item_type_name;
        if ($data->save()) {
            return redirect()->back()->with('success', 'Item Type updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!.');
        }

    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function destroy($id)
    {
        $data = ItemType::find($id);
        if ($data->delete()) {
            return redirect()->route('itemtype.index')->with('success', 'Successfully deleted.');
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function multi_destroy(Request $request)
    {
        $req           = $request->all();
        $deletable_ids = $req['deletable_ids'];

        $deletable_ids = explode(',', $deletable_ids);
        if (count($deletable_ids) > 0) {
            foreach ($deletable_ids as $deletable_id) {
                $this->destroy($deletable_id);
            }
            return redirect()->route('itemtype.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('itemtype.index')->with('error', 'Select record(s) form list for delete.');
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'item_type_name'  => 'required',
            ],
            [
                'item_type_name.required'  => 'Enter item type',
            ]
        );
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
}
