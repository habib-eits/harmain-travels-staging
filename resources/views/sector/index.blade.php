@extends('admin.layouts.admin')

@section('title', 'Sector')

@section('content')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h3 class="mb-sm-0 font-size-18">All {{ $title }}</h3>

                <div class="page-title-right d-flex">

                    <div class="page-btn">
                        <a href="#" class="btn btn-added btn-success btn-rounded w-md" onclick="addRecord()">
                            <i class="me-2 bx bx-plus"></i>Add
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if (session('flash-danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('flash-danger') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="table" class="table table-striped table-sm " style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sector Name</th>
                                <th>Status</th>

                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- General Modal for Create/Edit -->
    <div class="table-responsivee">
        <div class="modal fade" id="create-update-modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">

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



                                <div class="clearfix"></div>


                                <div class="col-md-12 mb-1">
                                    <label class="form-label">Sector Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>






                                <div class="col-md-12">
                                    <label class="form-label">Active</label>
                                    <select name="Status" id="Status" class="form-select">
                                        <option value="1">Enable</option>
                                        <option value="2">Disable</option>
                                        <option value="3">Deleted</option>
                                    </select>
                                </div>

                            </div>

                            <div class="modal-footer-btn mt-3">
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

    <script>
        var table = null;
        $(document).ready(function() {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sector.index') }}",
                columns: [

                    {
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'Status',
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
                var btn = $('#submit-btn');
                tinymce.triggerSave(); // Important: sync content

                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('sector.store') }}",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: formData,
                    enctype: "multipart/form-data",
                    beforeSend: function() {
                        btn.prop('disabled', true);
                        btn.html('Processing');
                    },
                    success: function(response) {

                        $('#create-update-modal').modal('hide');
                        $('#create-update-form')[0].reset(); // Reset all form data
                        table.ajax.reload();

                        notyf.success({
                            message: response.message,
                            duration: 3000
                        });
                        btn.prop('disabled', false);
                        btn.html('Create');
                    },
                    error: function(e) {
                        btn.prop('disabled', false);
                        btn.html('Create');
                        notyf.error({
                            message: e.responseJSON.message,
                            duration: 5000
                        });
                    }
                });
            });

        });

        // Handle the delete button click
        function addRecord() {
            $('#modal-title').text('Create');
            $('#record-id').val(''); // Clear the hidden input
            $('#submit-btn').text('Create');
            $('#create-update-modal').modal('show');
        }

        function editRecord(id) {
            var btn = $('#submit-btn');
            $.get("{{ route('sector.edit', ':id') }}".replace(':id', id), function(response) {
                $('#record-id').val(response.data.id);
                $('#name').val(response.data.name);



                ;
                $('#Status').val(response.data.Status);

                  




                $('#modal-title').text('Update');
                $('#submit-btn').text('Update');

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
                    url: "{{ route('sector.destroy', ':id') }}".replace(':id', id),
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

    <script src="{{ URL('/assets/js/tinymce.min.js') }}"></script>
    <script id="rendered-js">
        tinymce.init({
            selector: "textarea:not(.kashif)", // Select all textarea exluding the mceNoEditor class
            height: 200,
            menubar: false,
            paste_data_images: true, // ✅ Allow image pasting
            plugins: [
                'advlist autolink lists link image charmap print preview anchor textcolor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code help wordcount'
            ],
            mobile: {
                theme: 'mobile'
            },
            toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tiny.cloud/css/codepen.min.css'
            ],
        });
        //# sourceURL=pen.js
    </script>

@endsection
