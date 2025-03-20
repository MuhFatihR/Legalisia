<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Dcblogdev\MsGraph\Facades\MsGraph;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller{
    use ListensForLdapBindFailure;

    public function __construct(){
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:prosia')->except('logout');

        $this->listenForLdapBindFailure();
    }

    public function index(){
        if (empty($_COOKIE['email'])) {
            // return view('auth.login');
            // return redirect('https://apps.compnet.co.id/');
            // return redirect('https://login.microsoftonline.com/df3e0c68-de00-41b5-bd67-0036e460bc8c/oauth2/v2.0/authorize?state=53e504dbc3bdb58b0dd1971148d0ff94&scope=offline_access%20openid%20User.ReadWrite%20Mail.Read%20Mail.Send%20Calendars.ReadWrite%20Presence.Read.All&response_type=code&approval_prompt=auto&redirect_uri=https%3A%2F%2Fmtcm.compnet.co.id%2Fmsgraph%2Flogin.php&client_id=6394b479-27da-4935-88ef-28205e811f3a');
            return redirect('https://mtcm.compnet.co.id/msgraph/login.php');
        }

        if (!auth()->check()) {
            $email = $_COOKIE['email'];
            $user = User::query()->where('email', 'LIKE', $email)->first();

            if(!$user){
            // return view('auth.login');
            $selectData = Employee::where('email', $email)->first();

            // $employeePass = $selectData->auth_key;
            if ($selectData) {
                $full_name = $selectData->full_name;
                $email = $selectData->email;
                $mobile = $selectData->mobile;
                $gender = $selectData->gender;
                $nik = $selectData->nik;
                $title = $selectData->title;
                $department = $selectData->department;
                $division = $selectData->division;
                $company = $selectData->company;

                $user = User::updateOrCreate(
                    ['email' => $email], // Kondisi untuk mencari pengguna yang ada
                    [
                        'name' => $full_name,
                        'mobile' => $mobile,
                        'gender' => $gender,
                        'nik' => $nik,
                        'title' => $title,
                        'department' => $department,
                        'division' => $division,
                        'company' => $company,
                    ]
                );

                if($user){
                    Auth::login($user);
                }else{
                    // return redirect('https://apps.compnet.co.id/');
                    // return redirect('https://login.microsoftonline.com/df3e0c68-de00-41b5-bd67-0036e460bc8c/oauth2/v2.0/authorize?state=53e504dbc3bdb58b0dd1971148d0ff94&scope=offline_access%20openid%20User.ReadWrite%20Mail.Read%20Mail.Send%20Calendars.ReadWrite%20Presence.Read.All&response_type=code&approval_prompt=auto&redirect_uri=https%3A%2F%2Fmtcm.compnet.co.id%2Fmsgraph%2Flogin.php&client_id=6394b479-27da-4935-88ef-28205e811f3a');
                    return redirect('https://mtcm.compnet.co.id/msgraph/login.php');
                }

            } else {
                return back()->with('loginError', 'Your Email or Password is incorrect')->onlyInput('email');
            }
            } else {
                Auth::login($user);
            }
            // return view('home');
        }

        return redirect()->intended();
    }

    public function authenticate(Request $request){
        $hrdprod = DB::connection('mysqlHRD');

        $credentials = [
            'mail' => $request->email,   // declared for mail with email input
            'password' => $request->password,  // declared for password with password input
            'fallback' => [
                'email' => $request->email,
                'password' => $request->password,
            ],
        ];
        $guards = [
            'compnet.co.id' => 'compnet',
            'prosia.co.id' => 'prosia',
        ];

        $domain = explode('@', $request->email)[1];
        $guard = $guards[$domain] ?? 'compnet';
        Auth::shouldUse($guard);

        $emailNow = $request->email;
        $passwordNow = Hash::make($request->password);
        // $inputPassword = bcrypt($passwordNow);

        $user = User::query()->where('email', 'LIKE', $emailNow)->first();

        if(!$user){
            $selectData = Employee::where('email', $emailNow)->first();

            $employeePass = $selectData->auth_key;
            if ($selectData) {
                $full_name = $selectData->full_name;
                $mobile = $selectData->mobile;
                $gender = $selectData->gender;
                $nik = $selectData->nik;
                $title = $selectData->title;
                $department = $selectData->department;
                $division = $selectData->division;
                $company = $selectData->company;

                $user = User::create([
                    'email' => $emailNow,
                    'name' => $full_name,
                    'password' => $passwordNow, // Use plain password here
                    'mobile' => $mobile,
                    'gender' => $gender,
                    'nik' => $nik,
                    'title' => $title,
                    'department' => $department,
                    'division' => $division,
                    'company' => $company,
                ]);

                if($user){
                    if(Auth::attempt($credentials)){
                        $request->session()->regenerate();  // generate login token laravel
                        if($guard == "prosia"){
                            return redirect()->intended();
                        }
                        return redirect()->intended();  // redirect to home page
                    }
                }

            } else {
                return back()->with('loginError', 'Your Email or Password is incorrect')->onlyInput('email');
            }
        }else{
            if(Auth::attempt($credentials)){
                $request->session()->regenerate();  // generate login token laravel
                if($guard == "prosia"){
                    return redirect()->intended();
                }
                return redirect()->intended();  // redirect to home page
            }
        }
    }

    public function connect(){
        return MsGraph::connect();
    }

    public function logout(Request $request){
        Session::flush();
        Auth::logout();
        return redirect('https://apps.compnet.co.id/');
    }
}
