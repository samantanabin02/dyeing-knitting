<?php
namespace App\Http\Controllers\Admin;

use App\Exports\SalesExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Delivery;
use App\Models\Item;
use App\Sales;
use App\SalesItemQuantity;
use App\UnitType;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $req    = $request->all();
        $datums = Sales::select('sales.*', 'company.*')
            ->join('company', 'company.company_id', '=', 'sales.company_id')
            ->where('sales.deleted_at', 1)->orderBy('sales.sales_id', 'desc')->paginate(10);
        return view('admin.sales.index', ['datums' => $datums]);
    }
    public function create()
    {
        $item      = Item::select('id', 'item_name')->get();
        $companies = Company::select('company_id', 'company_name')->get();
        $unit      = UnitType::select('unit_type_name', 'unit_type_id')->get();
        $lot       = Delivery::select('lot_no', 'id')->get();
        return view('admin.sales.create', compact('item', 'companies', 'unit', 'lot'));
    }
    public function store(Request $request)
    {
        $validator                     = $this->validator($request->all())->validate();
        $insert_data                   = new Sales;
        $req                           = $request->all();
        $insert_data->invoice_no       = $req['invoice_no'];
        $insert_data->invoice_date     = $req['invoice_date'];
        $insert_data->despatch_doc     = $req['despatch_doc'];
        $insert_data->challan_no       = $req['challan_no'];
        $insert_data->company_id       = $req['company_id'];
        $insert_data->despatch_through = $req['despatch_through'];
        $insert_data->other_charges    = !empty($req['other_charges']) ? $req['other_charges'] : 0;
        $insert_data->sgst_persentage  = !empty($req['sgst_persentage']) ? $req['sgst_persentage'] : 0;
        $insert_data->cgst_persentage  = !empty($req['cgst_persentage']) ? $req['cgst_persentage'] : 0;
        $insert_data->igst_persentage  = !empty($req['igst_persentage']) ? $req['igst_persentage'] : 0;
        if ($insert_data->save()) {
            if (isset($req['quantity']) && $req['quantity'] != '') {
                $insert_id              = $insert_data->sales_id;
                $lot_no_id              = $req['lot_no_id'];
                $item_id                = $req['item_id'];
                $hsn_code               = $req['hsn_code'];
                $quantity               = $req['quantity'];
                $rate                   = $req['rate'];
                $unit_id                = $req['unit_id'];
                $disc_persentage        = !empty($req['disc_persentage']) ? $req['disc_persentage'] : 0;
                $amount                 = $req['amount'];
                $quantity_details_array = array();
                foreach ($quantity as $key => $quantity_row) {
                    $quantity_details_array[$key]['sales_id']        = $insert_id;
                    $quantity_details_array[$key]['lot_no_id']       = $lot_no_id[$key];
                    $quantity_details_array[$key]['item_id']         = $item_id[$key];
                    $quantity_details_array[$key]['hsn_code']        = $hsn_code[$key];
                    $quantity_details_array[$key]['unit_id']         = $unit_id[$key];
                    $quantity_details_array[$key]['quantity']        = $quantity[$key];
                    $quantity_details_array[$key]['rate']            = $rate[$key];
                    $quantity_details_array[$key]['disc_persentage'] = $disc_persentage[$key];
                    $quantity_details_array[$key]['amount']          = $amount[$key];
                }
                foreach ($quantity_details_array as $quantity_details_row) {
                    $quantity_data = new SalesItemQuantity;
                    $quantity_data->create($quantity_details_row);
                }
            }
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
        $data             = Sales::find($id);
        $item_data        = Item::select('id', 'item_name')->get();
        $companies        = Company::select('company_id', 'company_name')->get();
        $quantity_details = SalesItemQuantity::where('sales_id', $id)->get();
        $unit             = UnitType::select('unit_type_name', 'unit_type_id')->get();
        $lot              = Delivery::select('lot_no', 'id')->get();
        return view('admin.sales.edit', compact('data', 'quantity_details', 'item_data', 'companies', 'unit', 'lot'));
    }
    public function update(Request $request, $id)
    {
        $validator              = $this->validator($request->all())->validate();
        $data                   = Sales::find($id);
        $req                    = $request->all();
        $data->invoice_no       = $req['invoice_no'];
        $data->invoice_date     = $req['invoice_date'];
        $data->despatch_doc     = $req['despatch_doc'];
        $data->challan_no       = $req['challan_no'];
        $data->company_id       = $req['company_id'];
        $data->despatch_through = $req['despatch_through'];
        $data->other_charges    = !empty($req['other_charges']) ? $req['other_charges'] : 0;
        $data->sgst_persentage  = !empty($req['sgst_persentage']) ? $req['sgst_persentage'] : 0;
        $data->cgst_persentage  = !empty($req['cgst_persentage']) ? $req['cgst_persentage'] : 0;
        $data->igst_persentage  = !empty($req['igst_persentage']) ? $req['igst_persentage'] : 0;
        if ($data->save()) {
            if (isset($req['quantity']) && $req['quantity'] != '') {
                $insert_id              = $id;
                $lot_no_id              = $req['lot_no_id'];
                $item_id                = $req['item_id'];
                $hsn_code               = $req['hsn_code'];
                $quantity               = $req['quantity'];
                $rate                   = $req['rate'];
                $unit_id                = $req['unit_id'];
                $disc_persentage        = !empty($req['disc_persentage']) ? $req['disc_persentage'] : 0;
                $amount                 = $req['amount'];
                $quantity_details_array = array();
                foreach ($quantity as $key => $quantity_row) {
                    $quantity_details_array[$key]['sales_id']        = $insert_id;
                    $quantity_details_array[$key]['lot_no_id']       = $lot_no_id[$key];
                    $quantity_details_array[$key]['item_id']         = $item_id[$key];
                    $quantity_details_array[$key]['hsn_code']        = $hsn_code[$key];
                    $quantity_details_array[$key]['unit_id']         = $unit_id[$key];
                    $quantity_details_array[$key]['quantity']        = $quantity[$key];
                    $quantity_details_array[$key]['rate']            = $rate[$key];
                    $quantity_details_array[$key]['disc_persentage'] = $disc_persentage[$key];
                    $quantity_details_array[$key]['amount']          = $amount[$key];
                }
                if (count($quantity_details_array)) {
                    SalesItemQuantity::where('sales_id', $id)->delete();
                    foreach ($quantity_details_array as $quantity_details_row) {
                        $quantity_data = new SalesItemQuantity;
                        $quantity_data->create($quantity_details_row);
                    }
                }
            }
            return redirect()->back()->with('success', 'Item updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
        }

    }
    public function destroy($id)
    {
        $data = Sales::find($id);
        if ($data->delete()) {
            DB::table('sales_item_quantity')->where('sales_id', $id)->delete();
            return redirect()->route('sales.index')->with('success', 'Successfully deleted.');
        }
    }
    public function multi_destroy(Request $request)
    {
        $req           = $request->all();
        $deletable_ids = $req['deletable_ids'];
        $deletable_ids = explode(',', $deletable_ids);
        if (count($deletable_ids) > 0) {
            foreach ($deletable_ids as $deletable_id) {
                $this->destroy($deletable_id);
            }
            return redirect()->route('sales.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('sales.index')->with('error', 'Select record(s) form list for delete.');
        }
    }
    public function export_data(Request $request)
    {
        //echo phpinfo();die;
        $req                       = $request->all();
        $search_data               = [];
        $search_data['search_key'] = $req['export_search_key'];
        return Excel::download(new SalesExport($search_data), 'sales.xlsx');
    }
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'invoice_no'       => 'required',
                'invoice_date'     => 'required',
                'despatch_doc'     => 'required',
                'challan_no'       => 'required',
                'despatch_through' => 'required',
                'company_id'       => 'required',

            ],
            [
                'invoice_no.required'       => 'Please Give A Invoice No.',
                'invoice_date.required'     => 'Please Select Invoice Date',
                'despatch_doc.required'     => 'Please Give A Despatch Doc.',
                'challan_no.required'       => 'Please Give A Challan No.',
                'despatch_through.required' => 'Please Give A Dispatch Through.',
                'company_id.required'       => 'Please Select Comapny Name.',
            ]
        );
    }
}
