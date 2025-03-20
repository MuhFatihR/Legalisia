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
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="row mb-2">
                        <div class="col-12">
                            <h4>
                                FILTER
                            </h4>
                            <hr class="border-bottom border-0 border-dark">
                        </div>
                    </div>
                </div>

                <div class="card-block" id="reportSOListForm">
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
                                        <label for="so_number_filter">SO Number</Title></label>
                                        <input type="text" id="so_number_filter" name="so_number_filter"
                                            class="form-control input-sm" placeholder="Search SO Number" autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="tanggal_kontrak_filter">Effective Date</Title></label>
                                        <input type="date" id="tanggal_kontrak_filter"
                                            name="tanggal_kontrak_filter"class="form-control input-sm date datetimepicker"
                                            type="text" data-min-view="2" data-date-format="yyyy-mm-dd"
                                            autocomplete="off">
                                    </div>
                                </div>

                                {{-- Row 2 --}}
                                <div class="form-group row">
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
                                        <label for="status_filter">Status</Title></label>
                                        <input type="text" id="status_filter" name="status_filter"
                                            class="form-control input-sm" placeholder="Search Status"
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

        <div class="container-fluid" id="reportPenomoranKontrakApproverResult">
            <div class="card header-info-filter">
                <div class="card-body">
                    <table id="reportPenomoranKontrakApproverTable" width="100%" class="table table-striped nowrap">
                        <thead class="mt-30">
                            <tr>
                                <th scope="col">No Kontrak Compnet</th>
                                <th scope="col">No Kontrak Customer</th>
                                <th scope="col">SO Number</th>
                                <th scope="col">Tanggal Kontrak</th>
                                <th scope="col">Customer/Partner</th>
                                <th scope="col">Job Category</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div class="modal fade" id="modalApprovalDetail" tabindex="-1" aria-labelledby="modalApprovalDetail" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-xl" id="modalApprovalSize">
            <div class="modal-content d-flex" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">
                <div class="modal-header d-flex">
                    <h5 class="modal-title" id="modalApprovalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close"></button>
                </div>

                <div class="modal-body">
                    <div class="pb-2 d-flex justify-content-end">
                        <h5 id="statusApp"> </h5>
                    </div>

                    <form action="" class="row g-3 d-flex" id="reportDetail">
                        {{ csrf_field() }}
                        <input type="hidden" id="Approval_id" value="">
                        {{-- Numbering By --}}
                        <div class="row pt-3">
                            <!-- Bagian ini tidak akan terlihat karena display: none -->
                            <div class="col-4 col-xs-12 d-flex" style="display: none !important;">
                                <label for="numberingBy" class="form-label">Numbering By: </label>
                                <div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="checkbox" name="numberingByDetail[]" id="numberingBy1Detail"
                                            value="numbercustomerDetail" class="form-check-input" disabled>
                                        <label for="numberingBy1Detail" class="form-check-label ms-2">Customer</label>
                                    </div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="checkbox" name="numberingByDetail[]" id="numberingBy2Detail"
                                            value="numbercompnetDetail" class="form-check-input" disabled>
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

                            <div class="col-6 col-xs-12" style="pointer-events: none;"
                                id="customText1Detail">
                                <label for="" class="form-label" style="opacity: 1;">Number by Customer</label>
                                <input type="text" name="customerTextDetail" id="customerTextDetail"
                                    class="form-control form-control-sm" placeholder="-" disabled>
                            </div>
                        </div>


                        {{-- Template & Project --}}
                        <div class="row pt-3">
                            <div class="col-4 col-xs-12 d-flex" style="display: none !important;">
                                <label for="projectDetail" class="form-label">Project: </label>
                                <div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="radio" name="projectDetail" id="project1Detail"
                                            value="projectSODetail" class="form-check-input" disabled checked>
                                        <label for="project1" class="form-check-label ms-2">SO </label>
                                    </div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="radio" name="projectDetail" id="project2Detail"
                                            value="projectPODetail" class="form-check-input" disabled>
                                        <label for="project2" class="form-check-label ms-2">PO </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-xs-12" id="contractTemplateTextDetailDiv">
                                <label for="" class="form-label" style="opacity: 0.5;">Contract Template</label>
                                <input type="text" name="contractTemplateTextDetail" id="contractTemplateTextDetail"
                                    class="form-control form-control-sm" placeholder="" disabled>
                            </div>

                            <div class="col-3 col-xs-12 pt-1" id="soField1Detail">
                                <label for="soNumberProjectDetail" class="form-labelDetail" style="opacity: 0.5;">Number </label>
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
                                    class="form-control form-control-sm" disabled>
                            </div>

                            <div class="col-3 col-xs-12" style="display: none" id="poField1Detail">
                                <label for="poNumberProject" class="form-label">Number </label>
                                <input type="text" name="poNumberProjectDetail" id="poNumberProjectDetail"
                                    class="form-control form-control-sm" disabled>
                            </div>

                            <div class="col-3 col-xs-12 pb-1" style="display: none" id="poField2Detail">
                                <label for="poPrincipalProject" class="form-label">Principal </label>
                                <select name="poPrincipalProjectDetail" id="poPrincipalProjectDetail" class="form-select"
                                    disabled>
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

                        {{-- Contract Template & Effective Date & Job Category --}}
                        <div class="row pt-3">
                            <div class="col-4 col-xs-12 d-flex" style="display: none !important">
                                <label for="" class="form-label">Contract Template: </label>
                                <div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="radio" name="contractTemplateDetail" id="contractTemplate1Detail"
                                            value="Customer" class="form-check-input" disabled checked>
                                        <label for="contractTemplate1" class="form-check-label ms-2">Customer</label>
                                    </div>
                                    <div class="d-flex align-items-center ms-2">
                                        <input type="radio" name="contractTemplateDetail" id="contractTemplate2Detail"
                                            value="Compnet" class="form-check-input" disabled>
                                        <label for="contractTemplate2" class="form-check-label ms-2">Compnet</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-xs-12">
                                <label for="" class="form-label" style="opacity: 0.5;">Company Name </label>
                                <select name="companyNameDetail" id="companyNameDetail" class="form-select"
                                    style="width: 100%" disabled>
                                    <option value="" disabled selected> Select Your Company </option>
                                    <option value="NCI"> PT. Nusantara Compnet Integrator </option>
                                    <option value="IOT"> PT. Inovasi Otomasi Teknologi </option>
                                    <option value="PSA"> PT. Pro Sistimatika Automasi </option>
                                    <option value ="SJT"> PT. Sugi Jaya Teknologi </option>
                                    <option value="CIS"> PT. Compnet Integrator Services </option>
                                </select>
                            </div>

                            <div class="col-6 col-xs-12">
                                <label for="" class="form-label" style="opacity: 0.5;">Job Category </label>
                                <select name="jobPositionDetail" id="jobPositionDetail" class="form-select"
                                    style="width: 100%" disabled>
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

                        {{-- Company Name & Description --}}
                        {{-- <div class="row pt-3">
                            <div class="col-6 col-xs-12">
                                <label for="effectiveDate" class="form-label" style="opacity: 0.5;">Effective Date </label>
                                <input type="date" name="effectiveDateDetail" id="effectiveDateDetail"
                                    class="form-control input-sm date datetimepicker" disabled>
                            </div>

                            <div class="col-6 col-xs-12">
                                <label for="description" class="form-label" style="opacity: 0.5;">Description </label>
                                <textarea name="descriptionDetail" id="descriptionDetail" class="form-control form-control-sm" disabled></textarea>
                            </div>
                        </div> --}}

                        <div class="row pt-3 d-flex">

                            <div class="col-6 col-xs-12">
                                <div>
                                    <label for="effectiveDate" class="form-label" style="opacity: 0.5;">Effective Date </label>
                                    <input type="date" name="effectiveDateDetail" id="effectiveDateDetail"
                                    class="form-control input-sm date datetimepicker" disabled>
                                </div>

                                <div class="pt-3" id="ProjectName">
                                    <label for="ProjectNameTextDetail" class="form-label" style="opacity: 0.5;">Project Name</label>
                                    <input type="text" name="ProjectNameTextDetail" id="ProjectNameTextDetail"
                                        class="form-control form-control-sm" disabled>
                                </div>

                            </div>

                                <div class="col-6 col-xs-12">
                                    <label for="description" class="form-label" style="opacity: 0.5;">Description </label>
                                    <textarea name="descriptionDetail" id="descriptionDetail" class="form-control form-control-sm" style="height: 80%" disabled></textarea>
                                </div>
                        </div>

                        {{-- Approver --}}
                        {{-- <div class="row pt-4">
                            <div id="firstapproverDetail">
                                <div class="jenisapproverdivDetail ">
                                    <div class="col-6 col-xs-12 pb-3">
                                        <label class="approver-label mb-2">Approver 1</label>
                                        <select name="jenisapproverDetail[]"
                                            class="form-select input-sm approverSelectDetail">
                                            @php
                                                $esql =
                                                    "SELECT DISTINCT id, full_name, email FROM employee WHERE active = '1' ORDER BY full_name";
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
                            <div id="chooseApproverDetail"></div>

                            <div class="col-md-4 col-xs-12 mt-3">
                                <button type="button" id="btnAddDetail" class="btn btn-success">+</button>
                                <button type="button" id="btnRemoveDetail" class="btn btn-danger"> - </button>
                            </div>
                        </div> --}}

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
                {{-- <form action="" class="row g-3 d-flex" id="aproveButton"> --}}
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div class="btnapprove-wrap">
                        <button type="button" class="btn btn-danger" id="btnReject">Reject</button>
                        <button type="button" class="btn btn-success" id="btnApprove">Approve</button>
                    </div>
                </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>

    {{-- Modal Approve Notes --}}
    <div class="modal fade" id="approveNotesModal" tabindex="-1" role="dialog"
        aria-labelledby="approveNotesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">
                <div class="modal-header d-flex">
                    <h5 class="modal-title" id="approveNotesModalLabel">Approve Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="" id="reportApproveNotes">
                        <input type="hidden" id="approver_id" value="">

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Notes:</label>
                            <textarea class="form-control" id="approveNotes" name="approveNotes"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btnApproveFinal">Approve Document</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Reject Notes --}}
    <div class="modal fade" id="rejectNotesModal" tabindex="-1" role="dialog" aria-labelledby="rejectNotesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">
                <div class="modal-header d-flex">
                    <h5 class="modal-title" id="rejectNotesModalLabel">Reject Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="" id="reportRejectNotes">
                        <input type="hidden" id="approver_id" value="">

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Notes:</label>
                            <textarea class="form-control" id="rejectNotes" name="rejectNotes"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="btnRejectFinal">Reject Document</button>
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

        $(document).ready(function() {
            table = $('#reportPenomoranKontrakApproverTable').DataTable({
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
                "ajax": {
                    "url": "{{ url('') }}/approver/dataApproval",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "type": "GET",
                    "contentType": 'application/json'
                }
            });
            table.columns.adjust();

            $('#submitSearch').click(function(e) {
                e.preventDefault();

                if ($.fn.DataTable.isDataTable('#reportPenomoranKontrakApproverTable')) {
                    table.destroy();
                }

                var so_number = $('#so_number_filter').val();
                var tanggal_kontrak = $('#tanggal_kontrak_filter').val();
                var no_kontrak_compnet = $('#no_kontrak_compnet_filter').val();
                var no_kontrak_customer = $('#no_kontrak_customer_filter').val();
                var customer_name = $('#customer_name_filter').val();
                var job_position = $('#job_position_filter').val();
                var status_approval = $('#status_filter').val();

                // console.log(data);
                table = $('#reportPenomoranKontrakApproverTable').DataTable({
                    "responsive": false,
                    "paging": true,
                    "scrollX": true,
                    "scrollY": "500px",
                    "scrollCollapse": true,
                    "scroller": true,
                    "order": [],
                    "autoWidth": true,
                    "searching": false,
                    "ajax": {
                        "url": "{{ url('') }}/approver/searchApproval",
                        "type": "GET",
                        "data": {
                            so_number_filter: so_number,
                            tanggal_kontrak_filter: tanggal_kontrak,
                            no_kontrak_compnet_filter: no_kontrak_compnet,
                            no_kontrak_customer_filter: no_kontrak_customer,
                            customer_name_filter: customer_name,
                            job_position_filter: job_position,
                            status_filter: status_approval
                        },
                    }
                });
            });

        });

        function modalShow(obj) {
            let id = parseInt(obj.attributes.id.value);
            $('#Approval_id').val(obj.attributes.id.value);
            $('#approver_id').val(obj.attributes.data_appid.value);
            $('#modalApprovalLabel').text("Detail Penomoran Kontrak");

            getModal(id);
        }

        function getModal(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{ url('/') }}/approver/listApproval/" + id,
                contentType: "application/json",
                searching: false,
                lengthMenu: [
                    [-1],
                    ['All']
                ],
                success: function(data) {

                    $('#statusApproval').text(data.status);

                    // Update various fields
                    let status = '';

                    if (data.status == 'Waiting Approval') {
                        status = '<span class="badge badge-info">Waiting Approval</span>'
                    } else if (data.status == 'Rejected') {
                        status = '<span class="badge badge-danger">Rejected</span>'
                    } else {
                        status = '<span class="badge badge-success">Approved</span>'
                    }
                    $('#statusApp').html(status);

                    $('#customer_name').val(data.customer_name);
                    $('#effective_date').val(data.tanggal_kontrak);
                    $('#compnetTextDetail').val(data.no_kontrak_compnet);
                    $('#customerTextDetail').val(data.no_kontrak_customer);

                    $('#soNumberProjectDetail').val(data.so_id);
                    $('#soCustomerProjectDetail').val(data.customer_name);
                    $('#poNumberProjectDetail').val(data.po_number);
                    $('#poPrincipalProjectDetail').val(data.supplier_id);

                    $('#effectiveDateDetail').val(data.tanggal_kontrak);
                    $('#jobPositionDetail').val(data.job_position);
                    $('#companyNameDetail').val(data.company_name);
                    $('#descriptionDetail').val(data.deskripsi);
                    $('#ProjectNameTextDetail').val(data.project_name);

                    // NumberingBy
                    if (data.no_kontrak_compnet && data.no_kontrak_customer) {
                        // Both exist
                        $('#numberingBy1Detail').prop('checked', true);
                        $('#numberingBy2Detail').prop('checked', true);
                    } else if (data.no_kontrak_compnet && !data.no_kontrak_customer) {
                        // Only no_kontrak_compnet exists
                        $('#numberingBy1Detail').prop('checked', false);
                        $('#numberingBy2Detail').prop('checked', true);
                    } else if (data.no_kontrak_customer && !data.no_kontrak_compnet) {
                        // Only no_kontrak_customer exists
                        $('#numberingBy1Detail').prop('checked', true);
                        $('#numberingBy2Detail').prop('checked', false);
                    } else {
                        // Neither exists
                        $('#numberingBy1Detail').prop('checked', false);
                        $('#numberingBy2Detail').prop('checked', false);
                    }

                    $('#soNumberProjectDetail').prop('disabled', true).css({
                        'background-color': '#fff', // Keep background white
                        'color': '#000', // Keep text color black
                        'opacity': '0.5' // Override the default opacity
                    });

                    $('#poPrincipalProjectDetail').prop('disabled', true).css({
                        'background-color': '#fff', // Keep background white
                        'color': '#000', // Keep text color black
                        'opacity': '0.5' // Override the default opacity
                    });

                    $('#companyNameDetail').prop('disabled', true).css({
                        'background-color': '#fff', // Keep background white
                        'color': '#000', // Keep text color black
                        'opacity': '0.5' // Override the default opacity
                    });

                    $('#jobPositionDetail').prop('disabled', true).css({
                        'background-color': '#fff', // Keep background white
                        'color': '#000', // Keep text color black
                        'opacity': '0.5' // Override the default opacity
                    });

                    $('#customText2Detail').prop('disabled', true).css({
                        'background-color': '#fff', // Keep background white
                        'color': '#000', // Keep text color black
                        'opacity': '0.5' // Override the default opacity
                    });

                    $('#contractTemplateTextDetail').prop('disabled', true).css({
                        'background-color': '#fff', // Keep background white
                        'color': '#000', // Keep text color black
                        'opacity': '0.5' // Override the default opacity
                    });

                    $('#effectiveDateDetail').prop('disabled', true).css({
                        'background-color': '#fff', // Keep background white
                        'color': '#000', // Keep text color black
                        'opacity': '0.5' // Override the default opacity
                    });

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

                    // Contract Template
                    if (data.template_kontrak == 'Customer') {
                        $('#contractTemplate1Detail').prop('checked', true);
                        $('#contractTemplateTextDetail').val('Customer');
                    } else {
                        $('#contractTemplate2Detail').prop('checked', true);
                        $('#contractTemplateTextDetail').val('Compnet');
                    }

                    $('#btnApprove').on('click', function() {
                        $('#approveNotesModal').modal('show');
                    });

                    $('#btnReject').on('click', function() {
                        $('#rejectNotesModal').modal('show');
                    });

                    // $('#editApproverButton').click(){

                    // };

                    // Approver
                    // $('#firstapproverDetail').empty();
                    // $('#morejenisapproverDetail').empty();

                    // Handle File Table
                    $('#fileTable').DataTable().destroy();
                    let listFile = $('#listFile');
                    let rowTable = '';
                    listFile.empty();
                    if (data.penomoran_kontrak_attachment) {
                        rowTable += '<tr>' +
                            '<td class="fileNameCell">' + data.penomoran_kontrak_attachment.file_name +
                            '</td>' +
                            '<td>' +
                            '<a href="{{ url('/') }}/penomorankontrak/' + encodeURIComponent(data
                                .penomoran_kontrak_attachment.file_name) +
                            '" target="_blank" class="btn btn-sm btn-primary me-2"><i class="fas fa-eye"></i> View </a>' +
                            '</tr>';
                    } else {
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

                    // Handle Approver Table
                    updateApproverTable(data);

                    // Show the modal
                    $("#modalApprovalDetail").modal('show');
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

        function updateApproverTable(data) {
            var id_approver = parseInt($('#approver_id').val());
            $('#approverTable').DataTable().destroy();
            let listApprover = $('#listApprover');
            let rowTable2 = '';
            listApprover.empty();
            if (data.approver != null && data.approver.length > 0) {
                // Temukan level approver yang dipilih sekali di luar loop
                let selectedApprover = data.approver.find(e => e.id === id_approver);
                let selectedApproverLevel = selectedApprover?.approver_level;

                // Cek apakah ada approver dengan level lebih kecil dan status 'Waiting'
                let hasLowerLevelWaiting = data.approver.some(e =>
                    e.approver_level < selectedApproverLevel && (e.status_approval === 'Waiting' || e
                        .status_approval === 'Rejected')
                );

                if (hasLowerLevelWaiting) {
                    // Disable tombol jika ada approver dengan level lebih kecil dan status 'Waiting'
                    $('#btnReject').prop('disabled', true).removeClass('btn-danger').addClass('btn-disabled');
                    $('#btnApprove').prop('disabled', true).removeClass('btn-success').addClass('btn-disabled');
                }

                // Iterasi untuk membangun tabel
                data.approver.forEach(e => {
                    rowTable2 += '<tr>' +
                        '<td>' + (e.approver_name ?? '-') + '</td>' +
                        '<td>' + (e.status_approval ?? '-') + '</td>' +
                        '<td>' + (e.tanggal_approval ?? '-') + '</td>' +
                        '<td>' + (e.approver_level ?? '-') + '</td>' +
                        '<td>' + (e.notes ?? '-') + '</td>' +
                        '</tr>';
                });

                // Setelah membangun tabel, atur tombol berdasarkan status approver yang dipilih
                if (selectedApprover) {
                    if (selectedApprover.status_approval != 'Waiting') {
                        $('#btnReject').prop('disabled', true).removeClass('btn-danger').addClass('btn-disabled');
                        $('#btnApprove').prop('disabled', true).removeClass('btn-success').addClass('btn-disabled');
                    } else if (selectedApprover.status_approval == 'Waiting') {
                        // Hanya enable tombol jika tidak ada lower level waiting
                        if (!hasLowerLevelWaiting) {
                            $('#btnReject').prop('disabled', false).removeClass('btn-disabled').addClass('btn-danger');
                            $('#btnApprove').prop('disabled', false).removeClass('btn-disabled').addClass('btn-success');
                        }
                    }
                }
            } else {
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
        }

        $('#btnApproveFinal').on('click', function() {

            $(this).prop('disabled', true).text('Please wait...');

            var form = $('#reportApproveNotes')[0];
            var formData = new FormData(form);
            var id = parseInt($('#approver_id').val());
            console.log('approver_id:', id);
            let url = "{{ url('') }}/approver/approveApproval/" + id;

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
                            icon: 'success',
                            title: 'Success',
                        });
                        $('#approveNotesModal').modal('hide');
                        $('#modalApprovalDetail').modal('hide');
                    } else {
                        swal.fire('Error: ' + data.message);
                    }
                    clearform();
                    ReloadTable();
                    $('#btnApproveFinal').prop('disabled', false).text('Approve Document');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var myText = errorThrown;
                    swal.fire(myText);
                    $('#btnApproveFinal').text('Submit');
                    $('#btnApproveFinal').prop('disabled', false).text('Approve Document');;
                }
            });
        });

        $('#btnRejectFinal').on('click', function() {

            $(this).prop('disabled', true).text('Please wait...');

            var form = $('#reportRejectNotes')[0];
            var formData = new FormData(form);
            var id = parseInt($('#approver_id').val());
            console.log('approver_id:', id);
            let url = "{{ url('') }}/approver/rejectApproval/" + id;

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
                            icon: 'success',
                            title: 'Success',
                        });
                        $('#rejectNotesModal').modal('hide');
                        $('#modalApprovalDetail').modal('hide');

                    } else {
                        swal.fire('Error: ' + data.message);
                    }
                    clearform();
                    ReloadTable();
                    $('#btnReject').prop('disabled', true);
                    $('#btnApprove').prop('disabled', true);
                    $('#btnRejectFinal').prop('disabled', false).text('Reject Document');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var myText = errorThrown;
                    swal.fire(myText);
                    $('#btnRejectFinal').text('Submit');
                    $('#btnRejectFinal').prop('disabled', false).text('Reject Document');
                }
            });

        });

        function ReloadTable() {
            table.ajax.reload(null, false);
        }

        function clearform() {
            $("#approveNotes").val('');
            $("#rejectNotes").val('');
        }

        // function attachApproverDetailEventHandlers() {
        //     // Attach event handler for change event
        //     $(document).off('change', '.approverSelectDetail').on('change', '.approverSelectDetail', function() {
        //         filterApproverOptionsDetail();
        //     });

        //     // Update labels
        //     updateApproverLabelsDetail();

        //     // Attach click handlers for add/remove buttons
        //     $('#btnAddDetail').off('click').on('click', function() {
        //         var newField = $('#firstapproverDetail .jenisapproverdivDetail:first').clone();

        //         // Reset the select value and label in the cloned field
        //         newField.find('select').val('');
        //         newField.find('.approver-label').text('');

        //         $('#morejenisapproverDetail').append(newField);
        //         filterApproverOptionsDetail();
        //         updateApproverLabelsDetail();
        //     });

        //     $('#btnRemoveDetail').off('click').on('click', function() {
        //         var approvers = $('#morejenisapproverDetail .jenisapproverdivDetail');
        //         if (approvers.length > 0) {
        //             approvers.last().remove();
        //             filterApproverOptionsDetail();
        //             updateApproverLabelsDetail();
        //         } else {
        //             swal.fire('Cannot remove the first approver.');
        //         }
        //     });
        // }
    </script>
@endsection
