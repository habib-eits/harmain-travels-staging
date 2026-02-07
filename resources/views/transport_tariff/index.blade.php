@extends('template.tmp')
@section('title', $title)

@section('content')


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">All {{ $title  }}</h3>

                            <div class="page-title-right d-flex">

                                {{-- <div class="page-btn">
                                    <a href="#" class="btn btn-added btn-primary" onclick="addRecord()" >
                                        <i class="me-2 bx bx-plus"></i>Add
                                    </a>
                                </div> --}}
                            </div>



                        </div>
                    </div>
                </div>


                <div class="card shadow-sm ">
               <div class="card-body">


                 <form id="create-update-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="record-id"> <!-- Used for Edit -->
                <div class="row g-3">
            <!-- Left Column -->
            
            <div class="col-md-3 mt-1 d-none">
                <label class="form-label" for="sno">S#</label>
                <input type="text" id="sno" name="sno" class="form-control" value="" placeholder="S#">
            </div>
            
            <div class="col-md-3 mt-1">
                <label class="form-label" for="date_from">Date From</label>
                <input type="date" id="date_from" name="date_from" class="form-control" value="">
            </div>
            <div class="col-md-3 mt-1">
                <label class="form-label" for="date_to">Date To</label>
                <input type="date" id="date_to" name="date_to" class="form-control" value="">
            </div>
            <div class="col-md-3 mt-1">
                <label class="form-label" for="sector">Sector</label>
                <select class="form-select select2" id="sector" name="sector">
                    <option value="">Select Sector</option>
                    @foreach($sector as $sec)
                        <option value="{{ $sec->name }}">{{ $sec->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-1">
                <label class="form-label" for="vehicle_type">Type</label>
                <select class="form-select select2" id="vehicle_type" name="vehicle_type">
                     @foreach($transport_type as $type)
                        <option value="{{ $type->name }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mt-1">
                <label class="form-label" >Status</label>
                <select class="form-select select2"  name="status" id="status1">
                    @foreach($transport_status as $stat)
                        <option value="{{ $stat->name }}">{{ $stat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-none mt-1">
                <label class="form-label" >Rate for this Customer Name</label>
                <select class="form-select select2" id="PartyID" name="PartyID">
                    
                    <option value="">Select Customer</option>
                    @foreach($party as $part)
                        <option value="{{ $part->PartyID }}">{{ $part->PartyName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mt-1">
                <label class="form-label" for="SupplierID">Provider Name</label>
                <select class="form-select select2" id="SupplierID" name="SupplierID">
                    <option value="">Select Provider</option>
                    @foreach($supplier as $sup)
                        <option value="{{ $sup->PartyID }}">{{ $sup->PartyName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mt-1 ">
                <label class="form-label text-danger" for="purchase_price">Purchase</label>
                <input type="text" id="purchase_price" name="purchase_price" class="form-control text-danger fw-bold" >
            </div>

            <div class="col-md-3 mt-1">
                <label class="form-label text-success" for="sale_price">Sale</label>
                <input type="text" id="sale_price" name="sale_price" class="form-control text-success fw-bold" >
            </div>

            <div class="col-md-3 mt-1">
                <label class="form-label" for="transport_category">Category</label>
                <select class="form-select select2" id="transport_category" name="transport_category">
                    <option value="Transport">Transport</option>
                    <option value="Tour">Tour</option>
                </select>
            </div>
            
            <div class="col-md-3 mt-1">
                <label class="form-label" for="transport_category">Active</label>
                <select class="form-select select2" id="is_active" name="is_active">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="clearfix"></div>
             <div class="col-md-3 mt-1">
        <button type="button" class="btn btn-warning text-white fw-bold w-100 btn-rounded" id="save-btn">
            <i class="bi bi-save"></i> Save Tariff
        </button>

       


    </div>
    <div class="col-md-3 mt-1">
        <button type="button" class="btn btn-success fw-bold w-100 btn-rounded" id="modify-btn">
            <i class="bi bi-pencil-square"></i> Modify Tariff
        </button>
    </div>
    <div class="col-md-3 mt-1">
        <button type="button" class="btn btn-danger fw-bold w-100 btn-rounded"  id="delete-btn">
            <i class="bi bi-trash"></i> Delete Tariff
        </button>
    </div>
    
    
    <div class="col-md-3 mt-1">
        <button type="button" class="btn btn-secondary fw-bold w-100 btn-rounded" id="cancel-btn" >
            <i class="bi bi-trash"></i> Cancel Tariff
        </button>
    </div>
        </div>

    </form>

   
               </div>
                </div>
                

                


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="table" class="table table-striped table-sm " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Location</th>            
                                            <th>From</th>            
                                            <th>To</th>            
                                            <th>Supplier</th>            
                                             <th>Purchase</th>
                                            <th>Sale</th>
                                            <th>Type</th>
                                            <th>Category</th>
                                            <th>Status</th>
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

 


   



    <script>
         var table = null;
        $(document).ready(function() {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transporttariff.index') }}",
                columns: [
                   {
        data: null,
        name: 'serial',
        render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        },
        orderable: false,
        searchable: false
    },
                    { data: 'sector' },
                    { data: 'date_from' },
                    { data: 'date_to' },
                    { data: 'supplier.PartyName' },
//                  {
//     data: 'party.PartyName',
//     render: function (data, type, row) {
//         return data ? data : 'N/A'; // or '' if you prefer blank instead of 'N/A'
//     }
// },
                    { data: 'purchase_price' },
                    { data: 'sale_price' },
                    { data: 'vehicle_type' },
                    { data: 'status' },
                    { 
                        data: 'is_active',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="badge bg-success">Active</span>';
                            } else {
                                return '<span class="badge bg-danger">Disable</span>';
                            }
                        }
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ],
            });

            // Change cursor to pointer (hand) on table rows
            $('#table tbody').css('cursor', 'pointer');

            // Set a custom height for table rows
            $('#table tbody').on('draw.dt', function () {
                $('#table tbody tr').css('height', '48px'); // Adjust height as needed
            });
            // For initial load and redraws
            $('#table').on('draw.dt', function () {
                $('#table tbody tr').css('height', '48px'); // Adjust height as needed
            });

            $('#table tbody').on('click', 'tr', function () {
                var data = table.row(this).data(); // Get row data
                if (data && data.id) {
                    editRecord(data.id); // Call your function with the row ID
                }
            });

            $('#save-btn').on('click', function(e) {
            $('#record-id').val('');    
            createOrUpdate();                
            });

            $('#modify-btn').on('click', function(e) {             
            createOrUpdate();
            });

            $('#delete-btn').on('click', function(e) {
            deleteRecord($('#record-id').val());
            });

            // Reset form and select2 values on cancel
            $('#cancel-btn').on('click', function(e) {
                resetForm();
            });




         


        });



        function resetForm() {
            $('#create-update-form')[0].reset();
            // Reset all select2 fields
            // $('#create-update-form .select2').val('').trigger('change');
            $('#PartyID').val('').trigger('change');
            $('#SupplierID').val('').trigger('change');
        }


        function createOrUpdate()
        {

             let formData = new FormData(document.getElementById('create-update-form'));
                $.ajax({
                    type: "POST",
                    url: "{{ route('transporttariff.store') }}",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: formData,
                    enctype: "multipart/form-data",
                   
                    success: function(response) {

                        
                        table.ajax.reload(false,null); // Reload the table without resetting pagination
                        
                        notyf.success({
                            message: response.message,
                            duration: 3000
                        });
                        
                        resetForm();

                        
                    },
                    error: function(e) {

                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });

        }

   

        function editRecord(id) {
            $.get("{{ route('transporttariff.edit', ':id') }}".replace(':id', id), function(response) {
                $('#record-id').val(response.data.id);
                 
                 $('#sr_no').val(response.data.sr_no);
                 $('#date_from').val(response.data.date_from);
                 $('#date_to').val(response.data.date_to);
                 $('#sector').val(response.data.sector).trigger('change');
                  $('#purchase_price').val(response.data.purchase_price);
                 $('#sale_price').val(response.data.sale_price);
                 $('#vehicle_type').val(response.data.vehicle_type).trigger('change');

          
                 $('#status').val(response.data.status).trigger('change');
                 
                 
                 
                 $('#PartyID').val(response.data.PartyID).trigger('change');
                 $('#SupplierID').val(response.data.SupplierID).trigger('change');

                  $('#is_active').val(response.data.is_active).trigger('change');




                $('#modal-title').text('Update');
                $('#submit-btn').text('Update');

                  $('#create-update-modal').on('shown.bs.modal', function() {
                        $('.select2').select2({
                            dropdownParent: $('#create-update-modal'), // Ensures dropdown is rendered within the modal
                            width: '100%' // Matches the width styling
                        });
                    });

                $('#create-update-modal').modal('show');
            }).fail(function(xhr) {
                 notyf.error({
                            message: xhr.responseJSON.message,
                            duration: 5000
                        });
                // alert('Error fetching brand details: ' + xhr.responseText);
            });
        }

        function deleteRecord(id) {
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('transporttariff.destroy', ':id') }}".replace(':id', id),
                    data: {
                        _token: "{{ csrf_token() }}" // CSRF token for Laravel
                    },
                    success: function(response) {
                        table.ajax.reload(false, null); // Reload the table without resetting pagination
                        notyf.success({
                            message: response.message,
                            duration: 3000
                        });
                    },
                    error: function(e) {
                        notyf.error({
                            message: e.responseJSON?.message || 'An error occurred.',
                            duration: 5000
                        });
                    }
                });
            }
        }

    </script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const dateFrom = document.getElementById("date_from");
    const dateTo = document.getElementById("date_to");

    dateFrom.addEventListener("change", function () {
        const selectedDate = this.value;
        
        // Set the same date in date_to
        dateTo.value = selectedDate;
        
        // Set the min attribute to prevent selection of earlier dates
        dateTo.min = selectedDate;
    });
});
</script>
   
     
@endsection
