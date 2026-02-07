@extends('tmp')

@section('title', $pagetitle)
 

@section('content')

 
 
 
  
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
<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">




    <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Trial Balance</h4>
                                      OPENING, NET ACTIVITY & CLOSING TRIAL.    <br>
                                       As Of  {{dateformatman(request()->StartDate)}} -  {{dateformatman(request()->EndDate)}}
                                  

                                </div>
                            </div>
                        </div>
 
            
  <div class="card">
      <div class="card-body">
           @if(count($chartofaccount)>0)    
          <table width="100%" class="table table-sm table-bordered  table-striped align-middle table-nowrap mb-0">
          <tbody>
		  <tr>
		    <th width="10%" rowspan="2" class="col-md-1 text-center">HEAD</th>
		    <th width="15%" rowspan="2" class="col-md-2 text-center" >DESCRIPTION</th>
		    <th colspan="2" class="col-md-1 text-center"><div align="center">OPENING TRIAL </div></th>
		    <th colspan="2" class="col-md-1 text-center"><div align="center">ACTIVITY TRIAL </div></th>
		    <th colspan="2" class="col-md-1 text-center"><div align="center">CLOSING TRIAL </div></th>
		    </tr>
		  <tr>
          <th width="10%" class="col-md-1 text-center">DEBIT</th>
          <th width="10%" class="col-md-1 text-center">CREDIT</th>
           <th width="10%" class="col-md-1 text-center">DEBIT</th>
          <th width="10%" class="col-md-1 text-center">CREDIT</th>
           <th width="10%" class="col-md-1 text-center">DEBIT</th>
          <th width="10%" class="col-md-1 text-center">CREDIT</th>
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
            <td>TOTAL</td>
            <td class="text-end fw-bolder"><div align="right"> {{number_format($OpeningDr,2)}}</div></td>
            <td class="text-end fw-bolder"><div align="right"> {{number_format($OpeningCr,2)}}</div></td>
            <td class="text-end fw-bolder"><div align="right"> {{number_format($ActivityCr,2)}}</div></td>
            <td class="text-end fw-bolder"><div align="right"> {{number_format($ActivityCr,2)}}</div></td>
            <td class="text-end fw-bolder"><div align="right"> {{number_format($OpeningDr+$ActivityDr,2)}}</div></td>
            <td class="text-end fw-bolder"><div align="right"> {{number_format($OpeningCr+$ActivityCr,2)}}</div></td>
            </tr>
           </tbody>
           </table>
           @else
             <p class=" text-danger">No data found</p>
           @endif   
      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
  @endsection