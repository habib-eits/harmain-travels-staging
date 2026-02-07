@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')



<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Party Balances</h4>
                                        <strong class="text-end"><div align="center">{{(request()->ReportType=='C') ? 'Creditor Customers' : 'Debitor Customers' }}</div></strong> 
        From {{request()->StartDate}} TO {{request()->EndDate}}

                                </div>
                            </div>
                        </div>
 @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

 @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger p-1   border-3">
                   <p class="font-weight-bold"> There were some problems with your input.</p>
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

 

  <div class="card">
      <div class="card-body">

        @if(count($party)>0)
     <table class="table table-sm mt-4" id="salesTable">
    <thead class="bg-light">
        <tr>
            <th width="3%">S.NO</th>
            <th width="5%" style="text-align: center;">CODE</th>
            <th width="10%">NAME</th>
            <th width="10%" style="text-align: center;" >DEBIT</th>
            <th width="10%" style="text-align: center;" >CREDIT</th>
            <th width="10%" style="text-align: center;" >BALANCE</th>
         </tr>
    </thead>
    <tbody>
        @foreach ($party as $key => $value)
            <?php 
                $DrTotal = $DrTotal + $value->Dr;
                $CrTotal = $CrTotal + $value->Cr;
            ?>
            <tr>
                <td><div align="center">{{ $key+1 }}.</div></td>
                <td><div align="CENTER">{{ $value->PartyID }}</div></td>
                <td><a href="{{ URL('/PartySalesLedger3/'.$value->PartyID) }}" target="_blank">{{ $value->PartyName }}</a></td>
                <td><div align="right">{{ number_format($value->Dr, 2) }}</div></td>
                <td><div align="right">{{ number_format($value->Cr, 2) }}</div></td>
                <td><div align="right">{{ number_format(($value->Dr) - $value->Cr, 2) }}</div></td>
               
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
             <td></td>
             <td></td>
            <td><strong>TOTAL</strong></td>
            <td align="right"><strong>{{ number_format($DrTotal, 2) }}</strong></td>
            <td align="right"><strong>{{ number_format($CrTotal, 2) }}</strong></td>
            <td align="right"><strong>{{ number_format(($DrTotal) - ($CrTotal), 2) }}</strong></td>
        </tr>
    </tfoot>
</table>
@else
<p>No record found</p>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#salesTable').DataTable({
            paging: false,  // Disable pagination (optional)
            ordering: true, // Enable column sorting
            info: true,     // Show table info (optional)
            searching: true,
            dom: 'lfrtip',
            order: [[2, 'asc']] // Set initial sorting on the 4th column (CREDIT) in descending order
        });
    });
</script>

      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->

  @endsection