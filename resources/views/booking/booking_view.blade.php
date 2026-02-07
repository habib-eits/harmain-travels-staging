@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')



<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Booking Details</h4>
            <div class="page-title-right ">
              
             
                  
              
                 <a href="#" onclick="history.back()" class="btn btn-success btn-rounded w-md  w-smfloat-right">Back to Leads</a>

            </div>
            
            
            
          </div>
        </div>
      </div>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
   <script>
 @if(session('success'))
    toastr.options =
    {
      "closeButton" : false,
      "progressBar" : true
    }
          Command: toastr["success"]("{{session('success')}}")
    @endif
  </script>                      
 

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

               
          
            
  <div class="card shadow-sm">
      <div class="card-body">


<div class="row">


<div class="col-md-6">
  <table class="table table-striped">
   
   <tbody>
     <tr>
       <td width="40%">Title</td>
       <td> {{$booking[0]->title}}</td>
     </tr>

       <tr>
       <td width="40%">Start Date</td>
       <td> {{$booking[0]->start}}</td>
     </tr>

       <tr>
       <td width="40%">Start Date</td>
       <td> {{$booking[0]->end}}</td>
     </tr>

         <tr>
       <td width="40%">Agent</td>
       <td> {{$booking[0]->agent_name}}</td>
     </tr>

            <tr>
       <td width="40%">Client</td>
       <td> {{$booking[0]->PartyName}}</td>
     </tr>

              <tr>
       <td width="40%">Client Contact</td>
       <td> {{$booking[0]->client_contact}}</td>
     </tr>


       <tr>
       <td width="40%">Client Contact</td>
       <td> {{$booking[0]->client_address}}</td>
     </tr>


       <tr>
       <td width="40%">Client Contact</td>
       <td> {{$booking[0]->client_address}}</td>
     </tr>



       <tr>
       <td width="40%">Vendor Name</td>
       <td> {{$booking[0]->SupplierName}}</td>

         
     </tr>

 <tr>
        <td width="40%">Notes</td>
       <td> {{$booking[0]->remarks}}</td>
     </tr>
     


   </tbody>
 </table> 

</div>

<div class="col-md-6">
  <table class="table table-striped">
   
   <tbody>
      


    <tr>
        <td width="40%">Vendor Cost</td>
       <td> {{$booking[0]->vendor_cost}}</td>
     </tr>


    <tr>
        <td width="40%">CNC Cost</td>
       <td> {{$booking[0]->cnc_cost}}</td>
     </tr>

      <tr>
        <td width="40%">Profit</td>
       <td> {{$booking[0]->profit}}</td>
     </tr>


      <tr>
        <td width="40%">VAT 5%</td>
       <td> {{$booking[0]->output_vat}}</td>
     </tr>


   <tr>
        <td width="40%">VAT 5%</td>
       <td> {{$booking[0]->net_invoice}}</td>
     </tr>

      <tr>
        <td width="40%">Service</td>
       <td> {{$booking[0]->services}}</td>
     </tr>


     <tr>
        <td width="40%">Payment Mode</td>
       <td> {{$booking[0]->payment_status}}</td>
     </tr>

    <tr>
        <td width="40%">Payment Collected By</td>
       <td> {{$booking[0]->collected_by}}</td>
     </tr>


       <tr>
        <td width="40%">Payment Collected By</td>
       <td> {{$booking[0]->collected_by}}</td>
     </tr>


    <tr>
        <td width="40%">Payment Receipt</td>
       <td>  <?php 

                                                    if($booking[0]->file)
                                                    {

                                                        ?>

                                               <a href="{{ env('APP'). Storage::url('app/public/uploads/'.$booking[0]->file) }}" title="" target="_blank" class="btn btn-success btn-sm ">View</a>  


                                                        <?php

                                                    }

                                                     ?>
</td>
     </tr>

         <tr>
        <td width="40%">Invoice Proof</td>
       <td> <?php 

                                                    if($booking[0]->invoice_file)
                                                    {

                                                        ?>

                                               <a href="{{ env('APP'). Storage::url('app/public/uploads/'.$booking[0]->invoice_file) }}"  class="btn btn-success btn-sm " title="" target="_blank">View</a>


                                                        <?php

                                                    }

                                                     ?></td>
     </tr>


    



   </tbody>
 </table> 

</div>
  
</div>

 


       <!-- end of car body -->
     </div>

    <!-- end of car -->
      </div>
        
        


      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
$(document).ready(function(){
    // Capture change event using ID
    $('#vendor_cost').keyup(function(){
        // Get the selected value
        var vendor_cost = $(this).val();

        input_vat = parseFloat(vendor_cost) * parseFloat(0);
         
         $('#input_vat').val(input_vat.toFixed(2));

     });

       $('#cnc_cost').keyup(function(){
        // Get the selected value
        vendor_cost =  $('#vendor_cost').val();
        cnc_cost =  $('#cnc_cost').val();
        profit = parseFloat(cnc_cost) - parseFloat(vendor_cost)  ;
        output_vat = parseFloat(profit) * parseFloat(0.05);
        $('#output_vat').val(output_vat.toFixed(2));


         
        net_invoice = parseFloat(cnc_cost)+ parseFloat(output_vat);
         $('#profit').val(profit.toFixed(2));
         $('#net_invoice').val(net_invoice.toFixed(2));


         if(profit<0)
         {
          $('#cnc_cost').css('background-color', 'orange');
         }
         else
         {

          $('#cnc_cost').css('background-color', 'white');
         }


     });




   $('#vendor_cost').blur(function(){
        $('#cnc_cost').focus(); 

     });


    $('#cnc_cost').blur(function(){
         $('#services').focus(); 

     });


});
</script>


 
<script>
$('.colors option').each(function() {
$(this).css('background-color', $(this).val());
});

$('.colors').on('change', function() {
$(this).css('background-color', $(this).val());
});
</script>


<style type="text/css" media="screen">
    
.colors {

background-color: orange;
}

.colors option{

height: 35px;

}

select option {
  padding: 10px; /* Adjust padding as needed */
  height: 50px; /* Adjust the height of each option */
  font-size: 35px; /* Adjust font size */
}


</style>

  @endsection