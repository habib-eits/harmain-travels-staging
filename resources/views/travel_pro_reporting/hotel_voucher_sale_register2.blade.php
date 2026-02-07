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
    Umrah Sale Register (Local Currency)
  </div>

  <div class="d-flex justify-content-between my-2">
    <div><strong>From:</strong> 01-01-2025</div>
    <div><strong>To:</strong> 24-11-2026</div>
  </div>


  {{-- <table class="table table-bordered">
    <thead>
        <tr>
            <th>Guest Name</th>
            <th>Passport</th>
            <th>Nights</th>
            <th>Receivable</th>
            <th>Payable</th>
        </tr>
    </thead>
    <tbody>
        @if($invoice_master)

        
        <tr>
            <td>{{ $invoice_master->headPassenger->passenger_name }}</td>
            <td>{{ $invoice_master->headPassenger->passport_no }}</td>
            <td>
                {{ $invoice_master->hotel->sum('Nights') }}
            </td>
            <td>
                {{ number_format($invoice_master->hotel->sum('HotelReceivable'), 2) }}
            </td>
            <td>
                {{ number_format($invoice_master->hotel->sum('HotelPayable'), 2) }}
            </td>
        </tr>
        @endif
    </tbody>
</table> --}}

 

  <!-- TABLE -->
  <table class="table table-sm">
    <thead>
      <tr>
        <th>Sr #</th>
        <th>HV #</th>
        {{-- <th>Ref #</th> --}}
        <th>Guest Name</th>
        <th>Pax</th>
        <th>Passport</th>
        <th>Nights</th>
        <th>Receivable</th>
        <th>ROE Receivable</th>
        <th>Receivable A/C</th>
        <th>Payable</th>
        <th>ROE Payable</th>
        <th>SPO</th>
        <th>Package</th>
      </tr>
    </thead>

    <tbody>
    
      @php
    // Initialize grand totals
    $grandTotalPax = 0;
    $grandTotalNights = 0;
    $grandTotalHotelReceivable = 0;
    $grandTotalHotelReceivablePKR = 0;
    $grandTotalHotelPayable = 0;
    $grandTotalHotelPayablePKR = 0;
@endphp


      <!-- First Date Group -->
      @foreach($invoice_dates as $date)

      @php
         $invoice_master = \App\Models\InvoiceMaster::with(['headPassenger', 'hotel'])
    ->whereHas('headPassenger')
    ->whereBetween('Date', [request()->StartDate, request()->EndDate])
    ->when(request()->PartyID, function ($query, $PartyID) {
        return $query->where('PartyID', $PartyID);
    })
    ->when(request()->package_id, function ($query, $package_id) {
        return $query->where('package_id', $package_id);
    })
    ->get();

    // Initialize date-wise totals
        $dateTotalPax = 0;
        $dateTotalNights = 0;
        $dateTotalHotelReceivable = 0;
        $dateTotalHotelReceivablePKR = 0;
        $dateTotalHotelPayable = 0;
        $dateTotalHotelPayablePKR = 0;


      @endphp

      <tr><td colspan="14" class="date-heading">{{ dateformatman2($date) }}</td></tr>
@foreach($invoice_master as $key => $invoice)

@php
    $party = DB::table('party')->where('PartyID', optional($invoice->hotel->first())->SupplierID )->first();

    $hotelReceivable = $invoice->hotel->sum('HotelReceivable');
            $hotelReceivablePKR = $invoice->hotel->sum(fn($h) => $h->HotelReceivable * $h->ExRateSaleHotel);
            $hotelPayable = $invoice->hotel->sum('HotelPayable');
            $hotelPayablePKR = $invoice->hotel->sum(fn($h) => $h->HotelPayable * $h->ExRatePurchaseHotel);
            $hotelPax = $invoice->hotel->sum('HotelPax');
            $hotelNights = $invoice->hotel->sum('Nights');

            // Add to date totals
            $dateTotalPax += $hotelPax;
            $dateTotalNights += $hotelNights;
            $dateTotalHotelReceivable += $hotelReceivable;
            $dateTotalHotelReceivablePKR += $hotelReceivablePKR;
            $dateTotalHotelPayable += $hotelPayable;
            $dateTotalHotelPayablePKR += $hotelPayablePKR;

            
@endphp
      <tr>
        <td>{{ ++$key }}</td>
        <td align="center">{{ $invoice->InvoiceMasterID }}</td>
        {{-- <td>AJ-1</td> --}}
        <td class="text-upper">{{ $invoice->headPassenger->passenger_name }}</td>
        <td align="center">{{ $invoice->hotel->sum('HotelPax') }}</td>
        <td class="text-upper">{{ $invoice->headPassenger->passport_no }}</td>
         <td align="center">{{ $invoice->hotel->sum('Nights') }}</td>
       <td align="center">{{ number_format($invoice->hotel->sum('HotelReceivable'),2) }}</td>
       <td align="center">{{ number_format($invoice->hotel->sum(fn($hotel) => $hotel->HotelReceivable * $hotel->ExRateSaleHotel), 2) }}</td>
       <td>{{ $party->PartyName ?? ''}}</td>
        <td align="center">{{ number_format($invoice->hotel->sum('HotelPayable'),2) }}</td>
       <td align="center">    {{ number_format($invoice->hotel->sum(fn($hotel) => $hotel->HotelPayable * $hotel->ExRatePurchaseHotel), 2) }}
</td>
        <td>{{ $invoice->CareOf}}</td>
        <td>{{ $invoice->package->name }}</td>
      </tr>
@endforeach
{{-- row total --}}
      <tr class="total-row">
          <td colspan="3">Total ({{ dateformatman2($date) }})</td>
        <td align="center">{{ $dateTotalPax }}</td>
        <td></td>
        <td align="center">{{ $dateTotalNights }}</td>
        <td align="center">{{ number_format($dateTotalHotelReceivable, 2) }}</td>
        <td align="center">{{ number_format($dateTotalHotelReceivablePKR, 2) }}</td>
        <td></td>
        <td align="center">{{ number_format($dateTotalHotelPayable, 2) }}</td>
        <td align="center">{{ number_format($dateTotalHotelPayablePKR, 2) }}</td>
        <td colspan="3"></td>
      </tr>

        @php
        // Add date totals to grand totals
        $grandTotalPax += $dateTotalPax;
        $grandTotalNights += $dateTotalNights;
        $grandTotalHotelReceivable += $dateTotalHotelReceivable;
        $grandTotalHotelReceivablePKR += $dateTotalHotelReceivablePKR;
        $grandTotalHotelPayable += $dateTotalHotelPayable;
        $grandTotalHotelPayablePKR += $dateTotalHotelPayablePKR;
    @endphp

@endforeach
 

      <!-- Grand Total -->
 <tr class="grand-total">
    <td colspan="3">Grand Total</td>
    <td align="center">{{ $grandTotalPax }}</td>
    <td></td>
    <td align="center">{{ $grandTotalNights }}</td>
    <td align="center">{{ number_format($grandTotalHotelReceivable, 2) }}</td>
    <td align="center">{{ number_format($grandTotalHotelReceivablePKR, 2) }}</td>
    <td></td>
    <td align="center">{{ number_format($grandTotalHotelPayable, 2) }}</td>
    <td align="center">{{ number_format($grandTotalHotelPayablePKR, 2) }}</td>
    <td colspan="3"></td>
</tr>
    </tbody>



  </table>

  <div class="footer">
    Developed By - <strong>XTBook Travel Pro</strong><br>
    For more info visit <a href="https://www.xtbooks.app" target="_blank">www.xtbooks.app</a>
  </div>

</div>

</body>
</html>
