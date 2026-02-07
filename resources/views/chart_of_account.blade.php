@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')

   <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid"><div class="row">

                      <div class="row"><div class="row">
  <div class="col-12">
  
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

            
  <!-- enctype="multipart/form-data" -->
 <form action="{{URL('/ChartOfAccSave')}}" method="post"> 
 {{csrf_field()}} 
 <div class="card shadow-sm">
    
  <div class="card-header">
        <h2>Chart of Account</h2>
  </div>
    
      <div class="card-body">
         <div class="col-md-6 col-sm-12">
                <div class="mb-3 row">
                  <div class="col-sm-3">
                    <label class="col-form-label fw-bold" for="first-name">Code</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text" id="first-name " class="form-control" name="ItemCode" placeholder="Item Code">
                  </div>
                </div>

                <div class="mb-3 row">
                  <div class="col-sm-3">
                    <label class="col-form-label fw-bold" for="first-name">Chart of Acc</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text" id="first-name" class="form-control" name="ItemName" placeholder="Item Name">
                  </div>
                </div>

                <div class="mb-3 row">
                  <div class="col-sm-3">
                    <label class="col-form-label fw-bold" for="first-name">Parent Head</label>
                  </div>
                  <div class="col-sm-9">
                    <select name="Taxable" id="Taxable" class="form-select">
                        <option value="">Select</option>
                        <?php foreach ($chart as $key => $value): ?>
                          <option value="{{$value->ChartOfAccountID}}">{{$value->ChartOfAccountName}}</option>
                        <?php endforeach ?>
                  
                      </select>
                  </div>
                </div>

            
             

              
                


              </div>
      </div>
      <div class="card-footer bg-light">
        
        <div><button type="submit" class="btn btn-success w-sm float-right">Save</button>
             <a href="{{URL('/Dashboard')}}" class="btn btn-secondary w-sm float-right">Cancel</a>
        
        
      </div>
  </div>
  
  </div>
  </form>
  


 <div class="card shadow-sm">
    <div class="card-header">
      <h4>Chart of Accounts</h4>
    </div>
      <div class="card-body">
         @if(count($chartofaccount)>0)    
       <div class="table-responsive">
          <table class="table table-sm align-middle table-nowrap mb-0" id="student_table">
        <tbody><tr>
        <th class="col-md-1">S.No</th>
        <th class="col-md-1">Code</th>
        <th class="col-md-3">Chart of Account</th>
         <th class="col-md-1">L1</th>
        <th class="col-md-1">L2</th>
        <th class="col-md-1">L3</th>
        <th class="col-md-1">Action</th>
        </tr>
        </tbody>
        <tbody>
        @foreach ($chartofaccount as $key =>$value)
         <tr>
         <td class="col-md-1">{{$key+1}}</td>
         <td class="col-md-1">{{$value->ChartOfAccountID}}</td>
         <td class="col-md-4">{{$value->ChartOfAccountName}}</td>
          <td class="col-md-1">{{$value->L1}}</td>
         <td class="col-md-1">{{$value->L2}}</td>
         <td class="col-md-1">{{$value->L3}}</td>
         <td class="col-md-1"><a href="{{URL('/')}}"><i class="bx bx-pencil align-middle me-1"></i></a> <a href="#" onclick="delete_confirm2('FCBDelete',6)"><i class="bx bx-trash  align-middle me-1"></i></a></td>
         </tr>
         @endforeach   
         </tbody>
         </table>
       </div>
         @else
           <p class=" text-danger">No data found</p>
         @endif   
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