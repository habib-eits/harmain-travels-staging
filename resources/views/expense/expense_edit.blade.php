@extends('template.tmp')
@section('title', $pagetitle)

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- <script src="{{asset('assets/invoice/js/jquery-1.11.2.min.js')}}"></script>
<script src="{{asset('assets/invoice/js/jquery-ui.min.js')}}"></script>
<script src="js/ajax.js"></script> -->
<!-- 
<script src="{{asset('assets/invoice/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/invoice/js/bootstrap-datepicker.js')}}"></script>  -->


<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">

<!-- multipe image upload  -->
<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="multiple/dist/imageuploadify.min.css" rel="stylesheet">

<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="{{asset('assets/libs/parsleyjs/parsley.min.js')}}"></script>
<script src="{{asset('assets/js/pages/form-validation.init.js')}}"></script>

        <link rel="stylesheet" type="text/css" href="{{asset('assets/src/jquery.modallink-1.0.0.css')}}" />



<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->

            <!-- enctype="multipart/form-data" -->
            <form action="{{URL('/ExpenseUpdate')}}" method="post">

<input type="hidden" name="ExpenseMasterID" value="{{$expense_master[0]->ExpenseMasterID}}">
                <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">


                <div class="card shadow-sm">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-1  row">
                                    <div class="col-sm-3 ">
                                        <label class="col-form-label" for="password">Supplier </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="SupplierID" id="SupplierID" class="form-select select2 mt-5"  >
                                            <option value="">Select</option>
                                            <?php foreach ($supplier as $key => $value) : ?>
                                                <option value="{{$value->PartyID}}" {{($value->PartyID== $expense_master[0]->SupplierID) ? 'selected=selected':'' }}>{{$value->PartyName}}</option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-1 row " id="WalkinCustomer">
                                    <div class="col-sm-3">
                                        <label class="col-form-label text-danger" for="password">or Walkin Customer </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="WalkinCustomerName" value="" placeholder="Walkin cusomter" id="1WalkinCustomerName">

                                    </div>
                                </div>
                              
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="password">Paid Through </label>
                                    </div>
                                    <div class="col-sm-9">
                                         <select name="ChartOfAccountID_From" id="ChartOfAccountID_From" class="form-select form-control-sm select2   " style="width: 100% !important;">
                                                    <option value="">select</option>
                                                    @foreach ($chartofaccount as $key => $value)
                                                    <option value="{{$value->ChartOfAccountID }}" {{($value->ChartOfAccountID== $expense_master[0]->ChartOfAccountID) ? 'selected=selected':'' }}>{{$value->ChartOfAccountID}}-{{$value->ChartOfAccountName}}</option>
                                                    @endforeach
                                                </select>

                                    </div>
                                </div>
                                  <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label text-danger" for="password">Invoice #  </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="ReferenceNo" autocomplete="off" class="form-control" value="{{$expense_master[0]->ReferenceNo}}">

                                        </div>
                                    </div>
                                </div>
                      


                            </div>
                            <div class="col-md-6">

                        

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label text-danger" for="password">Expense # </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div id="invoict_type"> <input type="text" name="ExpenseNo" autocomplete="off" class="form-control" value="{{$expense_master[0]->ExpenseNo}}" readonly=""></div>


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
                                                <input type="text" name="Date" autocomplete="off" class="form-control" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" data-date-container="#datepicker21" data-provide="datepicker" data-date-autoclose="true" value="{{$expense_master[0]->Date}}">
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
                            <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                                <table>
                                    <thead>
                                          <tr class="bg-light borde-1 border-light " style="height: 40px;">
                                            <th width="2%" class="text-center"><input id="check_all" type="checkbox" /></th>
                                            <th width="1%">EXPENSE ACCOUNT </th>
                                            <th width="10%">NARRATION </th>

                                            
                                            <th width="4%">Tax RATE</th>
                                            <th width="4%">Exclusive Value</th>
                                            <th width="4%">VAT</th>

                                            <th width="4%">Inclusive Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                   @foreach($expense_detail as $key=> $value1)
                                   <?php $no = $key + 1; ?>
                                        <tr class="p-3">
                                            <td class="p-1 bg-light borde-1 border-light text-center"><input class="case" type="checkbox" /></td>

                                            <td>

                                                <select name="ItemID0[]" id="ItemID0_{{$no}}" class="item form-select form-control-sm select2   changesNoo " onchange="km(this.value,<?php echo $no; ?>);" style="width: 300px !important;">
                                                    <option value="">select</option>
                                                    @foreach ($chartofaccount as $key => $value)

                                                    <option value="{{$value->ChartOfAccountID }}" {{($value->ChartOfAccountID== $value1->ChartOfAccountID) ? 'selected=selected':'' }}>{{$value->ChartOfAccountID}}-{{$value->ChartOfAccountName}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="ChartOfAccountID[]" id="ItemID_{{$no}}" value="{{$value1->ChartOfAccountID}}">
                                            </td>


                                            <td>
                                                <input type="text" name="Description[]" id="Description_{{$no}}" class=" form-control " value="{{$value1->Notes}}">
                                            </td>

                                                 <td>
                                                <select name="Tax[]" id="TaxID_1" class="form-select changesNo" required="">
                                                    <?php foreach ($tax as $key => $valueX1) : ?>
                                                        <option value="{{$valueX1->TaxPer}}" {{($valueX1->TaxPer== $value1->TaxPer) ? 'selected=selected':'' }}>{{$valueX1->Description}}</option>
                                                    <?php endforeach ?>
                                                </select>
                                            </td>


                                              <td >
                                                <input type="number" name="Amount[]" id="Amount_1" class=" form-control changesNo exclusive" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="{{$value1->Amount}}" required="">
                                            </td>





                                                    <td>
                                                <input type="number" name="TaxVal[]" id="TaxVal_1" class=" form-control totalLinePrice2 tax changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" required="" value="{{$value1->Tax}}">
                                            </td>

                                            <td>
                                                <input type="number" name="AmountAfterTax[]" id="AmountAfterTax_1" class=" form-control totalLinePrice  changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" required="" value="{{$value1->AmountAfterTax}}">
                                            </td>
                                        </tr>
@endforeach

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
                               

                          

                                <div class="mt-2"><button type="submit" class="btn btn-success w-md float-right">Save</button>
                                    <a href="{{URL('/Expense')}}" class="btn btn-secondary w-md float-right">Cancel</a>
                                    <a href="{{URL('/Attachment'.'/'.$expense_master[0]->ExpenseNo)}}" class="modal-link btn btn-success">Attach file</a>

                                </div>







                            </div>


                                   <div class="col-lg-4 col-12 ">
                                <!-- <input type="text" class="form-control" id="TotalTaxAmount" name="TaxTotal" placeholder="TaxTotal" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"> -->
                                <form class="form-inline">

                                      <div class="form-group mt-1">
                                        <label>Value before Tax: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>

                                            <input type="text" class="form-control" id="subTotal" name="SubTotal" placeholder="Subtotal" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$expense_master[0]->SubTotal}}" required="">
                                        </div>
                                    </div>



                                    <div class="form-group mt-1">
                                        <label>VAT: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>

                                            <input type="text" class="form-control" id="grandtotaltax" name="grandtotaltax" placeholder="Subtotal" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$expense_master[0]->Tax}}" required="">
                                        </div>
                                    </div>
                                  
                                   


 
                                   

                                    <div class="form-group mt-1">

                                        <label>Value After Tax: &nbsp;</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">{{session::get('Currency')}}</span>
                                            <input type="number" name="Grandtotal" class="form-control" step="0.01" id="grandtotal" placeholder="Grand Total" readonly onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="{{$expense_master[0]->GrantTotal}}" required="">
                                        </div>
                                    </div>



                                   

                            </div>
                        </div>
                        <div>



                        </div>





            </form>

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




