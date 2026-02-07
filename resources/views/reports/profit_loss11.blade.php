@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')



<div class="main-content">
<div class="page-content">
 <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $pagetitle }}</h4>
                    
                    <!-- Date Filter Form -->
                    {{-- <form method="GET" action="{{ route('profit.loss') }}" class="mt-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="StartDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="StartDate" name="StartDate" 
                                       value="{{ request('StartDate', date('Y-m-01')) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="EndDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="EndDate" name="EndDate" 
                                       value="{{ request('EndDate', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </div>
                    </form> --}}
                </div>

                <div class="card-body">
                    @if(!empty($data))
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Account Name</th>
                                        @foreach($months as $month)
                                            <th class="text-center">{{ $month['label'] }}</th>
                                        @endforeach
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $monthlyTotals = [];
                                        foreach($months as $month) {
                                            $monthlyTotals[$month['label']] = 0;
                                        }
                                        $grandTotal = 0;
                                    @endphp
                                    
                                    @foreach($data as $account)
                                        @php
                                            $rowTotal = 0;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $account['name'] }}</strong></td>
                                            @foreach($months as $month)
                                                @php
                                                    $balance = $account['monthly_balances'][$month['label']] ?? 0;
                                                    $rowTotal += $balance;
                                                    $monthlyTotals[$month['label']] += $balance;
                                                @endphp
                                                <td class="text-end">
                                                    {{ number_format($balance, 2) }}
                                                </td>
                                            @endforeach
                                            <td class="text-end"><strong>{{ number_format($rowTotal, 2) }}</strong></td>
                                        </tr>
                                        @php
                                            $grandTotal += $rowTotal;
                                        @endphp
                                    @endforeach
                                    
                                    <!-- Monthly Totals Row -->
                                    <tr class="table-info">
                                        <td><strong>Monthly Totals</strong></td>
                                        @foreach($months as $month)
                                            <td class="text-end">
                                                <strong>{{ number_format($monthlyTotals[$month['label']], 2) }}</strong>
                                            </td>
                                        @endforeach
                                        <td class="text-end"><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h5>No Data Found</h5>
                            <p>No account transactions found for the selected date range.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        white-space: nowrap;
    }
    
    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>
</div>
</div>


    <!-- END: Content-->

 

 
  @endsection