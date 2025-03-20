@extends('layouts.app', [
    'title' => 'Portal Legal',
    'pageTitle' => 'Penomoran Kontrak',
])

@section('content')
    <?php
    use Illuminate\Support\Facades\DB;

    $crmprod = DB::connection('mysqlSUGARCRM');
    $fapgprod = DB::connection('mysqlFAPG');
    $hrdprod = DB::connection('mysqlHRD');
    $legalprod = DB::connection('mysql');

    $uploadName = auth()->user()->name;

    ?>

    {{-- Filter --}}
    <div class="content">
        <div class="container-fluid" display="none">
            <div class="card">
                <div class="card-header pb-1">
                    <div class="row mb-2">
                        <div class="col-12">
                            <h4 class="">
                                FILTER
                            </h4>
                            <hr class="border-bottom border-0 border-dark">
                        </div>
                    </div>
                </div>

                <div class="card-block" id="filterPenomoranKontrak">
                    <form action="" method="" id="report">
                        <div class="row">
                            <div class="col-sm-12">
                                {{-- Row 1 --}}
                                <div class="form-group row">
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="no_format_penomoran_filter">Format Penomoran</Title></label>
                                        <input type="text" id="no_format_penomoran_filter"
                                            name="no_format_penomoran_filter" class="form-control input-sm"
                                            placeholder="Search Format Penomoran" autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="no_kontrak_compnet_filter">No Kontrak Compnet</Title></label>
                                        <input type="text" id="no_kontrak_compnet_filter"
                                            name="no_kontrak_compnet_filter" class="form-control input-sm"
                                            placeholder="Search No Kontrak Compnet" autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="no_kontrak_customer_filter">No Kontrak Customer</Title></label>
                                        <input type="text" id="no_kontrak_customer_filter"
                                            name="no_kontrak_customer_filter" class="form-control input-sm"
                                            placeholder="Search No Kontrak Customer" autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="so_number_filter">So Number</Title></label>
                                        <input type="text" id="so_number_filter" name="so_number_filter"
                                            class="form-control input-sm" placeholder="Search SO Number" autocomplete="off">
                                    </div>
                                </div>

                                {{-- Row 2 --}}
                                <div class="form-group row">
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="tanggal_kontrak_filter">Effective Date</Title></label>
                                        <input type="date" id="tanggal_kontrak_filter"
                                            name="tanggal_kontrak_filter"class="form-control input-sm date datetimepicker"
                                            type="text" data-min-view="2" data-date-format="yyyy-mm-dd"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="company_name_filter">Company Name</Title></label>
                                        <input type="text" id="company_name_filter" name="company_name_filter"
                                            class="form-control input-sm" placeholder="Search Company Name"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="job_position_filter">Job Category</Title></label>
                                        <input type="text" id="job_position_filter" name="job_position_filter"
                                            class="form-control input-sm" placeholder="Search Job Category"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="customer_name_filter">Customer/Partner</Title></label>
                                        <input type="text" id="customer_name_filter" name="customer_name_filter"
                                            class="form-control input-sm" placeholder="Search Customer/Partner"
                                            autocomplete="off">
                                    </div>
                                </div>

                                {{-- Row 3 --}}
                                <div class="form-group row">
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="currency_filter">Currency</Title></label>
                                        <input type="text" id="currency_filter" name="currency_filter"
                                            class="form-control input-sm" placeholder="Search Currency" autocomplete="off">
                                    </div>

                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="amount_filter">Amount</Title></label>
                                        <input type="text" id="amount_filter" name="amount_filter"
                                            class="form-control input-sm" placeholder="Search Amount" autocomplete="off">
                                    </div>

                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="submitSearch">&nbsp;</label>
                                        <button id="submitSearch" name="submitSearch"
                                            class="btn btn-space btn-primary form-control input-sm"
                                            type="button">Go</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="container-fluid pb-5" id="reportPenomoranKontrakResult">
            <div class="card header-info-filter">
                <div class="card-body ">
                    <div class="button-row" style="display: flex; justify-content:end; gap: 10px;">
                        <button id="excelButton" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-file-excel me-0"></i> Excel
                        </button>
                        <button id="btnAddRecord" type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#modalPenomoranKontrak">Add File Penomoran Final
                        </button>
                    </div>

                    <table id="reportPenomoranKontrakTable" width="100%" class="table table-striped nowrap">
                        <thead class="mt-30">
                            <tr>
                                <th scope="col">Format Penomoran</th>
                                <th scope="col">No Kontrak Compnet</th>
                                <th scope="col">No Kontrak Customer</th>
                                <th scope="col">SO Number</th>
                                <th scope="col">Tanggal Kontrak</th>
                                <th scope="col">Company Name</th>
                                <th scope="col">Job Category</th>
                                <th scope="col">Customer/Partner</th>
                                <th scope="col">Currency</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>

    </div>

    {{-- Modal Add --}}
    <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAdd" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-xl" id="modalAddSize">
            <div class="modal-content d-flex">
                <div class="modal-header d-flex">
                    <h5 class="modal-title fw-bold" id="modalPenomoranKontrakLabelAdd">Upload New File Contract</h5>
                    <input type="hidden" id="iditem" name="iditem" value="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close"></button>
                </div>
                <div class="modal-body">
                    <div class="py-2 d-flex justify-content-between"></div>

                    <form action="" method="" id="reportAdd">
                        {{ csrf_field() }}
                        {{-- CompanyName & No Kontrak --}}
                        <div class="row mb-3">
                            <div class="col-md-6 col-xs-12">
                                <label for="">Company Name </label>
                                <select name="companyName" id="companyName" class="select2"
                                    data-placeholder="Select Company">
                                    <option value="" disabled selected> Select Company </option>
                                    <option value="NCI"> PT. Nusantara Compnet Integrator </option>
                                    <option value="IOT"> PT. Inovasi Otomasi Teknologi </option>
                                    <option value="PSA"> PT. Pro Sistimatika Automasi </option>
                                    <option value ="SJT"> PT. Sugi Jaya Teknologi </option>
                                    <option value="CIS"> PT. Compnet Integrator Services </option>
                                </select>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <label for="">No Kontrak (Approved) </label>
                                <select name="approved" id="approved" class="select2"
                                    ata-placeholder="Select No Kontrak">
                                    <option value="" disabled selected> </option>
                                    <?php
                                    $uploaderName = auth()->user()->name;
                                    $getApprovedNoKontrak =
                                        "SELECT *
                                            FROM penomoran_kontrak
                                            WHERE status = 'Approved'
                                            AND used = '0'
                                            AND nama_uploader = '" .
                                        $uploaderName .
                                        "'";
                                    $queryApprovedNoKontrak = $legalprod->select($getApprovedNoKontrak);
                                    foreach ($queryApprovedNoKontrak as $listApprovedNoKontrak) {
                                        if ($listApprovedNoKontrak->no_kontrak_compnet !== null && $listApprovedNoKontrak->no_kontrak_customer !== null) {
                                            echo "<option value='" . $listApprovedNoKontrak->id . "' dataidpenomoran='" . $listApprovedNoKontrak->id . "'>  " . $listApprovedNoKontrak->no_kontrak_compnet . ' | ' . $listApprovedNoKontrak->no_kontrak_customer . ' </option>';
                                        } elseif ($listApprovedNoKontrak->no_kontrak_compnet == null) {
                                            echo "<option value='" . $listApprovedNoKontrak->id . "' dataidpenomoran='" . $listApprovedNoKontrak->id . "'>  " . $listApprovedNoKontrak->no_kontrak_customer . ' </option>';
                                        } elseif ($listApprovedNoKontrak->no_kontrak_customer == null) {
                                            echo "<option value='" . $listApprovedNoKontrak->id . "' dataidpenomoran='" . $listApprovedNoKontrak->id . "'>  " . $listApprovedNoKontrak->no_kontrak_compnet . ' </option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="hidden" name="idPenomoranKontrak" id="idPenomoranKontrak">
                            </div>

                        </div>

                        {{-- Insertion --}}
                        <div class="row mb-3">
                            <div class="col-md-6 col-xs-12">
                                <label for="currency" class="form-label">Currency</label>
                                <select name="currency" id="currency" class="select2"
                                    data-placeholder="Select Currency">
                                    <option value="">Currency</option>
                                    <?php
                                    $getCurrency = $legalprod->select('SELECT * FROM currency');
                                    foreach ($getCurrency as $currencyOption) {
                                        echo "<option value='" . $currencyOption->id . "'>  " . $currencyOption->currency_name . ' </option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control form-control-sm"
                                    placeholder="Insert Amount">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 col-xs-12">
                                <label for="fileUpload" class="form-label">Insert File</label>
                                <input type="file" name="fileUpload" id="fileUpload"
                                    class="form-control form-control-sm" accept="application/pdf">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="submitAdd">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div class="modal fade" id="modalPenomoranKontrak" tabindex="-1" aria-labelledby="modalPenomoranKontrak"
        aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-xl" id="modalPenomoranSize">
            <div class="modal-content d-flex" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">
                <div class="modal-header d-flex">
                    <h5 class="modal-title" id="modalPenomoranKontrakLabel">Detail </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close"></button>
                </div>

                <div class="modal-body">
                    <div class="pb-2 d-flex justify-content-end">
                        <h5 id="statusApp"> </h5>
                    </div>

                    <form action="" class="row g-3 d-flex" id="reportDetail">
                        {{ csrf_field() }}
                        <input type="hidden" id="penomoran_id" value="">
                        {{-- Numbering By --}}
                        <div class="row pt-3">
                            <div class="col-6 col-xs-12" style="pointer-events: none; opacity: 0.5;"
                                id="customText2Detail">
                                <label for="" class="form-label">Number by Compnet</label>
                                <input type="text" name="compnetTextDetail" id="compnetTextDetail"
                                    class="form-control form-control-sm" placeholder="-">
                            </div>

                            <div class="col-6 col-xs-12" style="pointer-events: none; opacity: 0.5;"
                                id="customText1Detail">
                                <label for="" class="form-label">Number by Customer</label>
                                <input type="text" name="customerTextDetail" id="customerTextDetail"
                                    class="form-control form-control-sm" placeholder="-">
                            </div>
                        </div>

                        {{-- Kontrak Template & Project SO/PO --}}
                        <div class="row pt-3">
                            <div class="col-4 col-xs-12 d-flex" style="display: none !important;">
                                <label for="projectDetail" class="form-label">Project: </label>
                                <div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="radio" name="projectDetail" id="project1Detail"
                                            value="projectSODetail" class="form-check-input" checked>
                                        <label for="project1" class="form-check-label ms-2">SO </label>
                                    </div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="radio" name="projectDetail" id="project2Detail"
                                            value="projectPODetail" class="form-check-input">
                                        <label for="project2" class="form-check-label ms-2">PO </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-xs-12" id="contractTemplateTextDetailDiv"
                                style="pointer-events: none; opacity: 0.5;">
                                <label for="" class="form-label">Contract Template</label>
                                <input type="text" name="contractTemplateTextDetail" id="contractTemplateTextDetail"
                                    class="form-control form-control-sm" placeholder="">
                            </div>

                            <div class="col-3 col-xs-12 pt-1" id="soField1Detail"
                                style="pointer-events: none; opacity: 0.5;">
                                <label for="soNumberProjectDetail" class="form-labelDetail">Number </label>
                                <select name="soNumberProjectDetail" id="soNumberProjectDetail" class="form-select"
                                    data-placeholder="Select SO Number">
                                    <option value="" disabled selected>Number SO</option>
                                    <?php
                                    $numberSOQuery = $crmprod->select("SELECT snc.name as sonumber, na.name AS soName
                                                                                                                                                    FROM so_numbering_c_npwp_account_c_c sncn
                                                                                                                                                    JOIN so_numbering_c snc ON sncn.so_numbering_c_npwp_account_cso_numbering_c_idb = snc.id
                                                                                                                                                    JOIN npwp_account_c na ON sncn.so_numbering_c_npwp_account_cnpwp_account_c_ida = na.id
                                                                                                                                                    WHERE sncn.deleted = '0'");
                                    foreach ($numberSOQuery as $numberSOData) {
                                        echo "<option value='" . $numberSOData->sonumber . "' data-socustomerDetail='" . $numberSOData->soName . "'>  " . $numberSOData->sonumber . ' </option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-3 col-xs-12" id="soField2Detail" style="pointer-events: none; opacity: 0.5;">
                                <label for="soCustomerProject" class="form-label">Customer </label>
                                <input type="text" name="soCustomerProjectDetail" id="soCustomerProjectDetail"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-3 col-xs-12" id="poField1Detail"
                                style="display: none; pointer-events: none; opacity: 0.5">
                                <label for="poNumberProject" class="form-label">Number </label>
                                <input type="text" name="poNumberProjectDetail" id="poNumberProjectDetail"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-3 col-xs-12 pb-1" id="poField2Detail"
                                style="display: none; pointer-events: none; opacity: 0.5">
                                <label for="poPrincipalProject" class="form-label">Principal </label>
                                <select name="poPrincipalProjectDetail" id="poPrincipalProjectDetail"
                                    class="form-select">
                                    <option value="" disabled selected>Principal Name</option>
                                    <?php
                                    $poPrincipal = $fapgprod->select("SELECT DISTINCT id_supplier AS id, supplier_name AS name
                                                                                                                                                                                                                                                                                                    FROM master_supplier
                                                                                                                                                                                                                                                                                                    ORDER BY supplier_name");
                                    foreach ($poPrincipal as $poPrincipalData) {
                                        echo "<option value='" . $poPrincipalData->id . "'> " . $poPrincipalData->name . ' </option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        {{-- Company Name & Job Category --}}
                        <div class="row pt-3">
                            <div class="col-4 col-xs-12 d-flex" style="display: none !important">
                                <label for="" class="form-label">Contract Template: </label>
                                <div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="radio" name="contractTemplateDetail" id="contractTemplate1Detail"
                                            value="Customer" class="form-check-input" checked>
                                        <label for="contractTemplate1" class="form-check-label ms-2">Customer</label>
                                    </div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="radio" name="contractTemplateDetail" id="contractTemplate2Detail"
                                            value="Compnet" class="form-check-input">
                                        <label for="contractTemplate2" class="form-check-label ms-2">Compnet</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-xs-12" style="pointer-events: none; opacity: 0.5;">
                                <label for="" class="form-label">Company Name </label>
                                <select name="companyNameDetail" id="companyNameDetail" class="form-select"
                                    style="width: 100%">
                                    <option value="" disabled selected> Select Your Company </option>
                                    <option value="NCI"> PT. Nusantara Compnet Integrator </option>
                                    <option value="IOT"> PT. Inovasi Otomasi Teknologi </option>
                                    <option value="PSA"> PT. Pro Sistimatika Automasi </option>
                                    <option value ="SJT"> PT. Sugi Jaya Teknologi </option>
                                    <option value="CIS"> PT. Compnet Integrator Services </option>
                                </select>
                            </div>

                            <div class="col-6 col-xs-12" style="pointer-events: none; opacity: 0.5;">
                                <label for="" class="form-label">Job Category </label>
                                <select name="jobPositionDetail" id="jobPositionDetail" class="form-select"
                                    style="width: 100%">
                                    <option value="" disabled selected> Select Your Job Category </option>
                                    <option value="MTC"> Maintenance Murni </option>
                                    <option value="LEA"> Leasing/Sewa Perangkat </option>
                                    <option value="ADA"> Pengadaan </option>
                                    <option value ="PS"> Partnership </option>
                                    <option value="PIM"> Pengadaan - Instalasi - Maintenance </option>
                                    <option value="SM"> Sewa Menyewa </option>
                                    <option value="PIMR"> Pengadaan - Instalasi - Maintenance - Renta </option>
                                    <option value="SK"> Sub Kontrak </option>
                                    <option value="MOU"> Memorandum of Understanding </option>
                                    <option value="NDA"> Non Disclosure Agreement </option>
                                    <option value="FWR"> Kontrak dengan Forwarder </option>
                                    <option value="PKS"> Kerja Sama </option>
                                    <option value="KSO"> Konsorsium </option>
                                </select>
                            </div>
                        </div>

                        {{-- Effective Date, Amount & Description --}}
                        {{-- <div class="row pt-3 d-flex">
                            <div class="col-6 col-xs-12">
                                <div style="pointer-events: none; opacity: 0.5;">
                                    <label for="effectiveDate" class="form-label">Effective Date </label>
                                    <input type="date" name="effectiveDateDetail" id="effectiveDateDetail"
                                        class="form-control input-sm date datetimepicker">
                                </div>

                                <div class="pt-3" style="pointer-events: none; opacity: 0.5;">
                                    <label for="amountDetail" class="form-label">Amount</label>
                                    <input type="text" name="amountDetail" id="amountDetail"
                                        class="form-control form-control-sm">
                                </div>

                            </div>

                            <div class="col-6 col-xs-12" style="pointer-events: none; opacity: 0.5;">
                                <label for="description" class="form-label">Description </label>
                                <textarea name="descriptionDetail" id="descriptionDetail" class="form-control form-control-sm" style="height: 80%"></textarea>
                            </div>
                        </div> --}}


                        <div class="row pt-3">
                            <div class="col-md-6 col-xs-12 d-flex flex-column justify-content-between">
                                <div style="pointer-events: none; opacity: 0.5;">
                                    <label for="effectiveDate" class="form-label">Effective Date </label>
                                    <input type="date" name="effectiveDateDetail" id="effectiveDateDetail"
                                        class="form-control input-sm date datetimepicker">
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-2" style="pointer-events: none; opacity: 0.5;">
                                        <label for="currencyDetail" class="form-label">Currency</label>
                                        <input type="text" name="currencyDetail" id="currencyDetail"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-10" style="pointer-events: none; opacity: 0.5;">
                                        <label for="amountDetail" class="form-label">Amount</label>
                                        <input type="text" name="amountDetail" id="amountDetail"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-xs-12" style="pointer-events: none; opacity: 0.5;">
                                <label for="description" class="form-label">Description </label>
                                <textarea name="descriptionDetail" id="descriptionDetail" class="form-control form-control-sm" style="height: 80%"></textarea>
                            </div>
                        </div>

                        {{-- Softcopy Dokumen Kontrak --}}
                        <div class="col-12 pt-3">
                            <label for="softcopy" class="form-label fw-bolder" style="font-size: 18px">Softcopy Dokumen
                                Kontrak</label>
                            <div id="table_invoices">
                                <table id="fileTable" class="table table-sm table-bordered table-hover"
                                    style="width: 100%; border: 1px solid #dee2e6">
                                    <thead>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listFile"></tbody>
                                </table>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSaveEdit">Save Edit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#reportAdd .select2').select2({
                placeholder: $(this).data("placeholder") ?? 'Select Data',
                dropdownParent: $('#reportAdd'),
                width: '100%',
                templateResult: function(data) {
                    if (data.title) {
                        return $('<div class="m-0">' +
                            '<p class="m-0" >' + data.text + '</p>' +
                            '<p class="small mb-0" >' + data.title + '</p>' +
                            '</div>');
                    } else {
                        return $('<span class="mb-0">' + data.text + '</span>');
                    }
                },
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    if (typeof data.text === 'undefined') {
                        return null;
                    }
                    var search = params.term.toLowerCase();
                    var text = data.text.toLowerCase();
                    var title = data.title ? data.title.toLowerCase() : '';

                    if (text.indexOf(search) > -1) {
                        var modifiedData = $.extend({}, data, true);
                        var reg = new RegExp(search, 'gi');
                        modifiedData.text = modifiedData.text.replace(reg, function(str) {
                            return str.bold()
                        });
                        return modifiedData;
                    }

                    if (title.indexOf(search) > -1) {
                        var modifiedData2 = $.extend({}, data, true);
                        var reg2 = new RegExp(search, 'gi');
                        modifiedData2.title = modifiedData2.title.replace(reg2, function(str2) {
                            return str2.bold()
                        });
                        return modifiedData2;
                    }
                    return null;
                }
            });

            table = $('#reportPenomoranKontrakTable').DataTable({
                "responsive": false,
                "paging": true,
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true,
                "scroller": true,
                "order": [],
                "autoWidth": true,
                "searching": false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10', '25', '50', 'All']
                ],
                "dom": "<'row be-datatable-header' <'col-sm-4 mt-1'l>>" +
                    "<'row be-datatable-body'<'col-sm-12'tr>>" +
                    "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>",
                "buttons": [{
                    extend: 'excelHtml5',
                    text: '<i class="fa-regular fa-file-excel me-0"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    filename: function() {
                        return "Data Penomoran Final Kontrak-" + new Date().getTime();
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                }],

                "ajax": {
                    "url": "{{ url('') }}/finalkontrak/dataPenomoranRegisterFinalKontrak",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "type": "GET",
                    "contentType": 'application/json'
                }
            });
            table.columns.adjust();

            $('#excelButton').on('click', function() {
                table.buttons('.buttons-excel').trigger();
            });

            $(document).on('click', '.uploadFileButton', function() {
                // Trigger the corresponding file input click
                $(this).siblings('.fileInputDetail').click();
            });

            $(document).on('change', '.fileInputDetail', function() {
                var file = $(this).prop('files')[0];
                if (file) { // Ensure a file was selected
                    var newFileName = file.name; // Get the name of the selected file
                    // Update the file name in the same row
                    $(this).closest('tr').find('.fileNameCell').text(newFileName);
                }
            });

            $("#btnAddRecord").click(function() {
                $("#modalAdd").modal('show');
            });
        });

        $('#submitSearch').click(function(e) {
            e.preventDefault();

            if ($.fn.DataTable.isDataTable('#reportPenomoranKontrakTable')) {
                table.destroy();
            }

            var format_penomoran = $('#no_format_penomoran_filter').val();
            var no_kontrak_compnet = $('#no_kontrak_compnet_filter').val();
            var no_kontrak_customer = $('#no_kontrak_customer_filter').val();
            var tanggal_kontrak = $('#tanggal_kontrak_filter').val();
            var customer_name = $('#customer_name_filter').val();
            var job_position = $('#job_position_filter').val();
            var company_name = $('#company_name_filter').val();
            var so_number = $('#so_number_filter').val();
            var currency = $('#currency_filter').val();
            var amount = $('#amount_filter').val();

            // console.log(data);
            table = $('#reportPenomoranKontrakTable').DataTable({
                "responsive": false,
                "paging": true,
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true,
                "scroller": true,
                "order": [],
                "autoWidth": true,
                "searching": false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10', '25', '50', 'All']
                ],
                "dom": "<'row be-datatable-header' <'col-sm-4 mt-1'l>>" +
                    "<'row be-datatable-body'<'col-sm-12'tr>>" +
                    "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>",
                "buttons": [{
                    extend: 'excelHtml5',
                    text: '<i class="fa-regular fa-file-excel me-0"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    filename: function() {
                        return "Data Penomoran Final Kontrak-" + new Date().getTime();
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                }],
                "ajax": {
                    "url": "{{ url('') }}/finalkontrak/searchPenomoranRegisterFinalKontrak",
                    "type": "GET",
                    "data": {
                        no_format_penomoran_filter: format_penomoran,
                        no_kontrak_compnet_filter: no_kontrak_compnet,
                        no_kontrak_customer_filter: no_kontrak_customer,
                        tanggal_kontrak_filter: tanggal_kontrak,
                        customer_name_filter: customer_name,
                        job_position_filter: job_position,
                        company_name_filter: company_name,
                        so_number_filter: so_number,
                        currency_filter: currency,
                        amount_filter: amount
                    },
                }
            });
        });

        $("#submitAdd").click(function(e) {
            e.preventDefault();
            var formData = new FormData($('#reportAdd')[0]);
            let url = "{{ url('/') }}/finalkontrak/savePenomoranRegisterFinalKontrak";

            $(this).prop('disabled', true).text('Please wait...');

            // Company Name
            var selectedCompany = $('#companyName').val();
            if (!selectedCompany) {
                swal.fire({
                    title: 'Warning',
                    text: 'Company Must Be Chosen!',
                    icon: 'warning',
                    target: '#modalAdd'
                });
                $('#submitAdd').text('Submit');
                $('#submitAdd').prop('disabled', false);
                return false;
            }

            var approved = $('select[name="approved"]').val();
            if (!approved) {
                swal.fire({
                    title: 'Warning',
                    text: 'No Kontrak Must be Selected!',
                    icon: 'warning',
                    target: '#modalAdd'
                });
                $('#submitAdd').text('Submit');
                $('#submitAdd').prop('disabled', false).text('Save');
                return false;
            }

            // Currency
            var selectedCurrency = $('#currency').val();
            // console.log(selectedCurrency);
            if (!selectedCurrency) {
                swal.fire({
                    title: 'Warning',
                    text: 'Currency Must Be Chosen!',
                    icon: 'warning',
                    target: '#modalAdd'
                });
                $('#submitAdd').text('Submit');
                $('#submitAdd').prop('disabled', false);
                return false;
            }

            // Amount
            var selectedAmount = $('#amount').val();
            if (!selectedAmount) {
                swal.fire({
                    title: 'Warning',
                    text: 'Amount Must Be Filled!',
                    icon: 'warning',
                    target: '#modalAdd'
                });
                $('#submitAdd').text('Submit');
                $('#submitAdd').prop('disabled', false);
                return false;
            }

            // Upload File
            var fileUpload = $('input[name="fileUpload"]')[0];
            if (!fileUpload) {
                swal.fire({
                    title: 'Warning',
                    text: 'You need to insert a file!',
                    icon: 'warning',
                    target: '#modalAdd'
                });
                $('#submitAdd').text('Submit');
                $('#submitAdd').prop('disabled', false).text('Save');
                return false;
            }

            // Validasi ukuran file (maksimal 15MB)
            var fileSize = fileUpload.files[0].size; // ukuran file dalam byte
            if (fileSize > 15 * 1024 * 1024) { // 15MB dalam byte
                swal.fire({
                    title: 'Warning',
                    text: 'File size must not exceed 15MB!',
                    icon: 'warning',
                    target: '#modalAdd'
                });
                $('#submitAdd').text('Submit');
                $('#submitAdd').prop('disabled', false).text('Save');
                return false;
            }


            $('#btngenerateAdd').hide();
            $('#loadingAdd').show();
            $('#loadingAdd').addClass('d-flex justify-content-center align-items-end');

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.code == 200) {
                        swal.fire({
                            title: 'Success',
                            text: data.message,
                            icon: 'success',
                        });
                        location.reload();
                    } else {
                        swal.fire({
                            title: 'Insert Failed!',
                            text: 'Internal Server Error!',
                            icon: 'error',
                        });
                        console.log(data.message);
                    }
                    $('#modalAdd').modal('hide');

                    clearform(); // Reset the form here
                    ReloadTable();
                    $('#submitAdd').text('Submit');
                    $('#submitAdd').prop('disabled', false);
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    var myText = errorThrown;
                    swal.fire({
                        title: 'Warning',
                        // text: 'You need to choose the No Kontrak!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').text('Submit');
                    $('#submitAdd').prop('disabled', false);
                },

                complete: function() {
                    $('#btngenerateAdd').show();
                    $('#loadingAdd').removeClass('d-flex justify-content-center align-items-end');
                    $('#loadingAdd').hide();
                }
            });
        });


        $('#modalAdd').on('hidden.bs.modal', function() {
            // Clear the form
            $('#reportAdd')[0].reset(); // Resets the form fields
            $('#companyName').val(null).trigger('change'); // Reset Select2 fields if any
            $('#approved').val(null).trigger('change');
            $('#amount').val('');
            $('#currency').val('').trigger('change');
            $("#fileUpload").val('');

            $("#submitAdd").prop("disabled", false);
        });

        $('#btnSaveEdit').on('click', function() {
            var form = $('#reportDetail')[0];
            var formData = new FormData(form);
            var id = parseInt($('#penomoran_id').val());
            console.log('penomoran_id:', id);

            let url = "{{ url('') }}/finalkontrak/listPenomoranRegisterFinalKontrak/" + id;

            if ($('#project1Detail').is(':checked')) {
                // var poNumberProject = $('#poNumberProjectDetail').val();
                formData.append('poPrincipalProjectDetail', "");

            } else if ($('#project2Detail').is(':checked')) {
                // var soNumberProjectDetail = $('#soNumberProjectDetail').val();
                formData.append('soNumberProjectDetail', "");
            }

            // Upload File
            $('input[name="fileInputDetail"]').on('change', function () {
                var fileUpload1 = $('input[name="fileInputDetail"]')[0];
                if (!fileUpload1) {
                    swal.fire({
                        title: 'Warning',
                        text: 'You need to insert a file!',
                        icon: 'warning',
                        target: '#modalPenomoranKontrak'
                    });
                    $('#save').text('Submit');
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }

                // Validasi ukuran file (maksimal 15MB)
                var fileSize = fileUpload1.files[0].size; // ukuran file dalam byte
                if (fileSize > 15 * 1024 * 1024) { // 15MB dalam byte
                    swal.fire({
                        title: 'Warning',
                        text: 'File size must not exceed 15MB!',
                        icon: 'warning',
                        target: '#modalPenomoranKontrak'
                    });
                    $('#submitAdd').text('Submit');
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }
            });

            var poPrincipalName = $('select[name="poPrincipalProjectDetail"] option:selected').text();
            formData.append('poPrincipalNameDetail', poPrincipalName);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status) {
                        swal.fire({
                            title: 'Success',
                            text: 'Data Updated Successfully!',
                            icon: 'success',
                        });
                        $('#modalPenomoranKontrak').modal('hide');
                    } else {
                        swal.fire('Error: ' + data.message);
                    }
                    ReloadTable();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var myText = errorThrown;
                    swal.fire(myText);
                    $('#btnSaveEdit').text('Submit');
                    $('#btnSaveEdit').prop('disabled', false);
                }
            });
        });


        function modalShow(obj) {
            let id = parseInt(obj.attributes.id.value);
            $('#penomoran_id').val(obj.attributes.id.value);
            $('#modalPenomoranKontrakLabel').text(obj.attributes.data_name.value);

            getModal(id);
        }

        function getModal(id) {
            var baseUrl = '{{ url('/') }}';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{ url('/') }}/finalkontrak/listPenomoranRegisterFinalKontrak/" + id,
                contentType: "application/json",
                searching: false,
                lengthMenu: [
                    [-1],
                    ['All']
                ],
                success: function(data) {
                    $('#compnetTextDetail').val(data.no_kontrak_compnet);
                    $('#customerTextDetail').val(data.no_kontrak_customer);

                    $('#contractTemplateTextDetail').val(data.template_kontrak)
                    $('#soNumberProjectDetail').val(data.so_id);
                    $('#soCustomerProjectDetail').val(data.customer_name);
                    $('#poNumberProjectDetail').val(data.po_number);
                    $('#poPrincipalProjectDetail').val(data.supplier_id);

                    $('#effectiveDateDetail').val(data.tanggal_kontrak);
                    $('#jobPositionDetail').val(data.job_position);
                    $('#companyNameDetail').val(data.company_name);
                    $('#descriptionDetail').val(data.deskripsi);
                    console.log(data.amount);
                    $('#amountDetail').val(data.amount);
                    $('#currencyDetail').val(data.currency_full);

                    // NumberingBy
                    if (data.no_kontrak_compnet && data.no_kontrak_customer) {
                        $('#numberingBy1Detail').prop('checked', true);
                        $('#numberingBy2Detail').prop('checked', true);
                    } else if (data.no_kontrak_compnet && !data.no_kontrak_customer) {
                        $('#numberingBy1Detail').prop('checked', false);
                        $('#numberingBy2Detail').prop('checked', true);
                    } else if (data.no_kontrak_customer && !data.no_kontrak_compnet) {
                        $('#numberingBy1Detail').prop('checked', true);
                        $('#numberingBy2Detail').prop('checked', false);
                    } else {
                        $('#numberingBy1Detail').prop('checked', false);
                        $('#numberingBy2Detail').prop('checked', false);
                    }

                    // Project SO/PO Field
                    if (data.so_id) {
                        $('#div_nomor_so').show()
                        $('#div_nomor_po').hide()
                        $('#nomor_so').val(data.so_id);
                    } else if (data.po_number) {
                        $('#div_nomor_po').show()
                        $('#div_nomor_so').hide()
                        $('#nomor_po').val(data.po_number);
                    }

                    if (data.so_id) {
                        $('#project1Detail').prop('checked', true);
                    } else if (data.po_number) {
                        $('#project2Detail').prop('checked', true);
                    }

                    toggleFieldsDetail();
                    $('input[name="projectDetail"]').change(function() {
                        toggleFieldsDetail();
                    });

                    function toggleFieldsDetail() {
                        if ($('#project1Detail').is(':checked')) {
                            $('#soField1Detail').show();
                            $('#soField2Detail').show();
                            $('#poField1Detail').hide();
                            $('#poField2Detail').hide();

                            $('#poNumberProject').val('');
                            $('#poPrincipalProject').val('');

                        } else if ($('#project2Detail').is(':checked')) {
                            $('#soField1Detail').hide();
                            $('#soField2Detail').hide();
                            $('#poField1Detail').show();
                            $('#poField2Detail').show();

                            $('#soNumberProject').val('');
                            $('#soCustomerProject').val('');
                        }
                    }

                    // Contract Template
                    if (data.template_kontrak == 'Customer') {
                        $('#contractTemplate1Detail').prop('checked', true);
                    } else {
                        $('#contractTemplate2Detail').prop('checked', true);
                    }

                    // Softcopy Dokumen Kontrak
                    $('#fileTable').DataTable().destroy();
                    let listFile = $('#listFile');
                    let rowTable = '';
                    listFile.empty();
                    if (data.penomoran_final_kontrak_attachment) {
                        rowTable += '<tr>' +
                            '<td class="fileNameCell">' + data.penomoran_final_kontrak_attachment.file_name +
                            '</td>' +
                            '<td>' +
                            '<a href="' + baseUrl + '/penomoranfinalkontrak/' + encodeURIComponent(data
                                .penomoran_final_kontrak_attachment.file_name) +
                            '" target="_blank" class="btn btn-sm btn-primary me-2"><i class="fas fa-eye"></i> View </a>' +
                            '<button type="button" class="btn btn-sm btn-inverse uploadFileButton"><i class="fas fa-upload"></i> Update Current File </button>' +
                            '<input type="file" class="fileInputDetail" name="fileInputDetail" style="display: none;" accept="application/pdf">' +
                            '</td>' +
                            '</tr>';
                    } else {
                        rowTable = '<td style="text-align: center" colspan="2">No Data</td>';
                    }
                    listFile.append(rowTable);
                    $('#fileTable').DataTable({
                        responsive: true,
                        processing: true,
                        paging: false,
                        searching: false,
                        lengthMenu: [
                            [-1],
                            ['All']
                        ],
                        columnDefs: [{
                            targets: 0,
                            render: function(data, type, row) {
                                return "<div class='text-wrap' style='width: 600px;'>" +
                                    data + "</div>";
                            }
                        }, {
                            targets: 1,
                            className: 'text-end',
                            render: function(data, type, row) {
                                return data;
                            }
                        }]
                    });
                    $("#modalPenomoranKontrak").modal('show');

                },
                error: function() {
                    swal.fire({
                        icon: 'error',
                        title: 'Internal Server Error',
                        text: "Ouch, sistemnya lagi error...",
                        showConfirmButton: false,
                    });
                }
            });
        }

        $('#approved').change(function() {
            const selectedOption = $(this).find(':selected');
            const dataIdPenomoran = selectedOption.attr('dataidpenomoran');
            $('#idPenomoranKontrak').val(dataIdPenomoran);
        });

        function clearform() {
            $("#report")[0].reset();
            $("#report").parsley().reset();

            $("#companyName").val('').trigger("change");
            $('#approved').val('').trigger('change');
            $("#amount").val('');
            $("#currency").val('').trigger('change');
            $("#fileUpload").val('');

            $("#submitAdd").prop("disabled", false);
        }

        function ReloadTable() {
            table.ajax.reload(null, false);
        }

        function ReloadTableApprovers() {
            let id = parseInt($('#penomoran_id').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{ url('/') }}/finalkontrak/listPenomoranRegisterFinalKontrak/" + id,
                contentType: "application/json",
                success: function(data) {
                    updateApproverTable(data);
                },
                error: function() {
                    swal.fire("Error fetching approver data.");
                }
            });
        }
    </script>
@endsection
