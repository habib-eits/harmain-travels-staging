@extends('template.tmp')

@section('title', 'Balance Sheet')
 

@section('content')



<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Balance Sheet</h4>
                                        <strong class="text-end"></strong> 
        From {{request()->StartDate}} TO {{request()->EndDate}}

                                </div>
                            </div>
                        </div>
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

            
            <?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>
  

 
<div class="card">
  <div class="card-body">
    <div class="row">
     

<!-- start of asset row -->

      <div class="col-md-12">
          <table class="table table-bordered table-sm">
           <thead>
             

             <tr  class="bg-light">
               <td class="col-md-1">
                 
               </td>
               <td class="col-md-10 ">
                 <strong>ASSETS</strong>
               </td>
               <td class="col-md-1">
                  
               </td>
             </tr>
           </thead>
           <tbody>

              <?php 
            $Totala=0;
           
             ?>

           @foreach($chartofaccounta as $value)
<?php 

$chartofaccount_l2 = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "A"  and L2 = "'.$value->ChartOfAccountID.'" and  ChartOfAccountID in (select ChartOfAccountID from v_journal )  ' );

 ?>

             <tr>
              <td>
                 {{$value->ChartOfAccountID}}
               </td>
               <td>
                 {{$value->ChartOfAccountName}}
               </td>
               <td>
                 
               </td>
             </tr>
             

 <?php 

$SubTotala=0;
  

  ?>

  @foreach($chartofaccount_l2 as $value1) 

<?php 
$query = DB::table('v_journal')
    ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'))
    ->whereBetween('Date', [request()->StartDate, request()->EndDate])
    ->where('ChartOfAccountID', $value1->ChartOfAccountID);

    if (request()->BranchID > 0) {
    $query->where('BranchID', request()->BranchID);
}

$activitya = $query->get();




 
if($activitya[0]->Balance ==null)
{
  $activitya[0]->Balance = 0;
} 
  

if($activitya[0]->Balance != 0 ){

$SubTotala = $SubTotala +  $activitya[0]->Balance; 
$Totala = $Totala +  $activitya[0]->Balance; ?>


             <tr>
              <td>
                 {{$value1->ChartOfAccountID}}
               </td>
               <td style="text-indent: 20px;"> 
                 {{$value1->ChartOfAccountName}}
               </td>
               <td align="right">
                 <a  target="_blank" href="{{URL('/BalanceSheetDetail/'.$value1->ChartOfAccountID.'/'.request()->StartDate.'/'.request()->EndDate)}}">{{($activitya[0]->Balance ==null) ? '0' :  number_format($activitya[0]->Balance,2)}}</a>
               </td>
             </tr>
<?php } ?>               
@endforeach
 
<tr>
  <td></td>
  <td style="text-indent: 20px;" align="" ><strong>Total {{$value->ChartOfAccountName}}</strong></td>
  <td align="right">{{number_format($SubTotala,2)}}</td>
</tr>

<tr style="height: 30px;">
  <td></td>
  <td></td>
  <td></td>
</tr>
              
@endforeach
             <tr>
            <td>
                 
               </td>
               <td align="left">
                 <strong>TOTAL</strong>
               </td>
               <td align="right">
                 {{number_format($Totala,2)}}
               </td>
             </tr>
 
           </tbody>
         </table>  
      </div>
      

<!-- enf of assets row -->

 
<!-- start of Liablity row -->

      <div class="col-md-12">
          <table class="table table-bordered table-sm">
           <thead>
             

             <tr  class="bg-light">
               <td class="col-md-1">
                 
               </td>
               <td class="col-md-10 ">
                 <strong>LIABILITY</strong>
               </td>
               <td class="col-md-1">
                  
               </td>
             </tr>
           </thead>
           <tbody>

              <?php 
            $Totall=0;
           
             ?>

           @foreach($chartofaccountl as $value)
<?php 

$chartofaccount_l2 = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "L"  and L2 = "'.$value->ChartOfAccountID.'" and  ChartOfAccountID in (select ChartOfAccountID from v_journal )  ' );

 ?>

             <tr>
              <td>
                 {{$value->ChartOfAccountID}}
               </td>
               <td>
                 {{$value->ChartOfAccountName}}
               </td>
               <td>
                 
               </td>
             </tr>
             

 <?php 

