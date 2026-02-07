@extends('template.tmp')
@section('title', 'Edit PNR')
@section('content')
   <div class="main-content">

 <div class="page-content">
 <div class="container-fluid">

  <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Edit PNR - {{ $pnr->pnr }}</h4>
                                       <div class="col d-flex justify-content-end">
                             
                                 <a href="{{ route('pnr.index') }}" class="btn btn-secondary mr-2">
                                    Back to List
                                </a>
                            </div>    

                                </div>
                            </div>
                        </div>

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
                <form action="{{ route('pnr.update', $pnr->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                              <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pnr" class="form-label"><strong>PNR Number *</strong></label>
                                <input type="text" name="pnr" id="pnr" class="form-control" value="{{ old('pnr', $pnr->pnr) }}" required>
                            </div>
                     </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="branch_id" class="form-label"><strong>Branch *</strong></label>
                                <select name="branch_id" id="branch_id" class="form-select" required>
                                     @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ (old('branch_id', $pnr->branch_id) == $branch->id) ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="UserID" class="form-label"><strong>User *</strong></label>
                                <select name="UserID" id="UserID" class="form-select" required>
                                     @foreach($users as $user)
                                        <option value="{{ $user->UserID }}" {{ (old('UserID', $pnr->UserID) == $user->UserID) ? 'selected' : '' }}>
                                            {{ $user->FullName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                  

                   
  </div>
                    <hr>
                    <h5>Departure Flight Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightNoDeparture" class="form-label">Flight Number</label>
                                <input type="text" name="FlightNoDeparture" id="FlightNoDeparture" class="form-control" value="{{ old('FlightNoDeparture', $pnr->FlightNoDeparture) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="SectorDeparture" class="form-label">Sector</label>
                                <input type="text" name="SectorDeparture" id="SectorDeparture" class="form-control" value="{{ old('SectorDeparture', $pnr->SectorDeparture) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightDateDeparture" class="form-label">Departure Date</label>
                                <input type="date" name="FlightDateDeparture" id="FlightDateDeparture" class="form-control" value="{{ old('FlightDateDeparture', $pnr->FlightDateDeparture) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightTimeDeparture" class="form-label">Departure Time</label>
                                <input type="time" name="FlightTimeDeparture" id="FlightTimeDeparture" class="form-control" value="{{ old('FlightTimeDeparture', $pnr->FlightTimeDeparture) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightDateArrivalDeparture" class="form-label">Arrival Date</label>
                                <input type="date" name="FlightDateArrivalDeparture" id="FlightDateArrivalDeparture" class="form-control" value="{{ old('FlightDateArrivalDeparture', $pnr->FlightDateArrivalDeparture) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightTimeArrivalDeparture" class="form-label">Arrival Time</label>
                                <input type="time" name="FlightTimeArrivalDeparture" id="FlightTimeArrivalDeparture" class="form-control" value="{{ old('FlightTimeArrivalDeparture', $pnr->FlightTimeArrivalDeparture) }}">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>Return Flight Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightNoReturn" class="form-label">Flight Number</label>
                                <input type="text" name="FlightNoReturn" id="FlightNoReturn" class="form-control" value="{{ old('FlightNoReturn', $pnr->FlightNoReturn) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="SectorReturn" class="form-label">Sector</label>
                                <input type="text" name="SectorReturn" id="SectorReturn" class="form-control" value="{{ old('SectorReturn', $pnr->SectorReturn) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightDateReturn" class="form-label">Departure Date</label>
                                <input type="date" name="FlightDateReturn" id="FlightDateReturn" class="form-control" value="{{ old('FlightDateReturn', $pnr->FlightDateReturn) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightDepartureTimeReturn" class="form-label">Departure Time</label>
                                <input type="time" name="FlightDepartureTimeReturn" id="FlightDepartureTimeReturn" class="form-control" value="{{ old('FlightDepartureTimeReturn', $pnr->FlightDepartureTimeReturn) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightArrivalDateReturn" class="form-label">Arrival Date</label>
                                <input type="date" name="FlightArrivalDateReturn" id="FlightArrivalDateReturn" class="form-control" value="{{ old('FlightArrivalDateReturn', $pnr->FlightArrivalDateReturn) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="FlightArrivalTimeReturn" class="form-label">Arrival Time</label>
                                <input type="time" name="FlightArrivalTimeReturn" id="FlightArrivalTimeReturn" class="form-control" value="{{ old('FlightArrivalTimeReturn', $pnr->FlightArrivalTimeReturn) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Update PNR</button>
                            <a href="{{ route('pnr.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection

