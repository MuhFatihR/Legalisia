<?php

namespace App\Http\Controllers\Approver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Mail\DocumentApprovedMail;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\LogApprovalController;

use App\Models\PenomoranKontrak;
use App\Models\PenomoranKontrakAttachment;
use App\Models\Approver;
use Carbon\Carbon;

use Exception;

class ApproverController extends Controller{
    public function index(){
        return view('approver/approver');
    }

    public function getListApproval(){
        try {

            $legalprod = DB::connection('mysql');

            $approverName = auth()->user()->name;
            $approverEmail = auth()->user()->email;
            $approverID = auth()->user()->id;


            $query = "SELECT a.id AS id, a.pk_id AS pk_id, pk.so_id, pk.tanggal_kontrak, pk.no_kontrak_compnet, pk.no_kontrak_customer, pk.customer_name, pk.job_position, pk.company_name, pk.nama_uploader, pk.deskripsi,a.status_approval, a.employee_id, a.approver_email, a.approver_name
                    FROM penomoran_kontrak pk
                    JOIN approver a ON a.pk_id = pk.id
                    WHERE a.approver_email = '" . $approverEmail . "'";
            $que = $legalprod->select($query);

            $data = array();

            foreach ($que as $datakontrak) {
                if ($datakontrak->job_position == 'MTC') {
                    $jobPosition = 'Maintenance Murni';
                } else if ($datakontrak->job_position == 'LEA') {
                    $jobPosition = 'Leasing/Sewa Perangkat';
                } else if ($datakontrak->job_position == 'ADA') {
                    $jobPosition = 'Pengadaan';
                } else if ($datakontrak->job_position == 'PS') {
                    $jobPosition = 'Partnership';
                } else if ($datakontrak->job_position == 'PIM') {
                    $jobPosition = 'Pengadaan - Instalasi - Maintenance';
                } else if ($datakontrak->job_position == 'SM') {
                    $jobPosition = 'Sewa Menyewa';
                } else if ($datakontrak->job_position == 'PIMR') {
                    $jobPosition = 'Pengadaan - Instalasi - Maintenance - Renta';
                } else if ($datakontrak->job_position == 'SK') {
                    $jobPosition = 'Sub Kontrak';
                } else if ($datakontrak->job_position == 'MOU') {
                    $jobPosition = 'Memorandum of Understanding';
                } else if ($datakontrak->job_position == 'NDA') {
                    $jobPosition = 'Non Disclosure Agreement';
                } else if ($datakontrak->job_position == 'FWR') {
                    $jobPosition = 'Kontrak dengan Forwarder';
                } else if ($datakontrak->job_position == 'PKS') {
                    $jobPosition = 'Kerja Sama';
                } else if ($datakontrak->job_position == 'KSO') {
                    $jobPosition = 'Konsorsium';
                }

                // if ($datakontrak->company_name == 'NCI') {
                //     $companyName = 'PT. Nusantara Compnet Integrator';
                // } else if ($datakontrak->company_name == 'IOT') {
                //     $companyName = 'PT. Inovasi Otomasi Teknologi';
                // } else if ($datakontrak->company_name == 'PSA') {
                //     $companyName = 'PT. Pro Sistimatika Automasi';
                // } else if ($datakontrak->company_name == 'SJT') {
                //     $companyName = 'PT. Sugi Jaya Teknologi';
                // } else if ($datakontrak->company_name == 'CIS') {
                //     $companyName = 'PT. Compnet Integrator Services';
                // }

                if ($datakontrak->no_kontrak_customer && $datakontrak->no_kontrak_compnet) {
                    $no_kontrak = $datakontrak->no_kontrak_compnet . ' | ' . $datakontrak->no_kontrak_customer;
                } else if ($datakontrak->no_kontrak_compnet) {
                    $no_kontrak = $datakontrak->no_kontrak_compnet;
                } else if ($datakontrak->no_kontrak_customer) {
                    $no_kontrak = $datakontrak->no_kontrak_customer;
                }

                // $row = array();
                // $row[] = $datakontrak->no_urut ? $datakontrak->no_urut : '-';
                // $row[] = $datakontrak->tanggal_kontrak;
                // $row[] = $datakontrak->no_kontrak_compnet ? $datakontrak->no_kontrak_compnet : '-';
                // $row[] = $datakontrak->no_kontrak_customer ? $datakontrak->no_kontrak_customer : '-';
                // $row[] = $datakontrak->customer_name;
                // $row[] = $jobPosition;
                // $row[] = $companyName;
                // $row[] = $datakontrak->nama_uploader;
                // $row[] = $datakontrak->deskripsi;
                // $row[] = $datakontrak->status_approval;
                // $row[] = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPenomoranKontrak" id="' . $datakontrak->pk_id . '" data_name="' . $no_kontrak . '" data_appid="' . $datakontrak->id . '" onclick="modalShow(this)">Detail</button>';
                // $data[] = $row;

                $row = array();
                $row[] = $datakontrak->no_kontrak_compnet ? $datakontrak->no_kontrak_compnet : '-';
                $row[] = $datakontrak->no_kontrak_customer ? $datakontrak->no_kontrak_customer : '-';
                $row[] = $datakontrak->so_id ? $datakontrak->so_id : '-';
                $row[] = $datakontrak->tanggal_kontrak;
                $row[] = $datakontrak->customer_name;
                $row[] = $jobPosition;
                $row[] = $datakontrak->status_approval;
                $row[] = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPenomoranKontrak" id="' . $datakontrak->pk_id . '" data_name="' . $no_kontrak . '" data_appid="' . $datakontrak->id . '" onclick="modalShow(this)">Detail</button>';
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

    // public function searchData(Request $req)
    // {
    //     try {
    //         $legalprod = DB::connection('mysql');

    //         $no_urut = $req->input('no_urut_filter');
    //         $tanggal_kontrak = $req->input('tanggal_kontrak_filter');
    //         $no_kontrak_compnet = $req->input('no_kontrak_compnet_filter');
    //         $no_kontrak_customer = $req->input('no_kontrak_customer_filter');
    //         $customer_name = $req->input('customer_name_filter');
    //         $job_position = $req->input('job_position_filter');
    //         $approverEmail = auth()->user()->email;

    //         $where = [];

    //         if (!empty($no_urut)) {
    //             $where[] = "no_urut LIKE '%" . $no_urut . "%' ";
    //         }
    //         if (!empty($tanggal_kontrak)) {
    //             $where[] = "tanggal_kontrak LIKE '%" . $tanggal_kontrak . "%' ";
    //         }
    //         if (!empty($no_kontrak_compnet)) {
    //             $where[] = "no_kontrak_compnet LIKE '%" . $no_kontrak_compnet . "%' ";
    //         }
    //         if (!empty($no_kontrak_customer)) {
    //             $where[] = "no_kontrak_customer LIKE '%" . $no_kontrak_customer . "%' ";
    //         }
    //         if (!empty($customer_name)) {
    //             $where[] = "customer_name LIKE '%" . $customer_name . "%' ";
    //         }
    //         if (!empty($job_position)) {
    //             $where[] = "job_position LIKE '%" . $job_position . "%' ";
    //         }

    //         $where_clause = '';
    //         // if (!empty($where)) {
    //         //     $where_clause = ' WHERE ' . implode(' AND ', $where);
    //         // }

    //         if (!empty($where)) {
    //             $where_clause = implode(' AND ', $where);
    //         }

    //         // $queSearch = "SELECT * FROM penomoran_kontrak" . $where_clause;
    //         // $queSearchData = $legalprod->select($queSearch);

    //         $querySearch = "SELECT a.id AS id, a.pk_id AS pk_id, pk.no_urut, pk.tanggal_kontrak, pk.no_kontrak_compnet, pk.no_kontrak_customer, pk.customer_name, pk.job_position, pk.company_name, pk.nama_uploader, pk.deskripsi,a.status_approval, a.employee_id, a.approver_email, a.approver_name
    //                 FROM penomoran_kontrak pk
    //                 JOIN approver a ON a.pk_id = pk.id
    //                 WHERE a.approver_email = '" . $approverEmail . "' AND " . $where_clause . "";
    //         $queSearchData = $legalprod->select($querySearch);

    //         $data = array();
    //         foreach ($queSearchData as $datakontrak) {
    //             if ($datakontrak->job_position == 'MTC') {
    //                 $jobPosition = 'Maintenance Murni';
    //             } else if ($datakontrak->job_position == 'LEA') {
    //                 $jobPosition = 'Leasing/Sewa Perangkat';
    //             } else if ($datakontrak->job_position == 'ADA') {
    //                 $jobPosition = 'Pengadaan';
    //             } else if ($datakontrak->job_position == 'PS') {
    //                 $jobPosition = 'Partnership';
    //             } else if ($datakontrak->job_position == 'PIM') {
    //                 $jobPosition = 'Pengadaan - Instalasi - Maintenance';
    //             } else if ($datakontrak->job_position == 'SM') {
    //                 $jobPosition = 'Sewa Menyewa';
    //             } else if ($datakontrak->job_position == 'PIMR') {
    //                 $jobPosition = 'Pengadaan - Instalasi - Maintenance - Renta';
    //             } else if ($datakontrak->job_position == 'SK') {
    //                 $jobPosition = 'Sub Kontrak';
    //             } else if ($datakontrak->job_position == 'MOU') {
    //                 $jobPosition = 'Memorandum of Understanding';
    //             } else if ($datakontrak->job_position == 'NDA') {
    //                 $jobPosition = 'Non Disclosure Agreement';
    //             } else if ($datakontrak->job_position == 'FWR') {
    //                 $jobPosition = 'Kontrak dengan Forwarder';
    //             } else if ($datakontrak->job_position == 'PKS') {
    //                 $jobPosition = 'Kerja Sama';
    //             } else if ($datakontrak->job_position == 'KSO') {
    //                 $jobPosition = 'Konsorsium';
    //             }

    //             if ($datakontrak->no_kontrak_customer) {
    //                 $no_kontrak = $datakontrak->no_kontrak_customer;
    //             } else if ($datakontrak->no_kontrak_compnet) {
    //                 $no_kontrak = $datakontrak->no_kontrak_compnet;
    //             }

    //             $row = array();
    //             $row[] = $datakontrak->no_kontrak_compnet ? $datakontrak->no_kontrak_compnet : '-';
    //             $row[] = $datakontrak->no_kontrak_customer ? $datakontrak->no_kontrak_customer : '-';
    //             $row[] = $datakontrak->no_urut ? $datakontrak->no_urut : '-';
    //             $row[] = $datakontrak->tanggal_kontrak;
    //             $row[] = $datakontrak->customer_name;
    //             $row[] = $jobPosition;
    //             $row[] = $datakontrak->status_approval;
    //             $row[] = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalApprovalDetail" id="' . $datakontrak->id . '" data_name="' . $no_kontrak . '" onclick="modalShow(this)">Detail</button>';
    //             $data[] = $row;
    //         }

    //         return response()->json([
    //             "sql" => $querySearch,
    //             "draw" => -1,
    //             "recordsTotal" => count($data),
    //             "recordsFiltered" => count($data),
    //             "data" => $data
    //         ]);
    //     } catch (Exception $ex) {
    //         return response()->json([
    //             'code' => $ex->getCode(),
    //             'message' => $ex->getMessage()
    //         ]);
    //     }
    // }

    public function searchData(Request $req){
        try {
            $approverEmail = auth()->user()->email;

            $query = DB::table('penomoran_kontrak as pk')
                ->join('approver as a', 'a.pk_id', '=', 'pk.id')
                ->select(
                    'a.id as id',
                    'a.pk_id as pk_id',
                    'pk.so_id',
                    'pk.tanggal_kontrak',
                    'pk.no_kontrak_compnet',
                    'pk.no_kontrak_customer',
                    'pk.customer_name',
                    'pk.job_position',
                    'pk.company_name',
                    'pk.nama_uploader',
                    'pk.deskripsi',
                    'a.status_approval',
                    'a.employee_id',
                    'a.approver_email',
                    'a.approver_name'
                )
                ->where('a.approver_email', $approverEmail);

            // Apply filters if they are present
            if ($req->filled('so_number_filter')) {
                $query->where('pk.so_id', 'LIKE', '%' . $req->input('so_number_filter') . '%');
            }
            if ($req->filled('tanggal_kontrak_filter')) {
                $query->where('pk.tanggal_kontrak', 'LIKE', '%' . $req->input('tanggal_kontrak_filter') . '%');
            }
            if ($req->filled('no_kontrak_compnet_filter')) {
                $query->where('pk.no_kontrak_compnet', 'LIKE', '%' . $req->input('no_kontrak_compnet_filter') . '%');
            }
            if ($req->filled('no_kontrak_customer_filter')) {
                $query->where('pk.no_kontrak_customer', 'LIKE', '%' . $req->input('no_kontrak_customer_filter') . '%');
            }
            if ($req->filled('customer_name_filter')) {
                $query->where('pk.customer_name', 'LIKE', '%' . $req->input('customer_name_filter') . '%');
            }
            if ($req->filled('job_position_filter')) {
                $query->where('pk.job_position_full', 'LIKE', '%' . $req->input('job_position_filter') . '%');
            }
            if ($req->filled('status_filter')) {
                $query->where('a.status_approval', 'LIKE', '%' . $req->input('status_filter') . '%');
            }

            $queSearchData = $query->get();

            // Process data as before
            $data = [];
            foreach ($queSearchData as $datakontrak) {
                if ($datakontrak->job_position == 'MTC') {
                    $jobPosition = 'Maintenance Murni';
                } else if ($datakontrak->job_position == 'LEA') {
                    $jobPosition = 'Leasing/Sewa Perangkat';
                } else if ($datakontrak->job_position == 'ADA') {
                    $jobPosition = 'Pengadaan';
                } else if ($datakontrak->job_position == 'PS') {
                    $jobPosition = 'Partnership';
                } else if ($datakontrak->job_position == 'PIM') {
                    $jobPosition = 'Pengadaan - Instalasi - Maintenance';
                } else if ($datakontrak->job_position == 'SM') {
                    $jobPosition = 'Sewa Menyewa';
                } else if ($datakontrak->job_position == 'PIMR') {
                    $jobPosition = 'Pengadaan - Instalasi - Maintenance - Renta';
                } else if ($datakontrak->job_position == 'SK') {
                    $jobPosition = 'Sub Kontrak';
                } else if ($datakontrak->job_position == 'MOU') {
                    $jobPosition = 'Memorandum of Understanding';
                } else if ($datakontrak->job_position == 'NDA') {
                    $jobPosition = 'Non Disclosure Agreement';
                } else if ($datakontrak->job_position == 'FWR') {
                    $jobPosition = 'Kontrak dengan Forwarder';
                } else if ($datakontrak->job_position == 'PKS') {
                    $jobPosition = 'Kerja Sama';
                } else if ($datakontrak->job_position == 'KSO') {
                    $jobPosition = 'Konsorsium';
                }

                if ($datakontrak->no_kontrak_customer) {
                    $no_kontrak = $datakontrak->no_kontrak_customer;
                } else if ($datakontrak->no_kontrak_compnet) {
                    $no_kontrak = $datakontrak->no_kontrak_compnet;
                }

                $row = array();
                $row[] = $datakontrak->no_kontrak_compnet ? $datakontrak->no_kontrak_compnet : '-';
                $row[] = $datakontrak->no_kontrak_customer ? $datakontrak->no_kontrak_customer : '-';
                $row[] = $datakontrak->so_id ? $datakontrak->so_id : '-';
                $row[] = $datakontrak->tanggal_kontrak;
                $row[] = $datakontrak->customer_name;
                $row[] = $jobPosition;
                $row[] = $datakontrak->status_approval;
                $row[] = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalApprovalDetail" id="' . $datakontrak->pk_id . '" data_name="' . $no_kontrak . '" data_appid="' . $datakontrak->id . '" onclick="modalShow(this)">Detail</button>';
                $data[] = $row;
            }

            return response()->json([
                "draw" => -1,
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function getdetailApproval($id){
        try {
            // $hrdprod = DB::connection('mysqlHRD');
            $data = PenomoranKontrak::query()->where('id', '=', $id)->with(['penomoran_kontrak_attachment', 'approver'])->first();
            // $hrdsql = "SELECT DISTINCT id, full_name, email FROM employee WHERE active = '1' ORDER BY full_name";
            // $listemployee = $hrdprod->select($hrdsql);

            // return response()->json([
            //     'data' => $data,
            //     'listemployee' => $listemployee
            // ]);

            return response()->json($data);
        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function ApproveDocument(Request $req, $id){
        try {
            $legalprod = DB::connection('mysql');
            $hrdprod = DB::connection('mysqlHRD');

            $approverNotes = $req->input('approveNotes');
            $date = date('Y-m-d H:i:s');

            $approveDocument = Approver::query()->where('id', '=', $id)->update([
                'status_approval' => 'Approved',
                'tanggal_approval' => $date,
                'notes' => $approverNotes
            ]);

            $queryGetpkid = "SELECT * FROM penomoran_kontrak pk JOIN approver a ON a.pk_id = pk.id WHERE a.id = '" . $id . "'";
            $queGetpkid = $legalprod->select($queryGetpkid);

            if (count($queGetpkid) > 0) {
                $pk_id = $queGetpkid[0]->pk_id;
                $nama_uploader = $queGetpkid[0]->nama_uploader;
                $approverName = $queGetpkid[0]->approver_name;
            } else {
                throw new Exception('No contract found for the given approver ID.');
            }

            $queryGetUploaderEmail = "SELECT * FROM employee WHERE full_name = '" . $nama_uploader . "'";
            $queGetUploaderEmail = $hrdprod->select($queryGetUploaderEmail);

            if (count($queGetUploaderEmail) > 0) {
                $email_uploader = $queGetUploaderEmail[0]->email;
            } else {
                throw new Exception('No uploader email found for the given name.');
            }

            $no_kontrak_compnet = $queGetpkid[0]->no_kontrak_compnet ?? '';
            $no_kontrak_customer = $queGetpkid[0]->no_kontrak_customer ?? '';

            // $no_kontrak = trim($no_kontrak_compnet . ($no_kontrak_customer ? ' | ' . $no_kontrak_customer : ''));

            $no_kontrak = '';

            if ($no_kontrak_customer && $no_kontrak_compnet) {
                $no_kontrak = $no_kontrak_compnet . ' | ' . $no_kontrak_customer;
            } elseif ($no_kontrak_customer) {
                $no_kontrak = $no_kontrak_customer;
            } elseif ($no_kontrak_compnet) {
                $no_kontrak = $no_kontrak_compnet;
            }

            $projectNumber = "";

            $customerName = $queGetpkid[0]->customer_name;
            $soNumber = $queGetpkid[0]->so_id;
            $poNumber = $queGetpkid[0]->po_number;

            if (!$soNumber && $poNumber) {
                $projectNumber = $poNumber;
            } else if (!$poNumber && $soNumber) {
                $projectNumber = $soNumber;
            }

            $queryGetStatus = "SELECT * FROM approver WHERE pk_id = " . $pk_id;
            $queGetStatus = $legalprod->select($queryGetStatus);

            $allApproved = true;
            foreach ($queGetStatus as $datastatus) {
                if ($datastatus->status_approval == 'Rejected' || $datastatus->status_approval == 'Waiting') {
                    $allApproved = false;
                    break;
                }
            }

            if ($allApproved) {
                $updateApprovePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $pk_id)->update([
                    'status' => 'Approved'
                ]);
            }

            $queryGetApproverLevel = "SELECT * FROM approver WHERE id = " . $id;
            $queGetApproverLevel = $legalprod->select($queryGetApproverLevel);

            if (count($queGetApproverLevel) > 0) {
                $approverLevel = $queGetApproverLevel[0]->approver_level;
                $approverEmployeeID = $queGetApproverLevel[0]->employee_id;
                $approverLevelInt = intval($approverLevel);
                $nextApproverLevel = $approverLevelInt + 1;
            } else {
                throw new Exception('Approver level not found for the given ID.');
            }

            $queryGetNextApprover = "SELECT * FROM approver WHERE pk_id = " . $pk_id . " AND approver_level = '" . $nextApproverLevel . "'";
            $queGetNextApprover = $legalprod->select($queryGetNextApprover);

            if (count($queGetNextApprover) > 0) {
                $nextApproverEmail = $queGetNextApprover[0]->approver_email;
                $nextApproverName = $queGetNextApprover[0]->approver_name;

                $messageApproval = 'Dear Bapak/Ibu ' . $nextApproverName . ',
                                    <br><br>' . $approverName . ' has approved the contract with the following details. ' . '<br><br>' .
                                            '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                                                <tr>
                                                    <td style="border: 1px solid #000;">Contract Number</td>
                                                    <td style="border: 1px solid #000;">' . $no_kontrak . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #000;">Project Number</td>
                                                    <td style="border: 1px solid #000;">' . $projectNumber . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #000;">Customer Name</td>
                                                    <td style="border: 1px solid #000;">' . $customerName . '</td>
                                                </tr>
                                        </table>' . '<br><br>' .
                                        'Please proceed the approval process through the following link: <a href="' . url('approver/listApproval') . '">Link Approval</a>.' . '<br><br><br>' .
                                        'Thank you.';

                Mail::to($nextApproverEmail)->send(new DocumentApprovedMail($messageApproval));
                // Mail::to('ricky.krisdianto@compnet.co.id')->send(new DocumentApprovedMail($messageApproval));
            }

            $message = 'Dear Bapak/Ibu ' . $nama_uploader . ',
                        <br><br>' . $approverName . ' has approved the contract with the following details. ' . '<br><br>' .
                            '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                                    <tr>
                                        <td style="border: 1px solid #000;">Contract Number</td>
                                        <td style="border: 1px solid #000;">' . $no_kontrak . '</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #000;">Project Number</td>
                                        <td style="border: 1px solid #000;">' . $projectNumber . '</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #000;">Customer Name</td>
                                        <td style="border: 1px solid #000;">' . $customerName . '</td>
                                    </tr>
                            </table>' . '<br><br>' .
                            'Thank you.';

            Mail::to($email_uploader)->send(new DocumentApprovedMail($message));
            // Mail::to('ricky.krisdianto@compnet.co.id')->send(new DocumentApprovedMail($message));

            $log = auth()->user()->name . ' Approved File ' . $no_kontrak. ' On Approver Level '. $approverLevel;
            $log .= "<br> - Approval ID : " . $id;
            // $log .= "<br> - Penomoran Kontrak ID : " . $pk_id;
            // $log .= "<br> - Approver ID : " . $approverEmployeeID;
            $log .= "<br> - Approve Note : " . $approverNotes;

            LogApprovalController::add($log, 'approver', 'UPDATE');

            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ],  500);
        }
    }

    public function RejectDocument(Request $req, $id){
        try {

            $legalprod = DB::connection('mysql');
            $hrdprod = DB::connection('mysqlHRD');

            $approverNotes = $req->input('rejectNotes');
            // $date = Carbon::now()->toDateTimeString();
            $date = date('Y-m-d H:i:s');

            $rejectDocument = Approver::query()->where('id', '=', $id)->update([
                'status_approval' => 'Rejected',
                'tanggal_approval' => $date,
                'notes' => $approverNotes
            ]);

            $queryGetpkid = "SELECT * FROM penomoran_kontrak pk JOIN approver a ON a.pk_id = pk.id WHERE a.id = '" . $id . "'";
            $queGetpkid = $legalprod->select($queryGetpkid);

            $pk_id = $queGetpkid[0]->pk_id;
            $nama_uploader = $queGetpkid[0]->nama_uploader;
            $approverName = $queGetpkid[0]->approver_name;
            $customerName = $queGetpkid[0]->customer_name;
            $soNumber = $queGetpkid[0]->so_id;
            $poNumber = $queGetpkid[0]->po_number;

            $projectNumber = "";

            if (!$soNumber && $poNumber) {
                $projectNumber = $poNumber;
            } else if (!$poNumber && $soNumber) {
                $projectNumber = $soNumber;
            }

            $no_kontrak_compnet = $queGetpkid[0]->no_kontrak_compnet ?? '';
            $no_kontrak_customer = $queGetpkid[0]->no_kontrak_customer ?? '';

            // $no_kontrak = trim($no_kontrak_compnet . ($no_kontrak_customer ? ' | ' . $no_kontrak_customer : ''));

            $no_kontrak = '';

            if ($no_kontrak_customer && $no_kontrak_compnet) {
                $no_kontrak = $no_kontrak_compnet . ' | ' . $no_kontrak_customer;
            } elseif ($no_kontrak_customer) {
                $no_kontrak = $no_kontrak_customer;
            } elseif ($no_kontrak_compnet) {
                $no_kontrak = $no_kontrak_compnet;
            }

            $queryGetUploaderEmail = "SELECT * FROM employee WHERE full_name = '" . $nama_uploader . "'";
            $queGetUploaderEmail = $hrdprod->select($queryGetUploaderEmail);
            $email_uploader = $queGetUploaderEmail[0]->email;

            $queryGetApproverLevel = "SELECT * FROM approver WHERE id = " . $id;
            $queGetApproverLevel = $legalprod->select($queryGetApproverLevel);

            if (count($queGetApproverLevel) > 0) {
                $approverLevel = $queGetApproverLevel[0]->approver_level;
                $approverEmployeeID = $queGetApproverLevel[0]->employee_id;
                $approverLevelInt = intval($approverLevel);
                $nextApproverLevel = $approverLevelInt + 1;
            } else {
                throw new Exception('Approver level not found for the given ID.');
            }

            $updateRejectPenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $pk_id)->update([
                'status' => 'Rejected'
            ]);

            $queryGetNextApprover = "SELECT * FROM approver WHERE pk_id = " . $pk_id . " AND approver_level = '" . $nextApproverLevel . "'";
            $queGetNextApprover = $legalprod->select($queryGetNextApprover);

            if (count($queGetNextApprover) > 0) {
                $nextApproverEmail = $queGetNextApprover[0]->approver_email;
                $nextApproverName = $queGetNextApprover[0]->approver_name;

                // $messageApproval = 'Dear Bapak/Ibu ' . $nextApproverName . ',
                // <br><br>File ' . $no_kontrak . ' telah disetujui oleh ' . $approverName . ',
                // <br>silahkan lakukan proses approval pada file tersebut.';

                $messageApproval = 'Dear Bapak/Ibu ' . $nextApproverName . ',
                <br><br>' . $approverName . ' has rejected the contract with the following details. ' . '<br><br>' .
                        '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                            <tr>
                                <td style="border: 1px solid #000;">Contract Number</td>
                                <td style="border: 1px solid #000;">' . $no_kontrak . '</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000;">Project Number</td>
                                <td style="border: 1px solid #000;">' . $projectNumber . '</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000;">Customer Name</td>
                                <td style="border: 1px solid #000;">' . $customerName . '</td>
                            </tr>
                    </table>' . '<br><br>' .
                    'Thank you.';

                Mail::to($nextApproverEmail)->send(new DocumentApprovedMail($messageApproval));
                // Mail::to('ricky.krisdianto@compnet.co.id')->send(new DocumentApprovedMail($messageApproval));
            }

            // $message = 'Dear ' . $nama_uploader . ',
            // <br><br>File ' . $no_kontrak . ' telah ditolak oleh ' . $approverName . ' pada ' . $date . '';

            $message = 'Dear Bapak/Ibu ' . $nama_uploader . ',
            <br><br>' . $approverName . ' has rejected the contract with the following details. ' . '<br><br>' .
                '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                        <tr>
                            <td style="border: 1px solid #000;">Contract Number</td>
                            <td style="border: 1px solid #000;">' . $no_kontrak . '</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000;">Project Number</td>
                            <td style="border: 1px solid #000;">' . $projectNumber . '</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000;">Customer Name</td>
                            <td style="border: 1px solid #000;">' . $customerName . '</td>
                        </tr>
                </table>' . '<br><br>' .
                'Thank you.';

            Mail::to($email_uploader)->send(new DocumentApprovedMail($message));
            // Mail::to('ricky.krisdianto@compnet.co.id')->send(new DocumentApprovedMail($message));

            $log = auth()->user()->name . ' Rejected File ' . $no_kontrak. ' On Approver Level '. $approverLevel;
            $log .= "<br> - Approval ID : " . $id;
            // $log .= "<br> - Penomoran Kontrak ID : " . $pk_id;
            // $log .= "<br> - Approver ID : " . $approverEmployeeID;
            $log .= "<br> - Reject Note : " . $approverNotes;

            LogApprovalController::add($log, 'approver', 'UPDATE');

            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }
}