$SubTotall=0;
  

  ?>

  @foreach($chartofaccount_l2 as $value1) 

<?php 
$query = DB::table('v_journal')
    ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'))
    ->whereBetween('Date', [request()->StartDate, request()->EndDate])
    ->where('ChartOfAccountID', $value1->ChartOfAccountID);

if (request()->BranchID > 0) {
    $query->where('BranchID', request()->BranchID);
}

$activityl = $query->get();
 



 
if($activityl[0]->Balance ==null)
{
  $activityl[0]->Balance = 0;
} 
  
if($activityl[0]->Balance != 0 ){


$SubTotall = $SubTotall +  $activityl[0]->Balance; 
$Totall = $Totall +  $activityl[0]->Balance; ?>


             <tr>
              <td>
                 {{$value1->ChartOfAccountID}}
               </td>
               <td style="text-indent: 20px;"> 
                 {{$value1->ChartOfAccountName}}
               </td>
               <td align="right">
                 <a  target="_blank" href="{{URL('/BalanceSheetDetail/'.$value1->ChartOfAccountID.'/'.request()->StartDate.'/'.request()->EndDate)}}">{{($activityl[0]->Balance ==null) ? '0' :  number_format($activityl[0]->Balance,2)}}</a>
               </td>
             </tr>
   <?php } ?>          
@endforeach
 
<tr>
  <td></td>
  <td style="text-indent: 20px;" ><strong>Total {{$value->ChartOfAccountName}}</strong></td>
  <td align="right">{{$SubTotall}}</td>
</tr>

<tr style="height: 30px;">
  <td></td>
  <td></td>
  <td></td>
</tr>
              
@endforeach
             <tr>
            <td>
                 
               </td>
               <td>
                 <strong>TOTAL</strong>
               </td>
               <td align="right">
                 {{$Totall}}
               </td>
             </tr>
 
           </tbody>
         </table>  
      </div>
      

<!-- enf of Liability row -->


 
 
<!-- start of capital row -->

      <div class="col-md-12">
          <table class="table table-bordered table-sm">
           <thead>
             

             <tr  class="bg-light">
               <td class="col-md-1">
                 
               </td>
               <td class="col-md-10 ">
                 <strong>CAPITAL</strong>
               </td>
               <td class="col-md-1">
                  
               </td>
             </tr>
           </thead>
           <tbody>

              <?php 
            $Totalc=0;
           
             ?>

           @foreach($chartofaccountc as $value)
<?php 

$chartofaccount_l2 = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "C"  and L2 = "'.$value->ChartOfAccountID.'"   ' );

 ?>

             <tr>
              <td>
                 {{$value->ChartOfAccountID}}
               </td>
               <td>
                 {{$value->ChartOfAccountName}}
               </td>
               <td>
                 
               </td>
             </tr>
             

 <?php 

$SubTotalc=0;
  

  ?>

  @foreach($chartofaccount_l2 as $value1) 

<?php 
$activityc = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance') )
            ->whereBetween('Date',array(request()->StartDate,request()->EndDate))
            ->where('ChartOfAccountID',$value1->ChartOfAccountID)
            // ->groupby('ChartOfAccountID','ChartOfAccountName')
             ->get(); 



 
if($activityc[0]->Balance ==null)
{
  $activityc[0]->Balance = 0;
} 
  

if($activityc[0]->Balance != 0 ){

$SubTotalc = $SubTotalc +  $activityc[0]->Balance; 
$Totalc = $Totalc +  $activityc[0]->Balance; ?>


             <tr>
              <td>
                 {{$value1->ChartOfAccountID}}
               </td>
               <td style="text-indent: 20px;"> 
                 {{$value1->ChartOfAccountName}}
               </td>
               <td align="right">
                 <a  target="_blank" href="{{URL('/BalanceSheetDetail/'.$value1->ChartOfAccountID.'/'.request()->StartDate.'/'.request()->EndDate)}}">{{($activityc[0]->Balance ==null) ? '0' :  number_format($activityc[0]->Balance,2)}}</a>
               </td>
             </tr>
  <?php } ?>           
@endforeach
 
<tr>
  <td></td>
  <td style="text-indent: 20px;" ><strong>Total {{$value->ChartOfAccountName}}</strong></td>
  <td align="right">{{number_format($SubTotalc,2)}}</td>
