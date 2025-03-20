<?php

namespace App\Http\Controllers\Prosia;

use App\Http\Controllers\Controller;
use Dcblogdev\MsGraph\Facades\MsGraph;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    return $user->ldap;
  }
}