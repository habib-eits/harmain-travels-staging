@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')
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

 
 <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>


<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">




    <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Expenses</h4>
                                        <a href="{{URL('/ExpenseCreate')}}"  class="btn btn-primary w-md float-right "><i class="bx bx-plus"></i> Add New</a>

                                   

                                </div>
                            </div>
                        </div>

<script>
       function delete_invoice(id) {        


        url = '{{URL::TO('/')}}/ExpenseDelete/'+ id;
        
    
       
            jQuery('#staticBackdrop').modal('show', {backdrop: 'static'});
            document.getElementById('delete_link').setAttribute('href' , url);
         
    }
</script>

          <div class="row">
  <div class="col-12">
  
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

$chart = DB::table('chartofaccount')->where('Level',3)->get();
 ?>

  <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="Voucher">Reference No:</label>
                                    <input type="text" id="ReferenceNo" name="ReferenceNo" class="form-control">
                                </div> 

                                <div class="col-md-3">
                                    <label for="party_name">Account</label>
                                    <select name="ChartOfAccountID" id="ChartOfAccountID" class="form-select select2">
                                      <option value="">Select</option>
                                       @foreach($chart as $value)
                                        <option value="{{$value->ChartOfAccountID}}" }}>{{$value->ChartOfAccountName}}</option>
                                       @endforeach
                                      
                                    </select>
                                </div>
                                 

                                <div class="col-md-2">
                                    <label for="date">From:</label>
                                    <input type="date" id="startdate" name="start" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <label for="date">To:</label>
                                    <input type="date" id="enddate" name="end" class="form-control">
                                </div>
                               
                               <div class="col-md-3 d-flex flex-wrap gap-2">
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
            <table id="student_table" class="table table-striped table-sm " style="width:100%; font-size: 11px !important;">
                <thead>
                    <tr>
                        <th width="15">EXPENSE #</th>
                        <th width="15">DATE</th>
                        <th width="15">EXPENSE ACCOUNT</th>
                        <th width="15">INVOICE #</th>
                        <th width="15">NARRATION</th>
                        <th width="15">VENDOR NAME</th>
                        <th width="15">VAT</th>
                        <th width="15">TOTAL</th>
                        <th width="15">Action</th>
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
    </div>
    <!-- END: Content-->


     <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>



<script type="text/javascript">
$(document).ready(function() {

     var table = $('#student_table').DataTable({
       "pageLength": 50,
        "processing": true,
        "serverSide": true,
        
         "ajax": {
            "url": "{{ url('ajax_Expense') }}",
            "data": function (d) {
                d.ChartOfAccountID = $('#ChartOfAccountID').val();
                d.ReferenceNo = $('#ReferenceNo').val();
                d.startdate = $('#startdate').val();
                d.enddate = $('#enddate').val();
            }
        },
        "columns":[
            { "data": "ExpenseNo" },
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
            { "data": "ChartOfAccountName" },
            { "data": "ReferenceNo" },
            { "data": "Notes" },
            { "data": "SupplierName" },
            { "data": "Tax" },
            { "data": "Amount" },
              { "data": "action" },
            
        ],
         "order": [1, 'desc'],
     });


   // Handle filter button click
    $('#filter-button').on('click', function() {

        table.draw();
    });

   $('#reset-dates-button').on('click', function() {
        // Clear all input fields
        $('#ChartOfAccountID').val('').trigger('change');
        $('#ReferenceNo').val('');
        $('#startdate').val('');
        $('#enddate').val('');

        // Optionally, reset any filters in your DataTable
        table.search('').columns().search('').draw();
    });


});
</script>

      <!-- BEGIN: Vendor JS-->
    <script src="{{asset('assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->


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
  @endsection