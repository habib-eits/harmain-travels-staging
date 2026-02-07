@extends('tmp')
@section('title', $pagetitle)

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">

<!-- multipe image upload  -->
<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="multiple/dist/imageuploadify.min.css" rel="stylesheet">

<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->

            <!-- enctype="multipart/form-data" -->
            <form action="{{URL('/EstimateUpdate')}}" method="post">


                <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">

                <input type="hidden" name="EstimateMasterID" value="{{$estimate_master[0]->EstimateMasterID}}">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="password">Customer </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="PartyID" id="PartyID" class="form-select select2 mt-5" name="PartyID" required="">
                                            <option value="0">Select</option>
                                            <?php foreach ($party as $key => $value) : ?>
                                                <option value="{{$value->PartyID}}" {{($value->PartyID== $estimate_master[0]->PartyID) ? 'selected=selected':'' }}>{{$value->PartyName}}</option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-1 row " id="WalkinCustomer">
                                    <div class="col-sm-3">
                                        <label class="col-form-label text-danger" for="password">or Walkin Customer </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="WalkinCustomerName" value="{{$estimate_master[0]->WalkinCustomerName}}" placeholder="Walkin cusomter" id="1WalkinCustomerName">

                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="password">Salesperson </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="UserID" id="UserID" class="form-select">
                                            <option value="">Select</option>
                                            <?php foreach ($user as $key => $value) : ?>
                                                <option value="{{$value->UserID}}" {{($value->UserID== $estimate_master[0]->UserID) ? 'selected=selected':'' }}>{{$value->FullName}}</option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>

                           
                               
 
                            </div>
                            <div class="col-md-6">

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="password">Estimate# </label>
                                        </div>
                                        <div class="col-sm-9 pt-2">
                                            <input type="hidden" class="form-control" name="EstimateNo" value="{{$estimate_master[0]->EstimateNo}}">{{$estimate_master[0]->EstimateNo}}

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
                                                <input type="text" name="EstimateDate" autocomplete="off" class="form-control" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" data-date-container="#datepicker21" data-provide="datepicker" data-date-autoclose="true" value="{{$estimate_master[0]->EstimateDate}}">
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="contact-info">Expiry Date</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group" id="datepicker22">
                                                <input type="text" name="DueDate" autocomplete="off" class="form-control" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" data-date-container="#datepicker22" data-provide="datepicker" data-date-autoclose="true" value="{{$estimate_master[0]->ExpiryDate}}">
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="password">Reference No </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="first-name" class="form-control" name="ReferenceNo" value="{{$estimate_master[0]->ReferenceNo}}">

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
                                <table>
                                     <thead>
                                        <tr class=" borde-1 border-light " style="height: 40px;">
                                            <th width="2%" class="text-center"><input id="check_all" type="checkbox" /></th>
                                            <th width="50%">ITEM DETAILS </th>
 
                                            <th width="10%">QUANTITY</th>
                                            <th width="10%">RATE</th>
 
                                            <th width="10%">AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($estimate_detail as $key => $value1)
                                        <?php $no = $key + 1; ?>
                                       

                                        <tr class="p-3">
                                            <td class="p-3"><input class="case  text-center" type="checkbox" /></td>
                                            <td valign="top">

                                          
                                                  

                                                  <textarea name="Description[]" id="Description[]" rows="2" class="form-control" style="width: 100% !important;">{{$value1->Description}}</textarea>


                                            </td>
                                            


                                            <td valign="top">
                                                <input type="number" name="Qty[]" id="Qty_{{$no}}" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->Qty}}">
                                            </td>

                                            <td valign="top">
                                                <input type="number" name="Price[]" id="Price_{{$no}}" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->Rate}}">
                                            </td>

 
                                
                                        




                                            <td valign="top">
                                                <input type="number" name="ItemTotal[]" id="ItemTotal_{{$no}}" class=" form-control totalLinePrice " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->Total}}">
                                            </td>
                                        </tr>



                                        @endforeach
                                        <!-- end of for each -->

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-1 mb-2" style="margin-left: 29px;">
                            <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                                <button class="btn btn-danger delete" type="button"><i class="bx bx-trash align-middle font-medium-3 me-25"></i>Delete</button>
                                <button class="btn btn-success addmore" type="button"><i class="bx bx-list-plus align-middle font-medium-3 me-25"></i> Add More</button>

                            </div>

                            <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                                <div id="result"></div>

                            </div>
                            <br>

                        </div>


                        <div class="row mt-4">

                            <div class="col-lg-8 col-12  ">
                                <h6>Customer Notes: </h6>


                                <textarea class="form-control" rows='5' name="CustomerNotes" id="note" placeholder="">{{$estimate_master[0]->CustomerNotes}}</textarea>

                                <label for="" class="mt-2">Description</label>
                                <textarea class="form-control" rows='5' name="DescriptionNotes" id="note" placeholder="Description notes if any.">{{$estimate_master[0]->DescriptionNotes}}</textarea>

 
                                <div class="mt-2"><button type="submit" class="btn btn-success w-md float-right">Save</button>
                                    <a href="{{URL('/Estimate')}}" class="btn btn-secondary w-md float-right">Cancel</a>

                                </div>


                            </div>


                             <div class="col-lg-4 col-12 ">
                                <!-- <input type="text" class="form-control" id="TotalTaxAmount" name="TaxTotal" placeholder="TaxTotal" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"> -->
                                <form class="form-inline">
                                <div class="form-group mt-1">
                                        <label>Grand Total Tax: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>

                                            <input type="text" class="form-control" id="grandtotaltax" name="grandtotaltax" placeholder="Subtotal" value="{{$estimate_master[0]->Tax}}" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                                        </div>
                                    </div>
                                    <div class="form-group mt-1">
                                        <label>Sub Total: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>

                                            <input type="text" class="form-control" id="subTotal" name="SubTotal" placeholder="Subtotal" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$estimate_master[0]->SubTotal}}">
                                        </div>
                                    </div>
                                    <div class="form-group mt-1 d-none">
                                        <label>Discount: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">%</span>

                                            <input type="text" class="form-control" id="discountper" name="DiscountPer" placeholder="Tax" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$estimate_master[0]->DiscountPer}}">

                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>

                                            <input type="text" name="DiscountAmount" class="form-control" id="discountAmount" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$estimate_master[0]->Discount}}">
                                        </div>
                                    </div>



                                    <div class="form-group mt-1">

                                        <label>Total: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>
                                            <input type="number" name="Total" id="Total" class="form-control" step="0.01" id="totalafterdisc"   placeholder="Total" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$estimate_master[0]->Total}}">
                                        </div>
                                    </div>
                                    <div class="form-group mt-1 d-none">
                                        <label>Tax: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">%</span>

                                            <input type="text" class="form-control" id="taxpercentage" name="Taxpercentage" placeholder="tax %" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$estimate_master[0]->TaxPer}}">

                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>

                                            <input type="text" name="TaxpercentageAmount" class="form-control" id="taxpercentageAmount" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$estimate_master[0]->Tax}}">
                                        </div>
                                    </div>

                                    <div class="form-group mt-1 d-none">

                                        <label>Shipping: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>
                                            <input type="number" name="Shipping" class="form-control" step="0.01" id="shipping" placeholder="Grand Total" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$estimate_master[0]->Shipping}}">
                                        </div>
                                    </div>

                                    <div class="form-group mt-1 d-none">

                                        <label>Grand Total: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>
                                            <input type="number" name="Grandtotal" class="form-control" step="0.01" id="Grandtotal" placeholder="Grand Total"   onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$estimate_master[0]->GrandTotal}}">
                                        </div>
                                    </div>



                          
                            </div>
                        </div>
                        <div>



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



