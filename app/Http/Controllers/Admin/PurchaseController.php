<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\PurchaseExport;
use Maatwebsite\Excel\Facades\Excel;
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
        $query = Purchase::select('purchase.*')
            ->where('purchase.deleted_at', 1);
        if ($request->has('search_key') && $req['search_key']!='') {
            $query->where(function ($query) use ($req) {
                $query->where('invoice_challan_no', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('invoice_date', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('sgst_persentage', 'like', '%' . $req['search_key'] . '%');
                $query->orWhere('cgst_persentage', 'like', '%' . $req['search_key'] . '%');
            });
        }
        if ($request->has('purchase_company') && $req['purchase_company'] != null) {
            $query->where('purchase.purchase_company_id', $req['purchase_company']);
        }
        if ($request->has('transfer_company') && $req['transfer_company'] != null) {
            $query->where('purchase.material_transfer_company_id', $req['transfer_company']);
        }
        $datums = $query->orderBy('purchase.purchase_id', 'desc')->paginate(10);
        $companies=Company::select('company_id','company_name')->pluck('company_name','company_id');
        return view('admin.purchased.index', compact('datums','companies'));
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
                $data = Purchase::find($deletable_id);
                if ($data->delete()) {
                  DB::table('purchase_item_quantity')->where('purchase_id', $deletable_id)->delete();
                }
            }
            return redirect()->route('purchase.index')->with('success', 'Successfully deleted.');
        } else {
            return redirect()->route('purchase.index')->with('error', 'Select record(s) form list for delete.');
        }
    }

    public function export_data(Request $request)
    {
        $req  = $request->all();
        $search_data=[];
        $search_data['search_key']=$req['export_search_key'];
        $search_data['purchase_company']=$req['export_purchase_company'];
        $search_data['transfer_company']=$req['export_transfer_company'];
        return Excel::download(new PurchaseExport($search_data), 'Purchase.xlsx');
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
