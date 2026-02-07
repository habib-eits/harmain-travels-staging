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
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1"> </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>AIRLINE SUMMARY  SALE (-) SALES RETURN </strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
    <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
  <table class="table table-bordered table-sm">
    <tr class="bg-light">
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>DATE</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>VHNO</strong></div></td>
      <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong>NAME</strong></div></td>
      <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong>SECTOR</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>FARE/RATE</strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>TAX </strong></div></td>
       <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>INCOME </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>DISCOUNT </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>NET </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>PROFIT </strong></div></td>
    </tr>

    <?php 

    $Fare=0;
    $Taxable=0;
    $Service=0;
    $Discount=0;
    $Total=0;
    


     ?>
   @foreach ($invoice_detail as $key => $value)
    
<?php 

    $Fare= $Fare  +  $value->Fare;
    $Taxable= $Taxable + $value->Taxable;
    $Service= $Service + $value->Service;
    $Discount = $Discount + $value->Discount;
    $Total= $Total + $value->Total;



 ?>

    
    <tr>
      <td><div align="center">{{dateformatman($value->Date)}}</div></td>
      <td><div align="center">{{$value->InvoiceTypeCode}}-{{$value->InvoiceMasterID}}</div></td>
      <td>{{$value->PaxName}}</td>
      <td>{{$value->Sector}}</td>
      <td><div align="center">{{number_format($value->Fare,2)}}</div></td>
      <td><div align="right">{{number_format($value->Taxable,2)}}</div></td>
      <td><div align="right">{{number_format($value->Service,2)}}</div></td>
      <td><div align="right">{{number_format($value->Discount,2)}}</div></td>
      <td><div align="right">{{number_format($value->Total,2)}}</div></td>
      <td><div align="right">{{number_format($value->Service,2)}}</div></td>
    </tr>
@endforeach

  <tr style="font-weight: bolder;">
      <td colspan="4"> <div align="center">TOTAL</div></td>
    
      <td><div align="center">{{number_format($Fare,2)}}</div></td>
      <td><div align="right">{{number_format($Taxable,2)}}</div></td>
      <td><div align="right">{{number_format($Service,2)}}</div></td>
      <td><div align="right">{{number_format($Discount,2)}}</div></td>
      <td><div align="right">{{number_format($Total,2)}}</div></td>
      <td><div align="right">{{number_format($Service,2)}}</div></td>
    </tr>



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