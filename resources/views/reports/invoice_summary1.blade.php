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
  <table style="width: 100%;">
    <tr>
      <td colspan="2"><div align="center" class="style1">  </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong> INVOICE SUMMARY SALEMAN WISE</strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
    <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
   
  @if(count($invoice_summary)>0)
  <table class="table table-bordered table-sm">
  <thead style="display: table-header-group;">
   <tr class="bg-light">
     <th width="5%" bgcolor="#CCCCCC"><div align="center"><strong>S#</strong></div></th>
      <th width="15%" bgcolor="#CCCCCC"><div align="left"><strong>SALEMAN NAME</strong></div></th>
      <th width="5%" bgcolor="#CCCCCC"><div align="center"><strong>QTY</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>GROSS</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>TAXES </strong></div></th>
       <th width="9%" bgcolor="#CCCCCC"><div align="center"><strong>PAYABLE </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="center"><strong>SERVICE </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="center"><strong>DIS/ </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="center"><strong>PROFIT </strong></div></th>
       
   </tr>
  </thead>
   <tbody>

<?php   

$qty=0;
$total=0;
$taxable=0;
$service=0;
$discount=0;




 ?>

    @foreach ($invoice_summary as $key => $value)


<?php   

$qty=$qty+$value->Qty;
$total=$total+$value->Total;
$taxable=$taxable+$value->Taxable;
$service=$service+$value->Service;
$discount=$discount+$value->Discount;



 ?>
    
    
      <tr>
      
      <td><div align="center">{{$key+1}}</div></td>
       <td>{{$value->SalemanName}}</td>
       <td align="center">{{$value->Qty}}</td>
      <td><div align="center">{{number_format($value->Total,2)}}</div></td>
     
      <td><div align="center">{{number_format($value->Taxable,2)}}</div></td>
     
      <td><div align="center">{{number_format($value->Total,2)}}</div></td>
     
      <td><div align="center">{{number_format($value->Service,2)}}</div></td>
     
      <td><div align="center">{{number_format($value->Discount,2)}}</div></td>
      <td><div align="center">{{number_format($value->Service,2)}}</div></td>
      
 

    </tr>
 @endforeach

 <tr style="font-weight: bolder; background-color: #e9e9e9;"> 

  <td align="center" colspan="2" >Total</td>
  <td align="center">{{number_format($qty)}}</td>
  <td align="center">{{number_format($total,2)}}</td>
  <td align="center">{{number_format($taxable,2)}}</td>
  <td align="center">{{number_format($total,2)}}</td>
  <td align="center">{{number_format($service,2)}}</td>
  <td align="center">{{number_format($discount,2)}}</td>
  <td align="center">{{number_format($service,2)}}</td>
 </tr>

 
@else
<p class="text-danger"> <strong>  No record found</strong></p>
@endif
    

   
  </tbody>
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