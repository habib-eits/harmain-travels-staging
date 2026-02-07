 @extends('template.tmp')
@section('title', $pagetitle)
@section('content')



 
  
    <meta name="csrf-token" content="{{ csrf_token() }}" />
 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />

 <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>


   <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
 
<!-- SweetAlert CSS -->
 
<!-- SweetAlert JS -->
 
 
 
       <!-- Modal -->
<div id="myModalEdit" class="modal fade" role="dialog" >
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
   <div class="modal-header py-3 px-4 border-bottom-0">
        <h5 class="modal-title" id="modal-title">Update Event</h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>

    </div>
      <!-- enctype="multipart/form-data" -->
         <form action="{{URL('/BookingUpdate')}}" method="post"> 
            @csrf
        <div id="modalBody" class="modal-body p-4">
            <input type="hidden" name="id" id="id">    
              <div class="row">
                  
                      <div class="col-md-12">
                <div class="mb-3">
                <label for="basicpill-firstname-input">Title *</label>
                <input type="text" class="form-control" name="Title"  id="Title1" required="">
                </div>
                </div>


                 <div class="col-md-12">
                <div class="mb-3">
                <label for="basicpill-firstname-input">Start *</label>
                <input type="datetime-local" class="form-control" name="Start" id="Start1">
                </div>
                </div>
                
                <div class="col-md-12">
                <div class="mb-3">
                <label for="basicpill-firstname-input">End *</label>
                <input type="datetime-local" class="form-control" name="End" id="End1">
                </div>
                </div>


                    <div class="col-md-12">
 <div class="mb-3">
   
<select class="form-control colors" id="colorSelect" name="Color" style="width: 100%">
                     <option value="black"></option>
                     <option value="Orange"></option>
                     <option value="DodgerBlue"></option>
                     <option value="MediumSeaGreen
"></option>
                    <option value="Gray"></option>
                    <option value="SlateBlue"></option>
                    <option value="Violet"></option>
                       <option value="darkred"></option>
                     <option value="darkgreen"></option>
                    <option value="forestgreen"></option>
                    <option value="olive"></option>
                  
                    <option value="darkblue"></option>
                     <option value="darkorange"></option>
                     <option value="Teal"></option>
                     <option value="Thistle"></option>
                     <option value="SpringGreen"></option>
                     <option value="SkyBlue"></option>
                     <option value="RebeccaPurple"></option>
                     <option value="MediumOrchid"></option>
                     <option value="MediumVioletRed"></option>
                    <!-- Add all other HTML color names -->
 
  </select>
  </div>
   </div>



              </div>
                
                          <div class="row mt-2">
        <div class="col-6">
            <button type="button" class="btn btn-danger" id="btn-delete-event" onclick="confirmDelete();">Delete</button>
        </div>
        <div class="col-6 text-end">
            <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" id="btn-save-event">Save</button>
        </div>
    </div>



                
            </div>
      <div class="modal-footer d-none">
               <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>



        <button type="button" class="btn btn-light border  border-1 border-right" data-bs-dismiss="modal">Close</button>
                <input class="btn btn-primary" type="submit" value="Submit">

      </div>
</form>   

  </div>
  </div>
  </div>



 
       <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">


  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header py-3 px-4 border-bottom-0">
        <h5 class="modal-title" id="modal-title">Add Event</h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>

    </div>
      <!-- enctype="multipart/form-data" -->
         <form action="{{URL('/BookingStore')}}" method="post"> 
            @csrf
        <div id="modalBody" class="modal-body p-4">
                
              <div class="row">
                  
                      <div class="col-md-12">
                <div class="mb-3">
                <label for="basicpill-firstname-input">Title *</label>
                <input type="text" class="form-control" name="Title"  id="Title" required="">
                </div>
                </div>


                 <div class="col-md-12">
                <div class="mb-3">
                <label for="basicpill-firstname-input">Start *</label>
                <input type="datetime-local" class="form-control" name="Start" id="Start">
                </div>
                </div>
                
                <div class="col-md-12">
                <div class="mb-3">
                <label for="basicpill-firstname-input">End *</label>
                <input type="datetime-local" class="form-control" name="End" id="End">
                </div>
                </div>


                    <div class="col-md-12">
 <div class="mb-3">
   
<select class="form-control colors" id="colorSelect" name="Color" style="width: 100%">
                     <option value="black"></option>
                     <option value="Orange"></option>
                     <option value="DodgerBlue"></option>
                     <option value="MediumSeaGreen
"></option>
                    <option value="Gray"></option>
                    <option value="SlateBlue"></option>
                    <option value="Violet"></option>
                       <option value="darkred"></option>
                     <option value="darkgreen"></option>
                    <option value="forestgreen"></option>
                    <option value="olive"></option>
                  
                    <option value="darkblue"></option>
                     <option value="darkorange"></option>
                     <option value="Teal"></option>
                     <option value="Thistle"></option>
                     <option value="SpringGreen"></option>
                     <option value="SkyBlue"></option>
                     <option value="RebeccaPurple"></option>
                     <option value="MediumOrchid"></option>
                     <option value="MediumVioletRed"></option>
                    <!-- Add all other HTML color names -->

  </select>
  </div>
   </div>



              </div>
                
                              <div class="row mt-2">
        <div class="col-6">
            
        </div>
        <div class="col-6 text-end">
            <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" id="btn-save-event">Save</button>
        </div>
    </div>
    
                
            </div>


      <div class="modal-footer d-none">
        <button type="button" class="btn btn-light border  border-1 border-right" data-bs-dismiss="modal">Close</button>
                <input class="btn btn-primary" type="submit" value="Submit">

      </div>
</form> 


    </div>

  </div>


 



 
