@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')

<style>
  div {
  word-wrap: break-word;
}
</style>

<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Journal Detail</h4>
                                         
 
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

            
            
  <div class="table-responsive">
    <div class="card">
      <div class="card-body">
        
 @if(count($journal)>0)    
<table class="table table-sm align-middle  mb-3">
<tbody><tr>
 <th class="col-md-1">DATE</th>
<th class="col-md-1">ACCOUNT</th>
<th class="col-md-1">VHNO</th>
<th class="col-md-1">TYPE</th>
<th class="col-md-6">TRANSACTION DETAILS  </th>
<th class="col-md-1">DEBIT</th>
<th class="col-md-1">CREDIT</th>
<th class="col-md-1">EDIT</th>
</tr>
</tbody>
<tbody>
  <?php 

$TotalDr=0;
$TotalCr=0;
 $link = '';
     $linke = '';



   ?>
@foreach ($journal as $key =>$value)

<?php 

$TotalDr = $TotalDr+ $value->Dr;
$TotalCr = $TotalCr+ $value->Cr;



 
$vhno = substr($value->VHNO,0,2);
 
 
 


 switch ($vhno) {

   case 'SI':  
     $link = 'InvoiceView/'.$value->InvoiceMasterID;
     $linke = 'InvoiceEdit/'.$value->InvoiceMasterID;
     break;

 case 'SR':  
     $link = 'InvoiceView/'.$value->InvoiceMasterID;
     $linke = 'InvoiceEdit/'.$value->InvoiceMasterID;
     break;


 case 'BILL':  
     $link = 'BillView/'.$value->InvoiceMasterID;
     $linke = 'BillEdit/'.$value->InvoiceMasterID;
     break;

 case 'BILLPAY':  
     $link = 'PurchasePaymentView/'.$value->PurchasePaymentMasterID;
     $linke = 'PurchasePaymentEdit/'.$value->PurchasePaymentMasterID;
     break;     


 case 'EX':  
     $link = 'ExpenseView/'.$value->ExpenseMasterID;
     $linke = 'ExpenseEdit/'.$value->ExpenseMasterID;
     break;



 case 'JV':  
     $link = 'VoucherView/'.$value->VoucherMstID;
     $linke = 'VoucherEdit/'.$value->VoucherMstID;
     break;


 case 'BP':  
     $link = 'VoucherView/'.$value->VoucherMstID;
     $linke = 'VoucherEdit/'.$value->VoucherMstID;
     break;

 case 'BR':  
     $link = 'VoucherView/'.$value->VoucherMstID;
     $linke = 'VoucherEdit/'.$value->VoucherMstID;
     break;
 

 case 'CP':  
     $link = 'VoucherView/'.$value->VoucherMstID;
     $linke = 'VoucherEdit/'.$value->VoucherMstID;
     break;


 case 'CR':  
     $link = 'VoucherView/'.$value->VoucherMstID;
     $linke = 'VoucherEdit/'.$value->VoucherMstID;
     break;

   case 'PAY':  
     $link = 'PaymentView/'.$value->VoucherMstID;
     $linke = 'PaymentEdit/'.$value->VoucherMstID;
     break;

  case 'LP':  
     $link = '#';
     $linke = '#';
     break;

   
   
 }
 
  
 ?>

 
 
 <tr>
  <td class="col-md-1">{{dateformatman2($value->Date)}}</td>
 <td class="col-md-1">{{$value->ChartOfAccountName}}</td>
 <td class="col-md-1">{{$value->VHNO}}</td>
 <td class="col-md-1">{{$value->JournalType}} </td>
 <td width="20">{{$value->Narration}}</td>
 <td class="col-md-1" ><a href="{{URL('/'.$link)}}">{{$value->Dr}}</a></td>
 <td class="col-md-1" ><a href="{{URL('/'.$link)}}">{{$value->Cr}}</a></td>
 <td class="col-md-1" ><a href="{{URL('/'.$linke)}}" target="_blank"><i class="font-size-18 bx bx-pencil align-middle me-1 text-secondary"></i></a></td>
 </tr>
 @endforeach   

 <tr>
   <td></td>
   <td colspan="2">Total Debits and Credits ({{dateformatman2($StartDate)}} - {{dateformatman2($EndDate)}})</td>
   
   <td></td>
   <td></td>
   <td>{{session::get('Currency')}} {{$TotalDr}}</td>
   <td>{{session::get('Currency')}} {{$TotalCr}}</td>
   
 </tr>
 <tr>
   <td>As on {{dateformatman2($EndDate)}}</td>
   <td>Closing Balance  </td>
   <td></td>
   <td></td>
   <td></td>
   <td>{{session::get('Currency')}} {{number_format($TotalDr-$TotalCr,2)}}</td>
   <td></td>
 </tr></tbody>
 </table>
 <div ><span >Total Count : {{count($journal)}}</span></div>
 @else
   <p class=" text-danger">No data found</p>
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
 
  @endsection