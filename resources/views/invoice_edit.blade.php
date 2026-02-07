@extends('tmp')

@section('title', 'Invoice')
 

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">


 @php
 

 $company = DB::table('company')->first();
 @endphp

 
        <!-- Responsive datatable examples -->
 

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <style type="text/css">
      
      .form-control, .form-select
      {
        border-radius: 0 !important;
        
      }

 

    </style>


<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                         <strong class="text-end"> </strong> 
         

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
  <div class="card shadow-sm">
      <div class="card-body">
  

<!-- enctype="multipart/form-data" -->

  <form id="invoice_form" method="post">

 
      <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">

 
 <div class="">
     <div class="">
     
 

<div class="row">
  <div class="col-6"> 

 
<div class="col-6">
  <label for="">Invoice Type</label>
 <select class="form-select select2" name="InvoiceTypeID" id="InvoiceTypeID" required="">
   <?php foreach ($invoice_type as $key => $value): ?>
     <option value="{{$value->InvoiceTypeID}}" {{($value->InvoiceTypeID== $invoice_mst[0]->InvoiceTypeID) ? 'selected=selected':'' }}>{{$value->InvoiceTypeCode}}-{{$value->InvoiceType}}</option>
   <?php endforeach ?>
</select> 

<div class="clearfix mt-1"></div>
 <label for="">Party</label>

