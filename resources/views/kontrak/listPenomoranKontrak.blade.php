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
                                        <label for="no_urut_filter">No Urut</Title></label>
                                        <input type="text" id="no_urut_filter" name="no_urut_filter"
                                            class="form-control input-sm" placeholder="Search No Urut" autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="so_number_filter">SO Number</Title></label>
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
                                        <label for="customer_name_filter">Customer/Partner</Title></label>
                                        <input type="text" id="customer_name_filter" name="customer_name_filter"
                                            class="form-control input-sm" placeholder="Search Customer/Partner"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="job_position_filter">Job Category</Title></label>
                                        <input type="text" id="job_position_filter" name="job_position_filter"
                                            class="form-control input-sm" placeholder="Search Job Category"
                                            autocomplete="off">
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
                <div class="card-body">
                    <div class="button-row" style="display: flex; justify-content:end; gap: 10px;">
                        <button id="excelButton" class="btn btn-success btn-sm">
                            <i class="fa-regular fa-file-excel me-0"></i> Excel
                        </button>
                        <button id="btnAddRecord" type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#modalPenomoranKontrak" class="btn btn-primary w-100 w-md-25">Add Penomoran
                        </button>
                    </div>

                    <table id="reportPenomoranKontrakTable" width="100%" class="table table-striped nowrap">
                        <thead class="mt-30">
                            <tr>
                                <th scope="col">No Kontrak Compnet</th>
                                <th scope="col">No Kontrak Customer</th>
                                <th scope="col">No Urut</th>
                                <th scope="col">SO Number</th>
                                <th scope="col">Tanggal Kontrak</th>
                                <th scope="col">Customer/Partner</th>
                                <th scope="col">Job Category</th>
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
                    <h5 class="modal-title fw-bold" id="modalPenomoranKontrakLabelAdd">Add New Contract Number</h5>
                    <input type="hidden" id="iditem" name="iditem" value="">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close"></button>
                </div>
                <div class="modal-body">
                    <div class="py-1 d-flex justify-content-between"></div>

                    <form action="" method="" id="reportAdd">
                        {{ csrf_field() }}
                        {{-- Numbering By --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-2">
                                        <label for="numberingBy2" class="form-check-label mb-0">Numbering By
                                            Compnet</label>
                                    </div>
                                    <div class="col-md-1">
                                        <input type="checkbox" name="numberingBy[]" id="numberingBy2"
                                            value="numbercompnet" class="form-check-input">
                                    </div>
                                </div>

                                <div class="row align-items-center mb-2">
                                    <div class="col-md-2">
                                        <label for="numberingBy1" class="form-check-label mb-0">Numbering By
                                            Customer</label>
                                    </div>
                                    <div class="col-md-1" style="max-width: 4%;">
                                        <input type="checkbox" name="numberingBy[]" id="numberingBy1"
                                            value="numbercustomer" class="form-check-input">
                                    </div>
                                    <div class="col-md-3" id="customText" style="pointer-events: none; opacity: 0;">
                                        <input type="text" name="customerText" id="customerText"
                                            class="form-control form-control-sm" placeholder="Insert Number by Customer">
                                    </div>
                                </div>

                                <div class="row align-items-center mb-2" id="noNumberingBy">
                                    <div class="col-md-2">
                                        <label for="" class="form-check-label mb-0">No Need Numbering</label>
                                    </div>
                                    <div class="col-md-1">
                                        <input type="checkbox" name="noNeedNumberingBy" id="numberingBy3"
                                            value="noNeedNumbering" class="form-check-input">
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Contract Template --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <label for="numberingBy2" class="form-check-label mb-0">Contract Template:</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" name="contractTemplate" id="contractTemplate2"
                                            value="Compnet" class="form-check-input" checked>
                                        <label for="contractTemplate2" class="form-check-label ms-2">Compnet</label>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2">
                                        <input type="radio" name="contractTemplate" id="contractTemplate1"
                                            value="Customer" class="form-check-input">
                                        <label for="contractTemplate2" class="form-check-label ms-2">Customer</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Project --}}
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <label for="project" class="form-label">Project: </label>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="d-flex align-items-center toggle-radio" data-style="square">
                                            <input type="radio" name="project" id="projectSO" value="projectSO"
                                                checked>
                                            <label for="projectSO" class="form-check-label px-4">SO </label>

                                            <input type="radio" name="project" id="projectPO" value="projectPO">
                                            <label for="projectPO" class="form-check-label px-4">PO </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-xs-12" id="soField1">
                                <label for="soNumberProject" class="form-label">Number </label>
                                <select name="soNumberProject" id="soNumberProject" class="select2"
                                    data-placeholder="Select SO Number">
                                    <option value="">Number SO</option>
                                    <?php
                                    $numberSOQuery = $crmprod->select("SELECT snc.name as sonumber, na.name AS soName
                                                                                                                                                                                                                                                                                FROM so_numbering_c_npwp_account_c_c sncn
                                                                                                                                                                                                                                                                                JOIN so_numbering_c snc ON sncn.so_numbering_c_npwp_account_cso_numbering_c_idb = snc.id
                                                                                                                                                                                                                                                                                JOIN npwp_account_c na ON sncn.so_numbering_c_npwp_account_cnpwp_account_c_ida = na.id
                                                                                                                                                                                                                                                                                WHERE sncn.deleted = '0'");
                                    echo "<option value='-' data-title='not registered yet'>Not Registered Yet</option>";
                                    foreach ($numberSOQuery as $numberSOData) {
                                        echo "<option value='" . $numberSOData->sonumber . "' data-title='" . $numberSOData->soName . "'>  " . $numberSOData->sonumber . ' </option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6 col-xs-12" id="soField2" style="pointer-events: none; opacity: 0.5;">
                                <label for="soCustomerProject" class="form-label">Customer </label>
                                <input type="text" name="soCustomerProject" id="soCustomerProject"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4 col-xs-12" style="display: none" id="poField1">
                                <label for="poNumberProject" class="form-label">Number </label>
                                <input type="text" name="poNumberProject" id="poNumberProject"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6 col-xs-12" style="display: none" id="poField2">
                                <label for="poPrincipalProject" class="form-label">Principal </label>
                                <select name="poPrincipalProject" id="poPrincipalProject" class="select2"
                                    data-placeholder="Select Principal Name">
                                    <option value="" disabled selected>Principal</option>
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

                        {{-- Project Name --}}
                        <div class="row mb-3">
                            <div class="col-md-6 col-xs-12" id="ProjectName">
                                <label for="ProjectNameText" class="form-label">Project Name</label>
                                <input type="text" name="ProjectNameText" id="ProjectNameText"
                                    class="form-control form-control-sm" placeholder="Input Project Name">
                            </div>
                        </div>

                        {{-- JobPosition & CompanyName --}}
                        <div class="row mb-3">
                            <div class="col-md-6 col-xs-12 mb-2">
                                <label class="form-label">Job Category </label>
                                <select name="jobPosition" id="jobPosition" class="select2"
                                    data-placeholder="Select Job Category">
                                    <option value="" disabled selected> Select Job Category </option>
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

                            <div class="col-md-6 col-xs-12">
                                <label class="form-label">Company Name </label>
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
                        </div>

                        {{-- Effective Date, Description & Insertion --}}
                        <div class="row mb-3">
                            <div class="col-md-6 col-xs-12 d-flex flex-column justify-content-between">
                                <div class="mb-3">
                                    <label for="effectiveDate" class="form-label">Effective Date </label>
                                    <input type="date" name="effectiveDate" id="effectiveDate"
                                        class="form-control input-sm date datetimepicker">
                                </div>

                                <div>
                                    <label for="fileUpload" class="form-label">Insert File</label>
                                    <input type="file" name="fileUpload" id="fileUpload"
                                        class="form-control form-control-sm" accept="application/pdf">
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <label for="description" class="form-label">Description </label>
                                <textarea name="description" id="description" class="form-control form-control-sm" rows="3"
                                    style="height: 80%;"></textarea>
                            </div>
                        </div>

                        {{-- Approver --}}
                        <div class="row mb-3">
                            <div id="approverContainer">
                                <div class="jenisapproverdiv">
                                    <div class="col-md-7 mt-2">
                                        <label class="approver-label mb-2">Approver 1</label>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-2">
                                                <select name="jenisapprover[]"
                                                    class="select2 form-control approverSelect">
                                                    <option value="" disabled selected>Choose Your Approver</option>
                                                    @php
                                                        $esql =
                                                            "SELECT DISTINCT id, full_name, email FROM employee WHERE active = '1' AND email IS NOT NULL AND full_name IS NOT NULL ORDER BY full_name";
                                                        $listemployee = $hrdprod->select($esql);
                                                        foreach ($listemployee as $dataemployee) {
                                                            echo "<option value='" . $dataemployee->id . "' data_email='" . $dataemployee->email . "'>" . $dataemployee->full_name . '</option>';
                                                            // echo "<option value='" . $dataemployee->id . "' data_email='" . $dataemployee->email . "'>" . $dataemployee->full_name . " (" . $dataemployee->email . ")</option>";
                                                        }
                                                    @endphp
                                                </select>
                                            </div>
                                            <button type="button" class="btn btn-success btnAdd me-1">+</button>
                                            <button type="button" class="btn btn-danger btnRemove">-</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    <h5 class="modal-title" id="modalPenomoranKontrakLabel"></h5>
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
                            <!-- Bagian ini tidak akan terlihat karena display: none -->
                            <div class="col-4 col-xs-12 d-flex" style="display: none !important;">
                                <label for="numberingBy" class="form-label">Numbering By: </label>
                                <div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="checkbox" name="numberingByDetail[]" id="numberingBy1Detail"
                                            value="numbercustomerDetail" class="form-check-input">
                                        <label for="numberingBy1Detail" class="form-check-label ms-2">Customer</label>
                                    </div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="checkbox" name="numberingByDetail[]" id="numberingBy2Detail"
                                            value="numbercompnetDetail" class="form-check-input">
                                        <label for="numberingBy2Detail" class="form-check-label ms-2">Compnet</label>
                                    </div>
                                </div>
                            </div>

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

                            <div class="col-3 col-xs-12" id="soField1Detail">
                                <label for="soNumberProjectDetail" class="form-labelDetail pb-2">Number </label>
                                <select name="soNumberProjectDetail" id="soNumberProjectDetail"
                                    class="form-select select2" data-placeholder="Select SO Number">
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


                            <div class="col-3 col-xs-12" style="display: none" id="poField1Detail">
                                <label for="poNumberProject" class="form-label">Number </label>
                                <input type="text" name="poNumberProjectDetail" id="poNumberProjectDetail"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-3 col-xs-12" style="display: none" id="poField2Detail">
                                <label for="poPrincipalProjectDetail" class="form-label">Principal </label>
                                <select name="poPrincipalProjectDetail" id="poPrincipalProjectDetail"
                                    class="form-select select2">
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

                            <div class="col-6 col-xs-12">
                                <label style="pointer-events: none; opacity: 0.5;" class="form-label">Company Name
                                </label>
                                <select name="companyNameDetail" id="companyNameDetail" class="form-select"
                                    style="pointer-events: none; opacity: 0.5; width: 100%">
                                    <option value="" disabled selected> Select Your Company </option>
                                    <option value="NCI"> PT. Nusantara Compnet Integrator </option>
                                    <option value="IOT"> PT. Inovasi Otomasi Teknologi </option>
                                    <option value="PSA"> PT. Pro Sistimatika Automasi </option>
                                    <option value ="SJT"> PT. Sugi Jaya Teknologi </option>
                                    <option value="CIS"> PT. Compnet Integrator Services </option>
                                </select>
                            </div>

                            <div class="col-6 col-xs-12">
                                <label style="pointer-events: none; opacity: 0.5;" class="form-label">Job Category
                                </label>
                                <select name="jobPositionDetail" id="jobPositionDetail" class="form-select"
                                    style="pointer-events: none; opacity: 0.5; width: 100%">
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

                        {{-- Effective Date & Description --}}
                        <div class="row pt-3 d-flex">

                            <div class="col-6 col-xs-12">
                                <div style="pointer-events: none; opacity: 0.5;">
                                    <label for="effectiveDate" class="form-label">Effective Date </label>
                                    <input type="date" name="effectiveDateDetail" id="effectiveDateDetail"
                                        class="form-control input-sm date datetimepicker">
                                </div>

                                <div class="pt-3" id="ProjectName">
                                    <label for="ProjectNameTextDetail" class="form-label">Project Name</label>
                                    <input type="text" name="ProjectNameTextDetail" id="ProjectNameTextDetail"
                                        class="form-control form-control-sm">
                                </div>

                            </div>

                            <div class="col-6 col-xs-12">
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

                        {{-- Informasi Approval --}}
                        <div class="col-12 pt-3">
                            <div class="approverHeader" style="display: flex; justify-content:space-between">
                                <label for="" class="form-label fw-bolder" style="font-size: 18px">Informasi
                                    Approval</label>
                                <div class="detailButton">
                                    <button type="button" id="btn-print" class="btn btn-sm btn-primary"><i
                                            class="feather icon-printer"></i>Print Summary</button>
                                    <button type="button" id="editApproverButton" class="btn btn-sm btn-success">
                                        <<i class="feather icon-edit"></i> Add / Edit Approvers
                                    </button>
                                </div>
                            </div>
                            <div id="table_approver">
                                <table id="approverTable" class="table table-sm table-bordered table-hover"
                                    style="width: 100%; border: 1px solid #dee2e6">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th>Tanggal Approval</th>
                                            <th>Jenjang Approval</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listApprover"></tbody>
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

    {{-- Modal Edit Approvers --}}
    <div class="modal fade" id="editApproversModal" tabindex="-1" role="dialog" aria-labelledby="editApproversModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">
                <div class="modal-header d-flex">
                    <h5 class="modal-title" id="modalPenomoranKontrakLabel">Add or Edit Approvers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="" id="reportEditApprover">
                        <input type="hidden" id="iditem" name="iditem" value="">

                        <div class="row pt-4">
                            <div id="firstapproverDetail">
                                <div class="jenisapproverdivDetail ">
                                    <div class="col-12 col-xs-12 pb-3">
                                        <label class="approver-label mb-2">Approver 1</label>
                                        <select name="jenisapproverDetail[]"
                                            class="form-select input-sm approverSelectDetail" style="width: 100%">
                                            @php
                                                $esql =
                                                    "SELECT DISTINCT id, full_name, email FROM employee WHERE active = '1' AND email IS NOT NULL AND full_name IS NOT NULL ORDER BY full_name";
                                                $listemployee = $hrdprod->select($esql);
                                                foreach ($listemployee as $dataemployee) {
                                                    echo "<option value='" .
                                                        $dataemployee->id .
                                                        "' data_email='" .
                                                        $dataemployee->email .
                                                        "' data_fullName='" .
                                                        $dataemployee->full_name .
                                                        "'>" .
                                                        $dataemployee->full_name .
                                                        '</option>';
                                                }
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="morejenisapproverDetail"></div>
                            <div class="col-md-4 col-xs-12 mt-3">
                                <button type="button" id="btnAddDetail" class="btn btn-success">+</button>
                                <button type="button" id="btnRemoveDetail" class="btn btn-danger"> - </button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="saveApproverChanges" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var snLength = 0;
        var table;
        var tableApprovers;
        var filter;
        var originalApproverOptions = $('.approverSelect').first().html(); // Menyimpan opsi asli dari select approver
        var originalApproverOptionsDetail = null;

        var currentApproversData = null;
        var currentListEmployee = null;

        $(document).ready(function() {
            // Select2 (Add)
            $('#reportAdd .select2').select2({
                placeholder: $(this).data("placeholder") ?? 'Select Data',
                dropdownParent: $('#reportAdd'),
                width: '100%',
                // theme: 'bootstrap4',
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

            $('#soNumberProjectDetail').select2({
                placeholder: $('#soNumberProjectDetail').data("placeholder") ?? 'Select SO Number',
                dropdownParent: $('#modalPenomoranKontrak'),
                width: '100%'
            });

            $('#poPrincipalProjectDetail').select2({
                placeholder: 'Select Principal Name',
                dropdownParent: $('#modalPenomoranKontrak'),
                width: '100%'
            });


            // Numberingby CustomText (Add)
            $('#numberingBy1').change(function() {
                if ($(this).is(':checked')) {
                    $('#customText').css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                } else {
                    $('#customText').css({
                        'pointer-events': 'none',
                        'opacity': '0'
                    });
                }
            });

            // Project SO/PO (Add)
            toggleFields();
            $('input[name="project"]').change(function() {
                toggleFields();
            });

            // Autofill SO Customer (Add)
            $('#soNumberProject').on('change', function() {
                let soCustomer = $('#soNumberProject option:selected').attr('data-title');
                $('#soCustomerProject').val(soCustomer);
            });

            // Autofill SO Customer (Detail)
            $('#soNumberProjectDetail').on('change', function() {
                let soCustomer = $('#soNumberProjectDetail option:selected').attr('data-socustomerDetail');
                $('#soCustomerProjectDetail').val(soCustomer);
            });


            // --------------------------------------------------
            initializeSelect2($('.approverSelect'));

            // Event handler untuk tombol Add
            $('#approverContainer').on('click', '.btnAdd', function() {
                var lastApproverDiv = $('.jenisapproverdiv').last();

                // Hancurkan instance Select2 sebelum cloning
                lastApproverDiv.find('.approverSelect').select2('destroy');

                // Clone elemen
                var newField = lastApproverDiv.clone();

                // Inisialisasi ulang Select2 pada elemen asli
                initializeSelect2(lastApproverDiv.find('.approverSelect'));

                // Reset nilai select pada elemen baru
                newField.find('select').val('');

                // Hapus atribut ID untuk menghindari duplikasi
                newField.find('[id]').each(function() {
                    $(this).removeAttr('id');
                });

                // Update label
                var approverCount = $('.jenisapproverdiv').length + 1;
                newField.find('.approver-label').text('Approver ' + approverCount);

                // Sisipkan field baru setelah field terakhir
                newField.insertAfter(lastApproverDiv);

                // Inisialisasi Select2 pada elemen baru
                initializeSelect2(newField.find('.approverSelect'));

                // Mengatur ulang opsi approver
                filterApproverOptions();
                updateApproverLabels();
            });

            // Event handler untuk tombol Remove
            $('#approverContainer').on('click', '.btnRemove', function() {
                var approvers = $('.jenisapproverdiv');
                if (approvers.length > 1) {
                    // Hancurkan instance Select2 sebelum menghapus elemen
                    $(this).closest('.jenisapproverdiv').find('.approverSelect').select2('destroy');
                    $(this).closest('.jenisapproverdiv').remove();
                    filterApproverOptions();
                    updateApproverLabels();
                } else {
                    swal.fire({
                        title: 'Warning',
                        text: 'Cannot delete the first approver!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                }
            });

            // Event handler untuk perubahan select approver
            $(document).on('change', '.approverSelect', function() {
                filterApproverOptions();
            });
            filterApproverOptions();
            updateApproverLabels();


            // --------------------------------------------------
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
                    className: 'd-none',
                    filename: function() {
                        return "Data Penomoran Kontrak-" + new Date().getTime();
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }],
                "ajax": {
                    "url": "{{ url('') }}/kontrak/dataPenomoranKontrak",
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

            // Event delegation for file input changes
            $(document).on('change', '.fileInputDetail', function() {
                var file = $(this).prop('files')[0];
                if (file) { // Ensure a file was selected
                    var newFileName = file.name; // Get the name of the selected file
                    // Update the file name in the same row
                    $(this).closest('tr').find('.fileNameCell').text(newFileName);
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                // Get the modal element by its ID
                var modalAdd = document.getElementById('modalAdd');

                // Attach the event listener for the 'hidden.bs.modal' event
                modalAdd.addEventListener('hidden.bs.modal', function() {
                    // Find the form within the modal
                    var form = modalAdd.querySelector('form');

                    // Reset the form fields
                    if (form) {
                        form.reset();

                        // If you're using Select2 or other plugins, you may need to reset them as well
                        // For example, to reset Select2 fields:
                        $(form).find('select').val(null).trigger('change');

                        // Reset any custom elements or clear any validation messages if necessary
                    }
                });
            });


            // --------------------------------------------------
            $('#submitSearch').click(function(e) {
                e.preventDefault();

                if ($.fn.DataTable.isDataTable('#reportPenomoranKontrakTable')) {
                    table.destroy();
                }

                var no_urut = $('#no_urut_filter').val();
                var tanggal_kontrak = $('#tanggal_kontrak_filter').val();
                var no_kontrak_compnet = $('#no_kontrak_compnet_filter').val();
                var no_kontrak_customer = $('#no_kontrak_customer_filter').val();
                var customer_name = $('#customer_name_filter').val();
                var job_position = $('#job_position_filter').val();
                var company_name = $('#company_name_filter').val();
                var nama_uploader = $('#nama_uploader_filter').val();
                var deskripsi = $('#deskripsi_filter').val();
                var so_number = $('#so_number_filter').val();

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
                            return "Data Penomoran Kontrak-" + new Date().getTime();
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    }],
                    "ajax": {
                        "url": "{{ url('') }}/kontrak/searchPenomoranKontrak",
                        "type": "GET",
                        "data": {
                            no_urut_filter: no_urut,
                            tanggal_kontrak_filter: tanggal_kontrak,
                            no_kontrak_compnet_filter: no_kontrak_compnet,
                            no_kontrak_customer_filter: no_kontrak_customer,
                            customer_name_filter: customer_name,
                            job_position_filter: job_position,
                            company_name_filter: company_name,
                            nama_uploader_filter: nama_uploader,
                            deskripsi_filter: deskripsi,
                            so_number_filter: so_number
                        },
                    }
                });
            });


            $("#submitAdd").click(function(e) {
                e.preventDefault();
                var formData = new FormData($('#reportAdd')[0]);
                let url = "{{ url('/') }}/kontrak/savePenomoranKontrak";

                $(this).prop('disabled', true).text('Please wait...');

                var nonumberingby = $('input[name="noNeedNumberingBy"]');

                // Numbering By
                var numberingby = $('input[name="numberingBy[]"]');
                if (numberingby.filter(':checked').length === 0 && !nonumberingby.is(':checked')) {
                    swal.fire({
                        title: 'Warning',
                        text: 'Please select at least one of the numbering checkboxes!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }

                // Numbering By (Customer)
                var numberingbycustomer = $('#numberingBy1');
                var customerText = $('#customerText');
                if (numberingbycustomer.is(':checked') && customerText.val().trim() === '') {
                    swal.fire({
                        title: 'Warning',
                        text: 'Please fill in the number if the Customer checkbox is checked!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }

                // Project - SO Number
                var projectso = $('input[name="project"]:checked').val();
                var sonumber = $('select[name="soNumberProject"]').val();
                if (projectso === 'projectSO' && !sonumber) {
                    swal.fire({
                        title: 'Warning',
                        text: 'SO Number Must be Selected!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').text('Submit');
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }

                // Project - PO Number & Principal
                var projectpo = $('input[name="project"]:checked').val();
                var ponumber = $('input[name="poNumberProject"]').val();
                var poprincipal = $('select[name="poPrincipalProject"]').val();
                var poPrincipalName = $('select[name="poPrincipalProject"] option:selected').text();
                formData.append('poPrincipalName', poPrincipalName);
                if (projectpo === 'projectPO' && (!ponumber || !poPrincipalName)) {
                    swal.fire({
                        title: 'Warning',
                        text: 'PO Number Must be Selected!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').text('Submit');
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }

                // Job Category
                var selectedJob = $('#jobPosition').val();
                if (!selectedJob) {
                    swal.fire({
                        title: 'Warning',
                        text: 'Job Category Must be Selected!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').text('Submit');
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }

                // Company Name
                var selectedCompany = $('#companyName').val();
                if (!selectedCompany) {
                    swal.fire({
                        title: 'Warning',
                        text: 'Company Must be Selected!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').text('Submit');
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }

                var projectName = $('#ProjectNameText').val();
                if (!projectName) {
                    swal.fire({
                        title: 'Warning',
                        text: 'Project Name Must Be Inputted!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').text('Submit');
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }

                // Effective Date
                var effectivedate = $('#effectiveDate');
                if (effectivedate.val().trim() === '') {
                    swal.fire({
                        title: 'Warning',
                        text: 'Please select a valid date!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').prop('disabled', false).text('Save');
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

                // Approver
                var approver = $('select[name="jenisapprover[]"]').val();
                if (approver == null || approver == "") {
                    swal.fire({
                        title: 'Warning',
                        text: 'Aprrover Must be Selected!',
                        icon: 'warning',
                        target: '#modalAdd'
                    });
                    $('#submitAdd').text('Submit');
                    $('#submitAdd').prop('disabled', false).text('Save');
                    return false;
                }

                var selectedApprovers = [];
                $('select[name="jenisapprover[]"]').each(function() {
                    var selectedValue = $(this).val();
                    if (selectedValue) {
                        var selectedOption = $(this).find('option:selected');
                        var selectedEmail = selectedOption.attr('data_email');
                        var selectedName = selectedOption.text();

                        if (selectedApprovers.includes(selectedValue)) {
                            swal.fire({
                                title: 'Warning',
                                text: 'This approver has already been selected. Please choose another.',
                                icon: 'warning',
                                target: '#modalAdd'
                            });
                            $(this).val('');
                            $('#submitAdd').prop('disabled', false).text('Save');
                        } else {
                            selectedApprovers.push(selectedValue);
                            formData.append('jenisapproveremail[]', selectedEmail);
                            formData.append('jenisapprovername[]', selectedName);
                        }
                    }
                });

                $('#btngenerateAdd').hide();
                $('#loadingAdd').show();
                $('#loadingAdd').addClass('d-flex justify-content-center align-items-end');

                // console.log(data);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.code == 200) {
                            swal.fire({
                                title: 'Success',
                                text: data.message,
                                icon: 'success',
                            });
                        } else {
                            swal.fire({
                                title: 'Insert Failed!',
                                text: 'Internal Server Error!',
                                icon: 'error',
                            });
                            console.log(data.message);
                        }
                        $('#modalAdd').modal('hide');

                        clearform();
                        ReloadTable();
                        $('#submitAdd').text('Submit');
                        $('#submitAdd').prop('disabled', false).text('Save');
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        var myText = errorThrown;
                        // swal.fire(myText);
                        swal.fire({
                            title: 'Error!',
                            text: myText,
                            icon: 'error',
                        });
                        $('#modalAdd').modal('hide');
                        $('#submitAdd').text('Submit');
                        $('#submitAdd').prop('disabled', false).text('Save');
                    },

                    complete: function() {
                        $('#btngenerateAdd').show();
                        $('#loadingAdd').removeClass(
                            'd-flex justify-content-center align-items-end');
                        $('#loadingAdd').hide();
                        $('#submitAdd').prop('disabled', false).text('Save');
                    }

                });
            });


            // --------------------------------------------------
            $('#modalAdd').on('hidden.bs.modal', function() {
                // Clear the form
                $("input[name='numberingBy[]']").prop('checked', false).trigger('change');
                $("input[name='noNeedNumberingBy']").prop('checked', false).trigger('change');
                $("#customerText").val('').trigger("change");

                $("input[name='contractTemplate']").prop('checked', false).first().prop('checked', true)
                    .trigger('change');
                $("#effectiveDate").val('').trigger("change");

                $("input[name='project']").prop('checked', false).first().prop('checked', true).trigger(
                    'change');
                $("#soNumberProject").val('').trigger("change");
                $("#soCustomerProject").val('').trigger("change");
                $("#poNumberProject").val('').trigger("change");
                $("#poPrincipalProject").val('').trigger("change");

                $("#jobPosition").val('').trigger("change");
                $("#companyName").val('').trigger("change");

                $("#ProjectNameText").val('');
                $("#description").val('');
                $("#fileUpload").val('');

                $('.jenisapproverdiv').not(':first').remove();
                $('#approverContainer .jenisapproverdiv:first select[name="jenisapprover[]"]').val('')
                    .trigger('change');

                $("#submitAdd").prop("disabled", false);
            });

            $('#modalPenomoranKontrak').on('hidden.bs.modal', function() {
                $('#fileTable').DataTable().destroy(); // Hapus DataTable
                $('#approverTable').DataTable().destroy();
            });


            // --------------------------------------------------
            $('#btnSaveEdit').on('click', function() {
                var form = $('#reportDetail')[0];
                var formData = new FormData(form);
                // var id = parseInt($('#penomoran_id').val());
                var id = parseInt($('#penomoran_id').val());
                console.log('penomoran_id:', id);

                let url = "{{ url('') }}/kontrak/listPenomoranKontrak/" + id;


                $('input[name="fileInputDetail"]').on('change', function () {
                    // Upload File
                    var fileUpload1 = $('input[name="fileInputDetail"]')[0];
                    if (!fileUpload1) {
                        swal.fire({
                            title: 'Warning',
                            text: 'You need to insert a file!',
                            icon: 'warning',
                            target: '#modalPenomoranKontrak'
                        });
                        $('#btnSaveEdit').text('Submit');
                        $('#btnSaveEdit').prop('disabled', false).text('Save');
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
                        $('#btnSaveEdit').text('Submit');
                        $('#btnSaveEdit').prop('disabled', false).text('Save');
                        return false;
                    }
                });


                if ($('#project1Detail').is(':checked')) {
                    // var poNumberProject = $('#poNumberProjectDetail').val();
                    formData.append('poPrincipalProjectDetail', "");
                    if ($('#compnetTextDetail').val() == '' && $('#customerTextDetail').val() == '') {
                        swal.fire({
                            title: 'Warning',
                            text: 'Cannot delete Numbering By Customer!',
                            icon: 'warning',
                            target: '#modalPenomoranKontrak'
                        });
                        return false;
                    }

                } else if ($('#project2Detail').is(':checked')) {
                    // var soNumberProjectDetail = $('#soNumberProjectDetail').val();

                    formData.append('soNumberProjectDetail', "");
                    if ($('#poNumberProjectDetail').val() == '') {
                        swal.fire({
                            title: 'Warning',
                            text: 'Numbering PO Cannot Empty!',
                            icon: 'warning',
                            target: '#modalPenomoranKontrak'
                        });
                        return false;
                    }
                }

                // <div class="d-flex align-items-center ms-2">
                //     <input type="radio" name="projectDetail" id="project1Detail"
                //         value="projectSODetail" class="form-check-input" checked>
                //     <label for="project1" class="form-check-label ms-2">SO </label>
                // </div>
                // <div class="d-flex align-items-center ms-2">
                //     <input type="radio" name="projectDetail" id="project2Detail"
                //         value="projectPODetail" class="form-check-input">
                //     <label for="project2" class="form-check-label ms-2">PO </label>
                // </div>

                var poPrincipalName = $('select[name="poPrincipalProjectDetail"] option:selected').text();
                formData.append('poPrincipalNameDetail', poPrincipalName);

                var customerTextDetail = $('input[name="customerTextDetail"]').val();
                // if(!customerTextDetail){
                //     formData.append('customerTextDetail', "");
                // }else{
                formData.append('customerTextDetail', customerTextDetail);
                // }

                var descriptionDetail = $('textarea[name="descriptionDetail"]').val();
                formData.append('descriptionDetail', descriptionDetail);

                if ($('#ProjectNameTextDetail').prop('disabled')) {
                    var ProjectNameTextDetail = $('input[name="ProjectNameTextDetail"]').val();
                    formData.append('ProjectNameTextDetail', ProjectNameTextDetail);
                }


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

            $("#btnAddRecord").click(function() {
                $("#modalAdd").modal('show');
            });

            $('#saveApproverChanges').on('click', function() {
                var form = $('#reportEditApprover')[0];
                var formData = new FormData(form);
                $(this).prop('disabled', true).text('Please wait...');
                var id = parseInt($('#iditem').val());
                console.log('iditem:', id);

                let url = "{{ url('') }}/kontrak/listPenomoranKontrakApprovers/" + id;

                var selectedApprovers = [];
                $('select[name="jenisapproverDetail[]"]').each(function() {
                    var selectedValue = $(this).val();
                    if (selectedValue) {
                        var selectedOption = $(this).find('option:selected');
                        var selectedEmail = selectedOption.attr('data_email');
                        var selectedName = selectedOption.attr('data_fullName');

                        if (selectedApprovers.includes(selectedValue)) {
                            swal.fire(
                                'This approver has already been selected. Please choose another.'
                            );
                            $(this).val('');
                            $('#saveApproverChanges').prop('disabled', false).text('Save Changes');
                        } else {
                            selectedApprovers.push(selectedValue);
                            formData.append('jenisapproveremailDetail[]', selectedEmail);
                            formData.append('jenisapprovernameDetail[]', selectedName);
                        }
                    }
                });

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
                                target: '#modalPenomoranKontrak'
                            });
                            $('#editApproversModal').modal('hide');
                            $('#saveApproverChanges').prop('disabled', false).text(
                                'Save Changes');

                        } else {
                            swal.fire('Error: ' + data.message);
                        }
                        ReloadTableApprovers();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var myText = errorThrown;
                        swal.fire(myText);
                        $('#saveApproverChanges').text('Submit');
                        $('#saveApproverChanges').prop('disabled', false).text('Save Changes');;
                    }
                });
            });


            // --------------------------------------------------
            originalApproverOptionsDetail = $('#firstapproverDetail .approverSelectDetail').html();

            // Event handler untuk tombol Add di modal Edit Approvers
            $('#editApproversModal').on('click', '#btnAddDetail', function() {
                var approverCount = $('.jenisapproverdivDetail').length;
                var newApproverField = createApproverField(approverCount, null);

                $('#morejenisapproverDetail').append(newApproverField);

                // Initialize Select2 and event handlers for the new field
                initializeSelect2Detail($('.approverSelectDetail').last());
                updateApproverLabelsDetail();
                filterApproverOptionsDetail();
            });

            // Event handler untuk tombol Remove di modal Edit Approvers
            $('#editApproversModal').on('click', '#btnRemoveDetail', function() {
                var approvers = $('#morejenisapproverDetail .jenisapproverdivDetail');
                if (approvers.length > 0) {
                    approvers.last().remove();
                    filterApproverOptionsDetail();
                    updateApproverLabelsDetail();
                } else {
                    swal.fire({
                        title: 'Warning',
                        text: 'Cannot remove the first approver!',
                        icon: 'warning',
                        target: '#editApproversModal'
                    });
                }
            });

            // Event handler untuk perubahan select approver
            $('#editApproversModal').on('change', '.approverSelectDetail', function() {
                filterApproverOptionsDetail();
            });
            // filterApproverOptionsDetail();
            // updateApproverLabelsDetail();
        });

        // Fungsi untuk menginisialisasi Select2
        function initializeSelect2(element) {
            element.select2({
                placeholder: "Choose Your Approver",
                allowClear: true,
                width: '100%',
                dropdownParent: element.parent(), // Agar dropdown muncul dengan benar
            });
        }

        // Project SO/PO Field
        function toggleFields() {
            if ($('#projectSO').is(':checked')) {
                $('#soField1').show();
                $('#soField2').show();
                $('#poField1').hide();
                $('#poField2').hide();

                $('#poNumberProject').val('');
                $('#poPrincipalProject').val('');

                $('#noNumberingBy').hide();

                $('#numberingBy3').prop('checked', false);
                $('#numberingBy1').css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });
                $('#numberingBy2').css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });

            } else if ($('#projectPO').is(':checked')) {
                $('#soField1').hide();
                $('#soField2').hide();
                $('#poField1').show();
                $('#poField2').show();

                $('#soNumberProject').val('');
                $('#soCustomerProject').val('');

                $('#noNumberingBy').show();

            }
        }

        function toggleFieldsDetail() {
            if ($('#project1Detail').is(':checked')) {
                $('#soField1Detail').show();
                $('#soField2Detail').show();
                $('#poField1Detail').hide();
                $('#poField2Detail').hide();

                $('#poNumberProjectDetail').val('');
                $('#poPrincipalProjectDetail').val('');

            } else if ($('#project2Detail').is(':checked')) {
                $('#soField1Detail').hide();
                $('#soField2Detail').hide();
                $('#poField1Detail').show();
                $('#poField2Detail').show();

                $('#soNumberProjectDetail').val('');
                $('#soCustomerProjectDetail').val('');
            }
        }

        $('#numberingBy3').change(function() {
            if ($(this).is(':checked')) {
                $('#numberingBy1').prop('checked', false).css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });
                $('#numberingBy2').prop('checked', false).css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });
                $('#customerText').css({
                    'pointer-events': 'none',
                    'opacity': '0'
                });


            } else {
                $('#numberingBy1').css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });
                $('#numberingBy2').css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });

                if ($('#numberingBy1').is(':checked')) {
                    $('#customerText').css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                } else {
                    $('#customerText').css({
                        'pointer-events': 'none',
                        'opacity': '0'
                    });
                }

            }
        });

        $('#numberingBy1').change(function() {
            if ($(this).is(':checked') && !$('#numberingBy3').is(':checked')) {
                $('#customerText').css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });
            } else {
                $('#customerText').css({
                    'pointer-events': 'none',
                    'opacity': '0'
                });
            }
        });

        // Fungsi untuk memfilter opsi approver agar tidak duplikat (dengan menghapus opsi)
        function filterApproverOptions() {
            var selectedValues = [];
            $('.approverSelect').each(function() {
                if ($(this).val() !== "") {
                    selectedValues.push($(this).val());
                }
            });

            $('.approverSelect').each(function() {
                var $dropdown = $(this);
                var currentValue = $dropdown.val();

                // Kembalikan opsi ke kondisi awal
                $dropdown.html(originalApproverOptions);

                // Hapus opsi yang sudah dipilih di select lain
                $dropdown.find('option').each(function() {
                    if (selectedValues.indexOf($(this).val()) !== -1 && $(this).val() !== currentValue) {
                        $(this).remove();
                    }
                });

                // Set nilai saat ini
                $dropdown.val(currentValue);

                // Refresh Select2
                $dropdown.trigger('change.select2');
            });
        }

        // Fungsi untuk memperbarui label approver
        function updateApproverLabels() {
            $('.approver-label').each(function(index) {
                $(this).text('Approver ' + (index + 1));
            });
        }


        // --------------------------------------------------
        function modalShow(obj) {
            let id = parseInt(obj.attributes.id.value);
            $('#penomoran_id').val(obj.attributes.id.value);
            $('#iditem').val(obj.attributes.id.value);
            // $('#modalPenomoranKontrakLabel').text(obj.attributes.data_name.value);
            $('#modalPenomoranKontrakLabel').text("Detail Penomoran Kontrak");

            getModal(id);
        }

        function getModal(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{ url('/') }}/kontrak/listPenomoranKontrak/" + id,
                contentType: "application/json",
                searching: false,
                lengthMenu: [
                    [-1],
                    ['All']
                ],
                success: function(data) {
                    $('#statusApproval').text(data.data.status);

                    let status = '';

                    if (data.data.status == 'Waiting Approval') {
                        status = '<span class="badge badge-info">Waiting Approval</span>'
                    } else if (data.data.status == 'Rejected') {
                        status = '<span class="badge badge-danger">Rejected</span>'
                    } else {
                        status = '<span class="badge badge-success">Approved</span>'
                    }
                    $('#statusApp').html(status);

                    console.log('project name:', data.data.project_name);

                    $('#customer_name').val(data.data.customer_name);
                    $('#effective_date').val(data.data.tanggal_kontrak);
                    $('#compnetTextDetail').val(data.data.no_kontrak_compnet);
                    $('#customerTextDetail').val(data.data.no_kontrak_customer);

                    $('#soNumberProjectDetail').val(data.data.so_id);
                    $('#soCustomerProjectDetail').val(data.data.customer_name);
                    $('#poNumberProjectDetail').val(data.data.po_number);
                    $('#poPrincipalProjectDetail').val(data.data.supplier_id);

                    $('#effectiveDateDetail').val(data.data.tanggal_kontrak);
                    $('#jobPositionDetail').val(data.data.job_position);
                    $('#companyNameDetail').val(data.data.company_name);
                    $('#descriptionDetail').val(data.data.deskripsi);
                    $('#ProjectNameTextDetail').val(data.data.project_name);

                    // NumberingBy
                    if (data.data.no_kontrak_compnet && data.data.no_kontrak_customer) {
                        // Both exist
                        $('#numberingBy1Detail').prop('checked', true);
                        $('#numberingBy2Detail').prop('checked', true);
                    } else if (data.data.no_kontrak_compnet && !data.data.no_kontrak_customer) {
                        // Only no_kontrak_compnet exists
                        $('#numberingBy1Detail').prop('checked', false);
                        $('#numberingBy2Detail').prop('checked', true);
                    } else if (data.data.no_kontrak_customer && !data.data.no_kontrak_compnet) {
                        // Only no_kontrak_customer exists
                        $('#numberingBy1Detail').prop('checked', true);
                        $('#numberingBy2Detail').prop('checked', false);
                    } else {
                        // Neither exists
                        $('#numberingBy1Detail').prop('checked', false);
                        $('#numberingBy2Detail').prop('checked', false);
                    }

                    function updateCustomText1Detail() {
                        if ($('#numberingBy1Detail').is(':checked')) {
                            $('#customText1Detail').css({
                                'pointer-events': 'auto',
                                'opacity': '1'
                            });
                        } else {
                            $('#customText1Detail').css({
                                'pointer-events': 'none',
                                'opacity': '0.5'
                            });
                        }
                    }
                    updateCustomText1Detail();

                    $('#numberingBy1Detail').change(function() {
                        updateCustomText1Detail();
                    });
                    $('#numberingBy2Detail').change(function() {
                        if ($('#numberingBy2Detail').is(':checked')) {
                            $('#customText2Detail').css({
                                'pointer-events': 'none',
                                'opacity': '0.5'
                            });
                            // $('#compnetTextDetail').val(data.no_kontrak_compnet);
                        } else {
                            $('#customText2Detail').css({
                                'pointer-events': 'none',
                                'opacity': '0.5'
                            });
                            $('#compnetTextDetail').val('');
                        }
                    });

                    // Project SO/PO Field
                    if (data.data.so_id) {
                        $('#div_nomor_so').show()
                        $('#div_nomor_po').hide()
                        $('#nomor_so').val(data.data.so_id);
                    } else if (data.data.po_number) {
                        $('#div_nomor_po').show()
                        $('#div_nomor_so').hide()
                        $('#nomor_po').val(data.data.po_number);
                    }

                    if (data.data.so_id) {
                        $('#project1Detail').prop('checked', true);
                    } else if (data.data.po_number) {
                        $('#project2Detail').prop('checked', true);
                    }

                    // Set selected value for Number SO
                    $('#soNumberProjectDetail').val(data.data.so_id).trigger('change');

                    // Set selected value for Principal
                    $('#poPrincipalProjectDetail').val(data.data.supplier_id).trigger('change');


                    toggleFieldsDetail();
                    $('input[name="projectDetail"]').change(function() {
                        toggleFieldsDetail();
                    });

                    // Contract Template
                    if (data.data.template_kontrak == 'Customer') {
                        $('#contractTemplate1Detail').prop('checked', true);
                        $('#contractTemplateTextDetail').val('Customer');
                    } else {
                        $('#contractTemplate2Detail').prop('checked', true);
                        $('#contractTemplateTextDetail').val('Compnet');
                    }



                    $('#editApproverButton').on('click', function() {
                        $('#editApproversModal').modal('show');
                    });

                    // Approver
                    $('#firstapproverDetail').empty();
                    $('#morejenisapproverDetail').empty();

                    let approvers = data.data.approver;
                    if (approvers && approvers.length > 0) {
                        approvers.forEach((approver, index) => {
                            let approverDiv = `
                                <div class="jenisapproverdivDetail">
                                    <div class="col-12 col-xs-12 pb-3">
                                        <label class="approver-label mb-2">Approver ${index + 1}</label>
                                        <select name="jenisapproverDetail[]" class="form-select input-sm approverSelectDetail">
                                            <option value="" disabled ${approver.employee_id ? '' : 'selected'}>Choose Your Approver</option>
                                            ${data.listemployee.map(employee => `
                                                                                                                                                <option value="${employee.id}" data_email="${employee.email}" data_fullName="${employee.full_name}" ${employee.id == approver.employee_id ? 'selected' : ''}>
                                                                                                                                                    ${employee.full_name}
                                                                                                                                                </option>`).join('')}
                                        </select>
                                    </div>
                                </div>`;

                            if (index === 0) {
                                $('#firstapproverDetail').append(approverDiv);
                            } else {
                                $('#morejenisapproverDetail').append(approverDiv);
                            }
                        });
                    } else {
                        // If no approvers, create an empty field
                        let approverDiv = `
                            <div class="jenisapproverdivDetail">
                                <div class="col-12 col-xs-12">
                                    <label class="approver-label mb-2">Approver 1</label>
                                    <select name="jenisapproverDetail[]" class="form-select input-sm approverSelectDetail">
                                        <option value="" disabled selected>Choose Your Approver</option>
                                        ${data.listemployee.map(employee => `
                                                                    <option value="${employee.id}" data_email="${employee.email}" data_fullName="${employee.full_name}">
                                                                        ${employee.full_name}
                                                                    </option>
                                                                `).join('')}
                                    </select>
                                </div>
                            </div>
                        `;
                        $('#firstapproverDetail').append(approverDiv);
                    }
                    attachApproverDetailEventHandlers();

                    // Handle File Table
                    $('#fileTable').DataTable().destroy();
                    let listFile = $('#listFile');
                    let rowTable = '';
                    listFile.empty();
                    if (data.data.penomoran_kontrak_attachment) {
                        rowTable += '<tr>' +
                            '<td class="fileNameCell">' + data.data.penomoran_kontrak_attachment.file_name +
                            '</td>' +
                            '<td>' +
                            '<a href="{{ url('/') }}/penomorankontrak/' + encodeURIComponent(data.data
                                .penomoran_kontrak_attachment.file_name) +
                            '" target="_blank" class="btn btn-sm btn-primary me-2"><i class="fas fa-eye"></i> View </a>' +
                            '<button type="button" class="btn btn-sm btn-inverse uploadFileButton"><i class="fas fa-upload"></i> Update Current File </button>' +
                            '<input type="file" class="fileInputDetail" name="fileInputDetail" style="display: none;" accept="application/pdf">' +
                            '</td>' +
                            '</tr>';
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: 'No Files',
                            text: "File Doesn't Exist!",
                            showConfirmButton: false,
                        });
                        location.reload();
                        rowTable = '<tr><td style="text-align: center" colspan="2">No Data</td></tr>';
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

                    currentApproversData = data.data.approver;
                    currentListEmployee = data.listemployee;

                    originalApproverOptionsDetail = generateApproverOptions(currentListEmployee);

                    $('#btn-print').prop('disabled', true).css({
                        'opacity': '0.3'
                    });

                    updateApproverTable(data);

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

        $('#btn-print').click(function(e) {
            let id = parseInt($('#penomoran_id').val());
            window.open("{{ url('/') }}/kontrak/listPenomoranKontrak/" + id + "/print", '_blank',
                'scrollbars=yes,status=yes');
        });

        // Function to generate the options HTML
        function generateApproverOptions(employeeList) {
            return employeeList.map(employee => `
                <option value="${employee.id}" data_email="${employee.email}" data_fullName="${employee.full_name}">
                    ${employee.full_name}
                </option>
            `).join('');
        }

        // Event Modal Detail - Approver
        function attachApproverDetailEventHandlers() {
            // Initialize Select2 for new elements
            initializeSelect2Detail($('.approverSelectDetail'));

            updateApproverLabelsDetail();
            filterApproverOptionsDetail();
        }

        // Cek Duplikasi Approver Detail
        function filterApproverOptionsDetail() {
            var selectedValues = [];
            $('.approverSelectDetail').each(function() {
                var value = $(this).val();
                if (value !== "") {
                    selectedValues.push(value);
                }
            });

            $('.approverSelectDetail').each(function() {
                var $dropdown = $(this);
                var currentValue = $dropdown.val();

                // Reset options to the original unfiltered options
                $dropdown.html('<option value="" disabled>Choose Your Approver</option>' +
                    originalApproverOptionsDetail);

                // Remove options that have been selected in other selects
                $dropdown.find('option').each(function() {
                    var optionValue = $(this).val();
                    if (selectedValues.indexOf(optionValue) !== -1 && optionValue !== currentValue) {
                        $(this).remove();
                    }
                });

                // Set the current value
                $dropdown.val(currentValue);

                // Refresh Select2
                $dropdown.trigger('change.select2');
            });
        }


        function createApproverField(index, selectedValue) {
            return `
                <div class="jenisapproverdivDetail">
                    <div class="col-12 col-xs-12 pb-3">
                        <label class="approver-label mb-2">Approver ${index + 1}</label>
                        <select name="jenisapproverDetail[]" class="form-select input-sm approverSelectDetail">
                            <option value="" disabled ${selectedValue ? '' : 'selected'}>Choose Your Approver</option>
                            ${originalApproverOptionsDetail}
                        </select>
                    </div>
                </div>
            `;
        }

        // Urutan Approver Detail
        function updateApproverLabelsDetail() {
            $('.jenisapproverdivDetail').each(function(index) {
                $(this).find('.approver-label').text('Approver ' + (index + 1));
            });
        }

        // Fungsi untuk menginisialisasi Select2 pada elemen approver di modal edit
        function initializeSelect2Detail(element) {
            element.select2({
                placeholder: "Choose Your Approver",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#editApproversModal'),
            });
        }

        // --------------------------------------------------
        function updateApproverTable(data) {
            $('#approverTable').DataTable().destroy();
            let listApprover = $('#listApprover');
            let rowTable2 = '';
            listApprover.empty();
            if (data.data.approver != null && data.data.approver.length > 0) {
                data.data.approver.forEach(e => {
                    rowTable2 += '<tr>' +
                        '<td>' + (e.approver_name ?? '-') + '</td>' +
                        '<td>' + (e.status_approval ?? '-') + '</td>' +
                        '<td>' + (e.tanggal_approval ?? '-') + '</td>' +
                        '<td>' + (e.approver_level ?? '-') + '</td>' +
                        '<td>' + (e.notes ?? '-') + '</td>' +
                        '</tr>';
                });
            } else {
                swal.fire({
                    icon: 'error',
                    title: 'No Approver',
                    text: "Data Approver Doesn't Exist!",
                    showConfirmButton: false,
                });
                location.reload();
                rowTable2 = '<tr><td style="text-align: center" colspan="5">No Data</td></tr>';
            }
            listApprover.append(rowTable2);
            tableApprovers = $('#approverTable').DataTable({
                responsive: true,
                processing: true,
                paging: false,
                searching: false,
                lengthMenu: [
                    [-1],
                    ['All']
                ],
                order: [
                    [3, 'asc']
                ],
                columnDefs: [{
                    targets: 3,
                    render: function(data, type, row) {
                        return parseInt(data) || 0;
                    }
                }]
            });

            // Collect all status_approval values
            var statuses = data.data.approver.map(function(approver) {
                return approver.status_approval;
            });

            // var so_value = data.data.so_id.map(function(so_id) {
            //     return so_id;
            // });

            var so_value = data.data.so_id;


            function disableAllElements() {
                $('#customerTextDetail').prop('disabled', true);
                $('#soField1Detail').prop('disabled', true).css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });

                // $('#soNumberProjectDetail').prop('disabled', true);
                // $('#select2-soNumberProjectDetail').css({
                //     'pointer-events': 'none',
                //     'opacity': '0.5'
                // });

                $('#poNumberProjectDetail').prop('disabled', true);
                $('#poField2Detail').prop('disabled', true).css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });

                // $('#poPrincipalProjectDetail').prop('disabled', true);
                // $('#poPrincipalProjectDetail').css({
                //     'pointer-events': 'none',
                //     'opacity': '0.5'
                // });
                $('#descriptionDetail').prop('disabled', true);
                $('#ProjectNameTextDetail').prop('disabled', true);
                $('#description').css({
                    'opacity': '0.5'
                });
                $('#editApproverButton').prop('disabled', true).css({
                    'opacity': '0.3'
                });
                $('#btnSaveEdit').prop('disabled', true).css({
                    'opacity': '0.3'
                });
                $('.uploadFileButton').prop('disabled', true).css({
                    'opacity': '0.3'
                });
            }

            // Function to enable all elements
            function enableAllElements() {
                $('#customerTextDetail').prop('disabled', false);
                $('#soField1Detail').prop('disabled', false).css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });

                // $('#soNumberProjectDetail').prop('disabled', false);
                // $('#soNumberProjectDetail').css({
                //     'pointer-events': 'auto',
                //     'opacity': '1'
                // });

                $('#poNumberProjectDetail').prop('disabled', false);
                $('#poField2Detail').prop('disabled', false).css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });

                // $('#poPrincipalProjectDetail').prop('disabled', false);
                // $('#poPrincipalProjectDetail').css({
                //     'pointer-events': 'auto',
                //     'opacity': '1'
                // });
                $('#descriptionDetail').prop('disabled', false);
                $('#ProjectNameTextDetail').prop('disabled', false);
                $('#description').css({
                    'opacity': '1'
                });
                $('#editApproverButton').prop('disabled', false).css({
                    'opacity': '1'
                });
                $('#btnSaveEdit').prop('disabled', false).css({
                    'opacity': '1'
                });
                $('.uploadFileButton').prop('disabled', false).css({
                    'opacity': '1'
                });
            }

            function enableRejectedElements() {
                $('#customerTextDetail').prop('disabled', false);
                $('#soField1Detail').prop('disabled', false).css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });

                // $('#soNumberProjectDetail').prop('disabled', false);
                // $('#soNumberProjectDetail').css({
                //     'pointer-events': 'auto',
                //     'opacity': '1'
                // });

                $('#poNumberProjectDetail').prop('disabled', false);
                $('#poField2Detail').prop('disabled', false).css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });

                // $('#poPrincipalProjectDetail').prop('disabled', false);
                // $('#poPrincipalProjectDetail').css({
                //     'pointer-events': 'auto',
                //     'opacity': '1'
                // });
                $('#descriptionDetail').prop('disabled', false);
                $('#ProjectNameTextDetail').prop('disabled', false);
                $('#description').css({
                    'opacity': '1'
                });
                $('#editApproverButton').prop('disabled', true).css({
                    'opacity': '0.3'
                });
                $('#btnSaveEdit').prop('disabled', false).css({
                    'opacity': '1'
                });
                $('.uploadFileButton').prop('disabled', false).css({
                    'opacity': '1'
                });
            }

            function enableNotRegisteredSO() {
                $('#soField1Detail').prop('disabled', false).css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });

                // var customerTextDetail = $('select[name="customerTextDetail"] option:selected').text();
                // var customerTextDetail = data.data.no_kontrak_customer;
                // formData.append('customerTextDetail', customerTextDetail);

                $('#btnSaveEdit').prop('disabled', false).css({
                    'opacity': '1'
                });
            }

            function disableNotRegisteredSO() {
                $('#soField1Detail').prop('disabled', true).css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });

                $('#btnSaveEdit').prop('disabled', true).css({
                    'opacity': '0.3'
                });
            }

            var customerTextDetailValue = $('#customerTextDetail').val();

            // Check the statuses and apply logic
            if (statuses.every(function(status) {
                    return status === 'Waiting';
                })) {
                if (customerTextDetailValue === '-' && $('#project2Detail').is(':checked')) {
                    enableAllElements();
                    $('#customerTextDetail').prop('disabled', true);
                } else {
                    enableAllElements(); // All statuses are "Waiting"
                }
            } else if (statuses.includes('Approved')) {
                disableAllElements(); // At least one status is "Approved"
                if (statuses.includes('Rejected')) {
                    if (customerTextDetailValue === '-' && $('#project2Detail').is(':checked')) {
                        enableRejectedElements();
                        $('#customerTextDetail').prop('disabled', true);
                    } else {
                        enableRejectedElements(); // At least one status is "Rejected"
                    }


                }

                if (so_value == '-' && $('#project1Detail').is(':checked')) {
                    // $('#soField1Detail').prop('disabled', false).css({
                    //     'pointer-events': 'auto',
                    //     'opacity': '1'
                    // });

                    // $('#btnSaveEdit').prop('disabled', false).css({
                    //     'opacity': '1'
                    // });
                    enableNotRegisteredSO();
                    // formData.append('customerTextDetail', customerTextDetail);
                    // $('#customerTextDetail').val(data.data.no_kontrak_customer);
                } else {
                    // $('#soField1Detail').prop('disabled', true).css({
                    //     'pointer-events': 'none',
                    //     'opacity': '0.5'
                    // });

                    // $('#btnSaveEdit').prop('disabled', true).css({
                    //     'opacity': '0.3'
                    // });
                    if (statuses.includes('Rejected')) {
                        if (customerTextDetailValue === '-' && $('#project2Detail').is(':checked')) {
                            enableRejectedElements();
                            $('#customerTextDetail').prop('disabled', true);
                        } else {
                            enableRejectedElements(); // At least one status is "Rejected"
                        }

                    } else {
                        disableNotRegisteredSO();
                    }
                }
            } else if (statuses.includes('Rejected')) {
                // if(compnetTextDetailValue === '-' && customerTextDetailValue === '-'){
                //     enableRejectedElements();
                //     $('#customerTextDetail').prop('disabled', true);
                // } else {
                //     enableRejectedElements(); // At least one status is "Rejected"
                // }
                if (customerTextDetailValue === '-' && $('#project2Detail').is(':checked')) {
                    enableRejectedElements();
                    $('#customerTextDetail').prop('disabled', true);
                } else {
                    enableRejectedElements(); // At least one status is "Rejected"
                }

            } else {
                // Default case: enable all elements
                enableAllElements();
            }

            if (statuses.every(function(status) {
                    return status === 'Approved';
                })) {
                $('#btn-print').prop('disabled', false).css({
                    'opacity': '1'
                });
            }

        }



        function clearform() {
            $("#reportAdd")[0].reset();
            $("#reportAdd").parsley().reset();

            $("input[name='numberingBy[]']").prop('checked', false).trigger('change');
            $("#customerText").val('').trigger("change");

            $("input[name='contractTemplate']").prop('checked', false).first().prop('checked', true).trigger('change');
            $("#effectiveDate").val('').trigger("change");

            $("input[name='project']").prop('checked', false).first().prop('checked', true).trigger('change');
            $("#soNumberProject").val('').trigger("change");
            $("#soCustomerProject").val('').trigger("change");
            $("#poNumberProject").val('').trigger("change");
            $("#poPrincipalProject").val('').trigger("change");

            $("#jobPosition").val('').trigger("change");
            $("#companyName").val('').trigger("change");

            $("#ProjectNameText").val('');
            $("#description").val('');
            $("#fileUpload").val('');

            $('#morejenisapprover .jenisapproverdiv').remove();
            $('#firstapprover select[name="jenisapprover[]"]').val('').trigger('change');
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
                url: "{{ url('/') }}/kontrak/listPenomoranKontrak/" + id,
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
