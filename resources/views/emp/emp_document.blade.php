@extends('template.tmp')

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
                            <div class="col-xl-9">
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

           @include('emp.emp_info')


                            <div class="card">
                                            <div class="card-body">
                                                <div>
                                                    <div class="row mb-3">
                                                        <div class="col-xl-3 col-sm-6">
                                                            <div class="mt-2">
                                                                <h5>Uploaded Documents</h5>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>

                                                <div>
                                                    
                                                    <!-- end row -->
                                                </div>
        
                                               
                                                    
                                                 @if(count($documents)>0)     
        
                                                    <div >
                                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                                            <thead>
                                                                <tr>
                                                                  <th scope="col">Name</th>
                                                                  <th scope="col">Date modified</th>
                                                                  <th scope="col" colspan="2">Size</th>
                                                                </tr>
                                                              </thead>
                                                            <tbody>
                                                               
<?php foreach ($documents as $key => $value): ?>
  


                                                                <tr>
                                                                    <td><a href="{{URL('/documents/'.$value->File)}}" class="text-dark fw-medium"><i class="mdi mdi-file-document font-size-16 align-middle text-primary me-2"></i> {{$value->FileName}}</a></td>
                                                                    <td>{{$value->eDate}}</td>
                                                                    <td>{{$value->FileSize}}</td>
                                                                    <td>
                                                                        <div class="dropdown">
                                                                            <a class="font-size-16 text-muted dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                                            </a>
                                                                            
                                                                            <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">
                                                                                <a class="dropdown-item" href="{{URL('/documents/'.$value->File)}}" target="_blank">Open</a>
                                                                               <!--  <a class="dropdown-item" href="#">Edit</a>
                                                                                <a class="dropdown-item" href="#">Rename</a> -->
                                                                                <div class="dropdown-divider"></div>
                                                                                <a class="dropdown-item" href="{{URL('/EmployeeDocumentDelete/'.$value->DocumentID)}}">Remove</a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                               
<?php endforeach ?>

                                                         
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                 @endif
 

 @if(count($documents)==0)     
<p class="text-danger">No document uploaded</p>
 @endif
                                            </div>
                                        </div>
                                <!-- end card -->


                                  <div class="card">
                                                                  <div class="card-header bg-transparent   mt-2">
                                                                        <h5>Upload Document</h5> 
                                                                    </div>
                                                                    <div class="card-body">
                                                                      
                                                                   <form action="{{URL('/EmployeeDocumentUpload')}}" method="post" enctype="multipart/form-data"> {{csrf_field()}}

                                                                     <div class="row">
                                                                          <div class="col-md-4">
                                                                      <div class="mb-3">
                                                                      <label for="basicpill-firstname-input">File Name*</label>
                                                                      <input type="text" class="form-control  form-control-sm" name="FileName" value="{{old('FileName')}}" required>
                                                                      </div>
                                                                      </div>
                                                                      
                                                                      <div class="col-md-4"><div class="mb-3"><label for="basicpill-firstname-input">Select File<br></label><br><input type="file" id="UploadFile" name="UploadFile" required="">
                                                                        </div></div>
                                                                    
                                                                    <div class="clearfix"></div>
                                                                     <div class="col-md-4">
                                                                     <button type="submit" class="btn  btn-rounded btn-success w-md float-right">Upload</button>

                                                                           
                                                                     </div>


                                                                     </div>
                                                                     

                                                                      </form>
                                                                     
                                                                      
                                                                    </div>
                                                                </div>
                                
                            </div>
                            <!-- end col -->
                         
                         <!-- employee detail side bar -->
                         @include('template.emp_sidebar')

                           
                        </div>
                        <!-- end row -->

                      

                       

                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>


  @endsection