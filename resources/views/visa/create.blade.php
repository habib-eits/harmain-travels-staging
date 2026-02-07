@extends('template.tmp')
@section('title', 'Visa')

@section('content')


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0 font-size-18">Visa Create</h3>
                        </div>
                    </div>
                </div>


                <div class="card shadow-sm ">
                    <div class="card-body">
                        <form id="create-or-update-visa-master-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                {{-- InvoiceMasterID --}}
                                <input type="hidden" name="InvoiceMasterID" id="InvoiceMasterID"
                                    value="{{ $visaMaster->InvoiceMasterID }}"> <!-- Used for Edit -->

                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="Date">Date</label>
                                    <input type="date" id="Date" name="Date" class="form-control"
                                        value="{{ $visaMaster->Date ?? date('Y-m-d') }}">
                                </div>

                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Customer Name</label>
                                    <select class="form-select select2" id="PartyID" name="PartyID">

                                        <option value="">Select Customer</option>
                                        @foreach ($party as $row)
                                            <option @selected($visaMaster->PartyID == $row->PartyID) value="{{ $row->PartyID }}">
                                                {{ $row->PartyName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Saleman Name</label>
                                    <select class="form-select select2" id="UserID" name="UserID">

                                        <option value="">Select Saleman</option>
                                        @foreach ($user as $row)
                                            <option value="{{ $row->UserID }}">{{ $row->FullName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 d-none mt-1">
                                    <label class="form-label" for="GroupNo">Group No</label>
                                    <input type="text" id="GroupNo" name="GroupNo" class="form-control  fw-bold"
                                        value="{{ $visaMaster->GroupNo }}">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="GroupNo">Total</label>
                                    <input type="text" id="visaMasterTotal" class="form-control  fw-bold"
                                        value="{{ $visaMaster->Total }}" readonly>
                                </div>

                                <div class="clearfix"></div>
                                <div class="col-md-3 mt-1">
                                    <button type="button" class="btn btn-warning text-white fw-bold w-100 btn-rounded"
                                        id="create-or-update-visa-master-form-btn">
                                        <i class="bi bi-save"></i>Save
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>


                <div class="card shadow-sm ">
                    <div class="card-body">
                        <form id="create-or-update-visa-detail-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                {{-- InvoiceDetailID --}}
                                <input type="hidden" name="InvoiceDetailID" id="InvoiceDetailID"> <!-- Used for Edit -->

                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Item Name</label>
                                    <select class="form-select select2" id="ItemID" name="ItemID">
                                        <option value="">Select Item</option>
                                        <option value="1">Visa</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Supplier Name</label>
                                    <select class="form-select select2" id="SupplierID" name="SupplierID">
                                        <option value="">Select Supplier</option>
                                        @foreach ($supplier as $row)
                                            <option value="{{ $row->PartyID }}">{{ $row->PartyName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Visa Type Name</label>
                                    <select class="form-select select2" id="VisaType" name="VisaType">
                                        <option value="">Select Visa Type</option>
                                        <option value="Umrah">Umrah</option>
                                        <option value="Tourist">Tourist</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="PaxName">Pax Name</label>
                                    <input type="text" id="PaxName" name="PaxName" class="form-control" value="">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="Passport">Passport</label>
                                    <input type="text" id="Passport" name="Passport" class="form-control"
                                        value="">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="Nationality">Nationality</label>
                                    <input type="text" id="Nationality" name="Nationality" class="form-control"
                                        value="">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Visa Status</label>
                                    <select class="form-select select2" id="VisaStatus" name="VisaStatus">
                                        <option value="">Select Visa Status</option>
                                        <option value="Process" selected>Process</option>
                                        <option value="Accepted">Accepted</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="VisaNo">Visa No</label>
                                    <input type="text" id="VisaNo" name="VisaNo" class="form-control"
                                        value="">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="DOB">DOB</label>
                                    <input type="date" id="DOB" name="DOB" class="form-control"
                                        value="">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="Age">Age</label>
                                    <input type="text" id="Age" name="Age" class="form-control"
                                        value="">
                                </div>

                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Pax Type</label>
                                    <select class="form-select select2" id="PaxType" name="PaxType">
                                         <option value="ADULT">ADULT</option>
    <option value="CHILD">CHILD</option>
    <option value="INFANT">INFANT</option>
  </select>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select select2" id="Gender" name="Gender">
                                         <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="IssueDate">IssueDate</label>
                                    <input type="date" id="IssueDate" name="IssueDate" class="form-control"
                                        value="">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="ExpiryDate">ExpiryDate</label>
                                    <input type="date" id="ExpiryDate" name="ExpiryDate" class="form-control"
                                        value="">
                                </div>

                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Relation Type</label>
                                    <select class="form-select select2" id="RelationType" name="RelationType">
                                        <option value="">Select Relation Type</option>
                                        <option value="Head" selected>Head</option>
                                        <option value="Relation">Relation</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1 d-none">
                                    <label class="form-label">Relation</label>
                                    <select class="form-select select2" id="Relation" name="Relation">
                                        <option value="">Select Relation</option>
                                        <option value="Self">Self</option>
                                        
                                        {{-- <option value="Husband">Husband</option>
                                        <option value="Btother">Brother</option>
                                        <option value="Sister">Sister</option> --}}
                                    </select>
                                </div>

                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Shikra</label>
                                    <select class="form-select select2" id="ShirkaID" name="ShirkaID">
                                        <option value="">Select Shikra</option>
                                        @foreach ($shirka as $row)
                                            <option value="{{ $row->id }}">{{ $row->shirka_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label">Package Name</label>
                                    <select class="form-select select2" id="PackageName" name="PackageName">
                                        <option value="">Select Package Name</option>
                                        @foreach ($package as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="DepartureDate">Departure Date</label>
                                    <input type="date" id="DepartureDate" name="DepartureDate" class="form-control"
                                        value="">
                                </div>

                                <div class="clearfix"></div>

                                {{-- <div class="col-md-3 mt-1">
                                    <label class="form-label" for="Fare">Fare</label>
                                    <input type="number" step="0.01" id="Fare" name="Fare"
                                        class="form-control" value="">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="VAT">VAT</label>
                                    <input type="number" step="0.01" id="VAT" name="VAT"
                                        class="form-control" readonly>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="Service">Service</label>
                                    <input type="number" step="0.01" id="Service" name="Service"
                                        class="form-control" value="">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="Total">Total</label>
                                    <input type="number" step="0.01" id="Total" name="Total"
                                        class="form-control" value="" readonly>
                                </div> --}}


                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="VisaSaleRate">Visa Sale</label>
                                    <input type="number" step="0.01" id="VisaSaleRate" class="form-control calculation"
                                        name="VisaSaleRate" placeholder="Enter Sale Rate">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="ExRateSale">Ex Rate Sale</label>
                                    <input type="number" step="0.01" id="ExRateSale" class="form-control calculation"
                                        name="ExRateSale" placeholder="Enter Ex Rate Sale" value="1">
                                </div>

                               
                                <div class="col-md-3 mt-1">
                                    <label class="form-label text-success" for="Receivable">Receivable</label>
                                    <input type="number" step="0.01" id="Receivable" class="form-control"
                                        name="Receivable" readonly>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="VisaPurchaseRate">Visa Purchase</label>
                                    <input type="number" step="0.01" id="VisaPurchaseRate" class="form-control calculation"
                                        name="VisaPurchaseRate" placeholder="Enter Visa Purchase">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label" for="ExRatePurchase">Ex Rate
                                        Purchase</label>
                                    <input type="number" step="0.01" id="ExRatePurchase" class="form-control calculation"
                                        name="ExRatePurchase" placeholder="Enter Ex Rate Purchase" value="1">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <label class="form-label text-danger" for="Payable">Payable</label>
                                    <input type="number" id="Payable" class="form-control" name="Payable"
                                        placeholder="Enter Payable" readonly>
                                </div>






                                <div class="clearfix"></div>
                                <div class="col-md-3 mt-1">
                                    <button type="button" class="btn btn-warning text-white fw-bold w-100 btn-rounded"
                                        id="create-or-update-visa-detail-form-btn">
                                        <i class="bi bi-save"></i>Save
                                    </button>
                                </div>
                                <div class="col-md-3 mt-1">
                                    <button type="button" class="btn btn-warning text-white fw-bold w-100 btn-rounded"
                                        id="visa-detail-update-all-btn">
                                        <i class="bi bi-save"></i>Update All
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="visa-detail-table" class="table table-striped table-sm " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Pax Name</th>
                                            <th>Passport</th>
                                            <th>Visa Status</th>
                                            <th>Action</th>


                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @include('visa.js')




@endsection
