  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>


         <div class="col-md-4"> 
     <div class="mb-2 mt-2">
        <!-- Dropdown -->
        <label for="dateRangeSelector">Select Date Range</label>
        <select id="dateRangeSelector" name="dateRangeSelector" class="form-select">
            <option value="">Select Date Range</option>
            <option value="Today">Today</option>
            <option value="Yesterday">Yesterday</option>
            <option value="This Week">This Week</option>
            <option value="This Month">This Month</option>
            <option value="This Quarter">This Quarter</option>
            <option value="This Year">This Year</option>
            <option value="Year to Date">Year to Date</option>
            <option value="Previous Week">Previous Week</option>
            <option value="Previous Month">Previous Month</option>
            <option value="Previous Quarter">Previous Quarter</option>
            <option value="Previous Year">Previous Year</option>
            <option value="Custom Range">Custom Range</option>
        </select>


              </div>
              </div>

 

 <div class="col-md-4 mt-2  mb-2 ">
   
        <!-- Date Fields -->
        
            <label for="StartDate">Start Date</label>
            <input type="date" id="StartDate" name="StartDate" placeholder="Start Date" class="form-control" value="{{date('Y-m-01')}}">

           
 

 </div>




 <div class="col-md-4  mb-2  mt-2">
   
       

            <label for="EndDate">End Date</label>
            <input type="date" id="EndDate" name="EndDate" placeholder="End Date" class="form-control" value="{{date('Y-m-d')}}">
 

 </div>

 

<script>
        $(document).ready(function () {
            // Handle the date range selection
            $('#dateRangeSelector').on('change', function () {
                let range = $(this).val();
                let startDate = null;
                let endDate = null;

                switch (range) {
                    case "Today":
                        startDate = moment().format("YYYY-MM-DD");
                        endDate = moment().format("YYYY-MM-DD");
                        break;
                    case "Yesterday":
                        startDate = moment().subtract(1, "days").format("YYYY-MM-DD");
                        endDate = startDate;
                        break;
                    case "This Week":
                        startDate = moment().startOf("week").format("YYYY-MM-DD");
                        endDate = moment().endOf("week").format("YYYY-MM-DD");
                        break;
                    case "This Month":
                        startDate = moment().startOf("month").format("YYYY-MM-DD");
                        endDate = moment().endOf("month").format("YYYY-MM-DD");
                        break;
                    case "This Quarter":
                        startDate = moment().startOf("quarter").format("YYYY-MM-DD");
                        endDate = moment().endOf("quarter").format("YYYY-MM-DD");
                        break;
                    case "This Year":
                        startDate = moment().startOf("year").format("YYYY-MM-DD");
                        endDate = moment().endOf("year").format("YYYY-MM-DD");
                        break;
                    case "Year to Date":
                        startDate = moment().startOf("year").format("YYYY-MM-DD");
                        endDate = moment().format("YYYY-MM-DD");
                        break;
                    case "Previous Week":
                        startDate = moment().subtract(1, "week").startOf("week").format("YYYY-MM-DD");
                        endDate = moment().subtract(1, "week").endOf("week").format("YYYY-MM-DD");
                        break;
                    case "Previous Month":
                        startDate = moment().subtract(1, "month").startOf("month").format("YYYY-MM-DD");
                        endDate = moment().subtract(1, "month").endOf("month").format("YYYY-MM-DD");
                        break;
                    case "Previous Quarter":
                        startDate = moment().subtract(1, "quarter").startOf("quarter").format("YYYY-MM-DD");
                        endDate = moment().subtract(1, "quarter").endOf("quarter").format("YYYY-MM-DD");
                        break;
                    case "Previous Year":
                        startDate = moment().subtract(1, "year").startOf("year").format("YYYY-MM-DD");
                        endDate = moment().subtract(1, "year").endOf("year").format("YYYY-MM-DD");
                        break;
                    case "Custom Range":
                        startDate = ""; // Let user manually set dates
                        endDate = "";
                        break;
                }

                // Populate the date fields
                $('#StartDate').val(startDate);
                $('#EndDate').val(endDate);
            });
        });
    </script>

