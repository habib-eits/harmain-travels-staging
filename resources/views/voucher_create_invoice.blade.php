@extends('tmp')
@section('title', $pagetitle)
 

@section('content')

    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700|Noto+Serif:400,700"> 
    <!-- Bootstrap core CSS -->
    
        <link rel="stylesheet" type="text/css" href="{{asset('assets/src/jquery.modallink-1.0.0.css')}}" />

 
      <!-- Custom styles for this template -->
 
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
 
 
   <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
       <!-- END: Vendor CSS-->
 
    <!-- BEGIN: Theme CSS-->
     
    <!-- BEGIN: Page CSS-->
       <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
     <!-- END: Custom CSS-->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
  

<style type="text/css">

.form-control
{
border-radius: 0 !important;


}

.select2
{
border-radius: 0 !important;
width: 100% !important;

}


.swal2-popup {
font-size: 0.8rem;
font-weight: inherit;
color: #5E5873;
}

.select2-container--default .select2-search--dropdown {
     padding: 1px !important; 
    background-color: #556ee6 !important;
}


</style>



<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Voucher</h4>
            <div class="page-title-right ">
              
              
            </div>
            
            
            
          </div>
        </div>
      </div>
    
          
          
          <!-- enctype="multipart/form-data" -->
          <form action="{{URL('/VoucherSave')}}" method="post">
            
            <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
            
            <div class="card shadow-sm">
              <div class="card-body">
                
                <div class="row">
                  
                  <!-- <img src="{{asset('assets/images/logo/ft.png')}}" alt=""> -->
                  
                  <div class="col-6">
                    
                    <input type="hidden" name="VoucherType" id="VoucherType" class="form-control">
                    <textarea name="Narration_mst" id="Narration" cols="30" rows="7" class="form-control " placeholder="Narration" required=""></textarea>
                    <div class="clearfix mt-1"></div>
                    
                    
                  </div>
                  
                  <div class="col-6">
                    <div class="row">
                      <div class="col-12">
                        <div class="mb-1 row">
                          <div class="col-sm-3">
                            <label class="col-form-label" for="first-name">Invoice #</label>
                          </div>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="Voucher" id="Voucher" value="{{$vhno}}">
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
                            <label class="col-form-label" for="password">Account</label>
                          </div>
                          <div class="col-sm-9">
                            <select class="form-select changesNooo" name="ChartOfAccount1" id="ChartOfAccount1">
                              <?php foreach ($chartofaccount1 as $key => $value): ?>
                              <option value="{{$value->ChartOfAccountID}}">{{$value->ChartOfAccountID}}-{{$value->ChartOfAccountName}}</option>
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
                            <div class="input-group" id="datepicker21">
                              <input type="date" id="VHDate" name="VHDate" class="form-control" value="{{date('Y-m-d')}}">
                            </div>
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
                  <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                    <table   style="border-collapse: collapse;" cellspacing="0" cellpadding="0">
                      <thead>
                        <tr class="bg-light borde-1 border-light "  style="height: 40px;">
                          <th width="2%" class="p-1"><input id="check_all"  type="checkbox" style="margin-left: 13px;"/></th>
                          <th width="10%">Account</th>
                           <th width="12%">Supplier</th>
                           <th width="12%">Party</th>
                          <th width="10%">Narration</th>
                          
                          
                          <th width="5%">Invoice</th>
                          <th width="5%">Ref No</th>
                          <th width="5%">Amount</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <tr  class="bg-light border-1 border-light" >
                          <td class=" bg-light border-1 border-light"><input class="case" type="checkbox" style="margin-left: 15px;" /></td>
                          <td>
                            <select name="ChOfAcc[]" id="ItemID0_1" class="form-select  form-control-sm select2  " required="">
                              <option value="">Select Account</option>
                              @foreach ($chartofaccount as $key => $value)
                              <option value="{{$value->ChartOfAccountID}}">{{$value->ChartOfAccountID}}-{{$value->ChartOfAccountName}}</option>
                              @endforeach
                            </select>
                          </td>
                    
                        <td> <select name="SupplierID[]" id="SupplierID_1" class="   form-select select2 supplier" onchange="ajax_balance(this.value);">
                          <option value="">Select SupplierID</option>
                          @foreach ($supplier as $key => $value)
                          <option value="{{$value->SupplierID}}">{{$value->SupplierName}}</option>
                          @endforeach
                        </select>
                      </td>

                         <td> <select name="PartyID[]" id="PartyID_1" class=" row-party-id   form-select select2 party" onchange="ajax_balance(this.value);">
                          <option value="">Select PartyID</option>
                          @foreach ($party as $key => $value)
                          <option value="{{$value->PartyID}}">{{$value->PartyID}}-{{$value->PartyName}}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <input type="text" name="Narration[]" id="RefNo_1" class="form-control      " autoco required=""mplete="off" required="" >
                      </td>
                      
                      
                      <td>
                        <input type="number" name="Invoice[]" id="OPVAT_1" class=" form-control  " autocomplete="off" >
                      </td>
                      <td>
                        <input type="number" name="RefNo[]" id="IPVAT_1" class=" form-control  " autocomplete="off"  >
                      </td>
                      
                      
                      
                      
                      <td>
                        <input type="number" name="Debit[]" id="debit_1" class=" row-debit form-control changesNo totalLinePricee" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01"  required="" >
                      </td>
                      
                    </tr>
                    
                  </tbody>
                  <tfooter>
                  <tr class="bg-light border-1 border-light "  style="height: 40px;">
                    <th width="2%" > </th>
                    <th width="10%">  </th>
                     <th width="12%"> </th>
                    <th width="10%"> </th>
                    
                    
                    <th width="5%"> </th>
                    <th width="5%"> </th>
                    <th width="5%"> </th>
                    <th width="5%"><input type="text"  readonly="" class=" form-control " id="sum_dr"> </th>
                  </tr>
                  </tfooter>
                </table>
              </div>
            </div>
            <div class="row mt-1 mb-2" style="margin-left: 29px;">
              <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  ' >
                <button class="btn btn-danger delete" type="button"><i class="bx bx-trash align-middle font-medium-3 me-25"></i>Delete</button>
                <button class="btn btn-success addmore" type="button"><i class="bx bx-list-plus align-middle font-medium-3 me-25"></i> Add More</button>
              </div>
              <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  ' >
                <div id="result"></div>
              </div>
              <br>
              
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
            
            
            
          </div><div class="card-footer bg-light"> <div  ><button type="submit"  class="btn btn-primary w-lg me-50 float-right">Save</button>
          <a href="{{URL('/Voucher')}}" class="btn btn-secondary w-lg float-right">Cancel</a>
                       <a href="{{URL('/Attachment'.'/'.$vhno)}}" class="modal-link btn btn-success">Attach file</a>

        </div></div>
      </div>
    </div>
    
    
    
    
    
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
<script src="{{asset('assets/invoice/js/jquery-1.11.2.min.js')}}"></script>
<script src="{{asset('assets/invoice/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/invoice/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/invoice/js/bootstrap-datepicker.js')}}"></script>
<!-- <script src="js/ajax.js"></script> -->
<script>

