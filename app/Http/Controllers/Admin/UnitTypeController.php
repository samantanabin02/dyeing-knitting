<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UnitType;
use DB;
use Illuminate\Http\Request;
use Validator;

class UnitTypeController extends Controller
{
    public function index(Request $request)
    {
        $req   = $request->all();
        $query = DB::table("unit_type");
        if ($request->has('search_key')) {
            $query->where(function ($query) use ($req) {
                $query->where('unit_type_name', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('unit_type_price', 'like', '%' . $req['search_key'] . '%');
            });
        }
        if ($request->has('status') && $req['status'] != null) {
            $query->where('status', $req['status']);
        }
        $datums = $query->orderBy('unit_type_id', 'desc')->paginate(10);
        return view('admin.unit_type.index', ['datums' => $datums]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function create()
    {
        $data = array();
        return view('admin.unit_type.create', ['data' => $data]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function store(Request $request)
    {
        $validator             = $this->validator($request->all())->validate();
        $data                  = new UnitType;
        $data->unit_type_name  = $request->unit_type_name;
        $data->unit_type_price = $request->unit_type_price;
        $data->status          = 1;
        if ($data->save()) {
            return redirect()->back()->with('success', 'Unit type created successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function show($id)
    {
        $data = UnitType::find($id);
        return view('admin.unit_type.show', ['data' => $data]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function edit($id)
    {
        $data = UnitType::find($id);
        return view('admin.unit_type.edit', ['data' => $data]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function update(Request $request, $id)
    {
        $validator             = $this->validator($request->all())->validate();
        $data                  = UnitType::find($id);
        $data->unit_type_name  = $request->unit_type_name;
        $data->unit_type_price = $request->unit_type_price;
        if ($data->save()) {
            return redirect()->back()->with('success', 'Unit type updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!.');
        }

    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function destroy($id)
    {
        $data = UnitType::find($id);
        if ($data->delete()) {
            return redirect()->route('unittype.index')->with('success', 'Successfully deleted.');
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
            return redirect()->route('unittype.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('unittype.index')->with('error', 'Select record(s) form list for delete.');
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'unit_type_name'  => 'required',
                'unit_type_price' => 'required',
            ],
            [
                'unit_type_name.required'  => 'Enter type name',
                'unit_type_price.required' => 'Enter type price.',
            ]
        );
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
}
