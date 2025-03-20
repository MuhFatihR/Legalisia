@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Approval</title>

    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('files/assets/images/favicon.ico') }}" type="image/x-icon">

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">

    <!-- Required Framework -->
    <link rel="stylesheet" type="text/css" href="{{ asset('files/build/bootstrap/css/bootstrap.min.css') }}">

    <!-- Other CSS files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('files/assets/css/style.css') }}">
    <!-- Include other CSS files as needed -->

    <!-- Custom Styles -->
    <style>
        @media print {
            body {
                font-family: Tahoma, sans-serif;
                font-size: 10pt;
                margin: 0;
            }

            .print-page {
                page-break-after: always;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                margin-left: 5%;
                margin-right: 5%;
            }

            .print-page:last-child {
                page-break-after: auto;
            }

            .content {
                flex: 1;
                /* margin: 0 auto; */
                width: 100%;
            }

            footer {
                width: 100%;
            }

            .adjusted-table {
                table-layout: auto;
                width: 100%;
                border: 1px solid black;
            }

            td {
                word-wrap: normal;
                white-space: normal;
                font-size: 10pt;
            }

            .footer-divider {
                display: block !important;
                border: 0;
                height: 1px !important;
                background-color: black !important;
                margin-top: 1rem !important;
                margin-bottom: 1rem !important;
            }

            hr {
                border-top: solid 1px #000 !important;
                color: black !important;
                background-color: black !important;
            }

        }

        hr.footer-divider {
            display: block !important;
            border: 0;
            height: 1px !important;
            background-color: black !important;
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
        }

        hr {
            border-top: solid 1px #000 !important;
            color: black !important;
            background-color: black !important;
        }
    </style>

</head>

