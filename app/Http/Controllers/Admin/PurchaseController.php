<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Item;
use App\Purchase;
use App\PurchaseItemQuantity;
use Illuminate\Http\Request;
use Validator;
use DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $req    = $request->all();
        $datums = Purchase::select('purchase.*','company.*','items.*')
            ->join('company', 'company.company_id', '=', 'purchase.purchase_company_id')
            ->join('items', 'items.id', '=', 'purchase.item_id')
            ->where('purchase.deleted_at', 1)->orderBy('purchase.purchase_id', 'desc')->paginate(10);
        return view('admin.purchased.index', ['datums' => $datums]);
    }
    public function create()
    {
        $item      = Item::select('id', 'item_name')->get();
        $companies = Company::select('company_id', 'company_name')->get();
        return view('admin.purchased.create', compact('item', 'companies'));
    }
    public function store(Request $request)
    {
        $validator                                 = $this->validator($request->all())->validate();
        $insert_data                               = new Purchase;
        $req                                       = $request->all();
        $insert_data->purchase_company_id          = $req['purchase_company_id'];
        $insert_data->item_id                      = $req['item_id'];
        $insert_data->material_transfer_company_id = $req['material_transfer_company_id'];
        $insert_data->purchased_date               = $req['purchased_date'];
        if ($insert_data->save()) {
            if (isset($req['quantity']) && $req['quantity'] != '') {
                $insert_id              = $insert_data->purchase_id;
                $quantity               = $req['quantity'];
                $rate                   = $req['rate'];
                $amount                 = $req['amount'];
                $quantity_details_array = array();
                foreach ($quantity as $key => $quantity_row) {
                    $quantity_details_array[$key]['purchase_id'] = $insert_id;
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
        return view('admin.purchased.edit', compact('data', 'quantity_details', 'item', 'companies'));
    }
    public function update(Request $request, $id)
    {
        $validator                          = $this->validator($request->all())->validate();
        $data                               = Purchase::find($id);
        $req                                = $request->all();
        $data->purchase_company_id          = $req['purchase_company_id'];
        $data->item_id                      = $req['item_id'];
        $data->material_transfer_company_id = $req['material_transfer_company_id'];
        $data->purchased_date               = $req['purchased_date'];

        if ($data->save()) {
            if (isset($req['quantity']) && $req['quantity'] != '') {
                $insert_id              = $id;
                $quantity               = $req['quantity'];
                $rate                   = $req['rate'];
                $amount                 = $req['amount'];
                $quantity_details_array = array();
                foreach ($quantity as $key => $quantity_row) {
                    $quantity_details_array[$key]['purchase_id']  = $insert_id;
                    $quantity_details_array[$key]['quantity'] = $quantity[$key];
                    $quantity_details_array[$key]['rate']     = $rate[$key];
                    $quantity_details_array[$key]['amount']   = $amount[$key];
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
                'purchase_company_id'          => 'required',
                'item_id'                      => 'required',
                'material_transfer_company_id' => 'required',
                'purchased_date'               => 'required',
            ],
            [
                'purchase_company_id.required'          => 'Select company.',
                'item_id.required'                      => 'Select Item.',
                'material_transfer_company_id.required' => 'Select transfer company.',
                'purchased_date.required'               => 'Select purchase date.',
            ]
        );
    }
}
