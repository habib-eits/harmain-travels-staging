<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laravel Full Calendar Integration Example</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    {{-- javascript --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>    
</head>

<body>


    <div class="container mt-4">
        <h2 class="mb-5">Laravel Calendar CRUD Events Example</h2>
        <div id='fullCalendar'></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h4 class="modal-title">Add Events</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"> </button>
        
      </div>
        <div id="modalBody" class="modal-body">
                
              <div class="row">
                  
                      <div class="col-md-12">
                <div class="mb-3">
                <label for="basicpill-firstname-input">Title *</label>
                <input type="text" class="form-control" name="Title" value="{{old('Title')}}" id="Title">
                </div>
                </div>


                 <div class="col-md-12">
                <div class="mb-3">
                <label for="basicpill-firstname-input">Start *</label>
                <input type="datetime-local" class="form-control" name="Start" value="{{old('Start')}}" id="Start">
                </div>
                </div>
                
                <div class="col-md-12">
                <div class="mb-3">
                <label for="basicpill-firstname-input">End *</label>
                <input type="datetime-local" class="form-control" name="End" value="{{old('End')}}" id="End">
                </div>
                </div>


              </div>
                
                
                
            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light border  border-1 border-right" data-bs-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

    <script>
        $(document).ready(function () {

            var endpoint = "";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendar = $('#fullCalendar').fullCalendar({
                editable: true,
                editable: true,
                events: "{{URL('/bookings')}}",
                displayEventTime: true,
                 header: {
  start: 'title', // will normally be on the left. if RTL, will be on the right
  center: '',
  end: 'today prev,next' ,// will normally be on the right. if RTL, will be on the left
  right: 'month,basicWeek,basicDay,prev,next'
},initialView: 'dayGridMonth',
                eventRender: function (event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
       
         select: function (start, end, allDay) {
// alert($.fullCalendar.formatDate(start, "Y-MM-DD h:mm:ss")+$.fullCalendar.formatDate(end, "Y-MM-DD h:mm:ss"));
  $("#myModal").modal('show');
                // var title = prompt($.fullCalendar.formatDate(start, "Y-MM-DD h:mm:ss"));

                alert($('#Title').val());
                $('#Start').val($.fullCalendar.formatDate(start, "Y-MM-DD h:mm:ss"));
                $('#End').val($.fullCalendar.formatDate(end, "Y-MM-DD h:mm:ss"));

                if (title) {

                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD h:mm:ss");

                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD-DD h:mm:ss");



                    $.ajax({

                        url: SITEURL + "/store",

                        data: 'title=' + title + '&start=' + start + '&end=' + start,

                        type: "POST",

                        success: function (data) {

                            displayMessage("Added Successfully");

                        }

                    });

                    calendar.fullCalendar('renderEvent',

                            {
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            },
                    true
                            );
                }
                calendar.fullCalendar('unselect');
            },        





       // end of calendar
            });
        });

        function displayMessage(message) {
            toastr.success(message, 'Event');            
        }
    </script>




</body>
</html>