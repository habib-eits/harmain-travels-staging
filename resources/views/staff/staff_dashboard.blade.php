@extends('template.staff_tmp')

@section('title', 'Emplyee Section')
 

@section('content')

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
<!-- 
                                         <a href="{{URL('/Employee')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-arrow-left  me-1 pt-5"></i> Go Back</a> -->
                                         
                                    </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-12">
                                 @if (session('error'))

<div class="alert alert-{{ Session::get('class') }} p-3 ">
                    
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

           @include('staff.staff_info')

<!-- 
                             <div class="card">
                                  <div class="card-header bg-transparent border-bottom h5  ">
                                        Offical Details
                                    </div>
                                    <div class="card-body">

                                        dddd

 
                                    </div>
                                </div> -->
                                <!-- end card -->


                                 <div class="card">
                                                            <div class="card-header bg-transparent border-bottom h5">
                                                                  Personal Information
                                                              </div>
                                                              <div class="card-body">
                                                                <!-- start of personal detail row -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-sm table-responsive">
  <tr>
    <td class="fw-bold col-md-3">Branch</td>
    <td  class="col-md-6">{{$employee[0]->BranchName}}</td>
   
  </tr>
  <tr>
    <td class="fw-bold">Title</td>
    <td>{{$employee[0]->Title}}</td>
  </tr>
   <tr>
    <td class="fw-bold">First Name</td>
    <td>{{$employee[0]->FirstName}}</td>
  </tr>
  <tr>
    <td class="fw-bold">Middle Name</td>
    <td>{{$employee[0]->MiddleName}}</td>
  </tr>
  <tr>
    <td class="fw-bold">Last Name</td>
    <td>{{$employee[0]->LastName}}</td>
  </tr>
  <tr>
    <td class="fw-bold">Date of Birth</td>
    <td>{{dateformatman($employee[0]->DateOfBirth)}}</td>
  </tr>
  <tr>
     <td class="fw-bold">Is Supervisor</td>
    <td>Yes</td>
  </tr>
  <tr>
      <td class="fw-bold">Gender</td>
      <td>{{$employee[0]->Gender}}</td>
  </tr>
    <tr>
      <td class="fw-bold">Email</td>
      <td>{{$employee[0]->Email}}</td>
  </tr>
   <tr>
      <td class="fw-bold">Password</td>
      <td class="text-success">{{$employee[0]->Password}}</td>
  </tr>
   <tr>
      <td class="fw-bold">Nationality</td>
      <td>{{$employee[0]->Nationality}}</td>
  </tr>
    <tr >
      <td class="fw-bold">Mobile No</td>
      <td>{{$employee[0]->MobileNo}}</td>
  </tr>
    <tr>
      <td class="fw-bold">Home Phone</td>
      <td>{{$employee[0]->HomePhone}}</td>
  </tr>
    <tr>
      <td class="fw-bold">Full Address</td>
      <td>{{$employee[0]->FullAddress}}</td>
  </tr>
    <tr>
      <td class="fw-bold">Education Level</td>
      <td>{{$employee[0]->EducationLevel}}</td>
  </tr>
    <tr>
      <td class="fw-bold">Last Degree</td>
      <td>{{$employee[0]->LastDegree}}</td>
  </tr>
  
</table>

                                                                <div class="row">
                                                                

                                       
                                        
 
                                     

                                       
                                                                </div>
                                                                <!-- end of personal detail row -->
                                                              </div>
                                                          </div>
                                

                                    <div class="card">
                                       <div class="card-header bg-transparent border-bottom h5">
                                             Marital Detail
                                        </div>
                                      <div class="card-body">

  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-sm table-responsive">
  <tr>
    <td class="fw-bold col-md-3">Marital Status</td>
    <td  class="col-md-6">{{$employee[0]->MaritalStatus}}</td>
   
  </tr>
  <tr>
    <td class="fw-bold">SSNorGID</td>
    <td>{{$employee[0]->SSNorGID}}</td>
  </tr>
  <tr>
    <td class="fw-bold">Spouse Name</td>
    <td>{{$employee[0]->SpouseName}}</td>
  </tr>
 
 
 
  <tr>
      <td class="fw-bold">Spouse Employer</td>
      <td>{{$employee[0]->SpouseEmployer}}</td>
  </tr>
    <tr>
      <td class="fw-bold">Spouse Work Phone</td>
      <td>{{$employee[0]->SpouseWorkPhone}}</td>
  </tr>
 
  
