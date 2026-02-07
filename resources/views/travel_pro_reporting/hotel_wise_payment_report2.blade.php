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

  th, td {
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

    html, body {
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

    table, th, td {
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
    Hotel Wise Payment Report (All Cities)

)
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
      <th>Sr #</th>
      <th>HV #</th>
      <th>Client Name</th>
      <th>City</th>
      <th>CheckIn</th>
      <th>CheckOut</th>
      <th>Nights</th>
      <th>Hotel Name</th>
      <th>Room Type</th>
      <th>Total Pax</th>
      <th>Supplier Name</th>
      <th>BRN</th>
      <th>Payable</th>
      @if(request()->profit == 1)
      <th>Receivable</th>
      <th>Profit</th>
      @endif

    </tr>
  </thead>

  <tbody>
    @php
      $totalNights = 0;
      $totalPax = 0;
      $totalPayable = 0;
      $totalReceivable = 0;
      $totalProfit = 0;
    @endphp

    @foreach($invoice_hotel as $item)
      @php
        $totalNights += $item->Nights;
        $totalPax += $item->HotelPax;
        $totalPayable += $item->HotelPayable;
        $totalReceivable += $item->HotelReceivable;
        $totalProfit += $item->HotelReceivable - $item->HotelPayable;

      @endphp

      <tr>
        <td align="center">{{ $loop->iteration }}</td>
        <td align="center">{{ $item->InvoiceMasterID }}</td>
        <td class="text-upper">{{ $item->party->PartyName }}</td>
        <td align="center">{{ $item->HotelCity }}</td>
        <td align="center">{{ dateformatman($item->CheckInDate) }}</td>
        <td align="center">{{ dateformatman($item->CheckOutDate) }}</td>
        <td align="center">{{ $item->Nights }}</td>
        <td>{{ optional($item->hotel_name)->hotel_name }}</td>
        <td>{{ $item->RoomType }}</td>
        <td align="center">{{ $item->HotelPax }}</td>
        <td>{{ optional($item->party)->PartyName }}</td>
        <td>{{ $item->BRN }}</td>
        <td align="right">{{ number_format($item->HotelPayable, 2) }}</td>
        @if(request()->profit == 1)
        <td align="right">{{ number_format($item->HotelReceivable, 2) }}</td>
        <td align="right">{{ number_format($item->HotelReceivable-$item->HotelPayable, 2) }}</td>
        @endif
      </tr>
    @endforeach

    <!-- Total Row -->
    <tr class="fw-bold" style="background:#f2f2f2;">
      <td colspan="6" align="right">Total:</td>
      <td align="center">{{ $totalNights }}</td>
      <td colspan="2"></td>
      <td align="center">{{ $totalPax }}</td>
      <td colspan="2" align="right">Total Payable:</td>
      <td align="right">{{ number_format($totalPayable, 2) }}</td>
      @if(request()->profit == 1)
      <td align="right">{{ number_format($totalReceivable, 2) }}</td>
      <td align="right">{{ number_format($totalProfit, 2) }}</td>
      @endif
    </tr>
  </tbody>
</table>
@endif


@if (request()->ReportType == 'Summary')
  <strong>Summary</strong>

<table class="table table-sm">
  <thead>
    <tr>
      <th align="center">Sr #</th>
      <th align="center">City</th>
      <th style="text-align: left;">Hotel Name</th>
      <th style="text-align: left;">Supplier Name</th>
      <th align="center">Payable</th>
      @if(request()->profit == 1)
      <th align="center">Receivable</th>
      <th align="center">Profit</th>
      @endif
    </tr>
  </thead>
  <tbody>
    @php $grandTotalPayable = 0; @endphp
    @php $grandTotalReceivable = 0; @endphp

    @foreach($invoice_hotel1 as $item)
      @php $grandTotalPayable += $item->TotalPayable; @endphp
      @php $grandTotalReceivable += $item->TotalReceivable; @endphp
      <tr>
        <td width="50" align="center">{{ $loop->iteration }}</td>
        <td align="center">{{ $item->HotelCity }}</td>
        <td>{{ optional($item->hotel_name)->hotel_name }}</td>
        <td>{{ optional($item->party)->PartyName }}</td>
        <td align="right">{{ number_format($item->TotalPayable, 2) }}</td>
       @if(request()->profit == 1)
        <td align="right">{{ number_format($item->TotalReceivable, 2) }}</td>
        <td align="right">{{ number_format($item->TotalReceivable-$item->TotalPayable, 2) }}</td>
        @endif
      </tr>
    @endforeach

    {{-- Grand Total Row --}}
    <tr>
      <th colspan="4" align="right" style="text-align: right;">Grand Total</th>
      <th style="text-align: right;">{{ number_format($grandTotalPayable, 2) }}</th>
    @if(request()->profit == 1)
      <th style="text-align: right;">{{ number_format($grandTotalReceivable, 2) }}</th>
      <th style="text-align: right;">{{ number_format($grandTotalReceivable-$grandTotalPayable, 2) }}</th>
      @endif
    </tr>
  </tbody>
</table>
@endif





@include('travel_pro_reporting.footer')

</div>

</body>
</html>
