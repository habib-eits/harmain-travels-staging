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
                                    <h4 class="mb-sm-0 font-size-18">Expense Detail</h4>
                                        <strong class="text-end"> {{date('M-Y')}} </strong> 
         

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

            
  <div class="card">
      <div class="card-body">
  

            <?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>      

 @if(count($expense)>0)    
<table class="table table-sm align-middle table-nowrap mb-0">
<tbody><tr>
<th class="col-md-1">S.No</th>
<th class="col-md-1">VHNO</th>
<th class="col-md-4">ACCOUNT</th>
<th class="col-md-1">DATE</th>
<th class="col-md-1">DR</th>
<th class="col-md-1">CR</th>
</tr>
</tbody>
<tbody>
@foreach ($expense as $key =>$value)

<?php 

$DrTotal  =$DrTotal+ $value->Dr;
$CrTotal  =$CrTotal+$value->Cr;
  
 ?>

 <tr>
 <td class="col-md-1">{{$key+1}}</td>
 <td class="col-md-1">{{$value->VHNO}}</td>
 <td class="col-md-1">{{$value->ChartOfAccountName}}</td>
 <td class="col-md-1">{{$value->Date}}</td>
 <td class="col-md-1">{{$value->Dr}}</td>
 <td class="col-md-1">{{$value->Cr}}</td>
 </tr>
 @endforeach   
 </tbody>

<tr style="background-color: #D9D9D9; font-weight: bolder;">
  <td></td>
  <td></td>
  <td></td>
  <td>TOTAL</td>
  <td>{{number_format($DrTotal,2)}}</td>
  <td>{{number_format($CrTotal,2)}}</td>
   
</tr>

<tr>
  <td></td>
  <td></td>
  <td></td>
  <td>Difference</td>
  <td>{{number_format($DrTotal-$CrTotal,2)}}</td>
  <td></td>
  
</tr>

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