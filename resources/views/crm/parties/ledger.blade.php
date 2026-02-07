@extends('crm.parties.template.app')
@section('content')
    <!-- Party Info -->
    <div class="card ledger-header mb-4 shadow">
        <div class="card-body">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <h4 class="mb-1 fw-bold" id="partyName"></h4>
                    <div class="text-muted small">Party Ledger • Account No: <span id="partyID">-</span></div>
                </div>

                <div class="col-lg-7">
                    <form id="ledgerFilterForm" class="row g-3 align-items-end">
                        <div class="col-sm-5 col-md-4">
                            <label for="startDate" class="form-label small fw-bold mb-1">From</label>
                            <input type="date" class="form-control" id="startDate" value="{{ date('Y-m-01')}}">
                        </div>
                        <div class="col-sm-5 col-md-4">
                            <label for="endDate" class="form-label small fw-bold mb-1">To</label>
                            <input type="date" class="form-control" id="endDate" value="{{ date('Y-m-d')}}">
                        </div>
                        <div class="col-sm-2 col-md-4 d-flex">
                            <button type="submit" class="btn btn-primary w-100">Load Ledger</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ledger Table -->
    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="ledgerTable" class="table table-ledger mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Narration</th>
                            <th class="text-end">Debit ({{ env('APP_CURRENCY') }})</th>
                            <th class="text-end">Credit ({{ env('APP_CURRENCY') }})</th>
                            <th class="text-end">Balance ({{ env('APP_CURRENCY') }})</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="2" class="text-end">Closing Balance</td>
                            <td colspan="3" id="closingBalance" class="text-end amount-balance balance-positive">—</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        const APP_URL = "{{ config('app.url') }}";

        document.addEventListener('DOMContentLoaded', function() {

            const token = localStorage.getItem('party_token');
            if (!token) {
                window.location.href = APP_URL + '/api/party/login';
                return;
            }

            // Logout button
            const logoutBtn = document.getElementById('logoutBtn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function() {
                    localStorage.removeItem('party_token');
                    axios.post(
                            APP_URL + '/api/party/logout', {}, {
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': 'Bearer ' + token,
                                    'Accept': 'application/json',
                                }
                            }
                        )
                        .then(function() {
                            window.location.href = APP_URL + '/api/party/login';
                        });
                });
            }

            const ledgerTableBody = document.querySelector('#ledgerTable tbody');

            function loadLedger(startDate = '', endDate = '') {
                axios.post(
                        APP_URL + '/api/party/ledger', {
                            startDate,
                            endDate
                        }, {
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + token,
                                'Accept': 'application/json',
                            }
                        }
                    )
                    .then(function(response) {
                        const data = response.data;

                        // Update Party Info
                        if (data.party) {
                            document.getElementById('partyName').innerText = data.party.PartyName ||
                                '';
                            document.getElementById('partyID').innerText = data.party.PartyID || '-';
                        }

                        // Update Date Inputs
                        if (data.startDate) document.getElementById('startDate').value = data.startDate;
                        if (data.endDate) document.getElementById('endDate').value = data.endDate;


                        let rows = '';

                        rows += `
                                <tr>
                                    <td></td>
                                    <td>Previous Balance</td>
                                    <td class="text-end"></td>
                                    <td class="text-end"></td>
                                    <td class="text-end">${data.openingBalance}</td>
                                </tr>
                            `;
                        if (data.ledgers && data.ledgers.length) {
                            data.ledgers.forEach(row => {


                                rows += `
                                    <tr>
                                        <td>${row.Date}</td>
                                        <td>${row.Narration || ''}</td>
                                        <td class="text-end">${row.Dr}</td>
                                        <td class="text-end">${row.Cr}</td>
                                        <td class="text-end">${row.Balance}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            rows = `<tr><td colspan="5" class="text-center">No records found</td></tr>`;
                        }

                        ledgerTableBody.innerHTML = rows;

                        // Update closing balance
                        document.getElementById('closingBalance').innerText = data.closingBalance;
                        document.getElementById('closingBalance').className = 'text-end amount-balance ' + (data
                            .closingBalance >= 0 ? 'balance-positive' : 'balance-negative');
                    })
                    .catch(function(error) {
                        console.error('Error loading ledger:', error);
                        ledgerTableBody.innerHTML =
                            `<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>`;
                    });
            }

            // Initial load
            loadLedger();

            // Handle filter form submit
            document.getElementById('ledgerFilterForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                loadLedger(startDate, endDate);
            });

        });
    </script>
@endpush
