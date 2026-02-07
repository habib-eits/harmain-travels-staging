@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')



<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                       
 @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

 @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger p-1   border-3">
                   <p class="font-weight-bold"> There were some problems with your input.</p>
                    <ul>
                        
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>

                        @endforeach
                    </ul>
                </div>
                </div>
 
            @endif



            <div class="card">
              <div class="card-body">
                
                <form method="post" action="{{ URL('VoucherSalemanSummaryReport') }}">
                  @csrf
                  <div class="row">
                    <div class="col-md-3">
                      <div class="mb-2">
                        <label for="example-text-input" class="form-label">From Date</label>
                        <input class="form-control" type="date" value="{{ request('StartDate', date('Y-m-01')) }}" id="StartDate" name="StartDate" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="mb-2">
                        <label for="example-text-input" class="form-label">To Date</label>
                        <input class="form-control" type="date" value="{{ request('EndDate', date('Y-m-d')) }}" id="EndDate" name="EndDate" required>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="mb-2">
                        <label for="example-text-input" class="form-label">Salesman</label>
                        <select class="form-select select2" name="UserID" id="UserID">
                          <option value="">--Select--</option>
                          @foreach ($users as $value)
                          <option value="{{ $value->UserID }}" {{ request()->UserID == $value->UserID ? 'selected' : '' }}>{{ $value->FullName }}</option>
                          @endforeach
                           
                        </select>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="mb-2">
                        <label for="example-text-input" class="form-label">&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                      </div>
                    </div>

                  </div>
                </form>

              </div>
            </div>
            
            <?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>
  <div class="card">
      <div class="card-body">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1"> </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>SALEMAN SALES SUMMARY REPORT </strong></div></td>
    </tr>
    <tr>
      <td width="50%">DATED: {{date('d-m-Y')}}</td>
      <td width="50%">&nbsp;</td>
    </tr>
  </table>

  <style>
    .group-border {
        border-right: 1px solid #000 !important; /* thick black border */
    }
</style>


 @php
            $grandVisaProfit = $grandTicketProfit = $grandHotelProfit = $grandTransportProfit = $grandTotalProfit = 0;
        @endphp
<table class="table table-bordered table-sm text-center align-middle">
    <thead class="table-light">
        <tr>
            <th>User</th>
            <th>Visa Sale</th>
            <th>Visa Purchase</th>
            <th>Visa Profit</th>
            <th>Ticket Sale</th>
            <th>Ticket Purchase</th>
            <th>Ticket Profit</th>
            <th>Hotel Payable</th>
            <th>Hotel Receivable</th>
            <th>Hotel Profit</th>
            <th>Transport Sale</th>
            <th>Transport Purchase</th>
            <th>Transport Profit</th>
            <th>Profit</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($sale as $item)


           @php
                $totalProfit = 
                    ($item->total_visa_profit ?? 0) +
                    ($item->total_ticket_profit ?? 0) +
                    ($item->total_hotel_profit ?? 0) +
                    ($item->total_transport_profit ?? 0);

                // accumulate totals
                $grandVisaProfit += $item->total_visa_profit;
                $grandTicketProfit += $item->total_ticket_profit;
                $grandHotelProfit += $item->total_hotel_profit;
                $grandTransportProfit += $item->total_transport_profit;
                $grandTotalProfit += $totalProfit;
            @endphp



            <tr>
                <td>{{ $item->FullName }}</td>
                <td>{{ number_format($item->total_visa_sale, 2) }}</td>
                <td>{{ number_format($item->total_visa_purchase, 2) }}</td>
                <td>{{ number_format($item->total_visa_profit, 2) }}</td>
                <td>{{ number_format($item->total_ticket_sale, 2) }}</td>
                <td>{{ number_format($item->total_ticket_purchase, 2) }}</td>
                <td>{{ number_format($item->total_ticket_profit, 2) }}</td>
                <td>{{ number_format($item->total_hotel_payable, 2) }}</td>
                <td>{{ number_format($item->total_hotel_receivable, 2) }}</td>
                <td>{{ number_format($item->total_hotel_profit, 2) }}</td>
                <td>{{ number_format($item->total_transport_sale, 2) }}</td>
                <td>{{ number_format($item->total_transport_purchase, 2) }}</td>
                <td>{{ number_format($item->total_transport_profit, 2) }}</td>
                <td class="fw-bold text-success">{{ number_format($totalProfit, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="13" class="text-center text-muted">No data available for this period.</td>
            </tr>
        @endforelse
    </tbody>

    @if($sale->count())
        <tfoot class="table-secondary fw-bold">
            <tr>
                <td>Total</td>
                <td>{{ number_format($sale->sum('total_visa_sale'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_visa_purchase'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_visa_profit'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_ticket_sale'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_ticket_purchase'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_ticket_profit'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_hotel_payable'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_hotel_receivable'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_hotel_profit'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_transport_sale'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_transport_purchase'), 2) }}</td>
                <td>{{ number_format($sale->sum('total_transport_profit'), 2) }}</td>
                                <td class="text-success">{{ number_format($grandTotalProfit, 2) }}</td>

            </tr>
        </tfoot>
    @endif
</table>

       
      </div>
  </div>
  
  
 
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
  @endsection