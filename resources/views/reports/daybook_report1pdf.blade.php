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
      <td colspan="2"><div align="center"><strong>DAY BOOK - CASH &amp; SALE </strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
	  <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
  
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td valign="top"><table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-collapse:collapse;">
    <tr>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>DATE</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>VHNO</strong></div></td>
      <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong>NAME</strong></div></td>
      <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong>SECTOR</strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>TOTAL </strong></div></td>
      <td width="8%" bgcolor="#CCCCCC"><div align="center"><strong>AIRLINE</strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>PROFIT </strong></div></td>
       </tr>
   @foreach ($invoice_detail as $key => $value)
   	
    
    <tr>
      <td><div align="center">{{dateformatman($value->Date)}}</div></td>
      <td><div align="center">{{$value->InvoiceTypeCode}}-{{$value->InvoiceMasterID}}</div></td>
      <td>{{$value->PaxName}}</td>
      <td>{{$value->Sector}}</td>
      <td><div align="right">{{number_format($value->Total,2)}}</div></td>
      <td><div align="center">{{number_format($value->Fare,2)}}</div></td>
      <td><div align="right">{{number_format($value->Service,2)}}</div></td>
       </tr>
@endforeach
  
 
@for($i=count($invoice_detail); $i<$row; $i++)
  <tr>
    <td>.</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
   </tr>
@endfor
 <tr>
    <td><div align="center"><strong>TOTAL</strong></div></td>
    <td></td>
    <td></td>
    <td></td>
    <td><div align="right"><strong>{{number_format($invoice_detail_summary[0]->Total,2)}}</strong></div></td>
    <td><div align="right"><strong>{{number_format($invoice_detail_summary[0]->Fare,2)}}</strong></div></td>
    <td><div align="right"><strong>{{number_format($invoice_detail_summary[0]->Service,2)}}</strong></div></td>
   </tr>
  </table></td>
      <td valign="top"><table width="100%" border="1" cellpadding="3" cellspacing="0"  style="border-collapse:collapse;">
    <tr>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>VHNO</strong></div></td>
      <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong>DESCRIPTION</strong></div></td>
   
        <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>RECEIPT </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="center"><strong>PAYMENT </strong></div></td>
    </tr>
   @foreach ($journal as $key => $value)
   	
    
    <tr>
      <td><div align="center">{{$value->VHNO}}</div></td>
      
      <td>{{$value->Narration}}</td>
      <td><div align="right">{{number_format($value->Dr,2)}}</div></td>
      <td><div align="right">{{number_format($value->Cr,2)}}</div></td>
    </tr>
@endforeach


@for($i=count($journal); $i<$row; $i++)
  <tr>
    <td>.</td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
@endfor
  <tr>
    <td><div align="center"><strong>TOTAL</strong></div></td>
    <td></td>
    <td><div align="right"><strong>{{number_format($journal_summary[0]->Dr,2)}}</strong></div></td>
    <td><div align="right"><strong>{{number_format($journal_summary[0]->Cr,2)}}</strong></div></td>
  </table></td>
    </tr>
  </table>
  
</div>
</body>
</html>