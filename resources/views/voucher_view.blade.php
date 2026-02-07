<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Party List</title>
    <style type="text/css">
<!--
.style1 {font-size: 20px}
body,td,th {
  font-size: 12px;
  font-family: Arial, Helvetica, sans-serif;
}
.style2 {
  font-size: 16px;
  font-weight: bold;
}
-->


<?php 

$company = DB::table('company')->first();
 ?>
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
  @foreach($voucher_master as $key => $value)
<div align="center">
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2"><div align="center" class="style1">{{$company->Name}} </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>{{$company->Address}}<br>
        {{$company->Contact}} , {{$company->Mobile}} <br>
        {{$company->Email}} , {{$company->Website}} <br>
        <br>
</strong></div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><div align="center" class="style2"><u>{{$voucher_master[0]->VoucherTypeName}}</u></div></td>
    </tr>
    
    <tr>
      <td width="50%" height="18" valign="top">VOUCHER # {{$voucher_master[0]->Voucher}}</td>
    <td width="50%" valign="top"><div align="right">VH DATED: {{$value->Date}}</div></td>
    </tr>
  </table>
 
  <table width="800" border="1" cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF" style="border-collapse:collapse;">
    <tr>
      <td><strong>CHOFACC</strong></td>
      <td><strong>DESCRIPTION</strong></td>
      <td><strong>CHQ/REF # </strong></td>
      <td><strong>PARTY / SUPPLIER</strong></td>
       <td><div align="right"><strong>DEBIT</strong></div></td>
      <td><div align="right"><strong>CREDIT</strong></div></td>
    </tr>
  
<?php 

$voucher = DB::table('v_voucher_detail')
              ->where('VoucherMstID',$voucher_master[0]->VoucherMstID)
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
    </tr>
    <tr>
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
      <td><div align="right">{{number_format($DebitTotal,2)}}</div></td>
      <td><div align="right">{{number_format($CreditTotal,2)}}</div></td>
    </tr>
  </table>
  <p><br>
  </p>
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
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

</body>
</html>