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

 


@php

 $revenue = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
       ->where('ChartOfAccountID',410200)
       ->whereBetween('date', array(request()->StartDate, request()->EndDate))
      ->first();    

@endphp
            

<?php 
$DrTotal=0;
$CrTotal=0;

$date1 = date_create(date('Y-m-d', strtotime(request()->StartDate)));
$date2 = date_create(date('Y-m-d', strtotime(request()->EndDate)));
$interval = $date1->diff($date2);
$diff = ($interval->days==0) ? 1 : $interval->days+1;
$avg = ($invoice_summary[0]->Service/$diff);



             ?>

 <!-- start page title -->



<div class="row">
  <div class="col-md-6 mt-4"><h1>SALESMAN WISE TICKET REGISTER SUMMARY</h1></div>
  <div class="col-md-5"> <form method="post" name="form1" id="form1" class="form-inline w-100 d-flex align-items-center" action="{{URL('/SalemanTicketRegister1')}}">
                        @csrf


                     @include('components.start_end_date')
            
             <button type="submit" class="btn btn-success mt-4" id="online">Submit</button>
                      
<div class="clearfix"></div>



                    </form></div>
</div>


                   
                        <!-- end page title -->


  <div class="card">
      <div class="card-body">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
  
    <tr>
      <td colspan="2"><div align="center"></div><br>
      <h2 class="text-danger text-center">Daily Averge : {{number_format($avg,2)}}</h2></td>
    </tr>
    <tr>
      <td width="50%">From {{dateformatman2(request()->StartDate)}} To {{dateformatman2(request()->EndDate)}}</td>
    <td width="50%"><div align="right">DATED: {{dateformatman2(date('Y-m-d'))}}</div></td>
    
    </tr>
  </table>
   
  <div class="table-responsive mb-0 fixed-solution">
    
  @if($invoice_detail)
<table class="table table-bordered table-sm mt-4" id="salesTable">
  <thead class="bg-light">
    <tr>
      <th width="10%" bgcolor="#CCCCCC"><div align="left"><strong>Saleman Name</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>Total Invoices</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>Cost</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>VAT 5% </strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>Total Sale</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>Net Profit</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>Per%</strong></div></th>
    </tr>
  </thead>
  <tbody>
    @php
      // Initialize aggregate variables
      $totalInvoices = 0;
      $totalFare = 0;
      $totalTaxable = 0;
      $totalSale = 0;
      $totalProfit = 0;
    @endphp

    @foreach ($invoice_detail as $key => $value)
      @php
        // Update aggregate totals
        $totalInvoices += $value->TotalInvoices;
        $totalFare += $value->Fare;
        $totalTaxable += $value->Taxable;
        $totalSale += $value->Total;
        $totalProfit += $value->Service;
      @endphp
      <tr>
        <td><div align="left">{{ $value->SalemanName }}</div></td>
        <td><div align="center">{{ number_format($value->TotalInvoices, 2) }}</div></td>
        <td><div align="center">{{ number_format($value->Fare, 2) }}</div></td>
        <td><div align="center">{{ number_format($value->Taxable, 2) }}</div></td>
        <td><div align="center">{{ number_format($value->Total, 2) }}</div></td>
        <td><div align="center">{{ number_format(abs($value->Service), 2) }}</div></td>
        <td><div align="center">{{ number_format(abs($value->Service / ($value->Total == 0 ? 1 : $value->Total)) * 100, 2) }}%</div></td>
      </tr>
    @endforeach

  </tbody>

    <tr class="bg-light">
      <td><div align="left"><strong>Totals</strong></div></td>
      <td><div align="center"><strong>{{ number_format($totalInvoices, 2) }}</strong></div></td>
      <td><div align="center"><strong>{{ number_format($totalFare, 2) }}</strong></div></td>
      <td><div align="center"><strong>{{ number_format($totalTaxable, 2) }}</strong></div></td>
      <td><div align="center"><strong>{{ number_format($totalSale, 2) }}</strong></div></td>
      <td><div align="center"><strong>{{ number_format(abs($totalProfit), 2) }}</strong></div></td>
      <td><div align="center"><strong>{{ number_format(abs($totalProfit / ($totalSale == 0 ? 1 : $totalSale)) * 100, 2) }}%</strong></div></td>


    </tr>
    
       <!-- Add Aggregate Row -->
       <tr class="bg-dark bg-soft">
        <td><div align="left"><strong>Other Income</strong></div></td>
        <td><div align="center"></td>
          <td><div align="center"></td>
            <td><div align="center"></td>
              <td><div align="center"></td>
                <td><div align="center"><strong>{{ number_format(abs($revenue->Balance), 2) }}</strong></div></td>
        <td><div align="center"></td>
      </tr>
      
 
      <!-- Add Aggregate Row -->
      <tr class="bg-light">
        <td><div align="left"><strong>Grand Income</strong></div></td>
        <td><div align="center"></td>
          <td><div align="center"></td>
            <td><div align="center"></td>
              <td><div align="center"></td>
                <td><div align="center"><strong>{{ number_format(abs($revenue->Balance + $totalProfit), 2) }}</strong></div></td>
        <td><div align="center"></td>
      </tr>
   

 
</table>
  
@else
<p class="text-danger">No invoice found</p>

@endif

   </div>

      </div>
  </div>
  
 

  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
<script>
    $(document).ready(function () {
        $('#salesTable').DataTable({
            paging: false,  // Disable pagination (optional)
            ordering: true, // Enable column sorting
            info: true,    // Hide table info (optional)
            // stateSave: true,
            // responsive: true,
            searching: false,
            dom: 'lfrtip',
            order: [[6, 'desc']] // Column index 6 (7th column) in descending order
        });
    });
</script>

  @endsection