@extends('template.tmp')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                <i class="fas fa-ticket-alt"></i> Group Tickets Sale Management
                            </h3>
                            <a href="{{ route('group-ticket-sale.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Group Ticket
                            </a>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table id="groupTicketsTable" class="table table-bordered table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Voucher Type</th>
                                            <th>Voucher No</th>
                                            <th>Date</th>
                                            <th>PNR</th>
                                            <th>Sector</th>
                                            <th>Airline</th>
                                            <th>Flight No</th>
                                            <th>Departure</th>
                                            <th>Arrival</th>
                                            <th>Fare</th>
                                            <th>Quantity</th>
                                            <th>Total Amount</th>
                                            <th>Receivable</th>
                                            <th>Actions</th>
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
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#groupTicketsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('group-ticket-sale.data') }}",
            type: 'GET'
        },
        columns: [
            { data: 'GroupTicketID', name: 'GroupTicketID' },
            { data: 'VoucherType', name: 'VoucherType' },
            { data: 'VoucherNo', name: 'VoucherNo' },
            { data: 'Date', name: 'Date' },
            { data: 'PNR', name: 'PNR' },
            { data: 'Sector', name: 'Sector' },
            { data: 'AirlineName', name: 'AirlineName' },
            { data: 'FlightNo', name: 'FlightNo' },
            { data: 'DateOfDep', name: 'DateOfDep' },
            { data: 'DateOfArr', name: 'DateOfArr' },
            { data: 'Fare', name: 'Fare' },
            { data: 'Quantity', name: 'Quantity' },
            { data: 'total_amount', name: 'total_amount', orderable: false },
            { data: 'Receivable', name: 'Receivable' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: "Loading data...",
            emptyTable: "No group tickets found",
            zeroRecords: "No matching records found"
        }
    });

    // Delete function
    window.deleteGroupTicket = function(id) {
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
                        table.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error('Error deleting group ticket');
                    }
                },
                error: function() {
                    toastr.error('Error deleting group ticket');
                }
            });
        });
    };
});
</script>
@endpush
