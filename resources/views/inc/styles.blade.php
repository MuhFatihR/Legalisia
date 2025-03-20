<!-- Favicon icon -->
<link rel="icon" href="{{ asset('files/assets/images/favicon.ico') }}" type="image/x-icon">
<!-- Google font-->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
<!-- Required Framework -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/build/bootstrap/css/bootstrap.min.css') }}">
<!-- Date-time picker css -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/assets/pages/advance-elements/css/bootstrap-datetimepicker.css') }}">
<!-- Date-range picker css  -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/build/daterangepicker/css/daterangepicker.css') }}" />
<!-- Date-Dropper css -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/build/datedropper/css/datedropper.min.css') }}" />
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/build/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">


<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/build/DataTables/DataTables-1.13.6/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('files/build/DataTables/Responsive-2.5.0/css/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('files/build/DataTables/Buttons-2.4.1/css/buttons.bootstrap5.min.css')}}">


<!-- themify-icons line icon -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/assets/icon/themify-icons/themify-icons.css') }}">
<!-- ico font -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/assets/icon/icofont/css/icofont.css') }}">
<!-- feather Awesome -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/assets/icon/feather/css/feather.css') }}">
<!-- font Awesome -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/assets/icon/font-awesome/css/all.min.css') }}">
<!-- Style.css -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/assets/css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('files/assets/css/jquery.mCustomScrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('files/assets/scss/partials/menu/_pcmenu.scss') }}">
{{-- Select2 --}}
<link rel="stylesheet" href="{{ asset('files/bower_components/select2/dist/css/select2.min.css') }}">
<!-- sweet alert framework -->
<link rel="stylesheet" type="text/css" href="{{ asset('files/assets/css/sweetalert2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('files/assets/css/toggle-radios.css') }}">

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        background-color: white;
        padding: 3px 3px 3px 15px;
        border-radius: 2px;
        color: #000000;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #ccc;
    }


    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: auto;
        color: red;
        -webkit-user-select: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px;
        position: absolute;
        top: 1px;
        right: 1px;
        width: 20px;
    }

    .form-select {
        display: block;
        width: 96%;
        padding: .375rem 2.25rem .375rem .75rem;
        -moz-padding-start: calc(0.75rem - 3px);
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #000000;
        background-color: #fff;
        background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e);
        background-repeat: no-repeat;
        background-position: right .75rem center;
        background-size: 16px 12px;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }


</style>


@yield('css')
