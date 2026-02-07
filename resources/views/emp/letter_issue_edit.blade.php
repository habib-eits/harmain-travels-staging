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
 

    <form action="{{URL('/issue_letter_update')}}" method="post">
        {{csrf_field()}}
<div class="card">
<div class="card-body">
 

<input class="form-control" type="hidden"  name="IssueLetterID" id="example-email-input" required value="{{$issue_letter[0]->IssueLetterID}}">

<h4 class="card-title">Letter Templates</h4>
<p class="card-title-desc"> </p>

 


 
<div class="mb-3 row">
<label for="example-email-input" class="col-md-2 col-form-label">Title</label>
<div class="col-md-10">
<input class="form-control" type="text"  name="Title" id="example-email-input" required value="{{$issue_letter[0]->Title}}">
</div>
</div>

 
<div class="mb-3 row">
<label for="example-email-input" class="col-md-2 col-form-label">Content</label>
<div class="col-md-10">
 <textarea id="basic-example" name="Content">
 

  <?php   
 $letter=str_replace("^NAME^",$employee[0]->FirstName,$issue_letter[0]->Content);

// $letter=str_replace("^FNAME^",$employee[0]->fname,$letter);
// $letter=str_replace("^DATE^",date('d-m-Y'),$letter);
// $letter=str_replace("^PROJECT^",$employee[0]->project_name,$letter);
// $letter=str_replace("^ADDRESS^",$employee[0]->res_address,$letter);

// $letter=str_replace("^CNIC^",$employee[0]->cnic,$letter);
// $letter=str_replace("^CONTACT^",$employee[0]->mobile,$letter);
// $letter=str_replace("^REP_CNIC^",$employee[0]->rep_cnic,$letter);
// $letter=str_replace("^REP_CONTACT^",$employee[0]->rep_contact,$letter);
// $letter=str_replace("^CATEGORY^",$employee[0]->category_name,$letter);
// $letter=str_replace("^REG_NO^",$employee[0]->reg_no,$letter);
// $letter=str_replace("^PLOT_NO^",$employee[0]->plot_no,$letter);
// $letter=str_replace("^SPECIFICATION^",$employee[0]->specification_name,$letter);
// $letter=str_replace("^TOTAL_PRICE^",$v_ledger_summary[0]->receivable,$letter);
// $letter=str_replace("^PAID^",$v_ledger_summary[0]->paid,$letter);
// $letter=str_replace("^BALANCE^",$v_ledger_summary[0]->balance,$letter);
// $letter=str_replace("^AREA^",$employee[0]->area,$letter);
// $letter=str_replace("^REP_NAME^",$employee[0]->rep_name,$letter);

// $letter=str_replace("^BOOKING_DATE^",$employee[0]->booking_date ,$letter);
// $letter=str_replace("^MEMBERSHIP_ID^",$employee[0]->customer_id ,$letter);
// $letter=str_replace("^PAYMENT_TYPE^",$employee[0]->payment_type ,$letter);
// $letter=str_replace("^FORM_NO^",$employee[0]->application_form_no ,$letter);

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