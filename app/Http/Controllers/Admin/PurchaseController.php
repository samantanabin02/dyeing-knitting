<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Item;
use App\Purchase;
use App\PurchaseItemQuantity;
use App\UnitType;
use DB;
use Illuminate\Http\Request;
use Validator;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $req    = $request->all();
        $datums = Purchase::select('purchase.*', 'company.*')
            ->join('company', 'company.company_id', '=', 'purchase.purchase_company_id')
            ->where('purchase.deleted_at', 1)->orderBy('purchase.purchase_id', 'desc')->paginate(10);
        return view('admin.purchased.index', ['datums' => $datums]);
    }
    public function create()
    {
        $item      = Item::select('id', 'item_name')->get();
        $companies = Company::select('company_id', 'company_name')->get();
        $unit      = UnitType::select('unit_type_name', 'unit_type_id')->get();
        return view('admin.purchased.create', compact('item', 'companies', 'unit'));
    }
    public function store(Request $request)
    {
        $validator                                 = $this->validator($request->all())->validate();
        $insert_data                               = new Purchase;
        $req                                       = $request->all();
        $insert_data->invoice_challan_no           = $req['invoice_challan_no'];
        $insert_data->invoice_date                 = $req['invoice_date'];
        $insert_data->purchase_company_id          = $req['purchase_company_id'];
        $insert_data->material_transfer_company_id = $req['material_transfer_company_id'];
        $insert_data->other_charges                = !empty($req['other_charges'])? $req['other_charges'] : 0;
        $insert_data->sgst_persentage              = !empty($req['sgst_persentage'])? $req['sgst_persentage'] : 0;
        $insert_data->cgst_persentage              = !empty($req['cgst_persentage'])? $req['cgst_persentage'] : 0;
        $insert_data->igst_persentage              = !empty($req['igst_persentage'])? $req['igst_persentage'] : 0;
        if ($insert_data->save()) {
            if (isset($req['quantity']) && $req['quantity'] != '') {
                $insert_id              = $insert_data->purchase_id;
                $item_id                = $req['item_id'];
                $unit_id                = $req['unit_id'];
                $quantity               = $req['quantity'];
                $rate                   = $req['rate'];
                $amount                 = $req['amount'];
                $quantity_details_array = array();
                foreach ($quantity as $key => $quantity_row) {
                    $quantity_details_array[$key]['purchase_id'] = $insert_id;
                    $quantity_details_array[$key]['item_id']     = $item_id[$key];
                    $quantity_details_array[$key]['unit_id']     = $unit_id[$key];
                    $quantity_details_array[$key]['quantity']    = $quantity[$key];
                    $quantity_details_array[$key]['rate']        = $rate[$key];
                    $quantity_details_array[$key]['amount']      = $amount[$key];
                }
                foreach ($quantity_details_array as $quantity_details_row) {
                    $quantity_data = new PurchaseItemQuantity;
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
        $data             = Purchase::find($id);
        $item             = Item::select('id', 'item_name')->get();
        $companies        = Company::select('company_id', 'company_name')->get();
        $quantity_details = PurchaseItemQuantity::where('purchase_id', $id)->get();
        $unit             = UnitType::select('unit_type_name', 'unit_type_id')->get();
        return view('admin.purchased.edit', compact('data', 'quantity_details', 'item', 'companies', 'unit'));
    }
    public function update(Request $request, $id)
    {
        $validator                          = $this->validator($request->all())->validate();
        $data                               = Purchase::find($id);
        $req                                = $request->all();
        $data->invoice_challan_no           = $req['invoice_challan_no'];
        $data->invoice_date                 = $req['invoice_date'];
        $data->purchase_company_id          = $req['purchase_company_id'];
        $data->material_transfer_company_id = $req['material_transfer_company_id'];
        $data->other_charges                = !empty($req['other_charges'])? $req['other_charges'] : 0;
        $insert_data->sgst_persentage              = !empty($req['sgst_persentage'])? $req['sgst_persentage'] : 0;
        $insert_data->cgst_persentage              = !empty($req['cgst_persentage'])? $req['cgst_persentage'] : 0;
        $insert_data->igst_persentage              = !empty($req['igst_persentage'])? $req['igst_persentage'] : 0;
        if ($data->save()) {
            if (isset($req['quantity']) && $req['quantity'] != '') {
                $insert_id              = $id;
                $item_id                = $req['item_id'];
                $unit_id                = $req['unit_id'];
                $quantity               = $req['quantity'];
                $rate                   = $req['rate'];
                $amount                 = $req['amount'];
                $quantity_details_array = array();
                foreach ($quantity as $key => $quantity_row) {
                    $quantity_details_array[$key]['purchase_id'] = $insert_id;
                    $quantity_details_array[$key]['item_id']     = $item_id[$key];
                    $quantity_details_array[$key]['unit_id']     = $unit_id[$key];
                    $quantity_details_array[$key]['quantity']    = $quantity[$key];
                    $quantity_details_array[$key]['rate']        = $rate[$key];
                    $quantity_details_array[$key]['amount']      = $amount[$key];
                }
                if (count($quantity_details_array)) {
                    PurchaseItemQuantity::where('purchase_id', $id)->delete();
                    foreach ($quantity_details_array as $quantity_details_row) {
                        $quantity_data = new PurchaseItemQuantity;
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
        $data = Purchase::find($id);
        if ($data->delete()) {
            DB::table('purchase_item_quantity')->where('purchase_id', $id)->delete();
            return redirect()->route('purchase.index')->with('success', 'Successfully deleted.');
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
            return redirect()->route('purchase.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('purchase.index')->with('error', 'Select record(s) form list for delete.');
        }
    }
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'invoice_challan_no'           => 'required',
                'invoice_date'                 => 'required',
                'purchase_company_id'          => 'required',
                'material_transfer_company_id' => 'required',

            ],
            [
                'invoice_challan_no.required'           => 'Please Give A Invoice Challan No.',
                'invoice_date.required'                 => 'Please Select Invoice Date',
                'purchase_company_id.required'          => 'Please Select Company Name.',
                'material_transfer_company_id.required' => 'Please Select Transferred Company.',
            ]
        );
    }
}
