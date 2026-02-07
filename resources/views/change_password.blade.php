@extends('template.tmp')

@section('title', 'Users')
 

@section('content')

 <div class="main-content">

 <div class="page-content">
<div class="container-fluid">

 <!-- start page title -->
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
 <h4 class="mb-sm-0 font-size-18">Manage Users</h4>

 <div class="page-title-right">
<div class="page-title-right">

</div>
</div>
</div>
</div>
<div>
 <!-- end page title -->

 @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-3">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

 @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger pt-3 pl-0   border-3">
                   <p class="font-weight-bold"> There were some problems with your input.</p>
                    <ul>
                        
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>

                        @endforeach
                    </ul>
                </div>
                </div>
 
            @endif
<div class="row">
 <div class="col-12">
    <form action="{{URL('/UpdatePassword')}}" method="post">
        {{csrf_field()}}
<div class="card">
<div class="card-body">

<h4 class="card-title">Update User</h4>
<p class="card-title-desc"></p>

 


<div class="mb-3 row">
<label for="example-email-input" class="col-md-2 col-form-label">Old Password</label>
<div class="col-md-10">
<input class="form-control" type="password"   name="old_password" id="example-email-input" value="{{old('old_password')}}">
</div>
</div>
<div class="mb-3 row">
<label for="example-url-input" class="col-md-2 col-form-label">New Password</label>
<div class="col-md-10">
<input class="form-control" type="password"  name="new_password"  value="{{old('new_password')}}"  >
</div>

</div>
<div class="mb-3 row">
<label for="example-url-input" class="col-md-2 col-form-label">New Confirm Password</label>
<div class="col-md-10">
<input class="form-control" type="password"  name="new_confirm_passowrd" value="{{old('new_confirm_passowrd')}}"  >
</div>

</div>

<div class="mb-3 row">
<label for="example-url-input" class="col-md-2 col-form-label">  </label>
<div class="col-md-10">
   <input type="submit" class="btn btn-primary w-md" value="Change Password"  >  

    <a href="{{URL('/Dashboard')}}" class="btn btn-secondary w-md">Cancel</a>  
</div>

</div>
 
 
                                      
                               
                                   
    
                                      
                                        

                                       

                                    </div>
                                </div>

                            </form>
                            </div> <!-- end col -->
                        </div>
                      

  
                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>


    
</div>
  @endsection