<script>
    $('input[name=tax_action]').change(function(e) {
        $('.exclusive_cal').val(e.target.value)
    })


    /**
     * Site : http:www.smarttutorials.net id="Qty_' + i + '"
     * @author muni
     */

    var i = $('table tr').length;
 
    $(".addmore").on('click', function() {
         
        html = ` <tr class="p-3">
                                          <td bordercolor="1" class="p-1    text-center"><input class="case" type="checkbox" /></td>

                                          <td valign="top">

                                              
                                           <textarea name="Description[]  " id="Description[]" rows="2" class="form-control " style="width: 100% !important;"></textarea></td>


                                             <td valign="top">
                                          <input type="number" name="Qty[]" id="Qty_${i}" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="1">                                            </td>

                                            <td valign="top">
                                          <input type="number" name="Price[]" id="Price_${i}" class=" form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01">                                            </td>
                                          
 
                                            <td valign="top">
                                          <input type="number" name="ItemTotal[]" id="ItemTotal_${i}" class=" form-control totalLinePrice " autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01">                                            </td>

                                        </tr>`;
         i++;
        $('table').append(html);
        $('.changesNoo', 'table').select2();
        

 

    });





    //to check all checkboxes
    $(document).on('change', '#check_all', function() {
        $('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
    });

 


    function km(v, id) {

        // alert(v+id);

        id_arr = 'ItemID0_' + id;
        id = id_arr.split("_");

        val = $('#ItemID0_' + id[1]).val().split("|");


        // alert($('#ItemID0_'+id[1]).val());
        $('#ItemID_' + id[1]).val(val[0]);

        // alert('val done');

        var data = <?php echo $item; ?>;
        // console.log(data);

        // console.log( "readaay!" );

        var data = <?php echo $item; ?> // this is dynamic data in json_encode(); from controller

        // console.log($('#ItemID_' + id[1]).val());


        var item_idd = $('#ItemID_' + id[1]).val();
        // console.log(item_idd);
        var index = -1;
        var val = parseInt(item_idd);
        var json = data.find(function(item, i) {
            if (item.ItemID === val) {
                index = i + 1;
                return i + 1;
            }
        });
            



        
        $('#Price_' + id[1]).val(json["SellingPrice"]);
        $('#TaxID_' + id[1]).val(json["Percentage"]);

        var Qty = $('#Qty_' + id[1]).val();
        var Price = $('#Price_' + id[1]).val();
        var QtyRate = parseFloat(Price) * parseFloat(Qty);


      
        var DiscountType = $('#DiscountType_' + id[1]).val();



        var Discount = $('#Discount_' + id[1]).val();




       if(DiscountType==1)
       {


        var DiscountCalculated=  (parseFloat(QtyRate) * parseFloat(Discount) / 100  ).toFixed(2);
       }
       else
       {
        var DiscountCalculated= parseFloat(Discount) ;
       }
      
         $('#DiscountAmount_' + id[1]).val( DiscountCalculated   );


        var Gross=  parseFloat(QtyRate)-parseFloat(DiscountCalculated);

         $('#Gross_' + id[1]).val( Gross   );

       
 var TaxID = $('#TaxID_' + id[1]).val();

 var TaxCalculation =  ((parseFloat(Gross)* parseFloat(TaxID))/100).toFixed(2);

  $('#TaxVal_' + id[1]).val( TaxCalculation   );


var ItemTotal = parseFloat(Gross)-parseFloat(TaxCalculation);

  $('#ItemTotal_' + id[1]).val( ItemTotal   ); 



 var grandtotaltax = 0;

$('.totalLinePrice2').each(function() {
            if ($(this).val() != '') grandtotaltax += parseFloat($(this).val());
        });

$('#grandtotaltax').val((parseFloat(grandtotaltax)).toFixed(2));


    TaxIncExc();



        calculateTotal();

        if (isNaN($('#discountAmount').val())) {
            $('#discountAmount').val(0);
        }

        calculatediscount();
        calculateTotal();
 TaxIncExc();


    }



 



    //deletes the selected table rows
    $(".delete").on('click', function() {
        $('.case:checkbox:checked').parents("tr").remove();
        $('#check_all').prop("checked", false);
        calculatediscount();
        calculateTotal();
    });




    


    //price change
    $(document).on('change keyup blur ', '.changesNo', function() {



     singlerowcalculation($(this).attr('id'));

 



     
    });

    //////////

function singlerowcalculation(idd)



{

         id_arr = idd;
        id = id_arr.split("_");

     


        var Qty = $('#Qty_' + id[1]).val();
        var Price = $('#Price_' + id[1]).val();
        var QtyRate = parseFloat(Price) * parseFloat(Qty);




        
         $('#ItemTotal_' + id[1]).val(QtyRate);



    subTotal = 0;
        $('.totalLinePrice').each(function() {
            if ($(this).val() != '') subTotal += parseFloat($(this).val());
        });




$('#subTotal').val((subTotal).toFixed(2));


var vat = parseFloat(subTotal *.05).toFixed(2);


var Total = parseFloat(vat)+parseFloat(subTotal);

$('#grandtotaltax').val(vat);
$('#Total').val(Total).toFixed(2);
$('#Grandtotal').val(Total).toFixed(2);
  


}

// 

function TaxIncExc()
{
        var TaxType = $('#TaxType').val();
        // var subTotal = $('#subTotal').val();
        var DiscountAmount = $('#discountAmount').val();
        var grandtotaltax = 0;

      


                var table_lenght = $('table tr').length - 1;
 
                
                var Qty = 0
                var Price = 0;
                var TaxVal = 0;
                var Gross = 0;
                for (let i = 1; i <= table_lenght; i++) {
                   
                    Qty = $('#Qty_' + i).val();
                    Price = $('#Price_' + i).val();
                    TaxVal = $('#TaxVal_' + i).val();
                    Gross = $('#Gross_' + i).val();

                  $('#ItemTotal_' + i).val(  parseFloat (Gross)  );   

                }    



        $('.totalLinePrice2').each(function() {
        if ($(this).val() != '') grandtotaltax += parseFloat($(this).val());
        });

        subTotal = 0;
        $('.totalLinePrice').each(function() {
            if ($(this).val() != '') subTotal += parseFloat($(this).val());
        });



                if(TaxType =='TaxInclusive')
                {

                           subTotal1 = (parseFloat(subTotal)-parseFloat(TaxVal)).toFixed(2);
                            $('#subTotal').val(subTotal1);    

                          var Total =  parseFloat(subTotal1).toFixed(2)-parseFloat(DiscountAmount).toFixed(2);

                         $('#Total').val(parseFloat(Total).toFixed(2));  
                         $('#Grandtotal').val(parseFloat(Total)+parseFloat(grandtotaltax));  

                }
                else
                {
                   
                    $('#subTotal').val(parseFloat(subTotal).toFixed(2));    

                    var Total =  parseFloat(subTotal)-parseFloat(DiscountAmount).toFixed(2);
                    var Grandtotal =  ((parseFloat(Total)+parseFloat(grandtotaltax))).toFixed(2);
                    
                 
                    $('#Total').val(Total);  
                    $('#Grandtotal').val(Grandtotal);  

                }

}
    

    $(document).on('change', '.changesNoo', function() {



        id_arr = $(this).attr('id');
        id = id_arr.split("_");

        val = $('#ItemID0_' + id[1]).val().split("|");


        // alert($('#ItemID0_'+id[1]).val());
        $('#ItemID_' + id[1]).val(val[0]);


        calculatediscount();

    });

    ////////////////////////////////////////////

    function calculatediscount() {
        
        subTotal = parseFloat($('#subTotal').val());

grandtotaltax = $('#grandtotaltax').val();
        discountper = $('#discountper').val();
         
        if (discountper != '' && typeof(discountper) != "undefined") {
            discountamount = parseFloat(subTotal) * (parseFloat(discountper) / 100);

            $('#discountAmount').val(parseFloat(discountamount.toFixed(2)));
            total = parseFloat(subTotal).toFixed(2) - parseFloat(discountamount).toFixed(2);
            $('#Total').val(total.toFixed(2));
            $('#Grandtotal').val(parseFloat(total)+parseFloat(grandtotaltax));


        } else {
            $('#discountper').val(0);
            // alert('dd');
            $('#DiscountAmount').val(0);
            total = (subTotal).toFixed(2);
             

        }
  $('#Grandtotal').val(total+parseFloat($('#grandtotaltax').val()));
 
    }


    $(document).on('blur', '#discountAmount', function() {


        calculatediscountper();
       

    });

    function calculatediscountper() {
 
        subTotal = parseFloat($('#subTotal').val());


        discountAmount = $('#discountAmount').val();
        // totalafterdisc = $('#totalAftertax').val();
        // console.log('testing'.totalAftertax);
        if (discountAmount != '' && typeof(discountAmount) != "undefined") {
            discountper = (parseFloat(discountAmount) / parseFloat(subTotal)) * 100;

            $('#discountper').val(parseFloat(discountper.toFixed(2)));

            total = subTotal - discountAmount;
            $('#Total').val(total.toFixed(2));
            // $('#grandtotal').val(total.toFixed(2));
            $('#Grandtotal').val(total+parseFloat($('#grandtotaltax').val()));

        } else {
            $('#discountper').val(0);
            // alert('dd');
            // $('#discountper').val(0);
            total = subTotal;
            $('#Total').val(total.toFixed(2));

        }

        $('#Grandtotal').val(total+parseFloat($('#grandtotaltax').val()));
 
    }

    //////////////////

    // discount percentage
    $(document).on(' blur ', '#discountper', function() {
        calculatediscount();
       

    });
    $(document).on('change keyup blur   onclick', '#taxpercentage', function() {
        calculateTotal();
    });


    $(document).on('change keyup blur   onclick', '#shipping', function() {
        calculateTotal();
    });



    //total price calculation 
    function calculateTotal() {

        // grand_tax = 0;

        
        subTotal = $('#subTotal').val();
        grandtotaltax=$('#grandtotaltax').val();      
        discountAmount = $('#discountAmount').val();
        Total = parseFloat(subTotal)-parseFloat(discountAmount);
        Grandtotal = parseFloat(Total) + parseFloat(grandtotaltax);

        $('#Total').val(Total);
        $('#Grandtotal').val(Grandtotal);


 
    }




    $(document).on('change keyup blur', '#amountPaid', function() {
        calculateAmountDue();
    });

    //due amount calculation
    function calculateAmountDue() {
        amountPaid = $('#amountPaid').val();
        total = $('#grandtotal').val();
        if (amountPaid != '' && typeof(amountPaid) != "undefined") {
            amountDue = parseFloat(total) - parseFloat(amountPaid);
            $('.amountDue').val(amountDue.toFixed(2));
        } else {
            total = parseFloat(total).toFixed(2);
            $('.amountDue').val(total);
        }
    }


    //It restrict the non-numbers
    var specialKeys = new Array();
    specialKeys.push(8, 46); //Backspace
    function IsNumeric(e) {
        var keyCode = e.which ? e.which : e.keyCode;
        // console.log(keyCode);
        var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
        return ret;
    }

    //datepicker
    $(function() {
        $.fn.datepicker.defaults.format = "dd-mm-yyyy";
        $('#invoiceDate').datepicker({
            startDate: '-3d',
            autoclose: true,
            clearBtn: true,
            todayHighlight: true
        });
    });
</script>

<!-- <script src="{{asset('assets/js/jquery-3.6.0.js')}}" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script> -->

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
            }
        });
    });


    //]]>
