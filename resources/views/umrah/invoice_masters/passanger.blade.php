@extends('template.tmp')

@section('title', 'Umrah')


@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                
                <form>
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="pnr">PNR</label>
                                        <input type="text" id="pnr" class="form-control" name="pnr" placeholder="Enter PNR">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="visa_no">Visa #</label>
                                        <input type="text" id="visa_no" class="form-control" name="visa_no" placeholder="Enter Visa #">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="visa_date">Visa Date</label>
                                        <input type="date" id="visa_date" class="form-control" name="visa_date" placeholder="Enter Visa Date">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="visa_days">Visa Days</label>
                                        <input type="number" id="visa_days" class="form-control" name="visa_days" placeholder="Enter Visa Days">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="passenger_name">Passenger Name</label>
                                        <input type="text" id="passenger_name" class="form-control" name="passenger_name" placeholder="Enter Passenger Name">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="passport_no">Passport #</label>
                                        <input type="text" id="passport_no" class="form-control" name="passport_no" placeholder="Enter Passport #">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="type">Type</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="Adult">Adult</option>
                                            <option value="Child">Child</option>
                                            <option value="Infant">Infant</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="gender">Gender</label>
                                        <select id="gender" class="form-control" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                
                              
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="relation_type">Relation Type</label>
                                        <select id="relation_type" class="form-control" name="relation_type">
                                            <option value="Head">Head</option>
                                            <option value="Relation">Relation</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="shirka_name">Shirka Name</label>
                                        <input type="text" id="shirka_name" class="form-control" name="shirka_name" placeholder="Enter Shirka Name">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="visa_sale">Visa Sale</label>
                                        <input type="text" id="visa_sale" class="form-control" name="visa_sale" placeholder="Enter Visa Sale">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="ticket_sale">Ticket Sale</label>
                                        <input type="text" id="ticket_sale" class="form-control" name="ticket_sale" placeholder="Enter Ticket Sale">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="visa_purchase">Visa Purchase</label>
                                        <input type="text" id="visa_purchase" class="form-control" name="visa_purchase" placeholder="Enter Visa Purchase">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="ticket_purchase">Ticket Purchase</label>
                                        <input type="text" id="ticket_purchase" class="form-control" name="ticket_purchase" placeholder="Enter Ticket Purchase">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="forex_purchase">Forex Purchase</label> 
                                        <input type="text" id="forex_purchase" class="form-control" name="forex_purchase" placeholder="Enter Foreign Exchange Purchase">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="forex_sale">Forex Sale</label>
                                        <input type="text" id="forex_sale" class="form-control" name="forex_sale" placeholder="Enter Foreign Exchange Sale">
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