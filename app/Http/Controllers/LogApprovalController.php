<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use App\Models\LogApproval;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class LogApprovalController extends Controller{
    public function viewLog(){
        return view('log/logApproval');
    }

    public function getList(){
        try {
            $query = LogApproval::query();
            $logs = $query->latest()->get();

            $data = array();
            $no = 1;
            foreach ($logs as $dataLogApproval) {
                $row = array();
                $row[] = $no++;
                $row[] = $dataLogApproval->user_email ? $dataLogApproval->user()->first()->full_name : "-";
                $row[] = $dataLogApproval->created_at->format('Y-m-d H:i:s');
                $row[] = $dataLogApproval->updated_table;
                $row[] = $dataLogApproval->action;
                // $row[] = $dataLogApproval->method;
                $row[] = $dataLogApproval->subject;
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
            // $method = $req->input('method_filter');
            $log = $req->input('log_filter');

            // Adjust the database names as per your configuration
            $logApprovalTable = 'portal_legal.log_approval';
            $employeeTable = 'employee';

            $logSearch = DB::connection('mysql')->table($logApprovalTable . ' as log')
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
                // ->when($method, function ($query) use ($method) {
                //     $query->where('log.method', 'like', '%' . $method . '%');
                // })
                ->when($log, function ($query) use ($log) {
                    $query->where('log.subject', 'like', '%' . $log . '%');
                })
                ->orderBy('log.created_at', 'desc')
                ->get();

            $data = [];
            $no = 1;
            foreach ($logSearch as $dataLogApproval) {
                $row = [];
                $row[] = $no++;
                $row[] = $dataLogApproval->user_email ? $dataLogApproval->full_name : "-";
                $row[] = date('Y-m-d H:i:s', strtotime($dataLogApproval->created_at));
                $row[] = $dataLogApproval->updated_table;
                $row[] = $dataLogApproval->action;
                // $row[] = $dataLogApproval->method
                $row[] = $dataLogApproval->subject;
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
        $logData['agent'] = LogApproval::getBrowser();
        $logData['updated_table'] = $table;
        $logData['url'] = Request::fullUrl();
        $logData['ip'] = Request::ip();

        LogApproval::create($logData);
    }

}
