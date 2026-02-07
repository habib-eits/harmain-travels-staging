@extends('tmp')

@section('title', 'Invoice')


@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">





<!-- Modal -->
<div class="modal fade exampleModal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add new customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

        </button>
      </div>
      <form method="post">
        
        <div class="modal-body">

          <div class="row">
            <div class="col-12">
              <label for=""><strong>Customer : *</strong></label>
              <input type="text" class="form-control" id="PartyName" name="PartyName" required>
              <span class="error-message" id="name-error">Name is required.</span>
            </div>

            <div class="col-12 mt-2">
              <label for=""><strong>Mobile No: *</strong></label>
              <input type="text" class="form-control" id="Phone" name="Phone" required="">
              <span class="error-message" id="email-error">Phone Number is required.</span>

            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="submitButton" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Responsive datatable examples -->



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
      <!-- start page title -->

 
 @if(count($rec_vouchers) > 0 )
  <div class="alert alert-danger " >
                     Amount already received against this Invoice, Please delete the receipt first 
                   @foreach($rec_vouchers as $value)
                  
                   <p>
                                      

                   <a href="{{ URL('/VoucherEdit/'.$value->VoucherMstID) }}"> {{$value->Voucher}}->{{number_format($value->Debit,2)}}</a>
                   </p> 
                  
                   @endforeach
