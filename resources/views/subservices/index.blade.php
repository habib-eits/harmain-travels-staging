@extends('template.tmp')
@section('title', 'Sub Service Index')
@section('content')

<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Sub Services  List</h4>
                                       <div class="col d-flex justify-content-end">
                                <button type="button" id="importButton" class="btn btn-primary mr-2" data-bs-toggle="modal" data-bs-target=".exampleModal">
                                    Add New
                                </button>
                            </div>    
 
                                </div>
                            </div>
                        </div>
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
 
            
  <div class="card">
      <div class="card-body">

                                <table id="service-table" class="table table-sm table-hover w-100">
                                    <thead >
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="40%">Name</th>
                                            <th width="40%">Service</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ isset($item->service) ? $item->service->name : 'N/A' }}</td>
                                                <td>
                                                    <a href="#" onclick="edit_service({{ $item->id }})">
                                                        <i class="mdi mdi-pencil  font-size-18 align-middle text-secondary"></i>
                                                    </a>
                                                    <a href="#"
                                                        onclick="delete_confirm_n(`subServiceDelete`,'{{ $item->id }}')">
                                                        <i class="mdi mdi-delete font-size-18  align-middle me-1 text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
     
        <!-- Modal -->
        <div class="modal fade exampleModal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Sub Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            
                        </button>
                    </div>
                    <form action="{{ route('subService.store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                             
                                    <div class="row">
                                        <div class="col-12">
                                            <label for=""><strong>Sub Service Name: *</strong></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for=""><strong>Service: *</strong></label>
                                            {{-- <br> --}}
                                            <div >
                                                <select name="service_id" id="service_id"
                                                    class="form-select select2 " required style="width: 100% !important;">
                                                    <option value="">--Select Service--</option>
                                                    @foreach ($services as $service)
                                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                               
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Update Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            
                        </button>
                    </div>
                    <form action="{{ route('subService.update') }}" method="post">
                        @csrf
                        <div class="modal-body">
                          
                                    <div class="row">
                                        <div class="col-12">
                                            <label for=""><strong>Sub Service Name: *</strong></label>
                                            <input type="text" class="form-control" id="updateName" name="name"
                                                required>
                                                <input type="hidden" name="sub_service_id"  id="update_sub_service_id">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for=""><strong>Service: *</strong></label>
                                            {{-- <br> --}}
                                            <div s>
                                                <select name="service_id" id="update_service_id"
                                                    class="form-select select2" required style="width: 100% !important;">
                                                    <option value="">--Select Service--</option>
                                                    @foreach ($services as $service)
                                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                               
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#service-table').DataTable({
                columnDefs: [{
                        orderable: false,
                        targets: [0,3]
                    } // Disable ordering for the first column (checkbox)
                ]
            });
        });
    </script>
    <script>
        function delete_confirm_n(url, id) {
            // alert(url);
            // alert(id);
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    url = "{{ URL::TO('/') }}/" + url + '/' + id;
                    window.location.href = url;
                }
            });

        };

        function edit_service(id) {
            $.ajax({
                url: 'subServiceEdit/' + id,
                type: 'GET',
                success: function(response) {
                    if (response.data) {
                        $('#update_sub_service_id').val(response.data.id);
                        $('#updateName').val(response.data.name);
                        $('#update_service_id').val(response.data.service_id);
                        $('#update_service_id').trigger('change');
                        $('#editModal').modal('show');
                    } else {
                        alert(response.error);
                    }

                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    alert(xhr.responseText);
                    console.error(xhr.responseText);
                }
            })
        };
    </script>
@endsection
