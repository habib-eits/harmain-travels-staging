@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')



<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">INVOICE DETAIL ITEM WISE</h4>
                                     
        From {{request()->startdate}} to {{request()->enddate}}

                                </div>
                            </div>
                        </div>
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
  
  

 

 @if(count($paymentmode) > 0)    

    @php
        // Initialize grand totals
        $grandTotalFare = 0;
        $grandTotalTaxable = 0;
        $grandTotalService = 0;
        $grandTotalTotal = 0;
    @endphp

    @foreach ($paymentmode as $key => $value1)
    <div class="card">
        <div class="card-body">

        <h2>{{$value1->PaymentMode}}</h2>

        <?php 
            $invoice_detail = DB::table('v_invoice_detail')
                ->whereBetween('Date', [request()->startdate, request()->enddate])
                ->where('ItemID', request()->itemid)
                ->where('PaymentMode', $value1->PaymentMode)
                ->get();

            // Initialize subtotals for this payment mode
            $subTotalFare = 0;
            $subTotalTaxable = 0;
            $subTotalService = 0;
            $subTotalTotal = 0;
        ?>

        @if(count($invoice_detail) > 0)    
        <table class="table table-sm align-middle table-nowrap mb-0">
        <thead>
            <tr>
                <th width="8">S.No</th>
                <th width="150">Invoice #</th>
                <th width="150">Item</th>
                <th width="150">PaxName</th>
                <th width="150">Fare</th>
                <th width="150">VAT</th>
                <th width="150">Service</th>
                <th width="150">Total</th>
                <th width="150">Saleman</th>
                <th width="150">Payment Mode</th>
                <th width="150">VHNO</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($invoice_detail as $key => $value)
            <tr>
                <td>{{$key + 1}}</td>
                <td><a href="{{URL('/InvoicePDF'.'/'.$value->InvoiceMasterID)}}" target="_blank">{{$value->InvoiceMasterID}}</a></td>
                <td>{{$value->ItemName}}</td>
                <td>{{$value->PaxName}}</td>
                <td>{{$value->Fare}}</td>
                <td>{{$value->Taxable}}</td>
                <td>{{$value->Service}}</td>
                <td>{{$value->Total}}</td>
                <td>{{$value->SalemanName}}</td>
                <td>{{$value->PaymentMode}}</td>
                <td>{{$value->Voucher}}</td>
            </tr>

            @php
                // Accumulate subtotals
                $subTotalFare += $value->Fare;
                $subTotalTaxable += $value->Taxable;
                $subTotalService += $value->Service;
                $subTotalTotal += $value->Total;
            @endphp
        @endforeach   
        </tbody>
        </table>

          <table class="table table-sm align-middle table-nowrap mb-0">
         
       <thead class="bg-light">
          <th width="8"></th>
                <th width="150"></th>
                <th width="150"></th>
                <th width="150"></th>
                <th width="150" align="right">{{ number_format($subTotalFare, 2) }}</th>
                <th width="150" align="right">{{number_format($subTotalTaxable, 2)}}</th>
                <th width="150" align="right">{{ number_format($subTotalService, 2) }}</th>
                <th width="150" align="right">{{ number_format($subTotalTotal, 2) }}</th>
                <th width="150"></th>
                <th width="150"></th>
                <th width="150"></th>
       </thead>
      </table>



        <div class="text-right mt-2 d-none">
            <strong>Subtotal for {{$value1->PaymentMode}}:</strong><br>
            Fare: {{ number_format($subTotalFare, 2) }}<br>
            VAT: {{ number_format($subTotalTaxable, 2) }}<br>
            Service: {{ number_format($subTotalService, 2) }}<br>
            Total: {{ number_format($subTotalTotal, 2) }}
        </div>

        @php
            // Accumulate grand totals
            $grandTotalFare += $subTotalFare;
            $grandTotalTaxable += $subTotalTaxable;
            $grandTotalService += $subTotalService;
            $grandTotalTotal += $subTotalTotal;
        @endphp

        @else
            <p class="text-danger">No data found</p>
        @endif

        </div>
    </div>
    @endforeach

  


<div class="card">
    <div class="card-body">
      <table class="table table-sm align-middle table-nowrap mb-0 bg-warning bg-light">
        <thead>
            <tr>
                <th width="8"></th>
                <th width="150"></th>
                <th width="150"></th>
                <th width="150"></th>
                <th width="150">Fare</th>
                <th width="150">VAT</th>
                <th width="150">Service</th>
                <th width="150">Total</th>
                <th width="150"></th>
                <th width="150"></th>
                <th width="150"></th>
            </tr>
        </thead>
        <tbody>
                <th width="8"></th>
                <th width="150"></th>
                <th width="150"></th>
                <th width="150"></th>
                <th width="150" align="right">{{ number_format($grandTotalFare, 2) }}</th>
                <th width="150" align="right">{{number_format($grandTotalTaxable, 2)}}</th>
                <th width="150" align="right">{{ number_format($grandTotalService, 2) }}</th>
                <th width="150" align="right">{{ number_format($grandTotalTotal, 2) }}</th>
                <th width="150"></th>
                <th width="150"></th>
                <th width="150"></th>

        </tbody>
      </table>
    </div>
</div>
      


@else
    <p class="text-danger">No data found</p>
@endif   

      
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
  @endsection