@endif                   
 
  </div>


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
      <div class="card shadow-sm">
        <div class="card-body">


          <!-- enctype="multipart/form-data" -->
          <form id="invoice_form" method="post">


            <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">


            <div class=" ">
              <div class=" ">



                <div class="row">
                  <div class="col-6">  


                  
                    <div class="col-6">
                      <label for="">Invoice Type</label>
                      <select class="form-select select2 " name="InvoiceTypeID"   id="InvoiceTypeID">
                        <?php foreach ($invoice_type as $key => $value): ?>
                        <option value="{{$value->InvoiceTypeID}}" >{{$value->InvoiceTypeCode}}-{{$value->InvoiceType}}
                        </option>
                        <?php endforeach ?>
                      </select>

                      <div class="clearfix mt-1"></div>
                      <label for="">Party</label>

                      <select name="PartyID" id="PartyID" class="form-select select2 mt-5">
                        <option value="">Select</option>
                        @foreach ($party as $value)
                            <option value="{{ $value->PartyID }}" >
                              {{ $value->PartyType }}-{{ $value->PartyID }} - {{ $value->PartyName }}-{{ $value->Phone }} 
                            </option>
                        @endforeach
                    </select>



                      <span id="PartyError" style="color: red; display: none;">Please select a party</span>
                    </div>
                    <div class="clearfix mt-1"></div>
                  </div>


                  <div class="col-2"> </div>
                  <div class="col-4">


                    <div class="row">
                      <div class="col-12">
                        <div class="mb-1 row">
                          <div class="col-sm-3">
                            <label class="col-form-label" for="first-name">Invoice #</label>
                          </div>
                          <div class="col-sm-9">
                            <input type="text" id="first-name" class="form-control" name="VHNO"
                              value="{{$vhno[0]->VHNO}}" readonly="">
                          </div>
                        </div>
                      </div>

                            <div class="col-12">
                        <div class="mb-1 row">
                          <div class="col-sm-3">
                            <label class="col-form-label" for="first-name">Lead #</label>
                          </div>
                          <div class="col-sm-9">
                            <input type="text" id="first-name" class="form-control" name="LeadID"
                              value="{{session::get('LeadID')}}" readonly="">
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
                              <input type="date" name="Date" class="form-control" value="{{date('Y-m-d')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="mb-1 row">
                            <div class="col-sm-3">
                              <label class="col-form-label" for="contact-info">Due Date</label>
                            </div>
                            <div class="col-sm-9">
                              <div class="input-group" id="datepicker22">

                                <input type="date" name="DueDate" class="form-control" value="{{date('Y-m-d')}}">


                              </div>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" name="PaymentMode" value="">
          

                        <div class="col-12">
                          <div class="mb-1 row">
                            <div class="col-sm-3">
                              <label class="col-form-label" for="password">Salesman </label>
                            </div>
                            <div class="col-sm-9">
                              <select name="SalemanID" id="SalemanID" class="form-select">
                                {{-- <option value="">Select</option> --}}
                                <?php foreach ($saleman as $key => $value): ?>
                                <option value="{{$value->UserID}}">{{$value->FullName}}</option>
                                <?php endforeach ?>
                              </select>
                              <span id="SalemanError" style="color: red; display: none;">Please select a Saleman</span>
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
                     <table  width="100%" >
                        <thead>
                          <tr class="bg-light borde-1 border-light " style="height: 40px;">
                            <th width="2%" class="p-1"><input id="check_all" type="checkbox" /></th>
                            <th width="12%">Item</th>
                             <th width="5%" class="d-none">Ref No</th>
                            <!-- <th width="5%">Visa </th> -->
                            <th width="10%">PAX Name</th>
                            <!-- <th width="8%">PNR</th> -->
                            <th width="5%">Sector</th>
                            <th width="5%">Cost</th>
                            <th width="5%">VAT%</th>
                            <th width="5%">Service</th>
                            
                          <!--   <th width="6%">VAT</th>
                            <th width="4%">Dis</th> -->
                            <th width="7%">Sale Price</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="p-3" style="vertical-align: top;">
                            <td class="p-1 bg-light borde-1 border-light"><input class="case" type="checkbox" /></td>
                            <td>

                              <select name="ItemID0[]" id="ItemID0_1" class="form-select form-control-sm  select2 changesNoo"  >
                                <option value="">Select Item</option>
                                @foreach ($items as $key => $value)
                                <option  value="{{$value->ItemID}}" data-tax="{{$value->Percentage}}">
                                  {{$value->ItemCode}}-{{$value->ItemName}}-{{$value->Percentage}}</option>
                                @endforeach
                              </select>
                              <select name="SupplierID[]" id="SupplierID_1" class="form-select select2 changesNo" 
                                onchange="ajax_balance(this.value);">
                                <option value="">Select Supplier</option>
                                @foreach ($supplier as $key => $value)
                                <option value="{{$value->PartyID}}">{{$value->PartyID}}-{{$value->PartyName}}</option>
                                @endforeach
                              </select>
                              <input type="hidden" name="ItemID[]" id="ItemID_1">
                            </td>
                            

                            <td class="d-none">
                              <input type="text" name="RefNo[]" id="RefNo_1" class="form-control      "
                                autocomplete="off" placeholder="RefNo"><input type="text" name="VisaType[]" id="VisaType_1" class="   form-control  "
                                autocomplete="off" placeholder="Visa">

                            </td>
 
                            <td>
                              <input type="text" name="PaxName[]" id="PaxName_1" class=" form-control  "
                                autocomplete="off" placeholder="PaxName"><input type="text" name="PNR[]" id="PNR_1" class=" form-control  " autocomplete="off" placeholder="PNR">
                            </td>
                           
                            <td>
                              <input type="text" name="Sector[]" id="Sector_1" class=" form-control  "
                                autocomplete="off" placeholder="Sector">
                            </td>
                            <td>
                              <input type="number" name="Fare[]" id="Fare_1" class=" form-control changesNo" 
                                autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;"
                                onpaste="return false;" step="0.01" placeholder="Fare">
                            </td>
                            <td>
                              <input type="number" name="Taxable[]" id="Taxable_1" class=" form-control  changesNo  "
                                autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;"
                                onpaste="return false;" step="0.01"  ><input type="number" name="TaxAmount[]" id="TaxAmount_1" class="form-control changesNo"
                                autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;"
                                onpaste="return false;" step="0.01" placeholder="VAT Amount">
                            </td>
                            <td>
                              <input type="number" name="Service[]" id="Service_1" class=" form-control  "
                                autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;"
                                onpaste="return false;" step="0.01" placeholder="Service">
                                 <input type="number" name="Discount[]" id="discount_1" class=" form-control changesNo"
                                autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;"
                                onpaste="return false;" step="0.01" placeholder="Discount">
                            </td>
                           




                           
                           
                            <td>
                              <input type="number" name="ItemTotal[]" id="total_1" 
                                class=" form-control totalLinePrice changesNo" autocomplete="off"
                                onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"
                                step="0.01" placeholder="Total">
                            </td>
                          </tr>


                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="row mt-1 mb-2" style="margin-left: 29px;">
                    <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                      <button class="btn btn-danger delete" type="button"><i
                          class="bx bx-trash align-middle font-medium-3 me-25"></i>Delete</button>
                      <button class="btn btn-success addmore" type="button"><i
                          class="bx bx-list-plus align-middle font-medium-3 me-25"></i> Add More</button>

                    </div>

                    <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                      <div id="result"></div>

                    </div>
                    <br>

                  </div>


                  <div class="row">

                    <div class="col-lg-8 col-12  ">
                      <h5>Notes: </h5>


                      <textarea class="form-control" rows='5' name="remarks" id="notes"
                        placeholder="Your Notes"></textarea>




                      <div class="mt-2"><button type="submit" class="btn-disable btn btn-success w-lg float-right" id="submitButton">Save</button>
                        <a href="{{URL('/Invoice')}}" class="btn btn-secondary w-lg float-right">Cancel</a>

                      </div>


                    </div>


                    <div class="col-lg-4 col-12 ">
                      <form class="form-inline">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="hidden" class="form-control" id="subTotal" name="subTotal"
                              placeholder="Subtotal" onkeypress="return IsNumeric(event);" ondrop="return false;"
                              onpaste="return false;">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group">
                            <input type="hidden" class="form-control" id="tax" placeholder="Tax"
                              onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group">
                            <input type="hidden" class="form-control" id="taxAmount" placeholder="Tax"
                              onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                          </div>
                        </div>
                        <div class="form-group">

                          <label>
                            <h5>Total: &nbsp;</h5>
                          </label>
                          <div class="input-group">
                            <span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>
                            <input type="number" name="Total" class="form-control" step="0.01" id="totalAftertax"
                              placeholder="Total" onkeypress="return IsNumeric(event);" ondrop="return false;"
                              onpaste="return false;">
                          </div>
                        </div>
                        
                        
                        <input type="hidden" class="form-control" id="amountPaid-" name="amountPaid" value="0" />
 

                        <div class="form-group mt-1">

                          <label>
                            <H5>Amount Due: &nbsp;</H5>
                          </label>
                          <div class="input-group">
                            <span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>
                            <input type="number" class="form-control amountDue" name="amountDue" id="amountDue"
                              placeholder="Amount Due" onkeypress="return IsNumeric(event);" ondrop="return false;"
                              onpaste="return false;" step="0.01">
                          </div>
                        </div>

                    </div>
                  </div>
                  <div>



                  </div>

 


                </div>
              </div>
            </div>





          </form>



        </div>
      </div>

    </div>
  </div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script>
    // Create an instance of Notyf
    let notyf = new Notyf({
        duration: 3000,
        position: {
            x: 'right',
            y: 'top',
        },
    });
