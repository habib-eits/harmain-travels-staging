@extends('tmp')

@section('title', $pagetitle)
 

@section('content')


<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">

          <div class="row">
  <div class="col-12">
  <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Cash Book</h4>
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
           @if(count($journal)>0)    
          <table class="table table-sm table-bordered  table-striped align-middle  mb-0">
          <tbody><tr>
          <th class="col-md-1 text-center">DATE</th>
          <th class="col-md-1 text-center" >VHNO</th>
          <th class="col-md-1 text-center" >SI/SR#</th>
          <th class="col-md-2 text-center">AC</th>
          <th class="col-md-3 text-center">Description</th>
          <th class="col-md-1 text-center">RECEIPTS</th>
          <th class="col-md-1 text-center">PAYMENTS</th>
          <th class="col-md-1 text-center">Balance</th>
          <th class="col-md-1 text-center">PARTY</th>
          <th class="col-md-1 text-center">SUPPLIER</th>
           </tr>
          </tbody>
          <tbody>
            <tr></tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-danger text-left fw-bolder">By Balance Brought Forward</td>
            <td></td>
            <td></td>
            <td class="text-danger text-end fw-bolder">{{number_format($sql[0]->Balance,2)}}</td>
            <td></td>
            <td></td>
          @foreach ($journal as $key =>$value)
           <tr>
           <td class="text-center">{{dateformatman($value->Date)}}</td>
           <td class="text-center"> <a href="{{URL('/VoucherView/'.$value->VoucherMstID)}}" target="_blank">{{$value->VHNO}}</a></td>
           <td class="text-center"> <a href="{{URL('/InvoicePDF/'.$value->InvoiceMasterID)}}" target="_blank" >{{$value->InvoiceMasterID}}</a> </td>
           <td class="text-left">{{$value->ChartOfAccountName}}</td>
           <td >{{$value->Narration}}</td>
           <td class="text-end"><div> {{($value->Dr==0) ? '' : number_format($value->Dr,2)}}</div></td>
           <td class="text-end"><div> {{($value->Cr==0) ? '' : number_format($value->Cr,2)}}</div></td>
              <td class="text-end">
               

               <?php 

if(!isset($balance)) { 

             $balance  =  $sql[0]->Balance + ($value->Dr-$value->Cr);
             $DrTotal = $DrTotal+$value->Dr;
             $CrTotal = $CrTotal+$value->Cr;
  echo number_format($balance,2);


}
else
{
  $balance = $balance + ($value->Dr-$value->Cr);
  $DrTotal = $DrTotal+$value->Dr;
             $CrTotal = $CrTotal+$value->Cr;
  echo number_format($balance,2);
}
              ?> 
{{($balance>0) ? "DR" : "CR"}}
             </td>
           <td class="text-center">{{$value->PartyID}}</td>
           <td class="text-center">{{$value->SupplierID}}</td>
           </tr>
           @endforeach   
          <tr  class="table-active">
              
           <td></td>
           <td></td>
           <td></td>
           <td>TOTAL</td>
            <td class="text-end"></td>
           <td class="text-end fw-bolder">{{number_format($DrTotal,2)}}</td>
           <td class="text-end fw-bolder">{{number_format($CrTotal,2)}}</td>
            
            <td class="text-end fw-bolder"> {{ number_format($balance)}} {{($balance>0) ? "DR" : "CR"}}</td>
            <td class="text-end"></td>
            <td class="text-end"></td>
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