@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')

  <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
  
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
          <form action="{{URL('/SupplierUpdate')}}" method="post">
        {{csrf_field()}}

        <input type="hidden" name="SupplierID" value="{{$supplier[0]->SupplierID}}">
<div>
<div >

<h4 class="card-title">Add Supplier</h4>
<p class="card-title-desc"></p>

 

<?php 
$SupplierCatID = old('SupplierCatID') ? old('SupplierCatID') : $supplier[0]->SupplierCatID ;
$SupplierName = old('SupplierName') ? old('SupplierName') : $supplier[0]->SupplierName ;
$Address = old('Address') ? old('Address') : $supplier[0]->Address ;
$Phone = old('Phone') ? old('Phone') : $supplier[0]->Phone ;
$Email = old('Email') ? old('Email') : $supplier[0]->Email ;
$InvoiceDueDays = old('InvoiceDueDays') ? old('InvoiceDueDays') : $supplier[0]->InvoiceDueDays ;
$Active = old('Active') ? old('Active') : $supplier[0]->Active ;

 ?>




<div class="mb-1 row">
<label for="example-email-input" class="col-md-2 col-form-label fw-bold ">Supplier Category</label>
<div class="col-md-4">
<select name="SupplierCatID" id="SupplierCatID" class="form-select" >
  <option value="">Select</option>
  <?php foreach ($supplier_category as $key => $value): ?>
    <option value="{{$value->SupplierCatID}}" {{($value->SupplierCatID== $SupplierCatID) ? 'selected=selected':'' }}>{{$value->SupplierCode}}-{{$value->SupplierCategory}}</option>
  <?php endforeach ?>
</select>
</div>
</div>
<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Supplier Name</label>
<div class="col-md-4">
<input class="form-control" type="text"  value="{{$SupplierName}}" name="SupplierName" >
</div>

</div>
<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Address</label>
<div class="col-md-4">
<input class="form-control" type="text"  name="Address" value="{{$Address}}"  >
</div>

</div>

<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Phone</label>
<div class="col-md-4">
<input class="form-control" type="text"  name="Phone" value="{{$Phone}}" >
</div>

</div>

<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Email</label>
<div class="col-md-4">
<input class="form-control" type="text"  name="Email" value="{{$Email}}"  >
</div>

</div>

<div class="mb-1 row">
<label for="example-url-input" class="col-md-2 col-form-label fw-bold">Invoice Due Days</label>
<div class="col-md-4">
<input class="form-control" type="number"  name="InvoiceDueDays" value="{{$InvoiceDueDays}}" >
</div>

</div>
 
  <div class="mb-1 row">
<label for="example-tel-input" class="col-md-2 col-form-label fw-bold">Active</label>
<div class="col-md-4">
<select name="Active" class="form-select" >

     
    <option value="Yes" {{($Active== 'Yes') ? 'selected=selected':'' }}>Yes</option>
    <option value="No" {{($Active== 'No') ? 'selected=selected':'' }}>No</option>
    
    


</select> </div>
 </div>
 


 
 
                                      
                                    
                                   
    
                                      
                                        

                                       

                                    </div>
                                 
                                </div>

                         





      </div>
         <div class="card-footer bg-secondary bg-soft">
                                       <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Update</button>


                                       
                                       
                <a href="{{URL('/Supplier')}}" class="btn btn-outline-secondary waves-effect">Cancel</a>
                                    </div>
  </div>
                                <!-- card end here -->
  </form>

  </div>
  </div>
  </div>
  @endsection