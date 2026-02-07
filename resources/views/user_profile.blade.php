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


<div class="row">
 <div class="col-12">
    <form action="{{URL('/user_update')}}" method="post">
        {{csrf_field()}}
<div class="card">
<div class="card-body">

<h4 class="card-title">User Profile</h4>
<p class="card-title-desc"></p>

  



<div class="mb-3 row">
<label for="example-email-input" class="col-md-2 col-form-label">Full Name</label>
<div class="col-md-10">
{{$v_users[0]->FullName}}
</div>
</div>
<div class="mb-3 row">
<label for="example-url-input" class="col-md-2 col-form-label">Username</label>
 
<div class="col-md-10">
{{$v_users[0]->Email}}
</div></div>
 
<div class="mb-3 row">
<label for="example-tel-input" class="col-md-2 col-form-label">User Type</label>
<div class="col-md-10">

 {{$v_users[0]->UserType}}

</div>
 </div>
 <div class="mb-3 row">
<label for="example-tel-input" class="col-md-2 col-form-label">Active</label>
<div class="col-md-10">
{{($v_users[0]->Active=='Y') ? 'Yes' : 'No'}} </div>
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