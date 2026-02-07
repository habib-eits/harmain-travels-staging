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

                                        <form action="{{URL('/LeaveUpdate')}}" method="post"> 
                                            {{csrf_field()}} 

<input type="hidden" name="LeaveID" value="{{$leave[0]->LeaveID}}">

<?php 

$BranchID = old('BranchID') ? old('BranchID') : $leave[0]->BranchID ;
$FromDate = old('FromDate') ? old('FromDate') : dateformatman($leave[0]->FromDate) ;
$ToDate = old('ToDate') ? old('ToDate') : dateformatman($leave[0]->ToDate) ;
 $Reason = old('Reason') ? old('Reason') : $leave[0]->Reason ;
 $DaysApproved = old('DaysApproved') ? old('DaysApproved') : $leave[0]->DaysApproved ;
 $DaysRemaining = old('DaysRemaining') ? old('DaysRemaining') : $leave[0]->DaysRemaining ;


 ?>
                                            
                                                <div class="col-md-4">
                                             <div class="mb-3">
                                                <label for="basicpill-firstname-input">Branch*</label>
                                                 <select name="BranchID" id="BranchID" class="form-select">
                                                <option value="">Select</option>

                                                 @foreach($branch as $value)
                                                  <option value="{{$value->BranchID}}" {{($value->BranchID== $BranchID) ? 'selected=selected':'' }}>{{$value->BranchName}}</option>
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
                                                                 

                                                                   <input name="FromDate" id="input-date1" class="form-control input-mask" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" value="{{$FromDate}}" im-insert="false">
                                                            <span class="text-muted">e.g "dd/mm/yyyy"</span>



                                                            </div>

                                      </div>
                                                <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="basicpill-firstname-input">To Date *</label>
                                                                 

                                                                   <input name="ToDate" id="input-date1" class="form-control input-mask" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" value="{{$ToDate}}" im-insert="false">
                                                            <span class="text-muted">e.g "dd/mm/yyyy"</span>



                                                            </div>

                                      </div>


                                        <div class="col-md-2">
                                      <div class="mb-3">
                                      <label for="basicpill-firstname-input"><span class="text-danger">Days Approved</span></label>
                                      <input type="text" class="form-control" name="DaysApproved" value="{{$DaysApproved}} ">
                                      </div>
                                      </div>   

                                       <div class="col-md-2">
                                      <div class="mb-3">
                                      <label for="basicpill-firstname-input"><span class="text-success">Days Remaining</span></label>
                                      <input type="text" class="form-control" name="DaysRemaining" value="{{$DaysRemaining}} ">
                                      </div>
                                      </div>
                                      
                                      
                                      

                                      <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label for="verticalnav-address-input">Reason</label>
                                                                <textarea id="verticalnav-address-input" class="form-control" rows="" name="Reason">{{$Reason}}</textarea>
                                                            </div>
                                                        </div>
