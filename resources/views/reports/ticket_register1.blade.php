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
      <td colspan="2"><div align="center" class="style1">  </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong> SALE (-) SALES RETURN REGISTER </strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
    <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
   
  
  <table class="table table-bordered table-sm">
  <thead class="bg-light">
   <tr>
     <th width="5%" bgcolor="#CCCCCC"><div align="center"><strong>DATE</strong></div></th>
      <th width="5%" bgcolor="#CCCCCC"><div align="center"><strong>V.NO</strong></div></th>
      <th width="5%" bgcolor="#CCCCCC"><div align="center"><strong>PAX NAME</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>PARTY</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="right"><strong>A/LINE </strong></div></th>
       <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>PNR </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>SECTOR </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>TICKET / </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>FARE/RATE </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>TAXES </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>INCOME </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>AIRLINE </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>DISC </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>NET </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>PROFIT </strong></div></th>
   </tr>
  </thead>
  <tbody>
   @foreach ($invoice_detail as $key => $value)
    
    
   <tbody>
      <tr>
      <td><div align="center">{{dateformatman($value->Date)}}</div></td>
      <td><div align="center">{{$value->InvoiceTypeCode}}-{{$value->InvoiceMasterID}}</div></td>
      <td>{{$value->PaxName}}</td>
      <td>{{$value->PaxName}}</td>
      <td>{{$value->SupplierName}}</td>
      <td>{{$value->PNR}}</td>
      <td>{{$value->Sector}}</td>
      <td>{{$value->RefNo}}</td>
      <td><div align="center">{{number_format($value->Fare,2)}}</div></td>
      <td><div align="right">{{number_format($value->Taxable,2)}}</div></td>
      <td><div align="right">{{number_format(abs($value->Service),2)}}</div></td>
      <td><div align="right">{{number_format($value->Fare,2)}}</div></td>
      <td><div align="right">{{number_format($value->Discount,2)}}</div></td>
      <td><div align="right">{{number_format($value->Total,2)}}</div></td>
      <td><div align="right">{{number_format(($value->Service),2)}}</div></td>
    </tr>
   </tbody>
@endforeach
    <tr class="bg-light">
     <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong></strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong></strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong></strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong></strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="right"><strong></strong></div></td>
       <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong></strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong> </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>TOTAL  </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_summary[0]->Fare,2)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_summary[0]->Taxable,2)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_summary[0]->Service,2)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_summary[0]->Fare,2)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_summary[0]->Discount,2)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_summary[0]->Total,2)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_summary[0]->Service,2)}} </strong></div></td>
       
      
      
   </tr>

    

   
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