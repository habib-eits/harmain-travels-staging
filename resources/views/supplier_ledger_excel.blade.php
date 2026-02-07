<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Party List</title>
     
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style></head>
<body>
<?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>

 
<p>@if(count($journal)>0) </p>
<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
 
	<tr>
      <th class="col-md-1 text-center">DATE</th>
      <th class="col-md-1 text-center" >VHNO</th>
      <th class="col-md-1 text-center">Type</th>
      <th class="col-md-5 text-center">Description</th>
      <th class="col-md-1 text-center"><div align="right">DR</div></th>
      <th class="col-md-1 text-center"><div align="right">CR</div></th>
      <th class="col-md-1 text-center"><div align="right">Balance</div></th>
  </tr>
    
 
      <tr>
    <td></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td>Opending Balance</td>
      <td><div align="right"></div></td>
      <td><div align="right"></div></td>
      <td class="text-danger text-end"><div align="right">{{$sql[0]->Balance}}</div></td>
	  </tr>
      @foreach ($journal as $key =>$value)
<tr>
      <td class="text-center">{{dateformatman($value->Date)}}</td>
      <td class="text-center"><div align="center">{{$value->VHNO}}</div></td>
      <td class="text-center"><div align="center">{{$value->JournalType}}</div></td>
      <td >{{$value->Narration}}</td>
      <td class="text-end"><div> 
        <div align="right">{{($value->Dr==0) ? '' : number_format($value->Dr,2)}}</div>
      </div></td>
      <td class="text-end"><div> 
        <div align="right">{{($value->Cr==0) ? '' : number_format($value->Cr,2)}}</div>
      </div></td>
       <td class="text-end">
               

         <div align="right">
           <?php 

if(!isset($balance)) { 

             $balance  =  $sql[0]->Balance + ($value->Dr-$value->Cr);
             $DrTotal = $DrTotal+$value->Dr;
             $CrTotal = $CrTotal+$value->Cr;
             echo $balance;


}
else
{
  $balance = $balance + ($value->Dr-$value->Cr);
  $DrTotal = $DrTotal+$value->Dr;
             $CrTotal = $CrTotal+$value->Cr;
  echo $balance;
}
              ?>
    {{($balance>0) ? "DR" : "CR"}} </div></td>
  </tr>
    @endforeach   
   <tr  class="table-active">
              
     <td></td>
     <td></td>
     <td><div align="center">TOTAL</div></td>
     <td class="text-end"></td>
     <td class="text-end fw-bolder"><div align="right">{{number_format($DrTotal,2)}}</div></td>
     <td class="text-end fw-bolder"><div align="right">{{number_format($CrTotal,2)}}</div></td>
            
     <td class="text-end fw-bolder"> </td>
    </tr>
     
</table>
  @else
           <p class=" text-danger">No data found</p>
         @endif 
<p>&nbsp;</p>
</body>
</html>