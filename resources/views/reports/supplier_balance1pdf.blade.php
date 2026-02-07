<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Supplier Balance</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 14px;
	font-weight: bold;
}
.style2 {
	font-weight: bold;
	font-size: 16px;
}
-->
</style></head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 
  <tr>
    <td colspan="2"><div align="center" class="style1">VENDOR BALANCE </div></td>
  </tr>
  <tr>
    <td width="50%">From {{request()->StartDate}} to {{request()->EndDate}} </td>
    <td width="50%"><div align="right">Dated : {{date('d-m-Y')}}</div></td>
  </tr>
</table>
</p>
 




  <?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>

             

 
</table>
</p>
<?php 
  $start_date = request()->StartDate;
  $start_date1 = request()->StartDate;
    $end_date = request()->EndDate;

     ?>

  <table width="100%" border="1" cellspacing="0" cellpadding="3">
    <tr>
      <td width="6%" bgcolor="#CCCCCC"><div align="center"><strong>S.NO</strong></div></td>
       <td width="40%" bgcolor="#CCCCCC"><div align="left"><strong>NAME</strong></div></td>
       <td width="16%" bgcolor="#CCCCCC"><div align="right"><strong>DEBIT</strong></div></td>
      <td width="16%" bgcolor="#CCCCCC"><div align="right"><strong> CREDIT </strong></div></td>
      <td width="16%" bgcolor="#CCCCCC"><div align="right"><strong>BALANCE</strong></div></td>
    </tr>
   @foreach ($supplier as $key => $value)
    

<?php 


 
  

  $DrTotal=$DrTotal+$value->Dr;
  $CrTotal=$CrTotal+$value->Cr;
 



 ?>



    
    <tr>
      <td><div align="center">{{$key+1}}.</div></td>
      <td>{{$value->PartyName}}-{{$value->BranchName}}</td>
             <td><div align="right">{{number_format($value->Dr,2)}}</div></td>
      <td><div align="right">{{number_format($value->Cr,2)}}</div></td>
      <td><div align="right">{{number_format(($value->Dr)-$value->Cr,2)}}</div></td>
       
      </tr>
@endforeach
  
    <tr>
      <td></td>
       <td><strong>TOTAL</strong></td>
       <td align="right"><strong>{{number_format($DrTotal,2)}}</strong></td>
      <td align="right"><strong>{{number_format($CrTotal,2)}}</strong></td>
      
      
      <td align="right"><strong>{{number_format(($DrTotal)-($CrTotal),2)}}</strong></td>
    </tr>



  </table>

	
</body>
</html>