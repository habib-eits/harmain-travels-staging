<form id="umrah-invoice-passenger-form" method="POST">
    @csrf
    <input type="hidden" id="umrah_invoice_passenger_id" name="umrah_invoice_passenger_id" value="">
       
    <div class="card shadow-sm " >
        <div class="card-header" style="background-color: #e9f7f9b6 !important;">  <span class="fas fa-users
"></span> Pessanger Detail</div>
        <div class="card-body" style="background-color: #e9f7f989 !important;" >

            <div class="row">
                  <div class="col-md-3 d-none">
                        <label class="form-label" for="client_name">Client Name</label>

                      <div class="d-flex gap-2 align-items-center">
    <select name="GroupNo" id="GroupNo" class="form-select select2" style="width:100%;">
        <option value="">Select</option>
        @foreach ($party as $row)
            <option value="{{ $row->PartyID }}">{{ $row->PartyName }}</option>
        @endforeach
    </select>
    <button id="ShowGroupNo" type="button" class="btn btn-warning">Load</button>
</div>

                </div>

 
         

           
                <div class="col-md-2 d-none">
                    <div class="mb-3">
                        <label class="form-label" for="pnr">PNR</label>
                        <input type="text" id="pnr" class="form-control uppercase" name="pnr">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="visa_no">Visa #</label>
                        <input type="text" id="visa_no" class="form-control" name="visa_no">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="visa_date">Visa Date</label>
                        <input type="date" id="visa_date" class="form-control" name="visa_date"
                             value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="visa_days">Visa Days</label>
                        <input type="number" id="visa_days" class="form-control" name="visa_days"">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label" for="passenger_name">Passenger Name</label>
                        <input type="text" id="passenger_name" class="form-control uppercase"
                            name="passenger_name"  >
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="passport_no">Passport #</label>
                        <input type="text" id="passport_no" class="form-control uppercase" name="passport_no"
                            >
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="type">Type</label>
                        <select id="type" class="form-select" name="type">
                            <option value="ADULT">ADULT</option>
                            <option value="CHILD">CHILD</option>
                            <option value="INFANT">INFANT</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="gender">Gender</label>
                        <select id="gender" class="form-select" name="gender">
                             <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="relation_type">Relation Type</label>
                        <select id="relation_type" class="form-select" name="relation_type">
                            <option value="Head">Head</option>
                            <option value="Relation">Relation</option>
                            
                        </select>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="11">Relation</label>
                        <select id="relation" class="form-select" name="relation">
                            <option value="Self">Self</option>
                            </select>
                    </div>
                </div>


                
              


                    <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="passport_no">DOB</label>
                        <input type="DATE" id="dob" class="form-control uppercase" name="dob">
                    </div>
                </div>

                    <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="passport_no">Nationality</label>
                        <input type="text" id="nationality" class="form-control uppercase" name="nationality">
                    </div>
                </div>
                
                
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="passport_no">Contact</label>
                        <input type="text" id="contact" class="form-control uppercase" name="contact">
                    </div>
                </div>

                     <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="relation_type">Visa Type</label>
                        <select id="visa_type" class="form-select" name="visa_type">
                            <option value="Umrah">Umrah</option>
                            <option value="Tourist">Tourist</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="shirka_name">Shirka Name</label>
                       
                        <select id="shirka_id" class="form-select select2" name="shirka_id">
                           @foreach ($shirka as $item)
                           <option value="{{$item->id}}">{{$item->shirka_name}}</option> 
                           @endforeach
                        </select>
                        
                        
                        
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="visa_purchase">Visa Purchase</label>
                        <input type="text" id="visa_purchase" class="form-control"
                            name="visa_purchase" value="0">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="visa_sale">Visa Sale</label>
                        <input type="text" id="visa_sale" class="form-control" name="visa_sale"
                            placeholder="Enter Visa Sale" value="0">
                    </div>
                </div>

                   <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="ticket_purchase">Ticket Purchase</label>
                        <input type="text" id="ticket_purchase" class="form-control"
                            name="ticket_purchase" placeholder="0" value="0">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label" for="ticket_sale">Ticket Sale</label>
                        <input type="text" id="ticket_sale" class="form-control" name="ticket_sale"
                            placeholder="Enter Ticket Sale" value="0">
                    </div>
                </div>


             
                 <div class="col-md-2">
                    
                        <label class="form-label" for="forex_purchase">Forex Purchase</label>
                        <input type="text" id="forex_purchase" class="form-control"
                            name="forex_purchase" placeholder="0" value="1">
                 </div>

                <div class="col-md-2">
                    
                        <label class="form-label" for="forex_sale">Forex Sale</label>
                        <input type="text" id="forex_sale" class="form-control" name="forex_sale"
                            placeholder="Enter Foreign Exchange Sale" value="1">
                    
                </div>



                <div class="clearfix"></div>

                
            </div>
        </div>
    </div>
        <div class="d-flex flex-wrap gap-2   mb-4">

        <button 
            type="button" 
            id="save-umrah-invoice-passanger-btn"
            class="btn btn-primary waves-effect waves-light  me-2 btn-rounded" style="width: 220px;">
            Save
        </button>
        <button 
            type="button" 
            id="modify-umrah-invoice-passanger-btn"
            disabled
            class="btn btn-warning waves-effect waves-light  me-2 btn-rounded" style="width: 220px;">
            Modify
        </button>
        <button 
            type="button" 
            id="save-as-umrah-invoice-passanger-btn"
             disabled
            class="btn btn-success waves-effect waves-light  me-2 btn-rounded" style="width: 220px;">
            Save As
        </button>
        <button 
            type="button" 
            id="delete-umrah-invoice-passanger-btn"
             disabled
             
            class="btn btn-danger waves-effect waves-light  me-2 btn-rounded" style="width: 220px;">
           Delete
        </button>
        <button id="cancel-btn" class="btn btn-secondary waves-effect me-2 btn-rounded" style="width: 220px;">
            Cancel
        </button>

        <a 
            href="#" 
            class="btn btn-dark waves-effect waves-light  me-2 btn-rounded" style="width: 220px;"
            data-bs-toggle="modal" 
            data-bs-target="#update-all-pax-rate-model">
            Update All Pax Rate
        </a>
    </div>
</form>