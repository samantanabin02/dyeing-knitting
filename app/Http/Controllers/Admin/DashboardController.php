<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {

        $datums    = '';
        $user_noti = Admin::find(1);
        foreach ($user_noti->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        return view('admin.dashboard', ['datums' => $datums]);
    }
    public function changepassword()
    {
        $datums      = '';
        $admin_email = Auth::guard('web_admin')->user()->email;
        if (isset($_POST) && !empty($_POST)) {
            $hashed_admin_password = Auth::guard('web_admin')->user()->password;
            if (!Hash::check($_POST['old_password'], $hashed_admin_password)) {
                return redirect()->route('admin-changepassword')->with('error', 'Sorry Old password not matched!.');
            }
            if ($_POST['password'] != $_POST['password_confirmation']) {
                return redirect()->route('admin-changepassword')->with('error', 'Sorry Confirm password not matched!.');
            }
            $password        = Hash::make($_POST['password']);
            $password_update = DB::table('admins')
                ->where('email', $admin_email)
                ->update(['password' => $password]);
            if ($password_update) {
                return redirect()->route('admin-changepassword')->with('success', 'Password hasbeen successfully updated.');
            } else {
                return redirect()->route('admin-changepassword')->with('error', 'Sorry some problem occured.Please try again.');
            }
        }
        return view('admin.accounts.changepassword', ['datums' => $datums]);

    }

    public function changeprofile(Request $request)
    {

        $admin_email = Auth::guard('web_admin')->user()->email;
        $query       = DB::table("admins")->where('email', $admin_email);
        $admin_data  = $query->first();
        $admin_image = $admin_data->image;
        if (isset($_POST) && !empty($_POST)) {
            $name         = $_POST['name'];
            $file_details = $request->file('profile_picture');
            if ($file_details) {
                $file_original_name = $file_details->getClientOriginalName();
                $file_extension     = pathinfo($file_original_name, PATHINFO_EXTENSION);
                $file_name          = rand() . '.' . $file_extension;
                $uploads_path       = public_path('uploads/admin_image/');
                $file_details->move($uploads_path, $file_name);
                $admin_image = $file_name;
            }
            $profile_update = DB::table('admins')
                ->where('email', $admin_email)
                ->update(['name' => $name, 'image' => $admin_image]);
            if ($profile_update) {
                return redirect()->route('admin-changeprofile')->with('success', 'Profile hasbeen successfully updated.');
            } else {
                return redirect()->route('admin-changeprofile')->with('error', 'Sorry some problem occured.Please try again.');
            }
        }
        return view('admin.accounts.changeprofile', ['admin_data' => $admin_data]);
    }

}
