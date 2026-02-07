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
                                          
                                  From {{request()->StartDate}} TO {{request()->EndDate}}

                                </div>
                            </div>
                        </div>
<div class="row">
  <div class="col-12">
  
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
           @if(count($trial)>0)    
          <table width="100%" class="table table-sm table-bordered  table-striped align-middle table-nowrap mb-0">
          <tbody>
		  <tr>
          <th width="25%" class="col-md-2 text-center">HEAD</th>
          <th width="50%" class="col-md-5 text-center" >DESCRIPTION</th>
          <th width="23%" class="col-md-2 text-center">DEBIT</th>
          <th width="21%" class="col-md-2 text-center">CREDIT</th>
           </tr>
          </tbody>
          <tbody>
            
          @foreach ($trial as $key =>$value)

          <?php 

          if(!isset($DrTotal)) { 

             
             $DrTotal = $value->Debit;
             $CrTotal = $value->Credit;
             


}
else
{
   $DrTotal = $DrTotal+$value->Debit;
    $CrTotal = $CrTotal+$value->Credit;
 }


 ?>
           <tr>
           
           <td class="text-center">{{$value->ChartOfAccountID}}</td>
           <td class="text-center"><div align="left">{{$value->ChartOfAccountName}}</div></td>
           <td class="text-center"><div align="right">{{number_format($value->Debit,2)}}</div></td>
           <td class="text-center"><div align="right">{{number_format(abs($value->Credit),2)}}</div></td>
           </tr>
           @endforeach   
          <tr  class="table-active">
              
           <td></td>
            <td>TOTAL</td>
            <td class="text-end fw-bolder"><div align="right">{{number_format($DrTotal,2)}}</div></td>
           <td class="text-end fw-bolder"><div align="right">{{number_format(abs($CrTotal),2)}}</div></td>
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
<!-- BEGIN: Vendor JS-->
    <script src="{{asset('assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->
  @endsection