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
                        <h4 class="mb-sm-0 font-size-18 pt-3">Itemwise Sale </h4>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <form action="{{URL('/ItemWiseSale2')}}" method="post" name="form1" id="form1" class="form-inline w-100 d-flex align-items-center">
                        @csrf
                        
                        @php
                        $branches = \App\Models\Branch::getBranchList();
                    @endphp
                    
            
                        <div class="col-md-3">
                            <div class="form-group mx-2 ">
                              <select name="BranchID" id="" class="form-select" id="select2-basic" required="">
                                @if(session('UserType') == 'SuperAdmin')
                                <option value="0">All Branches</option>
                                @endif
                              <?php foreach ($branches as $key => $value): ?>
                              <option value="{{$value->id}}" {{($value->id== request()->BranchID) ? 'selected=selected':'' }}
                              >{{$value->id}}-{{$value->name}}</option>
        
                              <?php endforeach ?>
                            </select>
         
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group mx-2 ">
                                <input type="date" class="form-control" id="StartDate" name="StartDate" value="{{ request()->StartDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
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
      <td colspan="2"><div align="center" class="style1"> </div></td>
    </tr>
  
    <tr>
      <td width="50%">From {{dateformatman2(request()->StartDate)}} - {{dateformatman2(request()->EndDate)}}</td>
    <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
  <table class="table table-bordered table-striped  table-sm">
    <tr class="bg-light">
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>S.NO</strong></div></td>
      <td width="30%" bgcolor="#CCCCCC"><div align="center"><strong>ITEM NAME</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>NO OF SALES</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>TOTAL INVOICE</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>PROFIT</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>Percentage</strong></div></td>
           </tr>
 
<?php   

$total=0;
$profit=0;
$invoice=0;

 ?>

@foreach ($today_sale as $key => $value)
     

     <?php  

      $total +=$value->Total;
      $profit +=$value->Profit;
      $invoice +=$value->Invoice;

      ?>

    <tr>
      <td><div align="center">{{$key+1}}.</div></td>
      <td>  <a href="{{URL('/InvoiceDetailList').'/'.$value->ItemID.'/'.request()->StartDate.'/'.request()->EndDate}}" target="_blank">{{$value->ItemName}}</a></td>
      <td align="center">{{$value->Total}}</td>
      <td align="center">{{number_format($value->Invoice,2)}}</td>
      <td><div align="center">{{ $value->Profit > 0 ? number_format($value->Profit, 2) : '' }}</div></td>
      <td><div align="center">{{ $value->Percentage > 0 ? number_format($value->Percentage*100, 2) : '' }}</div></td>
      
    </tr>
@endforeach

<tr style="font-weight: bolder;">
  <td colspan="2" align="center" ><strong>Grand Total</strong></td>
  <td align="center">{{number_format($total)}}</td>
  <td align="center">{{number_format($invoice,2)}}</td>
  <td align="center">{{number_format($profit,2)}}</td>
 
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