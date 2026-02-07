@extends('template.tmp')

@section('title', $pagetitle)


@section('content')


<style>

    td, th
    {
        font-size: 0.875rem !important; /* same as .form-control-sm */
    }
    .select2-container .select2-selection--single {
    height: calc(1.8125rem + 2px) !important; /* same as .form-control-sm */
    padding: 0.25rem 0.5rem !important;
    font-size: 0.875rem !important;
    line-height: 1.5 !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.5 !important;
    padding-left: 0.5rem !important;
    padding-right: 0.5rem !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 1.8125rem !important;
}

</style>

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Transport BRN Code
</h4>


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

 

                <div class="card">
                    <div class="card-body">


                        
                        <table class="table  table-sm table-stripped table-bordered  align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>CheckIn</th>
                                     <th>HV</th>
                                     <th>City</th>
                                     <th width="250">Supplier Name</th>
                                    <th>Sector</th>
                                    <th>VehicleType</th>
                                    <th>Qty</th>
                                     <th width="75">HCN</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                <form name="hotel_hcn" id="hotel_hcn" method="post">
                                    @csrf
        <tr>
          <td>{{ $row->TransportDate ?? '-' }}</td>
          <td>{{ $row->invoiceMaster->InvoiceMasterID ?? '-' }}</td>
          <td>{{ $row->TransportCity ?? '-' }}</td>
           <td </td>
          <td>{{ $row->Sector ?? '-' }}</td>
          <td>{{ $row->VehicleType ?? '-' }}</td>
          <td>{{ $row->Quantity ?? '-' }}</td>
  
          <td><input type="text" name="HCN_NO" id="HCN_NO_{{ $row->id }}" class="form-control form-control-sm w-100" value="{{ $row->HCN_NO }}"></td>
          <td><input type="button" value="Update" onclick="updateHCN({{ $row->id }});" class="btn btn-danger btn-sm">
           <a href="{{ route('umrah_voucher_print', $row->invoiceMaster->InvoiceMasterID) }}" class="btn btn-light btn-sm" target="_blank"> <i class="mdi mdi-printer font-size-12"></a></i>
           
           <a href="{{ route('umrah-invoice-master.show', $row->invoiceMaster->InvoiceMasterID) }}" class="btn btn-light btn-sm" target="_blank"> <i class="mdi mdi-receipt font-size-12"></a></i>
        </td>
        </tr>
    </form>
        @endforeach
      </tbody>
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

    function updateHCN(id) {
        var hotel_id = $('#hotel_id_' + id).val();
        var HCN_NO = $('#HCN_NO_' + id).val();
         
        $.ajax({
            url: '{{ route('update.hotel.hcn') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                hotel_id: hotel_id,
                HCN_NO: HCN_NO
            },
            success: function(response) {
                // alert('HCN Updated Successfully');
                // location.reload();

               notyf.success({
                            message: response.message,
                            duration: 3000
                        });

            },
            error: function(xhr) {
                alert('An error occurred while updating HCN.');
            }
        });
    }


</script>

<script>
    $.get("url", data,
        function(data, textStatus, jqXHR) {

        },
        "dataType"
    );
</script>