</tr>

<tr style="height: 30px;">
  <td></td>
  <td></td>
  <td></td>
</tr>
              
@endforeach
             <tr>
            <td>
                 
               </td>
               <td>
                 <strong>TOTAL</strong>
               </td>
               <td align="right">
                 {{number_format($Totalc,2)}}
               </td>
             </tr>
 
           </tbody>
         </table>  
      </div>
      

<!-- enf of capital row -->

 
 
<!-- start of suspense row -->

      <div class="col-md-12">
          <table class="table table-bordered table-sm">
           <thead>
             

             <tr  class="bg-light">
               <td class="col-md-1">
                 
               </td>
               <td class="col-md-10 ">
                 <strong>SUSPENSE</strong>
               </td>
               <td class="col-md-1">
                  
               </td>
             </tr>
           </thead>
           <tbody>

              <?php 
            $Totals=0;
           
             ?>

           @foreach($chartofaccounts as $value)
<?php 

$chartofaccount_l2 = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "S"  and L2 = "'.$value->ChartOfAccountID.'"   ' );

 ?>

             <tr>
              <td>
                 {{$value->ChartOfAccountID}}
               </td>
               <td>
                 {{$value->ChartOfAccountName}}
               </td>
               <td>
                 
               </td>
             </tr>
             

 <?php 

$SubTotals=0;
  

  ?>

  @foreach($chartofaccount_l2 as $value1) 

<?php 
$activityc = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance') )
            ->whereBetween('Date',array(request()->StartDate,request()->EndDate))
            ->where('ChartOfAccountID',$value1->ChartOfAccountID)
            // ->groupby('ChartOfAccountID','ChartOfAccountName')
             ->get(); 



 
if($activityc[0]->Balance ==null)
{
  $activityc[0]->Balance = 0;
} 
  
if($activityc[0]->Balance != 0 ){


$SubTotals = $SubTotals +  $activityc[0]->Balance; 
$Totals = $Totals +  $activityc[0]->Balance; ?>


             <tr>
              <td>
                 {{$value1->ChartOfAccountID}}
               </td>
               <td style="text-indent: 20px;"> 
                 {{$value1->ChartOfAccountName}}
               </td>
               <td align="right">
                 {{($activityc[0]->Balance ==null) ? '0' :  number_format($activityc[0]->Balance,2)}}
               </td>
             </tr>
          <?php } ?>        
@endforeach
 
<tr>
  <td></td>
  <td style="text-indent: 20px;" ><strong>Total {{$value->ChartOfAccountName}}</strong></td>
  <td align="right">{{number_format($SubTotals,2)}}</td>
</tr>

<tr style="height: 30px;">
  <td></td>
  <td></td>
  <td></td>
</tr>
              
@endforeach
             <tr>
            <td>
                 
               </td>
               <td>
                 <strong>TOTAL</strong>
               </td>
               <td align="right">
                 {{number_format($Totals,2)}}
               </td>
             </tr>
 
           </tbody>
         </table>  
          <table class="table table-bordered table-sm">
           <thead>
             

             <tr  class="bg-light">
               <td class="col-md-1">
                 
               </td>
               <td class="col-md-10 ">
                 <strong>PROFT & LOSS</strong>
               </td>
               <td class="col-md-1">
                  
               </td>
             </tr>
           </thead>
           <tbody>

            <tr>
              <td></td>
              <td>TOTAL</td>
              <td align="right">{{number_format($profit_loss,2)}}</td>
            </tr>



             
            </tbody>
          </table>

  <table class="table table-bordered table-sm">
           <thead>
             

             <tr  class="bg-light">
               <td class="col-md-1">
                 
               </td>
               <td class="col-md-10 ">
                 <strong><strong>TOTAL OF LIABILITY, CAPITAL & SUSPENSE</strong></strong>
               </td>
               <td class="col-md-1" align="right">
                  <STRONG>{{number_format($Totall+$Totalc+$Totals+$profit_loss,2)}}</STRONG>
               </td>
             </tr>
           </thead>
           <tbody>

            



            
            </tbody>
          </table>


      </div>
      

<!-- enf of suspense row -->




    </div>
  </div>
</div>


  
  </div>
  

</div>

        </div>
     
    <!-- END: Content-->
 
  @endsection