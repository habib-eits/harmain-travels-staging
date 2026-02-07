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

    <?php 

$company = DB::table('company')->first();

     ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
	
<div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1">{{$company->Name}} </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>GENERAL LEDGER </strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} to {{request()->EndDate}}</td>
	  <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>

   <?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>

             
  @if(count($journal)>0)    
          <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
          <tbody><tr bgcolor="#CCCCCC">
          <th class="col-md-1 text-center">DATE</th>
          <th class="col-md-1 text-center" >VHNO</th>
          <th class="col-md-2 text-center">AC</th>
          <th class="col-md-4 text-center">Description</th>
          <th class="col-md-1 text-center">DEBIT</th>
          <th class="col-md-1 text-center">CREDIT</th>
          <th class="col-md-1 text-center">Balance</th>
          <th class="col-md-1 text-center">PARTY</th>
          <th class="col-md-1 text-center">SUPPLIER</th> 
           </tr>
          </tbody>
          <tbody>
            <tr> 
            <td></td>
            <td></td>
            <td></td>
            <td>By Balance Brought Forward</td>
            <td><div align="right"></div></td>
            <td><div align="right"></div></td>
            <td class="text-danger text-end"><div align="right">{{$sql[0]->Balance}}</div></td>
            <td></td>
            <td></td>
          @foreach ($journal as $key =>$value)
           <tr>
           <td class="text-center">{{dateformatman($value->Date)}}</td>
           <td class="text-center">{{$value->VHNO}}</td>
           <td class="text-center">{{$value->ChartOfAccountName}}</td>
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
             echo number_format($balance,2);


}
else
{
  $balance = $balance + ($value->Dr-$value->Cr);
  $DrTotal = $DrTotal+$value->Dr;
             $CrTotal = $CrTotal+$value->Cr;
   echo number_format($balance,2);
}
              ?>
             {{($balance>0) ? "DR" : "CR"}} </div></td>
           <td class="text-center"><div align="center">{{$value->PartyID}}</div></td>
           <td class="text-center"><div align="center">{{$value->SupplierID}}</div></td>
           </tr>
           @endforeach   
          <tr  class="table-active">
              
           <td></td>
           <td></td>
           <td bgcolor="#CCCCCC"><strong>TOTAL</strong></td>
            <td bgcolor="#CCCCCC" class="text-end"></td>
           <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right"><strong>{{number_format($DrTotal,2)}}</strong></div></td>
           <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right"><strong>{{number_format($CrTotal,2)}}</strong></div></td>
            
            <td bgcolor="#CCCCCC" class="text-end fw-bolder"> <div align="right"><strong>{{ number_format($balance)}} {{($balance>0) ? "DR" : "CR"}}</strong></div></td>
            <td class="text-end"></td>
            <td class="text-end"></td>
          </tr>
           </tbody>
  </table>
           @else
             <p class=" text-danger">No data found</p>
           @endif 
</div>
</body>
</html>