@extends('layouts.app', [
    'title' => 'Portal Legal',
    'pageTitle' => 'Log Activity',
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

                <div class="card-block" id="filterLogActivity">
                    <form action="" method="" id="report">
                        <div class="row">
                            <div class="col-sm-12">
                                {{-- Row 1 --}}
                                <div class="form-group row">
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="user_filter">User</Title></label>
                                        <input type="text" id="user_filter"
                                            name="user_filter" class="form-control input-sm"
                                            placeholder="Search User" autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="tanggal_filter">Timestamp</Title></label>
                                        <input type="date" id="tanggal_filter"
                                            name="tanggal_filter"class="form-control input-sm date datetimepicker"
                                            type="text" data-min-view="2" data-date-format="yyyy-mm-dd"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="table_name_filter">Table Name</Title></label>
                                        <input type="text" id="table_name_filter" name="table_name_filter"
                                            class="form-control input-sm" placeholder="Search Table Name" autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="action_filter">Action</Title></label>
                                        <input type="text" id="action_filter" name="action_filter"
                                            class="form-control input-sm" placeholder="Search by Action"
                                            autocomplete="off">
                                    </div>
                                </div>

                                {{-- Row 2 --}}
                                <div class="form-group row">
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="method_filter">Method</Title></label>
                                        <input type="text" id="method_filter" name="method_filter"
                                            class="form-control input-sm" placeholder="Search by Method"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-sm-3 xs-mb-15">
                                        <label for="log_filter">Log</Title></label>
                                        <input type="text" id="log_filter" name="log_filter"
                                            class="form-control input-sm" placeholder="Search Log"
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

        <div class="container-fluid pb-5" id="reportLogActivityResult">
            <div class="card header-info-filter">
                <div class="card-body ">
                    <div class="button-row" style="display: flex; justify-content:end">
                    </div>

                    <table id="reportLogActivityTable" width="100%" class="table table-striped nowrap">
                        <thead class="mt-30">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">User</th>
                                <th scope="col">Timestamp</th>
                                <th scope="col">Table Name</th>
                                <th scope="col">Action</th>
                                <th scope="col">Method</th>
                                <th scope="col">Log</th>
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
@endsection

@section('scripts')
    <script>
        var snLength = 0;
        var table;
        var filter;

        var currentApproversData = null;
        var currentListEmployee = null;

        $(document).ready(function() {

            table = $('#reportLogActivityTable').DataTable({
                "responsive": false,
                "paging": true,
                "scrollX": true,
                "scrollY": false,
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
                    "url": "{{ url('') }}/log/dataLogActivity",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "type": "GET",
                    "contentType": 'application/json'
                }
            });
            table.columns.adjust();

            // --------------------------------------------------
            $('#submitSearch').click(function(e) {
                e.preventDefault();

                if ($.fn.DataTable.isDataTable('#reportLogActivityTable')) {
                    table.destroy();
                }

                var user = $('#user_filter').val();
                var tanggal = $('#tanggal_filter').val();
                var table_name = $('#table_name_filter').val();
                var action = $('#action_filter').val();
                var method = $('#method_filter').val();
                var log = $('#log_filter').val();

                table = $('#reportLogActivityTable').DataTable({
                    "responsive": false,
                    "paging": true,
                    "scrollX": true,
                    "scrollY": false,
                    "scrollCollapse": true,
                    "scroller": true,
                    "order": [],
                    "autoWidth": true,
                    "searching": false,
                    "ajax": {
                        "url": "{{ url('') }}/log/searchLogActivity",
                        "type": "GET",
                        "data": {
                            user_filter: user,
                            tanggal_filter: tanggal,
                            table_name_filter: table_name,
                            action_filter: action,
                            method_filter: method,
                            log_filter: log
                        },
                    }
                });
            });

        });
    </script>
@endsection
