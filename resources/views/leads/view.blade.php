@extends('template.tmp')
@section('title', 'Lead View')
@section('content')
   <div class="main-content">

 <div class="page-content">
 <div class="container-fluid">

    <div class="content-wrapper">
        <div class="row" style="height: 81vh; overflow: auto;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-info">Lead Details</h3>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <a href="{{ url('leads') }}" class="btn btn-primary btn-rounded w-md">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        

                        <table class="table table-striped table-sm">
                            
                                <tr>
                                    <td>Customer/Lead Full Name: </td>
                                    <td>{{ $lead->name }}         </td>
                                </tr>
                            

                              <tr>
                                    <td>Contact / Email: </td>
                                    <td>{{ $lead->tel }}         </td>
                                </tr>


                                 <tr>
                                    <td>Other Number: </td>
                                    <td>{{ $lead->other_tel != null ? $lead->other_tel : 'N/A' }}         </td>
                                </tr>


                                   <tr>
                                    <td>Service: </td>
                                    <td>{{ $lead->service != null ? $lead->service : 'N/A' }}         </td>
                                </tr>
                            

                                <tr>
                                    <td>Channel: </td>
                                    <td>{{ $lead->channel != null ? $lead->channel : 'N/A' }}         </td>
                                </tr>
                            

                                 <tr>
                                    <td>Branch: </td>
                                    <td>{{ isset($lead->branch) ? $lead->branch->name : 'N/A' }}        </td>
                                </tr>
                            

                                <tr>
                                    <td>Service: </td>
                                    <td>{{ isset($lead->branchService) ? $lead->branchService->name : 'N/A' }}        </td>
                                </tr>
                            

                                <tr>
                                    <td>Agent: </td>
                                    <td>{{ isset($lead->agent) ? $lead->agent->name : 'N/A' }}      </td>
                                </tr>


                                     <tr>
                                    <td>Status: </td>
                                    <td>{{ $lead->status != null ? $lead->status : 'N/A' }}     </td>
                                </tr>
                            

                                    <tr>
                                    <td>Qualified Status: </td>
                                    <td>{{ $lead->approved_status != null ? $lead->approved_status : 'N/A' }}   </td>
                                </tr>
                            
                                  
                        </table>


                     
                       
                        
                         
                       
                            <hr class="mt-4">

  <div class="row mt-2">
                                <div class="col-12 text-left ">
                                    <strong>Notes/Remarks</strong>
                                </div>
                            </div>

 @if(count($leadDetails)>0)        
<table class="table table-sm align-middle table-nowrap mb-0">
<tbody><tr>
<th scope="col">S.No</th>
<th scope="col">Added By</th>
<th scope="col">Date Added</th>
<th scope="col">Follow Up Date</th>
<th scope="col">Status From</th>
<th scope="col">Status To</th>
<th scope="col">Note/Remarks</th>
</tr>
</tbody>
<tbody>
@foreach ($leadDetails as $key => $value)
 <tr>
 <td class="col-md-1">{{$key+1}}</td>
 <td class="col-md-1">{{ $value->date != null ? dmY($value->date) : 'N/A' }}</td>
 <td class="col-md-1">{{ $value->follow_up_date != null ? dmY($value->follow_up_date) : 'N/A' }}</td>
 <td class="col-md-1">{{ $value->status_from != null ? $value->status_from : 'N/A' }}</td>
 <td class="col-md-1">{{ $value->status_from != null ? $value->status_from : 'N/A' }}</td>
 <td class="col-md-1">{{ $value->status_to != null ? $value->status_to : 'N/A' }}</td>
 <td class="col-md-1">{{ $value->notes }}</td>
 </tr>
 @endforeach   
 </tbody>
 </table>
 @else
   <p class=" text-danger">No data found</p>
 @endif   


                          
                    </div>
                </div>


<h5>Leads Activity</h5>
<div class="card">
    <div class="card-body">
         @if(count($leadActivity)>0)        
<table class="table table-sm align-middle table-nowrap mb-0">
<tbody><tr>
<th scope="col">S.No</th>
<th scope="col">Date Added</th>
<th scope="col">Note/Remarks</th>


</tr>
</tbody>
<tbody>
@foreach ($leadActivity as $key => $value)
 <tr>
 <td class="col-md-1">{{$key+1}}</td>
 <td class="col-md-1">{{ $value->date != null ? dmY($value->date) : 'N/A' }}</td>
 <td class="col-md-1">{{ $value->description }}</td>
 </tr>
 @endforeach   
 </tbody>
 </table>
 @else
   <p class=" text-danger">No data found</p>
 @endif  
    </div>
</div>


<?php 

$invoice_master = DB::table('v_invoice_master')->where('LeadID', request()->id)->get();
  ?>


<h5>Invoice Sale </h5>
<div class="card">
    <div class="card-body">
@if(count($invoice_master)>0)        
<table class="table table-sm align-middle table-nowrap mb-0">
<tbody><tr>
<th width="15">S.No</th>
<th width="15">Invoice #</th>
<th width="15">Date</th>
<th width="15">Party Name</th>
<th width="15">Total Amount</th>
<th width="15">Paid</th>
<th width="15">Balance</th>


</tr>
</tbody>
<tbody>
@foreach ($invoice_master as $key => $invoice_master)
 <tr>
 <td >{{$key+1}}</td>
 <td ><a href="{{URL('/InvoicePDF/'.$invoice_master-> InvoiceMasterID)}}">{{ $invoice_master-> InvoiceMasterID}}</a></td>
 <td >{{ $invoice_master-> Date}}</td>
 <td >{{ $invoice_master->PartyName }}</td>
 <td >{{ $invoice_master->Total }}</td>
 <td >{{ $invoice_master->Paid }}</td>
 <td >{{ $invoice_master->Balance }}</td>
 </tr>
 @endforeach   
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




<!-- <script>
    $(document).ready(function() {
 // executes when HTML-Document is loaded and DOM is ready
console.log("document is ready");
  

  $( ".card" ).hover(
  function() {
    $(this).addClass('shadow-md').css('cursor', 'pointer'); 
  }, function() {
    $(this).removeClass('shadow-md');
  }
);
  
// document ready  
});
</script> -->

@endsection
