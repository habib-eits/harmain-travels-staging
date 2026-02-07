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
  $company=DB::table('company')->first();  

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
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>DATE</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>VHNO</strong></div></td>
      <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong>NAME</strong></div></td>
      <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong>SECTOR</strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>FARE/RATE</strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>TAX </strong></div></td>
       <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>INCOME </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>DISCOUNT </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>NET </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>PROFIT </strong></div></td>
    </tr>
        <?php 

    $Fare=0;
    $Taxable=0;
    $Service=0;
    $Discount=0;
    $Total=0;
    


     ?>
   @foreach ($invoice_detail as $key => $value)
   	<?php 

    $Fare= $Fare  +  $value->Fare;
    $Taxable= $Taxable + $value->Taxable;
    $Service= $Service + $value->Service;
    $Discount = $Discount + $value->Discount;
    $Total= $Total + $value->Total;



 ?>
    
    <tr>
      <td><div align="center">{{dateformatman($value->Date)}}</div></td>
      <td><div align="center">{{$value->InvoiceTypeCode}}-{{$value->InvoiceMasterID}}</div></td>
      <td>{{$value->PaxName}}</td>
      <td>{{$value->Sector}}</td>
      <td><div align="center">{{number_format($value->Fare,2)}}</div></td>
      <td><div align="right">{{number_format($value->Taxable,2)}}</div></td>
      <td><div align="right">{{number_format($value->Service,2)}}</div></td>
      <td><div align="right">{{number_format($value->Discount,2)}}</div></td>
      <td><div align="right">{{number_format($value->Total,2)}}</div></td>
      <td><div align="right">{{number_format($value->Service,2)}}</div></td>
    </tr>
@endforeach
  <tr style="font-weight: bolder;">
      <td colspan="4"> <div align="center">TOTAL</div></td>
    
      <td><div align="center">{{number_format($Fare,2)}}</div></td>
      <td><div align="right">{{number_format($Taxable,2)}}</div></td>
      <td><div align="right">{{number_format($Service,2)}}</div></td>
      <td><div align="right">{{number_format($Discount,2)}}</div></td>
      <td><div align="right">{{number_format($Total,2)}}</div></td>
      <td><div align="right">{{number_format($Service,2)}}</div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>
</body>
</html>