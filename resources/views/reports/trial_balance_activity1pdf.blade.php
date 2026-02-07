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
      <td colspan="2"><div align="center"><strong>OPENING, NET ACTIVITY &amp; CLOSING TRIAL.</strong></div></td>
    </tr>
    <tr>
      <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}}</td>
	  <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
   @if(count($chartofaccount)>0)    
          <table width="100%" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" >
          <tbody>
      <tr bgcolor="#CCCCCC">
        <th width="10%" rowspan="2" class="col-md-1 text-center">HEAD</th>
        <th width="15%" rowspan="2" class="col-md-2 text-center" >DESCRIPTION</th>
        <th colspan="2" bgcolor="#999999" class="col-md-1 text-center"><div align="center">OPENING TRIAL </div></th>
        <th colspan="2" bgcolor="#999999" class="col-md-1 text-center"><div align="center">ACTIVITY TRIAL </div></th>
        <th colspan="2" bgcolor="#999999" class="col-md-1 text-center"><div align="center">CLOSING TRIAL </div></th>
        </tr>
      <tr>
          <th width="10%" bgcolor="#CCCCCC" class="col-md-1 text-center">DEBIT</th>
          <th width="10%" bgcolor="#CCCCCC" class="col-md-1 text-center">CREDIT</th>
           <th width="10%" bgcolor="#CCCCCC" class="col-md-1 text-center">DEBIT</th>
          <th width="10%" bgcolor="#CCCCCC" class="col-md-1 text-center">CREDIT</th>
           <th width="10%" bgcolor="#CCCCCC" class="col-md-1 text-center">DEBIT</th>
          <th width="10%" bgcolor="#CCCCCC" class="col-md-1 text-center">CREDIT</th>
           </tr>
          </tbody>
          <tbody>
            

            <?php 
            $OpeningDr=0;
            $OpeningCr=0;
            $ActivityDr=0;
            $ActivityCr=0;
            $ClosingDr=0;
            $ClosingCr=0;
             ?>


          @foreach ($chartofaccount as $key =>$value)

          <?php 

         $opening = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr') )
            ->where('Date','<',request()->StartDate)
            ->where('ChartOfAccountID',$value->ChartOfAccountID)
            ->get(); 

      
            $activity = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr') )
            ->whereBetween('Date',array(request()->StartDate,request()->EndDate))
            ->where('ChartOfAccountID',$value->ChartOfAccountID)
            ->get(); 


             if(!isset($OpeningDr)) { 

             
             $OpeningDr = $opening[0]->Dr;
             $OpeningCr = $opening[0]->Cr;
             $ActivityDr = $activity[0]->Dr;
             $ActivityCr = $activity[0]->Cr;


            }
            else
            {
              $OpeningDr = $OpeningDr+$opening[0]->Dr;
             $OpeningCr = $OpeningCr+$opening[0]->Cr;
             $ActivityDr = $ActivityDr+$activity[0]->Dr;
             $ActivityCr = $ActivityCr+$activity[0]->Cr;
             }

           ?>

 



           <tr>
           
           <td class="text-center">{{$value->ChartOfAccountID}}</td>
           <td class="text-center"><div align="left">{{$value->ChartOfAccountName}}</div></td>
           <td class="text-center"><div align="right">{{number_format($opening[0]->Dr,2)}} </div></td>
           <td class="text-center"><div align="right">{{number_format($opening[0]->Cr,2)}} </div></td>
           <td class="text-center"><div align="right">{{number_format($activity[0]->Dr,2)}} </div></td>
           <td class="text-center"><div align="right">{{number_format($activity[0]->Cr,2)}} </div></td>
        <td class="text-center"><div align="right">{{number_format(($opening[0]->Dr+$activity[0]->Dr),2)}} </div></td>
        <td class="text-center"><div align="right">{{number_format(($opening[0]->Cr+$activity[0]->Cr),2)}} </div></td>
            
           </tr>
           @endforeach   
          <tr  class="table-active">
              
           <td></td>
            <td bgcolor="#CCCCCC">TOTAL</td>
            <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right"> {{number_format($OpeningDr,2)}}</div></td>
            <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right"> {{number_format($OpeningCr,2)}}</div></td>
            <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right"> {{number_format($ActivityCr,2)}}</div></td>
            <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right"> {{number_format($ActivityCr,2)}}</div></td>
            <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right"> {{number_format($OpeningDr+$ActivityDr,2)}}</div></td>
            <td bgcolor="#CCCCCC" class="text-end fw-bolder"><div align="right"> {{number_format($OpeningCr+$ActivityCr,2)}}</div></td>
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