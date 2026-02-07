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
                            <h4 class="mb-sm-0 font-size-18">  Hotel Wise Profit Report</h4>


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


                        <form action="{{route('hotel.wise.payment.report2')}}" name="hotel_hcn" id="hotel_hcn" method="post">
                            @csrf

                            <div class="row">


                                     <div class="col-lg-3 col-md-3  col-sm-3 ">
                                    <label class="control-label" for="DateFrom">Select Report </label>
                                    <select name="ReportType" id="ReportType" class="form-select select2">
                                        <option value="Detail">Voucher Wise Report</option>
                                        <option value="Summary">Summary Report</option>
                                          
                                    </select>
                                </div>

                                <input type="text" name="profit" value="1" hidden >


                                <div class="col-lg-3 col-md-3  col-sm-3">
                                    <label class="control-label" for="DateFrom">Date From</label>
                                    <input type="date" id="StartDate" name="StartDate" class="form-control"
                                        value="{{date('Y-m-01')}}" required="">
                                </div>

                                <div class="col-lg-3 col-md-3  col-sm-3">
                                    <label class="control-label" for="DateFrom">Date To</label>
                                    <input type="date" id="EndDate" name="EndDate" class="form-control"
                                        value="{{date('Y-m-d')}}" required="">
                                </div>
                                
                                     <div class="col-lg-3 col-md-3  col-sm-3 ">
                                    <label class="control-label" for="DateFrom">City  </label>
                                    <select name="location_id" id="location_id" class="form-select select2">
                                        <option value="">All</option>
                                          @foreach ($location as $item)
                                            <option value="{{ $item->location }}">{{ $item->location }}</option>
                                        @endforeach
                                        
                                    </select>
                                </div>

                                
                                
                                <div class="col-lg-3 col-md-3  col-sm-3 mt-2 ">
                                    <label class="control-label" for="DateFrom">Party Name </label>
                                    <select name="PartyID" id="PartyID" class="form-select select2">
                                        <option value="">All Clients</option>
                                          @foreach ($party as $item)
                                            <option value="{{ $item->PartyID }}">{{ $item->PartyName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                
                                <div class="col-lg-3 col-md-3  col-sm-3 mt-2 ">
                                    <label class="control-label" for="DateFrom">Hotel Name </label>
                                    <select name="hotel_id" id="hotel_id" class="form-select select2">
                                        <option value="">All Clients</option>
                                          @foreach ($hotel as $item)
                                            <option value="{{ $item->id }}">{{ $item->hotel_name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-3 col-md-3  col-sm-3 mt-2 ">
                                    <label class="control-label" for="DateFrom">Supplier Name </label>
                                    <select name="supplier_id" id="supplier_id" class="form-select select2">
                                        <option value="">All Clients</option>
                                          @foreach ($supplier as $item)
                                            <option value="{{ $item->PartyID }}">{{ $item->PartyName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                  
                                
                                
                              
                                
                                <div class="col-lg-3 col-md-3  col-sm-3 mt-2">
                                
                                    <button type="submit" class="btn btn-primary btn-block  mt-4 w-100">View Report</button>
                                </div>

                            </div>


                        </form>

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
    $.get("url", data,
        function(data, textStatus, jqXHR) {

        },
        "dataType"
    );
</script>
