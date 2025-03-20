<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use App\Models\LogActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class LogActivityController extends Controller{
    public function viewLog(){
        return view('log/logActivity');
    }

    public function getList(){
        try {
            $query = LogActivity::query();
            $logs = $query->latest()->get();

            $data = array();
            $no = 1;

            foreach ($logs as $dataLogActivity) {
                $row = array();
                $row[] = $no++;
                $row[] = $dataLogActivity->user_email ? $dataLogActivity->user()->first()->full_name : "-";
                $row[] = $dataLogActivity->created_at->format('Y-m-d H:i:s');
                $row[] = $dataLogActivity->updated_table;
                $row[] = $dataLogActivity->action;
                $row[] = $dataLogActivity->method;
                $row[] = $dataLogActivity->subject;
                $data[] = $row;
            }

            return response()->json([
                "sql" => $query,
                "draw" => -1,
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function searchData(HttpRequest $req){
        try {
            $user = $req->input('user_filter');
            $tanggal = $req->input('tanggal_filter');
            $table_name = $req->input('table_name_filter');
            $action = $req->input('action_filter');
            $method = $req->input('method_filter');
            $log = $req->input('log_filter');

            // Adjust the database names as per your configuration
            // $logActivityTable = 'portal_legal.log_activity';
            // $employeeTable = 'hrd.employee';

            // $logSearch = DB::connection('mysql')->table($logActivityTable . ' as log')
            //     ->leftJoin($employeeTable . ' as emp', 'log.user_email', '=', 'emp.email')
            //     ->select('log.*', 'emp.full_name')
            //     ->when($user, function ($query) use ($user) {
            //         $query->where('emp.full_name', 'like', '%' . $user . '%');
            //     })
            //     ->when($tanggal, function ($query) use ($tanggal) {
            //         $query->where('log.created_at', 'like', '%' . $tanggal . '%');
            //     })
            //     ->when($table_name, function ($query) use ($table_name) {
            //         $query->where('log.updated_table', 'like', '%' . $table_name . '%');
            //     })
            //     ->when($action, function ($query) use ($action) {
            //         $query->where('log.action', 'like', '%' . $action . '%');
            //     })
            //     ->when($method, function ($query) use ($method) {
            //         $query->where('log.method', 'like', '%' . $method . '%');
            //     })
            //     ->when($log, function ($query) use ($log) {
            //         $query->where('log.subject', 'like', '%' . $log . '%');
            //     })
            //     ->orderBy('log.created_at', 'desc')
            //     ->get();

            // $data = [];
            // $no = 1;

            $logActivityTable = 'portal_legal.log_activity';
            $employeeTable = 'employee';

            $logSearch = DB::connection('mysql')->table($logActivityTable . ' as log')
                ->leftJoin(DB::connection('mysqlHRD')->getDatabaseName() . '.' . $employeeTable . ' as emp', 'log.user_email', '=', 'emp.email')
                ->select('log.*', 'emp.full_name')
                ->when($user, function ($query) use ($user) {
                    $query->where('emp.full_name', 'like', '%' . $user . '%');
                })
                ->when($tanggal, function ($query) use ($tanggal) {
                    $query->where('log.created_at', 'like', '%' . $tanggal . '%');
                })
                ->when($table_name, function ($query) use ($table_name) {
                    $query->where('log.updated_table', 'like', '%' . $table_name . '%');
                })
                ->when($action, function ($query) use ($action) {
                    $query->where('log.action', 'like', '%' . $action . '%');
                })
                ->when($method, function ($query) use ($method) {
                    $query->where('log.method', 'like', '%' . $method . '%');
                })
                ->when($log, function ($query) use ($log) {
                    $query->where('log.subject', 'like', '%' . $log . '%');
                })
                ->orderBy('log.created_at', 'desc')
                ->get();

            $data = [];
            $no = 1;

            foreach ($logSearch as $dataLogActivity) {
                $row = [];
                $row[] = $no++;
                $row[] = $dataLogActivity->user_email ? $dataLogActivity->full_name : "-";
                $row[] = date('Y-m-d H:i:s', strtotime($dataLogActivity->created_at));
                $row[] = $dataLogActivity->updated_table;
                $row[] = $dataLogActivity->action;
                $row[] = $dataLogActivity->method;
                $row[] = $dataLogActivity->subject;
                $data[] = $row;
            }

            return response()->json([
                "draw" => -1,
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public static function add($subject, $table, $action){
        $legalprod = DB::connection('mysql');

        $logData = [];
        $logData['user_email'] = auth()->check() ? auth()->user()->email : 0;
        $logData['subject'] = $subject;
        $logData['action'] = $action;
        $logData['method'] = Request::method();
        $logData['agent'] = LogActivity::getBrowser();
        $logData['updated_table'] = $table;
        $logData['url'] = Request::fullUrl();
        $logData['ip'] = Request::ip();

        LogActivity::create($logData);
    }

}
