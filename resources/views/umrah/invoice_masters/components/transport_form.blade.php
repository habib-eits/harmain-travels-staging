
<form id="umrah-invoice-transport-form" method="POST">
    @csrf
    <input type="hidden" id="invoice_transport_id" name="invoice_transport_id" value="">
 
    <div class="card shadow-sm" >
        <div class="card-header" style="background-color: #fbebeb !important;">
            <span class="fas fa-bus-alt
"></span> &nbsp;Transportation
        </div>
        <div class="card-body" style="background-color:#FFFAFA !important;">

            <div class="row">
                  
                 <div class="col-md-2">
                    
                        <label class="form-label" for="pnr">Date</label>
                        <input type="date" id="TransportDate" class="form-control" name="TransportDate"
                            placeholder="Enter PNR" value="{{ date('Y-m-d') }}">
                    
                </div>

                     <div class="col-md-2">
                        <label class="form-label" for="client_name">City</label>

                        
                        <select id="TransportCity"  name="TransportCity" class="form-select select2">
                            <option value="">Select</option>
                            @foreach ($location as $row)
                                <option value="{{ $row->location }}">{{ $row->location }}</option>
                            @endforeach
                        </select>        
                        
                </div>

                
                <div class="col-md-3">
                        <label class="form-label" for="client_name">Sector</label>

                        
                        <select name="Sector" id="Sector" class="form-select select2">
                            <option value="">Select</option>
                            @foreach ($sector as $row)
                                <option value="{{ $row->name }}">{{ $row->name }}</option>
                            @endforeach
                        </select>        
                        
                </div>

                
                     <div class="col-md-2">
                        <label class="form-label" for="client_name">Vehicle</label>

                        
                        <select name="VehicleType" id="VehicleType" class="form-select select2">
                            <option value="">Select</option>
                            @foreach ($transport_type as $row)
                                <option value="{{ $row->name }}">{{ $row->name }}</option>
                            @endforeach
                        </select>        
                        
                </div>
         

           
                <div class="col-md-2">
                    
                        <label class="form-label" for="pnr">Status</label>
                       

                            <select name="VehicleStatus" id="VehicleStatus" class="form-select">
          <option value="SHARING" selected>SHARING</option>
          <option value="PRIVATE">PRIVATE</option>
          </select>


                    
                </div>

                <div class="col-md-2">
                    
                        <label class="form-label" for="visa_no">Quantity </label>
                        <input type="number" id="Quantity" class="form-control" name="Quantity"
                            placeholder="" value="1">
                    
                </div>

                    <div class="col-md-2">
                    
                        <label class="form-label" for="visa_no">Pax </label>
                        <input type="text" id="TransportPax" class="form-control" name="TransportPax"
                            placeholder="" value="1">
                    
                </div>
 
                   <div class="col-md-2">
                    
                        <label class="form-label" for="visa_no">Purchase </label>
                        <input type="number" id="TransportPurchase" class="form-control" name="TransportPurchase"
                            placeholder="" value="1" step="0.01">
                    
                </div>

                   <div class="col-md-2">
                    
                        <label class="form-label" for="visa_no">Sale </label>
                        <input type="number" id="TransportSale" class="form-control" name="TransportSale"
                            placeholder="" value="1" step="0.01">
                    
                </div>

                   <div class="col-md-2">
                    
                        <label class="form-label" for="visa_no">Payable </label>
                        <input type="number" id="TransportPayable" class="form-control" name="TransportPayable"
                            placeholder="" value="1" step="0.01" readonly>
                    
                </div>

                   <div class="col-md-2">
                    
                        <label class="form-label" for="visa_no">Receivable </label>
                        <input type="number" id="TransportReceivable" class="form-control" name="TransportReceivable"
                            placeholder="" value="1" step="0.01" readonly>
                    
                </div>



                <div class="col-md-1">
                    
                        <label class="form-label" for="visa_no">Flight </label>
                        <input type="text" id="Flight" class="form-control" name="Flight"
                            placeholder="" value="1">
                    
                </div>



                <div class="col-md-1">
                    
                        <label class="form-label" for="visa_no">Pickup Time </label>
                        <input type="text" id="PickupTime" class="form-control" name="PickupTime"
                            placeholder="" value="1">
                    
                </div>



                <div class="col-md-2">
                        <label class="form-label" for="client_name">PickFrom</label>

                        
                        <select name="PickFrom" id="PickFrom" class="form-select select2">
                            <option value="">Select</option>
                            @foreach ($location as $row)
                                <option value="{{ $row->location }}">{{ $row->location }}</option>
                            @endforeach
                        </select>        
                        
                </div>


                <div class="col-md-2">
                        <label class="form-label" for="client_name">Destination</label>

                        
                        <select name="DestinationTo" id="DestinationTo" class="form-select select2">
                            <option value="">Select</option>
                            @foreach ($location as $row)
                                <option value="{{ $row->location }}">{{ $row->location }}</option>
                            @endforeach
                        </select>        
                        
                </div>


                    <div class="col-md-1">
                    
                        <label class="form-label" for="visa_no">BRN Code </label>
                        <input type="text" id="TransportBrnCode" class="form-control" name="TransportBrnCode"
                            placeholder="" value="1">
                    
                </div>

     <div class="col-md-1">
                   
                        <label class="form-label" for="visa_no">Confirmation</label>
                        <input type="text" id="TCN" class="form-control" name="TCN"
                            placeholder="" value="1">
                    
                </div>
     <div class="col-md-2">
                        <label class="form-label" for="client_name">Transport Supplier</label>

                        
                        <select name="TransportSupplier" id="TransportSupplier" class="form-select select2">
                            <option value="">Select</option>
                            @foreach ($supplier as $row)
                                <option value="{{ $row->PartyID }}">{{ $row->PartyID }}-{{ $row->PartyName }}</option>
                            @endforeach
                        </select>        
                        
                </div>


               
                
                <div class="col-md-1">
                   
                        <label class="form-label" for="visa_no">ROE Purchase </label>
                        <input type="text" id="ExRatePurchaseTransport" class="form-control" name="ExRatePurchaseTransport"
                            placeholder="" value="1">
                    
                </div>


                <div class="col-md-1">
                   
                        <label class="form-label" for="visa_no">ROE Sale </label>
                        <input type="text" id="ExRateSaleTransport" class="form-control" name="ExRateSaleTransport"
                            placeholder="" value="1">
                    
                </div>





 



                <div class="clearfix m-3"></div>

                     <div class="d-flex align-items-center gap-3">
        <button type="button" class="btn btn-rounded btn-warning btn-block"  style="width: 220px;" id="SaveTransport">Save Sector</button>
        <button type="button" class="btn btn-rounded btn-success " style="width: 220px;" id="ModifyTransport">Modify Sector</button>
        <button type="button" class="btn btn-rounded btn-danger " style="width: 220px;" id="DeleteTransport">Delete Sector</button>
        <button type="button" class="btn btn-rounded bg-dark text-white " style="width: 220px;" id="AddNewSector">Add New</button>
        <button type="button" class="btn btn-rounded btn-primary " style="width: 220px;" id="ChangeSector">Change Sector</button>



      
      </div>

                
            </div>
        </div>
    </div>
 
</form>