<select name="PartyID" id="PartyID" class="form-select select2 mt-5" name="PartyID" required="">
 <?php foreach ($party as $key => $value): ?>
     <option value="{{$value->PartyID}}" {{($value->PartyID== $invoice_mst[0]->PartyID) ? 'selected=selected':'' }}>{{$value->PartyID}}-{{$value->PartyName}}-{{$value->Phone}}</option>
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
                    <input type="text" id="first-name" class="form-control" name="VHNO" value="{{$invoice_mst[0]->InvoiceMasterID}}" >
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="email-id">Date</label>
                  </div>
                  <div class="col-sm-9">
                      {{-- <input type="text" name="Date"  autocomplete="off" class="form-control" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" data-date-container="#datepicker21" data-provide="datepicker" data-date-autoclose="true" value=""> --}}
  
                      <input type="date" name="Date" class="form-control" value="{{$invoice_mst[0]->Date}}">

                    
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="contact-info">Due Date</label>
                  </div>
                  <div class="col-sm-9">

                      {{-- <input type="text" name="DueDate"  autocomplete="off" class="form-control" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" data-date-container="#datepicker22" data-provide="datepicker" data-date-autoclose="true"  value="{{$invoice_mst[0]->DueDate}}"> --}}
                      <input type="date" name="DueDate" class="form-control"  value="{{$invoice_mst[0]->DueDate}}">
 
                  </div>
                </div>
              </div>
              <input type="hidden"  name="PaymentMode"  vlaue="{{$invoice_mst[0]->PaymentMode}}">
              {{-- <div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="password">Payment Mode </label>
                  </div>
                  <div class="col-sm-9">
                    <select name="PaymentMode" id="PaymentMode" class="form-select">
                  <option value="Cash" {{($invoice_mst[0]->PaymentMode=='Cash') ? 'selected=selected':'' }}>Cash</option>
                  <option value="ENBD Bank" {{($invoice_mst[0]->PaymentMode=='ENBD Bank') ? 'selected=selected':'' }}>ENBD Bank</option>
                  <option value="ADCB Bank" {{($invoice_mst[0]->PaymentMode=='ADCB Bank') ? 'selected=selected':'' }}>ADCB Bank</option>
                  <option value="Credit Card" {{($invoice_mst[0]->PaymentMode=='Credit Card') ? 'selected=selected':'' }}>Credit Card</option>
           
                </select>
                  </div>
                </div>
              </div> --}}

<div class="col-12">
                <div class="mb-1 row">
                  <div class="col-sm-3">
                    <label class="col-form-label" for="password">Salesman </label>
                  </div>
                  <div class="col-sm-9">
                    <select name="SalemanID" id="SalemanID" class="form-select">
                  <?php foreach ($saleman as $key => $value): ?>
                    
                    <option value="{{$value->UserID}}" {{($value->UserID== $invoice_mst[0]->UserID) ? 'selected=selected':'' }}>{{$value->FullName}}</option>
                  <?php endforeach ?>
                  
                  
           
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
              <th width="5%">VAT%</th>
              <th width="5%">Service</th>
              <th width="5%" class="d-none" >O/P Vat</th>
              <th width="5%" class="d-none" >I/P VAT</th>
              <th width="6%">VAT</th>
              <th width="4%">Dis</th>
              <th width="10%">Total</th>
            </tr>
          </thead>
          <tbody>

            @foreach($invoice_det as $key => $value1)
            <?php $no= $key+1; ?>
            <tr class="p-3">
              <td class="p-1 bg-light borde-1 border-light"><input class="case" type="checkbox"/></td>
              <td>

                 <select name="ItemID0[]" id="ItemID0_{{$no}}" class="form-select select2 form-control-sm   changesNoo" style="width:200px">
                  @foreach ($items as $key => $value) 
                    <option value="{{$value->ItemID}}" data-tax="{{$value->Percentage ?? 0}}" {{($value->ItemID== $value1->ItemID) ? 'selected=selected':'' }}  >{{$value->ItemCode}}-{{$value->ItemName}}-{{$value->Percentage}}</option>
                  @endforeach
                 </select>
                 
              </td>
              <td> <select name="SupplierID[]" id="SupplierID_{{$no}}" class=" form-select select2 changesNo" onchange="ajax_balance(this.value);" style="width:200px">
                   @foreach ($supplier as $key => $value) 
                    <option value="{{$value->PartyID}}" {{($value->PartyID== $value1->SupplierID) ? 'selected=selected':'' }}>{{$value->PartyType}}-{{$value->PartyID}}-{{$value->PartyName}}</option>
                  @endforeach
                 </select>

                </td>

                <td>
                  <input type="text" name="RefNo[]" id="RefNo_{{$no}}" class="form-control     changesNo" autocomplete="off"  value="{{$value1->RefNo}}" >
                </td>

                <td>
                  <input type="text" name="VisaType[]" id="VisaType_{{$no}}" class="   form-control changesNo" autocomplete="off" value="{{$value1->VisaType}}"  >
                </td>
                <td>
                  <input type="text" name="PaxName[]" id="PaxName_{{$no}}" class=" form-control changesNo" autocomplete="off"  value="{{$value1->PaxName}}" >
                </td>
                <td>
                  <input type="text" name="PNR[]" id="PNR_{{$no}}" class=" form-control changesNo" autocomplete="off"  value="{{$value1->PNR}}">
                </td>
                <td>
                  <input type="text" name="Sector[]" id="Sector_{{$no}}" class=" form-control changesNo" autocomplete="off" value="{{$value1->Sector}}" >
                </td>
              <td>
                <input type="number" name="Fare[]" id="Fare_{{$no}}" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01"  value="{{$value1->Fare}}">
              </td>
              <td>
                <input type="number" name="Taxable[]"  id="Taxable_{{$no}}" class=" form-control  changesNo " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="5" >
              </td>
              <td>
                <input type="number" name="Service[]" id="Service_{{$no}}" class=" form-control" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->Service}}">
              </td>
              <td class="d-none">
                <input type="number" name="OPVAT[]" id="OPVAT_{{$no}}" class=" form-control " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->OPVAT}}">
              </td>
              <td class="d-none">
                <input type="number" name="IPVAT[]" id="IPVAT_{{$no}}" class=" form-control " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->IPVAT}}">
              </td>
               
              
              
              
              <td>
                <input type="number" name="TaxAmount[]" id="TaxAmount_{{$no}}" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->Taxable}}">
              </td>
              <td>
                <input type="number" name="Discount[]" id="discount_{{$no}}" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->Discount}}">
              </td>
              <td>
                <input type="number" name="ItemTotal[]" id="total_{{$no}}" class=" form-control totalLinePrice changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->Total}}">
              </td>
            </tr>