</script>
<script type="text/javascript">
    //<![CDATA[


    $(function() {
        $('#paymentdetails').hide();
        $('#PaymentMode').change(function() {

            if (this.options[this.selectedIndex].value == 'Cheque') {
                // alert('dd');

                $('#paymentdetails').show();
                $('#PaymentDetails').focus();

            } else {
                $('#paymentdetails').hide();
                $('#PaymentDetails').val('');
            }
        });
    });


    //]]>
</script>
<!-- ajax trigger -->
<script>
    function ajax_balance(SupplierID) {

        // alert($("#csrf").val());

        $('#result').prepend('')
        $('#result').prepend('<img id="theImg" src="{{asset('
            assets / images / ajax.gif ')}}" />')

        var SupplierID = SupplierID;

        // alert(SupplierID);
        if (SupplierID != "") {
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
                success: function(data) {



                    $('#result').html(data);



                }
            });
        } else {
            alert('Please Select Branch');
        }




    }
</script>

<script>
    $(function() {
    
          var InvoiceType = $('#InvoiceType').val();

        // console.log(InvoiceType);
        if (InvoiceType != "") {
            /*  $("#butsave").attr("disabled", "disabled"); */
            // alert('next stage if else');
            // console.log(InvoiceType);

            $.ajax({

                url: "{{URL('/ajax_invoice_vhno')}}",
                type: "POST",
                data: {
                    // _token: p3WhH7hWcpfbcxtNskY1ZrCROfa3dpKp3MfEJwXu,
                    "_token": $("#csrf").val(),
                    InvoiceType: InvoiceType,

                },
                cache: false,

                success: function(data) {

                    // alert(data.success);
                    $('#invoict_type').html(data);



                }
            });
        }


});

