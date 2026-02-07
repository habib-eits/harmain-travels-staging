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
    @if ($invoice->isNotEmpty())
  <div class="card">
      <div class="card-body">
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1"> </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>OUTSTANDING NORMAL INVOICES </strong></div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"></div></td>
    </tr>
    <tr>
      <td width="50%">DATED: {{date('d-m-Y')}}</td>
      <td width="50%"><div align="right">From {{request()->StartDate}} TO {{request()->EndDate}}</div></td>
    </tr>
  </table>
    <table class="table table-striped table-sm">
    <tr>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>INV#</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>REF#</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>VHNO</strong></div></td>
      <td width="12%" bgcolor="#CCCCCC"><div align="center"><strong>INV DATE </strong></div></td>
      <td width="12%" bgcolor="#CCCCCC"><div align="center"><strong>DUE DATE </strong></div></td>
      <td width="32%" bgcolor="#CCCCCC"><div align="left"><strong>PARTY NAME </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>BALANCE</strong></div></td>
    </tr>
    <?php $Total=0; ?>
   @foreach ($invoice as $key => $value)
    
    @php
        $Total = $Total + $value->Balance
    @endphp

    
    <tr>
       <td><div align="center">{{$value->InvoiceMasterID}}</div></td>
       <td><div align="center">{{$value->InvoiceMasterID}}</div></td>
      <td><div align="center">{{$value->InvoiceCode}}</div></td>
      <td><div align="center">{{$value->Date}}</div></td>
      
      <td><div align="center">{{$value->DueDate}}</div></td>
      <td>{{$value->PartyName}}</td>
      <td><div align="right">{{number_format($value->Balance,2)}}</div></td>
    </tr>
  @endforeach
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="center"><strong>TOTAL</strong></div></td>
      <td><div align="right">{{number_format($Total,2)}}</div></td>
    </tr>

  </table> 
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
      <td colspan="2"><div align="center"><strong>OUTSTANDING UMRAH INVOICES </strong></div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"></div></td>
    </tr>
    <tr>
      <td width="50%">DATED: {{date('d-m-Y')}}</td>
      <td width="50%"><div align="right">From {{request()->StartDate}} TO {{request()->EndDate}}</div></td>
    </tr>
  </table>
@if ($invoice_umrah->isNotEmpty())
    <table class="table table-striped table-sm">
    <tr>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>INV#</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>REF#</strong></div></td>
      <td width="12%" bgcolor="#CCCCCC"><div align="center"><strong>INV DATE </strong></div></td>
       <td width="24%" bgcolor="#CCCCCC"><div align="center"><strong>Package </strong></div></td>
      <td width="55%" bgcolor="#CCCCCC"><div align="left"><strong>PARTY NAME </strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>BALANCE</strong></div></td>
    </tr>
    <?php $Total=0; ?>
   @foreach ($invoice_umrah as $key => $value)
    
    @php
        $Total = $Total + $value->Balance
    @endphp

    
    <tr>
       <td><div align="center">{{$value->InvoiceMasterID}}</div></td>
       <td><div align="center">{{$value->InvoiceMasterID}}</div></td>
       <td><div align="center">{{$value->Date}}</div></td>
       
      <td><div align="center">{{$value->name}}</div></td>
      <td>{{$value->PartyName}}</td>
      <td><div align="right">{{number_format($value->Balance,2)}}</div></td>
    </tr>
  @endforeach
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
       <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="center"><strong>TOTAL</strong></div></td>
      <td><div align="right">{{number_format($Total,2)}}</div></td>
    </tr>

  </table> 
@endif     
      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
  @endsection