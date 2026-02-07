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
      <td colspan="2"><div align="center"><strong> INVOICE SUMMARY SALEMAN WISE</strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
	  <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
   
  
  <table width="100%" border="1" style="border-collapse: collapse;">
  <thead style="display: table-header-group;">
   <tr>
     <th width="5%" bgcolor="#CCCCCC"><div align="center"><strong>SALEMAN #</strong></div></th>
      <th width="15%" bgcolor="#CCCCCC"><div align="center"><strong>SALEMAN NAME</strong></div></th>
      <th width="5%" bgcolor="#CCCCCC"><div align="center"><strong>QTY</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="center"><strong>GROSS</strong></div></th>
      <th width="10%" bgcolor="#CCCCCC"><div align="right"><strong>TAXES </strong></div></th>
       <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>PAYABLE </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>SERVICE </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>DIS/ </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>PROFIT </strong></div></th>
      <th width="9%" bgcolor="#CCCCCC"><div align="right"><strong>NET </strong></div></th>
      
   </tr>
  </thead>
  <tbody>
   @foreach ($invoice_summary as $key => $value)
    
    
   <tbody>
      <tr>
      
      <td><div align="center">{{$value->UserID}}</div></td>
      <td>{{$value->SalemanName}}</td>
      <td>{{$value->Qty}}</td>
      <td><div align="right">{{number_format($value->Total,0)}}</div></td>
     
      <td><div align="right">{{number_format($value->Taxable,0)}}</div></td>
     
      <td><div align="right">{{number_format($value->Total,0)}}</div></td>
     
      <td><div align="right">{{number_format($value->Service,0)}}</div></td>
     
      <td><div align="right">{{number_format($value->Discount,0)}}</div></td>
      <td><div align="right">{{number_format($value->Service,0)}}</div></td>
      
     <td><div align="right">{{number_format($value->Total,0)}}</div></td> 


    </tr>
   </tbody>
@endforeach
    <tr>
     
       <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong></strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong> </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>TOTAL  </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_total[0]->Fare,0)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_total[0]->Taxable,0)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_total[0]->Service,0)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_total[0]->Fare,0)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_total[0]->Discount,0)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_total[0]->Total,0)}} </strong></div></td>
      <td width="9%" bgcolor="#CCCCCC"><div align="right"><strong>{{number_format($invoice_total[0]->Profit,0)}} </strong></div></td>
       
      
      
   </tr>

    

   
  </tbody>
</table>
</div>
</body>
</html>