@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')

   


   <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

<div class="row">
  <div class="col-12">
  
  <div class="card shadow-sm">
      <div class="card-body">
          <!-- enctype="multipart/form-data" -->
<form action="{{URL('/AdjustmentBalanceSave')}}" method="post"> 

 
      <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">

 
 <div >
     <div >
     


<div class="row">
 
    <!-- <img src="{{asset('assets/images/logo/ft.png')}}" alt=""> -->


 
<div class="col-6">

   
           

     <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="email-id">Party/Customer</label>
                  </div>
                  <div class="col-sm-9">
                     <select name="PartyID" id="" class="select2 form-select" id="select2-basic">
                <option value="">Select</option>
                <?php foreach ($party as $key => $value): ?>
                  <option value="{{$value->PartyID}}">{{$value->PartyName}}</option>
                  
                <?php endforeach ?>
             
              </select>
                  </div>
                </div>


                 <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="email-id">Supplier</label>
                  </div>
                  <div class="col-sm-9">
                     <select name="SupplierID" id="" class="select2 form-select" id="select2-basic">
                <option value="">Select</option>
                <?php foreach ($party as $key => $value): ?>
                  <option value="{{$value->PartyID}}">{{$value->PartyName}}</option>
                  
                <?php endforeach ?>
             
              </select>
                  </div>
                </div>




                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="email-id">Amount</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text"  name="Amount" id="Amount"class="form-control">
                  </div>
                </div>

                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="email-id">Narration</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text"  name="Narration" id="Narration"class="form-control">
                  </div>
                </div>         
               
</div>
 
   <div class="col-6">


    <div class="row">
              <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="first-name">Invoice #</label>
                  </div>
                  <div class="col-sm-9">
                    <div id="vhno_div"> <img src="{{asset('assets/images/ajax.gif')}}" alt="">
                     </div>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="password">Voucher Type</label>
                  </div>
                  <div class="col-sm-9">
                   <select class="form-select changesNooo" name="InvoiceType1" id="InvoiceType1">
   <?php foreach ($voucher_type as $key => $value): ?>
     <option value="{{$value->VoucherTypeID}}-{{$value->VoucherCode}}">{{$value->VoucherCode}}-{{$value->VoucherTypeName}}</option>
   <?php endforeach ?>
</select> 
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="email-id">Date</label>
                  </div>
                  <div class="col-sm-9">
                     <div class="input-group" id="datepicker22">
  <input type="text" name="VHDate"  id="VHDate" autocomplete="off" class="form-control kashif" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" data-date-container="#datepicker22" data-provide="datepicker" data-date-autoclose="true" value="{{date('Y-m-d')}}">
  <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
    </div>
                </div>
              </div>
           
              


              
               
              
              
             
            </div>
    


  </div>
</div>



    <hr class="invoice-spacing">
       
    <div class='text-center'>
      
    </div>
        <div class='row'>
          <div class='col-md-12'>
            <div class="row custom-options-checkable g-1">
          <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType1" checked value="1" >
            <label class="custom-option-item p-1" for="CustomType1">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Discount Allowed</span>
               </span>
             </label>
          </div>

          <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType2" value="2">
            <label class="custom-option-item p-1" for="CustomType2">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Discount Received</span>
               </span>
             </label>
          </div>
    <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType3" value="3">
            <label class="custom-option-item p-1" for="CustomType3">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Increase receivable</span>
               </span>
             </label>
          </div>
       
    <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType4" value="4">
            <label class="custom-option-item p-1" for="CustomType4">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Decrease receivable</span>
               </span>
             </label>
          </div>
       
          <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType5" value="5">
            <label class="custom-option-item p-1" for="CustomType5">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Increase Payable</span>
                                <span class="fw-bolder">Supplier</span>

               </span>
             </label>
          </div>
           <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType6" value="6">
            <label class="custom-option-item p-1" for="CustomType6">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Decrease Payable</span>
                <span class="fw-bolder">Supplier</span>
               </span>
             </label>
          </div>

      

          <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType7" value="7">
            <label class="custom-option-item p-1" for="CustomType7">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Fee Payable / Billed</span>
               </span>
             </label>
          </div>

              <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType8" value="8">
            <label class="custom-option-item p-1" for="CustomType8">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Fee Payable / Debit</span>
               </span>
             </label>
          </div>
           <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType9" value="9">
            <label class="custom-option-item p-1" for="CustomType9">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Fee Bill / Paid Increases</span>
               </span>
             </label>
          </div>
           <div class="col-md-4">
            <input class="custom-option-item-check" type="radio" name="CustomType" id="CustomType10" value="10">
            <label class="custom-option-item p-1" for="CustomType10">
              <span class="d-flex justify-content-between flex-wrap ">
                <span class="fw-bolder">Fee Bill / Paid Decrease</span>
               </span>
             </label>
          </div>

        </div>
          </div>
        </div>
     


        <div >


      
        </div>


        
        
          
        
  <!--  <div class='row'>
          <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
            <div class="well text-center">
          <h2>Back TO Tutorial: <a href="#"> Invoice System </a> </h2>
        </div>
          </div>
        </div>   -->
  
               
      
    </div>
     </div>
 </div>
 
      
         
       
     

