@extends('template.tmp')

@section('title', 'Umrah')


@section('content')


<style>

body[data-sidebar=dark].vertical-collpsed {
    min-height: 0px;
}

    .error-border {
        border: 2px solid red;
    }

    .error-message {
        color: red;
        display: none;
    }

    .paid-invoice-img {
        position: absolute;
        top: 0;
        right: 23px !important;
        margin-bottom: 20px;
        z-index: 9999;
        float: right;
    }
    .dropdown-divider {
    height: 0;
     margin: .0rem 0 !important ; 
    overflow: hidden;
    border-top: 1px solid #eff2f7;
}

.select2-container .select2-selection--single {
    background-color: #fff;
    border: 1px solid #ced4da;
    height: 38px
}
.select2-container .select2-selection--single:focus {
    outline: 0
}
.select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: .75rem;
    color: #495057
}
.select2-container .select2-selection--single .select2-selection__arrow {
    height: 34px;
    width: 34px;
    right: 3px
}
.select2-container .select2-selection--single .select2-selection__arrow b {
    border-color: #adb5bd transparent transparent transparent;
    border-width: 6px 6px 0 6px
}
.select2-container .select2-selection--single .select2-selection__placeholder {
    color: #495057
}
.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent #adb5bd transparent!important;
    border-width: 0 6px 6px 6px!important
}
.select2-container--default .select2-search--dropdown {
    /*padding: 10px;*/
    background-color: #fff
}
.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da;
    background-color: #fff;
    color: #74788d;
    outline: 0
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #556ee6
}
.select2-container--default .select2-results__option[aria-selected=true] {
    /*background-color: #f8f9fa;*/
    /*color: #343a40*/
}
.select2-container--default .select2-results__option[aria-selected=true]:hover {
    background-color: #556ee6;
    color: #fff
}
.select2-results__option {
    padding: 6px 12px
}
.select2-container[dir=rtl] .select2-selection--single .select2-selection__rendered {
    padding-left: .75rem
}
.select2-dropdown {
    border: 1px solid rgba(0, 0, 0, .15);
    background-color: #fff;
    -webkit-box-shadow: 0 .75rem 1.5rem rgba(18, 38, 63, .03);
    box-shadow: 0 .75rem 1.5rem rgba(18, 38, 63, .03)
}
.select2-search input {
    border: 1px solid #f6f6f6
}
.select2-container .select2-selection--multiple {
    min-height: 38px;
    background-color: #fff;
    border: 1px solid #ced4da!important
}
.select2-container .select2-selection--multiple .select2-selection__rendered {
    padding: 2px .75rem
}
.select2-container .select2-selection--multiple .select2-search__field {
    border: 0;
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field::-webkit-input-placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field::-moz-placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field:-ms-input-placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field::-ms-input-placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field::placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-selection__choice {
    background-color: #eff2f7;
    border: 1px solid #f6f6f6;
    border-radius: 1px;
    padding: 0 7px
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #ced4da
}
.select2-container--default .select2-results__group {
    font-weight: 600
}
.select2-result-repository__avatar {
    float: left;
    width: 60px;
    margin-right: 10px
}
.select2-result-repository__avatar img {
    width: 100%;
    height: auto;
    border-radius: 2px
}
.select2-result-repository__statistics {
    margin-top: 7px
}
.select2-result-repository__forks, .select2-result-repository__stargazers, .select2-result-repository__watchers {
    display: inline-block;
    font-size: 11px;
    margin-right: 1em;
    color: #adb5bd
}
.select2-result-repository__forks .fa, .select2-result-repository__stargazers .fa, .select2-result-repository__watchers .fa {
    margin-right: 4px
}
.select2-result-repository__forks .fa.fa-flash::before, .select2-result-repository__stargazers .fa.fa-flash::before, .select2-result-repository__watchers .fa.fa-flash::before {
    content: "\f0e7";
    font-family: 'Font Awesome 5 Free'
}
.select2-results__option--highlighted .select2-result-repository__forks, .select2-results__option--highlighted .select2-result-repository__stargazers, .select2-results__option--highlighted .select2-result-repository__watchers {
    color: rgba(255, 255, 255, .8)
}
.select2-result-repository__meta {
    overflow: hidden
}

</style>

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
            {{-- <form action="modelVoucherSave" method="POST">
                @csrf
                <input type="text" value="1" name="value">
                <button type="submit">Submit</button>
            </form> --}}
            <script>
                function delete_invoice(id) {        


                url = '{{URL::TO('/')}}/InvoiceDelete/'+ id;
        
    
       
            jQuery('#staticBackdrop').modal('show', {backdrop: 'static'});
            document.getElementById('delete_link').setAttribute('href' , url);
         
    }
            </script>


            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Umrah</h4>
                        <a href="{{URL('/UmrahCreate')}}" class="btn btn-primary w-md float-right "><i
                                class="bx bx-plus"></i> Add New</a>



                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-12">

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

$item = DB::table('item')->whereIn('ItemCode',['UB','UA'])->get();
 ?>

                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                {{-- <div class="col-md-2">
                                    <label for="item_name">Item Name:</label>
                                    <input type="text" id="item_name" name="item_name" class="form-control">
                                </div> --}}

                                <div class="col-md-2">
                                    <label for="party_name">Party Name</label>
                                    <input type="text" id="party_name" name="party_name" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="Phone">Phone</label>
                                    <input type="number" id="Phone" name="Phone" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <label for="date">From:</label>
                                    <input type="date" id="startdate" name="start" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <label for="date">To:</label>
                                    <input type="date" id="enddate" name="end" class="form-control">
                                </div>


                                   <div class="col-md-2">
                                    <label for="date">Item:</label>
                                     <select name="ItemID" id="ItemID" class="form-select select2">
                                         <option value="">select</option>
                                         @foreach($item as $value)
                                         <option value="{{$value->ItemID}}">{{$value->ItemName}}</option>
                                         @endforeach
                                     </select>
                                </div>

<?php 

$user = DB::table('user')->get();
 ?>
                                     <div class="col-md-2">
                                    <label for="date">Saleman:</label>
                                     <select name="UserID" id="UserID" class="form-select select2">
                                         <option value="">select</option>
                                         @foreach($user as $value)
                                         <option value="{{$value->UserID}}">{{$value->FullName}}</option>
                                         @endforeach
                                     </select>
                                </div>
                               
                               <div class="col-md-4 d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-danger w-md mt-4" id="filter-button">
                                        <i class="mdi mdi-filter"></i> Filter
                                    </button>
                                    <button type="button" class="btn btn-primary w-md mt-4" id="reset-dates-button">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </button>
                                </div>  
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">

                            <table id="student_table" class="table table-striped table-sm " style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Invoice#</th>
                                        <th class="col-md-3">Item</th>
                                        <th class="col-md-3">Customer</th>
                                        <th class="col-md-3">Phone</th>
                                        <th class="col-md-2">Date</th>
                                        <th class="col-md-3">PaxName</th>
                                        <th class="col-md-3">Ref #</th>
                                        <th class="col-md-3">PNR</th>
                                        <th class="col-md-3">Sector</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th class="col-md-3">ID</th>
                                        <th class="col-md-3">Invoice Type</th>
                                        <th class="col-md-3">Voucher</th>
                                        <th class="col-md-3">Mode</th>
                                        <th>Action</th>
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

    <!-- Modal for Payment Form -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title me-3" id="paymentModalLabel">Payment for INV</h5> <!-- Use the 'me-3' class for margin-end -->
                    <div><span id="invoiceType" class="badge bg-danger pl-3"></span></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="POST" action="{{URL('modelVoucherSave')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="customerName" class="col-md-2 col-form-label fw-bold">Customer Name</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="customerName" name="customer_name" value="">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="paymentNumber" class="col-md-2 col-form-label fw-bold">Payment #</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="InvoiceMasterID" name="InvoiceMasterID"
                                    value="">
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
                                <input class="form-control" type="text" id="balance" name="balance" value="" readonly="">
                            </div>
                            <label for="amountReceived" class="col-md-2 col-form-label fw-bold">Bank Charges (if
                                any)</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="bankCharges" name="bank_charges" value="" >
                            </div>


                        </div>


                        <div class="mb-0 row">
                            <label for="amountReceived" class="col-md-2 col-form-label fw-bold">Amount Received
                                ({{env('APP_CURRENCY')}})</label>
                            <div class="col-md-4">
                                <input class="form-control" required="" min="1" type="number" id="amountReceived" name="amount_received"
                                    value="" >
                                <span id="error-message" class="error-message">Amount received is greater than the
                                    Balance
                                    amount!</span><br><br>
                            </div>

                            <label for="bankCharges" class="col-md-2 col-form-label fw-bold" id="label">Choose
                                Account</label>
                            <div class="col-md-4 " id="bank_charges_div">
                                <select name="ChartOfAccountID" id="ChartOfAccountID" class="form-select"
                                    style="width: 100% !important;">
                                    @foreach($chartofaccount as $value)
                                    <option  value="{{$value->ChartOfAccountID}}">{{$value->ChartOfAccountName}}</option>
                                    @endforeach
                                </select>

                            </div>


                        </div>




                        <div class="row">


                            <div class="mb-1 row">
                                <div class="col-md-2 ml-2">
                                    <label class="col-form-label" for="email-id">Date</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group" id="datepicker21">
                                        <input type="date" name="Date"  class="form-control"
                                          
                                            
                                            value="{{date('Y-m-d')}}">
                                       
                                    </div>
                                </div>
                            </div>
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
                            {{-- <div class="col-md-4">
                                <select name="deposit_to" id="deposit-to" class="form-select">
                                </select>
                            </div> --}}
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

                          <div class="mb-3 row">
                            <label for="notes" class="col-md-2 col-form-label fw-bold">VHNO</label>
                            <div class="col-md-4" id="vhno">
                                e
                            </div>  <div class="col-md-4" id="payment_mode">
                                ff
                            </div>

                        </div>
                        
                       
                        
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
                            id="amountForm">Record Payment</button>
                        <button id="modelClose" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Modal for PDF View -->
    <div class="modal fade" id="pdfViewModal" tabindex="-1" aria-labelledby="pdfViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfViewModalLabel">Invoice PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pdfContainer">
                        <!-- PDF content will be loaded her                                                          e -->
                    </div>
                </div>
                <div class="modal-footer">
                    <a title="" class="btn btn-danger" id="print">Print</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



<!-- Modal for Payment Form -->
    <div class="modal fade" id="partyledgermodal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-top">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 ><div id="modal-title">Party Ledger</div></h5> <!-- Use the 'me-3' class for margin-end -->
                    <div><span id="invoiceType" class="badge bg-danger pl-3"></span></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                 
                <div id="ajax_ledger"></div>
                 <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-md" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>


    <!-- END: Content-->
    {{-- change deposit list according to payment mode select --}}
   <script></script>

    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        function loadPDF(url) {
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#pdfViewModal').modal('show'); // Show the modal after loading content
                $('#pdfContainer').html(response.html); // Update the PDF container with received HTML
            },
            error: function(xhr, status, error) {
                console.error('Error loading PDF:', error);
                alert('Failed to load PDF.');
            }
        });
    }

    
    </script>
