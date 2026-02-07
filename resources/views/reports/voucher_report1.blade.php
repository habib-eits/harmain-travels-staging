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
         @foreach($voucher_master as $key => $value)
<div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    
   
   
    <tr>
      <td colspan="2"><div align="center" class="style2"><u><strong>{{$voucher_type[0]->VoucherTypeName}}</strong></u></div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="left"><span class="style2">Voucher # {{$value->Voucher}}</span></div></td>
    </tr>
    <tr>
      <td width="50%" height="18" valign="top">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
    <td width="50%" valign="top"><div align="right">DATED: {{$value->Date}}</div></td>
    </tr>
  </table>
 
  <table class="table table-bordered table-sm">
    <tr class="bg-light">
      <td><strong>CHOFACC</strong></td>
      <td><strong>DESCRIPTION</strong></td>
      <td><strong>CHQ/REF # </strong></td>
      <td><strong>PARTY</strong></td>
      <td><strong>SUPPLIER</strong></td>
      <td><div align="right"><strong>DEBIT</strong></div></td>
      <td><div align="right"><strong>CREDIT</strong></div></td>
    </tr>
  
<?php 

$voucher = DB::table('v_voucher_detail')
              ->where('VoucherMstID',$value->VoucherMstID)

            ->get();


            $DebitTotal=0;
            $CreditTotal=0;

?>


    @foreach($voucher as $value1)
     

<?php if(!isset($DebitTotal))
{
  $DebitTotal = $value1->Debit;
  $CrebitTotal = $value1->Crebit;
}
else
{
$DebitTotal = $DebitTotal+ $value1->Debit;
$CreditTotal = $CreditTotal+ $value1->Credit;
}

 
 ?>
      <tr>
      <td>{{$value1->ChartOfAccountName}}</td>
      <td>{{$value1->Narration}}</td>
      <td>{{$value1->RefNo}}</td>
      <td>{{$value1->PartyName}}</td>
      <td>{{$value1->SupplierName}}</td>
      
      <td><div align="right">{{is_null($value1->Debit) ? '' : number_format($value1->Debit,2)}}</div></td>
      <td><div align="right">{{is_null($value1->Credit) ? '' : number_format($value1->Credit,2)}}</div></td>
      </tr>

    @endforeach


    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">{{env('APP_CURRENCY')}} </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right">{{number_format($DebitTotal,2)}}</div></td>
      <td><div align="right">{{number_format($CreditTotal,2)}}</div></td>
    </tr>
  </table>
  <p><br>
  </p>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="33%">PAID / CHECK BY </td>
      <td width="33%"><div align="center">AUTHORIZED BY </div></td>
      <td width="33%"><div align="right">RECEIVED BY </div></td>
    </tr>
    <tr>
      <td width="33%">(Operator : Administrator </td>
      <td width="33%">&nbsp;</td>
      <td width="33%">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p style="page-break-after: always;">&nbsp;</p>
</div>
@endforeach      
      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
  @endsection