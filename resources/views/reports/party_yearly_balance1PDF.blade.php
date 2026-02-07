
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
.style1 {font-size: 19px}
-->
</style></head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2"><div align="center" class="style1">VENDOR BALANCE </div></td>
  </tr>
  <tr>
    <td width="50%">From {{request()->StartDate}} TO {{request()->EndDate}} </td>
    <td width="50%"><div align="right">Dated : {{date('d-m-Y')}}</div></td>
  </tr>
</table>
</p>
<?php 
  $start_date = request()->StartDate;
  $start_date1 = request()->StartDate;
    $end_date = request()->EndDate;

     ?>

<table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-collapse:collapse;">
  <tr>
    <td><strong>Description</strong></td>
    <td style="width:8%;"><strong>Opening Balance </strong></td>
   <?php  while (strtotime($start_date) <= strtotime($end_date)) { ?>

    <td><div align="center">
      <?php  echo date("M-Y",strtotime($start_date)); ?>
    </div></td>
    <?php $start_date = date ("Y-m-d", strtotime("+1 month", strtotime($start_date)));     } ?>
    <td><div align="right">Total</div></td>
  </tr>
 @foreach($party as $value)
 <?php  
$grand=0;

 $start_date1 = request()->StartDate; 

 $sql = DB::table('journal')
            ->select( DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
            ->where('PartyID',$value->PartyID)
            ->where('ChartOfAccountID',110400)
              ->where('Date','<',request()->StartDate)
            // ->whereBetween('date',array($request->StartDate,$request->EndDate))

               ->get();
if(count($sql)>0){
  $opening= $sql[0]->Balance;
}
else
{
   $opening=0;
}
 
 ?>
  <tr>
    <td>{{$value->PartyName}}</td>
    <td><div align="right">{{($sql[0]->Balance==null) ? $sql[0]->Balance=0 : number_format($sql[0]->Balance,2)}}</div></td>
     <?php  while (strtotime($start_date1) <= strtotime($end_date)) { 

 


      ?>


     	<?php 

     	// start of nested loop for checking balance
$date= date("M-Y",strtotime($start_date1));
$opening_bal = DB::table('v_party_montly_balance')->where('PartyID',$value->PartyID)->where('Date',$date)->get();

 if(count($opening_bal)>0){
  $monthly= $opening_bal[0]->Balance;
}
else
{
   $monthly=0;
}
 

     	 ?>

    <td><div align="right">
      {{ (count($opening_bal)>0) ? number_format($opening_bal[0]->Balance,2) : 0    }} <?php 

      if(!isset($grand))
{
$grand =  $monthly;
 }
else
{
$grand = $grand + $monthly;
 }
  ?>
    </div></td>
    <?php $start_date1 = date ("Y-m-d", strtotime("+1 month", strtotime($start_date1)));     }


 
     ?>
    <td><div align="right">{{number_format($grand+$opening,2)}}</div></td>
  </tr>
  @endforeach
</table>
	
</body>
</html>