<div class="card-footer bg-light"> <div  ><button type="submit" id="submit" class="btn btn-primary w-lg me-50 float-right">Save</button>
              <a href="{{URL('/Voucher')}}" class="btn btn-secondary w-lg float-right">Cancel</a>

       </div></div>

      </div>

      <!-- card end -->
  </div>
   </form>
  </div>
</div>

        </div> 
      </div>
    </div>
    <!-- END: Content-->

 <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
 
$(document).on('change','#InvoiceType1',function(){


  id_arr = $('#InvoiceType1').val();
 
  id = id_arr.split("-");

 // alert($('#VHNO').val());
  vhdate = $('#VHDate').val();
 
dm = vhdate.split("-");

// alert($('#ItemID0_'+id[1]).val());
$('#VoucherType').val( id[0]  );
$('#VoucherCode').val( id[1]+$('#Voucher').val()  );


ajax_vhno();
// val = $('#ItemID0_'+id[1]).val().split("|");



// alert($('#ItemID0_'+id[1]).val());
// $('#Taxable_'+id[1]).val( val[1]  );
// $('#ItemID_'+id[1]).val( val[0]  );
  
 

 
});


 

$(document).on('change ','#VHDate',function(){


  id_arr = $('#InvoiceType1').val();
 
  id = id_arr.split("-");

  vhdate = $('#VHDate').val();
 
dm = vhdate.split("-");
 

 alert(dm[0] + dm[1]);
  ajax_vhno();

 
});
 
$(document).ready(function() {
     id_arr = $('#InvoiceType1').val();
 
  id = id_arr.split("-");

 // alert($('#VHNO').val());


// alert($('#ItemID0_'+id[1]).val());
$('#VoucherType').val( id[0]  );
$('#VoucherCode').val( id[1]+$('#Voucher').val()  );
}); 
   




   function ajax_balance(SupplierID) {
      
       // alert($("#csrf").val());
 
$('#result').prepend('')
$('#result').prepend('<img id="theImg" src="{{asset('assets/images/ajax.gif')}}" />')
 
       var SupplierID = SupplierID;

       // alert(SupplierID);
       if(SupplierID!=""  ){
        /*  $("#butsave").attr("disabled", "disabled"); */
        // alert(SupplierID);
          $.ajax({
              url: "{{URL('/Ajax_Balance')}}",
              type: "POST",
              data: {
                  _token: $("#csrf").val(),
                   SupplierID: SupplierID,
                 
              },
              cache: false,
              success: function(data){
            

              
                    $('#result').html(data);
           
                 
                  
              }
          });
      }
      else{
          alert('Please Select Branch');
      }

      
      

  }

 function onDateChange() {
   // Do something here
   alert('hello');
}

$('#VHDate').bind('changeDate', onDateChange);
$('#VHDate').bind('onselect', onDateChange);
$('#VHDate').bind('select', onDateChange);
$('#VHDate').bind('click', onDateChange);
$('#VHDate').bind('input', onDateChange);


// ajax vhno


function ajax_vhno()
{

       
       var VHDate = dm[0]+dm[1];


 
     // alert(id[1]+id[0]);
        
        /*  $("#butsave").attr("disabled", "disabled"); */
        // alert(SupplierID);
        
          $.ajax({
              url: "{{URL('/Ajax_VHNO')}}",
              type: "POST",
              data: {
                  _token: $("#csrf").val(),
                   VocherTypeID: id[0],
                   VocherCode: id[1],
                   VHDate: VHDate,
                 
              },
              cache: false,
              success: function(data){
            

              
                    $('#vhno_div').html(data);
    
              }
          });
      
}

function ajax_vhno1()
{

       // onload php date will work not boostrap picker
       var VHDate = {{date('Ym')}};


 
     // alert(id[1]+id[0]);
        
        /*  $("#butsave").attr("disabled", "disabled"); */
        // alert(SupplierID);
        
          $.ajax({
              url: "{{URL('/Ajax_VHNO')}}",
              type: "POST",
              data: {
                  _token: $("#csrf").val(),
                   VocherTypeID: id[0],
                   VocherCode: id[1],
                   VHDate: VHDate,
                 
              },
              cache: false,
              success: function(data){
            

              
                    $('#vhno_div').html(data);
    
              }
          });
      
}

$(document).ready(function() {


  ajax_vhno1();
      

});
</script>

  @endsection