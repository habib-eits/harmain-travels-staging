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
    Visa Sale Invoice
  </div>

 
<Br>

 <table width="100%" border="1"> <!-- Strat Table -->
                  <tbody><tr align="center" class="detail-heading">
                    <td>Client Name</td>
                    <td>Address</td>
                    <td>Contact #</td>
                  </tr>
                  <tr align="center" class="heading-height">
                       <td class="report-heading">{{ $invoice_master->party->PartyName ?? '' }}</td>
                       <td class="report-text">{{ $invoice_master->party->Address ?? '' }}</td>
                       <td class="report-num">{{ $invoice_master->party->Contact ?? '' }}</td>
                      </tr>
    </tbody></table>

 <br>
 <table width="100%" border="1" class="heading-height"> <!-- Strat Table -->
                  <tbody><tr align="right" class="detail-heading">
                    <td align="center">Date</td>
                    <td align="left">Pax Name</td>
                    <td align="left">Description</td>
                    <td>Amount</td>
                  </tr>
@php
    $total = 0;
@endphp
                  @foreach($invoice_detail as $detail)
 @php
        $total += $detail->Receivable;
    @endphp
                  <tr class="report-text" align="right">
                    <td align="center">{{ $detail->Date }}</td>
                    <td align="left">{{ $detail->PaxName }}</td>
                    <td align="left">Visa for Passport #: {{ $detail->Passport }}, Type: {{ $detail->PaxType }} {{  $detail->PackageName}} </td>
                    <td>{{ $detail->Receivable }}</td>
                  </tr>
     
                  @endforeach
                  

                  

                  <tr class="report-num-total" align="right">
                    <td class="report-heading-imp" colspan="3" align="right">Total</td>
                    <td>{{ number_format($total, 2) }}</td>
                  </tr>

        </tbody></table>

@include('travel_pro_reporting.footer')

</div>

</body>
</html>