/**
* Site : http:www.smarttutorials.net
* @author muni
*/

//adds extra table rows
var i=$('table tr').length;
$(".addmore").on('click',function(){
html = '<tr class="bg-light border-1 border-light ">';
html += '<td ><input class="case" type="checkbox" style="margin-left: 15px;" /></td>';
html += '<td><select name="ChOfAcc[]" id="ItemID0_'+i+'" class="form-select cls changesNoo select2" required=""> <option value="">Select Account</option> @foreach ($chartofaccount as $key => $value) <option value="{{$value->ChartOfAccountID}}">{{$value->ChartOfAccountID}}-{{$value->ChartOfAccountName}}</option>@endforeach</select> </td>';
// html += '<td><select name="ItemID[]" id="ItemID_'+i+'" class="form-select changesNoo"><option value="">Select Item</option><option value="">b</option></select></td>';
 
html += '<td><select name="SupplierID[]" id="SupplierID'+i+'" class="form-select select2 supplier" onchange="ajax_balance(this.value);"><option value="">Select Supplier</option>@foreach ($supplier as $key => $value)<option value="{{$value->SupplierID}}">{{$value->SupplierName}}</option>@endforeach</select></td>';

html += '<td><select name="PartyID[]" id="PartyID_'+i+'" class="form-select party select2" onchange="ajax_balance(this.value);"><option value="">Select PartyID</option>@foreach ($party as $key => $value)<option value="{{$value->PartyID}}">{{$value->PartyID}}-{{$value->PartyName}}</option>@endforeach</select></td>';
html += '<td><input type="text" name="Narration[]" id="RefNo_'+i+'" class="form-control changesNo"  required=""></td>';

html += '<td><input type="text" name="Invoice[]" id="OPVAT_'+i+'" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
html += '<td><input type="text" name="RefNo[]" id="IPVAT_'+i+'" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
html += '<td><input type="number" name="Debit[]" id="debit_'+i+'" class=" form-control changesNo totalLinePricee" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01"  required="" ></td>';
html += '</tr>';
$('table').append(html);

$('.select2','table').select2();

   $('#ItemID0_' + i).select2();
    $('#SupplireID_' + i).select2();
    $('#PartyID_' + i).select2();



i++;
});
//to check all checkboxes
$(document).on('change','#check_all',function(){
$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});
//deletes the selected table rows
$(".delete").on('click', function() {
$('.case:checkbox:checked').parents("tr").remove();
$('#check_all').prop("checked", false);
calculateTotal();
});
var prices = ["S10_1678|1969 Harley Davidson Ultimate Chopper|48.81","S10_1949|1952 Alpine Renault 1300|98.58"];



