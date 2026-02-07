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
      <td colspan="2"><div align="center"><strong>AIRLINE SUMMARY  SALE (-) SALES RETURN </strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
    <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
  <table class="table table-bordered table-sm">
    <tr class="bg-light">
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>S.NO</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>TYPE</strong></div></td>
      <td width="30%" bgcolor="#CCCCCC"><div align="center"><strong>SUPPLIER</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>FARE</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="right"><strong> TAX</strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>SERVICE </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>IPVAT </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>OPVAT </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>TOTAL </strong></div></td>
    </tr>
   @foreach ($supplier as $key => $value)
    
    
    <tr>
      <td><div align="center">{{$key+1}}.</div></td>
      <td>{{$value->InvoiceTypeCode}}</td>
      <td>{{$value->SupplierName}}</td>
      <td><div align="center">{{number_format($value->Fare,2)}}</div></td>
      <td><div align="right">{{number_format($value->Taxable,2)}}</div></td>
      <td><div align="right">{{number_format($value->Service,2)}}</div></td>
      <td><div align="right">{{number_format($value->IPVAT,2)}}</div></td>
      <td><div align="right">{{number_format($value->OPVAT,2)}}</div></td>
      <td><div align="right">{{number_format($value->Total,2)}}</div></td>
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