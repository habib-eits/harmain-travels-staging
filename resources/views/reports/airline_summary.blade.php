@extends('tmp')

@section('title', $pagetitle)


@section('content')





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
<div class="main-content">

  <div class="page-content">
    <div class="container-fluid">




      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Airline Summary</h4>



          </div>
        </div>
      </div>


      <div class="card">
        <div class="card-body">
          <!-- enctype="multipart/form-data" -->
          <form action="{{URL('/AirlineSummary1')}}" method="post" name="form1" id="form1"> {{csrf_field()}}



            <div class="col-md-4">
              <label for="basicpill-firstname-input">Invoice Type</label>
              <div class="mb-1">
                <select name="InvoiceTypeID" id="" class="  form-select" id="select2-basic">
                  <?php foreach ($invoice_type as $key => $value): ?>
                  <option value="{{$value->InvoiceTypeID}}">{{$value->InvoiceType}}</option>
                  <?php endforeach ?>
                  <option value="both" selected="">Both</option>

                </select>
              </div>
            </div>





            <div class="col-md-4">
              <div class="mb-0">
                <label for="basicpill-firstname-input">Supplier</label>
                <select name="SupplierID" id="" class="select2 form-select" id="select2-basic">
                  <option value="0">All</option>
                  <?php foreach ($supplier as $key => $value): ?>
                  <option value="{{$value->PartyID}}">{{$value->PartyName}}</option>

                  <?php endforeach ?>
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="mb-0">
                <label for="basicpill-firstname-input">Branch</label>
                <select name="BranchID" id="" class="select2 form-select" id="select2-basic" required="">
                    @if(session('UserType') == 'SuperAdmin')
                    <option value="0">All Branches</option>
                    @endif
                  <?php foreach ($branches as $key => $value): ?>
                  <option value="{{$value->id}}">{{$value->id}}-{{$value->name}}</option>

                  <?php endforeach ?>
                </select>
              </div>
            </div>


 @include('components.start_end_date')





        </div>
        <div class="card-footer bg-light">
          <button type="submit" class="btn btn-success w-lg float-right" id="online">Submit</button>
          <button type="submit" class="btn btn-success w-lg float-right" id="pdf">PDF</button>
          <a href="{{URL('/')}}" class="btn btn-secondary w-lg float-right">Cancel</a>
        </div>
      </div>
      </form>
    </div>
  </div>

</div>
</div>
</div>
<!-- END: Content-->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>


<script>
  $(document).ready(function(){
    $('#pdf').click(function(event){
        event.preventDefault();
        $('#form1').removeAttr('target');
        $('#form1').attr('action', '{{URL("/AirlineSummary1PDF")}}');
        $('#form1').submit();
    });
    $('#online').click(function(event){
        event.preventDefault();
        $('#form1').removeAttr('target');
        $('#form1').attr('action', '{{URL("/AirlineSummary1")}}');
        $('#form1').submit();
    });
});
</script>
@endsection