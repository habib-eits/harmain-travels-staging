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
                                  <div class="card-header bg-transparent border-bottom h5  ">
                                        FCB Deposite
                                    </div>
                                    <div class="card-body">
                                        @if(count($fcb)!=0)
                                       <div class="table-responsive">
                                             <table class="table table-sm  align-middle table-nowrap mb-0">
                                                <tbody><tr class="table-light">
                                                    <th scope="col" >S.No</th>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Agent</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Compliant</th>
                                                    <th scope="col">KYC Sent</th>
                                                    <th scope="col">Dialer</th>
                                                    
                                                    <th scope="col">Action</th>
                                                  </tr>
                                                </tbody><tbody>
                                               <?php $i=1; ?>
                                               @foreach($fcb as $value)
                                                    <tr>
                                                        <td class="col-md-1">{{$i}}.</td>
                                                         
                                                        <td class="col-md-1">{{$value->FCBID}}</td>
                                                        <td class="col-md-2">{{$value->FirstName}} {{$value->MiddleName}} {{$value->LastName}}</td>
                                                        <td class="col-md-1">{{number_format($value->FTDAmount,2)}}</td>
                                                        <td class="col-md-1"> 

                                                            {{$a=dateformatmonth($value->Date)}}</td>
                                                        <td class="col-md-2">{{$value->Compliant}}</td>
                                                        <td class="col-md-2">{{$value->KYCSent}}</td>
                                                        <td class="col-md-1">{{$value->Dialer}}</td>
                                                            
                                                             
                                                         
                                                        <td class="col-md-1"><a href="{{URL('/FCBEdit/'.$value->FCBID)}}"><i class="bx bx-pencil align-middle me-1"></i></a> <a href="#" onclick="delete_confirm2('FCBDelete',{{$value->FCBID}})"><i class="bx bx-trash  align-middle me-1"></i></a></td>
                                                        <td>
                                                            
                                                        </td>
                                                    </tr>
                                                   <?php $i++; ?>
                                                    @endforeach

                                                     

                                                   
                                                </tbody>
                                            </table>
                                        </div>
                                        @else

                                        <p class="text-danger text-center">No Record Found</p>
                                        @endif

 
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                         
                         <!-- employee detail side bar -->
                         @include('template.emp_sidebar')

                           
                        </div>
                        <!-- end row -->

                      

                       

                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>


  @endsection