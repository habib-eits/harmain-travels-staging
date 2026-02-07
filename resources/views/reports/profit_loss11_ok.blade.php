@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')



<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Profit & Loss</h4>
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
          <!--REVENUES START-->
          <div class="col-md-12"> 
          <table class="table table-bordered table-sm">
             
           <thead>
             

             <tr  class="bg-light">
               <td class="col-md-2">
                 
               </td>
               <td class="col-md-6 ">
                 <strong>REVENUES</strong>
               </td>
               <td class="col-md-2">
                  
               </td>
             </tr>
           </thead>
           <tbody>

              <?php 
            $Total=0;
           
             ?>

           @foreach($chartofaccountr as $value)
<?php 

$chartofaccount_l2 = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "R"  and L2 = "'.$value->ChartOfAccountID.'" and  ChartOfAccountID in (select ChartOfAccountID from v_journal )  ' );

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
             

 

  @foreach($chartofaccount_l2 as $value1) 

<?php 
$request = request(); // Assign it once

$activity = DB::table('v_journal')
    ->select(DB::raw('SUM(IF(ISNULL(Cr), 0, Cr)) - SUM(IF(ISNULL(Dr), 0, Dr)) AS Balance'))
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->where('ChartOfAccountID', $value1->ChartOfAccountID)
    ->when($request->BranchID > 0, function ($query) use ($request) {
        return $query->where('BranchID', $request->BranchID);
    })
    ->get();




 
if($activity[0]->Balance ==null)
{
  $activity[0]->Balance = 0;
} 
  


if($activity[0]->Balance != 0 ){
$Total = $Total +  $activity[0]->Balance; ?>


             <tr>
              <td>
                 {{$value1->ChartOfAccountID}}
               </td>
               <td style="text-indent: 20px;"> 
                 {{$value1->ChartOfAccountName}}
               </td>
               <td>
               <a  target="_blank" href="{{URL('/BalanceSheetDetail/'.$value1->ChartOfAccountID.'/'.request()->StartDate.'/'.request()->EndDate)}}">{{($activity[0]->Balance ==null) ? '0' :  number_format($activity[0]->Balance,2)}}</a>
               </td>
             </tr>
<?php } ?>             
@endforeach
 

              
@endforeach
             <tr>
            <td>
                 
               </td>
               <td> <STRONG> TOTAL {{(count($chartofaccountr)>0) ? $value->ChartOfAccountName : '0'}}</STRONG>
                 
               </td>
               <td>
                 {{$Total}}
               </td>
             </tr>
           </tbody>
           
          </table>   
       </div>
          <!--REVENUES END-->

          

          <!--EXPENSES START-->
          <div class="col-md-12">  
           <table class="table table-bordered table-sm">
           <thead>
             <tr  class="bg-light">
               <td class="col-md-2">
                 
               </td>
               <td class="col-md-6 ">
                 <strong>EXPENSES</strong>
               </td>
               <td class="col-md-2">
                  
               </td>
             </tr>
           </thead>
           <tbody>
            <?php 
            $Total1=0;
            ?>
            @foreach($chartofaccounte as $value)
              <?php 
              $chartofaccount_l2 = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "E"  and L2 = "'.$value->ChartOfAccountID.'" and  ChartOfAccountID in (select ChartOfAccountID from v_journal )  ' );
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
            $SubTotal=0;
            ?>

            @foreach($chartofaccount_l2 as $value1) 

            <?php 
            
            $request = request(); // Store the request once

$activitye = DB::table('v_journal')
    ->select(DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) AS Balance'))
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->where('ChartOfAccountID', $value1->ChartOfAccountID)
    ->when($request->BranchID > 0, function ($query) use ($request) {
        return $query->where('BranchID', $request->BranchID);
    })
    ->get();
 

              if($activitye[0]->Balance ==null)
              {
              $activitye[0]->Balance = 0;
              } 
              if($activitye[0]->Balance != 0 ){
              $SubTotal = $SubTotal +  $activitye[0]->Balance; 
              $Total1 = $Total1 +  $activitye[0]->Balance; ?>
              <tr>
                <td>
                 {{$value1->ChartOfAccountID}}
               </td>
                <td style="text-indent: 20px;"> 
                 {{$value1->ChartOfAccountName}}
               </td>
                <td>
                 <a  target="_blank" href="{{URL('/BalanceSheetDetail/'.$value1->ChartOfAccountID.'/'.request()->StartDate.'/'.request()->EndDate)}}">{{($activitye[0]->Balance ==null) ? '0' :  number_format($activitye[0]->Balance,2)}}</a>
               </td>
              </tr>
            <?php } ?>
             
@endforeach
 
<tr>
  <td></td>
  <td style="text-indent: 20px;" ><strong>Total {{$value->ChartOfAccountName}}</strong></td>
  <td>{{$SubTotal}}</td>
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
                 TOTAL
               </td>
               <td>
                 {{$Total1}}
               </td>
             </tr>

              <tr>
            <td>
                 
               </td>
               <td>
                 PROFIT & LOSS
               </td>
               <td>
                 {{$Total-$Total1}}
               </td>
             </tr>
           </tbody>
         </table>
          </div>
          <!--EXPENSES END-->
          
        </div>  
      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

 

 <script>
   $( document ).ready(function() {
    
  $('body').addClass('sidebar-enable vertical-collpsed')
 // $('body').removeClass('sidebar-enable vertical-collpsed')


// setTimeout(function(){
//           $("body").removeClass("sidebar-enable vertical-collpsed");
//      },5000);
});
 </script>
 
  @endsection