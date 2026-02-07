
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
    {{ request()->ReportType }}
  </div>

  <div class="d-flex justify-content-between my-2">
    <div><strong>From:</strong> {{ dateformatman(request()->StartDate) }} <strong>  To:</strong> {{ dateformatman(request()->EndDate) }}</div>
    
  </div>


 

 <div class="table-responsive">
    <!-- 🔍 Search box -->
    
    @if(request()->ReportType == 'Group Ticket Purchase Search' || request()->ReportType == 'Group Ticket Sale Search')
    <div class="mb-2">
        <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Search here...">
    </div>
    @endif

@php
    $totalFare = 0;
    $totalQty = 0;
    $totalPayable = 0;
    $totalReceivable = 0;
    
@endphp

@if(request()->ReportType == 'Group Ticket Purchase Search' || request()->ReportType == 'Group Ticket Purchase Register')
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Sr #</th>
            <th>Date</th>
            <th>V.No #</th>
            <th>PNR</th>
            <th>Sector</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Fare</th>
            <th>Seats</th>
            <th>Total</th>
            <th style="text-align: left;">Supplier Name</th>
        </tr>
    </thead>

    <tbody id="groupTicketTable">
      <tr>
        <td colspan="13" style="height: 30px;"></td>
      </tr>
        @foreach ($group_ticket as $item)
            @php
                $totalFare += $item->Fare;
                $totalQty += $item->Quantity;
                $totalPayable += $item->Payable;
            @endphp
            <tr>
                              <td align="center">{{ $loop->iteration }}</td>

                <td align="center">{{ dateformatman($item->Date) }}</td>
                <td align="center">{{ $item->VoucherType . '-' . $item->GroupTicketID }}</td>
                <td class="text-upper" align="center">{{ $item->PNR }}</td>
                <td align="center">{{ $item->Sector }}</td>
                <td align="center">{{ dateformatman($item->DateOfDep) }}</td>
                <td align="center">{{ dateformatman($item->DateOfArr) }}</td>
                <td align="center">{{ number_format($item->Fare, 2) }}</td>
                <td align="center">{{ $item->Quantity }}</td>
                <td align="center">{{ number_format($item->Payable, 2) }}</td>
                <td align="left">{{ $item->supplier->PartyName }}</td>
            </tr>
        @endforeach

        <!-- Row total -->
        <tr class="table-light">
            <td colspan="7" align="center"><strong>Total</strong></td>
            <td align="center"><strong>{{ number_format($totalFare, 2) }}</strong></td>
            <td align="center"><strong>{{ $totalQty }}</strong></td>
            <td align="center"><strong>{{ number_format($totalPayable, 2) }}</strong></td>
            <td></td>
        </tr>
    </tbody>
</table>
@endif


@if(request()->ReportType == 'Group Ticket Sale Search' || request()->ReportType == 'Group Ticket Sale Register'|| request()->ReportType == 'Group Departure Report')
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Sr #</th>
            <th>Date</th>
            <th>V.No #</th>
            <th style="text-align: left;">Customer Name</th>
            <th>PNR</th>
            <th>Sector</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Ticket #</th>
            <th>Pax Name</th>
            <th>Sale</th>
            <th>Discount</th>
            <th>Receivable</th>
        </tr>
    </thead>

    <tbody id="groupTicketTable">
      <tr>
        <td colspan="13" style="height: 30px;"></td>
      </tr>
        @foreach ($group_ticket as $item)
            @php
                $totalFare += $item->Fare;
                $totalQty += $item->Quantity;
                $totalReceivable += $item->Receivable;
            @endphp
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td align="center">{{ dateformatman($item->Date) }}</td>
                <td align="center">{{ $item->VoucherType . '-' . $item->GroupTicketID }}</td>
                <td align="left">{{ $item->party->PartyName ?? ''}}</td>
                <td class="text-upper" align="center">{{ $item->PNR }}</td>
                <td align="center">{{ $item->Sector }}</td>
                <td align="center">{{ dateformatman($item->DateOfDep) }}</td>
                <td align="center">{{ dateformatman($item->DateOfArr) }}</td>
                <td align="center">{{ $item->TicketNo }}</td>
                <td align="center">{{ $item->PaxName }}</td>
                <td align="center">{{ number_format($item->Fare, 2) }}</td>
                <td align="center">{{ $item->Quantity }}</td>
                <td align="center">{{ number_format($item->Receivable, 2) }}</td>
            </tr>
        @endforeach

        <!-- Row total -->
        <tr class="table-light">
            <td colspan="10" align="center"><strong>Total</strong></td>
            <td align="center"><strong>{{ number_format($totalFare, 2) }}</strong></td>
            <td align="center"><strong>{{ $totalQty }}</strong></td>
            <td align="center"><strong>{{ number_format($totalReceivable, 2) }}</strong></td>
            
        </tr>
    </tbody>
