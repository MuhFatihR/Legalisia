<?php

namespace App\Http\Controllers\FinalKontrak;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LogActivityController;

use App\Models\PenomoranKontrak;
use App\Models\PenomoranFinalKontrak;
use App\Models\PenomoranFinalKontrakAttachment;
use Exception;

class ListPenomoranRegisterFinalKontrakController extends Controller{
    public function viewList(){
        return view('finalkontrak/listPenomoranRegisterFinalKontrak');
    }

    public function searchData(Request $req){
        try {
            $legalprod = DB::connection('mysql');

            $format_penomoran = $req->input('no_format_penomoran_filter');
            $tanggal_kontrak = $req->input('tanggal_kontrak_filter');
            $no_kontrak_compnet = $req->input('no_kontrak_compnet_filter');
            $no_kontrak_customer = $req->input('no_kontrak_customer_filter');
            $customer_name = $req->input('customer_name_filter');
            $job_position = $req->input('job_position_filter');
            $company_name = $req->input('company_name_filter');
            $so_number = $req->input('so_number_filter');
            $currency = $req->input('currency_filter');
            $amount = $req->input('amount_filter');

            $where = [];

            if (!empty($format_penomoran)) {
                $where[] = "final_format LIKE '%" . $format_penomoran . "%' ";
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
            if (!empty($company_name)) {
                $where[] = "company_name_full LIKE '%" . $company_name . "%' ";
            }
            if (!empty($so_number)) {
                $where[] = "so_id LIKE '%" . $so_number . "%' ";
            }
            if (!empty($currency)) {
                $where[] = "currency_full LIKE '%" . $currency . "%' ";
            }
            if (!empty($amount)) {
                $where[] = "amount LIKE '%" . $amount . "%' ";
            }

            $where_clause = '';
            if (!empty($where)) {
                $where_clause = ' WHERE ' . implode(' AND ', $where);
            }

            $queSearch = "SELECT * FROM penomoran_final_kontrak" . $where_clause;
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

                if ($datakontrak->company_name == 'NCI') {
                    $companyName = 'PT. Nusantara Compnet Integrator';
                } else if ($datakontrak->company_name == 'IOT') {
                    $companyName = 'PT. Inovasi Otomasi Teknologi';
                } else if ($datakontrak->company_name == 'PSA') {
                    $companyName = 'PT. Pro Sistimatika Automasi';
                } else if ($datakontrak->company_name == 'SJT') {
                    $companyName = 'PT. Sugi Jaya Teknologi';
                } else if ($datakontrak->company_name == 'CIS') {
                    $companyName = 'PT. Compnet Integrator Services';
                }

                if ($datakontrak->no_kontrak_customer && $datakontrak->no_kontrak_compnet) {
                    $no_kontrak = $datakontrak->no_kontrak_compnet . ' | ' . $datakontrak->no_kontrak_customer;
                } else if ($datakontrak->no_kontrak_compnet) {
                    $no_kontrak = $datakontrak->no_kontrak_compnet;
                } else if ($datakontrak->no_kontrak_customer) {
                    $no_kontrak = $datakontrak->no_kontrak_customer;
                }

                $row = array();
                $row[] = $datakontrak->final_format ? $datakontrak->final_format : '-';
                $row[] = $datakontrak->no_kontrak_compnet ? $datakontrak->no_kontrak_compnet : '-';
                $row[] = $datakontrak->no_kontrak_customer ? $datakontrak->no_kontrak_customer : '-';
                $row[] = $datakontrak->so_id;
                $row[] = $datakontrak->tanggal_kontrak;
                $row[] = $companyName;
                $row[] = $jobPosition;
                $row[] = $datakontrak->customer_name;
                $row[] = $datakontrak->currency_full;
                $row[] = $datakontrak->amount;
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

    public function insertData(Request $req){
        DB::connection('mysql')->beginTransaction();

        try {
            $legalprod = DB::connection('mysql');

            $approved = $req->input('approved');
            $inputted_company = $req->input('companyName');
            $currency_id = $req->input('currency');
            $amount = $req->input('amount');

            $fileUpload = $req->file('fileUpload');
            if ($fileUpload) {
                $originalFileName = $fileUpload->getClientOriginalName();
                $path = public_path('penomoranfinalkontrak');
                $fileUpload->move($path, $originalFileName);
            } else {
                return response()->json([
                    'message' => 'No files uploaded!'
                ]);
            }

            $querySelect = "SELECT * FROM penomoran_kontrak WHERE id = '" . $approved . "' ";
            $que = $legalprod->select($querySelect);

            $so_id = $que[0]->so_id;
            $supplier_id = $que[0]->supplier_id;
            $po_number = $que[0]->po_number;
            $job_position = $que[0]->job_position;
            $job_position_full = $que[0]->job_position_full;
            $company_name = $que[0]->company_name;
            $no_urut = $que[0]->no_urut;
            $customer_name = $que[0]->customer_name;
            $tanggal_kontrak = $que[0]->tanggal_kontrak;
            $template_kontrak = $que[0]->template_kontrak;
            $no_kontrak_compnet = $que[0]->no_kontrak_compnet;
            $no_kontrak_customer = $que[0]->no_kontrak_customer;
            $nama_uploader = $que[0]->nama_uploader;
            $deskripsi = $que[0]->deskripsi;


            if ($inputted_company == 'NCI') {
                $company_name_full = 'PT. Nusantara Compnet Integrator';
            } else if ($inputted_company == 'IOT') {
                $company_name_full = 'PT. Inovasi Otomasi Teknologi';
            } else if ($inputted_company == 'PSA') {
                 $company_name_full = 'PT. Pro Sistimatika Automasi';
            } else if ($inputted_company == 'SJT') {
                 $company_name_full = 'PT. Sugi Jaya Teknologi';
            } else if ($inputted_company == 'CIS') {
                 $company_name_full = 'PT. Compnet Integrator Services';
            }

            if($currency_id == '1'){
                $currency_full = 'IDR';
            } else if($currency_id == '2'){
                $currency_full = 'USD';
            } else if($currency_id == '3'){
                $currency_full = 'JPN';
            } else if($currency_id == '4'){
                $currency_full = 'SGD';
            }

            $idPenomoranKontrak = $req->input('idPenomoranKontrak');
            // UPDATE STATUS "used" pada penomoran_kontrak
            PenomoranKontrak::query()->where('id', '=', $idPenomoranKontrak)->update([
                'used' => '1'
            ]);

            // VALIDASI PENOMORAN
            $currentYear = now()->year;
            $tahunFormat = $currentYear - 2017;
            $companyFormat = substr($inputted_company, 0, 1);
            $incrementNumber = '0001';

            $queLastFinalFormat = "SELECT MAX(final_format) as last_final_format FROM penomoran_final_kontrak WHERE company_name = '" . $inputted_company . "'"; // dikasi kondisi untuk perbedaan Company nomor incrementnya di reset
            $queGetLastFinalFormat = $legalprod->select($queLastFinalFormat);
            // dd($queGetLastFinalFormat);
            $lastFinalFormat = $queGetLastFinalFormat[0]->last_final_format;
            // dd($lastFinalFormat);
            if (!$lastFinalFormat) {
                // dd("lastFinalFormat: " . $lastFinalFormat);
                $incrementNumber = $incrementNumber;
            } else {
                $lastTahunFormat = substr($lastFinalFormat, 1, strlen($tahunFormat));

                if ($lastTahunFormat == $tahunFormat) {
                    $number_part = intval(substr($lastFinalFormat, -4));
                    $number_part += 1;
                    $incrementNumber = str_pad($number_part, 4, '0', STR_PAD_LEFT);
                    // dd($incrementNumber);
                } else {
                    $incrementNumber = $incrementNumber;
                }
            }
            $final_format = $companyFormat . $tahunFormat . $incrementNumber;

            PenomoranFinalKontrak::create([
                'so_id' => $so_id,
                'supplier_id' => $supplier_id,
                'po_number' => $po_number,
                'job_position' => $job_position,
                'company_name' => $inputted_company,
                'no_urut' => $no_urut,
                'customer_name' => $customer_name,
                'tanggal_kontrak' => $tanggal_kontrak,
                'template_kontrak' => $template_kontrak,
                'no_kontrak_customer' => $no_kontrak_customer,
                'no_kontrak_compnet' => $no_kontrak_compnet,
                'nama_uploader' => $nama_uploader,
                'deskripsi' => $deskripsi,
                'final_format' => $final_format,
                'job_position_full' => $job_position_full,
                'company_name_full' => $company_name_full,
                'currency_id' => $currency_id,
                'amount' => $amount,
                'currency_full' => $currency_full,
            ]);
            $penomoranKontrakID = DB::getPdo()->lastInsertId();

            PenomoranFinalKontrakAttachment::create([
                'pk_id' => $penomoranKontrakID,
                'path' => $path,
                'file_name' => $originalFileName
            ]);

            $log = auth()->user()->name . ' Added New Final Numbering ';
            // $log .= "<br> - ID : " . $penomoranKontrakID;
            $log .= "<br> - Number by Compnet : " . $no_kontrak_compnet;
            $log .= "<br> - Number by Customer : " . $no_kontrak_customer;

            LogActivityController::add($log, 'penomoran_final_kontrak, penomoran_final_kontrak_attachment', 'INSERT');

            DB::connection('mysql')->commit();
            return response()->json([
                'code' => 200,
                'message' => 'Insert Success!'
            ]);
        } catch (Exception $ex) {
            DB::connection('mysql')->rollback();

            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function getList(){
        try {
            $legalprod = DB::connection('mysql');

            $uploadName = auth()->user()->name;

            $query = "SELECT * FROM penomoran_final_kontrak pk WHERE nama_uploader = '" . $uploadName . "'";
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

                if ($datakontrak->company_name == 'NCI') {
                    $companyName = 'PT. Nusantara Compnet Integrator';
                } else if ($datakontrak->company_name == 'IOT') {
                    $companyName = 'PT. Inovasi Otomasi Teknologi';
                } else if ($datakontrak->company_name == 'PSA') {
                    $companyName = 'PT. Pro Sistimatika Automasi';
                } else if ($datakontrak->company_name == 'SJT') {
                    $companyName = 'PT. Sugi Jaya Teknologi';
                } else if ($datakontrak->company_name == 'CIS') {
                    $companyName = 'PT. Compnet Integrator Services';
                }

                if ($datakontrak->no_kontrak_customer && $datakontrak->no_kontrak_compnet) {
                    $no_kontrak = $datakontrak->no_kontrak_compnet . ' | ' . $datakontrak->no_kontrak_customer;
                } else if ($datakontrak->no_kontrak_compnet) {
                    $no_kontrak = $datakontrak->no_kontrak_compnet;
                } else if ($datakontrak->no_kontrak_customer) {
                    $no_kontrak = $datakontrak->no_kontrak_customer;
                }

                $row = array();
                $row[] = $datakontrak->final_format ? $datakontrak->final_format : '-';
                $row[] = $datakontrak->no_kontrak_compnet ? $datakontrak->no_kontrak_compnet : '-';
                $row[] = $datakontrak->no_kontrak_customer ? $datakontrak->no_kontrak_customer : '-';
                $row[] = $datakontrak->so_id;
                $row[] = $datakontrak->tanggal_kontrak;
                $row[] = $companyName;
                $row[] = $jobPosition;
                $row[] = $datakontrak->customer_name;
                $row[] = $datakontrak->currency_full;
                $row[] = $datakontrak->amount;
                $row[] = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPenomoranKontrak" id="' . $datakontrak->id . '" data_name="' . $datakontrak->final_format . '" onclick="modalShow(this)">Detail</button>';
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

    public function getdetail($id){
        try {
            $data = PenomoranFinalKontrak::query()->where('id', '=', $id)->with(['penomoran_final_kontrak_attachment'])->first();

            return response()->json($data);

        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function saveEdit(Request $req, $id){
        try {
            $legalprod = DB::connection('mysql');

            // Get the existing PenomoranKontrak record
            $penomoranFinalKontrak = PenomoranFinalKontrak::find($id);
            if (!$penomoranFinalKontrak) {
                return response()->json([
                    'code' => 404,
                    'message' => 'PenomoranKontrak not found'
                ], 404);
            }

            $attachmentBefore = PenomoranFinalKontrakAttachment::where('pk_id', $id)->first();
            $old_file_name = $attachmentBefore->file_name;

            $fileUpload = $req->file('fileInputDetail');
            if ($fileUpload) {
                $originalFileName = $fileUpload->getClientOriginalName();
                $path = public_path('penomoranfinalkontrak');
                $fileUpload->move($path, $originalFileName);
                PenomoranFinalKontrakAttachment::query()->where('pk_id', '=', $id)->update([
                    'path' => $path,
                    'file_name' => $originalFileName
                ]);
            }

            $attachmentAfter = PenomoranFinalKontrakAttachment::where('pk_id', $id)->first();
            $new_file_name = $attachmentAfter->file_name;

            $log_file_name = $old_file_name == $new_file_name ? "" : "<br> - File Uploaded : Changed From " . $old_file_name . " To " . $new_file_name;

            $log = auth()->user()->name . ' Edited Penomoran Final Kontrak Data';
            $log .= "<br> - ID : " . $id;
            $log .= $log_file_name;

            LogActivityController::add($log, 'penomoran_final_kontrak, penomoran_final_kontrak_attachment', 'UPDATE');

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
