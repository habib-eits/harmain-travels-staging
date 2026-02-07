@extends('template.tmp')

@section('title', 'Manage Letters')
 

@section('content')

 <div class="main-content">

 <div class="page-content">
<div class="container-fluid">

 <!-- start page title -->
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
 <h4 class="mb-sm-0 font-size-18">Issue Letter</h4>

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
 

    <form action="{{URL('/letter_issue_save')}}" method="post">
        {{csrf_field()}}
<div class="card">
<div class="card-body">
 <input class="form-control" type="hidden"  name="EmployeeID" id="example-email-input" required value="{{session::get('EmployeeID')}}">

<input class="form-control" type="hidden"  name="LetterID" id="example-email-input" required value="{{$letter[0]->LetterID}}">

<h4 class="card-title">Letter Templates</h4>
<p class="card-title-desc"> </p>

 


 
<div class="mb-3 row">
<label for="example-email-input" class="col-md-2 col-form-label">Title</label>
<div class="col-md-10">
<input class="form-control" type="text"  name="Title" id="example-email-input" required value="{{$letter[0]->Title}}">
</div>
</div>

 
<div class="mb-3 row">
<label for="example-email-input" class="col-md-2 col-form-label">Content</label>
<div class="col-md-10">
 <textarea id="basic-example" name="Content">
 

  <?php   
 $letter=str_replace("^FullName^",$employee[0]->FirstName .' '. $employee[0]->MiddleName .' '. $employee[0]->LastName,$letter[0]->Content);

 $letter=str_replace("^Passport^",$employee[0]->PassportNo,$letter);
// $letter=str_replace("^DATE^",date('d-m-Y'),$letter);
 $letter=str_replace("^FirstName^",$employee[0]->FirstName,$letter);
 $letter=str_replace("^Designation^",$employee[0]->JobTitleName,$letter);
 $letter=str_replace("^Location^",$employee[0]->BranchName,$letter);
  $letter=str_replace("^Nationality^",$employee[0]->Nationality,$letter);
  $letter=str_replace("^Designation^",$employee[0]->JobTitleName,$letter);
  $letter=str_replace("^Location^",$employee[0]->BranchName,$letter);
  $letter=str_replace("^Salary^",$employee[0]->Salary,$letter);

 

$letter=str_replace("^DATE^",date('d-m-Y'),$letter);
echo $letter;


	 ?>
   
 
 

  
 

  
 
</textarea>
 
  <script src="{{URL('/assets/js/tinymce.min.js')}}"></script>
      <script id="rendered-js" >
tinymce.init({
  selector: 'textarea',
  height: 500,
  menubar: false,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor textcolor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code help wordcount'
  ],
  mobile: { 
    theme: 'mobile' 
  },
  toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tiny.cloud/css/codepen.min.css'
  ],
});
//# sourceURL=pen.js
    </script>
</div>
</div>
 
 
 
                                      
    <input type="submit" class="btn btn-primary w-md">                                   
                                   
    
                                      
                                        

                                       

                                    </div>
                                </div>

                            </form>
                            </div> <!-- end col -->
                        </div>
                      

  
                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>


    
</div>
  @endsection