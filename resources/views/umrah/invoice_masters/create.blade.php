@extends('template.tmp')

@section('title', 'Umrah')
<!-- views\umrah\invoice_masters\create.blade.php -->

<style>
 

  .card-icy-white .card-header {
    background-color: #f8f9fa;
    color: #212529;
    border-bottom: 1px solid #e9ecef;
  }

.select2-container {
    width: 100% !important;
} 
</style>

 
 

<style>
 

  .card-header {
    background-color: #f1f3f5!important;
    color: #212529!important;
    border-bottom: 1px solid #ced4da !important;
  }


    .card-body {
background-color: #fff !important;    

  }

  .card {
    margin-bottom: 1.5rem !important;
    border-radius: 0px !important;
    /* box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05); */
    border: 1px solid rgb(218, 218, 218) !important;
    

  }

 
 
</style>



@section('content')
<style>
/* body {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 14px;
    line-height: 1.42857143;
    background: #fff;
} */

.form-label {
font-weight: normal;
}

.form-label {
padding-top: 7px;
margin-bottom: 0;
text-align: right;
}

.form-control {
    background-color: #fff;
}

.uppercase {
  text-transform: uppercase;
}

.form-control {
display: block;
width: 100%;
height: 34px;
padding: 6px 12px;
font-size: 14px;
line-height: 1.42857143;
color: #555;
background-color: #fff;
background-image: none;
border: 1px solid #ccc;
border-radius: 4px;
-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
-webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}

.card {
border-radius: 15px;
}

.select2-container .select2-selection--single {
background-color: #fff;
border: 1px solid #ced4da;
height: 34px !important;
}

.select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 34px !important;
    padding-left: .75rem;
    color: #495057;
}
    </style>
     
    <div class="main-content" style="background-color: white;">

        <div class="page-content">
            <div class="container-fluid">

                {{-- @include('umrah.invoice_masters.components.invoice_master_form')

                @include('umrah.invoice_masters.components.passenger_form')
                
                @include('umrah.invoice_masters.components.passenger_list_table')

                @include('umrah.invoice_masters.components.hotel_form')
                
                @include('umrah.invoice_masters.components.hotel_table')
                
                @include('umrah.invoice_masters.components.transport_form')
                
                @include('umrah.invoice_masters.components.transport_table')

                @include('umrah.invoice_masters.components.total_form') --}}


                <!-- Nav tabs -->
<ul class="nav nav-tabs" id="umrahTabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="invoice-tab" data-bs-toggle="tab" href="#invoice" role="tab">Invoice</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="passenger-tab" data-bs-toggle="tab" href="#passenger" role="tab">Passenger</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="hotel-tab" data-bs-toggle="tab" href="#hotel" role="tab">Hotel</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="transport-tab" data-bs-toggle="tab" href="#transport" role="tab">Transport</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="total-tab" data-bs-toggle="tab" href="#total" role="tab">Total</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content p-3 border border-top-0" id="umrahTabsContent">
  <div class="tab-pane fade show active" id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
    @include('umrah.invoice_masters.components.invoice_master_form')
  </div>

  <div class="tab-pane fade" id="passenger" role="tabpanel" aria-labelledby="passenger-tab">
    @include('umrah.invoice_masters.components.passenger_form')
    @include('umrah.invoice_masters.components.passenger_list_table')
  </div>

  <div class="tab-pane fade" id="hotel" role="tabpanel" aria-labelledby="hotel-tab">
    @include('umrah.invoice_masters.components.hotel_form')
    @include('umrah.invoice_masters.components.hotel_table')
  </div>

  <div class="tab-pane fade" id="transport" role="tabpanel" aria-labelledby="transport-tab">
    @include('umrah.invoice_masters.components.transport_form')
    @include('umrah.invoice_masters.components.transport_table')
  </div>

  <div class="tab-pane fade" id="total" role="tabpanel" aria-labelledby="total-tab">
    @include('umrah.invoice_masters.components.total_form')
  </div>
</div>

            </div>
        </div>

    </div> <!-- container-fluid -->

    <!-- Delete Umrah Invoice Passenger -->
    <div class="modal fade" id="delete-umrah-invoice-passenger">
        <div class="modal-dialog custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Delete Passenger</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                
                            </button>
                        </div>
                        
                            <div class="modal-body custom-modal-body pt-3 pb-0">
                                <p class="text-center">Are you sure you want to delete this passenger?</p>
                            </div>
                            <div class="modal-footer-btn p-3 mt-2">
                                <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-submit shadow-sm btn-danger" id="submit-umrah-invoice-passenger-destroy">Delete</button>
                            </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Delete Umrah Invoice Passenger -->

    <!-- Update All Pax Rate -->
    <div class="modal fade" id="update-all-pax-rate-model">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Update All Pax Rate</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                
                            </button>
                        </div>
                        
                            <div class="modal-body custom-modal-body pt-3 pb-0">
                                <p class="text-center">Are you sure you want to update all passengers Rate?</p>
                            </div>
                            <div class="modal-footer-btn p-3 mt-2">
                                <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-submit shadow-sm btn-warning" id="submit-update-all-pax-rate">Update</button>
                            </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update All Pax Rate -->

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

    @include('umrah.invoice_masters.js.umrah_invoice_master')
    @include('umrah.invoice_masters.js.umrah_invoice_passanger')
    @include('umrah.invoice_masters.js.umrah_invoice_hotel')
    @include('umrah.invoice_masters.js.umrah_invoice_transport')
    @include('umrah.invoice_masters.js.umrah_invoice_voucher')

@endsection
