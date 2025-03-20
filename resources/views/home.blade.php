@extends('layouts.app')
@section('title', 'My Home')
@section('css')
  <style>
    .header-navbar .navbar-wrapper .navbar-container .header-notification .show-notification:after, .header-navbar .navbar-wrapper .navbar-container .header-notification .profile-notification:after {
      box-shadow: none;
    }
  </style>
@endsection
@section('content')
<!-- Page-body start -->
  <div class="page-body">
    <div class="row">
      <div class="col-lg-12">
        <!-- tab header start -->
        <div class="tab-header card">
          <ul class="nav nav-tabs md-tabs tab-timeline" role="tablist" id="mytab">
            <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="tab" href="#personal" role="tab">Personal Info</a>
              <div class="slide"></div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#certifications" role="tab">Certification</a>
              <div class="slide"></div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#examinations" role="tab">Examination</a>
              <div class="slide"></div>
            </li>
          </ul>
        </div>
        <!-- tab header end -->
        <!-- tab content start -->
        <div class="tab-content">
          <!-- tab panel personal start -->
          <div class="tab-pane active" id="personal" role="tabpanel">
            <div class="row">
              <div class="col-xl-3">
                <div class="card">
                  <div class="card-header contact-user text-center">
                    @if(Auth::user()->photo)
                    <img class="img-radius" src="data:image/png;base64,{{ Auth::user()->photo }}" alt="{{ auth()->user()->name }}">
                    @else
                    <img class="img-radius" src="{{ asset('files/assets/images/user-profile.png') }}" alt="{{ auth()->user()->name }}">
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-xl-9">
                <!-- personal card start -->
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-header-text">About Me</h5>
                  </div>
                  <div class="card-block">
                    <div class="view-info">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="general-info">
                            <div class="row">
                              <div class="col-lg-12 col-xl-6">
                                <div class="table-responsive">
                                  <table class="table m-0">
                                    <tbody>
                                      <tr>
                                        <th scope="row">Name</th>
                                        <td>{{ auth()->user()->name??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Gender</th>
                                        <td>{{ auth()->user()->gender??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Mobile Number</th>
                                        <td>{{ auth()->user()->mobile??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Email</th>
                                        <td>{{ auth()->user()->email??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Location</th>
                                        <td>{{ auth()->user()->location??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Level</th>
                                        <td>{{ auth()->user()->level??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Report to</th>
                                        <td>{{ $spvname ?? '' }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Manager</th>
                                        <td>{{ $mgrname ?? '' }}</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <!-- end of table col-lg-6 -->
                              <div class="col-lg-12 col-xl-6">
                                <div class="table-responsive">
                                  <table class="table">
                                    <tbody>
                                      <tr>
                                        <th scope="row">Badge ID</th>
                                        <td>{{ auth()->user()->badgeid??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">NIK</th>
                                        <td>{{ auth()->user()->nik??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Job Title</th>
                                        <td>{{ auth()->user()->title??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Section</th>
                                        <td>{{ auth()->user()->section??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Department</th>
                                        <td>{{ auth()->user()->department??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Division</th>
                                        <td>{{ auth()->user()->division??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Directorate</th>
                                        <td>{{ auth()->user()->directorate??"-" }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Company</th>
                                        <td>{{ auth()->user()->company??"-" }}</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <!-- end of table col-lg-6 -->
                            </div>
                            <!-- end of row -->
                          </div>
                          <!-- end of general info -->
                        </div>
                        <!-- end of col-lg-12 -->
                      </div>
                      <!-- end of row -->
                    </div>
                    <!-- end of view-info -->
                  </div>
                  <!-- end of card-block -->
                </div>
                <!-- personal card end-->
              </div>
            </div>
          </div>
          <!-- tab pane personal end -->
          <!-- tab pane info start -->
          <div class="tab-pane" id="certifications" role="tabpanel">
            <div class="row">
              <div class="col-xl-3">
                <!-- user contact card left side start -->
                <div class="card">
                  <div class="card-header contact-user">
                    @if(Auth::user()->photo)
                    <img class="img-radius img-40" src="data:image/png;base64,{{ Auth::user()->photo }}" alt="{{ auth()->user()->name }}">
                    @else
                    <img class="img-radius img-40" src="{{ asset('files/assets/images/user-profile.png') }}" alt="{{ auth()->user()->name }}">
                    @endif
                    <h5 class="m-l-10">{{ auth()->user()->name }}</h5>
                  </div>
                  <div class="card-block groups-contact">
                    <h4>Certificates</h4>
                    <ul class="list-group">
                      <li class="list-group-item justify-content-between">
                        Android
                        <span class="badge bg-success rounded-pill">1</span>
                      </li>
                      <li class="list-group-item justify-content-between">
                        Cisco
                        <span class="badge bg-primary rounded-pill">1</span>
                      </li>
                      <li class="list-group-item justify-content-between">
                        Google
                        <span class="badge bg-danger rounded-pill">1</span>
                      </li>
                      <li class="list-group-item justify-content-between">
                        Microsoft
                        <span class="badge bg-info rounded-pill">2</span>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Will Expire</h4>
                  </div>
                  <div class="card-block">
                    <div class="connection-list">
                      <ul>
                        <li>MCAA</li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!-- user contact card left side end -->
              </div>
              <div class="col-xl-9">
                <!-- info card start -->
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-header-text">Certification</h5>
                  </div>
                  <div class="card-block">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="card b-l-success business-info services m-b-20">
                          <div class="card-header">
                            <div class="service-header">
                              <h5 class="card-header-text">AD 401</h5>
                            </div>
                          </div>
                          <div class="card-block">
                            <div class="row">
                              <div class="col-sm-12">
                                <p class="task-detail">Android Developer Certification</p>
                              </div>
                              <!-- end of col-sm-8 -->
                            </div>
                            <!-- end of row -->
                          </div>
                          <!-- end of card-block -->
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="card b-l-danger business-info services m-b-20">
                          <div class="card-header">
                            <div class="service-header">
                              <h5 class="card-header-text">GDC</h5>
                            </div>
                          </div>
                          <div class="card-block">
                            <div class="row">
                              <div class="col-sm-12">
                                <p class="task-detail">Google Developer Certification</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="card b-l-primary business-info services m-b-20">
                          <div class="card-header">
                            <div class="service-header">
                              <h5 class="card-header-text">CCNA</h5>
                            </div>
                          </div>
                          <div class="card-block">
                            <div class="row">
                              <div class="col-sm-12">
                                <p class="task-detail">Cisco Certification Network Associate</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="card b-l-info business-info services m-b-20">
                          <div class="card-header">
                            <div class="service-header">
                              <h5 class="card-header-text">MCAF</h5>
                            </div>
                          </div>
                          <div class="card-block">
                            <div class="row">
                              <div class="col-sm-12">
                                <p class="task-detail">Microsoft Certified: Azure Fundamentals</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="card b-l-info business-info services m-b-20">
                          <div class="card-header">
                            <div class="service-header">
                              <h5 class="card-header-text">MCAA</h5>
                            </div>
                          </div>
                          <div class="card-block">
                            <div class="row">
                              <div class="col-sm-12">
                                <p class="task-detail">Microsoft Certified: Azure Administrator</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- info card end -->
              </div>
            </div>
          </div>
          <!-- tab pane info end -->
          <!-- tab pane contact start -->
          <div class="tab-pane" id="examinations" role="tabpanel">
            <div class="row">
              <div class="col-sm-12">
                <!-- contact data table card start -->
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-header-text">Examination</h5>
                  </div>
                  <div class="card-block contact-details">
                    <div class="data_table_main table-responsive dt-responsive">
                      <table id="simpletable" class="table table-striped table-bordered nowrap">
                        <thead>
                          <tr>
                            <th>Exam Code</th>
                            <th>Name</th>
                            <th>Exam Date</th>
                            <th>Exam Result</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>AZ-104</td>
                            <td>Microsoft Certified: Azure Administrator Associate</td>
                            <td>{{ \Carbon\Carbon::now()->addWeeks(2)->format("d-M-Y") }}</td>
                            <td>-</td>
                            <td class="dropdown">
                              <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-cog" aria-hidden="true"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right b-none contact-menu">
                                <a class="dropdown-item" href="javascript:void();"><i class="icofont icofont-edit"></i>Edit</a>
                                <a class="dropdown-item" href="javascript:void();"><i class="icofont icofont-ui-delete"></i>Delete</a>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>Exam Code</th>
                            <th>Name</th>
                            <th>Exam Date</th>
                            <th>Exam Result</th>
                            <th>Action</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- contact data table card end -->
              </div>
            </div>
          </div>
        </div>
        <!-- tab content end -->
      </div>
    </div>
  </div>
  <!-- Page-body end -->
@endsection
@section('scripts')
<script>
$(document).ready(function() {
  $('#simpletable').DataTable({
    "paging": true,
    "ordering": true,
    "bLengthChange": true,
    "info": true,
    "searching": true
  });

  $("a[data-toggle=\"tab\"]").on("shown.bs.tab", function(e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  });
});
</script>
@endsection
