@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')



<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Salesman</h4>
                                         
 
                                </div>
                            </div>
                        </div>
 @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

 @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger p-1   border-3">
                   <p class="font-weight-bold"> There were some problems with your input.</p>
                    <ul>
                        
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>

                        @endforeach
                    </ul>
                </div>
                </div>
 
            @endif

            
            
   



<div class="card">
    <div class="card-header bg-transparent border-bottom">
          
      </div>
      <div class="card-body">
        
        <!-- enctype="multipart/form-data" -->
        <form action="{{URL('/SalesmanUpdate')}}" method="post"> {{csrf_field()}} 


<input type="hidden" name="SalemanID" value="{{$salesman[0]->SalemanID}}">


            <div class="col-md-4">
          <div class="mb-3">
          <label for="basicpill-firstname-input">Name*</label>
          <input type="text" class="form-control" name="SalemanName" value="{{$salesman[0]->SalemanName}}" required="">
          </div>
          </div>
          
          
           <div class="col-md-4">
          <div class="mb-3">
          <label for="basicpill-firstname-input">Mobile*</label>
          <input type="text" class="form-control" name="Mobile" value="{{$salesman[0]->Mobile}} ">
          </div>
          </div> 



           <div class="col-md-4">
          <div class="mb-3">
          <label for="basicpill-firstname-input">Address*</label>
          <input type="text" class="form-control" name="Address" value="{{$salesman[0]->Address}} ">
          </div>
          </div>
          


<div><button type="submit" class="btn btn-success w-lg float-right">Save</button>
     <a href="{{URL('/Salesman')}}" class="btn btn-secondary w-lg float-right">Cancel</a>
</div>



        </form>



      </div>
  </div>

 





   
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
  @endsection