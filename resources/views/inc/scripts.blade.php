<!-- Warning Section Starts -->
<!-- Older IE warning message -->
<!--[if lt IE 10]>
    <div class="ie-warning">
        <h1>Warning!!</h1>
        <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
        <div class="iew-container">
            <ul class="iew-download">
                <li>
                    <a href="http://www.google.com/chrome/">
                        <img src="{{ asset('files/assets/images/browser/chrome.png') }}" alt="Chrome">
                        <div>Chrome</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.mozilla.org/en-US/firefox/new/">
                        <img src="{{ asset('files/assets/images/browser/firefox.png') }}" alt="Firefox">
                        <div>Firefox</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.opera.com">
                        <img src="{{ asset('files/assets/images/browser/opera.png') }}" alt="Opera">
                        <div>Opera</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.apple.com/safari/">
                        <img src="{{ asset('files/assets/images/browser/safari.png') }}" alt="Safari">
                        <div>Safari</div>
                    </a>
                </li>
                <li>
                    <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                        <img src="{{ asset('files/assets/images/browser/ie.png') }}" alt="">
                        <div>IE (9 & above)</div>
                    </a>
                </li>
            </ul>
        </div>
        <p>Sorry for the inconvenience!</p>
    </div>
<![endif]-->
<!-- Warning Section Ends -->
<!-- Required Jquery -->
<script type="text/javascript" src="{{asset('files/build/jquery/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/jquery-ui/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/popper.js/js/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/bootstrap/js/bootstrap.min.js')}}"></script>

<!-- jquery slimscroll js -->
<script type="text/javascript" src="{{asset('files/build/jquery-slimscroll/js/jquery.slimscroll.js')}}"></script>

<!-- modernizr js -->
<script type="text/javascript" src="{{asset('files/build/modernizr/js/modernizr.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/modernizr/js/css-scrollbars.js')}}"></script>

<!-- Bootstrap date-time-picker js -->
<script type="text/javascript" src="{{asset('files/assets/pages/advance-elements/moment-with-locales.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/assets/pages/advance-elements/bootstrap-datetimepicker.min.js')}}"></script>

<!-- Date-range picker js -->
<script type="text/javascript" src="{{asset('files/build/daterangepicker/js/daterangepicker.js')}}"></script>

<!-- Date-dropper js -->
<script type="text/javascript" src="{{asset('files/build/datedropper/js/datedropper.min.js')}}"></script>

<!-- data-table js -->
<script type="text/javascript" src="{{asset('files/build/datatables.net/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('files/assets/pages/data-table/js/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/datatables.net-responsive/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/datatables.net-responsive-bs4/js/responsive.bootstrap4.js')}}"></script>

<script type="text/javascript" src="{{asset('files/build/DataTables/Buttons-2.4.1/js/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/DataTables/Buttons-2.4.1/js/buttons.bootstrap5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/DataTables/Buttons-2.4.1/js/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/DataTables/JSZip-3.10.1/jszip.min.js')}}"></script>

<!-- ck editor -->
<script type="text/javascript" src="{{asset('files/assets/pages/ckeditor/ckeditor.js')}}"></script>

<!-- i18next.min.js -->
<script type="text/javascript" src="{{asset('files/build/i18next/js/i18next.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/i18next-xhr-backend/js/i18nextXHRBackend.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/build/jquery-i18next/js/jquery-i18next.min.js')}}"></script>

<script type="text/javascript" src="{{asset('files/assets/js/pcoded.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/assets/js/vartical-layout.min.js')}}"></script>
<script type="text/javascript" src="{{asset('files/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>

<!-- Custom js -->
<script type="text/javascript" src="{{asset('files/assets/js/script.js')}}"></script>

{{-- Select2 --}}
<script type="text/javascript" src="{{asset('files/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

{{-- PARSLEY --}}
<script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>

<!-- sweet alert js -->
<script type="text/javascript" src="{{asset('files/assets/js/sweetalert2.min.js')}}"></script>

<script>
    $(document).ready(function () {
        $("#searchEmployee").select2({
            placeholder: $(this).data("placeholder") ?? 'Select Employee',
            dropdownParent: $('#loginasModal'),
            width: '100%',
            templateResult: function(data) {
                if (data.title) {
                    return $('<div class="m-0">' +
                        '<p class="m-0">' + data.text + '</p>' +
                        '<p class="small mb-0">' + data.title + '</p>' +
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
                var title = data.title.toLowerCase();
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
    });
</script>

@yield('scripts')