</script>


<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>



<script>
 



var i = $('table tr').length;

    $(".addmore").on('click', function() {
        var html = '<tr class="bg-light borde-1 border-light " style="vertical-align:top;">';
        html += '<td class="p-1"><input class="case" type="checkbox"/></td>';
        html += '<td><div class=""><select name="ItemID0[]" id="ItemID0_' + i + '" class="form-select select2 changesNoo" >';
        html += '<option value="">Select</option>'; // Add the Select option
        html += '@foreach ($items as $key => $value) <option value="{{$value->ItemID}}" data-tax="{{$value->Percentage}}">{{$value->ItemCode}}-{{$value->ItemName}}-{{$value->Percentage}}</option>@endforeach</select></div>';
        html += '<select name="SupplierID[]" id="SupplierID_' + i + '" onchange="ajax_balance(this.value);" class="form-select select2" ><option value="">Select</option>@foreach ($supplier as $key => $value) <option value="{{$value->PartyID}}">{{$value->PartyID}}-{{$value->PartyName}}</option>@endforeach<input type="hidden" name="ItemID[]" id="ItemID_' + i + '"></td>';
        
        html += '<td class="d-none"><input type="text" name="RefNo[]" id="RefNo_' + i + '" class="form-control" placeholder="RefNo"><input type="text" name="VisaType[]" id="VisaType_' + i + '" class="form-control" placeholder="Visa"></td>';
        // html += '<td>visa</td>';
        html += '<td><input type="text" name="PaxName[]" id="PaxName_' + i + '" class="form-control" placeholder="PaxName"><input type="text" name="PNR[]" id="PNR_' + i + '" class="form-control" placeholder="PNR"></td>';
        // html += '<td>pnr</td>';
        html += '<td><input type="text" name="Sector[]" id="Sector_' + i + '" class="form-control" placeholder="Sector"></td>';
        html += '<td><input type="text"  name="Fare[]" id="Fare_' + i + '" class="form-control" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="Fare"></td>';
        html += '<td><input type="text" name="Taxable[]" id="Taxable_' + i + '" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"  placeholder="VAT%"><input type="text" name="TaxAmount[]" id="TaxAmount_' + i + '" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="VAT Amt"></td>';
        html += '<td><input type="text" name="Service[]" id="Service_' + i + '" class="form-control" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="Service"><input type="text" name="Discount[]" id="discount_' + i + '" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="Discount"></td>';
        
        // html += '<td>tax</td>';
        // html += '<td>service</td>';
        html += '<td><input type="text"  name="ItemTotal[]" id="total_' + i + '" class="form-control totalLinePrice changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="Total"></td>';
        


          html += '</tr>';
        $('table').append(html);
       $('#ItemID0_' + i).select2();
      $('#SupplierID_' + i).select2();
        i++;



       
    });





    $(document).ready(function() {
    $('.select2').select2(); // Initialize Select2 if not already initialized

    $(document).on('change', '.select2', function() {
        let selectedValue = $(this).val();
        let taxPercentage = $(this).find(':selected').data('tax'); // Get the data-tax attribute
        id_arr = $(this).attr('id');
 
 id = id_arr.split("_");
        $('#Taxable_'+id[1]).val(taxPercentage);


        console.log("Selected Value:", selectedValue);
        console.log("Tax Percentage:", taxPercentage);
    });
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


 

//price change
$(document).on('blur change', '.changesNo', function() {

 
 

  InvoiceTypeID = $("#InvoiceTypeID option:selected").val();
  
  id_arr = $(this).attr('id');
 
  id = id_arr.split("_");


if(InvoiceTypeID==1)
{

   price = $('#price_'+id[1]).val();
  

  Fare = $('#Fare_'+id[1]).val();
  Total = $('#total_'+id[1]).val();

  Taxable = $('#Taxable_'+id[1]).val();

  Service = parseFloat(Total) - parseFloat(Fare);
  

 
         // TaxAmount = ( (parseFloat(Taxable)*parseFloat(Service))/100  ).toFixed(2);


  TaxPercentage = parseFloat ($('#ItemID0_'+id[1]).find('option:selected').data('tax') ) || 0;

$('#Taxable_'+id[1]).val(TaxPercentage);

TaxAmount = ( ( parseFloat(TaxPercentage) *parseFloat(Service))/(100+  parseFloat(TaxPercentage) )  ).toFixed(2);


     



// fix tax
     // TaxAmount = ( (5*parseFloat(Service))/(100+5)  ).toFixed(2);



     $('#TaxAmount_'+id[1]).val(TaxAmount);

      Service = parseFloat(Service) - parseFloat(TaxAmount);
$('#Service_'+id[1]).val(Service);

 

  
Discount = $('#discount_'+id[1]).val();


}
 
 
  if($('#Fare_'+id[1]).val() == "")
  {
      Fare=0;
  }
   
    if($('#discount_'+id[1]).val() == "")
  {
      Discount=0;
  }


   

  // if($('#Service_'+id[1]).val() == "")
  // {
  //     Service=0;
  // }

// InvoiceTypeID = $('#InvoiceTypeID').val();

  if($('#OPVAT_'+id[1]).val() == "")
  {
      OPVAT=0;
  }

  if($('#IPVAT_'+id[1]).val() == "")
  {
      IPVAT=0;
  }
   
// console.log("invoice:"+InvoiceTypeID);
// console.log(Fare);
// console.log(Service);
// console.log(total);

if(InvoiceTypeID==2)
{

  console.log("invoice if:"+InvoiceTypeID);

$('#Service_'+id[1]).val(0);
$('#Taxable_'+id[1]).val(0);
$('#TaxAmount_'+id[1]).val(0);



// alert(Total);
Discount = $('#discount_'+id[1]).val(); 

if(Discount!="")
{

Totalold = $('#total_'+id[1]).val();
Discount = $('#discount_'+id[1]).val(); 
console.log(Discount+'-'+Totalold);
Total1 = parseFloat(Totalold)- parseFloat(Discount);
console.log(Total1);
 $('#total_'+id[1]).val( Total1 );  
}

}

 


 calculateTotal();

 
});

 


$(document).on('change keyup blur','#tax',function(){
  calculateTotal();
});

//total price calculation 
function calculateTotal(){
  subTotal = 0 ; total = 0; 
  $('.totalLinePrice').each(function(){
    if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
  });
  $('#subTotal').val( subTotal.toFixed(2) );
  tax = $('#tax').val();
  if(tax != '' && typeof(tax) != "undefined" ){
    taxAmount = subTotal * ( parseFloat(tax) /100 );
    $('#taxAmount').val(taxAmount.toFixed(2));
    total = subTotal + taxAmount;
  }else{
    $('#taxAmount').val(0);
    total = subTotal;
  }
  $('#totalAftertax').val( total.toFixed(2) );
  calculateAmountDue();
}

$(document).on('change keyup blur','#amountPaid',function(){
  calculateAmountDue();
});

//due amount calculation
function calculateAmountDue(){
  amountPaid = $('#amountPaid').val();
  total = $('#totalAftertax').val();
  if(amountPaid != '' && typeof(amountPaid) != "undefined" ){
    amountDue = parseFloat(total) - parseFloat( amountPaid );
    $('.amountDue').val( amountDue.toFixed(2) );
  }else{
    total = parseFloat(total).toFixed(2);
    $('.amountDue').val( total);
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





<!-- ajax trigger -->
<script>
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
 
 



 

$(document).on('keyup','#Phone',function(){
  ajax_party_validate();
});




   
   function ajax_party_validate() {
      
       // alert($("#csrf").val());
 
  
       var Phone = $('#Phone').val();

       // alert(SupplierID);
       if(Phone!=""  ){
        /*  $("#butsave").attr("disabled", "disabled"); */
        // alert(SupplierID);
          $.ajax({
              url: "{{URL('/ajax_party_validate')}}",
              type: "POST",
              data: {
                  _token: $("#csrf").val(),
                   Phone: Phone,
                 
              },
              cache: false,
              success: function(data){
            

 
                if (data.total == 0) {
                            
                            $('#Phone').removeClass('border-red').addClass('border-green');
                            $('#submitButton').removeAttr('disabled');
                            

                                $('#email-error').text('validated successfully');
                                $("#email-error").css("color", "green");
                                $('#email-error').show();

                        } else {
                            $('#submitButton').attr('disabled','disabled');
                            $('#Phone').removeClass('border-green').addClass('border-red');
                            $("#email-error").css("color", "red");
                            $('#email-error').text('Phone no already exists');
                             $('#email-error').show();
                        }
                    $('#result').html(data);
              }
          });
      }
      else{
           $('#email-error').text('Phone number is required');
           $("#email-error").css("color", "red");
            $('#email-error').show();
      }

  }
 
</script>


<script>
  $(document).ready(function() {



  $('#PartyID').select2({
        allowClear: true,
        placeholder: 'This is my placeholder',    
          language: {
              noResults: function() {
                // console.log('no record ounf');
              return `<button style="width: 100%" type="button"
              class="btn btn-primary" 
              onClick='task()'>+ Add New Customer</button>
              </li>`;
              }
          },
        
          escapeMarkup: function (markup) {
              return markup;
          }
      });


      });



    
  function task()
  {
  // alert("Hello world! ");

 
            $('#PartyID').select2('close');


$('#exampleModal').modal('show');

  }
    


</script>


<script>
  $(document).ready(function() {
        $('#submitButton').click(function() {
             var isValid = true;
            
            // Validate the name field
            var PartyName = $('#PartyName').val().trim();
            if (PartyName === '') {
                $('#name').addClass('error');
                $('#name-error').show();
                isValid = false;
            } else {
                $('#name').removeClass('error');
                $('#name-error').hide();
            }
            
            // Validate the email field
            var Phone = $('#Phone').val().trim();
             if (Phone === '') {
                $('#email').addClass('error');
                $('#email-error').show();
                isValid = false;
            } else {
                $('#email').removeClass('error');
                $('#email-error').hide();
            }
            



            // If the form is valid, make the AJAX request
            if (isValid) {

                $.ajax({
                    url: '{{URL("/ajax_party_save")}}',  // Replace with your server endpoint
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Laravel's CSRF token
                        PartyName: PartyName,
                        Phone: Phone,
                    },
                    success: function(response) {
                        // Handle the response from the server


                        // alert('Form submitted successfully!');




                        console.log(response.PartyID);
                        console.log(response.PartyName);
                        console.log(response.Phone);


                        $("#PartyID").append('<option value=' + response.PartyID + ' selected >' + response.PartyID +'-'+ response.PartyName+'-'+ response.Phone +'</option>');
                        
                        $('#exampleModal').modal('hide');
                        
                        checkSelection();

                    },






                    error: function(xhr, status, error) {
                        // Handle any errors
                        alert('An error occurred: ' + error);
                    }
                });
            }
        });



$(document).on('keyup','#PartyName',function(){

var isValid = true;
            
            // Validate the name field
            var nameValue = $('#PartyName').val().trim();
            if (nameValue === '') {
                $('#name').addClass('error');
                $('#name-error').show();
                isValid = false;
            } else {
                $('#name').removeClass('error');
                $('#name-error').hide();
            }


  
});   



$(document).on('keyup','#Phone',function(){

         var isValid = true;
            
            // Validate the email field
            var emailValue = $('#Phone').val().trim();
            
            if (emailValue === '') {
                $('#email').addClass('error');
                $('#email-error').show();
                isValid = false;
            } else {
                $('#email').removeClass('error');
                $('#email-error').hide();
            }

  
});   



    });


</script>


<script>
  $(document).ready(function() {
    // Initialize select2
    $('#searchField').select2();

    // Event listener for input on select2 search field
    $(document).on('input', '.select2-search__field', function() {
        var searchValue = $(this).val().trim();

        // Check if the input is numeric or starts with '+'
        if (/^\d+$/.test(searchValue) || searchValue.startsWith('+')) {
            $('#Phone').val(searchValue);
            $('#PartyName').val('');
        } else {
            $('#PartyName').val(searchValue);
            $('#Phone').val('');
        }
    });
  });
</script>



 

{{-- submitButton   --}}
 <script>


$('#invoice_form').on('submit', function(e) {
        e.preventDefault();
        const btn = $("#submitButton");
        let formData = new FormData($("#invoice_form")[0]);

        // Validate form on the server side before uploading
        $.ajax({
            type: "POST",
            url: "{{URL('/InvoiceSave')}}", // Server-side validation route
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            beforeSend: function() {
                btn.prop('disabled', true);
                btn.html('Validating...');
            },
            success: function(res) {
                if (res.success) {
                    btn.prop('disabled', false);
                    btn.html('Save');
                    
                    notyf.success({
                        message: res.message,
                        duration: 3000
                    });

                    setTimeout(function() {
                        $('#progressModal').modal('hide'); // Hide modal after processing
                        window.location.href = res.redirect_url;
                    }, 1000);
                } else {
                    notyf.error({
                        message: res.message || 'An error occurred. Please try again.',
                        duration: 3000
                    });
                    btn.prop('disabled', false);
                    btn.html('Save');
                }
            },
            error: function(xhr, status, error) {
                // Handle validation error
                let errorMessage = xhr.responseJSON?.message || 'Validation failed. Please check the form and try again.';
                notyf.error({
                    message: errorMessage,
                    duration: 3000
                });
                btn.prop('disabled', false);
                btn.html('Save');
            }
        });
    });


 

 </script>

@endsection