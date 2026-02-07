@extends('template.tmp')
@section('title', 'Staff Index')
@section('content')
<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Staff List</h4>
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
         
                 <table id="staff-table" class="table table-sm table-hover w-100">
                                    <thead  >
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Contact Number</th>
                                            <th>Branch</th>
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
                                                    {{ $item->name != null ? $item->name : 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $item->email != null ? $item->email : 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $item->role != null ? $item->role : 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $item->tel != null ? $item->tel : 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ isset($item->branch) ? $item->branch->name : 'N/A' }}
                                                </td>
                                                <td>
                                                    <a href="#" onclick="edit_staff({{ $item->id }})">
                                                        <i class="mdi mdi-pencil font-size-18 align-middle text-secondary"></i>
                                                    </a>
                                                    <a href="#"
                                                        onclick="delete_confirm_n(`staffMemberDelete`,'{{ $item->id }}')">
                                                        <i class="mdi mdi-delete  font-size-18 align-middle me-1 text-danger"></i>
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
        <div class="modal fade exampleModal " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Staff </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            
                        </button>

                    </div>
                    <form action="{{ route('staff.store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            
                                    <small class="text-primary"><em>Input fields marked with <span class="text-danger">*</span> are the required
                                            fields</em></small>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="name"><strong>Full Name <span class="text-danger">*</span></strong></label>
                                            <input type="text" name="name" id="name" required
                                                class="form-control" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for="email"><strong>Email <span class="text-danger">*</span></strong></label>
                                            <input type="email" name="email" id="email" required
                                                class="form-control" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for="tel"><strong>Contact Number:</strong></label>
                                            <input type="tel" name="tel" id="tel" class="form-control"
                                                value="{{ old('tel') }}">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for="role"><strong>Role <span class="text-danger">*</span></strong></label>
                                            <select name="role" id="role" class="form-select" required>
                                                <option value="" selected disabled>--Select Role--</option>
                                                @foreach ($roles as $item)
                                                    <option value="{{ $item->name }}"
                                                        {{ old('role') == $item->name ? 'selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for="branch_id"><strong>Branch <span class="text-danger">*</span></strong></label>
                                            <select name="branch_id" id="branch_id" class="form-select" required>
                                                <option value="" selected disabled>--Select Branch--</option>
                                                  @foreach ($branches as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>

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
                    <div class="modal-header bg-secondary ">
                        <h5 class="modal-title text-white" id="editModalLabel">Update Staff </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                             
                        </button>
                    </div>
                    <form action="{{ route('staff.update') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            
                                    <small class="text-primary"><em>Input fields marked with <span class="text-danger">*</span> are the required
                                            fields</em></small>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="name"><strong>Full Name <span class="text-danger">*</span></strong></label>
                                            <input type="text" name="name" id="update_name" required
                                                class="form-control">
                                            <input type="hidden" name="id" id="update_id" required
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for="email"><strong>Email <span class="text-danger">*</span></strong></label>
                                            <input type="email" name="email" id="update_email" required
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for="tel"><strong>Contact Number:</strong></label>
                                            <input type="tel" name="tel" id="update_tel" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for="role"><strong>Role <span class="text-danger">*</span></strong></label>
                                            <select name="role" id="update_role" class="form-select" required>
                                                <option value=""disabled>--Select Role--</option>
                                                @foreach ($roles as $item)
                                                    <option value="{{ $item->name }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label for="branch_id"><strong>Branch <span class="text-danger">*</span></strong></label>
                                            <select name="branch_id" id="update_branch_id" class="form-select" required>
                                                <option value="" disabled>--Select Branch--</option>
                                                @foreach ($branches as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
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
            $('#staff-table').DataTable({
                columnDefs: [{
                        orderable: false,
                        targets: [0, 6]
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

        function edit_staff(id) {
            $.ajax({
                url: 'staffMemberEdit/' + id,
                type: 'GET',
                success: function(response) {
                    if (response.data) {
                        $('#update_id').val(response.data.id);
                        $('#update_name').val(response.data.name);
                        $('#update_email').val(response.data.email);
                        $('#update_tel').val(response.data.tel);
                        $('#update_role').val(response.data.role);
                        $('#update_branch_id').val(response.data.branch_id);
                        $('#update_role').trigger('change');
                        $('#update_branch_id').trigger('change');

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
