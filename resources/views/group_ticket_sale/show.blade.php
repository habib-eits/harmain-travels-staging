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
                                <i class="fas fa-ticket-alt"></i> Group Ticket Details: {{ $groupTicket->VoucherNo }}
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('group-ticket-sale.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Group Tickets
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Voucher Information -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Voucher Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th width="40%">Voucher Type:</th>
                                                            <td>{{ $groupTicket->VoucherType ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Voucher No:</th>
                                                            <td>{{ $groupTicket->VoucherNo ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Date:</th>
                                                            <td>{{ $groupTicket->Date ? date('M d, Y', strtotime($groupTicket->Date)) : 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Supplier ID:</th>
                                                            <td>{{ $groupTicket->SupplierID ?? 'N/A' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th width="40%">PNR:</th>
                                                            <td>{{ $groupTicket->PNR ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Sector:</th>
                                                            <td>{{ $groupTicket->Sector ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Care Of:</th>
                                                            <td>{{ $groupTicket->CareOf ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Party ID:</th>
                                                            <td>{{ $groupTicket->PartyID ?? 'N/A' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Flight Information -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Flight Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th width="40%">Airline Name:</th>
                                                            <td>{{ $groupTicket->AirlineName ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Flight No:</th>
                                                            <td>{{ $groupTicket->FlightNo ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Departure Date:</th>
                                                            <td>{{ $groupTicket->DateOfDep ? date('M d, Y', strtotime($groupTicket->DateOfDep)) : 'N/A' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th width="40%">Arrival Date:</th>
                                                            <td>{{ $groupTicket->DateOfArr ? date('M d, Y', strtotime($groupTicket->DateOfArr)) : 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Payment Due Date:</th>
                                                            <td>{{ $groupTicket->PaymentDueDate ? date('M d, Y', strtotime($groupTicket->PaymentDueDate)) : 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Exchange Rate:</th>
                                                            <td>{{ $groupTicket->ExRate ?? 'N/A' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Financial Information -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Financial Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th width="40%">Fare:</th>
                                                            <td>{{ number_format($groupTicket->Fare, 2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Quantity:</th>
                                                            <td>{{ $groupTicket->Quantity ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total Amount:</th>
                                                            <td><strong>{{ number_format($groupTicket->Fare * $groupTicket->Quantity, 2) }}</strong></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th width="40%">Payable:</th>
                                                            <td><strong>{{ number_format($groupTicket->Payable, 2) }}</strong></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($groupTicket->Remarks)
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Remarks</h5>
                                            </div>
                                            <div class="card-body">
                                                <p>{{ $groupTicket->Remarks }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Actions</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('group-ticket-sale.edit', $groupTicket) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i> Edit Group Ticket
                                                </a>
                                                <button type="button" class="btn btn-danger" onclick="deleteGroupTicket('{{ $groupTicket->GroupTicketID }}')">
                                                    <i class="fas fa-trash"></i> Delete Group Ticket
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this group ticket? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelDelete">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteGroupTicket(id) {
    // Clear any previous event handlers
    $('#confirmDelete').off('click');
    $('#cancelDelete').off('click');
    
    // Show modal
    $('#deleteModal').modal('show');
    
    // Handle cancel button
    $('#cancelDelete').on('click', function() {
        $('#deleteModal').modal('hide');
    });
    
    // Handle confirm delete button
    $('#confirmDelete').on('click', function() {
        $.ajax({
            url: "{{ url('group-ticket-sale') }}/" + id,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    window.location.href = "{{ route('group-ticket-sale.index') }}";
                } else {
                    toastr.error('Error deleting group ticket');
                }
            },
            error: function() {
                toastr.error('Error deleting group ticket');
            }
        });
    });
}
</script>
@endpush