<script>
  
  
  
       $('#SupplierID_' + {{$no}}).select2();
 </script>


            
            @endforeach

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
         
          
                  <textarea class="form-control" rows='5' name="Note" id="notes" placeholder="Your Notes">{{$invoice_mst[0]->Note}}</textarea> 
                  
           
         

           <div class="mt-2"><button type="submit" class="btn btn-success w-lg float-right" id="submitButton" >Save</button>
            <a href="{{URL('/Invoice')}}" class="btn btn-secondary w-lg float-right">Cancel</a>

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
              <input type="number" name="Total" class="form-control" step="0.01" id="totalAftertax" placeholder="Total" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$invoice_mst[0]->Total}}">
            </div>
          </div>
          
          <div class="form-group mt-1">
            <label><h5>Amount Paid: &nbsp;</h5></label>
            <div class="input-group">
<span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>              
              <input readonly type="number" class="form-control" id="amountPaid-"  name="amountPaid" placeholder="Amount Paid" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$invoice_mst[0]->Paid}}"> 
            </div>
          </div>
          
          <div class="form-group mt-1">
            
            <label><H5>Amount Due: &nbsp;</H5></label>
            <div class="input-group">
<span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>              
              <input type="number" class="form-control amountDue" name="amountDue"  id="amountDue" placeholder="Amount Due" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$invoice_mst[0]->Balance}}"> 
            </div>
          </div>
                    
          <div class="form-group mt-1">
            
            <label><H5>Voucher: &nbsp;</H5></label>
            <div class="input-group">
