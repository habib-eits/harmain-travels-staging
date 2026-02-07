@extends('template.tmp')
@section('title', 'Umrah Invoices')

@section('content')
<style>
    /* Hide action-links by default */
    #table tbody tr .action-links {
        display: none;
     }

    /* Show action-links when hovering over the entire row */
    #table tbody tr:hover .action-links {
        display: block;
    }

     #table tbody td {
        vertical-align: top;
    }

    /* Always reserve space for the links, but hide them */
    #table tbody tr .action-links {
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.2s ease;
        height: 1.5em; /* adjust to your link height! */
    }
    

    /* On hover, show the links without affecting layout */
    #table tbody tr:hover .action-links {
        visibility: visible;
        opacity: 1;
    }

    /* Ensure consistent row height by fixing padding */
    #table tbody tr td {
        padding-top: 4px;
        padding-bottom: 4px;
    }


    #table tbody tr {
    height: 60px; /* example */
}


    
</style>
</style>


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        @if(session('error'))
    <div class="alert alert-{{ session('class') }}">
        {{ session('error') }}
    </div>
@endif
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All Umrah Invoices</h3>

                            <div class="page-title-right d-flex">

                                <div class="page-btn">
                                    <a href="{{route('umrah-invoice-master.create')}}" class="btn btn-added btn-primary" ><i class="me-2"></i>Add New Umrah Invoice Master</a>
                                </div>  
                            </div>



                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">
                                <table id="table" class="table table-striped table-sm " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th width="200">Pax Name</th>
                                            <th width="250">Customer</th>
                                            <th>Pax Type</th>
                                            <th>Pax Passport</th>
                                            <th>PNR</th>
                                            <th>Visa No</th>
                                            <th>Package Name</th>
                                           
                                         </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

       
 
<!-- Large modal example -->
<!-- Modal for Payment Form -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title me-3" id="paymentModalLabel">Payment for INV</h5> <!-- Use the 'me-3' class for margin-end -->
                    <div><span id="invoiceType" class="badge bg-danger pl-3"></span></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
               
               <form method="POST"  id="paymentForm">
                    @csrf
                <div class="modal-body">

                            <div class="mb-3 row">
                            <label for="customerName" class="col-md-2 col-form-label fw-bold">Invoice Type</label>
                            <div class="col-md-4">
                                <select name="InvoiceType" id="InvoiceType" class="form-select">
                                    <option value="1">Sale Invoice</option>
                                    <option value="2">Sale Refund</option>
                                </select>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <label for="customerName" class="col-md-2 col-form-label fw-bold">Customer Name</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="customerName" name="customer_name" value="">
                            </div>

                             <label for="paymentNumber" class="col-md-2 col-form-label fw-bold">Payment #</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="InvoiceMasterID" name="InvoiceMasterID">
                                <input class="form-control" type="text" id="PartyID" name="PartyID" value="">
                            </div>

                            
                        </div>
                        
                        
                    

                       
                        <hr>


                        <div class="mb-3 row">
                            <label for="amountReceived" class="col-md-2 col-form-label fw-bold">Invoice Amount
                                ({{env('APP_CURRENCY')}})</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="Total" name="Total" value="" readonly="">
                            </div>


                        </div>
                        <div class="mb-3 row">

                            <label for="amountReceived" class="col-md-2 col-form-label fw-bold">Balance
                                ({{env('APP_CURRENCY')}})</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="Balance" name="Balance" value="" readonly="">
                            </div>
                            <label for="amountReceived" class="col-md-2 col-form-label fw-bold">Bank Charges (if
                                any)</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="bankCharges" name="bank_charges" value="" >
                            </div>


                        </div>


                        <div class="mb-3 row">
                            <label for="amountReceived" class="col-md-2 col-form-label fw-bold" id="amount_received">Amount Received
                                ({{env('APP_CURRENCY')}})</label>
                            <div class="col-md-4">
                                <input class="form-control" min="1" type="number" id="amountReceived" name="amount_received"
                                    value="" >
                               
                            </div>

                            <label for="bankCharges" class="col-md-2 col-form-label fw-bold" id="label">Choose
                                Account</label>
                            <div class="col-md-4 " id="bank_charges_div">
                                <select name="ChartOfAccountID" id="ChartOfAccountID" class="form-select"
                                    style="width: 100% !important;">
                                                                        <option  value="560110">BANK CHARGES</option>
                                                                    </select>

                            </div>


                        </div>




                        <div class="row">


                          
                                <div class="col-md-2 ml-2">
                                    <label class="col-form-label" for="email-id">Date</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group" id="datepicker21">
                                        <input type="date" name="Date"  class="form-control"
                                          
                                            
                                            value="2025-07-01">
                                       
                                    </div>
                                </div>

                                <div class="clearfix mb-3"></div>
                     
                            <label for="payment-mode" class="col-md-2 col-form-label fw-bold">Payment Mode</label>
                            <div class="col-md-4">
                                <select name="payment_mode" id="payment-mode" class="form-select">
                                    <option value="">Select</option>
                                    <option value="CASH">CASH</option>
                                    <option value="BANK">BANK</option>
                                    <option value="CARD">CARD</option>
                                </select>
                                <span id="PaymentModeError" style="color: red; display: none;">Please select a payment mode</span>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3 row">
                            <label for="deposit-to" class="col-md-2 col-form-label fw-bold">Deposit To</label>
                            
                            <div class="col-md-4">
                                <select name="deposit_to" id="deposit-to" class="form-select"></select>
                            </div>
                            
                            <!-- Hidden input to store ChartOfAccountName -->
                            <input type="hidden" id="selectedAccountName" name="selectedAccountName" value="">
                            




                            <label for="voucher-number" class="col-md-2 col-form-label fw-bold">Voucher#</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="voucherNumber" name="voucher_number"
                                    value="">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="notes" class="col-md-2 col-form-label fw-bold">Notes</label>
                            <div class="col-md-4">
                                <textarea class="form-control" id="notes" name="notes"></textarea>
                            </div>
                        </div>

                          {{-- <div class="mb-3 row">
                            <label for="notes" class="col-md-2 col-form-label fw-bold">VHNO</label>
                            <div class="col-md-4" id="vhno">
                                e
                            </div>  <div class="col-md-4" id="payment_mode">
                                ff
                            </div>

                        </div> --}}
                        
                       
                        
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="file" class="col-form-label fw-bold">Attachments</label>
                                <input id="file" class="form-control" type="file" name="file[]" multiple
                                    accept=".jpg, .jpeg, .png, .pdf">
                                <small class="text-muted">You can upload a maximum of 5 files, 5MB each</small>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="partyID" id="partyID" value="">
                    <input type="hidden" id="invoiceTypeID" name="InvoiceTypeID" value="">
                    <div class="modal-footer">
                        <button type="submit" class="btn-disable btn btn-primary me-1 waves-effect waves-light"
                            id="payment-btn">Record Payment</button>
                        <button id="modelClose" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

 <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>


    <!-- END: Content-->