</table>
                                    
                                         </div>
                                   </div>
                                                                                   


                                     <div class="card">
                                                                     <div class="card-header bg-transparent border-bottom h5">
                                                                           Visa / Passport Section
                                                                       </div>
                                                                       <div class="card-body">

  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-sm table-responsive">
  <tr>
    <td class="fw-bold col-md-3">Visa Issue Date</td>
    <td class="col-md-6" >{{dateformatman($employee[0]->VisaIssueDate)}}</td>
   
  </tr>
  <tr>
    <td class="fw-bold">Visa Expiry Date</td>
    <td>{{dateformatman($employee[0]->VisaExpiryDate)}}</td>
  </tr>
  <tr>
    <td class="fw-bold">Passpor tNo</td>
    <td>{{$employee[0]->PassportNo}}</td>
  </tr>
 
 
 
  <tr>
      <td class="fw-bold">Passport Expiry</td>
      <td>{{dateformatman($employee[0]->PassportExpiry)}}</td>
  </tr>
    <tr>
      <td class="fw-bold">Eid No</td>
      <td>{{$employee[0]->EidNo}}</td>
  </tr>
     <tr>
      <td class="fw-bold">Eid Expiry</td>
      <td>{{dateformatman($employee[0]->EidExpiry)}}</td>
  </tr>
 
  
</table>


                                                                      
                                                                       </div>
                                                                   </div>
                                                                     



  <div class="card">
                                  <div class="card-header bg-transparent border-bottom h5">
                                        Next of Kin 
                                    </div>
                                    <div class="card-body">

                                         <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-sm table-responsive">
  <tr>
    <td class="fw-bold col-md-3">Next of Kin Name</td>
    <td  class="col-md-6">{{$employee[0]->NextofKinName}}</td>
   
  </tr>
  <tr>
    <td class="fw-bold">Next of Kin Address</td>
    <td>{{$employee[0]->NextofKinAddress}}</td>
  </tr>
  <tr>
    <td class="fw-bold">Next of Kin Phone</td>
    <td>{{$employee[0]->NextofKinPhone}}</td>
  </tr>
 
 
 
  <tr>
      <td class="fw-bold">Next of Kin Relationship</td>
      <td>{{$employee[0]->NextofKinRelationship}}</td>
  </tr>
 
 
  
</table>




                                      
                                    </div>
                                </div>




  <div class="card">
                                  <div class="card-header bg-transparent border-bottom h5  ">
                                        Offical Details
                                    </div>
                                    <div class="card-body">

                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-sm table-responsive">
  <tr>
    <td class="fw-bold col-md-3">Job Title</td>
    <td class="col-md-6">{{$employee[0]->JobTitleName}}</td>
   
  </tr>
  <tr>
    <td class="fw-bold">Department</td>
    <td>{{$employee[0]->DepartmentName}}</td>
  </tr>
  <tr>
    <td class="fw-bold">Supervisor</td>
    <td>{{supervisor_name($employee[0]->SupervisorID)}}</td>
  </tr>
 
 
 
  <tr>
      <td class="fw-bold">Work Location</td>
      <td>{{$employee[0]->WorkLocation}}</td>
  </tr>
  <tr>
      <td class="fw-bold">Email Offical</td>
      <td>{{$employee[0]->EmailOffical}}</td>
  </tr>
   <tr>
      <td class="fw-bold">Work Phone</td>
      <td>{{$employee[0]->WorkPhone}}</td>
  </tr>
   <tr>
      <td class="fw-bold">StartDate</td>
      <td>{{dateformatman($employee[0]->StartDate)}}</td>
  </tr>  
  <tr>
      <td class="fw-bold">Salary</td>
      <td>{{$employee[0]->Salary}}</td>
  </tr>
  <tr>
      <td class="fw-bold">Comisison (If Any)</td>
      <td>{{$employee[0]->ExtraComission}}</td>
  </tr>
  <tr>
      <td class="fw-bold">Salary Remarks (If Any)</td>
      <td>{{$employee[0]->SalaryRemarks}}</td>
  </tr>
 
 
  
</table>

 
                                    </div>
                                </div>

                        
                                <!-- end card -->


                           
                                
                            </div>
                            <!-- end col -->

                           
                        </div>
                        <!-- end row -->

                       </form>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                         
                    
                        

                           
                        </div>
                        <!-- end row -->

                      

                       

                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>


  @endsection