</div>
                                               
                                               
                                               
                                               
                                            

                                            
                                            

                                           
                                            

                                      

 
                                    </div>
                                </div>
                                <!-- end card -->



                                <div class="card">
                                    <div class="card-body">
                                      <h5>Operational Manager</h5>
                                      <hr>

                                      
                                   
                                    
                                  <div class="row">
                                    
                                          <div class="col-md-4">
                                     <div class="mb-3">
                                        <label for="basicpill-firstname-input">Approval</label>
                                         <select name="OMStatus" id="OMStatus" class="form-select" {{(session::get('UserType')=='OM') ? '' : 'disabled'}}>
                                        <option value="">Select</option>
                                        <option value="Pending" {{($leave[0]->OMStatus=='Pending') ? 'selected=selected':'' }}>Pending</option>
                                        <option value="Yes" {{($leave[0]->OMStatus=='Yes') ? 'selected=selected':'' }}>Yes</option>
                                        <option value="No" {{($leave[0]->OMStatus=='No') ? 'selected=selected':'' }}>No</option>
                                        
                                       
                                     
                                      </select>
                                      </div>
                                       </div>
                                    

                                    <div class="col-md-4">
                                  <div class="mb-3">
                                  <label for="basicpill-firstname-input">Checked on</label>
                                  <input type="text" class="form-control" name="OMStatusDate" value="{{$leave[0]->OMStatusDate}} " {{(session::get('UserType')=='OM') ? '' : 'disabled'}}>
                                  </div>
                                  </div>




                                    
                                    <div class="col-md-12">
                                    <div class="mb-3">
                                    <label for="verticalnav-address-input">Remarks if any</label>
                                    <textarea id="verticalnav-address-input" class="form-control" rows="" name="OMRemarks" {{(session::get('UserType')=='OM') ? '' : 'disabled'}}>{{$leave[0]->OMRemarks}} </textarea>
                                    </div>
                                    </div>
                                  </div>

                                      
                                      
                                    </div>
                                </div>
                                



                                 <div class="card">
                                    <div class="card-body">
                                      <h5>HR Manager</h5>
                                      <hr>

                                      
                                     
                                    
                                  <div class="row">
                                    
                                          <div class="col-md-4">
                                     <div class="mb-3">
                                        <label for="basicpill-firstname-input">Approval</label>
                                        <select name="HRStatus" id="HRStatus" class="form-select" {{(session::get('UserType')=='HR') ? '' : 'disabled'}}>
                                        <option value="">Select</option>
                                        <option value="Pending" {{($leave[0]->HRStatus=='Pending') ? 'selected=selected':'' }}>Pending</option>
                                        <option value="Yes" {{($leave[0]->HRStatus=='Yes') ? 'selected=selected':'' }}>Yes</option>
                                        <option value="No" {{($leave[0]->HRStatus=='No') ? 'selected=selected':'' }}>No</option>
                                     
                                      </select>
                                      </div>
                                       </div>
                                    

                                    <div class="col-md-4">
                                  <div class="mb-3">
                                  <label for="basicpill-firstname-input">Checked on</label>
                                  <input type="text" class="form-control" name="HRStatusDate" value=" {{$leave[0]->HRStatusDate}} " {{(session::get('UserType')=='HR') ? '' : 'disabled'}} >
                                  </div>
                                  </div>




                                    
                                    <div class="col-md-12">
                                    <div class="mb-3">
                                    <label for="verticalnav-address-input">Remarks if any</label>
                                    <textarea id="verticalnav-address-input" class="form-control" rows="" name="HRRemarks" {{(session::get('UserType')=='HR') ? '' : 'disabled'}} >{{$leave[0]->HRRemarks}}</textarea>
                                    </div>
                                    </div>
                                  </div>

                                      
                                      
                                    </div>
                                </div>
                                

                                 <div class="card">
                                    <div class="card-body   ">
                                      <h5>General Manager</h5>
                                      <hr>



                                    
                                  <div class="row">
                                    
                                          <div class="col-md-4">
                                     <div class="mb-3">
                                        <label for="basicpill-firstname-input">Approval</label>
                                          <select name="GMStatus" id="GMStatus" class="form-select" {{(session::get('UserType')=='GM') ? '' : 'disabled'}}>
                                        <option value="">Select</option>
                                        <option value="Pending" {{($leave[0]->GMStatus=='Pending') ? 'selected=selected':'' }}>Pending</option>
                                        <option value="Yes" {{($leave[0]->GMStatus=='Yes') ? 'selected=selected':'' }}>Yes</option>
                                        <option value="No" {{($leave[0]->GMStatus=='No') ? 'selected=selected':'' }}>No</option>
                                     
                                      </select>
                                      </div>
                                       </div>
                                    

                                    <div class="col-md-4">
                                  <div class="mb-3">
                                  <label for="basicpill-firstname-input">Checked on</label>
                                  <input type="text" class="form-control" name="GMStatusDate" value=" {{$leave[0]->GMStatusDate}} " {{(session::get('UserType')=='GM') ? '' : 'disabled'}} >
                                  </div>
                                  </div>




                                    
                                    <div class="col-md-12">
                                    <div class="mb-3">
                                    <label for="verticalnav-address-input">Remarks if any</label>
                                    <textarea id="verticalnav-address-input" class="form-control" rows="" name="GMRemarks" {{(session::get('UserType')=='GM') ? '' : 'disabled'}} >{{$leave[0]->GMRemarks}}</textarea>
                                    </div>
                                    </div>
                                  </div>

                                       <div><button type="submit" class="btn btn-success w-md float-right">Update </button>
                                                 <a href="{{URL('/Leave')}}" class="btn btn-secondary w-md float-right">Cancel</a>
                                            </div>
                                      
                                    </div>


                                </div>
                                  </form>

                                 <!-- start page title -->
                 
                        <!-- end page title -->


 



                            </div>
                            <!-- end col -->
                         
                         <!-- employee detail side bar -->
                         @include('template.emp_sidebar')

                           
                        </div>
                        <!-- end row -->

                      

                       

                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>

<script type="text/javascript">
 

$("#success-alert").fadeTo(4000, 500).slideUp(100, function(){
    // $("#success-alert").slideUp(500);
    $("#success-alert").alert('close');
});


</script>
  @endsection