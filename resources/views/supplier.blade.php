@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')

 <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

<div class="row">
  <div class="col-12">
  
  @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

 @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger p-2 border-1">
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
          <form action="{{URL('/SaveSupplier')}}" method="post">
        {{csrf_field()}}
<div>
<div >

<h4 class="card-title">Add Supplier</h4>
<p class="card-title-desc"></p>

 



<div class="mb-1 row">
<label for="example-email-input" class="col-md-2 col-form-label fw-bold ">Supplier Category</label>
<div class="col-md-4">
<select name="SupplierCatID" id="SupplierCatID" class="form-select" >
  <option value="">Select</option>
  <?php foreach ($supplier_category as $key => $value): ?>
    <option value="{{$value->SupplierCatID}}" {{($value->SupplierCatID== old('SupplierCatID')) ? 'selected=selected':'' }}>{{$value->SupplierCode}}-{{$value->SupplierCategory}}</option>
  <?php endforeach ?>
</select>
</div>
</div>
<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Supplier Name</label>
<div class="col-md-4">
<input class="form-control" type="text"  value="{{old('SupplierName')}}" name="SupplierName" >
</div>

</div>
<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Address</label>
<div class="col-md-4">
<input class="form-control" type="text"  name="Address" value="{{old('Address')}}"  >
</div>

</div>

<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Phone</label>
<div class="col-md-4">
<input class="form-control" type="text"  name="Phone" value="{{old('Phone')}}" >
</div>

</div>

<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Email</label>
<div class="col-md-4">
<input class="form-control" type="text"  name="Email" value="{{old('Email')}}"  >
</div>

</div>

<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Invoice Due Days</label>
<div class="col-md-4">
<input class="form-control" type="number"  name="InvoiceDueDays" value="{{old('InvoiceDueDays')}}" >
</div>

</div>
 
  <div class="mb-1 row">
<label for="example-tel-input" class="col-md-2 col-form-label fw-bold">Active</label>
<div class="col-md-4">
<select name="Active" class="form-select" >

     
    <option value="Yes" {{(old('Active')== 'Yes') ? 'selected=selected':'' }}>Yes</option>
    <option value="No" {{(old('Active')== 'No') ? 'selected=selected':'' }}>No</option>
    
    


</select> </div>
 </div>
 


 
 
                                      
                                    
                                   
    
                                      
                                        

                                       

                                    </div>
                                 
                                </div>

                         





      </div>
         <div class="card-footer bg-light bg-soft">
                                       <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Submit</button>


                                       
                                       
                <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                                    </div>
  </div>
                                <!-- card end here -->
  </form>

 <div class="row">
      <div class="col-lg-12">
          
          <div class="card">
              
          <div class="card-body">
            <h4 class="card-title ">Suppliers</h4>
             <!-- <p class="card-title-desc"> Add <code>.table-sm</code> to make tables more compact by cutting cell padding in half.</p>  -->   
                                        
       <div class="table-responsive">
        <table class="table  m-0" id="student_table">
            <thead>
               <tr>
                 <th>Party Code</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                 
                <th>Invoice Due Days</th>
                <th>Action</th>
              </tr>
             </thead>
            <tbody>
 


                  
                @foreach($supplier as $value)
           <tr>
    
                <td scope="row">{{$value->SupplierID}}</td>
                <td>{{$value->SupplierName}}</td>
                <td>{{$value->Address}}</td>
                
                <td>{{$value->Phone}}</td>
                <td>{{$value->Email}}</td>
                 
                <td>{{$value->Active}}</td>
                <td><div class="d-flex gap-1">
        <a href="{{URL('/SupplierEdit/'.$value->SupplierID)}}" class="text-secondary"><i class="mdi mdi-pencil font-size-15"></i></a>
        <a href="#"  class="text-secondary" onclick="delete_confirm2('SupplierDelete',{{$value->SupplierID}})"><i class="mdi mdi-delete font-size-15"></i></a>
        <!-- <a href="{{URL('/checkUserRole/'.$value->SupplierID)}}"  class="text-secondary"><i class="fas fa-user-lock
 font-size-12"></i></a> -->
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

 
    
  @endsection