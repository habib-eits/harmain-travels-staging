 


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


        var DiscountCalculated=  (parseFloat(QtyRate) * parseFloat(Discount) / 100  );
       }
       else
       {
        var DiscountCalculated= parseFloat(Discount) ;
       }
      
         $('#DiscountAmount_' + id[1]).val( DiscountCalculated   );


        var Gross=  parseFloat(QtyRate)-parseFloat(DiscountCalculated);

         $('#Gross_' + id[1]).val( Gross   );

       
 var TaxID = $('#TaxID_' + id[1]).val();

 var TaxCalculation =  (parseFloat(Gross)* parseFloat(TaxID))/100;

  $('#TaxVal_' + id[1]).val( TaxCalculation   );


var ItemTotal = parseFloat(Gross)-parseFloat(TaxCalculation);

  $('#ItemTotal_' + id[1]).val( ItemTotal   ); 



 var grandtotaltax = 0;

$('.totalLinePrice2').each(function() {
            if ($(this).val() != '') grandtotaltax += parseFloat($(this).val());
        });

$('#grandtotaltax').val(parseFloat(grandtotaltax));


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

 



       calculatediscount();
        calculateTotal();
           TaxIncExc();
    });

    //////////

function singlerowcalculation(idd)
{   
     TaxIncExc();
       id_arr = idd;
        id = id_arr.split("_");

        Qty = $('#Qty_' + id[1]).val();

        TaxPer = $('#TaxID_' + id[1]).val();

        Price = $('#Price_' + id[1]).val();


        var Qty = $('#Qty_' + id[1]).val();
        var Price = $('#Price_' + id[1]).val();
        var QtyRate = parseFloat(Price) * parseFloat(Qty);




        
        var DiscountType = $('#DiscountType_' + id[1]).val();



        var Discount = $('#Discount_' + id[1]).val();




       if(DiscountType==1)
       {


        var DiscountCalculated=  (parseFloat(QtyRate) * parseFloat(Discount) / 100  );
       }
       else
       {
        var DiscountCalculated= parseFloat(Discount) ;
       }
      
         $('#DiscountAmount_' + id[1]).val( DiscountCalculated   );


        var Gross=  parseFloat(QtyRate)-parseFloat(DiscountCalculated);

         $('#Gross_' + id[1]).val( Gross   );

       
 var TaxID = $('#TaxID_' + id[1]).val();

 var TaxCalculation =  (parseFloat(Gross)* parseFloat(TaxID))/100;

  $('#TaxVal_' + id[1]).val( TaxCalculation   );


  $('#ItemTotal_' + id[1]).val( Gross-TaxCalculation    ); 

        var grandtotaltax = 0;

$('.totalLinePrice2').each(function() {
            if ($(this).val() != '') grandtotaltax += parseFloat($(this).val());
        });

$('#grandtotaltax').val(parseFloat(grandtotaltax));


     TaxIncExc();




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

                           subTotal1 = parseFloat(subTotal)-parseFloat(TaxVal);
                            $('#subTotal').val(subTotal1);    

                          var Total =  parseFloat(subTotal1)-parseFloat(DiscountAmount).toFixed(2);

                         $('#Total').val(parseFloat(Total));  
                         $('#Grandtotal').val(parseFloat(Total)+parseFloat(grandtotaltax));  

                }
                else
                {
                   
                    $('#subTotal').val(parseFloat(subTotal));    

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


        discountper = $('#discountper').val();
         
        if (discountper != '' && typeof(discountper) != "undefined") {
            discountamount = parseFloat(subTotal) * (parseFloat(discountper) / 100);

            $('#discountAmount').val(parseFloat(discountamount.toFixed(2)));
            total = subTotal - discountamount;
            $('#Total').val(total.toFixed(2));
            $('#Grandtotal').val(total.toFixed(2)+parseFloat($('#grandtotaltax').val()));


        } else {
            $('#discountper').val(0);
            // alert('dd');
            $('#DiscountAmount').val(0);
            total = subTotal;
             

        }
 $('#Total').val(total.toFixed(2));
 $('#Grandtotal').val(total.toFixed(2)+parseFloat($('#grandtotaltax').val()));
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
    } //<![CDATA[


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






    
    $("#TaxType").change(function() {

       TaxIncExc();

    });







 




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


     



 
   $( document ).ready(function() {
  $('body').addClass('sidebar-enable vertical-collpsed')

});
 


