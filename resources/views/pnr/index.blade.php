@extends('template.tmp')
@section('title', 'PNR Management')
@section('content')
   <div class="main-content">

 <div class="page-content">
 <div class="container-fluid">

  <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">PNR Management</h4>
                                       <div class="col d-flex justify-content-end">
                             
                                 <a href="{{ route('pnr.create') }}" class="btn btn-primary mr-2">
                                    Add New PNR
                                </a>
                            </div>    

                                </div>
                            </div>
                        </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
@if(Session::has('success'))
  toastr.options =
  {
    "closeButton" : false,
    "progressBar" : true
  }
        Command: toastr["success"]("{{session('success')}}")
  @endif
</script>

 @if (session('success'))
 <div class="alert alert-success" id="success-alert">
    {{ Session::get('success') }}  
 </div>
@endif

 @if (count($errors) > 0)
 <div >
    <div class="alert alert-danger p-1 border-3">
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
                        <table id="pnr-table" class="table table-sm table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>PNR</th>
                                    <th>Branch</th>
                                    <th>User</th>
                                    <th>Departure Flight</th>
                                    <th>Departure Date</th>
                                    <th>Return Flight</th>
                                    <th>Return Date</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pnrs as $key => $pnr)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            <strong>{{ $pnr->pnr }}</strong>
                                        </td>
                                        <td>
                                            {{ $pnr->branch->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $pnr->user->FullName ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $pnr->FlightNoDeparture ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $pnr->FlightDateDeparture ? \Carbon\Carbon::parse($pnr->FlightDateDeparture)->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $pnr->FlightNoReturn ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $pnr->FlightDateReturn ? \Carbon\Carbon::parse($pnr->FlightDateReturn)->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $pnr->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('pnr.show', $pnr->id) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="mdi mdi-eye font-size-16"></i>
                                            </a>
                                            <a href="{{ route('pnr.edit', $pnr->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="mdi mdi-pencil font-size-16"></i>
                                            </a>
                                            <a href="#" onclick="delete_confirm('{{ route('pnr.destroy', $pnr->id) }}')" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="mdi mdi-delete font-size-16"></i>
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
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#pnr-table').DataTable({
            columnDefs: [{
                orderable: false,
                targets: [9] // Disable ordering for the action column
            }]
        });
    });
</script>

<script>
    function delete_confirm(url) {
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
            // Create a form to submit the DELETE request
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            var methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
        });
    };
</script>
@endsection
