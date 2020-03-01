<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Gst;
use App\Models\Quantity;
use App\Models\ItemType;
use App\Models\UnitType;
use App\Models\Company;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;

class GstController extends Controller
{
    public function index(Request $request)
    {
        $req   = $request->all();
        $datums = Gst::select('gst_type.*')->orderBy('id', 'desc')->paginate(10);
        return view('admin.Gsts.index', ['datums' => $datums]);
    }

    public function create()
    {
        return view('admin.Gsts.create');
    }

    public function store(Request $request)
    {
        $validator  = $this->validate($request,['name'=>'required']);
        $insert_data  = new Gst;
        $req=$request->all();
        $insert_data->name=$req['name'];
        $insert_data->desc=$req['desc'];
        // $insert_data->rate=$req['rate'];
        $insert_data->status=$req['status'];
        //echo '<pre>';print_r($insert_data);die;
        if ($insert_data->save()) {
            return redirect()->back()->with('success', 'Gst created successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }
    }
    public function show($id)
    {
        $data = Gst::find($id);
        return view('admin.Gsts.show', ['data' => $data]);
    }
    public function edit($id)
    {
        $data = Gst::find($id);
        return view('admin.Gsts.edit', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $validator  = $this->validate($request,['name'=>'required']);
        $data  = Gst::find($id);
        $req=$request->all();
        $data->name=$req['name'];
        $data->desc=$req['desc'];
        // $data->rate=$req['rate'];
        $data->status=$req['status'];
        //echo '<pre>';print_r($req);die;
        if ($data->save()) {
            return redirect()->back()->with('success', 'Gst updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }

    }
    public function destroy($id)
    {
        $data = Gst::find($id);
        if ($data->delete()) {
            return redirect()->route('gsts.index')->with('success', 'Successfully deleted.');
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
            return redirect()->route('gsts.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('gsts.index')->with('error', 'Select record(s) form list for delete.');
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
