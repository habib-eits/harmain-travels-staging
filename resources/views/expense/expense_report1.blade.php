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
                                    <h4 class="mb-sm-0 font-size-18">Expense Report</h4>
                                         
 
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

            
            
  <div class="card">
      <div class="card-body">
         <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>

<?php 


$AmountTotal=0;
$Tax=0;

 ?>

 @if(count($expense_detail)>0)    
  <table class="table table-sm align-middle table-nowrap mb-0">
  <tbody><tr>
  <th scope="col">S.No</th>
  <th scope="col">Expense</th>
  <th scope="col">Date</th>
  <th scope="col">Supplier</th>
  <th scope="col">Account</th>
  <th scope="col">Tax</th>
  <th scope="col">Amount</th>
  </tr>
  </tbody>
  <tbody>
  @foreach ($expense_detail as $key =>$value)

<?php 

$AmountTotal = $AmountTotal + $value->Amount;
$Tax = $Tax + $value->Tax;

 ?>

   <tr>
   <td class="col-md-1">{{$key+1}}</td>
   <td class="col-md-1">{{$value->ExpenseNo}}</td>
   <td class="col-md-1">{{$value->Date}}</td>
   <td class="col-md-1">{{$value->SupplierName}}</td>
   <td class="col-md-1">{{$value->ChartOfAccountName}}</td>
   <td class="col-md-1">{{$value->Tax}}</td>
   <td class="col-md-1">{{$value->Amount}}</td>
   </tr>
   @endforeach   

   <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Total</td>
    <td>{{$Tax}}</td>
    <td>{{$AmountTotal}}</td>
   </tr>
   </tbody>
   </table>
   @else
     <p class=" text-danger">No data found</p>
   @endif     
</body>
</html>      
      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
  @endsection