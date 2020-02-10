<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Sitesetting;
use Illuminate\Http\Request;

class SitesettingController extends Controller
{

    public function index(Request $request)
    {

        if (count($_POST)) {
            $data         = Sitesetting::find(1);
            $logo         = $data->logo;
            $file_details = $request->file('logo');
            if ($file_details) {
                $file_original_name = $file_details->getClientOriginalName();
                $file_extension     = pathinfo($file_original_name, PATHINFO_EXTENSION);
                $file_name          = rand() . '.' . $file_extension;
                $uploads_path       = public_path('uploads/site_items/');
                $file_details->move($uploads_path, $file_name);
                $logo = $file_name;
            }
            $data->title          = $request->title;
            $data->contact_number = $request->contact_number;
            $data->contact_email  = $request->contact_email;
            $data->address        = $request->address;
            $data->embedded_map   = $request->embedded_map;
            $data->logo           = $logo;
            if ($data->save()) {
                return redirect()->back()->with('success', 'Sitesetting updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Some problem occurred.Please try again!.');
            }
        }
        $data = Sitesetting::find(1);
        return view('admin.Sitesettings.index', ['data' => $data]);

    }

}
