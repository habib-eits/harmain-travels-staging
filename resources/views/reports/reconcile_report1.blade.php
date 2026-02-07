@extends('tmp')
@section('title', $pagetitle)
@section('content')

@if (session('error'))
    <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
        {{ Session::get('error') }}  
    </div>
@endif

@if (count($errors) > 0)
    <div>
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
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">General Ledger</h4>
                            <strong class="text-end"></strong>
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
                        <div>
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
                        <div class="row mt-1 mb-2" >
                                        <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                                            <button class="btn btn-primary ReconcileUpdateYes" type="button">YES</button>
                                            <button class="btn btn-warning ReconcileUpdateNo" type="button">No</button>
                                        </div>

                                        <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                                            <div id="result"></div>

                                        </div>
                                        <br>

                                    </div>   
                      <table class="table table-sm table-bordered  table-striped align-middle  mb-0">
                      <tbody><tr>
                    <th><input id="check_all" type="checkbox" /></th>
                      <th class="col-md-1 text-center">DATE</th>
                      <th class="col-md-1 text-center" >VHNO</th>
                      <th class="col-md-2 text-center">AC</th>
                      <th class="col-md-3 text-center">Description</th>
                      <th class="col-md-1 text-center">DEBIT</th>
                      <th class="col-md-1 text-center">CREDIT</th>
                      <th class="col-md-1 text-center">Balance</th>
                       <th class="col-md-1 text-center">RECONCILE</th>
                       <th class="col-md-1 text-center">WORKING</th>
                       </tr>
                      </tbody>
                      <tbody>
                        <td></td>
                        <tr></tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>By Balance Brought Forward</td>
                        <td></td>
                        <td></td>
                        <td class="text-danger text-end">{{$sql[0]->Balance}}</td>
                        <td></td>
                        <td></td>
                      @foreach ($journal as $key =>$value)
                       <tr class="{{($value->BankReconcile=='YES') ? '' : 'table-danger'}}">
                       <td>
            <input class="case" type="checkbox" id="BankReconcile" value="{{$value->JournalID}}" name="BankReconcile[]"> </td>
                       <td class="text-center">{{dateformatman($value->Date)}}</td>
                       <td class="text-center">{{$value->VHNO}}</td>
                       <td class="text-center">{{$value->ChartOfAccountName}}</td>
                       <td >{{$value->Narration}}</td>
                       <td class="text-end"><div> {{($value->Dr==0) ? '' : number_format($value->Dr,2)}}</div></td>
                       <td class="text-end"><div> {{($value->Cr==0) ? '' : number_format($value->Cr,2)}}</div></td>
                          <td class="text-end">
                           

                           <?php 

            if(!isset($balance)) { 

                         $balance  =  $sql[0]->Balance + ($value->Dr-$value->Cr);
                         $DrTotal = $DrTotal+$value->Dr;
                         $CrTotal = $CrTotal+$value->Cr;
                         echo number_format(round($balance,2));


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
                        <td class="text-center">{{$value->BankReconcile}}</td>
                        <td class="text-center"><a  target="_blank" href="{{URL('/ReconcileUpdate/YES')}}/{{$value->JournalID}}">YES</a> /  <a target="_blank" href="{{URL('/ReconcileUpdate/NO')}}/{{$value->JournalID}}">NOT</a></td>
                       </tr>
                       @endforeach   
                      <tr  class="table-active">
                          
                       <td></td>
                       <td></td>
                       <td>TOTAL</td>
                        <td class="text-end"></td>
                       <td class="text-end fw-bolder">{{number_format($DrTotal,2)}}</td>
                       <td class="text-end fw-bolder">{{number_format($CrTotal,2)}}</td>
                        
                        <td class="text-end fw-bolder"> {{ number_format(round($balance,2))}} {{($balance>0) ? "DR" : "CR"}}</td>
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
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    //to check all checkboxes
$(document).on('change', '#check_all', function() {
    $('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

//updated the selected table rows
$(".ReconcileUpdateYes").on('click', function() {

    var ids=[];
    $('input[name="BankReconcile[]"]').each(function () {
    if(this.checked){
        var values=$(this).attr('value')
        ids.push(values);
    }
    });


    $.ajax({
            url: "{{URL('/ReconcileStatus')}}",
            type: "POST",
            dataType: "json",
            data: {
                "_token": "{{ csrf_token() }}",
                ids: ids,
                status: 'YES',
            },
          cache: false,
          success: function(data){
                Swal.fire({
                    icon: 'success',
                    title: 'RECONCILE Status Set to YES Successfully',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    } 
            });   
          }
      });

});

$(".ReconcileUpdateNo").on('click', function() {

    var ids=[];
    $('input[name="BankReconcile[]"]').each(function () {
    if(this.checked){
        var values=$(this).attr('value')
        ids.push(values);
    }
    });


    $.ajax({
            url: "{{URL('/ReconcileStatus')}}",
            type: "POST",
            dataType: "json",
            data: {
                "_token": "{{ csrf_token() }}",
                ids: ids,
                status: 'NO',
            },
          cache: false,
          success: function(data){
                Swal.fire({
                    icon: 'success',
                    title: 'RECONCILE Status Set to NO Successfully',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                         window.location.reload();
                    } 
                });
                     
          }
    });
});
</script>
<!-- BEGIN: Vendor JS-->
<script src="{{asset('assets/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->
@endsection