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

                                         <a href="{{URL('/StaffSalary')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-arrow-left  me-1 pt-5"></i> Go Back</a>
                                         
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
                                        Comission Detail on FCB Deposite
                                    </div>
                                    <div class="card-body">

                                         <table class="table table-sm align-middle table-nowrap mb-0">
       <tbody><tr>
        <th scope="col">S.No</th>
       <th scope="col">ID</th>
       <th scope="col">Amount</th>
       <th scope="col">Date</th>
       <th scope="col">Compliant</th>
       <th scope="col">KYC Sent</th>
       <th scope="col">Month</th>
                                         
</tr>
 </tbody>
 <tbody>
   <?php  $sum=0;  ?>
@foreach ($fcb as $key =>$value)
   <?php  $sum = $sum + $value->FTDAmount;  ?>                                    
<tr>
<td class="col-md-1">{{$key+1}}</td>
<td class="col-md-1">{{$value->ID}}</td>
<td class="col-md-1">{{$value->FTDAmount}}</td>
<td class="col-md-1">{{$value->Date}}</td>
<td class="col-md-1">{{$value->Compliant}}</td>
<td class="col-md-1">{{$value->KYCSent}}</td>
<td class="col-md-1">{{$value->MonthName}}</td>
 </tr>
 @endforeach   





 <tr>
     <td class="col-md-1"></td>
     <td class="col-md-1 "><h6 class="mt-1 text-danger">Total Deposite</h6></td>
     <td class="col-md-1">{{number_format($sum,2)}}</td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
 </tr>


 <tr>
     <td class="col-md-1"></td>
     <td class="col-md-1 text-danger"><h6 class="text-danger">Comission</h6></td>
     <td class="col-md-1"><?php $result = agent(count($fcb),$sum,$employee[0]->Salary);


echo number_format($result['comission1']+$result['comission2']+$result['comission3'],2);
      ?></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
 </tr> 

 <tr>
     <td class="col-md-1"></td>
     <td class="col-md-1 text-danger "><h6 class="text-danger">Basic Salary</h6></td>
     <td class="col-md-1"><?php  
echo number_format($employee[0]->Salary,2);
      ?></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
 </tr>
 <tr>
     <td class="col-md-1"></td>
     <td class="col-md-1 "><h6 class="text-danger">Grand Salary</h6></td>
     <td class="col-md-1"><?php  
echo number_format($result['grand'],2);
      ?></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
     <td class="col-md-1"></td>
 </tr>
  </tbody>
   </table> 

 

                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                         
                         <!-- employee detail side bar -->
 
                           
                        </div>
                        <!-- end row -->

                      

                       

                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>


  @endsection