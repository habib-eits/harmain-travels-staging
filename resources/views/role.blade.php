@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')

 <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">User Rights & Control</h4>

                                    <div class="page-title-right">
                                        <div class="page-title-right">
                                         <!-- button will appear here -->
                                    </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
 
                       <!-- enctype="multipart/form-data" -->
                       <form action="{{URL('/RoleSave')}}" method="post"> {{csrf_field()}} 

 <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
   
  <div class="row">
    
   
  
   
   
  
  

         <div class="col-md-4">
   <div class="mb-3">
      <label for="basicpill-firstname-input">User*</label>
       <select name="UserID" id="UserID" class="form-select">
 
       @foreach($users as $value)
        <option value="{{$value->UserID}}" {{(old('UserID')== $value->UserID) ? 'selected=selected': '' }}>{{$value->FullName}}</option>
       @endforeach
      
    
    </select>
    </div>
     </div>

  </div>
  
     <input type="checkbox" id="checkAll" name="checkAll" >
     <label>Check All</label>
    <hr>
                                     
                                        @foreach ($role as $key =>$value1)
                                          
                                         <h4  class="bg bg-light p-1"> {{$value1->Table}}</h4>

<div class="row">
      


<?php 

$permission = DB::table('role')->where('Table',$value1->Table)->get();


foreach ($permission as $key =>$value)




    

    { ?>

      <div class="col-sm-2 mt-2 mb-3">
                                           
                                            
                                             <div class="custom-control custom-checkbox">
  <input name="c{{$value->RoleID}}" type="checkbox" class="custom-control-input" id="{{$value->RoleID}}"  value="Y" checked=""  >
  <label class="custom-control-label" for="customCheck33079">{{$value->Action}} </label>
  <label>
  <input name="TableName[]" type="hidden" id="T{{$value->RoleID}}" value="{{$value1->Table}}">
   <input name="Action[]" type="hidden" id="A{{$value->RoleID}}" value="{{$value->Action}}">
   <input name="Allow[]" type="hidden" id="{{$value->RoleID}}Allow" value="Y" class="role">
  </label>
</div>
                                          
  </div>




<?php } ?>

</div>
                                    
                                         @endforeach   
                                         
                                        
<div><button type="submit" class="btn btn-success w-lg float-right">Save / Update</button>
     <a href="{{URL('/')}}" class="btn btn-secondary w-lg float-right">Cancel</a>
</div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                           
                        </div>




                       </form>
                        <!-- end row -->

                      

                       

                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>


$(document).ready(function(){
   

$('input[type="checkbox"]').change(function() {
    var vals = $(this).val();
    var id = $(this).attr('id');
   
    if($(this).is(':checked')){
 
         $('#'+id+'Allow').val('Y');

        

        
    }else{
        
         $('#'+id+'Allow').val('N');
        
        // $(this).next().next("input[type='text']").val("");
    }

});




});

$('#checkAll').click(function () {    
     $('input:checkbox').prop('checked', this.checked);    


if($('input[name="checkAll"]').is(':checked'))
{
  $(".role").val('Y');
}else
{
 // unchecked
 $(".role").val('N');
}

 });


</script>

 



  @endsection