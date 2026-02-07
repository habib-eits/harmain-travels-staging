{{--   
  Render a component for selecting start and end dates.

  Usage:
  <x-start-end-date />
  - Renders inputs for start and end dates with default values:
    - Start Date: January 1, 2022
    - End Date: Today's date.

  Example:
  <x-start-end-date />

  Action:
  - Use this component to display date inputs for specifying a start and end date range.

  Notes:
  - Customize the default dates by passing :startDate and :endDate parameters. 
--}}



<div class="col-md-4">
    <label class="col-form-label" for="email-id">Start Date</label>
    <div class="input-group" id="datepicker21">
      <input type="date" id="" name="StartDate" class="form-control" value="{{ date('Y-m-01') }}">
    </div>
  </div>

  <div class="col-md-4">
    <label class="col-form-label" for="email-id">End Date</label>
    <div class="input-group" id="datepicker22">
      <input type="date" id="" name="EndDate" class="form-control"  value="{{date('Y-m-d')}}" >
     
    </div>
  </div>



   {{-- 
                Render a component for selecting start and end dates.
                file path: resources\views\components\start-end-date.blade.php 
              --}}
            
              {{-- <x-start-end-date /> --}}