<script>
    $(document).ready(function() {
        $('#paymentModal').on('shown.bs.modal', function () {
            const paymentModeSelect = $('#payment-mode');
            const paymentModeError = $('#PaymentModeError');
            const $buttons = $('.btn-disable');
        
            function checkSelection() {
                const paymentModeValue = paymentModeSelect.val();

                if (paymentModeValue !== '') {
                    $buttons.prop('disabled', false);
                    paymentModeError.hide();
                } else {
                    $buttons.prop('disabled', true);
                    paymentModeError.show();
                }
            }

            // Initialize state
            checkSelection();

            // Event listener for select changes
            paymentModeSelect.on('change', checkSelection);
        });
    });
</script>
<script>
     $(document).ready(function() {
            // Check if invoiceMasterID is present in the session and call the function
            @if (session('invoiceMasterID'))
                openInvoiceModal({{ session('invoiceMasterID') }},1);
            @endif
        });
</script>
<script>
    function openInvoiceModal(invoiceMasterID,idddd) {
       
        // alert(invoiceMasterID+'-'+idddd);
        // Fetch the invoice data using AJAX
        $.ajax({
            url: '{{ url("get_specific_invoice1") }}/' + invoiceMasterID,
            method: 'GET',
            success: function(response) {
                console.log(response);
                // Populate modal fields with the fetched data
                $('#customerName').val(response.PartyName);
                $('#InvoiceMasterID').val(response.InvoiceMasterID);
                $('#partyID').val(response.PartyID);
                $('#balance').val(response.Total - response.Paid);
                $('#amountReceived').val('');
                $('#bankCharges').val('');
                $('#paymentDate').val('');
                $('#paymentMode').val('');
                $('#deposit-to').val('');
                $('#voucherNumber').val('');
                $('#notes').val('');
                $('#vhno').html(response.Voucher);
                $('#payment_mode').html(response.PaymentMode);
                $('#invoiceTypeID').val(response.InvoiceTypeID);
                $('#invoiceType').html('');
                $('#invoiceType').append(response.InvoiceType);
                // Remove existing classes and add the new class based on the InvoiceTypeID
                    if(response.InvoiceTypeID === 3) {
                        $('#invoiceType').removeClass('bg-danger').addClass('bg-success');
                    } else if(response.InvoiceTypeID === 5) {
                        $('#invoiceType').removeClass('bg-success').addClass('bg-danger');
                    } 
                    else {
                        // Optional: handle other cases or default styling
                        invoiceTypeSpan.removeClass('bg-danger bg-success');
                    }

                $('#Total').val(response.Total);
                $('#print').attr('href', '{{ URL("/InvoicePDF") }}/' + response.InvoiceMasterID);
                 
                // Show the modal
                $('#paymentModal').modal('show');
            }
        });
    }
