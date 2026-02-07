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
      <td colspan="2"><div align="center"><strong>PARTYWISE SALE </strong></div></td>
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



<table class="table table-bordered table-sm">
    <thead class="bg-light">
        <tr>
            <th rowspan="2" width="5%" class="text-center align-middle">S.NO</th>
            <th rowspan="2" width="5%" class="text-center align-middle">DATE</th>
            <th rowspan="2" width="5%" class="text-center align-middle">INV #</th>
            <th rowspan="2" width="20%" class="text-center align-middle">NAME</th>

            <th colspan="3" class="text-center bg-secondary text-white">VISA</th>
            <th colspan="3" class="text-center bg-secondary text-white">TICKET</th>
            <th colspan="3" class="text-center bg-secondary text-white">HOTEL</th>
            <th colspan="3" class="text-center bg-secondary text-white">TRANSPORT</th>

            <th rowspan="2" class="text-center align-middle bg-secondary text-white">TOTAL <Br> RECEIVABLE</th>
            <th rowspan="2" class="text-center align-middle bg-secondary text-white">TOTAL <Br>PAYABLE</th>
            <th rowspan="2" class="text-center align-middle bg-secondary text-white">NET <Br>PROFIT</th>
        </tr>
        <tr>
            <th class="text-center">SALE</th>
            <th class="text-center">COST</th>
            <th class="text-center">PROFIT</th>

            <th class="text-center">SALE</th>
            <th class="text-center">COST</th>
            <th class="text-center">PROFIT</th>

            <th class="text-center">SALE</th>
            <th class="text-center">COST</th>
            <th class="text-center">PROFIT</th>

            <th class="text-center">SALE</th>
            <th class="text-center">COST</th>
            <th class="text-center">PROFIT</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totals = [
                'visa_sale' => 0, 'visa_cost' => 0, 'visa_profit' => 0,
                'ticket_sale' => 0, 'ticket_cost' => 0, 'ticket_profit' => 0,
                'hotel_sale' => 0, 'hotel_cost' => 0, 'hotel_profit' => 0,
                'transport_sale' => 0, 'transport_cost' => 0, 'transport_profit' => 0,
                'receivable' => 0, 'payable' => 0, 'profit' => 0,
            ];
        @endphp

        @foreach ($sale as $key => $value)
            @php
                $receivable = $value->visa_sale + $value->ticket_sale + $value->HotelReceivable + $value->transport_sale;
                $payable = $value->visa_purchase + $value->ticket_purchase + $value->HotelPayable + $value->transport_purchase;
                $profit = $receivable - $payable;

                // accumulate totals
                $totals['visa_sale'] += $value->visa_sale;
                $totals['visa_cost'] += $value->visa_purchase;
                $totals['visa_profit'] += $value->visa_profit;

                $totals['ticket_sale'] += $value->ticket_sale;
                $totals['ticket_cost'] += $value->ticket_purchase;
                $totals['ticket_profit'] += $value->ticket_profit;

                $totals['hotel_sale'] += $value->HotelReceivable;
                $totals['hotel_cost'] += $value->HotelPayable;
                $totals['hotel_profit'] += $value->hotel_profit;

                $totals['transport_sale'] += $value->transport_sale;
                $totals['transport_cost'] += $value->transport_purchase;
                $totals['transport_profit'] += $value->transport_profit;

                $totals['receivable'] += $receivable;
                $totals['payable'] += $payable;
                $totals['profit'] += $profit;
            @endphp

            <tr>
    <td class="text-center">{{ $key+1 }}</td>
    <td class="text-center">{{ dateformatman($value->Date) }}</td>
    <td class="text-center ">INV-{{ $value->InvoiceMasterID }}</td>
    <td class="group-border">{{ $value->PartyName }}</td>

    {{-- VISA --}}
    <td class="text-center ">{{ number_format($value->visa_sale , 2) }}</td>
    <td class="text-center">{{ number_format($value->visa_purchase, 2) }}</td>
    <td class="text-center group-border">{{ number_format($value->visa_profit, 2) }}</td>

    {{-- TICKET --}}
    <td class="text-center">{{ number_format($value->ticket_sale, 2) }}</td>
    <td class="text-center">{{ number_format($value->ticket_purchase, 2) }}</td>
    <td class="text-center group-border">{{ number_format($value->ticket_profit, 2) }}</td>

    {{-- HOTEL --}}
    <td class="text-center">{{ number_format($value->HotelReceivable, 2) }}</td>
    <td class="text-center">{{ number_format($value->HotelPayable, 2) }}</td>
    <td class="text-center group-border">{{ number_format($value->hotel_profit, 2) }}</td>

    {{-- TRANSPORT --}}
    <td class="text-center">{{ number_format($value->transport_sale, 2) }}</td>
    <td class="text-center">{{ number_format($value->transport_purchase, 2) }}</td>
    <td class="text-center group-border">{{ number_format($value->transport_profit, 2) }}</td>

    {{-- TOTAL --}}
    <td class="text-center">{{ number_format($receivable, 2) }}</td>
    <td class="text-center">{{ number_format($payable, 2) }}</td>
    <td class="text-center">{{ number_format($profit, 2) }}</td>
</tr>

        @endforeach
 
        {{-- TOTAL ROW --}}
        <tr class="bg-light fw-bolder">
            <td colspan="4" class="text-center"><strong>TOTAL</strong></td>

            <td class="text-center">{{ number_format($totals['visa_sale'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['visa_cost'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['visa_profit'], 2) }}</td>

            <td class="text-center">{{ number_format($totals['ticket_sale'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['ticket_cost'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['ticket_profit'], 2) }}</td>

            <td class="text-center">{{ number_format($totals['hotel_sale'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['hotel_cost'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['hotel_profit'], 2) }}</td>

            <td class="text-center">{{ number_format($totals['transport_sale'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['transport_cost'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['transport_profit'], 2) }}</td>

            <td class="text-center">{{ number_format($totals['receivable'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['payable'], 2) }}</td>
            <td class="text-center">{{ number_format($totals['profit'], 2) }}</td>
        </tr>
    </tbody>
</table>
       
      </div>
  </div>
  
  
  <div class="card d-none">
      <div class="card-body">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1"> </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>PARTYWISE SALE </strong></div></td>
    </tr>
    <tr>
      <td width="50%">DATED: {{date('d-m-Y')}}</td>
      <td width="50%">&nbsp;</td>
    </tr>
  </table>
  <table  class="table table-bordered table-sm">
    <thead class="bg-light">
    <tr>
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>S.NO</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>TYPE</strong></div></td>
      <td width="30%" bgcolor="#CCCCCC"><div align="center"><strong>NAME</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>QTY</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="right"><strong> COST</strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>SALE </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>PROFIT </strong></div></td>
    </tr>
  </thead>
   @foreach ($party_wise as $key => $value)
    
    
    <tr>
      <td><div align="center">{{$key+1}}.</div></td>
      <td>{{$value->InvoiceType}}</td>
      <td>{{$value->PartyName}}</td>
      <td><div align="center">{{number_format($value->Qty,2)}}</div></td>
      <td><div align="right">{{number_format($value->Fare,2)}}</div></td>
      <td><div align="right">{{number_format($value->Total,2)}}</div></td>
      <td><div align="right">{{number_format($value->Service,2)}}</div></td>
    </tr>
@endforeach
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