////////////////////////////////////////////
$(document).on('change keyup blur','.changesNo',function(){


  let row = $(this).closest('tr');

ajax_invoice_knockoff(row);
calculateTotal();
});
//total price calculation
function calculateTotal(){
var sum_dr=0;
$.each($('.totalLinePricee'),function() {
if ($(this).val().length == 0) {

}
else
{
sum_dr += parseFloat($(this).val());

}
});
//alert(sum);
$("#sum_dr").val(sum_dr); // display in div in html
var sum_cr=0;
$.each($('.totalLinePrice'),function() {
if ($(this).val().length == 0) {

}
else
{
sum_cr += parseFloat($(this).val());

}
});
//alert(sum);
$("#sum_cr").val(sum_cr); // display in div in html
if (parseFloat($('#sum_dr').val())!=parseFloat($('#sum_cr').val()) ) {
// alert("Debit must be equal to Credit. Please check");
$('#sum_dr').css("border", "1px dashed red");
$('#sum_cr').css("border", "1px dashed red");
}
else
{
$('#sum_dr').css("border", "1px dashed green");
$('#sum_cr').css("border", "1px dashed green");
}


}


//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46); //Backspace
function IsNumeric(e) {
var keyCode = e.which ? e.which : e.keyCode;
console.log( keyCode );
var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
return ret;
}
//datepicker
$(function () {
$.fn.datepicker.defaults.format = "dd-mm-yyyy";
$('#invoiceDate').datepicker({
startDate: '-3d',
autoclose: true,
clearBtn: true,
todayHighlight: true
});
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
$('.js-example-basic-single').select2();
});
</script>
<!-- ajax trigger -->
<script>

////////////////////////////////////////////
///voucher trigger
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
// $(document).on('change','#VHDate',function(){

// id_arr = $('#InvoiceType1').val();

// id = id_arr.split("-");
// vhdate = $('#VHDate').val();

