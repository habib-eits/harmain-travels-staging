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

                                <div class="page-btn">
                                    <a href="#" class="btn btn-added btn-primary" onclick="addRecord()" >
                                        <i class="me-2 bx bx-plus"></i>Add
                                    </a>
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
                                            <th>ID</th>
                                            <th>Location</th>            
                                            <th>From</th>            
                                            <th>To</th>            
                                            <th>Supplier</th>            
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

    <!-- General Modal for Create/Edit -->
<div class="table-responsivee">
        <div class="modal fade" id="create-update-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4 id="modal-title"></h4> <!-- Dynamic Title -->
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form id="create-update-form" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="record-id"> <!-- Used for Edit -->

<div class="row">
                            <div class="col-md-3">
                                <label for="city" class="form-label">City</label>
                                <select class="form-select select2" id="location" name="location">
                                    <option value="MADINA">Madina</option>
                                    <option value="Makkah">Makkah</option>
                                </select>
                            </div>

                         

                            <div class="col-md-3">
                                <label for="sr_no" class="form-label">Sr #</label>
                                <input type="number" class="form-control" id="sr_no" name="sr_no" >
                            </div>

                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Date From</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" >
                            </div>

                            <div class="col-md-3 ">
                                <label for="date_to" class="form-label">Date To</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" >
                            </div>

                            <div class="col-md-3 mt-3">
                                <label for="room_type" class="form-label">Room Type</label>
                                <select class="form-control" id="room_type" name="room_type">
                                    <option value="SEPARATE">SEPARATE</option>
                                    <option value="SHARING">SHARING</option>
                      
                                </select>
                            </div>

                            <div class="col-md-3 mt-3">
                                <label for="room_status" class="">Status</label>
                                <select class="form-select" id="room_status" name="room_status">
                                    <option value="BED">Bed</option>
                                    <option value="ROOM">Room</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <label class="text-danger">Purchase</label>
                                <input type="number" class="form-control" name="purchase_price" id="purchase_price">
                            </div>
                           
                            <div class="col-md-2">
                                <label class="text-danger">Quint</label>
                                <input type="number" class="form-control" name="quint_purchase" id="quint_purchase">
                            </div>
                             <div class="col-md-2">
                                <label class="text-danger">Quad</label>
                                <input type="number" class="form-control" name="quad_purchase" id="quad_purchase">
                            </div>
                             <div class="col-md-2">
                                <label class="text-danger">Triple</label>
                                <input type="number" class="form-control" name="triple_purchase" id="triple_purchase">
                            </div>
                            <div class="col-md-2">
                                <label class="text-danger">Double</label>
                                <input type="number" class="form-control" name="double_purchase" id="double_purchase">
                            </div>
                           
                        </div>

                        <div class="row mt-3">

                             <div class="col-md-2">
                                <label class="text-success">Sale</label>
                                <input type="number" class="form-control" name="sale_price" id="sale_price">
                            </div>
                             <div class="col-md-2">
                                <label class="text-success">Quint</label>
                                <input type="number" class="form-control" name="quint_sale" id="quint_sale">
                            </div>
                           
                            <div class="col-md-2">
                                <label class="text-success">Quad</label>
                                <input type="number" class="form-control" name="quad_sale" id="quad_sale">
                            </div>
                           
                            <div class="col-md-2">
                                <label class="text-success">Triple</label>
                                <input type="number" class="form-control" name="triple_sale" id="triple_sale">
                            </div>
                             
                            <div class="col-md-2">
                                <label class="text-success">Double</label>
                                <input type="number" class="form-control" name="double_sale" id="double_sale" >
                            </div>
                        </div>

                        

                        <!-- Package & Provider Section -->
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="package_name" class="form-label">Package Name</label>
                                <select class="form-select select2" id="package_name" name="package_name">
                                    <option value="VIP">VIP</option>
                                    <option value="STANDARD">Standard</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="hotel_name" class="form-label">Hotel Name</label>
                                <select class="form-select select2" id="hotel_name" name="hotel_name">
                                    @foreach ($hotel as $row)
                                    <option value="{{$row->id}}">{{$row->hotel_name}}</option>    
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="provider_name" class="form-label">Provider Name</label>
                                <select class="form-select select2" id="PartyID" name="PartyID">
                                     @foreach ($party as $row)
                                    <option value="{{$row->PartyID}}">{{$row->PartyName}}</option>    
                                    @endforeach
                                </select>
                            </div>
                            
                            
                            <div class="col-md-3">
                                <label for="provider_name" class="form-label">Active Status</label>
                                <select class="form-select" id="is_active" name="is_active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Save Tariff</button>
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


   



    <script>
         var table = null;
        $(document).ready(function() {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('hoteltariff.index') }}",
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
                    { data: 'location' },
                    { data: 'date_from' },
                    { data: 'date_to' },
                    { data: 'PartyID' },
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


            $('#create-update-form').on('submit', function(e) {
                e.preventDefault();

                 let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('hoteltariff.store') }}",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: formData,
                    enctype: "multipart/form-data",
                   
                    success: function(response) {

                        $('#create-update-modal').modal('hide');
                        $('#create-update-form')[0].reset(); // Reset all form data
                        table.ajax.reload();

                        notyf.success({
                            message: response.message,
                            duration: 3000
                        });
                    },
                    error: function(e) {

                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

        });

        // Handle the delete button click
        function addRecord()
        {
            $('#modal-title').text('Create');
            $('#record-id').val(''); // Clear the hidden input
            $('#submit-btn').text('Create');

  $('#create-update-modal').on('shown.bs.modal', function() {
        
    $('.select2').select2({
            dropdownParent: $('#create-update-modal'), // Ensures dropdown is rendered within the modal
            width: '100%' // Matches the width styling
        });
    });

            $('#create-update-modal').modal('show');
        }

        function editRecord(id) {
            $.get("{{ route('hoteltariff.edit', ':id') }}".replace(':id', id), function(response) {

               
                $('#record-id').val(response.data.id);
                 $('#location').val(response.data.location),
                 $('#sr_no').val(response.data.sr_no),
                $('#room_type').val(response.data.room_type),
                 $('#date_from').val(response.data.date_from),
                 $('#date_to').val(response.data.date_to),
                 $('#room_status').val(response.data.room_status),
                 $('#purchase_price').val(response.data.purchase_price),
                 $('#sale_price').val(response.data.sale_price),
                 $('#triple_purchase').val(response.data.triple_purchase),
                 $('#triple_sale').val(response.data.triple_sale),
                 $('#double_purchase').val(response.data.double_purchase),
                 $('#double_sale').val(response.data.double_sale),
                 $('#quint_purchase').val(response.data.quint_purchase),
                 $('#quint_sale').val(response.data.quint_sale),
                 $('#quad_purchase').val(response.data.quad_purchase),
                 $('#quad_sale').val(response.data.quad_sale),
                 $('#package_name').val(response.data.package_name),
                 $('#hotel_name').val(response.data.hotel_name),
                 $('#PartyID').val(response.data.PartyID).trigger('change');

                 $('#BranchID').val(response.BranchID),
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
                    url: "{{ route('hoteltariff.destroy', ':id') }}".replace(':id', id),
                    data: {
                        _token: "{{ csrf_token() }}" // CSRF token for Laravel
                    },
                    success: function(response) {
                        table.ajax.reload();
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

   
     
@endsection