</table>
@endif


@if(request()->ReportType == 'Group Ticket Status')
@php
    $totalFare = 0;
    $totalQty = 0;
    $totalReceivable = 0;
    $grandPurchase = 0;
@endphp

<table class="table table-sm table-bordered"> 
    <thead>
        <tr>
            <th>Sr #</th>
            <th>Date</th>
            <th>V.No #</th>
            <th style="text-align:left;">Customer Name</th>
            <th>PNR</th>
            <th>Sector</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Ticket #</th>
            <th>Pax Name</th>
            <th>Sale</th>
            <th>Purchase</th>
        </tr>
    </thead>

    <tbody id="groupTicketTable">
      <tr>
        <td colspan="13" style="height: 30px;"></td>
      </tr>
      @php
          $group_ticket_sale_all = \App\Models\GroupTicket::where('VoucherType','GTS')->get();
      @endphp
        @foreach ($group_ticket_purchase as $item)
            @php
                $group_ticket_sale = \App\Models\GroupTicket::where('PNR',$item->PNR)->where('VoucherType','GTS')->get();
                $subTotalSale = 0;
                $subTotalPurchase = 0;
            @endphp

            <tr>
                <td colspan="12" style="font-size:12pt; background:#efefef; padding-left:5px; font-weight:500;">
                    GT #: {{ $item->GroupTicketID }}  
                    PNR: {{ $item->PNR }}  
                    Fare: {{ number_format($item->Fare,2) }}  
                    Seats: {{ $item->Quantity }}  
                    Date: {{ $item->Date }}  
                    Airline: {{ $item->AirlineName }}  
                    Sector: {{ $item->Sector }}
                </td>
            </tr>
 
            @foreach ($group_ticket_sale as $row)
                @php
                    $totalFare += $row->Fare;
                    $totalQty += $row->Quantity;
                    $totalReceivable += $row->Receivable;
                    $subTotalSale += $row->Fare;
                    $subTotalPurchase += $item->Fare;
                @endphp
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="center">{{ dateformatman($row->Date) }}</td>
                    <td align="center">{{ $row->VoucherType . '-' . $row->GroupTicketID }}</td>
                    <td>{{ $row->party->PartyName ?? '' }}</td>
                    <td align="center">{{ $row->PNR }}</td>
                    <td align="center">{{ $row->Sector }}</td>
                    <td align="center">{{ dateformatman($row->DateOfDep) }}</td>
                    <td align="center">{{ dateformatman($row->DateOfArr) }}</td>
                    <td align="center">{{ $row->TicketNo }}</td>
                    <td align="center">{{ $row->PaxName }}</td>
                    <td align="center">{{ number_format($row->Fare,2) }}</td>
                    <td align="center">{{ number_format($item->Fare,2) }}</td>
                </tr>
            @endforeach

            <tr style="font-weight:bold;">
                <td colspan="10" align="right">Sub Total</td>
                <td align="center">{{ number_format($subTotalSale,2) }}</td>
                <td align="center">{{ number_format($subTotalPurchase,2) }}</td>
            </tr>

            @php $grandPurchase += $subTotalPurchase; @endphp
        @endforeach

        <tr style="font-weight:bold; background:#f2f2f2;">
          <td colspan="3" style="text-align: center">Total Purchase : {{ $group_ticket_purchase->sum('Quantity') }}</td>
          <td colspan="3" style="text-align: center">Total Sale : {{ $group_ticket_sale_all->sum('Quantity') }}</td>
          <td colspan="3" style="text-align: center">Total Sale : {{ $group_ticket_purchase->sum('Quantity')-$group_ticket_sale_all->sum('Quantity') }}</td>
           <td  align="right">Grand Totals</td>
            <td align="center">{{ number_format($totalFare,2) }}</td>
            <td align="center">{{ number_format($grandPurchase,2) }}</td>
        </tr>
    </tbody>
</table>

@endif








</div>

<!-- 🧠 Search Script -->
<script>
document.getElementById('tableSearch').addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    const rows = document.querySelectorAll('#groupTicketTable tr');

    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(searchText) ? '' : 'none';
    });
});
</script>


@include('travel_pro_reporting.footer')

</div>

</body>
</html>
