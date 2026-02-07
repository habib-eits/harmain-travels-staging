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
      <td colspan="2"><div align="center"><strong>TAX REPORT  SALE (-) SALES RETURN </strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
    <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>

<?php 

$Total=0;
$IPVAT=0;
$Service=0;
$Taxable=0;

 ?>

  <table class="table table-bordered table-sm">
    <tr class="bg-light">
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>DATE</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>TYPE</strong></div></td>
      <td width="30%" bgcolor="#CCCCCC"><div align="center"><strong>ACTIVITY</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>PAX</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>Gross & Tax</strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>IPVAT </strong></div></td>
       <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>SERVICE </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>OPVAT </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>NET VAT </strong></div></td>
    </tr>
   @foreach ($invoice_detail as $key => $value)
    
<?php 

$Total=$Total+$value->Total;
$IPVAT=$IPVAT+$value->IPVAT;
$Service=$Service+$value->Service;
$Taxable=$Taxable+$value->Taxable;

 ?>

    
    <tr>
      <td><div align="center">{{dateformatman($value->Date)}}</div></td>
      <td><div align="center"><a href="{{URL('/UmrahEdit/'.$value->InvoiceMasterID)}}" target="_blank">{{$value->InvoiceTypeCode}}-{{$value->InvoiceMasterID}}</a></div></td>
      <td>{{$value->ItemName}}</td>
      <td>{{$value->PaxName}}</td>
      <td><div align="center">{{number_format($value->Total,2)}}</div></td>
      <td><div align="right">{{number_format($value->IPVAT,2)}}</div></td>
      <td><div align="right">{{number_format($value->Service,2)}}</div></td>
      <td><div align="right">{{number_format($value->Taxable,2)}}</div></td>
      <td><div align="right">{{number_format($value->Taxable,2)}}</div></td>
    </tr>
@endforeach


 <tr >
      
      
      
      <td colspan="4" class="text-end"><strong>TOTAL</strong></td>
      <td><div align="center"><strong>{{number_format($Total,2)}}</strong></div></td>
      <td><div align="right"><strong>{{number_format($IPVAT,2)}}</strong></div></td>
      <td><div align="right"><strong>{{number_format($Service,2)}}</strong></div></td>
      <td><div align="right"><strong>{{number_format($Taxable,2)}}</strong></div></td>
      <td><div align="right"><strong>{{number_format($Taxable,2)}}</strong></div></td>
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