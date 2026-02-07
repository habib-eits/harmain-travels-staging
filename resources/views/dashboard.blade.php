@extends('template.tmp')

@section('title', $pagetitle)
  

@section('content')
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<style id="compiled-css" type="text/css">
      .highcharts-figure,
.highcharts-data-table table {
    min-width: 360px;
    max-width: 800px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

 
 .page-content {
     background: #E9E8F9 !important;
}

    /* EOS */



.bg-primary {
    --bs-bg-opacity: 1;
    background-color: rgb(25 57 209) !important;
} 


.bg-primary2 {
    --bs-bg-opacity: 1;
    background-color: #008476 !important;
} 


.bg-primary3 {
    --bs-bg-opacity: 1;
    background-color: #805475 !important;
} 


.bg-primary4 {
    --bs-bg-opacity: 1;
    background-color: #2a3042 !important;
} 


.card-body {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.0rem 1.0rem !important;
}




.order-card {
    color: #fff;
}

.bg-c-blue {
    background: linear-gradient(45deg,#4099ff,#73b4ff);
}

.bg-c-green {
    background: linear-gradient(45deg,#2ed8b6,#59e0c5);
}

.bg-c-yellow {
    background: linear-gradient(45deg,#FFB64D,#ffcb80);
}

.bg-c-pink {
    background: linear-gradient(45deg,#FF5370,#ff869a);
}


.card {
    border-radius: 5px;
    -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    border: none;
    margin-bottom: 30px;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.card .card-block {
    padding: 25px;
}

.order-card i {
    font-size: 26px;
}

.f-left {
    float: left;
}

.f-right {
    float: right;
}

.media-body {
     
    margin-left: 25 !important;
}


  </style>

 


 
 <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                                    <div class="page-title-right ">
                                        <strong class="text-danger">{{session::get('Email')}}->{{session::get('BranchName')}}-{{session::get('UserType')}}-{{session::get('BranchID')}}</strong>
                                         
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->



 @if (session('error'))

<div class="alert alert-{{ Session::get('class') }} p-3" id="success-alert">
                    
                  {{ Session::get('error') }} 
                </div>

@endif

 

 

<!--  -->

<?php 

 
$assets = DB::table('v_journal')
    ->select(
        'ChartOfAccountID',
        'ChartOfAccountName',
        DB::raw('SUM(IFNULL(Dr, 0)) as Dr'),
        DB::raw('SUM(IFNULL(Cr, 0)) as Cr'),
        DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance')
    )
    ->whereIn('Category', ['BANK', 'CASH'])
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && request()->branch_id != null, function ($query) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', request()->branch_id);
        })
    ->groupBy('ChartOfAccountName', 'ChartOfAccountID')
    ->get();
 
 
 ?>


<!-- /////////////////////// -->

 
    <div class="row">

@if(Session::get('UserType')=='Admin' || Session::get('UserType')=='SuperAdmin')             
            

@foreach($assets as $value)
    <div class="col-md-2 col-sm-2">
        <div class="card shadow-sm text-center" style="border-top: 2px solid {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }};">
            <div class="card-body">
                <h5 class="card-title">{{$value->ChartOfAccountName}}</h5>
                <p class="card-text text-muted">{{env('APP_CURRENCY')}} {{number_format($value->balance)}} </p>
            </div>
        </div>
    </div>
@endforeach

    
@endif
</div>
 


<!-- /////////////////////// -->

 
    {{-- <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20 text-white">TODAY'S CASH SALE</h6>
                    <h2 class="text-end text-white mt-3"><i class="bx bx-dollar-circle f-left"></i><span>{{number_format($today_sale->CASH)}}</span></h2>
                 </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h6 class="m-b-20 text-white">ADCB BANK</h6>
                    <h2 class="text-end text-white mt-3"><i class="mdi mdi-bank f-left"></i><span>{{number_format($today_sale->ADCB)}}</span></h2>
                 </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h6 class="m-b-20 text-white">ENBD BANK</h6>
                    <h2 class="text-end text-white mt-3"><i class="mdi mdi-bank f-left"></i><span>{{number_format($today_sale->ENBD)}}</span></h2>
                 </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h6 class="m-b-20 text-white">TODAY'S TOTAL SALE</h6>
                    <h2 class="text-end text-white mt-3"><i class="fa fa-credit-card f-left"></i><span>{{number_format($today_sale->TOTAL_SALE)}}</span></h2>
                 </div>
            </div>
        </div>
     </div>
  --}}
<!-- end new card -->
<style>
    .card-box {
      border: 2px solid;
      border-radius: 10px;
      padding: 20px;
      color: #333;
      margin: 10px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
      min-width: 230px;
      flex: 1;
      background-color: white;
    }
    .icon {
      font-size: 28px;
      display: block;
    }
    .title {
      font-size: 16px;
      font-weight: 600;
      margin-top: 5px;
    }
    .number {
      font-weight: bold;
      float: right;
    }
    .subtext {
      font-size: 14px;
      color: #444;
      margin-top: 10px;
    }
    .border-green { border-color: #4CAF50; }
    .border-purple { border-color: #9C27B0; }
    .border-blue { border-color: #03A9F4; }
    .border-orange { border-color: #FF5722; }
    .text-green { color: #4CAF50; }
    .text-purple { color: #9C27B0; }
    .text-blue { color: #03A9F4; }
    .text-orange { color: #FF5722; }
  </style>

 

 
 


  <style>
    .summary-box {
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      margin-bottom: 20px;
    }
    
    .summary-box1 {
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      margin-bottom: 20px;
    }
    .summary-title {
      font-weight: 600;
      color: #e36b0a;
      margin-bottom: 20px;
    }
    .summary-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }
    .summary-item i {
      font-size: 24px;
      color: #e36b0a;
      margin-right: 10px;
    }
    .balance-table th, .balance-table td {
      vertical-align: middle;
    }

   hr
    {
        margin: 0.25rem 0 !important;
    }
  </style>
</head>
<body class="bg-light">
<div class="row">

       
{{--  
    <!-- Tickets -->
    <div class="col-md-3 col-sm-6">
      <div class="card">
        <div class="card-body border-green text-green">
        <span class="number">{{ number_format($flight->count()) }}▲</span>
        <span class="icon">🎫</span>
        <div class="title">Tickets</div>
        <hr>
        <div class="subtext">Amount: {{ number_format($umrah->sum('ticket_sale')) }}</div>
      </div>
      </div>
    </div>

    <!-- Visa Approval -->
    
    <div class="col-md-3 col-sm-6">
      <div class="card">
        <div class="card-body border-purple text-purple">
        <span class="number">{{ number_format($umrah->sum('visa_sale')) }}▲</span>
        <span class="icon">👍</span>
        <div class="title">Visa</div>
        <hr>
        <div class="subtext">Total Visa: {{ number_format($hotel->count()) }}</div>
      </div>
      </div>
    </div>

    <!-- Hotel Voucher -->
    <div class="col-md-3 col-sm-6">
     <div class="card">
         <div class="card-body border-blue text-blue">
        <span class="number">{{ number_format($hotel->sum('HotelReceivable')) }}▲</span>
        <span class="icon">📄</span>
        <div class="title">Hotel Voucher</div>
        <hr>
        <div class="subtext">Hotel Pax: {{ number_format($hotel->count()) }}</div>
      </div>
     </div>
    </div>

    <!-- Tour Voucher -->
    <div class="col-md-3 col-sm-6">
      <div class="card">
        <div class="card-body border-orange text-orange">
        <span class="number">{{ number_format($transport->sum('TransportReceivable')) }}▲</span>
        <span class="icon">✈️</span>
        <div class="title">Transport Voucher</div>
        <hr>
        <div class="subtext">Tour Pax: {{ number_format($transport->sum('Quantity')) }}</div>
      </div>
      </div>
    </div>

  </div>
  --}}

                             

@if(Session::get('UserType')=='SuperAdmin' || Session::get('UserType')=='Admin')
                           
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="card bg-primary bg-gradient ">
                                            <div class="card-body border-primary  rounded-top">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-light bg-soft text-primary font-size-18">
                                                            <i class="mdi mdi-passport text-white"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="font-size-14 mb-0 text-white">Party Balance</h5>
                                                </div>
                                                <div class="text-muted mt-4">
                                                    <h4 class="text-center text-white"><a href="{{URL('/PartyBalance')}}" target="_blank" class="text-white">{{number_format($party_balance[0]->Balance,2)}}  {{env('APP_CURRENCY')}}</a> </h4>
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                  
                                    <div class="col-sm-3">
                                        <div class="card bg-danger bg-gradient">
                                            <div class="card-body border-danger  rounded-top">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-light bg-soft text-primary font-size-18">
                                                           <i class="mdi mdi-passport text-white"></i>
                                                        </span>
                                                    </div>  
                                                    <h5 class="font-size-14 mb-0 text-white">Today's Income </h5>   <span class=" w-50  text-end text-white">         {{date('d-M-Y') }}</span>
                                                </div>
                                                <div class="text-muted mt-4">
                                                    <h4 class="text-center"><a href="{{URL('/SalemanTicketRegister')}}" class="text-white" >{{($today_revenue[0]->Balance ==null) ? '0' :  number_format($today_revenue[0]->Balance,2)}}   {{env('APP_CURRENCY')}}</a> </h4>
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-sm-3">
                                        <div class="card bg-danger bg-gradient">
                                            <div class="card-body border-danger border-top border-3 rounded-top">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-light bg-soft text-primary font-size-18">
                                                           <i class="mdi mdi-passport text-white"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="font-size-14 mb-0 text-white">Today's Income </h5>   <span class=" w-50  text-end text-white">         {{date('d-M-Y') }}</span>
                                                </div>
                                                <div class="text-muted mt-4">
                                                  {{--  <h4 class="text-center"><a href="{{URL('/SalemanTicketRegister')}}" class="text-white" >{{($expense[0]->Balance ==null) ? 0 :  number_format($expense[0]->Balance,2)    }}     {{env('APP_CURRENCY')}}</a> </h4> --}}
                                                  
                                                  
                                                    {{-- <h4 class="text-center"><a href="{{URL('/SalemanTicketRegister')}}" class="text-white" >{{($expense[0]->Balance ==null) ? 0 :  number_format($expense[0]->Balance-$bankCharges,2)    }}     {{env('APP_CURRENCY')}}</a> </h4>
                                                   
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>  --}}

                          
        
                                    <div class="col-sm-3">
                                        <div class="card bg-primary2 bg-gradient">
                                            <div class="card-body border-primary2  rounded-top">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-light bg-soft text-white font-size-18">
                                                            <i class="mdi mdi-calendar-cursor font-size-30 text-white "></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="font-size-14 mb-0 text-white">Monthly Income </h5> <span class="text-white w-50  text-end">{{date('M-Y') }}</span>
                                                </div>
                                                <div class="text-muted mt-4">
                                                    <h4 class="text-center"><a href="#" class="text-white">{{($monthly_revenue[0]->Balance ==null) ? '0' :  number_format($monthly_revenue[0]->Balance,2)}} 

 
                                                     {{env('APP_CURRENCY')}}</a> </h4>
                                                    
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                      <div class="col-sm-3">
                                        <div class="card bg-warning bg-gradient">
                                            <div class="card-body border-warning  rounded-top">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-light bg-soft text-primary font-size-18">
                                                            <i class="mdi mdi-fingerprint text-white"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="font-size-14 mb-0 text-white">Current Year P&L </h5> <span class="text-white w-50  text-end">{{date('Y') }}</span>
                                                </div>
                                                <div class="text-muted mt-4">
                                                    <h4 class="text-center text-white"><a href="#" class="text-white">{{number_format($profit_loss,2)}} {{env('APP_CURRENCY')}}</a> </h4>
                                                    
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endif



@php
    
    $recivable = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance') )
      ->where('ChartOfAccountID', 110400)    
      ->first();
      
      
      $payable = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance') )
      ->where('ChartOfAccountID', 210100)    
      ->first();

 $cash_detail = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance') )
      ->where('Category', 'Cash')    
      ->first();
      
 $bank_detail = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance') )
      ->where('Category', 'Bank')    
      ->first();





 @endphp                                    


  <div class="row">
    <!-- System Summary -->
    <div class="col-lg-8">
      <div class="summary-box">
        <h5 class="summary-title"><i class="fas fa-th-large me-2"></i>System Summary</h5>
        <div class="row">
          <div class="col-md-6 summary-item"><i class="fas fa-user font-size-24"></i>
            <div>
              <strong>Accounts</strong><br/>
              Receivable: {{ number_format($recivable->Balance,2) }}<br/>Payable: {{ number_format($payable->Balance,2) }}
            </div>
          </div>
          <div class="col-md-6 summary-item"><i class="fas fa-dollar-sign font-size-24"></i>
            <div>
              <strong>Cash in Hand</strong><br/>
              Cash: {{ number_format($cash_detail->Balance,2) }}<br/>Bank: {{ number_format($bank_detail->Balance,2) }}
            </div>
          </div>
          <div class="col-md-6 summary-item"><i class="fas fa-thumbs-up font-size-24"></i>
            <div>
              <strong>Visa Approval</strong><br/>
              Total Umrah: {{ number_format($umrah->sum('visa_sale')) }}<br/>
            </div>
          </div>
          <div class="col-md-6 summary-item"><i class="fas fa-building font-size-24"></i>
            <div>
              <strong>Umrah Booking</strong><br/>
              Booking: {{ number_format($hotel->count('HotelPayable')) }}<br/>Amount: {{ number_format($hotel->sum('HotelPayable')) }}
            </div>
          </div>
          <div class="col-md-6 summary-item"><i class="fas fa-plane font-size-24"></i>
            <div>
              <strong>Tour Booking</strong><br/>
              Booking: {{ number_format($transport->count('TransportPayable')) }}<br/>Amount: {{ number_format($transport->sum('TransportPayable')) }}
            </div>
          </div>
          <div class="col-md-6 summary-item"><i class="fas fa-laptop font-size-24"></i>
            <div>
              <strong>Ticket Booking</strong><br/>
              Booking: {{ number_format($umrah->count('ticket_sale')) }}<br/>Amount: {{ number_format($umrah->sum('ticket_sale')) }}
            </div>
          </div>
          <div class="col-md-6 summary-item"><i class="fas fa-ticket-alt font-size-24"></i>
            <div>
              <strong>Transport</strong><br/>
              Invoice: {{ number_format($transport->count('TransportPayable')) }}<br/>Amount: {{ number_format($transport->sum('TransportPayable')) }}
            </div>
          </div>
          <div class="col-md-6 summary-item"><i class="fas fa-cogs font-size-24"></i>
            <div>
              <strong>Other Services</strong><br/>
              Invoice: 0<br/>Amount: 0
            </div>
          </div>
          <div class="col-md-6 summary-item " style="visibility: hidden;"><i class="fas fa-heart font-size-24"></i>
            <div>
              <strong>Insurance</strong><br/>
              Invoices: 0<br/>Amount: 0
            </div>
          </div>
        </div>
      </div>
    </div>

@php
    
       $party_balance = DB::table('journal')
      ->select('party.PartyID', 'party.PartyName', DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
      ->join('party', 'journal.PartyID', '=', 'party.PartyID')
    //   ->whereBetween('journal.date', [$request->StartDate, $request->EndDate])
            ->whereIn('ChartOfAccountID', [110400,210100])
        //     ->when($request->BranchID > 0, function ($query) use ($request) {
        //       return $query->where('journal.BranchID', $request->BranchID);
        //   })
    //   ->where($where)
      ->orderby('party.PartyName')
      ->groupBy('party.PartyID', 'party.PartyName')
       // ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
       ->get();


       
@endphp

<style>
    .summary-box1 {
  max-height: 450px; /* adjust height as you need */
  overflow-y: auto;
  border: 1px solid #ddd; /* optional, just for clarity */
  padding: 10px;
}
</style>

    <!-- Balance Summary -->
    <div class="col-lg-4">
      <div class="summary-box1">
        <h5 class="summary-title"><i class="fas fa-wallet me-2"></i>Balance Summary</h5>
        <div class="table-responsive">
          <table class="table table-sm table-hover balance-table">
            <thead>
              <tr>
                <th>Account Name</th>
                <th class="text-end">Balance</th>
              </tr>
            </thead>
            <tbody>
           @foreach ($party_balance as $row)
               
           <tr><td>{{ $row->PartyName }}</td><td class="text-end text-success">{{ number_format($row->balance) }}</td></tr>
           @endforeach
           
   
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
 



 @php
     
     $flight = \App\Models\InvoiceMaster::where('FlightNights', '>', 0)->get();
      $umrah = \App\Models\UmrahInvoicePassenger::all();
      $hotel = \App\Models\InvoiceHotel::all();
      $transport = \App\Models\InvoiceTransport::all();

 @endphp 
 
  <div class="row ">

    <!-- Visa Approval & Voucher Summary -->
    <div class="col-lg-8">
      <div class="summary-box1">
        <h5 class="summary-title"><i class="fas fa-folder-open me-2"></i>Visa Approval & Voucher Summary</h5>

     

        <div class="table-responsive">
      <table class="table table-responsive table-sm" id="table">
<thead>

{{-- <tr class="report-text-datatable">
<td>&nbsp;</td>
<td style="color:#6666CC; font-size:18px; text-align:center;"><span style="font-size: 28px;" class="fa fa-thumbs-up"></span><br>{{ number_format($umrah->sum('visa_sale')) }}+ {{ number_format($hotel->sum('HotelReceivable')) }} </td>
<td style="color:#729A3A;  font-size:18px; text-align:center;"><span style="font-size:26px;" class="fa fa-file-pdf-o"></span><br>{{ number_format($flight->count('Date')) }}</td>
<td width="17%" style="color:#00CCFF;  font-size:18px;  text-align:center;"><span style="font-size:26px" class="fa fa-users"></span><br>{{ number_format($umrah->count()) }}</td>
<td width="17%" style="color:#D9534F;  font-size:18px; text-align:center;"><span style="font-size:28px" class="fa fa-street-view"></span><br>{{ number_format($transport->count()) }}</td>
</tr> --}}

<tr>
<th style="border-bottom:none;">Client Name</th>
<th nowrap="" style="border-bottom:none;text-align:center;">Visa+Only Hotel</th>
<th style="border-bottom:none;text-align:center;">Voucher</th>
<th style="border-bottom:none;text-align:center;">Voucher Pax</th>
<th style="border-bottom:none;text-align:center;">Transport Pax</th>
</tr>
</thead>

<tbody>
    
@php
    $party = \App\Models\Party::getPartyList();
    
@endphp

@foreach ($party as $value )


@php
    
  $visa = \App\Models\VisaDetail::select('InvoiceMasterID')->with('master:PartyID')
    ->whereHas('master', function ($query) use ($value) {
        $query->where('PartyID', $value->PartyID);
    })
    ->count();
    
    
    $hotel = \App\Models\InvoiceHotel::with('invoiceMaster')
    ->whereHas('invoiceMaster', function ($query) use ($value) {
        $query->where('PartyID', $value->PartyID);
    })
    ->sum('HotelPax');
    
    $transport = \App\Models\InvoiceTransport::with('invoiceMaster')
    ->whereHas('invoiceMaster', function ($query) use ($value) {
        $query->where('PartyID', $value->PartyID);
    })
    ->sum('Quantity');
    
    $pax = \App\Models\UmrahInvoicePassenger::with('invoiceMaster')
    ->whereHas('invoiceMaster', function ($query) use ($value) {
        $query->where('PartyID', $value->PartyID);
    })
    ->count('umrah_invoice_master_id');
    
    
    $voucher = \App\Models\InvoiceMaster::where('PartyID', $value->PartyID)->count('InvoiceMasterID');



@endphp
    
<tr>
    <td class="report-text-datatable">{{ $value->PartyName }}</td>
    
    <td style="font-size:15px; color:#6666CC; text-align:center;"><a style="color:#6666CC;" href="#" target="_blank">{{ number_format($visa) }}+{{ number_format($hotel) }}</a></td>
    <td style="color:#729A3A;font-size:15px;text-align:center;"><a style="color:#729A3A;" href="#">{{ number_format($voucher) }}</a></td>
    <td style="color:#00CCFF;font-size:15px;text-align:center;"><a style="color:#00CCFF;" href="#" target="_blank">{{ number_format($pax) }}</a></td>
    <td style="color:#D9534F;font-size:15px;text-align:center;"><a style="color:#D9534F;" href="#" target="_blank">{{ number_format($transport) }}</a></td>
</tr>
@endforeach    







</tbody></table>
        </div>
      </div>
    </div>


@php
    $arrival = \App\Models\InvoiceMaster::where('FlightDateArrivalDeparture', date('Y-m-d'))->count();
    $depart = \App\Models\InvoiceMaster::where('FlightDateReturn', date('Y-m-d'))->count();

    $makkah_check_in = \App\Models\InvoiceHotel::where('HotelCity', 'Makkah')
    ->where('CheckInDate',date('Y-m-d'))
    ->selectRaw('SUM(CAST(HotelPax AS UNSIGNED)) as total')
    ->value('total');

    $makkah_check_out = \App\Models\InvoiceHotel::where('HotelCity', 'Makkah')
    ->where('CheckOutDate',date('Y-m-d'))
    ->selectRaw('SUM(CAST(HotelPax AS UNSIGNED)) as total')
    ->value('total');
    
    $madina_check_in = \App\Models\InvoiceHotel::where('HotelCity', 'Madina')
    ->where('CheckInDate',date('Y-m-d'))
    ->selectRaw('SUM(CAST(HotelPax AS UNSIGNED)) as total')
    ->value('total');

    $madina_check_out = \App\Models\InvoiceHotel::where('HotelCity', 'Madina')
    ->where('CheckOutDate',date('Y-m-d'))
    ->selectRaw('SUM(CAST(HotelPax AS UNSIGNED)) as total')
    ->value('total');

    $today = date('Y-m-d');
    $in_makkah = \App\Models\InvoiceHotel::where('HotelCity', 'Makkah')
    ->whereDate('CheckInDate', '<=', $today)   // already checked-in
    ->whereDate('CheckOutDate', '>=', $today)  // not yet checked-out
    ->selectRaw('SUM(CAST(HotelPax AS UNSIGNED)) as total')
    ->value('total');
    
    $in_madina = \App\Models\InvoiceHotel::where('HotelCity', 'Madina')
    ->whereDate('CheckInDate', '<=', $today)   // already checked-in
    ->whereDate('CheckOutDate', '>=', $today)  // not yet checked-out
    ->selectRaw('SUM(CAST(HotelPax AS UNSIGNED)) as total')
    ->value('total');
     


    
     
@endphp

    <!-- KSA Status -->
    <div class="col-lg-4">
      <div class="summary-box text-center">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Flag_of_Saudi_Arabia.svg/200px-Flag_of_Saudi_Arabia.svg.png"
             alt="KSA Logo" class="img-fluid mb-3" style="max-height: 60px;">
        <h6 class="mb-3"><strong>KSA Status</strong></h6>
        <input type="text" class="form-control form-control-sm mb-4 text-center" value="{{ date('d/m/Y') }}" readonly>

        <div class="row text-center mb-4">
          <div class="col">
            <i class="fas fa-plane-arrival ksa-icon text-dark font-size-20"></i>
            <div>Arrival ({{ $arrival }})</div>
          </div>
          <div class="col">
            <i class="fas fa-plane-departure ksa-icon text-dark font-size-20"></i>
            <div>Departure ({{ $depart }})</div>
          </div>
        </div>

        <div class="row text-center mb-4">
          <div class="col">
            <i class="fas fa-kaaba ksa-icon font-size-20 text-black"></i>
            <div>Mak In ({{ number_format($makkah_check_in) }})</div>
          </div>
          <div class="col">
            <i class="fas fa-kaaba ksa-icon font-size-20 text-black"></i>
            <div>Mak Out ({{ number_format($makkah_check_out) }})</div>
          </div>
        </div>

        <div class="row text-center mb-4">
          <div class="col">
            <i class="fas fa-mosque ksa-icon font-size-20 text-success"></i>
            <div>Med In ({{ number_format($madina_check_in) }})</div>
          </div>
          <div class="col">
            <i class="fas fa-mosque ksa-icon font-size-20 text-success"></i>
            <div>Med Out ({{ number_format($madina_check_in) }})</div>
          </div>
        </div>

        <div class="row text-center mb-4">
          <div class="col">
            <i class="fas fa-kaaba ksa-icon font-size-20 text-dark"></i>
            <div>In Makkah ({{ number_format($in_makkah) }})</div>
          </div>
          <div class="col">
            <i class="fas fa-mosque ksa-icon font-size-20 text-success"></i>
            <div>In Madina ({{ number_format($in_madina) }})</div>
          </div>
        </div>
      </div>
    </div>

  </div>
 

<div class="row">

 
                            

                                    

@if(Session::get('UserType')=='SuperAdmin' || Session::get('UserType')=='Admin')
                           
                            
                                    
 
 

 


                                </div>
                                <!-- end row -->
                            </div>
                        </div>
 
                       <div class="row">
                           
                            <div class="col-xl-12">
                                <div class="row">
                               


                                     <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body border-secondary border-top border-3 rounded-top">
                                                
                                                <div class="text-muted mt-4">
                                                      <div id="sale_register"></div>
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


  <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body border-secondary border-top border-3 rounded-top">
                                                
                                                <div class="text-muted mt-4">
                                                      <div id="container2"></div>
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                         
                                  
                                       <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body border-secondary border-top border-3 rounded-top">
                                                
                                                <div class="text-muted mt-4">
                                                      <div id="container4"></div>
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


            
  <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body border-secondary border-top border-3 rounded-top">
                                                
                                                <div class="text-muted mt-4">
                                                      <div id="sale_report"></div>
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



  <div class="col-sm-6">
    <div class="card">
        <div class="card-body border-secondary border-top border-3 rounded-top">
            
            <div class="text-muted mt-4">
                  <div id="container3"></div>
                <div class="d-flex">
                     <span class="ms-2 text-truncate mt-3"> </span>
                </div>
            </div>
        </div>
    </div>
</div>
                  <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body border-secondary border-top border-3 rounded-top">
                                                
                                                <div class="text-muted mt-4">
                                                      <div id="container"></div>
                                                    <div class="d-flex">
                                                         <span class="ms-2 text-truncate mt-3"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  
                                   
                    </div>



 
@endif
 
                    
                                    
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

   
<script>
    
    Highcharts.chart('container2', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Monthly Income & Expense'
    },
   
    xAxis: {
        categories: [
           

@foreach($cash1 as $value)

 
    
    '{{$value->Date}}',
 
 @endforeach




        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Amount'
        }
    },
  
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [  

     {
        name: 'Income',
        data: [

 @foreach($cash1 as $value)

 
    
    {{$value->Rev}},
 
 @endforeach

        ]

    }, {
        name: 'Expense',
        data: [

        @foreach($cash1 as $value)

 
    
    {{$value->Exp}},
 
 @endforeach

 ]

    }],
      credits: {
    enabled: false
  },
});
</script>


<script>
    
    Highcharts.chart('sale_register', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Saleman Ticket Register'
    },

     subtitle: {
        text:
        '<a href="{{URL('/SalemanTicketRegister')}}" target="_default">DETAIL REPORT</a>'
    },
   
    xAxis: {
        categories:  {!!json_encode($ticket_register->pluck('SalemanName'))!!},
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Amount'
        },

        plotLines: [{
                        color: 'red', // Line color
                        value: {{$avg}}, // Target value
                        width: 2, // Line width
                        label: {
                             text: '{{number_format($avg,2)}}', // Label text
                            align: 'right',
                            style: {
                                color: 'red'
                            }
                        }
                    }]
                
    },
  
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },


    series: [  

    //  {
    //     name: 'Sale',
    //     data: {!!json_encode($ticket_register->pluck('TotalInvoices'))!!},

    // }, 

    {
        name: 'Net Profit',
        data:{!!json_encode($ticket_register->pluck('Service'))!!},

    }],
      credits: {
    enabled: false
  },
});
</script>



    <script type="text/javascript">//<![CDATA[


Highcharts.chart('container', {

   title: {
        text: 'Cash Flow'
    },
   

    yAxis: {
        title: {
            text: 'Amount'
        }
    },

  xAxis: {
        categories: [
           @foreach($v_cashflow as $value)
           '{{$value->MonthName}}',
           @endforeach
        ],
        // crosshair: true
    },

    

    

    series: [{
        // name: 'CashFlow',
        showInLegend: false,     
        name: ' ',
        data: [@foreach($v_cashflow as $value)
           {{$value->Balance}},
           @endforeach]
    } ],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    },
    credits: {
    enabled: false
  },

});


  //]]></script>


 



    <script> 

  // Create the chart
Highcharts.chart('container3', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Expenses'
    },
    

    accessibility: {
        announceNewData: {
            enabled: true
        },
        point: {
            valueSuffix: ''
        }
    },

    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y:.1f}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> <br/>'
    },

    series: [
        {
            // name: "Browsers",
            colorByPoint: true,
            data: [
              

@foreach($exp_chart as $value)
      
           
  {

                    name:'{{$value->ChartOfAccountName}}',
                    y: {{$value->Balance}},
                     },

                    @endforeach




                   
               
               
                
            ]
        }
    ],
     
});


  </script>


 

 <script>
     // Create the chart
Highcharts.chart('container4', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Cash Summary'
    },
   
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Amount'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}'
            }
        }
    },

    

    series: [
        {
            name: "",
            colorByPoint: true,
            data: [




 @foreach($cash as $value)

{
    name:"{{$value->ChartOfAccountName}}",
                     y: {{($value->Balance)}},
},
 @endforeach





                
                
            ]
        }
    ],
    drilldown: {
        breadcrumbs: {
            position: {
                align: 'right'
            }
        },
        series: [
            {
                name: "Chrome",
                id: "Chrome",
                data: [
                    [
                        "v65.0",
                        0.1
                    ],
                    [
                        "v64.0",
                        1.3
                    ],
                    [
                        "v63.0",
                        53.02
                    ],
                    [
                        "v62.0",
                        1.4
                    ],
                    [
                        "v61.0",
                        0.88
                    ],
                    [
                        "v60.0",
                        0.56
                    ],
                    [
                        "v59.0",
                        0.45
                    ],
                    [
                        "v58.0",
                        0.49
                    ],
                    [
                        "v57.0",
                        0.32
                    ],
                    [
                        "v56.0",
                        0.29
                    ],
                    [
                        "v55.0",
                        0.79
                    ],
                    [
                        "v54.0",
                        0.18
                    ],
                    [
                        "v51.0",
                        0.13
                    ],
                    [
                        "v49.0",
                        2.16
                    ],
                    [
                        "v48.0",
                        0.13
                    ],
                    [
                        "v47.0",
                        0.11
                    ],
                    [
                        "v43.0",
                        0.17
                    ],
                    [
                        "v29.0",
                        0.26
                    ]
                ]
            },
            {
                name: "Firefox",
                id: "Firefox",
                data: [
                    [
                        "v58.0",
                        1.02
                    ],
                    [
                        "v57.0",
                        7.36
                    ],
                    [
                        "v56.0",
                        0.35
                    ],
                    [
                        "v55.0",
                        0.11
                    ],
                    [
                        "v54.0",
                        0.1
                    ],
                    [
                        "v52.0",
                        0.95
                    ],
                    [
                        "v51.0",
                        0.15
                    ],
                    [
                        "v50.0",
                        0.1
                    ],
                    [
                        "v48.0",
                        0.31
                    ],
                    [
                        "v47.0",
                        0.12
                    ]
                ]
            },
            {
                name: "Internet Explorer",
                id: "Internet Explorer",
                data: [
                    [
                        "v11.0",
                        6.2
                    ],
                    [
                        "v10.0",
                        0.29
                    ],
                    [
                        "v9.0",
                        0.27
                    ],
                    [
                        "v8.0",
                        0.47
                    ]
                ]
            },
            {
                name: "Safari",
                id: "Safari",
                data: [
                    [
                        "v11.0",
                        3.39
                    ],
                    [
                        "v10.1",
                        0.96
                    ],
                    [
                        "v10.0",
                        0.36
                    ],
                    [
                        "v9.1",
                        0.54
                    ],
                    [
                        "v9.0",
                        0.13
                    ],
                    [
                        "v5.1",
                        0.2
                    ]
                ]
            },
            
              
        ]
    }
});
       


// sale report chart

  

 Highcharts.chart('sale_report', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Item Wise Sale'
    },
    tooltip: {
        valueSuffix: ''
    },

     subtitle: {
        text:
        '<a href="{{URL('/ItemWiseSale')}}" target="_default">DETAIL REPORT</a>'
    },


     
    plotOptions: {
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 20
            }, {
                enabled: true,
                distance: -40,
                format: '{point.percentage:.1f}%',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 10
                }
            }]
        }
    },
    series: [
        {
            name: 'No of sale ',
            colorByPoint: true,
            data: [
              


            @foreach($sale_report as $value)
                {
                    name: "{{$value->ItemName}}",
                    y: {{$value->Total}}
                },
                @endforeach
                
              
             
            ]
        }
    ]
});




// end of sale report chart


 </script>



                                </div>
                                <!-- end row -->
                            </div>
                        </div>
 
                           

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
       
                
            </div>

  @endsection