<span class="input-group-text bg-light">VHNO</span>              
              <input  readonly type="text" class="form-control" name="Voucher"  placeholder="Voucher" value="{{$invoice_mst[0]->Voucher}}"> 
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
 


      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>



 

   

 
 






    <script src="{{asset('assets/invoice/js/jquery-1.11.2.min.js')}}"></script>
    <script src="{{asset('assets/invoice/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/invoice/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/invoice/js/bootstrap-datepicker.js')}}"></script>
    <!-- <script src="js/ajax.js"></script> -->

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

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
  html += '<td><select name="ItemID0[]" id="ItemID0_'+i+'" class="form-select select2 changesNoo"> @foreach ($items as $key => $value) <option value="{{$value->ItemID}}" data-tax="{{$value->Percentage ?? 0}}">{{$value->ItemCode}}-{{$value->ItemName}}-{{$value->Percentage}}</option>@endforeach</select></td>';



  // html += '<td><select name="ItemID[]" id="ItemID_'+i+'" class="form-select changesNoo"><option value="">Select Item</option><option value="">b</option></select></td>';
  html += '<td><select name="SupplierID[]" id="SupplierID_'+i+'"  onchange="ajax_balance(this.value);" class="js-example-basic-single form-select" style="width:200px">@foreach ($supplier as $key => $value) <option value="{{$value->PartyID}}">{{$value->PartyType}}-{{$value->PartyID}}-{{$value->PartyName}}</option>@endforeach</select></td>';
  html += '<td><input type="text" name="RefNo[]" id="RefNo_'+i+'" class="form-control  " ></td>';
  html += '<td><input type="text" name="VisaType[]" id="VisaType_'+i+'" class="form-control " ></td>';
  html += '<td><input type="text" name="PaxName[]" id="PaxName_'+i+'" class="form-control " ></td>';
  html += '<td><input type="text" name="PNR[]" id="PNR_'+i+'" class="form-control " ></td>';
  html += '<td><input type="text" name="Sector[]" id="Sector_'+i+'" class="form-control " ></td>';
  html += '<td><input type="text" name="Fare[]" id="Fare_'+i+'" class="form-control  " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="Taxable[]" id="Taxable_'+i+'" class="form-control changesNo   " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="5"></td>';
  html += '<td><input type="text" name="Service[]" id="Service_'+i+'" class="form-control  " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td class="d-none"><input type="text" name="OPVAT[]" id="OPVAT_'+i+'" class="form-control  changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td class="d-none"><input type="text" name="IPVAT[]" id="IPVAT_'+i+'" class="form-control  changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="TaxAmount[]" id="TaxAmount_'+i+'" class="form-control changesNo " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="Discount[]" id="discount_'+i+'" class="form-control changesNo " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
  html += '<td><input type="text" name="ItemTotal[]" id="total_'+i+'" class="form-control totalLinePrice changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
 


  
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
$(document).on('blur','.changesNo',function(){

 
 

  InvoiceTypeID = $("#InvoiceTypeID option:selected").val();
  
  id_arr = $(this).attr('id');
  id = id_arr.split("_");


if(InvoiceTypeID==1)
{

   price = $('#price_'+id[1]).val() || 0;
  

  Fare = $('#Fare_'+id[1]).val() || 0;
  Total = $('#total_'+id[1]).val() || 0;

  Taxable = $('#Taxable_'+id[1]).val() || 0;

  Service = parseFloat(Total) - parseFloat(Fare);

 
     // TaxAmount = ( (parseFloat(Taxable)*parseFloat(Service))/100  ).toFixed(2);
          // TaxAmount = ( (5*parseFloat(Service))/(100+5)  ).toFixed(2);


  TaxPercentage = parseFloat ($('#ItemID0_'+id[1]).find('option:selected').data('tax') ) || 0;
  console.log(TaxPercentage);

$('#Taxable_'+id[1]).val(TaxPercentage);

TaxAmount = ( ( parseFloat(TaxPercentage) *parseFloat(Service))/(100+  parseFloat(TaxPercentage) )  ).toFixed(2);



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

//////////

// $(document).on(' blur','.totalLinePrice',function(){

 

//   id_arr = $(this).attr('id');
//   id = id_arr.split("_");

 
// Fare = $('#Fare_'+id[1]).val();

//   total = $('#total_'+id[1]).val();


//   Tax = $('#Taxable_'+id[1]).val();





 
// Profit = ( parseFloat(total)-parseFloat(Fare)).toFixed(2);

   

//    if($('#Taxable_'+id[1]).val() == "")
//   {
//       Tax=0;
//   }
// $('#Service_'+id[1]).val( parseFloat(Profit) - ( parseFloat(Profit/100)*parseFloat(Tax)).toFixed(2) );

//    $('#quantity_'+id[1]).val( ( parseFloat(Profit/100)*parseFloat(Tax)).toFixed(2) );
//    // Profit = (parseFloat(total)-parseFloat(Fare)).toFixed(2) ;

//     // Tax = ;

//    // Service = (parseFloat(Proft)-parseFloat(Tax)).toFixed(2) ;

//    // alert(Profit+Tax+Service);

// // $('#quantity'+id[1]).val( Tax );
// // $('#Service_'+id[1]).val( Service );


 
// });

// $(document).on('change','.changesNoo',function(){

 

//   id_arr = $(this).attr('id');
//   id = id_arr.split("_");

// val = $('#ItemID0_'+id[1]).val().split("|");


// // alert($('#ItemID0_'+id[1]).val());
// $('#Taxable_'+id[1]).val( val[1]  );
// $('#ItemID_'+id[1]).val( val[0]  );
  
 

 
// });

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


<script src="{{asset('assets/js/jquery-3.6.0.js')}}" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script type="text/javascript">
    //<![CDATA[


    $(function() {
        $('#WalkinCustomer').hide();
        $('#PartyID').change(function() {

            if (this.options[this.selectedIndex].value == '1') {
                // alert('dd');

                $('#WalkinCustomer').show();
                $('#1WalkinCustomerName').focus();

            } else {
                $('#WalkinCustomer').hide();
                $('#1WalkinCustomerName').val(0);
                $('#1WalkinCustomerMobile').val(0);
            }
        });
    });


    //]]>
</script>

  
<script>


  $('#invoice_form').on('submit', function(e) {
          e.preventDefault();
          const btn = $("#submitButton");
          let formData = new FormData($("#invoice_form")[0]);
  
          // Validate form on the server side before uploading
          $.ajax({
              type: "POST",
              url: "{{URL('/InvoiceUpdate')}}", // Server-side validation route
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
 
    <!-- END: Content-->
 
  @endsection



