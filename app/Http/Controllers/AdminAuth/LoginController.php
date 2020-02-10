<?php
namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Auth;
//Validator facade used in validator method
use DB;
//Class needed for login and Logout logic
use Hash;
//Auth facade
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;
use View;

class LoginController extends Controller
{
    //Where to redirect admin after login.
    protected $redirectTo = '/admin/dashboard';
    use AuthenticatesUsers;
    protected function guard()
    {
        return Auth::guard('web_admin');
    }
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'email'    => 'required | email',
                'password' => 'required',
            ],
            [
                'email.required'    => 'Enter email address.',
                'email.email'       => 'Enter valid email address.',
                'password.required' => 'Enter password.',
            ]
        );
    }
    public function password_sent(Request $request)
    {
        $admin_check = DB::table('admins')->where('email', $_POST['email'])->get();
        $admin_count = count($admin_check);
        if (!$admin_count) {
            echo '0';
            die;
        } else {
            $random_password = rand(1, 1000000);
            $password = Hash::make($random_password);
            DB::table('admins')
                ->where('email', $_POST['email'])
                ->update(['password' => $password]);

            $to      = $_POST['email'];
            $subject = "Admin Password";
            $view    = View::make('frontend.emails.password_sent_to_admin', ['email' => $_POST['email'], 'password' => $random_password]);
            $message = $view->render();
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: samantanabin03@gmail.com' . "\r\n";
            mail($to, $subject, $message, $headers);
            echo '1';die;
        }

    }
    public function login(Request $request)
    {
        $validator = $this->validator($request->all())->validate();
        $data      = [
            'email'    => $request->email,
            'password' => $request->password,
            'status'   => 1,
        ];
        $login = Auth::guard('web_admin')->attempt($data);
        if ($login) {
            return redirect()->route('admin-dashboard')->with('success', 'Successfully logged in.');
        } else {
            return redirect()->route('admin-login')->with('error', 'Invalid username/password');
        }
    }
    public function twof_login()
    {
        if (isset($_POST['admin_username']) && $_POST['admin_username'] != '') {
            $admin_username = $_POST['admin_username'];
        } else {
            $admin_username = '';
        }
        return view('admin.auth.2flogin', ['admin_username' => $admin_username]);
    }
    public function logout()
    {
        Auth::guard('web_admin')->logout();
        return redirect()->route('admin-login')->with('success', 'Successfully logged out.');
    }

}
