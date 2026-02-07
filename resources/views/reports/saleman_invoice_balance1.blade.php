@extends('template.tmp')

@section('title', $pagetitle)
 

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

         <div class="row d-sm-flex align-items-center justify-content-between">
                <div class="col-md-6">
                    <div class="page-title-box">
                        <h4 class="mb-sm-0 font-size-18 pt-3">Saleman Invoice Balances </h4>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <form action="{{URL('/SalemanInvoiceBalance1')}}" method="post" name="form1" id="form1" class="form-inline w-100 d-flex align-items-center">
                        @csrf
                        
            
                        <div class="col-md-4">
                            <div class="form-group mx-2 ">
                                <input type="date" class="form-control" id="StartDate" name="StartDate" value="{{ request()->StartDate }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mx-2">
                                <input type="date" class="form-control" id="EndDate" name="EndDate" value="{{ request()->EndDate }}">
                            </div>
                        </div>
            
                        <div class="form-group d-flex">
                            <button type="submit" class="btn btn-success w-md" id="online">Submit</button>
                            
                        </div>
                    </form>
                </div>
            </div> 



          
  <div class="card">
      <div class="card-body">
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
    
    
    <tr>
      <td width="50%">From {{dateformatman2(request()->StartDate)}} to {{dateformatman2(request()->EndDate)}}</td>
    <td width="50%"><div align="right">DATED: {{date('d/m/Y')}}</div></td>
    
    </tr>
  </table>
  <br>
  <table class="table table-bordered table-stripped table-sm">
    <tr class="bg-light">
       <td width="2%" bgcolor="#CCCCCC"><div align="center"><strong>SNO</strong></div></td>
      <td width="40%" bgcolor="#CCCCCC"><div align="center"><strong>SALEMAN NAME</strong></div></td>
       <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>INVOICE </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>BALANCE </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="CENTER"><strong>DETAIL </strong></div></td>
    </tr>

    <?php 

 
    $Total=0;
    $Balance=0;
    


     ?>
   @foreach ($invoice_master as $key => $value)
    
<?php 

 
    $Total = $Total + $value->Total;
    $Balance= $Balance + $value->Balance;



 ?>

    
    <tr>
       <td>{{++$key}}</td>
       <td>{{$value->FullName}}</td>
       <td><div align="right">{{number_format($value->Total,2)}}</div></td>
      <td><div align="right">{{number_format($value->Balance,2)}}</div></td>
       <td align="CENTER"><a href="{{URL('/SalemanInvoiceList').'/'.$value->FullName.'/'.request()->StartDate.'/'.request()->EndDate}}" title="" target="_blank">MORE DETAIL</a></td>
    </tr>
@endforeach

  <tr style="font-weight: bolder;">
      
    </tr>

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