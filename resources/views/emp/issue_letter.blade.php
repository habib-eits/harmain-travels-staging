@extends('template.tmp')

@section('title', 'Emplyee Section')
 

@section('content')



  <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Letter Issuance</h4>

                                    <div class="page-title-right"><ul class="nav nav-tabs">
 

                                         
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
  @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-3">
                    
                   {{ Session::get('error') }}  
                </div>

@endif


<form action="{{URL('/letter_issue_preview')}}" method="post"> 
 {{csrf_field()}}
 <div class="row">
                            <div class="col-lg-12">
                                <div class="card " >

                                     <div class="card-body card-body border-secondary border-top border-1 rounded-top">

                                        <h4 class="card-title ">Select Letter Template</h4>

   <table class="table table-sm m-0 table-striped"  >
            <thead>
               <tr>
                <th class="col-sm-1 text-center">#</th>
                <th class="col-sm-9">Title</th>
                
                
                
              </tr>
             </thead>
            <tbody>
 


                   <?php $no=1; ?> 
                @foreach($letter as $value)
           <tr>
     <td  class="text-center" > <input class="form-check-input  " type="radio" name="letter_id" id="formRadios1" checked="" value=" {{$value->LetterID}}"></td>
                <td scope="row"> 
            
             
                                                     {{$value->Title}}</td>
                
                  
                 
            </tr>

            @endforeach
             
              </tbody>
               </table>
                                         
               
<hr>
               
<input type="submit" name="Submit" value="Preview Letter" class="btn btn-primary btn-sm mt-3 w-md" />
                                    </div>
                                </div>
                            </div>
  </div>
</form>
   <div class="row">
                            <div class="col-lg-12">
                                <div class="card " >

                                     <div class="card-body card-body border-primary border-top border-1 rounded-top">

                                        <h4 class="card-title ">Issued Letter</h4>

<p class="card-title-desc"> </p>     
  <?php    if(count($issue_letter)!=0) {   ?>
 <table class="table table-hover align-middle table-sm table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                         
                                                        <th class="col-1" class="align-middle">#</th>
                                                         <th class="col-6">Title</th>
                                                         <th class="col-2">Date</th>
                                                          <th class="col-2">Action</th>
                                                    </tr>
                                                </thead>

 
                                                <tbody>
                                                
                                                
                                                <?php 



                                                $sno=1;
                                                foreach ($issue_letter as $key => $value) {
                                                    
                                                 ?>

                                                    <tr>
                                                         
                                                        <td>{{$sno}}</td>
                                                          <td>{{$value->Title}}</td>
                                                         <td>{{$value->eDate}}</td>
                                                          <td>
                                                            
                                                             

        <div class="d-flex gap-3">
        <a href="{{URL('/issue_letter_print/'.$value->IssueLetterID)}}" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
        <a href="{{URL('/issue_letter_print/'.$value->IssueLetterID)}}" class="text-secondary"><i class="mdi mdi-printer font-size-18"></i></a>
        <a href="#" onclick="delete_confirm('issue_letter_delete/{{$value->IssueLetterID}}')"    class="text-danger"><i class="mdi mdi-delete font-size-18"></i></a>
                                                             </div>
                                                        </td>
                                                    </tr>

                                                
                                                <?php 

                                                $sno++;
                                                } ?>



                                                </tbody>
                                            </table>

                                            <?php 
                                            }
                                            else
                                            {
                                              echo "<p class='text-danger'>No letter issued</p>";
                                            } ?>

                                                      </div>
                                </div>
                            </div>
  </div>
               
                        
                         
 

 
 

<!-- start form repeartor -->

                    
                </div>
                <!-- End Page-content -->


    




  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
      <script  >


 
    </script>



 <script>
 



jQuery(':button').click(function () {
    if (this.id == 'kashif') {
        
        $(document).ready(function(){$(".input-mask").inputmask()});

    }
    
});
 



  
 </script>


  @endsection