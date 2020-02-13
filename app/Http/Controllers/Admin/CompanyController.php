<?php
namespace App\Http\Controllers\Admin;

use App\Company;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Validator;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $req   = $request->all();
        $query = DB::table("company");
        if ($request->has('search_key')) {
            $query->where(function ($query) use ($req) {
                $query->where('company_name', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('company_address', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('company_country', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('company_state', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('company_city', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('company_email', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('company_number_1', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('company_number_2', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('company_gst_no', 'like', '%' . $req['search_key'] . '%');
            });
        }
        if ($request->has('status') && $req['status'] != null) {
            $query->where('status', $req['status']);
        }
        $datums = $query->orderBy('company_id', 'desc')->paginate(10);
        return view('admin.Company.index', ['datums' => $datums]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function create()
    {
        $data = array();
        return view('admin.Company.create', ['data' => $data]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function store(Request $request)
    {
        $validator              = $this->validator($request->all())->validate();
        $data                   = new Company;
        $data->company_name     = $request->company_name;
        $data->company_address  = $request->company_address;
        $data->company_country  = strtolower($request->company_country);
        $data->company_state    = strtolower($request->company_state);
        $data->company_city     = strtolower($request->company_city);
        $data->company_email    = strtolower($request->company_email);
        $data->company_number_1 = $request->company_number_1;
        $data->company_number_2 = $request->company_number_2;
        $data->company_gst_no   = $request->company_gst_no;
        $data->status           = $request->status;
        if ($data->save()) {
            return redirect()->back()->with('success', 'User created successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function show($id)
    {
        $data = Company::find($id);
        return view('admin.Company.show', ['data' => $data]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function edit($id)
    {
        $data = Company::find($id);
        return view('admin.Company.edit', ['data' => $data]);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function update(Request $request, $id)
    {
        $validator              = $this->validator($request->all())->validate();
        $data                   = Company::find($id);
        $data->company_name     = $request->company_name;
        $data->company_address  = $request->company_address;
        $data->company_country  = strtolower($request->company_country);
        $data->company_state    = strtolower($request->company_state);
        $data->company_city     = strtolower($request->company_city);
        $data->company_email    = strtolower($request->company_email);
        $data->company_number_1 = $request->company_number_1;
        $data->company_number_2 = $request->company_number_2;
        $data->company_gst_no   = $request->company_gst_no;
        $data->status           = $request->status;
        if ($data->save()) {
            return redirect()->back()->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!.');
        }

    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function destroy($id)
    {
        $data = Company::find($id);
        if ($data->delete()) {
            return redirect()->route('company.index')->with('success', 'Successfully deleted.');
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
            return redirect()->route('company.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('company.index')->with('error', 'Select record(s) form list for delete.');
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'company_name'     => 'required',
                'company_address'  => 'required',
                'company_country'  => 'required',
                'company_state'    => 'required',
                'company_city'     => 'required',
                'company_email'    => 'required',
                'company_number_1' => 'required',
                'company_number_2' => 'required',
                'company_gst_no'   => 'required',
                'status'           => 'required',
            ],
            [
                'company_name.required'   => 'Enter company name.',
                'company_address.required' => 'Enter company address.',
            ]
        );
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
}
