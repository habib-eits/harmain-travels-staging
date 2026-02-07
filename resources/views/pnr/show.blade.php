@extends('template.tmp')
@section('title', 'View PNR')
@section('content')
   <div class="main-content">

 <div class="page-content">
 <div class="container-fluid">

  <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">PNR Details - {{ $pnr->pnr }}</h4>
                                       <div class="col d-flex justify-content-end">
                             
                                 <a href="{{ route('pnr.index') }}" class="btn btn-secondary mr-2">
                                    Back to List
                                </a>
                                <a href="{{ route('pnr.edit', $pnr->id) }}" class="btn btn-warning mr-2">
                                    Edit PNR
                                </a>
                            </div>    

                                </div>
                            </div>
                        </div>

            <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Basic Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>PNR Number:</strong></td>
                                <td>{{ $pnr->pnr }}</td>
                            </tr>
                            <tr>
                                <td><strong>Branch:</strong></td>
                                <td>{{ $pnr->branch->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>User:</strong></td>
                                <td>{{ $pnr->user->FullName ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created At:</strong></td>
                                <td>{{ $pnr->created_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Updated At:</strong></td>
                                <td>{{ $pnr->updated_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h5>Departure Flight Details</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Flight Number:</strong></td>
                                <td>{{ $pnr->FlightNoDeparture ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Sector:</strong></td>
                                <td>{{ $pnr->SectorDeparture ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Departure Date:</strong></td>
                                <td>{{ $pnr->FlightDateDeparture ? \Carbon\Carbon::parse($pnr->FlightDateDeparture)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Departure Time:</strong></td>
                                <td>{{ $pnr->FlightTimeDeparture ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Arrival Date:</strong></td>
                                <td>{{ $pnr->FlightDateArrivalDeparture ? \Carbon\Carbon::parse($pnr->FlightDateArrivalDeparture)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Arrival Time:</strong></td>
                                <td>{{ $pnr->FlightTimeArrivalDeparture ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5>Return Flight Details</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Flight Number:</strong></td>
                                <td>{{ $pnr->FlightNoReturn ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Sector:</strong></td>
                                <td>{{ $pnr->SectorReturn ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Departure Date:</strong></td>
                                <td>{{ $pnr->FlightDateReturn ? \Carbon\Carbon::parse($pnr->FlightDateReturn)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Departure Time:</strong></td>
                                <td>{{ $pnr->FlightDepartureTimeReturn ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Arrival Date:</strong></td>
                                <td>{{ $pnr->FlightArrivalDateReturn ? \Carbon\Carbon::parse($pnr->FlightArrivalDateReturn)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Arrival Time:</strong></td>
                                <td>{{ $pnr->FlightArrivalTimeReturn ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <a href="{{ route('pnr.edit', $pnr->id) }}" class="btn btn-warning">Edit PNR</a>
                        <a href="#" onclick="delete_confirm('{{ route('pnr.destroy', $pnr->id) }}')" class="btn btn-danger">Delete PNR</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

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

