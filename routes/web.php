<?php

use App\Http\Controllers\Kontrak\ListPenomoranKontrakController;
use App\Http\Controllers\FinalKontrak\ListPenomoranRegisterFinalKontrakController;
use App\Http\Controllers\Approver\ApproverController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\LogApprovalController;
use Illuminate\Support\Facades\Route;

use App\Models\Menus;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest'], 'namespace' => 'App\Http\Controllers\Auth'], function () {
    Route::get('connect', 'AuthController@connect')->name('connect');
    Route::get('login', 'AuthController@index')->name('login');
    Route::post('login', 'AuthController@authenticate');

});

Route::group(['middleware' => ['auth:compnet,prosia'], 'namespace' => 'App\Http\Controllers'], function () {

    Route::get('/', function () {
        $user = auth()->user();
        $menus = Menus::getMenus($user->department);

        // JIKA USER = IT SUPPORT & LEGAL TEAM
        if (Menus::isMenuExist($menus, 'Portal Legal - Penomoran Kontrak')) {
            return redirect('/kontrak/listPenomoranKontrak');
        } else { // JIKA USER = APPROVER
            return redirect('/approver/listApproval');
        }
    });

    // Route::get('/', 'HomeController@index')->name('index');
    Route::get('home', 'HomeController@home')->name('home');
    Route::get('logout', 'Auth\AuthController@logout')->name('logout');
    Route::get('me', 'HomeController@me')->name('me');
    Route::get('profile', 'HomeController@profile')->name('profile');
});

// IT
Route::group(['middleware' => ['auth:compnet,prosia', 'role:it-support'], 'namespace' => 'App\Http\Controllers'], function () {
    // Log Activity
    Route::get('log/logActivity', [LogActivityController::class, 'viewLog'])->name('');
    Route::get('log/dataLogActivity', [LogActivityController::class, 'getList'])->name('');
    Route::get('log/searchLogActivity', [LogActivityController::class, 'searchData'])->name('');

    Route::post('loginas', 'HomeController@loginAs')->name('loginas');
});

// LEGAL TEAM
Route::group(['middleware' => ['auth:compnet,prosia', 'role:it-support,corporate-legal'], 'namespace' => 'App\Http\Controllers'], function () {
    // Penomoran Kontrak
    Route::get('kontrak/listPenomoranKontrak', [ListPenomoranKontrakController::class, 'viewList'])->name('');
    Route::get('kontrak/dataPenomoranKontrak', [ListPenomoranKontrakController::class, 'getList'])->name('listPenomoranKontrak'); // GET LIST DATA
    Route::get('kontrak/searchPenomoranKontrak', [ListPenomoranKontrakController::class, 'searchData'])->name(''); // FILTER
    Route::get('kontrak/listPenomoranKontrak/{id}', [ListPenomoranKontrakController::class, 'getdetail'])->name(''); // LIST DETAIL
    Route::post('kontrak/listPenomoranKontrak/{id}', [ListPenomoranKontrakController::class, 'saveEdit'])->name(''); // EDIT PUT
    Route::post('kontrak/listPenomoranKontrakApprovers/{id}', [ListPenomoranKontrakController::class, 'saveEditApprovers'])->name(''); // EDIT APPROVERS PUT
    Route::post('kontrak/savePenomoranKontrak', [ListPenomoranKontrakController::class, 'insertData'])->name(''); //STORE DATA
    Route::get('kontrak/listPenomoranKontrak/{id}/print', [ListPenomoranKontrakController::class, 'print'])->name(''); // PRINT DATA

    // Penomoran Final Kontrak
    Route::get('finalkontrak/listPenomoranRegisterFinalKontrak', [ListPenomoranRegisterFinalKontrakController::class, 'viewList'])->name('');
    Route::get('finalkontrak/dataPenomoranRegisterFinalKontrak', [ListPenomoranRegisterFinalKontrakController::class, 'getList'])->name('listPenomoranRegisterFinalKontrak'); // GET LIST DATA
    Route::get('finalkontrak/searchPenomoranRegisterFinalKontrak', [ListPenomoranRegisterFinalKontrakController::class, 'searchData'])->name(''); // FILTER
    Route::get('finalkontrak/listPenomoranRegisterFinalKontrak/{id}', [ListPenomoranRegisterFinalKontrakController::class, 'getdetail'])->name(''); // LIST DETAIL
    Route::post('finalkontrak/savePenomoranRegisterFinalKontrak', [ListPenomoranRegisterFinalKontrakController::class, 'insertData'])->name(''); //STORE DATA
    Route::post('finalkontrak/listPenomoranRegisterFinalKontrak/{id}', [ListPenomoranRegisterFinalKontrakController::class, 'saveEdit'])->name(''); // EDIT PUT

    // Log Approval
    Route::get('log/logApproval', [LogApprovalController::class, 'viewLog'])->name('');
    Route::get('log/dataLogApproval', [LogApprovalController::class, 'getList'])->name('');
    Route::get('log/searchLogApproval', [LogApprovalController::class, 'searchData'])->name('');
});

// APPROVER
Route::group(['middleware' => ['auth:compnet,prosia'], 'namespace' => 'App\Http\Controllers'], function () {
    // Approver
    Route::get('approver/listApproval', [ApproverController::class, 'index'])->name('');
    Route::get('approver/dataApproval', [ApproverController::class, 'getListApproval'])->name('listPenomoranKontrakApprover');
    Route::get('approver/searchApproval', [ApproverController::class, 'searchData'])->name('');
    Route::get('approver/listApproval/{id}', [ApproverController::class, 'getdetailApproval'])->name('');
    Route::post('approver/approveApproval/{id}', [ApproverController::class, 'ApproveDocument'])->name('');
    Route::post('approver/rejectApproval/{id}', [ApproverController::class, 'RejectDocument'])->name('');
});


// Route khusus untuk compnet
Route::group(['middleware' => ['auth:compnet'], 'namespace' => 'App\Http\Controllers\Compnet'], function () {
    Route::get('compnet', 'HomeController@index')->name('homecompnet');
});

// Route khusus untuk prosia
Route::group(['middleware' => ['auth:prosia'], 'namespace' => 'App\Http\Controllers\Prosia'], function () {
    Route::get('prosia', 'HomeController@index')->name('homeprosia');
});
