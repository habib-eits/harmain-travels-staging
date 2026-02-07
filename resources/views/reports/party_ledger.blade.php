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
            <h4 class="mb-sm-0 font-size-18">Party Ledger</h4>



          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <!-- enctype="multipart/form-data" -->
          <form action="{{URL('/PartyLedger1')}}" method="post" name="form1" id="form1"> {{csrf_field()}}



         

            <div class="col-md-4">
              <label for="basicpill-firstname-input">Party Name</label>
              <div class="mb-2">
                  <select name="PartyID" id="partyID" class="select2 form-select" required>
                      <option value="">Select</option>
                      <?php foreach ($party as $key => $value): ?>
                      <option value="{{$value->PartyID}}">{{$value->PartyID}}-{{$value->PartyName}}-{{$value->Phone}}</option>
                      <?php endforeach ?>
                  </select>
                  <span id="selectError" style="color: red; display: none;">Please select a party</span>
              </div>
          </div>
      



       

            <style>
              .datepicker {
                z-index: 1001 !important;
              }
            </style>

             
              {{-- 
                Render a component for selecting start and end dates.
                file path: resources\views\components\start-end-date.blade.php 
              --}}
            
 
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

<script>
  $(document).ready(function(){
    $('#pdf').click(function(event){
        event.preventDefault();
        $('#form1').removeAttr('target');
        $('#form1').attr('action', '{{URL("/PartyLedger1PDF")}}');
        $('#form1').attr('target', '_blank');
        $('#form1').submit();
    });


    $('#online').click(function(event){
        event.preventDefault();
        $('#form1').removeAttr('target');
        $('#form1').attr('action', '{{URL("/PartyLedger1")}}');
        $('#form1').submit();
    });
});
</script>

<script>
  $(document).ready(function() {
      const $selectID = $('#partyID');
      const $buttons = $('.btn-disable');
      const $selectError = $('#selectError');
  
      $selectID.on('change', function() {
          if ($selectID.val() === "") {
              $buttons.prop('disabled', true);
              $selectError.show();
          } else {
              $buttons.prop('disabled', false);
              $selectError.hide();
          }
      });
  
      // Initialize state
      if ($selectID.val() === "") {
          $buttons.prop('disabled', true);
          $selectError.show();
      } else {
          $buttons.prop('disabled', false);
          $selectError.hide();
      }
  });
  </script>

@endsection