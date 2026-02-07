 @extends('html.template')

@section('title', 'Accounting MIS')

@section('content')


    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700|Noto+Serif:400,700"> 
    <!-- Bootstrap core CSS -->
    <link href="{{asset('assets/invoice/css/jquery-ui.min.css')}}" rel="stylesheet">
     <link href="{{asset('assets/invoice/css/datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/invoice/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/invoice/css/style.css')}}" rel="stylesheet">

 
   <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-extended.min.css')}}">
    <!-- Custom styles for this template -->
    <link href="{{asset('assets/invoice/css/sticky-footer-navbar.css')}}" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="{{asset('assets/invoice/js/ie.js')}}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/plugins/forms/pickers/form-flat-pickr.min.css')}}">

        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

   <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/select/select2.min.css')}}">
    <!-- END: Vendor CSS-->
 
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-extended.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/colors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/components.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/themes/dark-layout.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/themes/bordered-layout.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/themes/semi-dark-layout.min.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/core/menu/menu-types/vertical-menu.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/plugins/forms/pickers/form-flat-pickr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/app-invoice.min.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    <!-- END: Custom CSS-->



    <style type="text/css">
      
      .form-control, .form-select
      {
        border-radius: 0 !important;
        
      }

    </style>

 

 

    

    <!-- Begin page content -->
    <div class="container-fluid" class="mt-4" >


<!-- enctype="multipart/form-data" -->
<form action="{{URL('/InvoiceSave')}}" method="post"> 

 
      <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">

 
 <div class="card shadow-sm">
     <div class="card-body">
     
 

<div class="row">
  <div class="col-6"> <img src="{{asset('assets/images/logo/ft.png')}}" alt="">


<br>
<br>
<div class="col-6">
  <label for="">Invoice Type</label>
 <select class="js-example-basic-single" name="InvoiceType">
   <?php foreach ($invoice_type as $key => $value): ?>
     <option value="{{$value->InvoiceTypeID}}">{{$value->InvoiceTypeCode}}-{{$value->InvoiceType}}</option>
   <?php endforeach ?>
</select> 

<div class="clearfix mt-1"></div>
 <label for="">Party</label>

<select name="PartyID" id="PartyID" class="js-example-basic-single mt-5">
 <?php foreach ($supplier as $key => $value): ?>
     <option value="{{$value->SupplierID}}">{{$value->SupplierName}}</option>
   <?php endforeach ?>
