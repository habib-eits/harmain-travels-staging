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


    <?php 

$company = DB::table('company')->first();
 
     ?>
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
	
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
   @if(count($trial)>0)    
          <table width="100%" border="1" cellpadding="3" cellspacing="0" >
          <tbody>
      <tr bgcolor="#CCCCCC">
          <th width="25%" class="col-md-2 text-center">HEAD</th>
          <th width="50%" class="col-md-5 text-center" >DESCRIPTION</th>
          <th width="23%" class="col-md-2 text-center">DEBIT</th>
          <th width="21%" class="col-md-2 text-center">CREDIT</th>
           </tr>
          </tbody>
          <tbody>
            
          @foreach ($trial as $key =>$value)

          <?php 

          if(!isset($DrTotal)) { 

             
             $DrTotal = $value->Debit;
             $CrTotal = $value->Credit;
             


}
else
{
   $DrTotal = $DrTotal+$value->Debit;
    $CrTotal = $CrTotal+$value->Credit;
 }


 ?>
           <tr>
           
           <td class="text-center">{{$value->ChartOfAccountID}}</td>
           <td class="text-center"><div align="left">{{$value->ChartOfAccountName}}</div></td>
           <td class="text-center"><div align="right">{{number_format($value->Debit,2)}}</div></td>
           <td class="text-center"><div align="right">{{number_format(abs($value->Credit),2)}}</div></td>
           </tr>
           @endforeach   
          <tr  class="table-active">
              
           <td></td>
            <td bgcolor="#CCCCCC">TOTAL</td>
            <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right">{{number_format($DrTotal,2)}}</div></td>
           <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right">{{number_format(abs($CrTotal),2)}}</div></td>
           </tr>
           </tbody>
  </table>
           @else
             <p class=" text-danger">No data found</p>
           @endif 
  <p>&nbsp;</p>
</div>
</body>
</html>