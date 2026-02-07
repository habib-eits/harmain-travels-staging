@extends('template.tmp')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plus"></i> Add New Group Ticket
                                <div class="card-tools text-end">
                                <a href="{{ route('group-ticket-purchase.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Group Tickets
                                </a>
                            </div>
                            </h3>
                          
                        </div>
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('group-ticket-purchase.store') }}" method="POST">
                                @csrf
                                
                                <!-- Voucher Information -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="VoucherType">Voucher Type <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('VoucherType') is-invalid @enderror" 
                                                   id="VoucherType" name="VoucherType" value="GTP" readonly  >
                                            @error('VoucherType')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="VoucherNo">Voucher No <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('VoucherNo') is-invalid @enderror" 
                                                   id="VoucherNo" name="VoucherNo"  required value="{{ $group_tick_no }}">
                                            @error('VoucherNo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                           
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('Date') is-invalid @enderror" 
                                                   id="Date" name="Date" value="{{ date('Y-m-d') }}" required>
                                            @error('Date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="SupplierID">Supplier <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('SupplierID') is-invalid @enderror" 
                                                    id="SupplierID" name="SupplierID" required>
                                                <option value="">Select Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->PartyID }}" {{ old('SupplierID') == $supplier->PartyID ? 'selected' : '' }}>
                                                        {{ $supplier->PartyName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('SupplierID')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Flight Information -->
                                 <div class="row">
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="PNR">PNR <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('PNR') is-invalid @enderror" 
                                                   id="PNR" name="PNR" value="{{ old('PNR') }}" required>
                                            @error('PNR')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="Sector">Sector <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('Sector') is-invalid @enderror" 
                                                   id="Sector" name="Sector" value="{{ old('Sector') }}" required>
                                            @error('Sector')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                            
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="DateOfDep">Departure Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('DateOfDep') is-invalid @enderror" 
                                                   id="DateOfDep" name="DateOfDep" value="{{ old('DateOfDep') }}" required>
                                            @error('DateOfDep')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="DateOfArr">Arrival Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('DateOfArr') is-invalid @enderror" 
                                                   id="DateOfArr" name="DateOfArr" value="{{ old('DateOfArr') }}" required>
                                            @error('DateOfArr')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="AirlineName">Airline Name <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('AirlineName') is-invalid @enderror" 
                                                    id="AirlineName" name="AirlineName" required>
                                                <option value="">Select Airline</option>
                                                @foreach($airline as $airline)
                                                    <option value="{{ $airline->name }}" {{ old('AirlineName') == $airline->name ? 'selected' : '' }}>
                                                        {{ $airline->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('AirlineName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="FlightNo">Flight No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('FlightNo') is-invalid @enderror" 
                                                   id="FlightNo" name="FlightNo" value="{{ old('FlightNo') }}" required>
                                            @error('FlightNo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Information -->
                                 
                                <div class="row">
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="Fare">Fare <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('Fare') is-invalid @enderror" 
                                                   id="Fare" name="Fare" value="{{ old('Fare') }}" required>
                                            @error('Fare')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="Quantity">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('Quantity') is-invalid @enderror" 
                                                   id="Quantity" name="Quantity" value="{{ old('Quantity', 1) }}" required>
                                            @error('Quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="Payable">Payable <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('Payable') is-invalid @enderror" 
                                                   id="Payable" name="Payable" value="{{ old('Payable') }}" required>
                                            @error('Payable')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="PaymentDueDate">Payment Due Date</label>
                                            <input type="date" class="form-control @error('PaymentDueDate') is-invalid @enderror" 
                                                   id="PaymentDueDate" name="PaymentDueDate" value="{{ old('PaymentDueDate') }}">
                                            @error('PaymentDueDate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="PartyID">Customer</label>
                                            <select class="form-control @error('PartyID') is-invalid @enderror" 
                                                    id="PartyID" name="PartyID">
                                                <option value="">Select Customer</option>
                                                @foreach($parties as $party)
                                                    <option value="{{ $party->PartyID }}" {{ old('PartyID') == $party->PartyID ? 'selected' : '' }}>
                                                        {{ $party->PartyName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('PartyID')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> -->
                              
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="ExRate">Exchange Rate</label>
                                            <input type="number" step="0.01" class="form-control @error('ExRate') is-invalid @enderror" 
                                                   id="ExRate" name="ExRate" value="{{ old('ExRate', 1) }}">
                                            @error('ExRate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="CareOf">Care Of</label>
                                            <input type="text" class="form-control @error('CareOf') is-invalid @enderror" 
                                                   id="CareOf" name="CareOf" value="{{ old('CareOf') }}">
                                            @error('CareOf')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="Remarks">Remarks</label>
                                    <textarea class="form-control @error('Remarks') is-invalid @enderror" 
                                              id="Remarks" name="Remarks" rows="3">{{ old('Remarks') }}</textarea>
                                    @error('Remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Group Ticket
                                    </button>
                                    <a href="{{ route('group-ticket-purchase.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fareInput = document.getElementById('Fare');
    const quantityInput = document.getElementById('Quantity');
    const payableInput = document.getElementById('Payable');

    function calculatePayable() {
        const fare = parseFloat(fareInput.value) || 0;
        const qty = parseFloat(quantityInput.value) || 0;
        const total = fare * qty;
        payableInput.value = total.toFixed(2);
    }

    fareInput.addEventListener('input', calculatePayable);
    quantityInput.addEventListener('input', calculatePayable);
});
</script>