</div>

   <div class="main-content">

 <div class="page-content">
 <div class="container-fluid">

 <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script>
 @if(Session::has('error'))
   toastr.options =
   {
     "closeButton" : false,
     "progressBar" : true
   }
         Command: toastr["{{session('class')}}"]("{{session('error')}}")
   @endif
 </script> 

         <div id="calendar"></div>
    </div>
    </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev, next today',
                    center: 'title',
                    right: 'month, agendaWeek, agendaDay, list',
                },
                 events: "{{URL('/bookings')}}",
                selectable: true,
                selectHelper: true,
                // select: function(start, end, allDays) {
                //     // $('#BookingModal').modal('toggle');


                //      end.subtract(1, 'days');


                //  $('#Start').val($.fullCalendar.formatDate(start, "Y-MM-DD"));
                // $('#End').val($.fullCalendar.formatDate(end, "Y-MM-DD"));



                //     $('#saveBtn').click(function() {
                //         var title = $('#title').val();
                //         var start_date = moment(start).format('YYYY-MM-DD');
                //         var end_date = moment(end).format('YYYY-MM-DD');

                //         $.ajax({
                //             url:"{{URL('/cstore')}}",
                //             type:"POST",
                //             dataType:'json',
                //             data:{ title, start_date, end_date  },
                //             success:function(response)
                //             {
                //                 $('#BookingModal').modal('hide')
                //                 $('#calendar').fullCalendar('renderEvent', {
                //                     'title': response.title,
                //                     'start' : response.start,
                //                     'end'  : response.end,
                //                     'color' : response.red,
                //                 });

                //             },
                //             error:function(error)
                //             {
                //                 if(error.responseJSON.errors) {
                //                     $('#titleError').html(error.responseJSON.errors.title);
                //                 }
                //             },
                //         });
                //     });
                // },
                editable: true,
              eventDrop: function(event) {
        // Create the data to send to the server
        const data = {
            id: event.id,  // Unique identifier for the event
            title: event.title,  // Unique identifier for the event
            start: event.start.format('Y-MM-DD h:mm:ss'),  // New start time
            end: event.end ? event.end.format('Y-MM-DD h:mm:ss') : event.start.format('Y-MM-DD h:mm:ss'),  // New end time (if defined)
        };


        // event.end.format('Y-MM-DD h:mm:ss') || event.start.format('Y-MM-DD h:mm:ss');

        // Make an AJAX call to update the event in the database
        $.ajax({
            url: '{{URL("/BookingDraged")}}',  // Your endpoint to update the event
            type: 'POST',  // Use POST to send data
            data: JSON.stringify(data),  // Convert data to JSON
            contentType: 'application/json',  // Specify JSON content type
            success: function(response) {
                Command: toastr["success"]("upated Successfully")


            },
            error: function(xhr, status, error) {
                console.error('Failed to update event:', status, error);
                revertFunc();  // Revert the event on error
            }
        });
    },
                eventClick: function(event){


                    var id = event.id;

                    var start = event.start;
                    var end = event.end || start;


                     alert(event.description);
                

                    $("#myModalEdit").modal('show');

                    
                    $('#id').val(event.id); 
                    $('#Title1').val(event.title); 
                    $('#Start1').val(moment(event.start).format('Y-MM-DD h:mm:ss')); 
                    $('#End1').val(moment(end).format('Y-MM-DD h:mm:ss')); 

                     $('#colorSelect').val(event.color);
                    $('#colorSelect').trigger('change');


                    
 
                    // if(confirm('Are you sure want to remove it')){
                    //     $.ajax({
                    //         url:"{{URL('/')}}" +'/'+ id,
                    //         type:"DELETE",
                    //         dataType:'json',
                    //         success:function(response)
                    //         {
                    //             $('#calendar').fullCalendar('removeEvents', response);
                    //             // swal("Good job!", "Event Deleted!", "success");
                    //         },
                    //         error:function(error)
                    //         {
                    //             console.log(error)
                    //         },
                    //     });
                    // }

                },
                 

         select: function (start, end, allDay) {
 // alert($.fullCalendar.formatDate(start, "Y-MM-DD h:mm:ss")+'---'+$.fullCalendar.formatDate(end, "Y-MM-DD h:mm:ss"));
  
  $("#myModal").modal('show');
    // var title = prompt($.fullCalendar.formatDate(start, "Y-MM-DD h:mm:ss"));


        // it is adding one more day so we have to minus one day 
             end.subtract(1, 'days');


                 $('#Start').val($.fullCalendar.formatDate(start, "Y-MM-DD h:mm:ss"));
                $('#End').val($.fullCalendar.formatDate(end, "Y-MM-DD h:mm:ss"));

                if (title) {

                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD h:mm:ss");

                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD-DD h:mm:ss");



                    $.ajax({

                        url: SITEURL + "/BookingStore",

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

            });


            $("#bookingModal").on("hidden.bs.modal", function () {
                $('#saveBtn').unbind();
            });

            $('.fc-event').css('font-size', '13px');
            $('.fc-event').css('width', '20px');
            $('.fc-event').css('border-radius', '50%');


        });
    </script>

    <script>
function confirmDelete() {
    id=$('#id').val();
    var confirmation = confirm("Are you sure you want to delete this item?");
    if (confirmation) {
        
        window.location="{{URL('/BookingDelete')}}"+'/'+id;
    } else {
        // Action to perform if canceled
        console.log("Deletion canceled.");
    }
}
</script>

<script>
$('.colors option').each(function() {
$(this).css('background-color', $(this).val());
});

$('.colors').on('change', function() {
$(this).css('background-color', $(this).val());
});
</script>


<style type="text/css" media="screen">
    
.colors {

background-color: yellow;

}

</style>



 @endsection