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
      <div class="col-md-6">
         <table class="table table-bordered table-sm">
           <thead>
             <tr class="bg-light">
               <td class="col-md-2">
                 Code
               </td>
               <td class="col-md-6">
                 Description
               </td>
               <td class="col-md-2">
                 Total
               </td>
             </tr>
           </thead>
           <tbody>

              <?php 
            $Totala=0;
           
             ?>
 


           @foreach($chartofaccounta as $value)



<?php 

 $activity = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance') )
             ->whereBetween('Date',array(request()->StartDate,request()->EndDate))
            ->where('L2',$value->ChartOfAccountID)
             ->get(); 



 
if($activity[0]->Balance ==null)
{
  $activity[0]->Balance = 0;
} 
  



$Totala = $Totala +  $activity[0]->Balance;

  

 ?>


             <tr>
              <td>
                 {{$value->ChartOfAccountID}}
               </td>
               <td>
                 {{$value->ChartOfAccountName}}
               </td>
               <td>
                 {{($activity[0]->Balance ==null) ? '0' :  $activity[0]->Balance}}
               </td>
             </tr>
              
              
@endforeach
<tr class="bg-light">
<td>
                 
               </td>
               <td>
                 TOTAL
               </td>
               <td>
                 {{$Totala}}
               </td>
             </tr>
           </tbody>
         </table>
      </div>
      <div class="col-md-6">
         <table class="table table-bordered table-sm">
           <thead>
             <tr class="bg-light">
               <td class="col-md-2">
                 Code
               </td>
               <td class="col-md-6">
                 Description
               </td>
               <td class="col-md-2">
                 Total
               </td>
             </tr>
           </thead>
           <tbody>

              <?php 
            $Totall=0;
           
             ?>
 


           @foreach($chartofaccountl as $value)



<?php 

 $activity = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance') )
             ->whereBetween('Date',array(request()->StartDate,request()->EndDate))
            ->where('L2',$value->ChartOfAccountID)
             ->get(); 



 
if($activity[0]->Balance ==null)
{
  $activity[0]->Balance = 0;
} 
  



$Totall = $Totall +  $activity[0]->Balance;

  

 ?>


             <tr>
              <td>
                 {{$value->ChartOfAccountID}}
               </td>
               <td>
                 {{$value->ChartOfAccountName}}
               </td>
               <td>
                 {{($activity[0]->Balance ==null) ? '0' :  $activity[0]->Balance}}
               </td>
             </tr>
              <tr>
              
@endforeach
<td>
                 
               </td>
               <td>
                 TOTAL
               </td>
               <td>
                 {{$Totall}}
               </td>
             </tr>
           </tbody>
         </table>     
 <table class="table table-bordered table-sm">
           <thead>
             <tr class="bg-light">
               <td class="col-md-2">
                 Code
               </td>
               <td class="col-md-6">
                 Description
               </td>
               <td class="col-md-2">
                 Total
               </td>
             </tr>
           </thead>
           <tbody>

              <?php 
            $Totalc=0;
           
             ?>
 


           @foreach($chartofaccountc as $value)



<?php 

 $activity = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance') )
             ->whereBetween('Date',array(request()->StartDate,request()->EndDate))
            ->where('L2',$value->ChartOfAccountID)
             ->get(); 



 
if($activity[0]->Balance ==null)
{
  $activity[0]->Balance = 0;
} 
  



$Totalc = $Totalc +  $activity[0]->Balance;

  

 ?>


             <tr>
              <td>
                 {{$value->ChartOfAccountID}}
               </td>
               <td>
                 {{$value->ChartOfAccountName}}
               </td>
               <td>
                 {{($activity[0]->Balance ==null) ? '0' :  $activity[0]->Balance}}
               </td>
             </tr>
              <tr>
              
@endforeach
<td>
                 
               </td>
               <td>
                 TOTAL
               </td>
               <td>
                 {{$Totalc}}
               </td>
             </tr>
           </tbody>
         </table>     


 <table class="table table-bordered table-sm">
  <thead>
             <tr class="bg-light">
               <td class="col-md-2">
                 Code
               </td>
               <td class="col-md-6">
                 Description
               </td>
               <td class="col-md-2">
                 Total
               </td>
             </tr>
           </thead>
           <tbody>
             <tr>
               <td></td>
               <td> <strong>Profit & Loss</strong></td>
               <td>{{$profit_loss}}</td>
             </tr>
           </tbody>    
</table>

 <table class="table table-bordered table-sm">
           <thead>
             <tr class="bg-light">
               <td class="col-md-2">
                 Code
               </td>
               <td class="col-md-6">
                 Description
               </td>
               <td class="col-md-2">
                 Total
               </td>
             </tr>
           </thead>
           <tbody>

              <?php 
            $Totals=0;
           
             ?>
 


           @foreach($chartofaccounts as $value)



<?php 

 $activity = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance') )
             ->whereBetween('Date',array(request()->StartDate,request()->EndDate))
            ->where('L2',$value->ChartOfAccountID)
             ->get(); 



 
if($activity[0]->Balance ==null)
{
  $activity[0]->Balance = 0;
} 
  



$Totals = $Totals +  $activity[0]->Balance;

  

 ?>


             <tr>
              <td>
                 {{$value->ChartOfAccountID}}
               </td>
               <td>
                 {{$value->ChartOfAccountName}}
               </td>
               <td>
                 {{($activity[0]->Balance ==null) ? '0' :  $activity[0]->Balance}}
               </td>
             </tr>
            
              
@endforeach
  <tr>
<td>
                 
               </td>
               <td>
                 TOTAL
               </td>
               <td>
                 {{$Totals}}
               </td>
             </tr>

               <tr>
<td>
                 
               </td>
               <td>
                 <strong>TOTAL</strong>
               </td>
               <td>
                 <STRONG>{{$Totall+$Totalc+$Totals+$profit_loss}}</STRONG>
               </td>
             </tr>



           </tbody>
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