<body>
    <div class="print-page">
        <div class="content mt-3">
            <!-- Header -->
            <div class="page-header d-flex justify-content-center">
                <span style="text-align: center">
                    <h6><b>LEMBAR PENERUS</b></h6>
                    <h6><b>DIVISI LEGAL</b></h6>
                    <h6><b>PT. NUSANTARA COMPNET INTEGRATOR</b></h6>
                </span>
            </div>

            <div style="border-block-style: double;" class="container mb-3 mt-2"></div>

            <!-- First Table -->
            <table class="container table table-bordered mb-5" style="border: 1px solid black;">
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">Customer</td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;">{{ $data->customer_name }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">Agreement Name</td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
            </table>

            <!-- Adjusted Table -->
            <table class="table table-bordered adjusted-table">
                <tr>
                    <td class="text-center" colspan="1">NO. AGENDA</td>
                    <td class="text-center" colspan="2">DITERIMA TANGGAL</td>
                    <td class="text-center" colspan="6">TINGKAT SURAT</td>
                </tr>
                <tr>
                    <td class="text-center" style="padding: 6px 9px; line-height: 1;"></td>
                    <td class="text-center" style="padding: 6px 9px; line-height: 1;" colspan="2"></td>
                    <td class="text-center" style="padding: 6px 9px; line-height: 1;">RAHASIA</td>
                    <td class="text-center" style="padding: 6px 9px; line-height: 1;"></td>
                    <td class="text-center" style="padding: 6px 9px; line-height: 1;">PENTING</td>
                    <td class="text-center" style="padding: 6px 9px; line-height: 1;"></td>
                    <td class="text-center" style="padding: 6px 9px; line-height: 1;">BIASA</td>
                    <td class="text-center" style="padding: 6px 9px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td class="text-center">DITERUSKAN KEPADA</td>
                    <td class="text-center">TERIMA</td>
                    <td class="text-center">TERUSKAN</td>
                    <td class="text-center" colspan="2">ACTION</td>
                    <td class="text-center" colspan="2">COMMENT</td>
                    <td class="text-center" colspan="2">PARAF</td>
                </tr>
                <tr>
                    <td class="text-start">{{ $data->nama_uploader }}</td>
                    <td class="text-start">{{ $data->date_created }}</td>
                    <td class="text-start">{{ $data->date_created }}</td>
                    <td class="text-start" colspan="2">Create</td>
                    <td class="text-start" colspan="2">{{ $data->deskripsi }}</td>
                    <td class="text-start" colspan="2">{{ $data->date_created }}</td>
                </tr>
                @php
                    $prevApproval = null;
                @endphp
                @foreach ($data->approver as $approval)
                    <tr>
                        <td class="text-start">{{ $approval->approver_name }}</td>
                        <td class="text-start">
                            @if ($approval->approver_level == 1)
                                {{ $data->date_created }}
                            @else
                                @if ($prevApproval)
                                    {{ $prevApproval->tanggal_approval }}
                                @else
                                    {{ $data->date_created }}
                                @endif
                            @endif
                        </td>
                        <td class="text-start">{{ $approval->tanggal_approval }}</td>
                        <td class="text-start" colspan="2">{{ $approval->status_approval }}</td>
                        <td class="text-start" colspan="2">{{ $approval->notes }}</td>
                        <td class="text-start" colspan="2">{{ $approval->tanggal_approval }}</td>
                    </tr>
                    @php
                        $prevApproval = $approval;
                    @endphp
                @endforeach
            </table>

            <h7 class="pt-5" style="text-decoration: underline; margin-left:3%;">DISPOSISI :</h7>

        </div>

        <hr class="footer-divider">

        <footer class="d-flex justify-content-between">
            <p>Date of Issued © 9/9/2024</p>
            <p>Draft by Jacklin</p>
        </footer>
    </div>

    <!-- Second Page -->
    <div class="print-page">
        <div class="content container mt-5">
            <!-- Header -->
            <div class="page-header d-flex justify-content-center gap-5">
                <img src="{{ asset('files/assets/images/compnet-logo-print.jpg') }}" alt="compnet-logo.jpg"
                    style="max-width: 20%; height: auto;">
                <span style="text-align: start">
                    <h6><b>EXECUTIVE SUMMARY</b></h6>
                    <h6><b>NUSANTARA COMPNET INTEGRATOR</b></h6>
                    <h6><b>LEGAL DEPT.</b></h6>
                </span>
            </div>

            <div style="border-block-style: double;" class="container mb-3 mt-3"></div>

            <h7><b>Customer Information</b></h7>
            <table class="table table-bordered" style="border: 1px solid black;">
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1; width: 25%;">Customer Name</td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1; width: 5%">:</td>
                    <td style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1; width: 25%;">Contract Name</td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1; width: 5%;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1; width: 25%;">Contract Number</td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1; width: 5%;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">Contract Date</td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">Account Manager /
                        <br>Requestor
                    </td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">SO</td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">PSSE</td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
            </table>

            <h7 class="mt-2"><b>Internal Information</b></h7>
            <table class="table table-bordered" style="border: 1px solid black;">
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1; width: 25%;">Para Pihak
                        <i><br>Parties</i>
                    </td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1; width: 5%;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">Jangka Waktu
                        <i><br>Period</i>
                    </td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">Ruang Lingkup
                        <i><br>Scope</i>
                    </td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">Harga dan tata cara
                        <br>pembayaran <i><br>Price and term of <br>payment</i>
                    </td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;"></td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;"></td>
                    <td style="font-size: 12px; padding: 2px 5px; line-height: 1;">:</td>
                    <td colspan="2" style="padding: 2px 5px; line-height: 1;"></td>
                </tr>
            </table>
        </div>

        <hr class="footer-divider">

        <footer class="d-flex justify-content-between" style="padding: 10px;">
            <p>Date of Issued © 9/9/2024</p>
            <p>Draft by Jacklin</p>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Include other scripts as needed -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Print Script -->
    <script>
        window.print();
    </script>
</body>

</html>
