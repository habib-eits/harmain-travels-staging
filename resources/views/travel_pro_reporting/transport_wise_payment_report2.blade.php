<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umrah Sale Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 14px;
            color: #333;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #d7d7d7;
            padding-bottom: 10px;
        }

        .header h2 {
            color: #0455c6;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header p {
            margin: 0;
            font-size: 13px;
            line-height: 1.4;
        }

        .section-title {
            background: #0455c6;
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 6px 0;
            margin-top: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 10px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .date-heading {
            background: #f5f5f5;
            color: #c67504;
            font-weight: 600;
            padding: 6px;
        }

        .total-row td {
            font-weight: 600;
            color: #0455c6;
        }

        .grand-total td {
            font-weight: 700;
            background: #f2f2f2;
            color: #0455c6;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
        }

        .text-upper {
            text-transform: uppercase;
        }

        /* ---------- PRINT ---------- */
        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm 15mm 12mm 15mm;
            }

            html,
            body {
                width: 297mm;
                height: 210mm;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                margin: 0;
                padding: 0;
            }

            table,
            th,
            td {
                border: 1px solid #888 !important;
            }

            th {
                background-color: #e9e9e9 !important;
                -webkit-print-color-adjust: exact;
            }

            .section-title {
                background-color: #0455c6 !important;
                color: #fff !important;
            }

            .date-heading {
                background-color: #f5f5f5 !important;
                color: #c67504 !important;
            }

            .grand-total td {
                background-color: #f2f2f2 !important;
                color: #0455c6 !important;
            }

            .header {
                border-bottom: 2px solid #999 !important;
            }
        }
    </style>





</head>

<body>

    <div class="container-fluid px-5">

        <!-- HEADER -->
        <div class="header mt-3">
            <h2>{{ $company->Name }}</h2>
            <p>{{ $company->Address }}<br>
                Phone #: {{ $company->Phone }}<br>
                Email: {{ $company->Email }} | Web: {{ $company->Website }}</p>
        </div>

        <div class="section-title">
            Transport Wise Payment Report (All Cities)


        </div>

        <div class="d-flex justify-content-between my-2">
            <div><strong>From:</strong> {{ dateformatman2(request()->StartDate) }}</div>
            <div><strong>To:</strong> {{ dateformatman2(request()->EndDate) }}</div>
        </div>




        @if (request()->ReportType == 'Detail')
            <!-- TABLE -->
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="width: 2%">Sr #</th>
                        <th style="width: 2%">HV #</th>
                        <th style="width: 2%">Inv. Date</th>
                        <th style="width: 2%">Client Name</th>
                        <th style="width: 2%">Guest Name</th>
                        <th style="width: 2%">Passport</th>
                        <th style="width: 2%">City</th>

                        <th style="width: 2%">Date</th>
                        <th style="width: 2%">Time</th>

                        <th style="width: 2%">Pax</th>
                        <th style="width: 2%">Sector</th>
                        <th style="width: 2%">Type</th>
                        <th style="width: 2%">Qty</th>
                        {{-- <th style="width: 2%">Purchase</th> --}}
                        <th style="width: 2%">Payable</th>
                        {{-- <th style="width: 2%">Sale</th> --}}
                        <th style="width: 2%">Receivable</th>

                        <th style="width: 2%">Supplier Name</th>
                        <th style="width: 2%">BRN</th>
                        @if (request()->profit == 1)
                            <th style="width: 2%">Receivable</th>
                            <th style="width: 2%">Profit</th>
                        @endif

                    </tr>
                </thead>

                <tbody>
                    @php
                        $totalQty = 0;
                        $totalPayable = 0;
                        $totalReceivable = 0;
                        $totalProfit = 0;
                    @endphp

                    @foreach ($invoice_transport as $item)
                        @php

                            $guest = DB::table('umrah_invoice_passengers')
                                ->where('umrah_invoice_master_id', $item->InvoiceMasterID)
                                ->where('relation_type', 'Head')
                                ->first();

                            $supplier = DB::table('party')->where('PartyID', $item->SupplierID)->first();
                            $totalQty += $item->Quantity ?? 0;
                            $totalPayable += $item->TransportPayable ?? 0;
                            $totalReceivable += $item->TransportReceivable ?? 0;
                            $totalProfit += $item->TransportReceivable - $item->TransportPayable ?? 0;
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->InvoiceMasterID }}</td>
                            <td class="text-upper">{{ $item->invoiceMaster->Date }}</td>
                            <td class="text-upper">{{ $item->invoiceMaster->party->PartyName }}</td>
                            <td>{{ $guest->passenger_name ?? 'NA' }}</td>
                            <td>{{ $guest->passport_no ?? 'NA' }}</td>
                            <td>{{ $item->DestinationTo ?? 'NA' }}</td>
                            <td>{{ date('d-m-Y', strtotime($item->TransportDate)) }}</td>
                            <td>{{ $item->PickupTime }}</td>


                            <td>{{ $item->Quantity ?? 'NA' }}</td>
                            <td>{{ $item->Sector ?? 'NA' }}</td>

                            <td>{{ $item->VehicleType }}</td>
                            <td>{{ $item->Quantity ?? 'NA' }}</td>
                            {{-- <td align="right">{{ number_format($item->TransportPurchase, 2) }}</td> --}}
                            <td align="right">{{ number_format($item->TransportPayable, 2) }}</td>
                            {{-- <td align="right">{{ number_format($item->TransportSale, 2) }}</td> --}}
                            <td align="right">{{ number_format($item->TransportReceivable, 2) }}</td>
                            <td>{{ $supplier->PartyName }}</td>
                            <td>{{ $item->TransportBrnCode }}</td>
                            @if (request()->profit == 1)
                                <td align="right">{{ number_format($item->TransportReceivable, 2) }}</td>
                                <td align="right">
                                    {{ number_format($item->TransportReceivable - $item->TransportPayable, 2) }}</td>
                            @endif
                        </tr>
                    @endforeach

                    {{-- ✅ TOTAL ROW --}}
                    <tr style="font-weight: bold; border-top: 1px solid #000;">
                        <td colspan="9" align="right">TOTAL</td>
                        <td>{{ $totalQty }}</td>
                        <td colspan="2"></td>
                        <td>{{ $totalQty }}</td>
                        <td align="right">{{ number_format($totalPayable, 2) }}</td>
                        <td colspan="2"></td>

                        @if (request()->profit == 1)
                            <td align="right">{{ number_format($totalReceivable, 2) }}</td>
                            <td align="right">{{ number_format($totalProfit, 2) }}</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        @endif






        @include('travel_pro_reporting.footer')

    </div>

</body>

</html>
