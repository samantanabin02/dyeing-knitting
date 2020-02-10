<?php

namespace App\Http\Controllers\AdminAuth;

use App\Admin;
use App\Http\Controllers\Controller;

//Validator facade used in validator method
use Auth;

//Admin Model
use Illuminate\Http\Request;

//Auth Facade used in guard
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    protected $redirectPath = 'admin_home';

    //shows registration form to admin
    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }
    //Handles registration request for admin
    public function register(Request $request)
    {
        $validator = $this->validator($request->all())->validate();
        $create    = $this->create($request->all());
        if ($create) {
            return redirect()->route('admin-register')->with('success', 'Successfully registered.');
        } else {
            return redirect()->route('admin-register')->with('error', 'Data not saved.');
        }
    }
    //Validates user's Input
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name'                  => 'required',
                'email'                 => 'required | email | unique:admins',
                'password'              => 'required | min:4',
                'password_confirmation' => 'required | same:password',
                'agree'                 => 'required',
            ],
            [
                'name.required'                  => 'Enter full name.',
                'email.required'                 => 'Enter email address.',
                'email.unique'                   => 'Email address already registered.',
                'password.required'              => 'Enter password.',
                'password_confirmation.required' => 'Enter confirm password.',
                'password_confirmation.same'     => 'The password confirmation does not match.',
                'agree.required'                 => 'Agree to the terms & conditions.',
            ]
        );
    }
    //Create a new admin instance after a validation.
    protected function create(array $data)
    {
        return Admin::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    //Get the guard to authenticate Admin
    protected function guard()
    {
        return Auth::guard('web_admin');
    }

}
