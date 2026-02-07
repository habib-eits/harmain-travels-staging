@extends('tmp1')
@section('title', 'Parties')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-parties-center justify-content-between">
                <h3 class="mb-sm-0 font-size-18">All Parties</h3>

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
                                <th>Party Name</th>
                                <th>TRN</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Status</th>
                                <th>Actions</th>           
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- General Modal for Create/Edit -->
    <div class="modal fade" id="create-update-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4 id="modal-title"></h4> <!-- Dynamic Title -->
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form id="create-update-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="PartyID" id="record-id"> <!-- Used for Edit -->

                            <div class="mb-3">
                                <label class="form-label">Item Name</label>
                                <input type="text" name="PartyName" id="PartyName" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">TRN</label>
                                <input type="text" name="TRN" id="TRN" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="Address" id="Address" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="Phone" id="Phone" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="Mobile" id="Mobile" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Website</label>
                                <input type="text" name="Website" id="Website" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="Email" id="Email" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="text" name="Password" id="Password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="Active" id="Active" class="form-control">
                                    <option value="Yes">Active</option>
                                    <option value="No">Inactive</option>
                                </select>
                            </div>


                            <div class="modal-footer-btn">
                                <button type="button" class="btn btn-cancel me-2 btn-dark"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="submit-btn" class="btn btn-submit btn-primary">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


   



   @push('scripts')
        <script>
        var table = null;
        $(document).ready(function() {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('parties.index') }}",
               columns: [
                    { data: 'PartyID' },
                    { data: 'PartyName' },
                    { data: 'TRN' },
                    { data: 'Address' },
                    { data: 'Phone' },
                    { data: 'Email' },
                    { data: 'Password'},
                    { data: 'Active' },
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
                    url: "{{ route('parties.store') }}",
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

                        notyfNew.success({
                            message: response.message,
                            duration: 3000
                        });
                    },
                    error: function(e) {

                        notyfNew.error({
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
            $('#create-update-modal').modal('show');
        }

        function editRecord(id) {
            $.get("{{ route('parties.edit', ':id') }}".replace(':id', id), function(response) {
                $('#record-id').val(response.PartyID);
                $('#PartyName').val(response.PartyName);
                $('#TRN').val(response.TRN);
                $('#Address').val(response.Address);
                $('#Phone').val(response.Phone);
                $('#Mobile').val(response.Mobile);
                $('#Website').val(response.Website);
                $('#Email').val(response.Email);
                $('#Password').val(response.Password);
                $('#Active').val(response.Active);

                $('#modal-title').text('Update');
                $('#submit-btn').text('Update');
                $('#create-update-modal').modal('show');
            }).fail(function(xhr) {
                alert('Error fetching brand details: ' + xhr.responseText);
            });
        }

        function deleteRecord(id) {
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('parties.destroy', ':id') }}".replace(':id', id),
                    data: {
                        _token: "{{ csrf_token() }}" // CSRF token for Laravel
                    },
                    success: function(response) {
                        table.ajax.reload();
                        notyfNew.success({
                            message: response.message,
                            duration: 3000
                        });
                    },
                    error: function(e) {
                        notyfNew.error({
                            message: e.responseJSON?.message || 'An error occurred.',
                            duration: 5000
                        });
                    }
                });
            }
        }

    </script>
   @endpush

     
@endsection