</select>
</div>
  </div>
  <div class="col-2">  </div>
  <div class="col-4">


    <div class="row">
              <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="first-name">Invoice #</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text" id="first-name" class="form-control" name="VHNO" value="{{$vhno[0]->VHNO}}" >
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="email-id">Date</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text"  name="Date" class="form-control invoice-edit-input date-picker flatpickr-input active" readonly="readonly">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="contact-info">Due Date</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text"  name="DueDate" class="form-control invoice-edit-input date-picker flatpickr-input active" readonly="readonly">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="password">Payment Mode </label>
                  </div>
                  <div class="col-sm-9">
                    <select name="PaymentMode" id="PaymentMode" class="form-select">
                  <option value="Cash">Cash</option>
                  <option value="Credit Card">Credit Card</option>
                  <option value="Bank Transfer">Bank Transfer</option>
           
                </select>
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
            <table    >
          <thead>
            <tr class="bg-light borde-1 border-light "  style="height: 40px;">
              <th width="2%" class="p-1"><input id="check_all"  type="checkbox"/></th>
              <th width="5%">Item</th>
              <th width="15%">Supplier</th>
              <th width="5%">Ref No</th>
              <th width="5%">Visa </th>
              <th width="10%">PAX Name</th>
              <th width="8%">PNR</th>
              <th width="5%">Sector</th>
              <th width="5%">Fare</th>
              <th width="5%">Tax%</th>
              <th width="5%">Service</th>
              <th width="5%">O/P Vat</th>
              <th width="5%">I/P VAT</th>
              <th width="6%">Tax</th>
              <th width="10%">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr class="p-3">
              <td class="p-1 bg-light borde-1 border-light"><input class="case" type="checkbox"/></td>
              <td>

                 <select name="ItemID0[]" id="ItemID0_1" class="form-select form-control-sm   changesNoo">
                  @foreach ($items as $key => $value) 
                    <option value="{{$value->ItemID}}|{{$value->Percentage}}">{{$value->ItemCode}}-{{$value->ItemName}}-{{$value->Percentage}}</option>
                  @endforeach
                 </select>
                 <input type="hidden" name="ItemID[]" id="ItemID_1">
              </td>
              <td> <select name="SupplierID[]" id="SupplierID_1" class="form-select changesNo" onchange="ajax_balance(this.value);">
                   @foreach ($supplier as $key => $value) 
                    <option value="{{$value->SupplierID}}">{{$value->SupplierName}}</option>
                  @endforeach
                 </select>

                </td>

                <td>
                  <input type="text" name="RefNo[]" id="RefNo_1" class="form-control     changesNo" autocomplete="off"   >
                </td>

                <td>
                  <input type="text" name="VisaType[]" id="VisaType_1" class="   form-control changesNo" autocomplete="off"  >
                </td>
                <td>
                  <input type="text" name="PaxName[]" id="PaxName_1" class=" form-control changesNo" autocomplete="off"  >
                </td>
                <td>
                  <input type="text" name="PNR[]" id="PNR_1" class=" form-control changesNo" autocomplete="off" >
                </td>
                <td>
                  <input type="text" name="Sector[]" id="Sector_1" class=" form-control changesNo" autocomplete="off" >
                </td>
              <td>
                <input type="number" name="Fare[]" id="Fare_1" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" >
              </td>
              <td>
                <input type="number" name="Taxable[]"  id="Taxable_1" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" >
              </td>
              <td>
                <input type="number" name="Service[]" id="Service_1" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01">
              </td>
              <td>
                <input type="number" name="OPVAT[]" id="OPVAT_1" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01">
              </td>
              <td>
                <input type="number" name="IPVAT[]" id="IPVAT_1" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01">
              </td>
               
              
              
              
              <td>
                <input type="number" name="TaxAmount[]" id="quantity_1" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01">
              </td>
              <td>
                <input type="number" name="Total[]" id="total_1" class=" form-control totalLinePrice" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01">
              </td>
            </tr>


          </tbody>
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


        <div class="row">
          
          <div class="col-lg-8 col-12  "><h5>Notes: </h5>
         
          
                  <textarea class="form-control" rows='5' name="remarks" id="notes" placeholder="Your Notes"></textarea> 
                  
           
         

           <div class="mt-2"><button type="submit" class="btn btn-success w-lg float-right">Save</button>
            <a href="{{URL('/')}}" class="btn btn-secondary w-lg float-right">Cancel</a>

       </div>


        </div>


          <div class="col-lg-4 col-12 ">   <form class="form-inline">
          <div class="form-group">
            <div class="input-group">
              <input type="hidden" class="form-control" id="subTotal" name="subTotal" placeholder="Subtotal" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <input type="hidden" class="form-control" id="tax"   placeholder="Tax" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <input type="hidden" class="form-control" id="taxAmount" placeholder="Tax" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
            </div>
          </div>
          <div class="form-group">
            
            <label><h5>Total: &nbsp;</h5></label>
            <div class="input-group">
<span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>              
              <input type="number" class="form-control" step="0.01" id="totalAftertax" placeholder="Total" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
            </div>
          </div>
          
          <div class="form-group mt-1">
            <label><h5>Amount Paid: &nbsp;</h5></label>
            <div class="input-group">
<span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>              
              <input type="number" class="form-control" id="amountPaid"  name="amountPaid" placeholder="Amount Paid" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01">
            </div>
          </div>
          
          <div class="form-group mt-1">
            
            <label><H5>Amount Due: &nbsp;</H5></label>
            <div class="input-group">
