<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Dcblogdev\MsGraph\Facades\MsGraph;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller{
    public function index(){
        return view('home');
    }

    public function profile(){
        return $this->index();
    }
    public function home(){
        return $this->index();
    }

    public function loginAs(Request $request){
        $hrdprod = DB::connection('mysqlHRD');
 
        $email = $request->employee;
        if($email){
            $user = User::query()->where('email', 'LIKE', $email)->first();
            if(!$user){
                // $selectData = "SELECT * FROM employee WHERE email = '". $email ."' ";
                $selectData = Employee::where('email', $email)->first();
 
                $full_name = $selectData->full_name;
                $email = $selectData->email;
                $mobile = $selectData->mobile;
                $gender = $selectData->gender;
                $nik = $selectData->nik;
                $title = $selectData->title;
                $department = $selectData->department;
                $division = $selectData->division;
                $company = $selectData->company;
 
                User::updateOrCreate(
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
            }
            $user = User::query()->where('email', 'LIKE', $email)->first();
 
            if($user){
                Auth::login($user);
            }else{
              return redirect('/')->with('loginError', 'User that you choose is not found');
            }
        }
        return redirect('home');
    }

    public function me(){
        $user = MsGraph::get('me/?$select=id,displayName,mail,userPrincipalName,mobilePhone,extensions,officeLocation,jobTitle,department,directReports,manager,userType,employeeHireDate,employeeId,employeeOrgData,employeeType,companyName,createdDateTime,deletedDateTime,lastPasswordChangeDateTime');
        $spv = MsGraph::get('me/manager/?$select=mail,jobTitle');
        $mgr = MsGraph::get('users/'.$spv['mail'].'/manager/?$select=mail,jobTitle');
        $user['supervisor'] = $spv;
        $user['manager'] = $mgr;
        return $user;
    }

}
