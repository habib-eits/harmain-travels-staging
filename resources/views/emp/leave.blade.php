@extends('template.tmp')

@section('title', 'Emplyee Section')
 

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript">

           

         function view_data(id)
    {
 window.open("{{URL('/LeaveDetail')}}/"+id,"_self"); 
//alert(id);
    }  

     function edit_data(id)
    {
 window.open("{{URL('/LeaveEdit')}}/"+id,"_self"); 
//alert(id);
    }

    function del_data(id)
    {

        var txt;
var r = confirm("Do you want to delete");
if (r == true) {
   window.open("{{URL('/LeaveDelete')}}/ "+id,"_self");  
} else {
  txt = "You pressed Cancel!";
}



//alert(id);
    }

        </script>
 <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Employee Detail</h4>

                                    <div class="page-title-right">
                                        <div class="page-title-right">
                                         <!-- button will appear here -->

                                         <a href="{{URL('/Employee')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-arrow-left  me-1 pt-5"></i> Go Back</a>
                                         
                                    </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-9">
                                 @if (session('error'))

<div class="alert alert-{{ Session::get('class') }} p-3 " id="success-alert">
                    
                  {{ Session::get('error') }} 
                </div>

@endif

  @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger pt-3 pl-0   border-3 bg-danger text-white">
                   <p class="font-weight-bold"> There were some problems with your input.</p>
                    <ul>
                        
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>

                        @endforeach
                    </ul>
                </div>
                </div>

            @endif

           @include('emp.emp_info')

                             <div class="card">
                                  <div class="card-header bg-transparent border-bottom h5  ">
                                        Offical Details
                                    </div>
                                    <div class="card-body">

                                        <form action="{{URL('/LeaveSave')}}" method="post"> 
                                            {{csrf_field()}} 


                                            
                                                <div class="col-md-4">
                                             <div class="mb-3">
                                                <label for="basicpill-firstname-input">Branch*</label>
                                                 <select name="BranchID" id="BranchID" class="form-select" required="">
                                                <option value="">Select</option>

                                                 @foreach($branch as $value)
                                                  <option value="{{$value->BranchID}}" {{(old('BranchID')== $value->BranchID) ? 'selected=selected': '' }}>{{$value->BranchName}}</option>
                                                 @endforeach
                                                
                                             
                                              </select>
                                              </div>
                                               </div>


                                               
                                                   <div class="col-md-4">
                                                <div class="mb-3">
                                                   <label for="basicpill-firstname-input">Employee Name*</label>
                                                    <select name="EmployeeID" id="EmployeeID" class="form-select">
                                            
                                            @foreach($employee as $value)
                                             <option value="{{$value->EmployeeID}}" {{(old('EmployeeID')== $value->EmployeeID) ? 'selected=selected': '' }}>{{$value->FirstName}}</option>
                                            @endforeach
                                           
                                                 </select>
                                                 </div>
                                                  </div>
                                               
                                               
<div class="row">
    
                                          <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="basicpill-firstname-input">From Date *</label>
                                                                 

                                                                    <div class="input-group" id="datepicker2">
  <input type="text" name="FromDate" autocomplete="off" class="form-control" placeholder="dd/mm/yyyy" data-date-format="dd/mm/yyyy" data-date-container="#datepicker2" data-provide="datepicker" data-date-autoclose="true">
  <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
    </div>
                                                            


 



                                                            </div>

                                      </div>
                                                  <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="basicpill-firstname-input">From Date *</label>
                                                                 

                                                                    <div class="input-group" id="datepicker21">
  <input type="text" name="ToDate"  autocomplete="off" class="form-control" placeholder="dd/mm/yyyy" data-date-format="dd/mm/yyyy" data-date-container="#datepicker21" data-provide="datepicker" data-date-autoclose="true">
  <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
    </div>
                                                            


 



                                                            </div>

                                      </div>

                                      <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label for="verticalnav-address-input">Reason</label>
                                                                <textarea id="verticalnav-address-input" class="form-control" rows="2" name="Reason">{{old('Reason')}}</textarea>
                                                            </div>
                                                        </div>
</div>
                                               
                                               
                                               
                                               
                                            

                                            
                                            

                                            <div><button type="submit" class="btn btn-success w-lg float-right">Save </button>
                                                 <a href="{{URL('/')}}" class="btn btn-secondary w-lg float-right">Cancel</a>
                                            </div>
                                            

                                        </form>

 
                                    </div>
                                </div>
                                <!-- end card -->

                                 <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Leave List</h4>
                                          

                                   

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->



                                


                        <div class="row">
                            <div class="col-md-12">
                                 <div class="card">
                                     <div class="card-body p-4">
                                         <table id="datatable" class="table   dt-responsive  nowrap w-100 table-sm">
                                            <thead>
                                            <tr>
                                                
                                                <th>From</th>
                                                <th>To</th>
                                                <th>No of Days</th>
                                                <th>Reason</th>
                                                <th>OM</th>
                                                <th>HR</th>
                                                <th>GM</th>
                                                
                                                
                                                <th>Action</th>
                                           
                                                
                                             </tr>
                                            </thead>
        
        
                                            <tbody>
                                             
                                            </tbody>
                                        </table>
                                     </div>
                                 </div>
        
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                           
                        </div>
                        <!-- end row -->



                            </div>
                            <!-- end col -->
                         
                         <!-- employee detail side bar -->
                         @include('template.emp_sidebar')

                           
                        </div>
                        <!-- end row -->

                      

                       

                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>

<script type="text/javascript">
$(document).ready(function() {

     

     $('#datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength":50,
        "ajax": "{{ url('ajax_leave') }}",
        "columns":[
           
            { "data": "FromDate" },
            { "data": "ToDate" },
            { "data": "NoOfDays" },
            { "data": "Reason" },
            { "data": "OMStatus" },
            { "data": "HRStatus" },
            { "data": "GMStatus" },
            
             
            
           
        
            { "data": "action" }
        ]
     
     });
});

$("#success-alert").fadeTo(4000, 500).slideUp(100, function(){
    // $("#success-alert").slideUp(500);
    $("#success-alert").alert('close');
});


</script>
  @endsection