<script>
$(document).on('click', '.viewlink', function(e) {


     e.preventDefault();

    var InvoiceMasterID = $(this).data('invoicemasterid');
    var PartyName = $(this).data('partyname');

 
    $('#InvoiceMasterID').val(InvoiceMasterID);
    $('#customerName').val(PartyName);

    $('#paymentModal').modal('show');

    // AJAX call to fetch amount and balance for invoice master
    $.ajax({
        url: "{{ url('ajax_umrah_invoice_balance') }}/" + InvoiceMasterID,

        method: 'GET',
        
        success: function(response) {
        console.log(response.data.partyID);
        console.log(response.data.TotalAmount);
        console.log(response.data.Balance);
        
        $('#PartyID').val(response.data.PartyID);
        $('#Total').val(response.data.Total);
        $('#Balance').val(response.data.Balance);

        var vhno = response.vhno; // Extract vhno from response
        var voucherNumber = voucherType + vhno; // Concatenate voucherType with vhno
        $('#voucherNumber').val(voucherNumber); // Update input field with concatenated voucher number
        },
        error: function(xhr, status, error) {
            console.error('Error fetching voucher number:', error);
        }
    });


    
});



           // Handle change event on payment mode dropdown
    $('#InvoiceType').change(function() {
        

           var selectedMode = $('#payment-mode').val();

       
       


        if (selectedMode !== '') {
            getAccountsCategory(selectedMode);
            generateVoucherNumber(selectedMode);
        } else {
            $('#voucherNumber').val('');
            $('#deposit-to').val(''); // Clear the selected value
            $('#deposit-to').empty(); // Remove all options
            $('#deposit-to').append('<option value="">Select an option</option>'); // Add a default option back
        }
        

        var invoiceType = $('#InvoiceType').val();
        
        if (invoiceType == 1) {
            $('#amount_received').text('Amount Received');
        } else {
            $('#amount_received').text('Amount Payable');
        }

         

    });



           // Handle change event on payment mode dropdown
    $('#payment-mode').change(function() {
        var selectedMode = $(this).val();

       
       


        if (selectedMode !== '') {
            getAccountsCategory(selectedMode);
            generateVoucherNumber(selectedMode);
        } else {
            $('#voucherNumber').val('');
            $('#deposit-to').val(''); // Clear the selected value
            $('#deposit-to').empty(); // Remove all options
            $('#deposit-to').append('<option value="">Select an option</option>'); // Add a default option back
        }






paymentchecking();

    });



        function getAccountsCategory(selectedMode) {
        $.ajax({
            url: "{{ url('ajax_accounts_by_category') }}",
            method: 'GET',
            data: { category: selectedMode },
            success: function(response) {
                var depositToSelect = $('#deposit-to');
                depositToSelect.empty();
                response.forEach(function(account) {
                    depositToSelect.append('<option value="' + account.ChartOfAccountID + '" data-account-name="' + account.ChartOfAccountName + '">' + account.ChartOfAccountID + ' - ' + account.ChartOfAccountName + '</option>');
                });


                paymentchecking();
                // Update hidden input with the name of the first account by default
                if (response.length > 0) {
                    $('#selectedAccountName').val(response[0].ChartOfAccountName);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching accounts:', error);
            }
        });
    }


    


</script>

<script>
        // JavaScript / jQuery code

    function generateVoucherNumber(paymentMode) {
        
        var invoiceTypeID = $('#InvoiceType').val();

        var voucherCode = '';
        var voucherType = '';

        if(invoiceTypeID == 1){
                switch (paymentMode) {
                case 'CASH':
                    voucherCode = 5; // Set voucher code for CASH payment mode
                    voucherType = 'CR'; // Set voucher Type for CASH payment mode
                    break;
                case 'BANK':
                    voucherCode = 2; // Set voucher Type for BANK payment mode
                    voucherType = 'BR';
                    break;
                case 'CARD':
                    voucherCode = 2; // Set voucher Type for CARD payment mode
                    voucherType = 'BR';
                    break;
                default:
                    break;
            }

        }
        else{
            switch (paymentMode) {
                case 'CASH':
                    voucherCode = 4; // Set voucher code for CASH payment mode
                    voucherType = 'CP'; // Set voucher Type for CASH payment mode
                    break;
                case 'BANK':
                    voucherCode = 1; // Set voucher Type for BANK payment mode
                    voucherType = 'BP';
                    break;
                    case 'CARD':
                    voucherCode = 1; // Set voucher Type for CARD payment mode
                    voucherType = 'BP';
                    break;
               
                default:
                    break;
            }
        }
        

        // AJAX call to fetch the voucher number
        $.ajax({
            url: "{{ url('ajax_get_voucher_number') }}", // Route to fetch voucher number
            method: 'GET',
            data: { voucher_code: voucherCode }, // Pass voucher code as data
            success: function(response) {
                var vhno = response.vhno; // Extract vhno from response
            var voucherNumber = voucherType + vhno; // Concatenate voucherType with vhno
            $('#voucherNumber').val(voucherNumber); // Update input field with concatenated voucher number
            },
            error: function(xhr, status, error) {
                console.error('Error fetching voucher number:', error);
            }
        });
    }
 
    </script>

{{-- <script>


function openPopup(id) {
  console.log("Opening modal for ID:", id);

  // Optional: dynamically update content
//   document.getElementById('myLargeModalLabel').innerText = 'Details for ID ' + id;
// alert(id);
$('#myLargeModalLabel').text('Details for ID ' +id);
$('#paymentModal').modal('show');
$('#customerName').val('shhow');
$('#InvoiceMasterID').val();


}
</script> --}}

 


    <script>

        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('ajax.index') }}",
                columns: [
                    { data: 'id' },
                    { data: 'Date' },
                    { data: 'passenger_name' },
                          { data: 'action'  },
                    { data: 'type' },
                    { data: 'passport_no' },
                    { data: 'pnr' },
                    { data: 'visa_no' },
                    { data: 'package_name' },
                ],
                order: [[0, 'desc']],
            });

 
 


 

        });
 

        // ajax add record

         $('#paymentForm').on('submit', function(e) {
 
                e.preventDefault();
        const btn = $("#payment-btn");
        let formData = new FormData($("#paymentForm")[0]);


                //   let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{URL('umrahSavePayment')}}",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: formData,
                    enctype: "multipart/form-data",
                     beforeSend: function () {
                    btn.prop('disabled', true);
                    btn.html('Processing');
                      },
                    success: function(response) {

                        $('#create-update-modal').modal('hide');
                        // $('#paymentForm')[0].reset(); // Reset all form data
                        // table.ajax.reload();

                         btn.prop('disabled', false);
                         btn.html('Save Payment');

                      if (response.success) {
                        // Success path
                        notyf.success({
                            message: response.message,
                            duration: 3000
                        });
                         $('#paymentModal').modal('hide');
                        $('#paymentForm')[0].reset(); 
                        table.ajax.reload();

                    } else {
                        // Handled "logical error" path
                        notyf.error({
                            message: response.message,
                            duration: 3000
                        });
                    }
                                    },
                    error: function(response) {

                        

                        btn.prop('disabled', false);
                        btn.html('Save Payment');

                        console.log(response);

                        notyf.error({
                            message: response.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });


    </script>

<script>
    $(document).ready(function() {
              $('#table thead tr').clone(true).appendTo('#table thead');
              $('#table thead tr:eq(1) th').each(function(i) {
                  var title = $(this).text();
                  $(this).html('<input type="text" placeholder="  ' + title +
                      '"  class="form-control form-control-sm" />');
  
  
                  // hide text field from any column you want too
                  if (title == 'Action') {
                      $(this).hide();
                  }
  
  
  
  
  
                  $('input', this).on('keyup change', function() {
                      if (table.column(i).search() !== this.value) {
                          table
                              .column(i)
                              .search(this.value)
                              .draw();
                      }
                  });
  
              });
              var table = $('#table').DataTable({
                  orderCellsTop: true,
                  fixedHeader: true,
                  retrieve: true,
                  paging: false
  
              });
          });
</script>
@endsection

 