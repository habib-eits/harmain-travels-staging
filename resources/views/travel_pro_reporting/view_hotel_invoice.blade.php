@extends('template.tmp')

@section('title', $pagetitle)


@section('content')



    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Hotel Confirmation #</h4>


                        </div>
                    </div>
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



                <div class="card shadow-sm">
                    <div class="card-body ">


 
                            <div class="row">

                                <div class="col-lg-3 col-md-3  col-sm-3">
                                    <label class="control-label" for="DateFrom">Date From</label>
                                    <input type="date" id="StartDate" name="StartDate" class="form-control"
                                        >
                                </div>

                                <div class="col-lg-3 col-md-3  col-sm-3">
                                    <label class="control-label" for="DateFrom">Date To</label>
                                    <input type="date" id="EndDate" name="EndDate" class="form-control"
                                        >
                                </div>





                                <div class="col-lg-3 col-md-3  col-sm-3 ">
                                    <label class="control-label" for="DateFrom">Client Name </label>
                                    <select name="PartyID" id="PartyID" class="form-select select2">
                                        <option value="">All Clients</option>
                                        @foreach ($party as $item)
                                            <option value="{{ $item->PartyID }}">{{ $item->PartyName }}</option>
                                        @endforeach
                                    </select>
                                </div>




                                <div class="col-lg-3 col-md-3  col-sm-3 ">
                                    <label class="control-label" for="DateFrom">Title </label>
                                    <select name="Title" id="Title" class="form-select select2">
                                        <option value="">All Clients</option>
                                        <option value="Umrah Voucher">Umrah Voucher</option>
                                        <option value="Hotel Voucher">Hotel Voucher</option>
                                        <option value="Transport Voucher">Transport Voucher</option>
                                    </select>
                                </div>


                                <div class="col-lg-3 col-md-3  col-sm-3 ">
                                    <label class="control-label" for="DateFrom">Package Name </label>
                                    <select name="package_id" id="package_id" class="form-select select2">
                                        <option value="">All Clients</option>
                                        <option value="Umrah Voucher">Umrah Voucher</option>
                                        <option value="Hotel Voucher">Hotel Voucher</option>
                                        <option value="Transport Voucher">Transport Voucher</option>
                                    </select>
                                </div>




                                <div class="col-lg-3 col-md-3  col-sm-3 ">

                                    <button type="bujton"  id="filter-button" class="btn btn-primary btn-block  mt-4 w-100">Search</button>
                                </div>

                            </div>


                        

                    </div>
                </div>

                <div class="card  shadow-sm">
                    
                    <div class="card-body ">
                        <table id="student_table" class="table table-striped table-sm " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Voucher</th>
                                            <th width="250">Customer</th>
                                            <th>Date</th>
                                            <th width="200">Head Name</th>
                                            <th>Package Name</th>
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
    <!-- END: Content-->

    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
         $(document).ready(function() {

        // Initialize DataTable with filter parameters
        var table = $('#student_table').DataTable({ 
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ url('ajax_umrah_report_index2') }}",
            "data": function (d) {
                d.StartDate = $('#StartDate').val();
                d.EndDate = $('#EndDate').val();
                d.PartyID = $('#PartyID').val();
                d.Title = $('#Title').val();
                d.package_id = $('#package_id').val();
                
            }
        },
        "columns": [
            { "data": "umrah_invoice_master_id" },
            { "data": "PartyName" },
            { "data": "Date" },
            { "data": "passenger_name" },
            { "data": "package_name" },
           
  
            
            { "data": "action", "orderable": false },

        ],
        "order": [[0, 'desc']],
    });

      // Handle filter button click
    $('#filter-button').on('click', function() {

        table.draw();
    });

});


    </script>
@endsection


 