</script>



<script>
    
    $("#TaxType").change(function() {

       TaxIncExc();

    });






</script>
 



<script>
    $("#InvoiceType").change(function() {

        // alert(p3WhH7hWcpfbcxtNskY1ZrCROfa3dpKp3MfEJwXu);

        var InvoiceType = $('#InvoiceType').val();

        // console.log(InvoiceType);
        if (InvoiceType != "") {
            /*  $("#butsave").attr("disabled", "disabled"); */
            // alert('next stage if else');
            // console.log(InvoiceType);

            $.ajax({

                url: "{{URL('/ajax_invoice_vhno')}}",
                type: "POST",
                data: {
                    // _token: p3WhH7hWcpfbcxtNskY1ZrCROfa3dpKp3MfEJwXu,
                    "_token": $("#csrf").val(),
                    InvoiceType: InvoiceType,

                },
                cache: false,

                success: function(data) {

                    // alert(data.success);
                    $('#invoict_type').html(data);



                }
            });
        }

    });
</script>
<script type="text/javascript">
    function GetSelectedTextValue(seletedVal) {
        gTotalVal = $('#grandtotal').val();
        if (gTotalVal) {


            var txt;
            if (confirm("Are you sure you want to update tax of complete invoice!")) {
                txt = "You pressed OK!";

                var TaxValue = seletedVal.value;

                var table_lenght = $('table tr').length;
                let discountamount = 0;


                var grandsum = 0
                var taxsum = 0;
                for (let i = 1; i < table_lenght; i++) {
                    Qty = $('#Qty_' + i).val();
                    Price = $('#Price_' + i).val();


                    $('#TaxID_' + i).val(TaxValue);
                    disPerLine = parseFloat(Price) * (TaxValue / 100);
                    $('#TaxVal_' + i).val(parseFloat(disPerLine));

                    grandsum += (Qty * Price) + disPerLine;
                    taxsum += disPerLine;

                    $('#ItemTotal_' + i).val((Qty * Price) + disPerLine);

                }
                $('#grandtotaltax').val(parseFloat(taxsum));
                // assigning subtotal value
                $('#subTotal').val(parseFloat(grandsum));


                // fetching discount percentage
                var discountper = $('#discountper').val();
                // calculating discount amount
                discountamount = parseFloat(grandsum) * (parseFloat(discountper) / 100);
                $('#discountAmount').val(parseFloat(discountamount));
                //amount after discount
                $('#totalafterdisc').val(parseFloat(grandsum) - parseFloat(discountamount));

                // fetching percentage of tax
                var taxper = $('#taxpercentage').val();
                // calculating percentage amount
                taxamount = parseFloat(grandsum) * (parseFloat(taxper) / 100);
                $('#taxpercentageAmount').val(parseFloat(taxamount));

                //calculating shiping cost
                var shipping = $('#shipping').val();



                var grandtotal = (parseFloat(grandsum) + parseFloat(taxamount) + parseFloat(shipping)) - parseFloat(discountamount);
                // Calculating grandtotal
                $('#grandtotal').val(grandtotal);
                // alert(discountamount);
            } else {
                $('#seletedVal').val('select');
            }

        } else {
            return alert("Please create invoice first");
        }
    }
</script>


 <script>
   $( document ).ready(function() {
  $('body').addClass('sidebar-enable vertical-collpsed')

});
 </script>

<script src="{{asset('assets/js/myapp.js')}}" type="text/javascript"></script>

<!-- END: Content-->

@endsection