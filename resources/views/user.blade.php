@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')
<div class="main-content">

<div class="page-content">
<div class="container-fluid">
 <h4 class="card-title">Add New User</h4>
<p class="card-title-desc"></p>
  
  @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-2" id="success-alert">
                    
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
      <div class="card-body">
          <form action="{{URL('/UserSave')}}" method="post">
        {{csrf_field()}}
<div class="">
<div class="">



 



<div class="mb-1 row">
<label for="example-email-input" class="col-md-2 col-form-label fw-bold ">Full Name</label>
<div class="col-md-4">
<input class="form-control" type="text"  value="{{old('FullName')}}"  name="FullName" id="example-email-input">
</div>
</div>
<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Username</label>
<div class="col-md-4">
<input class="form-control" type="text"  value="{{old('Email')}}" name="Email" required>
</div>

</div>
<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Password</label>
<div class="col-md-4">
<input class="form-control" type="text"  name="Password" value="{{old('Password')}}" required>
</div>

</div>

 <div class="mb-1 row">
<label for="example-tel-input" class="col-md-2 col-form-label fw-bold">Branch</label>
<div class="col-md-4">
<select name="branch_id" class="form-select">

     

     
   
    @foreach($branch as $value)
     <option value="{{$value->id}}" >{{$value->name}}</option>
    @endforeach
   
    


</select> </div>
 </div>




<div class="mb-1 row">
<label for="example-tel-input" class="col-md-2 col-form-label fw-bold">User Type</label>
<div class="col-md-4">
<select name="UserType" class="form-select">

     
    @if (session('UserType') == 'SuperAdmin')
        <option value="SuperAdmin">SuperAdmin</option>
    @endif
    <option value="Admin">Admin</option>
    <option value="User">User</option>
    <option value="Agent">Agent</option>
    <option value="Saleman">Saleman</option>
    
    
    


</select> </div>
 </div>
 



 <div class="mb-1 row">
<label for="example-tel-input" class="col-md-2 col-form-label fw-bold">Active</label>
<div class="col-md-4">
<select name="Active" class="form-select">

     
    <option value="Yes">Yes</option>
    <option value="No">No</option>
    


</select> </div>
 </div>



 
                                      
    <input type="submit" class="btn btn-primary w-md">                                   
                                   
    
                                      
                                        

                                       

                                    </div>
                                </div>

                            </form>
      </div>
  </div>

  <div class="row">
      <div class="col-lg-12">
          
          <div class="card">
              
          <div class="card-body">
<!--             <h4 class="card-title text-center">Manage Users</h4>
 -->             <!-- <p class="card-title-desc"> Add <code>.table-sm</code> to make tables more compact by cutting cell padding in half.</p>  -->   
                                        
       <div class="table-responsive">
        <table class="table table-sm" id="student_table">
            <thead>
               <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Branch</th>
                <th>User Type</th>
                <th>Created on</th>
                 
                <th width="5">Active</th>
                <th>Action</th>
              </tr>
             </thead>
            <tbody>
 


                   <?php $no=1; ?> 
                @foreach($user as $value)
           <tr>
     <td  >{{$no++}}</td>
                <td scope="row">{{$value->FullName}}   </td>
                <td>{{$value->Email}}</td>
                <td>{{$value->BranchName}}</td>
                <td>{{$value->UserType}}</td>
                <td>{{$value->eDate}}</td>
                 
                <td class="text-center"><span class="top-0 start-100 translate-middle badge border border-light rounded-circle  {{($value->Active=='Yes') ? 'bg-success' : 'bg-danger'}}  p-1"><span class="visually-hidden">unread messages</span></span></td>
                <td><div class="d-flex gap-1">
        <a href="{{URL('/UserEdit/'.$value->UserID)}}" class="text-secondary"><i class="mdi mdi-pencil font-size-15"></i></a>
        <a href="#"  onclick="delete_confirm2('UserDelete',{{$value->UserID}});" class="text-secondary"><i class="mdi mdi-delete font-size-15"></i></a>
        <a href="{{URL('/checkUserRole/'.$value->UserID)}}"  class="text-secondary"><i class="fas fa-user-lock
 font-size-12"></i></a>
                                                             </div> </td>
                 
            </tr>

            @endforeach
             
              </tbody>
               </table>
        
                  </div>
        
                   </div>
          </div>
      </div>



  </div>  
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
<script type="text/javascript">
$(document).ready(function() {
     $('#student_table').DataTable( );
});
</script>

</div>
</div>
</div>

    
  @endsection