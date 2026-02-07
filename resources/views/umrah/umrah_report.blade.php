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
            <h4 class="mb-sm-0 font-size-18">Umrah Sale Report</h4>



          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <!-- enctype="multipart/form-data" -->
          <form action="{{URL('/UmrahReport1')}}" method="post" name="form1" id="form1"> {{csrf_field()}}



         

      



           

            <style>
              .datepicker {
                z-index: 1001 !important;
              }
            </style>

  <?php 

$users = DB::table('user')->get();

   ?>         
            
 @include('components.start_end_date')

 


  <div class="col-md-4 mt-2">
<div class="mb-3">
<label for="basicpill-firstname-input">Saleman</label>
<select name="UserID" id="UserID" class="form-select">
  <option value="">Any</option>
  
   @foreach($users as $value)
    <option value="{{$value->UserID}}" >{{$value->FullName}}</option>
   @endforeach
  
</select>
</div>
</div>



  <div class="col-md-4 mt-2">
<div class="mb-3">
<label for="basicpill-firstname-input">Item *</label>
<select name="ItemID" id="ItemID" class="form-select">
  <option value="">Any</option>
  
   @foreach($item as $value)
    <option value="{{$value->ItemID}}" >{{$value->ItemName}}</option>
   @endforeach
  
</select>
</div>
</div>



  <div class="col-md-4 mt-2">
<div class="mb-3">
<label for="basicpill-firstname-input">Report Type *</label>
<select name="Type" id="Type" class="form-select">
  <option value="Date">Invoice Date</option>
  <option value="DepartureDate" selected>Departure Date</option>
</select>
</div>
</div>






        </div>
        <div class="card-footer bg-light  ">
          <button type="submit" class="btn-disable btn btn-success w-lg float-right" id="online">Submit</button>
          </div>
      </div>
      </form>
      

    </div>
  </div>
</div>




<!-- END: Content-->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
 
    <script>
        $(document).ready(function() {
    $('#StartDate').on('change', function() {
        var startDate = $(this).val();
        var endDate = $('#EndDate').val();

            if (!endDate || new Date(endDate) < new Date(startDate)) {
                $('#EndDate').val(startDate);
            }


        $('#EndDate').attr('min', startDate);
    });
});



    </script>

@endsection