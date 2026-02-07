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
            <h4 class="mb-sm-0 font-size-18">Itemwise Sale</h4>



          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <!-- enctype="multipart/form-data" -->
          <form action="{{URL('/ItemWiseSale2')}}" method="post" name="form1" id="form1"> {{csrf_field()}}



         

            <div class="col-md-4 d-none">
              <label for="basicpill-firstname-input">Item</label>
              <div class="mb-2">
                  <select name="ItemID" id="ItemID" class="select2 form-select"  >
                      <option value="">Select</option>
                      <?php foreach ($item as $key => $value): ?>
                      <option value="{{$value->ItemID}}">{{$value->ItemCode}}-{{$value->ItemName}}</option>
                      <?php endforeach ?>
                  </select>
                  <span id="selectError" style="color: red; display: none;">Please select item</span>
              </div>
          </div>
         



           

            <style>
              .datepicker {
                z-index: 1001 !important;
              }
            </style>

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
        <div class="card-footer bg-light  ">
          <button type="submit" class="btn-disable btn btn-success w-lg float-right" id="online">Submit</button>
          <button type="submit" class="btn-disable btn btn-success w-lg float-right" id="pdf">PDF</button>
          <a href="{{URL('/')}}" class="btn-disable btn btn-secondary w-lg float-right">Cancel</a>
        </div>
      </div>
      </form>
      

    </div>
  </div>
</div>





<!-- END: Content-->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
 

@endsection