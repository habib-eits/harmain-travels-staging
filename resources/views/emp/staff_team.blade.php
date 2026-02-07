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

                                         <a href="{{URL('/Employee')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-arrow-left  me-1 pt-5"></i> Go Back</a>
                                         
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


                             <div class="card">
                                  <div class="card-header bg-transparent border-bottom h5  ">
                                        Supervising 
                                    </div>
                                    <div class="card-body">
    
                                      

    @if(!$team->isEmpty())
    <table class="table table-sm align-middle table-nowrap mb-0">
    <tbody><tr class="table-light">
    <th scope="col">S.No</th>
    <th scope="col">Employee ID</th>
    <th scope="col">First Name</th>
    <th scope="col">Middle Name</th>
    <th scope="col">Last Name</th>
    <th scope="col">Designation</th>
    <th scope="col">Department</th>
    </tr>
    </tbody>
    <tbody>
    @foreach ($team as $key =>$value)
     <tr>
     <td class="col-md-1">{{$key+1}}</td>
     <td class="col-md-1">{{$value->EmployeeID}}</td>
     <td class="col-md-1">{{$value->FirstName}}</td>
     <td class="col-md-1">{{$value->MiddleName}}</td>
     <td class="col-md-1">{{$value->LastName}}</td>
     <td class="col-md-1">{{$value->JobTitleName}}</td>
     <td class="col-md-1">{{$value->DepartmentName}}</td>
     </tr>
     @endforeach   
     </tbody>
     </table> 
  @else
  <p class="text-danger">Sorry you are not supervisor</p> 
  @endif

 
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                         
                    

                           
                        </div>
                        <!-- end row -->

                      

                       

                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>


  @endsection