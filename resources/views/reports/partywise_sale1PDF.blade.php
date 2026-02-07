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

    @php
  $company = DB::table('company')->first();
    @endphp
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
	
<div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1">{{$company->Name}} </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>PARTYWISE SALE (-) SALES RETURN </strong></div></td>
    </tr>
    <tr>
      <td width="50%">DATED: {{date('d-m-Y')}}</td>
      <td width="50%">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-collapse:collapse;">
    <thead style="display: table-header-group;">
    <tr>
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>S.NO</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>TYPE</strong></div></td>
      <td width="30%" bgcolor="#CCCCCC"><div align="center"><strong>NAME</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>QTY</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="right"><strong> COST</strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>SALE </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>PROFIT </strong></div></td>
    </tr>
  </thead>
   @foreach ($party_wise as $key => $value)
   	
    
    <tr>
      <td><div align="center">{{$key+1}}.</div></td>
      <td>{{$value->InvoiceType}}</td>
      <td>{{$value->PartyName}}</td>
      <td><div align="center">{{number_format($value->Qty,2)}}</div></td>
      <td><div align="right">{{number_format($value->Fare,2)}}</div></td>
      <td><div align="right">{{number_format($value->Total,2)}}</div></td>
      <td><div align="right">{{number_format($value->Service,2)}}</div></td>
    </tr>
@endforeach
  </table>
  <p>&nbsp;</p>
</div>
</body>
</html>