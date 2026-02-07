@extends('template.tmp')

@section('title', 'Umrah')


@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                
                <form action="{{ route('umrah-invoice-master.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="issue_date">Issue Date</label>
                                        <input type="date" id="issue_date" class="form-control" name="issue_date" value="{{ $data->issue_date }}" placeholder="Enter Issue Date">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="client_name">Client Name</label>
                                        <input type="text" id="client_name" class="form-control" name="client_name" value="{{ $data->client_name }}" placeholder="Enter Client Name">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="ref_no">Ref Number</label>
                                        <input type="text" id="ref_no" class="form-control" name="ref_no" value="{{ $data->ref_no }}" placeholder="Enter Reference Number">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="sub_agent">Sub Agent</label>
                                        <input type="text" id="sub_agent" class="form-control" name="sub_agent" value="{{ $data->sub_agent }}" placeholder="Enter Sub Agent">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="package_name">Package Name</label>
                                        <input type="text" id="package_name" class="form-control" name="package_name" value="{{ $data->package_name }}" placeholder="Enter Package Name">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" id="email" class="form-control" name="email" value="{{ $data->email }}" placeholder="Enter Email">
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>
    
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                        
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="dep_flight_no">Flight # Departure</label>
                                        <input type="text" id="dep_flight_no" class="form-control" name="dep_flight_no" value="{{ $data->dep_flight_no }}" placeholder="Enter Flight # Departure">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="dep_sector">Sector</label>
                                        <input type="text" id="dep_sector" class="form-control" name="dep_sector" value="{{ $data->dep_sector }}" placeholder="Enter Sector">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="dep_date">Dept. Date</label>
                                        <input type="date" id="dep_date" class="form-control" name="dep_date" value="{{ $data->dep_date }}" placeholder="Enter Departure Date">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="dep_time">Dept. Time</label>
                                        <input type="time" id="dep_time" class="form-control" name="dep_time" value="{{ $data->dep_time }}" placeholder="Enter Departure Time">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="dep_arr_date">Arrival Date</label>
                                        <input type="date" id="dep_arr_date" class="form-control" name="dep_arr_date" value="{{ $data->dep_arr_date }}" placeholder="Enter Arrival Date">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="dep_arr_time">Arrival Time</label>
                                        <input type="time" id="dep_arr_time" class="form-control" name="dep_arr_time" value="{{ $data->dep_arr_time }}" placeholder="Enter Arrival Time">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="ret_flight_no">Flight # Return</label>
                                        <input type="text" id="ret_flight_no" class="form-control" name="ret_flight_no" value="{{ $data->ret_flight_no }}" placeholder="Enter Flight # Return">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="ret_sector">Sector</label>
                                        <input type="text" id="ret_sector" class="form-control" name="ret_sector" value="{{ $data->ret_sector }}" placeholder="Enter Sector">
                                    </div>
                                </div>
    
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="ret_date">Return Date</label>
                                        <input type="date" id="ret_date" class="form-control" name="ret_date" value="{{ $data->ret_date }}" placeholder="Enter Return Date">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="ret_dep_time">Dept. Time</label>
                                        <input type="time" id="ret_dep_time" class="form-control" name="ret_dep_time" value="{{ $data->ret_dep_time }}" placeholder="Enter Departure Time (Return)">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="ret_arr_date">Arrival Date</label>
                                        <input type="date" id="ret_arr_date" class="form-control" name="ret_arr_date" value="{{ $data->ret_arr_date }}" placeholder="Enter Arrival Date (Return)">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="ret_arr_time">Arrival Time</label>
                                        <input type="time" id="ret_arr_time" class="form-control" name="ret_arr_time" value="{{ $data->ret_arr_time }}" placeholder="Enter Arrival Time (Return)">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
    
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="sale_rate">Sale Rate</label>
                                        <input type="number" step="0.01" id="sale_rate" class="form-control" name="sale_rate" value="{{ $data->sale_rate }}" placeholder="Enter Sale Rate">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="sale_cur">Sale Currency</label>
                                        <input type="number" step="0.01" id="sale_cur" class="form-control" name="sale_cur" value="{{ $data->sale_cur }}" placeholder="Enter Sale Currency">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="ticket_cur">Currency Ticket</label>
                                        <input type="number" step="0.01" id="ticket_cur" class="form-control" name="ticket_cur" value="{{ $data->ticket_cur }}" placeholder="Enter Currency Ticket">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="purchase_rate">Purchase Rate</label>
                                        <input type="number" step="0.01" id="purchase_rate" class="form-control" name="purchase_rate" value="{{ $data->purchase_rate }}" placeholder="Enter Purchase Rate">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="purchase_cur">Purchase Currency</label>
                                        <input type="number" step="0.01" id="purchase_cur" class="form-control" name="purchase_cur" value="{{ $data->purchase_cur }}" placeholder="Enter Purchase Currency">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="flight_nights">Flight Nights</label>
                                        <input type="number" id="flight_nights" class="form-control" name="flight_nights" value="{{ $data->flight_nights }}" placeholder="Enter Flight Nights">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2  justify-content-end">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-secondary waves-effect">
                            Cancel
                        </button>
                    </div>
                </form>


           
        </div>
    </div>

    </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    </div>

@endsection