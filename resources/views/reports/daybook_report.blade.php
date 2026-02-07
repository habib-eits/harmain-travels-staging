@extends('tmp')

@section('title', $pagetitle)

@section('content')

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

@if (session('error'))
<div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
    {{ Session::get('error') }}
</div>
@endif

@if (count($errors) > 0)
<div>
    <div class="alert alert-danger p-1 border-3">
        <p class="font-weight-bold"> There were some problems with your input.</p>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Daybook</h4>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <!-- enctype="multipart/form-data" -->
                    <form action="{{URL('/DaybookReport1')}}" method="post" name="form1" id="form1"> 
                        {{csrf_field()}}

                        <div class="col-md-4">
                            <div class="mb-1">
                                <label for="basicpill-firstname-input">Account #</label>
                                <select name="ChartOfAccountID" class="select2 form-select" id="select2-basic">
                                    <option value="0">All</option>
                                    @foreach ($chartofaccount as $value)
                                    <option value="{{$value->ChartOfAccountID}}">
                                        {{$value->ChartOfAccountID}}-{{$value->ChartOfAccountName}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


 

                 
  @include('components.start_end_date')
 
                        <!-- Submit buttons -->
                        <div class="card-footer bg-light">
                            <button type="submit" class="btn btn-success w-lg float-right" id="online">Submit</button>
                            <button type="submit" class="btn btn-success w-lg float-right" id="pdf">PDF</button>
                            <a href="{{URL('/')}}" class="btn btn-secondary w-lg float-right">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

 

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>

<script>
  $(document).ready(function(){
    $('#pdf').click(function(event){
        event.preventDefault();
        $('#form1').removeAttr('target');
        $('#form1').attr('action', '{{URL("/DaybookReport1PDF")}}');
        $('#form1').submit();
    });
    $('#online').click(function(event){
        event.preventDefault();
        $('#form1').removeAttr('target');
        $('#form1').attr('action', '{{URL("/DaybookReport1")}}');
        $('#form1').submit();
    });
});
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

@endsection


 