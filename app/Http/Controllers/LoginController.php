<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    //To show login page
    public function index()
    {

        return view('login');
    }

    public function authenticate(Request $request) {

        $validator = Validator::make(request()->all(), [ 'email' =>'required|email','password'=> 'required']);

        if( $validator->fails() ) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if(!Auth::attempt( $request->only('email','password') ) ) {
            return redirect()->back()->with('error','Invalid Credentials')->withInput();
        }

        return redirect()->route('account.dashboard');
    }


    public function register(Request $request) {
        
        return view('register');
    }

    public function processRegister(Request $request) {

        $validator = Validator::make(request()->all(), [ 
                'email'=> 'required|email|unique:users',
                'password'=> 'required|min:6|max:12|confirmed',
                'password_confirmation'=> 'required',
                'name'=> 'required|min:6|max:25',
            ]);

            if( $validator->fails() ) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = new User();
            $user->email = $request->email;
            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->role = 'customer';
            $user->save();
            
            return redirect()->route('account.login')->with('success','You have registered successfully.');
    
    }
 
    public function logout() {
        Auth::logout();
        return redirect()->route('account.login');
    }
 
}
