@extends('template.tmp')

@section('title', 'Party Yearly Report')
 

@section('content')



<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        
 @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

 @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger p-1   border-3">
                   <p class="font-weight-bold"> There were some problems with your input.</p>
                    <ul>
                        
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>

                        @endforeach
                    </ul>
                </div>
                </div>
 
            @endif

            
             
  <div class="card overflow-auto">
      <div class="card-body">
         <table width="200%" class="table table-striped">
  <tr>
    <td colspan="2"><div align="center" class="style1">
      <h4>VENDOR BALANCE </h4>
    </div></td>
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

<table class="table table-striped" >
  <tr>
    <td style="width:23%;"><strong>Description</strong></td>
    <td style="width:8%;"><strong>Opening Balance </strong></td>
   <?php  while (strtotime($start_date) <= strtotime($end_date)) { ?>

    <td><div align="center">
      <?php  echo date("M-Y",strtotime($start_date)); ?>
    </div></td>
    <?php $start_date = date ("Y-m-d", strtotime("+1 month", strtotime($start_date)));     } ?>
    <td>Total</td>
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

    <td><div align="center">
      {{ (count($opening_bal)>0) ? $opening_bal[0]->Balance : 0    }} <?php 

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
    <td>{{$grand+$opening}}</td>
  </tr>
  @endforeach
</table>      
      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
  @endsection