<script>
    $('input[name=tax_action]').change(function(e) {
        $('.exclusive_cal').val(e.target.value)
    })


    /**
     * Site : http:www.smarttutorials.net
     * @author muni
     */
    var i = $('table tr').length;
    $(".addmore").on('click', function() {
        html = '<tr class="  border-1 border-light">';
        html += '<td class="p-1 text-center"><input class="case" type="checkbox"/></td>';
        html += '<td><select name="ItemID0[]" id="ItemID0_' + i + '"  style="width: 300px !important;" class="form-select select2 changesNoo" onchange="km(this.value,' + i + ');" required=""> <option value="">select</option>}@foreach ($items as $key => $value) <option value="{{$value->ChartOfAccountID }}|{{$value->ChartOfAccountName}}">{{$value->ChartOfAccountID }}-{{$value->ChartOfAccountName}}</option>@endforeach</select><input type="hidden" name="ChartOfAccountID[]" id="ItemID_' + i + '"></td>';



        html += '  <td><input type="text" name="Description[]" id="Description_' + i + '" class=" form-control " ></td>';

 
        html += '<td><select name="Tax[]" id="TaxID_' + i + '" class="form-select changesNo"><?php foreach ($tax as $key => $valueX1) : ?><option value="{{$valueX1->TaxPer}}">{{$valueX1->Description}}</option><?php endforeach ?></select></td>';

        html += '<td><input type="number" name="Amount[]" id="Amount_'+i+'" class=" form-control changesNo exclusive" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01" value="0"></td>';

        html += '<td><input type="number" name="TaxVal[]" id="TaxVal_' + i + '" class=" form-control totalLinePrice2 tax changesNo "autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" step="0.01"></td>';

 



        html += '<td><input type="text" name="AmountAfterTax[]" id="AmountAfterTax_' + i + '" class="form-control totalLinePrice  changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';

        html += '</tr>';
        $('table').append(html);
        $('.select2', 'table').select2();
        i++;


 

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






        calculateTotal();

        if (isNaN($('#discountAmount').val())) {
            $('#discountAmount').val(0);
        }

        calculatediscount();
        calculateTotal();



    }








 


    //deletes the selected table rows
    $(".delete").on('click', function() {
        $('.case:checkbox:checked').parents("tr").remove();
        $('#check_all').prop("checked", false);
        calculateTotal();
    });




    


       //price change
    $(document).on('change keyup blur ', '.changesNo', function() {

        id_arr = $(this).attr('id');
        id = id_arr.split("_");

 

        TaxPer = $('#TaxID_' + id[1]).val();

        Amount = $('#Amount_' + id[1]).val();

 
        TotalVal = ( (parseFloat(TaxPer) / 100) * parseFloat(Amount) ).toFixed(2);


 


         $('#TaxVal_' + id[1]).val(TotalVal);



        AmountAfterTax = parseFloat(TotalVal) + parseFloat(Amount);

         $('#AmountAfterTax_' + id[1]).val(AmountAfterTax);


    




       
        calculateTotal();
    });

    //////////

    $(document).on(' blur', '.totalLinePrice', function() {



        id_arr = $(this).attr('id');
        id = id_arr.split("_");



        total = $('#ItemTotal_' + id[1]).val();

taxper =  $('#TaxID_' + id[1]).val();
 

       tax =( total *taxper )/ 100;
 
        $('#TaxVal_' + id[1]).val(tax);



  taxtotal = 0;
        $('.tax').each(function() {
            if ($(this).val() != '') taxtotal += parseFloat($(this).val());
        });


 

$('#grandtotaltax').val(taxtotal);

 gtotal = 0;
        $('.totalLinePrice').each(function() {
            if ($(this).val() != '') gtotal += parseFloat($(this).val());
        });
 

 $('#grandtotal').val(gtotal);
  calculateTotal();
        // Profit = (parseFloat(total)-parseFloat(Fare)).toFixed(2) ;

        // Tax = ;

        // Service = (parseFloat(Proft)-parseFloat(Tax)).toFixed(2) ;

        // alert(Profit+Tax+Service);

        // $('#quantity'+id[1]).val( Tax );
        // $('#Service_'+id[1]).val( Service );



    });

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
        subTotal = 0;
        $('.totalLinePrice').each(function() {
            if ($(this).val() != '') subTotal += parseFloat($(this).val());
        });
        subTotal = parseFloat($('#subTotal').val());


        discountper = $('#discountper').val();
        // totalafterdisc = $('#totalAftertax').val();
        // console.log('testing'.totalAftertax);
        if (discountper != '' && typeof(discountper) != "undefined") {
            discountamount = parseFloat(subTotal) * (parseFloat(discountper) / 100);

            $('#discountAmount').val(parseFloat(discountamount.toFixed(2)));
            total = subTotal - discountamount;
            $('#totalafterdisc').val(total.toFixed(2));
            // $('#grandtotal').val(total.toFixed(2));

        } else {
            $('#discountper').val(0);
            // alert('dd');
            $('#DiscountAmount').val(0);
            total = subTotal;
            $('#totalafterdisc').val(total.toFixed(2));

        }

    }


    $(document).on('blur', '#discountAmount', function() {


        calculatediscountper();

    });

    function calculatediscountper() {
        subTotal = 0;

        $('.totalLinePrice').each(function() {
            if ($(this).val() != '') subTotal += parseFloat($(this).val());
        });
        subTotal = parseFloat($('#subTotal').val());


        discountAmount = $('#discountAmount').val();
        // totalafterdisc = $('#totalAftertax').val();
        // console.log('testing'.totalAftertax);
        if (discountAmount != '' && typeof(discountAmount) != "undefined") {
            discountper = (parseFloat(discountAmount) / parseFloat(subTotal)) * 100;

            $('#discountper').val(parseFloat(discountper.toFixed(2)));
            total = subTotal - discountAmount;
            $('#totalafterdisc').val(total.toFixed(2));
            // $('#grandtotal').val(total.toFixed(2));

        } else {
            $('#discountper').val(0);
            // alert('dd');
            $('#discountper').val(0);
            total = subTotal;
            $('#totalafterdisc').val(total.toFixed(2));

        }

    }

    //////////////////

    // discount percentage
    $(document).on('change keyup blur onmouseover onclick', '#discountper', function() {
        calculatediscount();


        calculateTotal();

    });
    $(document).on('change keyup blur   onclick', '#taxpercentage', function() {
        calculateTotal();
    });


    $(document).on('change keyup blur   onclick', '#shipping', function() {
        calculateTotal();
    });



    //total price calculation 
     //total price calculation 
    function calculateTotal() {

        // grand_tax = 0;
        grand_tax = 0;
        subTotal = 0;
        total = 0;
        total2 = 0;
        sumtax = 0;
        gt = 0;
        grandtotaltax = 0;
        var pretotal = 0;

        $('.exclusive').each(function() {
            if ($(this).val() != '') subTotal += parseFloat($(this).val());
        });

        $('#subTotal').val(subTotal.toFixed(2));

        $('.totalLinePrice2').each(function() {
            if ($(this).val() != '') grandtotaltax += parseFloat($(this).val());
        });

        $('#grandtotaltax').val(grandtotaltax.toFixed(2));
        console.log(grandtotaltax);
       

           $('#grandtotal').val(subTotal+grandtotaltax);

       
    
     

 

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

 <script type="text/javascript" src="{{asset('assets/src/jquery.modalLink-1.0.0.js')}}"></script>

 <script type="text/javascript">

            (function () {
                $(".modal-link").modalLink();

                 
            })();

        </script>

<!-- END: Content-->

@endsection