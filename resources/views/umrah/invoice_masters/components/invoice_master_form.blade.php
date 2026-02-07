<form id="umrah-invoice-master-form" method="POST">
    <input type="hidden" id="umrah_invoice_master_id" name="umrah_invoice_master_id"
        value="{{ $invoice_master->InvoiceMasterID ?? '' }}">
    {{-- <input type="number" id="umrah_invoice_master_id" name="umrah_invoice_master_id" value="4250"  > --}}

    @csrf
    <div class="card shadow-sm">

        <div class="card-header "><span class="fas fa-user-friends"></span> &nbsp;Invoice Details</div>
        <div class="card-body" style="background-color: #f9f9f9 !important; ">
            <div class="row">
                <div class="col-md-2">

                    <label class="form-label" for="issue_date">Issue Date</label>
                    <input type="date" id="Date" class="form-control" name="Date" placeholder="Enter Date"
                        value="{{ $invoice_master->Date ?? date('Y-m-d') }}">

                </div>

                <div class="col-md-3">
                    <label class="form-label" for="client_name">Client Name</label>
                <div class="d-flex gap-2 align-items-center">
    <select name="PartyID" id="PartyID" class="form-select select2" style="width: 100%;">
        <option value="">Select</option>
        @foreach ($party as $row)
            <option value="{{ $row->PartyID }}"
                {{ $row->PartyID == ($invoice_master->PartyID ?? null) ? 'selected' : '' }}>
                {{ $row->PartyID }} - {{ $row->PartyName }}
            </option>
        @endforeach
    </select>

    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
        data-bs-target="#addClientModal">
        Add
    </button>
