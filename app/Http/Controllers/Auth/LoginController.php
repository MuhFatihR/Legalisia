<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller{
    // public function index(){
    //     return view('auth.login');
    // }

    // public function authenticate(Request $request){
    //     $credentials = [
    //         'UserPrincipalName' => $request->post('email'),   // declared for userprincipalname with email input
    //         'password' => $request->post('password'),  // declared for password with password input
    //     ];
    //     if(Auth::attempt($credentials))
    //     {
    //         $request->session()->regenerate();  // generate login token laravel
    //         return redirect('/');  // redirect to admin page
    //     }
    //     return back()->with('loginError', 'Login failed'); // back to login page that showing error login notification

    // }

    // public function logout(Request $request){
    //     Auth::logout();

    //     $request->session()->invalidate(); // destroy login token laravel
    //     $request->session()->regenerateToken(); // regenerate login token laravel
    //     return redirect('/'); // redirect to login page
    // }
}
