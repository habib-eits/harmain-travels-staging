@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')
  <!-- BEGIN: Content-->

    <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid"><div class="row">

                      <div class="row">


 @if (session('error'))

<div class="alert alert-{{ Session::get('class') }} p-3"  id="success-alert">
                    
                  <strong>{{ Session::get('error') }} </strong>
                </div>

@endif

  @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger p-1   border-1 bg-danger text-white">
                   <p class="font-weight-bold"> There were some problems with your input.</p>
                    <ul>
                        
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>

                        @endforeach
                    </ul>
                </div>
                </div>

            @endif


  <div class="col-12">
  
  <!-- enctype="multipart/form-data" -->
  <form action="{{URL('/ItemUpdate')}}" method="post"> 
<input type="hidden" name="ItemID" value="{{$item[0]->ItemID}}">

  {{csrf_field()}} 

<div class="card shadow-sm">
    <div class="card-header">
      <h2>Item</h2>
    </div>
      <div class="card-body">
         <div class="col-md-6">
                <div class="mb-1 row">
                  <div class="col-sm-2">
                  <label class="col-form-label fw-bold" for="BranchID">Branch</label>
                  </div>
                  <div class="col-sm-9">
                  <select name="BranchID" id="BranchID" class="form-select">
                    <option value="">Select Branch</option>
                    @foreach($branches as $branch)
                      <option value="{{ $branch->id }}" {{ ($branch->id == $item[0]->BranchID) ? 'selected' : '' }}>
                      {{ $branch->name }}
                      </option>
                    @endforeach
                  </select>
                  </div>
                </div>

                <div class="mb-1 row">
                  <div class="col-sm-2">
                    <label class="col-form-label fw-bold" for="first-name">Item Name</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text" id="first-name" class="form-control" name="ItemName" placeholder="Item Name" value="{{$item[0]->ItemName}}">
                  </div>
                </div>

                <div class="mb-1 row">
                  <div class="col-sm-2">
                    <label class="col-form-label fw-bold" for="first-name">Taxable</label>
                  </div>
                 
                  <?php 
$Taxable = old('Taxable') ? old('Taxable') : $item[0]->Taxable ;
 ?>
                  <div class="col-sm-9">
                    <select name="Taxable" id="Taxable" class="form-select">
                        <option value="">Select</option>
                        <option value="No" {{($Taxable== 'No') ? 'selected=selected':'' }}>No</option>
                        <option value="Yes" {{($Taxable== 'Yes') ? 'selected=selected':'' }}>Yes</option>
                  
                      </select>
                  </div>
                </div>

                <div class="mb-1 row">
                  <div class="col-sm-2">
                    <label class="col-form-label fw-bold" for="first-name">Percentage</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text" id="Percentage"   class="form-control" name="Percentage" value="{{$item[0]->Percentage}}">
                  </div>
                </div>

             

              
                


              </div>
      </div>
      <div class="card-footer bg-light">
        
        <div><button type="submit" class="btn btn-success w-lg float-right">Update</button>
             <a href="{{URL('/Item')}}" class="btn btn-secondary w-lg float-right">Cancel</a>
        
        
      </div>
  </div>
  
  </div>

  </form>

<!-- card end here -->



 

  

</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->


         <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>


<script>
  $(document).on('change ','#Taxable',function(){
  if($('#Taxable').val()=='Yes')
  {
     $("#Percentage").prop("disabled", false);
     $("#Percentage").focus();
     $("#Percentage").attr("placeholder", "5").placeholder();

  }
  else
  {
    $("#Percentage").prop("disabled", true);
    $("#Percentage").removeAttr("placeholder");
  }

  

});


 

 

</script>

 




</script>
<script type="text/javascript">
$(document).ready(function() {
     $('#student_table').DataTable( );
});
</script>
 
  @endsection