</div>

                </div>


                <div class="col-md-2">

                    <label class="form-label" for="ref_no">Ref Number</label>
                    <input type="text" id="RefNo" class="form-control" name="RefNo"
                        placeholder="Enter Reference No" value="{{ $invoice_master->RefNo ?? '' }}" readonly>

                </div>

                <div class="col-md-2 d-none">

                    <label class="form-label" for="sub_agent">Sub Agent</label>
                    <select name="sub_agent" id="sub_agent" class="form-select select2">
                        <option value="">Select</option>
                        @foreach ($packages as $row)
                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="col-md-3">

                    <label class="form-label" for="package_name">Package Name</label>
                    <select name="package_id" id="package_id" class="form-select select2">
                        <option value="">Select</option>
                        @foreach ($packages as $row)
                            <option value="{{ $row->id }}"
                                {{ $row->id == ($invoice_master->package_id ?? null) ? 'selected' : '' }}>
                                {{ $row->name }}
                            </option>
                        @endforeach
                    </select>

                </div>


            </div>
        </div>
    </div>

    <div class="card shadow-sm">

        <div class="card-header"><span class="fas fa-plane"></span> Flight Details Card</div>
        <div class="card-body" style="background-color: #f9f9f9 !important; ">
            <div class="row">

                <div class="col-md-4">
                    <div class="mb-0">
                        <label class="form-label" for="sub_agent1">PNR</label>
                        <div class="input-group">
                            <input type="text" name="FlightPNR" id="FlightPNR" class="form-control uppercase"
                                value="{{ $invoice_master->FlightPNR ?? '' }}">
                            <a href="{{ route('pnr.create') }}" class="btn btn-outline-primary" target="_blank">
                                <i class="fas fa-plus"></i> Create PNR
                            </a>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="dep_flight_no">Flight # Departure</label>
                        <input type="text" id="FlightNoDeparture" class="form-control" name="FlightNoDeparture"
                            placeholder="Flight No" value="{{ $invoice_master->FlightNoDeparture ?? 'NILL' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="dep_sector">Sector</label>
                        <input type="text" id="SectorDeparture" class="form-control" name="SectorDeparture"
                            placeholder="ABC-XYZ" value="{{ $invoice_master->SectorDeparture ?? 'NILL' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="dep_date">Dept. Date</label>
                        <input type="date" id="FlightDateDeparture" class="form-control"
                            name="FlightDateDeparture" placeholder="Enter Departure Date"
                            value="{{ $invoice_master->FlightDateDeparture ?? date('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="dep_time">Dept. Time</label>
                        <input type="text" id="FlightTimeDeparture" class="form-control"
                            name="FlightTimeDeparture" placeholder="0000"
                            value="{{ $invoice_master->FlightTimeDeparture ?? '0000' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="dep_arr_date">Arrival Date</label>
                        <input type="date" id="FlightDateArrivalDeparture" class="form-control"
                            name="FlightDateArrivalDeparture" placeholder="Enter Arrival Date"
                            value="{{ $invoice_master->FlightDateArrivalDeparture ?? date('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="dep_arr_time">Arrival Time</label>
                        <input type="text" id="FlightTimeArrivalDeparture" class="form-control"
                            name="FlightTimeArrivalDeparture" placeholder="0000"
                            value="{{ $invoice_master->FlightTimeArrivalDeparture ?? '0000' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="ret_flight_no">Flight # Return</label>
                        <input type="text" id="FlightNoReturn" class="form-control" name="FlightNoReturn"
                            placeholder="Enter Flight # Return"
                            value="{{ $invoice_master->FlightNoReturn ?? 'NILL' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="ret_sector">Sector</label>
                        <input type="text" id="SectorReturn" class="form-control" name="SectorReturn"
                            placeholder="ABC-XYZ" value="{{ $invoice_master->SectorReturn ?? 'NILL' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="ret_date">Return Date</label>
                        <input type="date" id="FlightDateReturn" class="form-control" name="FlightDateReturn"
                            placeholder="Enter Return Date"
                            value="{{ $invoice_master->FlightDateReturn ?? date('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="ret_dep_time">Dept. Time</label>
                        <input type="text" id="FlightDepartureTimeReturn" class="form-control"
                            name="FlightDepartureTimeReturn" placeholder="0000"
                            value="{{ $invoice_master->FlightDepartureTimeReturn ?? '0000' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="ret_arr_date">Arrival Date</label>
                        <input type="date" id="FlightArrivalDateReturn" class="form-control"
                            name="FlightArrivalDateReturn" placeholder="Enter Arrival Date (Return)"
                            value="{{ $invoice_master->FlightArrivalDateReturn ?? date('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="ret_arr_time">Arrival Time</label>
                        <input type="text" id="FlightArrivalTimeReturn" class="form-control"
                            name="FlightArrivalTimeReturn" placeholder="0000"
                            value="{{ $invoice_master->FlightArrivalTimeReturn ?? '0000' }}">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow-sm" style="background-color: #f9f9f9 !important; ">
        <div class="card-body" style="background-color: #f9f9f9 !important; ">
            <div class="row">
                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="sale_rate">Sale Rate</label>
                        <input type="number" step="0.01" id="ExSaleRate" class="form-control" name="ExSaleRate"
                            placeholder="Enter Sale Rate" value="{{ $invoice_master->ExSaleRate ?? '1' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="sale_cur">Sale Currency</label>
                        <select name="SaleCurrency" id="SaleCurrency" class="form-select select2">


                            @foreach ($currency as $item)
                                <option value="{{ $item->name }}"
                                    {{ $item->name == ($invoice_master->SaleCurrency ?? null) ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="ticket_cur">Currency Ticket</label>
                        <select name="TicketCurrency" id="TicketCurrency" class="form-select select2">
                            <option value="Local" {{ $item->name == 'Local' ? 'selected=selected' : '' }}>Local
                            </option>
                            <option value="Foreign" {{ $item->name == 'Foreign' ? 'selected=selected' : '' }}>Foreign
                            </option>

                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="purchase_rate">Purchase Rate</label>
                        <input type="number" step="0.01" id="ExPurchaseRate" class="form-control"
                            name="ExPurchaseRate" placeholder="Enter Purchase Rate"
                            value="{{ $invoice_master->ExSaleRate ?? '1' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="purchase_cur">Purchase Currency</label>
                        <select name="PurchaseCurrency" id="PurchaseCurrency" class="form-select select2">
                            @foreach ($currency as $item)
                                <option value="{{ $item->name }}"
                                    {{ $item->name == ($invoice_master->PurchaseCurrency ?? null) ? 'selected=selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-0">
                        <label class="form-label" for="flight_nights"
                            style="color: #E85700; font-weight: bold;text-align: center;">Flight Nights</label>
                        <input type="number" id="FlightNights" class="form-control" name="FlightNights"
                            placeholder="Enter Flight Nights" value="{{ $invoice_master->FlightNights ?? '0' }}">
                    </div>
                </div>

            </div>
        </div>
    </div>



    <div class="d-flex flex-wrap gap-2  justify-content-center mb-4">

        <button type="submit" id="submit-umrah-invoice-master-btn"
            class="text-white bg-warning fw-bold  btn-rounded border-0 p-2" style="width: 300px;">
            Submit
        </button>
        <button type="reset" class="text-warning  bg-white fw-bold  btn-rounded border-1 broder-light p-2"
            style="width: 300px;">
            Reset
        </button>
    </div>
</form>






<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Your client creation form goes here -->
                <form method="post" name="PartyForm" id="PartyForm">

                    @csrf




                    <div class="mb-1 row">
                        <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Branch</label>
                        <div class="col-md-12">
                            <select name="BranchID" class="form-select">
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('BranchID') == $branch->id ? 'selected=selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="example-url-input" class="col-md-12 col-form-label fw-bold">Party
                            Type</label>
                        <div class="col-md-12">
                            <select name="PartyType" class="form-select">
                                @foreach ($party_type as $type)
                                    <option value="{{ $type->PartyCode }}"
                                        {{ old('PartyType') == $type->PartyCode ? 'selected=selected' : '' }}>
                                        {{ $type->PartyCode }}-{{ $type->PartyCategory }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="example-url-input" class="col-md-12 col-form-label fw-bold">Party
                            Name</label>
                        <div class="col-md-12">
                            <input class="form-control" type="text" value="{{ old('PartyName') }}"
                                name="PartyName">
                        </div>

                    </div>
                    <div class="mb-1 row">
                        <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Address</label>
                        <div class="col-md-12">
                            <input class="form-control" type="text" name="Address" value="{{ old('Address') }}">
                        </div>

                    </div>

                    <div class="mb-1 row">
                        <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Phone</label>
                        <div class="col-md-12">
                            <input class="form-control" type="text" name="Phone" value="{{ old('Phone') }}">
                        </div>

                    </div>

                    <div class="mb-1 row">
                        <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Email</label>
                        <div class="col-md-12">
                            <input class="form-control" type="text" name="Email" value="{{ old('Email') }}">
                        </div>

                    </div>



                    <div class="mb-1 row">
                        <label for="example-tel-input" class="col-md-2 col-form-label fw-bold">Active</label>
                        <div class="col-md-12">
                            <select name="Active" class="form-select">


                                <option value="Yes" {{ old('Active') == 'Yes' ? 'selected=selected' : '' }}>
                                    Yes</option>
                                <option value="No" {{ old('Active') == 'No' ? 'selected=selected' : '' }}>
                                    No</option>

                            </select>
                        </div>


                    </div>

            </div>
            <div class="card-footer  ">
                <button type="buton" class="btn btn-primary me-1 waves-effect waves-float waves-light"
                    id="submitButton">Submit</button>
                <button type="button" class="btn btn-outline-secondary waves-effect">Reset</button>
            </div>
        </div>
        <!-- card end here -->
        </form>
    </div>
</div>