<span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>              
              <input type="number" class="form-control amountDue" name="amountDue"  id="amountDue" placeholder="Amount Due" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01">
            </div>
          </div>
   
      </div></div> <div >


      
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
 
      
  
        
       
      </form>
 


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
  html = '<tr class="bg-light borde-1 border-light ">';
  html += '<td class="p-1"><input class="case" type="checkbox"/></td>';
  html += '<td><select name="ItemID0[]" id="ItemID0_'+i+'" class="form-select changesNoo"> @foreach ($items as $key => $value) <option value="{{$value->ItemID}}|{{$value->Percentage}}">{{$value->ItemCode}}-{{$value->ItemName}}-{{$value->Percentage}}</option>@endforeach</select><input type="hidden" name="ItemID[]" id="ItemID_'+i+'"></td>';



  // html += '<td><select name="ItemID[]" id="ItemID_'+i+'" class="form-select changesNoo"><option value="">Select Item</option><option value="">b</option></select></td>';
  html += '<td><select name="SupplierID[]" id="SupplierID_'+i+'"  onchange="ajax_balance(this.value);" class="form-select">@foreach ($supplier as $key => $value) <option value="{{$value->SupplierID}}">{{$value->SupplierName}}</option>@endforeach</select></td>';
  html += '<td><input type="text" name="RefNo[]" id="RefNo_'+i+'" class="form-control changesNo" ></td>';
  html += '<td><input type="text" name="VisaType[]" id="VisaType_'+i+'" class="form-control changesNo" ></td>';
  html += '<td><input type="text" name="PaxName[]" id="PaxName_'+i+'" class="form-control changesNo" ></td>';
  html += '<td><input type="text" name="PNR[]" id="PNR_'+i+'" class="form-control changesNo" ></td>';
  html += '<td><input type="text" name="Sector[]" id="Sector_'+i+'" class="form-control changesNo" ></td>';
  html += '<td><input type="text" name="Fare[]" id="Fare_'+i+'" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="Taxable[]" id="Taxable_'+i+'" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="Service[]" id="Service_'+i+'" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="OPVAT[]" id="OPVAT_'+i+'" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="IPVAT[]" id="IPVAT_'+i+'" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="TaxAmount[]" id="quantity_'+i+'" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="Total[]" id="total_'+i+'" class="form-control totalLinePrice" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '</tr>';
  $('table').append(html);
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

//autocomplete script
$(document).on('focus','.autocomplete_txt',function(){
  type = $(this).data('type');
  
  if(type =='productCode' )autoTypeNo=0;
  if(type =='productName' )autoTypeNo=1;  
  
  $(this).autocomplete({
    source: function( request, response ) {  
       var array = $.map(prices, function (item) {
                 var code = item.split("|");
                 return {
                     label: code[autoTypeNo],
                     value: code[autoTypeNo],
                     data : item
                 }
             });
             //call the filter here
             response($.ui.autocomplete.filter(array, request.term));
    },
    autoFocus: true,          
    minLength: 2,
    select: function( event, ui ) {
      var names = ui.item.data.split("|");            
      id_arr = $(this).attr('id');
        id = id_arr.split("_");
      $('#itemNo_'+id[1]).val(names[0]);
      $('#itemName_'+id[1]).val(names[1]);
      $('#quantity_'+id[1]).val(1);
      $('#price_'+id[1]).val(names[2]);
      $('#total_'+id[1]).val( 1*names[2] );
      calculateTotal();
    }           
  });
});

//price change
$(document).on('change keyup blur','.changesNo',function(){

 
 

  id_arr = $(this).attr('id');
  id = id_arr.split("_");
  quantity = $('#quantity_'+id[1]).val();
  price = $('#price_'+id[1]).val();
  

  Fare = $('#Fare_'+id[1]).val();

  Taxable = $('#Taxable_'+id[1]).val();

  Service = $('#Service_'+id[1]).val();

  OPVAT = $('#OPVAT_'+id[1]).val();

  IPVAT = $('#IPVAT_'+id[1]).val();


  

  if($('#Fare_'+id[1]).val() == "")
  {
      Fare=0;
  }
   

  if($('#Taxable_'+id[1]).val() == "")
  {
      Taxable=0;
      TaxResult =0;
  }
  else
  {
     TaxResult = ( (parseFloat(Taxable)*parseFloat(Service))/100  ).toFixed(2);

  }
   

  if($('#Service_'+id[1]).val() == "")
  {
      Service=0;
  }

  if($('#OPVAT_'+id[1]).val() == "")
  {
      OPVAT=0;
  }

  if($('#IPVAT_'+id[1]).val() == "")
  {
      IPVAT=0;
  }
   

 

  if( Fare!='' && Service !='' ) 
$('#quantity_'+id[1]).val( TaxResult ) ;
  $('#total_'+id[1]).val( ( parseFloat(Fare)+parseFloat(Service)+parseFloat(OPVAT)+parseFloat(IPVAT) ).toFixed(2) );  
  // +parseFloat(TaxResult)
  calculateTotal();
});

//////////

$(document).on('change','.changesNoo',function(){

 

  id_arr = $(this).attr('id');
  id = id_arr.split("_");

val = $('#ItemID0_'+id[1]).val().split("|");


// alert($('#ItemID0_'+id[1]).val());
$('#Taxable_'+id[1]).val( val[1]  );
$('#ItemID_'+id[1]).val( val[0]  );
  
 

 
});

////////////////////////////////////////////


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

     <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

 <script>
   // In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    $('.js-example-basic-single').select2();
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
 
</script>




 

  
 
@endsection