</script>

    <script>
        $(document).ready(function() {

        // Initialize DataTable with filter parameters
        var table = $('#student_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ url('ajax_umrah') }}",
            "data": function (d) {
                d.item_name = $('#item_name').val();
                d.party_name = $('#party_name').val();
                d.Phone = $('#Phone').val();
                d.startdate = $('#startdate').val();
                d.enddate = $('#enddate').val();
                d.ItemID = $('#ItemID').val();
                d.UserID = $('#UserID').val();
            }
        },
        "columns": [
            { "data": "InvoiceMasterID" },
            { "data": "ItemName" },
            { "data": "PartyName" },
            { "data": "Phone" },
           { 
                    "data": "Date",
                    "render": function (data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var date = new Date(data);
                            var day = ("0" + date.getDate()).slice(-2);
                            var month = ("0" + (date.getMonth() + 1)).slice(-2);
                            var year = date.getFullYear();
                            return day + '/' + month + '/' + year;
                        }
                        return data;
                    }
                },
            { "data": "PaxName" },
            { "data": "RefNo" },
            { "data": "PNR" },
            { "data": "Sector" },
            { "data": "Total" },
            { "data": "Paid" },
            { "data": "PartyID", "visible": false },
            { "data": "InvoiceTypeID", "visible": false },
            { "data": "Voucher", "visible": true },
            { "data": "PaymentMode", "visible": true },
            { "data": "action", "orderable": false },
        ],
        "order": [[0, 'desc']],
    });

    // Handle filter button click
    $('#filter-button').on('click', function() {

        table.draw();
    });


    $('#reset-dates-button').click(function() {
        $('#startdate').val(''); // Clear start date input
        $('#enddate').val('');   // Clear end date input
        $('#item_name').val('');   // Clear end date input
        $('#party_name').val('');   // Clear end date input
        $('#Phone').val('');   // Clear end date input
        // Optionally, reset any filters in your DataTable

        $('#UserID').val(null).trigger('change');
        $('#ItemID').val(null).trigger('change');



        table.search('').columns().search('').draw();
    });

    // Handle click event on the third column only
    $('#student_table tbody').on('click', 'tr td:nth-child(3)', function() {
        var tr = $(this).closest('tr'); // Get the closest tr ancestor
        var data = table.row(tr).data(); // Get DataTables row data
        var invoiceMasterID = data.InvoiceMasterID;
        var invoiceTypeID = data.InvoiceTypeID;
      

        // Call the function to open the modal
        openInvoiceModal(invoiceMasterID,invoiceTypeID);
    });


        // Handle click event on the third column only
    $('#student_table tbody').on('click', 'tr td:nth-child(4)', function() {
        var tr = $(this).closest('tr'); // Get the closest tr ancestor
        var data = table.row(tr).data(); // Get DataTables row data
        var invoiceMasterID = data.InvoiceMasterID;
        var PartyID = data.PartyID;
        var PartyName = data.PartyName;
      

        // Call the function to open the modal
        openLedgerModal(PartyID,PartyName);

    });

    // Handle form submission
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();
        // Handle form submission logic here
        console.log('Form submitted!');
        // Close the modal after form submission
        $('#paymentModal').modal('hide');
    });

    // Add click event listener for table rows
    $('#student_table tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var partyName = data.PartyName;
        $('input[name="customer_name"]').val(partyName);
    });

    // Add click event listener for table rows
    $('#student_table tbody').on('click', 'tr', function() {
        var data = table.row(this).data(); // Get data for the clicked row
        var invoiceNo = data.InvoiceMasterID; // Extract InvoiceMasterID from the row data

        // Update the card title with the clicked invoice number
        $('.modal-title').text('Payment for INV ' + invoiceNo);
    });

    // Function to fetch and populate accounts based on selected payment mode
    // function getAccountsCategory(selectedMode) {
    //     $.ajax({
    //         url: "{{ url('ajax_accounts_by_category') }}",
    //         method: 'GET',
    //         data: { category: selectedMode },
    //         success: function(response) {
    //             var depositToSelect = $('#deposit-to');
    //             depositToSelect.empty();
    //             response.forEach(function(account) {
    //                 depositToSelect.append('<option value="' + account.ChartOfAccountID + '">' + account.ChartOfAccountID + " - " + account.ChartOfAccountName + '</option>');
    //             });
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error fetching accounts:', error);
    //         }
    //     });
    // }

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

    $(document).ready(function() {
        $('#deposit-to').change(function() {
            var selectedOption = $(this).find('option:selected');
            var accountName = selectedOption.data('account-name');
            $('#selectedAccountName').val(accountName);
        });
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
    // Bind to modal show event
    $('#paymentModal').on('show.bs.modal', function() {
        $('#payment-mode').trigger('change'); // Trigger change event on payment mode dropdown
    });
    // Trigger change event on payment mode dropdown on document load to fetch initial data if needed
    $('#payment-mode').trigger('change');

});
    </script>

