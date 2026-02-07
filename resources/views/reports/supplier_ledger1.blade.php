@extends('tmp')

@section('title', $pagetitle)

@section('content')

<!-- BEGIN: Content-->

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            
            <div class="row d-sm-flex align-items-center justify-content-between">
                <div class="col-md-4">
                    <div class="page-title-box">
                        <h4 class="mb-sm-0 font-size-18 pt-3">Supplier Ledger: {{$supplier[0]->PartyID."- ".$supplier[0]->PartyName}}</h4>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <form method="post" name="form1" id="form1" class="form-inline w-100 d-flex align-items-center">
                        @csrf
                        <input type="hidden" name="ChartOfAccountID" value="{{ request()->ChartOfAccountID }}">
                        <input type="hidden" name="SupplierID" value="{{ request()->SupplierID }}">
            
                        <div class="col-md-4">
                            <div class="form-group mx-2 ">
                                <input type="date" class="form-control" id="StartDate" name="StartDate" value="{{ request()->StartDate }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mx-2">
                                <input type="date" class="form-control" id="EndDate" name="EndDate" value="{{ request()->EndDate }}">
                            </div>
                        </div>
            
                        <div class="form-group ms-auto d-flex gap-2">
                            <button type="submit" class="btn btn-success w-md" id="online">Submit</button>
                            <button type="submit" class="btn btn-primary w-md" id="excel">Export to Excel</button>
                        </div>
                    </form>
                </div>
            </div>
            
   @php
    use Illuminate\Support\Str;
@endphp         
            
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    @if (session('error'))
                    <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
                        {{ Session::get('error') }}
                    </div>
                    @endif

                    @if (count($errors) > 0)
                    <div>
                        <div class="alert alert-danger p-1 border-3">
                            <p class="font-weight-bold">There were some problems with your input.</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <?php 
                        $DrTotal=0;
                        $CrTotal=0;
                    ?>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if(count($journal)>0)
                            <table class="table table-sm table-bordered table-striped align-middle  mb-0">
                                <tbody>
                                    <tr>
                                        <th class="col-md-1 text-center">DATE</th>
                                        <th class="col-md-1 text-center">VHNO</th>
                                        <th class="col-md-1 text-center">Type</th>
                                        <th class="col-md-5 text-center">Description</th>
                                        <th class="col-md-1 text-center">DR</th>
                                        <th class="col-md-1 text-center">CR</th>
                                        <th class="col-md-1 text-center">Balance</th>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-danger text-end">Opening Balance</td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-danger text-end">{{  number_format( $sql[0]->Balance,2) }}</td>
                                    </tr>
                                    @foreach ($journal as $key =>$value)
                                    <tr>
                                        <td class="text-center">{{dateformatman($value->Date)}}</td>
                                        <td class="text-center"><a href="{{ Str::startsWith($value->VHNO, 'SI') ? URL('/InvoiceEdit/'.$value->InvoiceMasterID) : URL('/UmrahEdit/'.$value->InvoiceMasterID) }}" target="_blank">
    {{$value->VHNO}}
</a></td>
                                        <td class="text-center"><a href="{{ URL('/VoucherEdit/'.$value->VoucherMstID) }}" target="_blank">{{$value->JournalType}}</a></td>
                                        <td>{{$value->Narration}}</td>
                                        <td class="text-end">{{ $value->Dr ? number_format($value->Dr, 2) : '' }}</td>
                                        <td class="text-end">{{ $value->Cr ? number_format($value->Cr, 2) : '' }}</td>
                                        <td class="text-end">
                                            <?php 
                                                if (!isset($balance)) { 
                                                    $balance = $sql[0]->Balance + ($value->Dr - $value->Cr);
                                                    $DrTotal += $value->Dr;
                                                    $CrTotal += $value->Cr;
                                                } else {
                                                    $balance += ($value->Dr - $value->Cr);
                                                    $DrTotal += $value->Dr;
                                                    $CrTotal += $value->Cr;
                                                }
                                                echo number_format($balance,2);
                                            ?>
                                            {{ ($balance > 0) ? "DR" : "CR" }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-active">
                                        <td></td>
                                        <td></td>
                                        <td>TOTAL</td>
                                        <td class="text-end"></td>
                                        <td class="text-end fw-bolder">{{ number_format($DrTotal, 2) }}</td>
                                        <td class="text-end fw-bolder">{{ number_format($CrTotal, 2) }}</td>
                                        <td class="text-end fw-bolder"> {{number_format($DrTotal-$CrTotal,2)}}</td>

                                    </tr>
                                </tbody>
                            </table>
                            @else
                            <p class="text-danger">No data found</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- Include jQuery if not already included -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
  $(document).ready(function(){
    $('#pdf').click(function(event){
        event.preventDefault();
        $('#form1').removeAttr('target');
        $('#form1').attr('action', '{{URL("/SupplierLedger1PDF")}}');
        $('#form1').attr('target', '_blank');
        $('#form1').submit();
    });

    $('#excel').click(function(event){
        event.preventDefault();
        $('#form1').removeAttr('target');
        $('#form1').attr('action', '{{URL("/SupplierLedgerExcelExport")}}');
        $('#form1').submit();
    });

    $('#online').click(function(event){
        event.preventDefault();
        $('#form1').removeAttr('target');
        $('#form1').attr('action', '{{URL("/SupplierLedger1")}}');
        $('#form1').submit();
    });
});
</script>

@endsection
