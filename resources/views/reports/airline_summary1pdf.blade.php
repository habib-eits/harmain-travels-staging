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
-->
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
	
@php
$company = DB::table('company')->first();
@endphp

<div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1">{{$company->Name}} </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>AIRLINE SUMMARY  SALE (-) SALES RETURN </strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
	  <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
  <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-collapse:collapse;">
    <tr>
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
  <p>&nbsp;</p>
</div>
</body>
</html>