<script>
    function openLedgerModal(partyid,partyname) {
       
         
         $('#modal-title').text('PARTY LEDGER :' + partyid+'  ' +partyname);
         $('#partyledgermodal').modal('show');

        // Fetch the invoice data using AJAX
        $.ajax({
            url: '{{ url("ajax_party_ledger") }}/' + partyid,
            method: 'GET',
            success: function(response) {
                // Populate modal fields with the fetched data
                $('#ajax_ledger').html(response);
                
                // Show the modal
                $('#partyledgermodal').modal('show');
            }
        });
    }
</script>

    <script>
        // JavaScript / jQuery code

    function generateVoucherNumber(paymentMode) {
        
        var invoiceTypeID = $('#invoiceTypeID').val();

        var voucherCode = '';
        var voucherType = '';

        if(invoiceTypeID == 3){
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
    <script>
        $(document).ready(function() {
           $('#amountReceived').on('blur', function() {
                // event.preventDefault(); // Prevent form from submitting

 
                paymentchecking();
              



            });
        });



        // payment checking balance amount

        function paymentchecking()

        {
              // var total = parseFloat($('#Total').val()) ;
                // var received = parseFloat($('#amountReceived').val()) + parseFloat($('#paid').val());
                var balance = parseFloat($('#balance').val()) ;
                var received = parseFloat($('#amountReceived').val());

                if (received > balance) {
                    $('#amountReceived').addClass('error-border');
                    $('#error-message').show();

                  
                  $("#amountForm").attr("disabled", "disabled"); 

                } else {
                    $('#amountReceived').removeClass('error-border');
                    $('#error-message').hide();
                    $("#amountForm").submit();

                    $("#amountForm").removeAttr("disabled", "disabled"); 
                  

                    // Optionally, submit the form or do other actions here
                }
        }


    </script>

    <script type="text/javascript">
        $(document).ready(function() {
                    $('#student_table  tr').clone(true).appendTo( '#student_table thead' );
                    $('#student_table thead tr:eq(1) th').each( function (i) {
                        var title = $(this).text();
                        $(this).html( '<input type="text" placeholder=" Search '+title+'"  class="form-control form-control-sm" />' );


// hide text field from any column you want too
if (title == 'Action') {
$(this).hide();
}

        $( 'input', this ).on( 'keyup change', function () {
        
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        });
        
    });
    var table = $('#student_table').DataTable( {
        orderCellsTop: false,
        fixedHeader: true,
        retrieve: true,
        paging: false

    });
});

    </script>


<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>



    <script>
        $( document ).ready(function() {
    
  $('body').addClass('sidebar-enable vertical-collpsed')
 // $('body').removeClass('sidebar-enable vertical-collpsed')
// setTimeout(function(){
        //   $("body").removeClass("sidebar-enable vertical-collpsed");
    //  },5000);
});
    </script>


    <script>
        function viewPDF(url) {
        // Set the iframe source
        document.getElementById('pdf-viewer').src = url;

        // Show the modal using Bootstrap
        $('#pdfViewModal').modal('show');
    }
    </script>


    <script>
        $(document).ready(function() {
    $('#startdate').on('change', function() {
        var startDate = $(this).val();
        var endDate = $('#enddate').val();

            if (!endDate || new Date(endDate) < new Date(startDate)) {
                $('#enddate').val(startDate);
            }


        $('#enddate').attr('min', startDate);
    });
});



    </script>

     <script>
        $(document).ready(function() {
    $('#startdate').on('change', function() {
        var startDate = $(this).val();
        var endDate = $('#enddate').val();

            if (!endDate || new Date(endDate) < new Date(startDate)) {
                $('#enddate').val(startDate);
            }


        $('#enddate').attr('min', startDate);
    });
});



    </script>

  <!-- Plugins js -->
  <script src="{{URL('/')}}/assets/libs/dropzone/dropzone-min.js"></script>
  
  <!-- Form file upload init js -->
  <script src="{{URL('/')}}/assets/js/pages/form-file-upload.init.js"></script>

  <script src="{{URL('/')}}/assets/js/app.js"></script>

    <!-- BEGIN: Vendor JS-->
    <script src="{{asset('assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->


    @endsection