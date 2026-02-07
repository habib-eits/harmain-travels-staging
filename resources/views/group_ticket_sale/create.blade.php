@extends('template.tmp')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
              <div class="col-12">
                <div
                  class="page-title-box d-sm-flex align-items-center justify-content-between"
                >
                  <h4 class="mb-sm-0 font-size-18"><i class="fas fa-plus"></i> Add New Group Ticket</h4>
                  <a href="{{ route('group-ticket-sale.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Group Tickets
                                </a>
               
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                      
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

                            <form action="{{ route('group-ticket-sale.store') }}" method="POST">
                                @csrf
                                
                                <!-- Voucher Information -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="VoucherType">Voucher Type <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('VoucherType') is-invalid @enderror" 
                                                   id="VoucherType" name="VoucherType" value="GTS" readonly  >
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
                                                    id="SupplierID" name="SupplierID" >
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
                                    <div class="col-md-2 mt-2">
                                        <div class="form-group">
                                            <label for="PNR">PNR <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('PNR') is-invalid @enderror" 
                                                    id="PNR" name="PNR" required>
                                                <option value="12">Select PNR</option>
                                                @foreach($pnr as $item)
                                                    <option value="{{ $item->PNR }}" {{ old('PNR') == $item->PNR ? 'selected' : '' }}>
                                                        {{ $item->PNR }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('PNR')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                      <div class="col-md-1 mt-2">
                                        <div class="form-group">
                                            <label for="Sector">Balance <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('Balance') is-invalid @enderror" 
                                                   id="Balance" name="Balance" value="{{ old('Balance') }}" required readonly>
                                            @error('Balance')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    
 

                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="Sector">Sector <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('Sector') is-invalid @enderror" 
                                                   id="Sector" name="Sector" value="{{ old('Sector') }}" required readonly>
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
                                    <div class="col-md-3 mt-2 d-none">
                                        <div class="form-group">
                                            <label for="AirlineName">Airline Name <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('AirlineName') is-invalid @enderror" 
                                                    id="AirlineName" name="AirlineName" required>
                                                <option value="1">Select Airline</option>
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
                                    
                                    
                               
                                    
                                    <div class="col-md-3 mt-2">
  <input type="hidden" id="OldTicketNo" name="OldTicketNo" value="">
  <label class="control-label" for="TicketNo"><div id="txtTicketNo"> <span style="color: #333">Ticket #</span> </div></label>
  <div class="input-group">
    <input type="text" id="TicketNo" name="TicketNo" value="" class="form-control input-md" style="width: 75%;" minlength="16" pattern=".{16,}" onkeypress="return isNumberKey(event)" required="" maxlength="16">
    <span class="input-group-btn" style="width:50px;">
    <input id="AirlineCode" name="AirlineCode" class="form-control" type="text" value="" maxlength="2" onblur="return UpperCase(id)"></span>
  </div>
</div>
                                    
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="FlightNo">Pax Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('FlightNo') is-invalid @enderror" 
                                                   id="PaxName" name="PaxName" value="{{ old('PaxName') }}" required>
                                            @error('PaxName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="FlightNo">Fare <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('FlightNo') is-invalid @enderror" 
                                                   id="TicketPrice" name="TicketPrice" value="{{ old('TicketPrice') }}" required>
                                            @error('PaxName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Information -->
                                 
                                <div class="row">
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="Fare"> Selling Price<span class="text-danger">*</span></label>
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
                                                   id="Quantity" name="Quantity" value="1" required readonly>
                                            @error('Quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="Receivable">Discount <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('Receivable') is-invalid @enderror" 
                                                   id="Discount" name="Discount" value="{{ old('Discount') ?? 0 }}" required>
                                            @error('Discount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="Receivable">Receivable <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('Receivable') is-invalid @enderror" 
                                                   id="Receivable" name="Receivable" value="{{ old('Receivable') }}" required>
                                            @error('Receivable')
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
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label for="PartyID">Customer</label>
                                            <select class="form-select select2 @error('PartyID') is-invalid @enderror" 
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
                                    </div>
                              
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
                                            <label for="CareOf">Passport No</label>
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
                                    <a href="{{ route('group-ticket-sale.index') }}" class="btn btn-secondary">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#TicketNo').on('input', function() {
        let val = $(this).val().replace(/\D/g, ''); // Remove all non-digits
        // Format: 3-4-3-3 pattern => 214-1234-567-891
        let formatted = val;
        if (val.length > 3 && val.length <= 7)
            formatted = val.slice(0, 3) + '-' + val.slice(3);
        else if (val.length > 7 && val.length <= 10)
            formatted = val.slice(0, 3) + '-' + val.slice(3, 7) + '-' + val.slice(7);
        else if (val.length > 10)
            formatted = val.slice(0, 3) + '-' + val.slice(3, 7) + '-' + val.slice(7, 10) + '-' + val.slice(10, 13);

        $(this).val(formatted);
    });
});
</script>


<script>
$(document).ready(function() {

    function calculateReceivable() {
        let fare = parseFloat($('#Fare').val()) || 0;
        let qty = parseFloat($('#Quantity').val()) || 0;
        let discount = parseFloat($('#Discount').val()) || 0;

        let receivable = (fare * qty) - discount;
        $('#Receivable').val(receivable.toFixed(2));
    }

    // Trigger calculation when any value changes
    $('#Fare, #Quantity, #Discount').on('input', function() {
        calculateReceivable();
    });

    // Run once on page load (in case of old values)
    calculateReceivable();
});
</script>
 


<script>
$(document).ready(function() {

    // When TicketNo is complete (16 digits)
$('#TicketNo').on('blur', function() {
        let ticketNo = $(this).val().replace(/-/g, '').trim();
 
        // Trigger only when exactly 16 digits are entered
        if (ticketNo.length === 13) {
 
            $.ajax({
                url: '{{ url('/getAirlineCode') }}/' + ticketNo,
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    $('#AirlineCode').val(response.airline_code || '');
                },
                error: function(xhr) {
                    console.error('Error fetching airline code:', xhr.responseText);
                }
            });
        } else {
            // Clear field if incomplete
            $('#AirlineCode').val('');
        }
    });
});
</script>

<script>
$(document).ready(function() {
    // Trigger when PNR field loses focus or changes
    $('#PNR').on('change blur', function() {
        let pnr = $(this).val();

        if (pnr !== '') {
            $.ajax({
                url: "{{ route('getBalanceOfGroupticket', '') }}/" + pnr,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                        return;
                    }

                    // Assign values to fields
                    $('#SupplierID').val(response.SupplierID);
                    $('#PNR').val(response.PNR);
                    $('#Sector').val(response.Sector);
                    $('#DateOfDep').val(response.DateOfDep);
                    $('#DateOfArr').val(response.DateOfArr);
                    $('#AirlineName').val(response.AirlineName);
                    $('#FlightNo').val(response.FlightNo);
                    $('#Balance').val(response.Balance);
                    $('#TicketPrice').val(response.TicketPrice);
                },
                error: function() {
                    alert('No data found for this PNR.');
                }
            });
        }
    });
});
</script>



