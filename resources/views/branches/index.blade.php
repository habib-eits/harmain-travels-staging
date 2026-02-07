 @extends('template.tmp')
@section('title', 'Status Index')
@section('content')
   <div class="main-content">

 <div class="page-content">
 <div class="container-fluid">



 

  <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Branches List</h4>
                                       <div class="col d-flex justify-content-end">
                             
                                 <button type="button" id="importButton" class="btn btn-primary mr-2" data-bs-toggle="modal" data-bs-target=".exampleModal">
                                    Add New
                                </button>
                            </div>    
 
                                </div>
                            </div>
                        </div>


 <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
 <script>
@if(Session::has('error'))
  toastr.options =
  {
    "closeButton" : false,
    "progressBar" : true
  }
        Command: toastr["{{session('class')}}"]("{{session('error')}}")
  @endif
</script>

 @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} " id="success-alert">
                    
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
                <div class="row">
                    <div class="col-12">
                        <table id="branch-table" class="table table-sm table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                    <th>Logo</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <td>
                                            {{ ++$key }}
                                        </td>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->location }}
                                        </td>
                                        <td>
                                            {{ $item->tel }}
                                        </td>
                                        <td>
                                            {{ $item->email }}
                                        </td>
                                        <td>
                                            @if ($item->logo)
                                                <img src="{{ asset('storage/' . $item->logo) }}" alt="Logo" width="50" height="50">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" onclick="edit_branch({{ $item->id }})">
                                                <i class="mdi mdi-pencil font-size-18 align-middle text-secondary"></i>
                                            </a>
                                            <a href="#"
                                                onclick="delete_confirm_n(`branchDelete`,'{{ $item->id }}')">
                                                <i class="mdi mdi-delete font-size-18 align-middle me-1 text-danger"></i>
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
                <h5 class="modal-title" id="exampleModalLabel">Create Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                 </button>
                </div>
                <form action="{{ route('branch.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    
                        <div class="row">
                        <div class="col-12">
                            <label for="name"><strong>Name: *</strong></label>
                            <input type="text" name="name" id="name" required class="form-control" value="{{ old('name') }}">
                        </div>
                        <div class="col-12">
                            <label for="location"><strong>Location:</strong></label>
                            <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
                        </div>
                        <div class="col-12">
                            <label for="tel"><strong>Contact Number:</strong></label>
                            <input type="tel" name="tel" id="tel" class="form-control" value="{{ old('tel') }}">
                        </div>
                        <div class="col-12">
                            <label for="email"><strong>Email:</strong></label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-12">
                            <label for="logo"><strong>Logo:</strong></label>
                            <input type="file" name="logo" id="logo" class="form-control">
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
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Update Branch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                         </button>
                    </div>
                    <form action="{{ route('branch.update') }}" method="post" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <label for="name"><strong>Name: *</strong></label>
                                    <input type="text" name="name" id="update_name" required class="form-control">
                                    <input type="hidden" name="id" id="update_id" required class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="location"><strong>Location:</strong></label>
                                    <input type="text" name="location" id="update_location" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="tel"><strong>Contact Number:</strong></label>
                                    <input type="tel" name="tel" id="update_tel" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="email"><strong>Email:</strong></label>
                                    <input type="email" name="email" id="update_email" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="logo"><strong>Logo:</strong></label>
                                    <input type="file" name="logo" id="update_logo" class="form-control">
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
            $('#branch-table').DataTable({
                columnDefs: [{
                        orderable: false,
                        targets: [0, 4]
                    } // Disable ordering for the first column (checkbox)
                ]
            });
        });
    </script>
    <script>
        function delete_confirm_n(url, id) {
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

        function edit_branch(id) {
            $.ajax({
            url: 'branchEdit/' + id,
            type: 'GET',
            success: function(response) {
                if (response.data) {
                $('#update_id').val(response.data.id);
                $('#update_name').val(response.data.name);
                $('#update_location').val(response.data.location);
                $('#update_tel').val(response.data.tel);
                $('#update_email').val(response.data.email);
                if (response.data.logo) {
                    $('#update_logo').after('<img src="{{ asset('storage/') }}/' + response.data.logo + '" alt="Logo" width="50" height="50" class="mt-2">');
                }
                $('#editModal').modal('show');
                } else {
                alert(response.error);
                }
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
                console.error(xhr.responseText);
            }
            })
        };
        </script>
@endsection
