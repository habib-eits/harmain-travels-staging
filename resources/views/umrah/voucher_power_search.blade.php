@extends('tmp')

@section('title', 'Voucher Power Search')


@section('content')



    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Party Ledger</h4>
                            < 

                        </div>
                    </div>
                </div>
                @if (session('error'))
                    <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">

                        {{ Session::get('error') }}
                    </div>
                @endif

                @if (count($errors) > 0)

                    <div>
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

                <div class="abc"></div>

                <?php
                $DrTotal = 0;
                $CrTotal = 0;
                ?>
                <div class="card">
                    <div class="card-body">
  <table id="table" class="display nowrap" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Passenger Name</th>
        <th>Action</th>
        <th>Type</th>
        <th>Passport No</th>
        <th>PNR</th>
        <th>Visa No</th>
        <th>Package Name</th>
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
    <!-- END: Content-->

@endsection


  <script>
    $(document).ready(function() {
      $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('ajax.index') }}",
        columns: [
          { data: 'id', name: 'id' },
          { data: 'Date', name: 'Date' },
          { data: 'passenger_name', name: 'passenger_name' },
          { data: 'action', name: 'action', orderable: false, searchable: false },
          { data: 'type', name: 'type' },
          { data: 'passport_no', name: 'passport_no' },
          { data: 'pnr', name: 'pnr' },
          { data: 'visa_no', name: 'visa_no' },
          { data: 'package_name', name: 'package_name' },
        ],
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        pageLength: 10
      });
    });
  </script>
<script>

$.get("url", data,
    function (data, textStatus, jqXHR) {
        
    },
    "dataType"
);
    
</script>