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
          <div class="col-md-6"> <table class="table table-bordered table-sm">
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
            $Total=0;
           
             ?>
 


           @foreach($chartofaccountr as $value)



<?php 

 $activity = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance') )
            // ->whereBetween('Date',array(request()->StartDate,request()->EndDate))
            ->where('L2',$value->ChartOfAccountID)
             ->get(); 



 
if($activity[0]->Balance ==null)
{
  $activity[0]->Balance = 0;
} 
  



$Total = $Total +  $activity[0]->Balance;

  

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
                 {{$Total}}
               </td>
             </tr>
           </tbody>
         </table>   </div>
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
            $Total1=0;
           
             ?>
           @foreach($chartofaccounte as $value)



<?php 

 


 $activitye = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance') )
            // ->whereBetween('Date',array(request()->StartDate,request()->EndDate))
            ->where('L2',$value->ChartOfAccountID)
             ->get(); 



if($activitye[0]->Balance ==null)
{
  $activitye[0]->Balance = 0;
} 
  



$Total1 = $Total1 +  $activitye[0]->Balance;
 

  
 

  

 ?>


             <tr>
              <td>
                 {{$value->ChartOfAccountID}}
               </td>
               <td>
                 {{$value->ChartOfAccountName}}
               </td>
               <td>
                 {{($activitye[0]->Balance ==null) ? '0' :  $activitye[0]->Balance}}
               </td>
             </tr>
            
              
@endforeach

  <tr>
<td>
                 
               </td>
               <td>
                Total
               </td>
               <td>
                 {{$Total1}}
               </td>
             </tr>


              <tr class="bg-light">
<td>
                 
               </td>
               <td>
                <STRONG>PROFIT & LOSS</STRONG>
               </td>
               <td>
                 {{$Total-$Total1}}
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
      </div>
    </div>
    <!-- END: Content-->
 <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

 

 <script>
   $( document ).ready(function() {
    
  $('body').addClass('sidebar-enable vertical-collpsed')
 // $('body').removeClass('sidebar-enable vertical-collpsed')
setTimeout(function(){
          $("body").removeClass("sidebar-enable vertical-collpsed");
     },5000);
});
 </script>
 
  @endsection