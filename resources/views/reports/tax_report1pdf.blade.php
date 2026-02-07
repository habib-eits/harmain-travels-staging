<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{$pagetitle}}</title>
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
  $company=DB::table('company')->first();  

  @endphp
<div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1">{{$company->Name}} </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>TAX REPORT  SALE (-) SALES RETURN </strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
	  <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
  <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-collapse:collapse;">
    <tr>
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

<?php 

$Total=0;
$IPVAT=0;
$Service=0;
$Taxable=0;

 ?>
 
   @foreach ($invoice_detail as $key => $value)
   	    <?php 

$Total=$Total+$value->Total;
$IPVAT=$IPVAT+$value->IPVAT;
$Service=$Service+$value->Service;
$Taxable=$Taxable+$value->Taxable;

 ?>
    
    <tr>
      <td><div align="center">{{dateformatman($value->Date)}}</div></td>
      <td><div align="center">{{$value->InvoiceTypeCode}}-{{$value->InvoiceMasterID}}</div></td>
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
  <p>&nbsp;</p>
</div>
</body>
</html>