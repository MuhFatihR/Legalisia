<?php

namespace App\Http\Controllers\Kontrak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Request2;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Mail\DocumentApprovedMail;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\LogActivityController;

use App\Models\PenomoranKontrak;
use App\Models\PenomoranKontrakAttachment;
use App\Models\LogActivity;
use App\Models\Approver;
use Exception;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ListPenomoranKontrakController extends Controller
{
    public function viewList()
    {
        return view('kontrak/listPenomoranKontrak');
    }

    public function searchData(Request $req)
    {
        try {
            $legalprod = DB::connection('mysql');

            $no_urut = $req->input('no_urut_filter');
            $tanggal_kontrak = $req->input('tanggal_kontrak_filter');
            $no_kontrak_compnet = $req->input('no_kontrak_compnet_filter');
            $no_kontrak_customer = $req->input('no_kontrak_customer_filter');
            $customer_name = $req->input('customer_name_filter');
            $job_position = $req->input('job_position_filter');
            $so_number = $req->input('so_number_filter');

            $where = [];

            if (!empty($no_urut)) {
                $where[] = "no_urut LIKE '%" . $no_urut . "%' ";
            }
            if (!empty($tanggal_kontrak)) {
                $where[] = "tanggal_kontrak LIKE '%" . $tanggal_kontrak . "%' ";
            }
            if (!empty($no_kontrak_compnet)) {
                $where[] = "no_kontrak_compnet LIKE '%" . $no_kontrak_compnet . "%' ";
            }
            if (!empty($no_kontrak_customer)) {
                $where[] = "no_kontrak_customer LIKE '%" . $no_kontrak_customer . "%' ";
            }
            if (!empty($customer_name)) {
                $where[] = "customer_name LIKE '%" . $customer_name . "%' ";
            }
            if (!empty($job_position)) {
                $where[] = "job_position_full LIKE '%" . $job_position . "%' ";
            }
            if (!empty($so_number)) {
                $where[] = "so_id LIKE '%" . $so_number . "%' ";
            }


            $where_clause = '';
            if (!empty($where)) {
                $where_clause = ' WHERE ' . implode(' AND ', $where);
            }

            $queSearch = "SELECT * FROM penomoran_kontrak" . $where_clause;
            $queSearchData = $legalprod->select($queSearch);

            $data = array();
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
                $row[] = $datakontrak->no_urut ? $datakontrak->no_urut : '-';
                $row[] = $datakontrak->so_id ? $datakontrak->so_id : '-';
                $row[] = $datakontrak->tanggal_kontrak;
                $row[] = $datakontrak->customer_name;
                $row[] = $jobPosition;
                $row[] = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPenomoranKontrak" id="' . $datakontrak->id . '" data_name="' . $no_kontrak . '" onclick="modalShow(this)">Detail</button>';
                $data[] = $row;
            }

            return response()->json([
                "sql" => $queSearch,
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

    public function insertData(Request $req)
    {
        // DB::beginTransaction();

        DB::connection('mysql')->beginTransaction();

        try {
            $legalprod = DB::connection('mysql');

            $numberingBy = (array) $req->input('numberingBy', []);
            $noNeedNumberingBy = $req->input('noNeedNumberingBy');
            $customerText = $req->input('customerText');
            $contractTemplate = $req->input('contractTemplate');
            $effectiveDate = $req->input('effectiveDate');

            $soNumberProject = $req->input('soNumberProject');
            $soCustomerProject = $req->input('soCustomerProject');

            $poNumberProject = $req->input('poNumberProject');
            $poPrincipalProject = $req->input('poPrincipalProject');
            $poPrincipalName = $req->input('poPrincipalName');

            if (!$soNumberProject && $poNumberProject) {
                $projectNumber = $poNumberProject;
            } else if (!$poNumberProject && $soNumberProject) {
                $projectNumber = $soNumberProject;
            }

            $jobPosition = $req->input('jobPosition');
            $companyName = $req->input('companyName');
            $description = $req->input('description');
            $projectName = $req->input('ProjectNameText');

            $fileUpload = $req->file('fileUpload');
            if ($fileUpload) {
                $originalFileName = $fileUpload->getClientOriginalName();
                $path = public_path('penomorankontrak');
                $fileUpload->move($path, $originalFileName);
            } else {
                return response()->json([
                    'message' => 'No files uploaded!'
                ]);
            }

            $jenisapprover = $req->input('jenisapprover', []);
            $jenisapprovername = $req->input('jenisapprovername', []);
            $jenisapproveremail = $req->input('jenisapproveremail', []);

            if ($jenisapprover && $jenisapprovername && $jenisapproveremail) {

                $uploaderName = auth()->user()->name;
                $uploaderEmail = auth()->user()->email;
                // dd($uploaderEmail);
                $status = "Waiting Approval";

                if ($jobPosition == 'MTC') {
                    $jobPosition_full = 'Maintenance Murni';
                } else if ($jobPosition == 'LEA') {
                    $jobPosition_full = 'Leasing/Sewa Perangkat';
                } else if ($jobPosition == 'ADA') {
                    $jobPosition_full = 'Pengadaan';
                } else if ($jobPosition == 'PS') {
                    $jobPosition_full = 'Partnership';
                } else if ($jobPosition == 'PIM') {
                    $jobPosition_full = 'Pengadaan - Instalasi - Maintenance';
                } else if ($jobPosition == 'SM') {
                    $jobPosition_full = 'Sewa Menyewa';
                } else if ($jobPosition == 'PIMR') {
                    $jobPosition_full = 'Pengadaan - Instalasi - Maintenance - Renta';
                } else if ($jobPosition == 'SK') {
                    $jobPosition_full = 'Sub Kontrak';
                } else if ($jobPosition == 'MOU') {
                    $jobPosition_full = 'Memorandum of Understanding';
                } else if ($jobPosition == 'NDA') {
                    $jobPosition_full = 'Non Disclosure Agreement';
                } else if ($jobPosition == 'FWR') {
                    $jobPosition_full = 'Kontrak dengan Forwarder';
                } else if ($jobPosition == 'PKS') {
                    $jobPosition_full = 'Kerja Sama';
                } else if ($jobPosition == 'KSO') {
                    $jobPosition_full = 'Konsorsium';
                }

                // ---------PENOMORAN KONTRAK
                // effectiveDate = 12 Juni 2030
                $tahunFormat = date('Y', strtotime($effectiveDate)); // 2030
                $monthNumber = date('n', strtotime($effectiveDate)); // Juni
                $no_urut = '001';
                $suffix = '';

                $tahunPlus = $tahunFormat + 1; // 2031
                $tahunMinus = $tahunFormat - 1; // 2029

                // jika bulan pada tahun format >= 4
                if ($monthNumber >= 4) {
                    $rangeAwal = "$tahunFormat-04-1";
                    $rangeAkhir = "$tahunPlus-03-31";
                } else if ($monthNumber <= 4) {
                    $rangeAwal = "$tahunMinus-04-1";
                    $rangeAkhir = "$tahunFormat-03-31";
                }

                // jika pada tahunFormat dalam range tahun itu dari bulan 4 (april 2030) - (maret 2031) bulan 3 tahun depan == kosong then no urut = 001 untuk effective date yang dipilih tadi
                $queryFiscal = "SELECT no_urut, tanggal_kontrak, date_created
                                FROM penomoran_kontrak
                                WHERE tanggal_kontrak BETWEEN '" . $rangeAwal . "' AND '" . $rangeAkhir . "'
                                AND no_kontrak_compnet IS NOT NULL
                                ORDER BY date_created DESC LIMIT 1";
                $getFiscal = $legalprod->select($queryFiscal);

                if (empty($getFiscal)) {
                    $no_urut = '001';
                    $suffix = '';
                    // dd('1');
                } else {
                    if ($noNeedNumberingBy != '') { // Khusus No Need Numbering
                        // dd('2');
                        $letterNO = 'NA';
                        $no_urut_start = '001';

                        $no_urut = $letterNO . $no_urut_start;

                        // QUERY 1 : Check Nomor Kontrak untuk tanggal efektif yang sama (Pembuatan Letter A-Z)
                        $queryNoNeedDate = "SELECT no_urut, tanggal_kontrak, date_created
                                            FROM penomoran_kontrak
                                            WHERE tanggal_kontrak = '" . $effectiveDate . "'
                                            AND no_kontrak_compnet IS NOT NULL
                                            AND no_need_numbering IS NOT NULL
                                            ORDER BY date_created DESC LIMIT 1";
                        $getNoNeedDate = $legalprod->select($queryNoNeedDate);

                        // QUERY 2 : Get the latest contract number (no_urut) tanpa letter
                        $queryLastNoUrut = "SELECT MAX(no_urut) as last_no_urut
                                            FROM penomoran_kontrak
                                            WHERE no_need_numbering IS NOT NULL
                                            AND tanggal_kontrak BETWEEN '" . $rangeAwal . "' AND '" . $rangeAkhir . "'";
                        $getLastNoUrut = $legalprod->select($queryLastNoUrut);

                        $lastNoUrut = $getLastNoUrut[0]->last_no_urut ?? 0; //Mengecek hasil query ada isi ga, jika engga $lastNoUrut = 0

                        // Mengecek apakah ada nomor urut untuk tanggal yang sama (Q1)
                        if (!empty($getNoNeedDate)) {
                            $last_no_urut = $getNoNeedDate[0]->no_urut; // Mengambil no_urut dari hasil query yang terbaru

                            $number_part = intval(substr($last_no_urut, 2, 3)); // Ambil bagian angka - Pada index ke-2 (3 buah angka)
                            $suffix_part = substr($last_no_urut, 5); // Ambil bagian huruf (suffix) - Mulai dari index ke-5

                            if (!empty($suffix_part)) {
                                $suffix = chr(ord($suffix_part) + 1); // Tambah 1 ke kode ASCII untuk huruf berikutnya
                            } else {
                                $suffix = 'A';
                            }
                            $no_urut = $letterNO . str_pad($number_part, 3, '0', STR_PAD_LEFT) . $suffix;

                            // Gaada data pada tanggal itu (Query 1)
                        } else {
                            if ($lastNoUrut == 0) {
                                $no_urut;
                            } else {
                                $number_part = intval(substr($lastNoUrut, 2, 3));
                                $number_part += 1;
                                $no_urut = $letterNO . str_pad($number_part, 3, '0', STR_PAD_LEFT); // NA + 000 ( 000 + $number_part)
                            }
                        }
                    } else {
                        // dd('3');
                        $queEffectiveDate = "SELECT no_urut, tanggal_kontrak, date_created
                                            FROM penomoran_kontrak
                                            WHERE tanggal_kontrak = '" . $effectiveDate . "'
                                            AND no_kontrak_compnet IS NOT NULL
                                            AND  no_need_numbering IS NULL
                                            ORDER BY date_created DESC LIMIT 1";
                        $queGetEffectiveDate = $legalprod->select($queEffectiveDate);

                        $queLastNoUrut = "SELECT MAX(no_urut) as last_no_urut
                                        FROM penomoran_kontrak
                                        WHERE no_need_numbering IS NULL
                                        AND tanggal_kontrak BETWEEN '" . $rangeAwal . "' AND '" . $rangeAkhir . "'";
                        $queGetLastNoUrut = $legalprod->select($queLastNoUrut);

                        $lastNoUrut = $queGetLastNoUrut[0]->last_no_urut ?? 0;
                        if (!empty($queGetEffectiveDate)) {
                            $last_no_urut = $queGetEffectiveDate[0]->no_urut;

                            $number_part = intval(substr($last_no_urut, 0, 3));
                            $suffix_part = substr($last_no_urut, 3);

                            if (!empty($suffix_part)) {
                                $suffix = chr(ord($suffix_part) + 1);
                            } else {
                                $suffix = 'A';
                            }
                            $no_urut = str_pad($number_part, 3, '0', STR_PAD_LEFT) . $suffix;
                        } else {
                            if ($lastNoUrut == 0) {
                                $no_urut;
                            } else {
                                $number_part = intval(substr($lastNoUrut, 0, 3));
                                $number_part += 1;
                                $no_urut = str_pad($number_part, 3, '0', STR_PAD_LEFT);
                            }
                        }
                    }
                }


                $romanMonths = [
                    1 => 'I',
                    2 => 'II',
                    3 => 'III',
                    4 => 'IV',
                    5 => 'V',
                    6 => 'VI',
                    7 => 'VII',
                    8 => 'VIII',
                    9 => 'IX',
                    10 => 'X',
                    11 => 'XI',
                    12 => 'XII',
                ];
                $bulanFormat = isset($romanMonths[$monthNumber]) ? $romanMonths[$monthNumber] : '';

                // FORMAT NUMBERING BY
                $no_kontrak = $no_urut . '/K.' . $jobPosition . '/' . $companyName . '/' . $bulanFormat . '/' . $tahunFormat;
                $no_kontrak2 = $no_urut . '/K.' . $jobPosition . '/' . $companyName . '/' . $bulanFormat . '/' . $tahunFormat;

                if ($soNumberProject != '') {
                    $customerName = $soCustomerProject;
                } else if ($poNumberProject != '') {
                    $customerName = $poPrincipalName;
                }

                $emptyValue = '-';

                if (in_array('numbercustomer', $numberingBy) && in_array('numbercompnet', $numberingBy)) {
                    $sqlPenomoranKontrak = "INSERT INTO penomoran_kontrak (no_urut, no_kontrak_compnet, no_kontrak_customer, template_kontrak, tanggal_kontrak, so_id, po_number, customer_name, job_position, company_name, deskripsi, nama_uploader, status, supplier_id, job_position_full, email_uploader, project_name) VALUES ('" . $no_urut . "', '" . $no_kontrak . "', '" . $customerText . "', '" . $contractTemplate . "', '" . $effectiveDate . "', '" . $soNumberProject . "', '" . $poNumberProject . "', '" . $customerName . "', '" . $jobPosition . "', '" . $companyName . "', '" . $description . "', '" . $uploaderName . "', '" . $status . "', '" . $poPrincipalProject . "', '" . $jobPosition_full . "', '" . $uploaderEmail . "', '" . $projectName . "')";
                    $queryInsert2 = $legalprod->select($sqlPenomoranKontrak);
                    $nomor_kontrak = $no_kontrak . ' | ' . $customerText;
                } else if (in_array('numbercompnet', $numberingBy) && !in_array('numbercustomer', $numberingBy)) {
                    $sqlPenomoranKontrak = "INSERT INTO penomoran_kontrak (no_urut, no_kontrak_compnet, template_kontrak, tanggal_kontrak, so_id, po_number, customer_name, job_position, company_name, deskripsi, nama_uploader, status, supplier_id, job_position_full, email_uploader, project_name) VALUES ('" . $no_urut . "', '" . $no_kontrak . "','" . $contractTemplate . "', '" . $effectiveDate . "', '" . $soNumberProject . "', '" . $poNumberProject . "', '" . $customerName . "', '" . $jobPosition . "', '" . $companyName . "', '" . $description . "', '" . $uploaderName . "', '" . $status . "', '" . $poPrincipalProject . "', '" . $jobPosition_full . "', '" . $uploaderEmail . "', '" . $projectName . "')";
                    $queryInsert2 = $legalprod->select($sqlPenomoranKontrak);
                    $nomor_kontrak = $no_kontrak;
                } else if (in_array('numbercustomer', $numberingBy) && !in_array('numbercompnet', $numberingBy)) {
                    $sqlPenomoranKontrak = "INSERT INTO penomoran_kontrak (no_kontrak_customer, template_kontrak, tanggal_kontrak, so_id, po_number, customer_name, job_position, company_name, deskripsi, nama_uploader, status, supplier_id, job_position_full, email_uploader, project_name) VALUES ('" . $customerText . "', '" . $contractTemplate . "', '" . $effectiveDate . "', '" . $soNumberProject . "', '" . $poNumberProject . "', '" . $customerName . "', '" . $jobPosition . "', '" . $companyName . "', '" . $description . "', '" . $uploaderName . "', '" . $status . "', '" . $poPrincipalProject . "', '" . $jobPosition_full . "', '" . $uploaderEmail . "', '" . $projectName . "')";
                    $queryInsert2 = $legalprod->select($sqlPenomoranKontrak);
                    $nomor_kontrak = $customerText;
                } else if (!in_array('numbercustomer', $numberingBy) && !in_array('numbercompnet', $numberingBy)) {
                    $sqlPenomoranKontrak = "INSERT INTO penomoran_kontrak (no_need_numbering, no_kontrak_customer, no_kontrak_compnet, no_urut, template_kontrak, tanggal_kontrak, so_id, po_number, customer_name, job_position, company_name, deskripsi, nama_uploader, status, supplier_id, job_position_full, project_name, email_uploader) VALUES ('" . $noNeedNumberingBy . "', '" . $emptyValue . "','" . $no_kontrak2 . "', '" . $no_urut . "', '" . $contractTemplate . "', '" . $effectiveDate . "', '" . $soNumberProject . "', '" . $poNumberProject . "', '" . $customerName . "', '" . $jobPosition . "', '" . $companyName . "', '" . $description . "', '" . $uploaderName . "', '" . $status . "', '" . $poPrincipalProject . "', '" . $jobPosition_full . "', '" . $projectName . "', '" . $uploaderEmail . "')";
                    $queryInsert2 = $legalprod->select($sqlPenomoranKontrak);
                    $nomor_kontrak = $no_kontrak2;
                }

                $penomoranKontrakID = DB::getPdo()->lastInsertId();

                // FILE ATTACHMENT
                if ($fileUpload) {
                    $newPenomoranKontrakAttachment = PenomoranKontrakAttachment::create([
                        'pk_id' => $penomoranKontrakID,
                        'path' => $path,
                        'file_name' => $originalFileName
                    ]);
                }

                $attachmentID = DB::getPdo()->lastInsertId();

                // APPROVER TABLE
                $totalAppover = count($jenisapprover);
                if ($totalAppover > 0) {
                    $dataApprovers = [];
                    // $approverLevel = 1;
                    $approverLevel = 0;

                    for ($i = 0; $i < $totalAppover; $i++) {
                        // Tingkatkan $approverLevel di awal
                        $approverLevel++;

                        $dataApprovers[] = [
                            'pk_id' => $penomoranKontrakID,
                            'attachment_id' => $attachmentID,
                            'employee_id' => $jenisapprover[$i],
                            'approver_email' => $jenisapproveremail[$i],
                            'approver_name' => $jenisapprovername[$i],
                            'approver_level' => $approverLevel,
                            'status_approval' => 'Waiting'
                        ];

                        $messageNotifyApprovers = 'Dear Bapak/Ibu ' . $jenisapprovername[$i] . ',<br><br>' .
                            'You have been registered as level ' . $approverLevel . ' approver for the contract with the following details.' . '<br><br>' .
                            '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                            <tr>
                                <td style="border: 1px solid #000;">Contract Number</td>
                                <td style="border: 1px solid #000;">' . $nomor_kontrak . '</td>
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
                            'Approval can be made through the following link:  <a href="' . url('approver/listApproval') . '">Link Approval</a>.' . '<br><br><br>' .
                            'Thank you.';

                        // Kirim email ke approver saat ini
                        Mail::to($jenisapproveremail[$i])->send(new DocumentApprovedMail($messageNotifyApprovers));
                    }

                    Approver::insert($dataApprovers);
                }

                $insertedApprovalID = Approver::where('pk_id', $penomoranKontrakID)
                    ->where('attachment_id', $attachmentID)
                    ->pluck('id');

                $insertedApproverID = Approver::where('pk_id', $penomoranKontrakID)
                    ->where('attachment_id', $attachmentID)
                    ->pluck('employee_id');
                // dd($insertedApproverID);
                $insertedApprover = Approver::where('pk_id', $penomoranKontrakID)
                    ->where('attachment_id', $attachmentID)
                    ->pluck('approver_name');


                if (!in_array('numbercustomer', $numberingBy) && !in_array('numbercompnet', $numberingBy)) {
                    $log = auth()->user()->name . ' Added New Numbering ';
                    // $log .= "<br> - ID : " . $penomoranKontrakID;
                    $log .= "<br> - Number by Compnet : ";
                    $log .= "<br> - Number by Customer : " . $customerText;
                    $log .= "<br> - Total Approvers : " . $totalAppover;
                    for ($i = 0; $i < $totalAppover; $i++) {
                        if (isset($insertedApprovalID[$i]) && isset($insertedApprover[$i])) {
                            $log .= "<br> - Approval ID : " . $insertedApprovalID[$i];
                            $log .= " with Approver Name : " . $insertedApprover[$i];
                        }

                        // if (isset($insertedApproverID[$i])) {
                        //     $log .= "<br> - Approver ID : " . $insertedApproverID[$i];
                        // }
                    }
                } else {
                    $log = auth()->user()->name . ' Added New Numbering ';
                    // $log .= "<br> - ID : " . $penomoranKontrakID;
                    $log .= "<br> - Number by Compnet : " . $no_kontrak;
                    $log .= "<br> - Number by Customer : " . $customerText;
                    $log .= "<br> - Total Approvers : " . $totalAppover;
                    for ($i = 0; $i < $totalAppover; $i++) {
                        if (isset($insertedApprovalID[$i]) && isset($insertedApprover[$i])) {
                            $log .= "<br> - Approval ID : " . $insertedApprovalID[$i];
                            $log .= " with Approver Name : " . $insertedApprover[$i];
                        }

                        // if (isset($insertedApproverID[$i])) {
                        //     $log .= "<br> - Approver ID : " . $insertedApproverID[$i];
                        // }
                    }
                }

                LogActivityController::add($log, 'penomoran_kontrak, penomoran_kontrak_attachment, approver', 'INSERT');

                // $logInsert = LogActivity::create([
                //     'user_email' => auth()->check() ? auth()->user()->email : 0,
                //     'subject' => $log,
                //     'action' => 'INSERT',
                //     'method' => Request2::method(),
                //     'agent' => LogActivity::getBrowser(),
                //     'updated_table' => 'penomoran_kontrak, penomoran_kontrak_attachment, approver',
                //     'url' => Request2::fullUrl(),
                //     'ip' => Request2::ip()
                // ]);

                DB::connection('mysql')->commit();

                return response()->json([
                    'code' => 200,
                    'message' => 'Insert Success!'
                ]);
            } else {
                return response()->json([
                    'message' => 'Tidak ada Approver yang dimasukan!'
                ]);
            }
        } catch (Exception $ex) {
            DB::connection('mysql')->rollback();

            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function getList()
    {
        try {
            $legalprod = DB::connection('mysql');

            $uploadName = auth()->user()->name;

            $query = "SELECT * FROM penomoran_kontrak pk";
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

                $no_kontrak = "";

                if ($datakontrak->no_kontrak_customer && $datakontrak->no_kontrak_compnet) {
                    $no_kontrak = $datakontrak->no_kontrak_compnet . ' | ' . $datakontrak->no_kontrak_customer;
                } else if ($datakontrak->no_kontrak_compnet) {
                    $no_kontrak = $datakontrak->no_kontrak_compnet;
                } else if ($datakontrak->no_kontrak_customer) {
                    $no_kontrak = $datakontrak->no_kontrak_customer;
                }

                $row = array();
                $row[] = $datakontrak->no_kontrak_compnet ? $datakontrak->no_kontrak_compnet : '-';
                $row[] = $datakontrak->no_kontrak_customer ? $datakontrak->no_kontrak_customer : '-';
                $row[] = $datakontrak->no_urut ? $datakontrak->no_urut : '-';
                $row[] = $datakontrak->so_id ? $datakontrak->so_id : '-';
                $row[] = $datakontrak->tanggal_kontrak;
                $row[] = $datakontrak->customer_name;
                $row[] = $jobPosition;
                $row[] = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPenomoranKontrak" id="' . $datakontrak->id . '" data_name="' . $no_kontrak . '" onclick="modalShow(this)">Detail</button>';
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

    public function getdetail($id)
    {
        try {
            $hrdprod = DB::connection('mysqlHRD');
            $data = PenomoranKontrak::query()->where('id', '=', $id)->with(['penomoran_kontrak_attachment', 'approver'])->first();
            $hrdsql = "SELECT DISTINCT id, full_name, email FROM employee WHERE active = '1' ORDER BY full_name";
            $listemployee = $hrdprod->select($hrdsql);

            return response()->json([
                'data' => $data,
                'listemployee' => $listemployee
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function saveEdit(Request $req, $id)
    {
        try {
            $legalprod = DB::connection('mysql');

            // Get the existing PenomoranKontrak record
            $penomoranKontrak = PenomoranKontrak::find($id);
            if (!$penomoranKontrak) {
                return response()->json([
                    'code' => 404,
                    'message' => 'PenomoranKontrak not found'
                ], 404);
            }

            $getStatusAwal = "SELECT status FROM penomoran_kontrak WHERE id = '" . $id . "' ";
            $statusAwal = $legalprod->select($getStatusAwal);

            $statusData = $statusAwal[0]->status;
            // dd($statusData);

            $numberingBy = (array) $req->input('numberingByDetail', []);
            $customerText = $req->input('customerTextDetail');
            $compnetText = $req->input('compnetTextDetail');
            // $customerText = $req->input('customerTextDetail') === '-' ? '' : $req->input('customerTextDetail');
            // $compnetText = $req->input('compnetTextDetail') === '-' ? '' : $req->input('compnetTextDetail');

            $contractTemplate = $req->input('contractTemplateDetail');
            $effectiveDate = $req->input('effectiveDateDetail');

            $soNumberProject = empty($req->input('soNumberProjectDetail')) ? "-" : $req->input('soNumberProjectDetail');
            $soCustomerProject = empty($req->input('soCustomerProjectDetail')) ? "-" : $req->input('soCustomerProjectDetail');
            $poNumberProject = empty($req->input('poNumberProjectDetail')) ? "-" : $req->input('poNumberProjectDetail');
            $poPrincipalProject = empty($req->input('poPrincipalProjectDetail')) ? "-" : $req->input('poPrincipalProjectDetail');
            $poPrincipalName = $req->input('poPrincipalNameDetail') == "Principal Name" ? "-" : $req->input('poPrincipalNameDetail');

            $projectDetail = $req->input('projectDetail');

            $projectName = $req->input('ProjectNameTextDetail');

            $jobPosition = $req->input('jobPositionDetail');
            $companyName = $req->input('companyNameDetail');
            $description = $req->input('descriptionDetail');

            $fileUpload = $req->file('fileInputDetail');

            $attachmentBefore = PenomoranKontrakAttachment::where('pk_id', $id)->first();
            $old_file_name = $attachmentBefore->file_name;

            if ($fileUpload) {

                $queryGetFileName = "SELECT * FROM penomoran_kontrak_attachment WHERE pk_id = '" . $id . "'";
                $originalFileName = $fileUpload->getClientOriginalName();
                $path = public_path('penomorankontrak');
                $fileUpload->move($path, $originalFileName);
                $updateAttachment = PenomoranKontrakAttachment::query()->where('pk_id', '=', $id)->update([
                    'path' => $path,
                    'file_name' => $originalFileName
                ]);
            }

            $attachmentAfter = PenomoranKontrakAttachment::where('pk_id', $id)->first();
            $new_file_name = $attachmentAfter->file_name;

            // $jenisapprover = $req->input('jenisapproverDetail', []);
            // $jenisapprovername = $req->input('jenisapprovernameDetail', []);
            // $jenisapproveremail = $req->input('jenisapproveremailDetail', []);

            $tahunFormat = date('Y', strtotime($effectiveDate));
            $monthNumber = date('n', strtotime($effectiveDate));

            $romanMonths = [
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
                5 => 'V',
                6 => 'VI',
                7 => 'VII',
                8 => 'VIII',
                9 => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII',
            ];
            $bulanFormat = isset($romanMonths[$monthNumber]) ? $romanMonths[$monthNumber] : '';

            $queryNoUrut =  "SELECT * from penomoran_kontrak WHERE id = '" . $id . "' LIMIT 1";
            $queGetNoUrut = $legalprod->select($queryNoUrut);

            $no_urut = $queGetNoUrut[0]->no_urut;

            $old_so_id = $queGetNoUrut[0]->so_id;
            $old_supplier_id = $queGetNoUrut[0]->supplier_id;
            $old_po_number = $queGetNoUrut[0]->po_number;
            $old_description = $queGetNoUrut[0]->deskripsi;

            $new_no_kontrak = $no_urut . '/K.' . $jobPosition . '/' . $companyName . '/' . $bulanFormat . '/' . $tahunFormat;

            $customerName = "not registered yet";

            if ($soNumberProject != '-') {
                $customerName = $soCustomerProject;
            } else if ($poNumberProject != '-') {
                $customerName = $poPrincipalName;
            }

            if ($jobPosition == 'MTC') {
                $jobPosition_full = 'Maintenance Murni';
            } else if ($jobPosition == 'LEA') {
                $jobPosition_full = 'Leasing/Sewa Perangkat';
            } else if ($jobPosition == 'ADA') {
                $jobPosition_full = 'Pengadaan';
            } else if ($jobPosition == 'PS') {
                $jobPosition_full = 'Partnership';
            } else if ($jobPosition == 'PIM') {
                $jobPosition_full = 'Pengadaan - Instalasi - Maintenance';
            } else if ($jobPosition == 'SM') {
                $jobPosition_full = 'Sewa Menyewa';
            } else if ($jobPosition == 'PIMR') {
                $jobPosition_full = 'Pengadaan - Instalasi - Maintenance - Renta';
            } else if ($jobPosition == 'SK') {
                $jobPosition_full = 'Sub Kontrak';
            } else if ($jobPosition == 'MOU') {
                $jobPosition_full = 'Memorandum of Understanding';
            } else if ($jobPosition == 'NDA') {
                $jobPosition_full = 'Non Disclosure Agreement';
            } else if ($jobPosition == 'FWR') {
                $jobPosition_full = 'Kontrak dengan Forwarder';
            } else if ($jobPosition == 'PKS') {
                $jobPosition_full = 'Kerja Sama';
            } else if ($jobPosition == 'KSO') {
                $jobPosition_full = 'Konsorsium';
            }

            $emptyValue = '-';

            if ($statusData == 'Rejected') {
                if (in_array('numbercustomerDetail', $numberingBy) && in_array('numbercompnetDetail', $numberingBy)) {

                    if ($customerText == '-' && $compnetText == '-') {
                        // dd($compnetText);
                        // if ($soNumberProject != '-') {
                        // $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                        //     'so_id' => $soNumberProject,
                        //     'supplier_id' => "",
                        //     'po_number' => "",
                        //     'job_position' => $jobPosition,
                        //     'company_name' => $companyName,
                        //     'customer_name' => $customerName,
                        //     'tanggal_kontrak' => $effectiveDate,
                        //     'template_kontrak' => $contractTemplate,
                        //     'no_kontrak_compnet' => $emptyValue,
                        //     'no_kontrak_customer' => $customerText,
                        //     'deskripsi' => $description,
                        //     'status' => 'Waiting Approval',
                        //     'job_position_full' => $jobPosition_full,
                        //     'project_name' => $projectName
                        // ]);
                        // } else if ($poNumberProject != '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => $poPrincipalProject,
                            'po_number' => $poNumberProject,
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $emptyValue,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                        // }
                    } else {
                        if ($projectDetail == 'projectSODetail' && $soCustomerProject !== '-') {
                            // dd($soNumberProject);
                            $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                                'so_id' => $soNumberProject,
                                'supplier_id' => "",
                                'po_number' => "",
                                'job_position' => $jobPosition,
                                'company_name' => $companyName,
                                'customer_name' => $customerName,
                                'tanggal_kontrak' => $effectiveDate,
                                'template_kontrak' => $contractTemplate,
                                'no_kontrak_compnet' => $new_no_kontrak,
                                'no_kontrak_customer' => $customerText,
                                'deskripsi' => $description,
                                'status' => 'Waiting Approval',
                                'job_position_full' => $jobPosition_full,
                                'project_name' => $projectName
                            ]);
                        } else if ($projectDetail == 'projectSODetail' && $soCustomerProject == '-') {
                            // dd($soCustomerProject);
                            $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                                'so_id' => "-",
                                'supplier_id' => "",
                                'po_number' => "",
                                'job_position' => $jobPosition,
                                'company_name' => $companyName,
                                'customer_name' => $customerName,
                                'tanggal_kontrak' => $effectiveDate,
                                'template_kontrak' => $contractTemplate,
                                'no_kontrak_compnet' => $new_no_kontrak,
                                'no_kontrak_customer' => $customerText,
                                'deskripsi' => $description,
                                'status' => 'Waiting Approval',
                                'job_position_full' => $jobPosition_full,
                                'project_name' => $projectName
                            ]);
                        } else if ($projectDetail !== 'projectSODetail' && $poNumberProject !== '-') {
                            $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                                'so_id' => "",
                                'supplier_id' => $poPrincipalProject,
                                'po_number' => $poNumberProject,
                                'job_position' => $jobPosition,
                                'company_name' => $companyName,
                                'customer_name' => $customerName,
                                'tanggal_kontrak' => $effectiveDate,
                                'template_kontrak' => $contractTemplate,
                                'no_kontrak_compnet' => $new_no_kontrak,
                                'no_kontrak_customer' => $customerText,
                                'deskripsi' => $description,
                                'status' => 'Waiting Approval',
                                'job_position_full' => $jobPosition_full,
                                'project_name' => $projectName
                            ]);
                        } else if ($projectDetail !== 'projectSODetail' && $poNumberProject == '-') {
                            $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                                'so_id' => "",
                                'supplier_id' => "",
                                'po_number' => "",
                                'job_position' => $jobPosition,
                                'company_name' => $companyName,
                                'customer_name' => $customerName,
                                'tanggal_kontrak' => $effectiveDate,
                                'template_kontrak' => $contractTemplate,
                                'no_kontrak_compnet' => $new_no_kontrak,
                                'no_kontrak_customer' => $customerText,
                                'deskripsi' => $description,
                                'status' => 'Waiting Approval',
                                'job_position_full' => $jobPosition_full,
                                'project_name' => $projectName
                            ]);
                        }
                    }
                } else if (in_array('numbercompnetDetail', $numberingBy) && !in_array('numbercustomerDetail', $numberingBy)) {
                    // dd($soNumberProject);
                    if ($projectDetail == 'projectSODetail' && $soCustomerProject !== '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => $soNumberProject,
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail == 'projectSODetail' && $soCustomerProject == '-') {
                        // dd($soCustomerProject);
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "-",
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail !== 'projectSODetail' && $poNumberProject !== '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => $poPrincipalProject,
                            'po_number' => $poNumberProject,
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail !== 'projectSODetail' && $poNumberProject == '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    }
                } else if (in_array('numbercustomerDetail', $numberingBy) && !in_array('numbercompnetDetail', $numberingBy)) {
                    if ($projectDetail == 'projectSODetail' && $soCustomerProject !== '-') {
                        // dd($soNumberProject);
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => $soNumberProject,
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            // 'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail == 'projectSODetail' && $soCustomerProject == '-') {
                        // dd($soCustomerProject);
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "-",
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            // 'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail !== 'projectSODetail' && $poNumberProject !== '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => $poPrincipalProject,
                            'po_number' => $poNumberProject,
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            // 'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail !== 'projectSODetail' && $poNumberProject == '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            // 'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    }
                }
            } else {
                if (in_array('numbercustomerDetail', $numberingBy) && in_array('numbercompnetDetail', $numberingBy)) {

                    if ($customerText == '-' && $compnetText == '-') {
                        // dd($compnetText);
                        // if ($soNumberProject != '-') {
                        // $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                        //     'so_id' => $soNumberProject,
                        //     'supplier_id' => "",
                        //     'po_number' => "",
                        //     'job_position' => $jobPosition,
                        //     'company_name' => $companyName,
                        //     'customer_name' => $customerName,
                        //     'tanggal_kontrak' => $effectiveDate,
                        //     'template_kontrak' => $contractTemplate,
                        //     'no_kontrak_compnet' => $emptyValue,
                        //     'no_kontrak_customer' => $customerText,
                        //     'deskripsi' => $description,
                        //     'status' => 'Waiting Approval',
                        //     'job_position_full' => $jobPosition_full,
                        //     'project_name' => $projectName
                        // ]);
                        // } else if ($poNumberProject != '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => $poPrincipalProject,
                            'po_number' => $poNumberProject,
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $emptyValue,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            // 'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                        // }
                    } else {
                        if ($projectDetail == 'projectSODetail' && $soCustomerProject !== '-') {
                            // dd($soNumberProject);
                            $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                                'so_id' => $soNumberProject,
                                'supplier_id' => "",
                                'po_number' => "",
                                'job_position' => $jobPosition,
                                'company_name' => $companyName,
                                'customer_name' => $customerName,
                                'tanggal_kontrak' => $effectiveDate,
                                'template_kontrak' => $contractTemplate,
                                'no_kontrak_compnet' => $new_no_kontrak,
                                'no_kontrak_customer' => $customerText,
                                'deskripsi' => $description,
                                // 'status' => 'Waiting Approval',
                                'job_position_full' => $jobPosition_full,
                                'project_name' => $projectName
                            ]);
                        } else if ($projectDetail == 'projectSODetail' && $soCustomerProject == '-') {
                            // dd($soCustomerProject);
                            $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                                'so_id' => "-",
                                'supplier_id' => "",
                                'po_number' => "",
                                'job_position' => $jobPosition,
                                'company_name' => $companyName,
                                'customer_name' => $customerName,
                                'tanggal_kontrak' => $effectiveDate,
                                'template_kontrak' => $contractTemplate,
                                'no_kontrak_compnet' => $new_no_kontrak,
                                'no_kontrak_customer' => $customerText,
                                'deskripsi' => $description,
                                // 'status' => 'Waiting Approval',
                                'job_position_full' => $jobPosition_full,
                                'project_name' => $projectName
                            ]);
                        } else if ($projectDetail !== 'projectSODetail' && $poNumberProject !== '-') {
                            $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                                'so_id' => "",
                                'supplier_id' => $poPrincipalProject,
                                'po_number' => $poNumberProject,
                                'job_position' => $jobPosition,
                                'company_name' => $companyName,
                                'customer_name' => $customerName,
                                'tanggal_kontrak' => $effectiveDate,
                                'template_kontrak' => $contractTemplate,
                                'no_kontrak_compnet' => $new_no_kontrak,
                                'no_kontrak_customer' => $customerText,
                                'deskripsi' => $description,
                                // 'status' => 'Waiting Approval',
                                'job_position_full' => $jobPosition_full,
                                'project_name' => $projectName
                            ]);
                        } else if ($projectDetail !== 'projectSODetail' && $poNumberProject == '-') {
                            $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                                'so_id' => "",
                                'supplier_id' => "",
                                'po_number' => "",
                                'job_position' => $jobPosition,
                                'company_name' => $companyName,
                                'customer_name' => $customerName,
                                'tanggal_kontrak' => $effectiveDate,
                                'template_kontrak' => $contractTemplate,
                                'no_kontrak_compnet' => $new_no_kontrak,
                                'no_kontrak_customer' => $customerText,
                                'deskripsi' => $description,
                                // 'status' => 'Waiting Approval',
                                'job_position_full' => $jobPosition_full,
                                'project_name' => $projectName
                            ]);
                        }
                    }
                } else if (in_array('numbercompnetDetail', $numberingBy) && !in_array('numbercustomerDetail', $numberingBy)) {
                    // dd($soNumberProject);
                    if ($projectDetail == 'projectSODetail' && $soCustomerProject !== '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => $soNumberProject,
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            // 'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail == 'projectSODetail' && $soCustomerProject == '-') {
                        // dd($soCustomerProject);
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "-",
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            // 'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail !== 'projectSODetail' && $poNumberProject !== '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => $poPrincipalProject,
                            'po_number' => $poNumberProject,
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            // 'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail !== 'projectSODetail' && $poNumberProject == '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            // 'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    }
                } else if (in_array('numbercustomerDetail', $numberingBy) && !in_array('numbercompnetDetail', $numberingBy)) {
                    if ($projectDetail == 'projectSODetail' && $soCustomerProject !== '-') {
                        // dd($soNumberProject);
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => $soNumberProject,
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            // 'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            // 'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail == 'projectSODetail' && $soCustomerProject == '-') {
                        // dd($soCustomerProject);
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "-",
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            // 'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            // 'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail !== 'projectSODetail' && $poNumberProject !== '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => $poPrincipalProject,
                            'po_number' => $poNumberProject,
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            // 'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            // 'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    } else if ($projectDetail !== 'projectSODetail' && $poNumberProject == '-') {
                        $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
                            'so_id' => "",
                            'supplier_id' => "",
                            'po_number' => "",
                            'job_position' => $jobPosition,
                            'company_name' => $companyName,
                            'customer_name' => $customerName,
                            'tanggal_kontrak' => $effectiveDate,
                            'template_kontrak' => $contractTemplate,
                            // 'no_kontrak_compnet' => $new_no_kontrak,
                            'no_kontrak_customer' => $customerText,
                            'deskripsi' => $description,
                            // 'status' => 'Waiting Approval',
                            'job_position_full' => $jobPosition_full,
                            'project_name' => $projectName
                        ]);
                    }
                }
            }


            // if (in_array('numbercustomerDetail', $numberingBy) && in_array('numbercompnetDetail', $numberingBy)) {
            //     if ($customerText !== '-' && $compnetText !== '-') {
            //         if ($soNumberProject != '-') {
            //             $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //                 'so_id' => $soNumberProject,
            //                 'supplier_id' => "",
            //                 'po_number' => "",
            //                 'job_position' => $jobPosition,
            //                 'company_name' => $companyName,
            //                 'customer_name' => $customerName,
            //                 'tanggal_kontrak' => $effectiveDate,
            //                 'template_kontrak' => $contractTemplate,
            //                 'no_kontrak_compnet' => $new_no_kontrak,
            //                 'no_kontrak_customer' => $customerText,
            //                 'deskripsi' => $description,
            //                 'status' => 'Waiting Approval',
            //                 'job_position_full' => $jobPosition_full,
            //                 'project_name' => $projectName
            //             ]);
            //         } else if ($poNumberProject != '-') {
            //             $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //                 'so_id' => "",
            //                 'supplier_id' => $poPrincipalProject,
            //                 'po_number' => $poNumberProject,
            //                 'job_position' => $jobPosition,
            //                 'company_name' => $companyName,
            //                 'customer_name' => $customerName,
            //                 'tanggal_kontrak' => $effectiveDate,
            //                 'template_kontrak' => $contractTemplate,
            //                 'no_kontrak_compnet' => $new_no_kontrak,
            //                 'no_kontrak_customer' => $customerText,
            //                 'deskripsi' => $description,
            //                 'status' => 'Waiting Approval',
            //                 'job_position_full' => $jobPosition_full,
            //                 'project_name' => $projectName
            //             ]);
            //         }
            //     } else {
            //         if ($soNumberProject != '-') {
            //             $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //                 'so_id' => $soNumberProject,
            //                 'supplier_id' => "",
            //                 'po_number' => "",
            //                 'job_position' => $jobPosition,
            //                 'company_name' => $companyName,
            //                 'customer_name' => $customerName,
            //                 'tanggal_kontrak' => $effectiveDate,
            //                 'template_kontrak' => $contractTemplate,
            //                 'no_kontrak_compnet' => $emptyValue,
            //                 'no_kontrak_customer' => $emptyValue,
            //                 'deskripsi' => $description,
            //                 'status' => 'Waiting Approval',
            //                 'job_position_full' => $jobPosition_full,
            //                 'project_name' => $projectName
            //             ]);
            //         } else if ($poNumberProject != '-') {
            //             $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //                 'so_id' => "",
            //                 'supplier_id' => $poPrincipalProject,
            //                 'po_number' => $poNumberProject,
            //                 'job_position' => $jobPosition,
            //                 'company_name' => $companyName,
            //                 'customer_name' => $customerName,
            //                 'tanggal_kontrak' => $effectiveDate,
            //                 'template_kontrak' => $contractTemplate,
            //                 'no_kontrak_compnet' => $emptyValue,
            //                 'no_kontrak_customer' => $emptyValue,
            //                 'deskripsi' => $description,
            //                 'status' => 'Waiting Approval',
            //                 'job_position_full' => $jobPosition_full,
            //                 'project_name' => $projectName
            //             ]);
            //         }
            //     }
            // } else if (in_array('numbercompnetDetail', $numberingBy) && !in_array('numbercustomerDetail', $numberingBy)) {
            //     if ($soNumberProject != '-') {
            //         $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //             'so_id' => $soNumberProject,
            //             'supplier_id' => "",
            //             'po_number' => "",
            //             'job_position' => $jobPosition,
            //             'company_name' => $companyName,
            //             'customer_name' => $customerName,
            //             'tanggal_kontrak' => $effectiveDate,
            //             'template_kontrak' => $contractTemplate,
            //             'no_kontrak_compnet' => $new_no_kontrak,
            //             'no_kontrak_customer' => $customerText,
            //             'deskripsi' => $description,
            //             'status' => 'Waiting Approval',
            //             'job_position_full' => $jobPosition_full,
            //             'project_name' => $projectName
            //         ]);
            //     } else if ($poNumberProject != '-') {
            //         $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //             'so_id' => "",
            //             'supplier_id' => $poPrincipalProject,
            //             'po_number' => $poNumberProject,
            //             'job_position' => $jobPosition,
            //             'company_name' => $companyName,
            //             'customer_name' => $customerName,
            //             'tanggal_kontrak' => $effectiveDate,
            //             'template_kontrak' => $contractTemplate,
            //             'no_kontrak_compnet' => $new_no_kontrak,
            //             'no_kontrak_customer' => $customerText,
            //             'deskripsi' => $description,
            //             'status' => 'Waiting Approval',
            //             'job_position_full' => $jobPosition_full,
            //             'project_name' => $projectName
            //         ]);
            //     }
            // } else if (in_array('numbercustomerDetail', $numberingBy) && !in_array('numbercompnetDetail', $numberingBy)) {
            //     if ($soNumberProject != '-') {
            //         $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //             'so_id' => $soNumberProject,
            //             'supplier_id' => "",
            //             'po_number' => "",
            //             'job_position' => $jobPosition,
            //             'company_name' => $companyName,
            //             'customer_name' => $customerName,
            //             'tanggal_kontrak' => $effectiveDate,
            //             'template_kontrak' => $contractTemplate,
            //             'no_kontrak_compnet' => $emptyValue,
            //             'no_kontrak_customer' => $customerText,
            //             'deskripsi' => $description,
            //             'status' => 'Waiting Approval',
            //             'job_position_full' => $jobPosition_full,
            //             'project_name' => $projectName
            //         ]);
            //     } else if ($poNumberProject != '-') {
            //         $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //             'so_id' => "",
            //             'supplier_id' => $poPrincipalProject,
            //             'po_number' => $poNumberProject,
            //             'job_position' => $jobPosition,
            //             'company_name' => $companyName,
            //             'customer_name' => $customerName,
            //             'tanggal_kontrak' => $effectiveDate,
            //             'template_kontrak' => $contractTemplate,
            //             'no_kontrak_compnet' => $emptyValue,
            //             'no_kontrak_customer' => $customerText,
            //             'deskripsi' => $description,
            //             'status' => 'Waiting Approval',
            //             'job_position_full' => $jobPosition_full,
            //             'project_name' => $projectName
            //         ]);
            //     }
            // }
            // else if (!in_array('numbercustomerDetail', $numberingBy) && !in_array('numbercompnetDetail', $numberingBy)) {

            //     if ($soNumberProject != '-') {
            //         $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //             'so_id' => $soNumberProject,
            //             'supplier_id' => "",
            //             'po_number' => "",
            //             'job_position' => $jobPosition,
            //             'company_name' => $companyName,
            //             'customer_name' => $customerName,
            //             'tanggal_kontrak' => $effectiveDate,
            //             'template_kontrak' => $contractTemplate,
            //             'deskripsi' => $description,
            //             'status' => 'Waiting Approval',
            //             'job_position_full' => $jobPosition_full,
            //             'project_name' => $projectName

            //         ]);
            //     } else if ($poNumberProject != '-') {
            //         $updatePenomoranKontrak = PenomoranKontrak::query()->where('id', '=', $id)->update([
            //             'so_id' => "",
            //             'supplier_id' => $poPrincipalProject,
            //             'po_number' => $poNumberProject,
            //             'job_position' => $jobPosition,
            //             'company_name' => $companyName,
            //             'customer_name' => $customerName,
            //             'tanggal_kontrak' => $effectiveDate,
            //             'template_kontrak' => $contractTemplate,
            //             'deskripsi' => $description,
            //             'status' => 'Waiting Approval',
            //             'job_position_full' => $jobPosition_full,
            //             'project_name' => $projectName
            //         ]);
            //     }
            // }

            $queryAfter =  "SELECT * from penomoran_kontrak WHERE id = '" . $id . "' LIMIT 1";
            $queAfter = $legalprod->select($queryAfter);

            $new_so_id = $queAfter[0]->so_id;
            $new_supplier_id = $queAfter[0]->supplier_id;
            $new_po_number = $queAfter[0]->po_number;
            $new_description = $queAfter[0]->deskripsi;

            $log_so_id = $old_so_id == $new_so_id ? "" : "<br> - SO Number : Changed From " . $old_so_id . " To " . $new_so_id;
            $log_supplier_id = $old_supplier_id == $new_supplier_id ? "" : "<br> - ID Supplier : Changed From " . $old_supplier_id . " To " . $new_supplier_id;
            $log_po_number = $old_po_number == $new_po_number ? "" : "<br> - PO Number : Changed From " . $old_po_number . " To " . $new_po_number;
            $log_description = $old_description == $new_description ? "" : "<br> - Description : Changed From " . $old_description . " To " . $new_description;
            $log_file_name = $old_file_name == $new_file_name ? "" : "<br> - File Uploaded : Changed From " . $old_file_name . " To " . $new_file_name;

            // $updateStatusApprover = Approver::query()->where('pk_id', '=', $id)->update([
            //     'status_approval' => 'Waiting',
            //     'tanggal_approval' => '-',
            //     'notes' => '-'
            // ]);

            $searchapprover = Approver::where('pk_id', $id)->where('status_approval', 'Rejected')->first();

            if ($searchapprover) {
                $approverid = $searchapprover->id;
                $updateStatusApprover = Approver::query()->where('id', '=', $approverid)->update([
                    'status_approval' => 'Waiting',
                    'tanggal_approval' => '-'
                    // 'notes' => '-'
                ]);
            }

            $log = auth()->user()->name . ' Edited Penomoran Kontrak Data';
            $log .= "<br> - ID : " . $id;
            $log .= $log_so_id;
            $log .= $log_supplier_id;
            $log .= $log_po_number;
            $log .= $log_po_number;
            $log .= $log_description;
            $log .= $log_file_name;

            LogActivityController::add($log, 'penomoran_kontrak, penomoran_kontrak_attachment', 'UPDATE');

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

    public function saveEditApprovers(Request $req, $id)
    {
        try {
            $legalprod = DB::connection('mysql');

            $jenisapprover = $req->input('jenisapproverDetail', []);
            $jenisapprovername = $req->input('jenisapprovernameDetail', []);
            $jenisapproveremail = $req->input('jenisapproveremailDetail', []);

            $data = PenomoranKontrak::query()->where('id', '=', $id)->with(['approver'])->first();

            $approver = Approver::where('pk_id', $id)->first();

            $queryNoKontrak = "SELECT * FROM penomoran_kontrak pk WHERE id = '" . $id . "'";
            $queNoKontrak = $legalprod->select($queryNoKontrak);

            $no_kontrak_compnet = $queNoKontrak[0]->no_kontrak_compnet ?? '';
            $no_kontrak_customer = $queNoKontrak[0]->no_kontrak_customer ?? '';

            $customerName = $queNoKontrak[0]->customer_name;
            $soNumber = $queNoKontrak[0]->so_id;
            $poNumber = $queNoKontrak[0]->po_number;

            if (!$soNumber && $poNumber) {
                $projectNumber = $poNumber;
            } else if (!$poNumber && $soNumber) {
                $projectNumber = $soNumber;
            }

            // $no_kontrak = trim($no_kontrak_compnet . ($no_kontrak_customer ? ' | ' . $no_kontrak_customer : ''));

            $no_kontrak = '';

            if ($no_kontrak_customer && $no_kontrak_compnet) {
                $no_kontrak = $no_kontrak_compnet . ' | ' . $no_kontrak_customer;
            } elseif ($no_kontrak_customer) {
                $no_kontrak = $no_kontrak_customer;
            } elseif ($no_kontrak_compnet) {
                $no_kontrak = $no_kontrak_compnet;
            }

            $no_kontrak = trim($no_kontrak);

            // Memeriksa apakah data ditemukan
            $attachmentID = $approver->attachment_id;

            $totalAppover = count($jenisapprover);
            if ($totalAppover > 0) {

                $existingApproversData = Approver::where('pk_id', $id)
                    ->get(['employee_id', 'approver_level', 'approver_name'])
                    ->keyBy('approver_level')
                    ->toArray();

                $newApproversData = [];

                $approverLevel = 0;

                // Step 1: Fetch existing approvers before the update
                $existingApprovers = Approver::where('pk_id', $id)->pluck('employee_id')->toArray();

                // Step 2: Initialize an array to keep track of new approvers
                $newApprovers = [];

                for ($i = 0; $i < $totalAppover; $i++) {
                    $approverLevel++;

                    // Update atau buat approver
                    Approver::updateOrCreate(
                        [
                            'pk_id' => $id,
                            'employee_id' => $jenisapprover[$i],
                            'attachment_id' => $attachmentID
                        ],
                        [
                            'approver_email' => $jenisapproveremail[$i],
                            'approver_name' => $jenisapprovername[$i],
                            'approver_level' => $approverLevel,
                            'status_approval' => 'Waiting'
                        ]
                    );

                    // Build new approvers data
                    $newApproversData[$approverLevel] = [
                        'employee_id' => $jenisapprover[$i],
                        'approver_level' => $approverLevel,
                        'approver_name' => $jenisapprovername[$i]
                    ];

                    // Pemeriksaan duplikat
                    $duplicates = Approver::query()
                        ->where('pk_id', '=', $id)
                        ->where('employee_id', '=', $jenisapprover[$i])
                        ->orderBy('approver_level', 'desc')
                        ->get();

                    if ($duplicates->count() > 1) {
                        $duplicates->slice(1)->each(function ($duplicate) {
                            $duplicate->delete();
                        });
                    }

                    $messageNotifyApprovers = 'Dear Bapak/Ibu ' . $jenisapprovername[$i] . ',<br><br>' .
                        'You have been reassigned as level ' . $approverLevel . ' approver for the contract with the following details.' . '<br><br>' .
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
                        'Approval can be made through the following link:  <a href="' . url('approver/listApproval') . '">Link Approval</a>.' . '<br><br><br>' .
                        'Thank you.';

                    // Kirim email ke approver saat ini
                    Mail::to($jenisapproveremail[$i])->send(new DocumentApprovedMail($messageNotifyApprovers));

                    // Step 3: Add to new approvers list
                    $newApprovers[] = $jenisapprover[$i];
                }

                // Step 4: Identify and notify removed approvers
                $removedApprovers = array_diff($existingApprovers, $newApprovers);

                if (!empty($removedApprovers)) {
                    // Get details of removed approvers
                    $removedApproverDetails = Approver::where('pk_id', $id)
                        ->whereIn('employee_id', $removedApprovers)
                        ->get(['employee_id', 'approver_email', 'approver_name']);

                    foreach ($removedApproverDetails as $removedApprover) {
                        $messageNotifyRemovedApprover = 'Dear Bapak/Ibu ' . $removedApprover->approver_name . ',<br><br>' .
                            'You have been removed from the approver list for the contract with the following details.' . '<br><br>' .
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

                        // Send email to removed approver
                        Mail::to($removedApprover->approver_email)->send(new DocumentApprovedMail($messageNotifyRemovedApprover));
                    }

                    // Step 5: Delete the removed approvers from the database
                    Approver::where('pk_id', $id)
                        ->whereIn('employee_id', $removedApprovers)
                        ->delete();
                }

                // Now, create the log
                $log = auth()->user()->name . ' Edited Approver Data';
                $log .= "<br> - ID Penomoran Kontrak: " . $id;

                // Get all approver levels
                $allApproverLevels = array_unique(array_merge(array_keys($existingApproversData), array_keys($newApproversData)));

                // Loop through all levels to compare old and new approvers
                foreach ($allApproverLevels as $level) {
                    $oldApprover = isset($existingApproversData[$level]) ? $existingApproversData[$level]['approver_name'] : null;
                    $newApprover = isset($newApproversData[$level]) ? $newApproversData[$level]['approver_name'] : null;

                    if ($oldApprover !== $newApprover) {
                        if ($oldApprover && $newApprover) {
                            // Approver changed from old to new at this level
                            $log .= "<br> - Approver Name at Level " . $level . ": Changed From " . $oldApprover . " To " . $newApprover;
                        } elseif ($oldApprover && !$newApprover) {
                            // Approver at this level was removed
                            $log .= "<br> - Approver Name at Level " . $level . ": " . $oldApprover . " Has Been Removed From Approval";
                        } elseif (!$oldApprover && $newApprover) {
                            // New approver added at this level
                            $log .= "<br> - Approver Name at Level " . $level . ": " . $newApprover . " Has Been Added To Approval";
                        }
                    }
                }

                // Log the activity
                LogActivityController::add($log, 'approver', 'UPDATE');
            } else {
                // Opsional: Jika tidak ada approver, hapus semua approver terkait
                Approver::where('pk_id', $id)
                    ->where('attachment_id', $attachmentID)
                    ->delete();
            }

            // $log = auth()->user()->name . ' Edited Approver Data';
            // $log .= "<br> - ID Penomoran Kontrak: " . $id;
            // for ($i = 0; $i < (ini harus sesuai sama jumlah perubahan); $i++){
            //     $log .= "<br> - Approver Employe ID : Changed From " . (ini untuk approver lama). "To ". (ini untuk approver baru) ."";
            // }

            // LogActivityController::add($log, 'approver', 'UPDATE');

            return response()->json([
                'data' => $data,
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

    public function print($id)
    {
        try {
            // $data = PenomoranKontrak::query()->where('id', '=', $id)->with(['approver'])->first();
            $data = PenomoranKontrak::query()->where('id', '=', $id)->with(['approver' => function ($query) {
                $query->orderBy('approver_level', 'asc');
            }])->first();
            return view('print.print', compact('data'));
        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }
}
