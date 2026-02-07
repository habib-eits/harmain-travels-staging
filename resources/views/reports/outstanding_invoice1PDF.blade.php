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
  $comany = DB::table('company')->first();  

  @endphp
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
	
<div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1">{{$comany->Name}} </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>OUTSTANDING INVOICES </strong></div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"></div></td>
    </tr>
    <tr>
      <td width="50%">DATED: {{date('d-m-Y')}}</td>
      <td width="50%"><div align="right">From {{request()->StartDate}} TO {{request()->EndDate}}</div></td>
    </tr>
  </table>
  <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-collapse:collapse;">
    <tr>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>INV#</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>REF#</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>VHNO</strong></div></td>
      <td width="12%" bgcolor="#CCCCCC"><div align="center"><strong>INV DATE </strong></div></td>
      <td width="12%" bgcolor="#CCCCCC"><div align="center"><strong>DUE DATE </strong></div></td>
      <td width="32%" bgcolor="#CCCCCC"><div align="center"><strong>PARTY NAME </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>BALANCE</strong></div></td>
    </tr>
    <?php $Total=0; ?>
   @foreach ($invoice as $key => $value)
    
    {{ $Total = $Total + $value->Balance  }}

    
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
  <p>&nbsp;</p>
</div>
</body>
</html>