// dm = vhdate.split("-");

// ajax_vhno();

// });

$(document).ready(function() {
id_arr = $('#InvoiceType1').val();

id = id_arr.split("-");
// alert($('#VHNO').val());
// alert($('#ItemID0_'+id[1]).val());
$('#VoucherType').val( id[0]  );
$('#VoucherCode').val( id[1]+$('#Voucher').val()  );
});

function ajax_balance(PartyID) {

// alert($("#csrf").val());

$('#result').prepend('')
$('#result').prepend('<img id="theImg" src="{{asset('assets/images/ajax.gif')}}" />')

var PartyID = PartyID;
// alert(PartyID);
if(PartyID!=""  ){
/*  $("#butsave").attr("disabled", "disabled"); */
// alert(SupplierID);
$.ajax({
url: "{{URL('/Ajax_Balance')}}",
type: "POST",
data: {
_token: $("#csrf").val(),
PartyID: PartyID,

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
// // ajax vhno
// function ajax_vhno()
// {

// var VHDate = dm[0]+dm[1];

// // alert(id[1]+id[0]);

// /*  $("#butsave").attr("disabled", "disabled"); */
// // alert(SupplierID);

// $.ajax({
// url: "{{URL('/Ajax_VHNO')}}",
// type: "POST",
// data: {
// _token: $("#csrf").val(),
// VocherTypeID: id[0],
// VocherCode: id[1],
// VHDate: VHDate,

// },
// cache: false,
// success: function(data){


// $('#vhno_div').html(data);

// }
// });

// }
 
// end ajax vhno

$( "#submit" ).click(function() {
// alert($('#sum_dr').val());
// alert($('#sum_cr').val());
if (parseFloat($('#sum_dr').val())!=parseFloat($('#sum_cr').val()) ) {
// alert("Debit must be equal to Credit. Please check");
$('#sum_dr').css("border", "1px dashed red");
$('#sum_cr').css("border", "1px dashed red");
// this.value == '';
/* or with jQuery: $(this).val(''); */
Swal.fire({
position: 'top-right',
// icon: 'error',
title: 'Debit must be equal to Credit. Please check',
showConfirmButton: false,
timer: 2000
})
return false;
}
});


</script>



<script src="http://code.jquery.com/jquery-1.12.4.min.js"></script>
 
<!-- BEGIN: Vendor JS-->
 <!-- BEGIN Vendor JS-->
<!-- BEGIN: Page Vendor JS-->
   <!-- END: Page Vendor JS-->
 <!-- BEGIN: Theme JS-->
   <!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
 <!-- END: Page JS-->



 <script type="text/javascript" src="{{asset('assets/src/jquery.modalLink-1.0.0.js')}}"></script>

 <script type="text/javascript">

            (function () {
                $(".modal-link").modalLink();

                 
            })();

        </script>

<script>
 


$(document).ready(function() {
    // Initialize select2 for dynamically created elements
    $(document).on('select2:select', '.supplier', function(e) {
        id_arr = $(this).attr('id'); 
         id = id_arr.split("_"); 
         // alert(id[1]);

         $('#ItemID0_'+id[1]).val('210100').trigger('change.select2');
    });

    // If select2 is applied dynamically later, reinitialize it
    $('.supplier').select2();
});


$(document).ready(function() {
    // Initialize select2 for dynamically created elements
    $(document).on('select2:select', '.party', function(e) {
        id_arr = $(this).attr('id'); 
         id = id_arr.split("_"); 
         // alert(id[1]);

         $('#ItemID0_'+id[1]).val('110400').trigger('change.select2');
    });

    // If select2 is applied dynamically later, reinitialize it
    $('.party').select2();
});


</script>


<script>

function ajax_invoice_knockoff(row)
{


  
  let amount= row.find('.row-debit').val();
  let partyid = row.find('.row-party-id').val();
 
  

console.log('bc'+amount+'-'+partyid);


}


</script>

@endsection