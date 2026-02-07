<!DOCTYPE html>
<html>

<head>
    <title>Laravel Multiple Images Upload Using Dropzone</title>
    <meta name="_token" content="{{csrf_token()}}" />
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
        <link href="{{URL('/')}}/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{URL('/')}}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="{{URL('/')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

<style>
    
    body{

        margin-left: 0px !important;
background-color: #fff !important;
    }
    .container{
margin: 0px !important;
padding: 0px !important;
width: 100% !important;
margin-right: 0px !important;
    }

</style>
 
</head>



   

<body>
    <div class="container-fluid">

       <br>
        <label>Attach File</label>
        
        <form style="width: 100%;" method="post" action="{{url('AttachmentSave')}}" enctype="multipart/form-data"  >
            {{csrf_field()}}
            
            <input type="text" name="InvoiceNo" value="{{request()->vhno}}" readonly="">
            <input type="file" name="filenames[]" multiple="" class="form-control form-control-sm" style="width: 75%;">


            <div>
        <button type="submit" class="btn btn-success btn-sm float-right mt-1">Upload</button>
             </div>
            



        </form>
          
  @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-1 mt-2" id="success-alert">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

      


          @if(count($attachment)>0)        
         <table class="table table-sm table-bordered align-middle table-nowrap mt-1">
         <tbody><tr class="bg-light">
         <th scope="col">S.No</th>
         <th scope="col">Attachment Name</th>
         <th scope="col" class="text-center">Action</th>
          </tr>
         </tbody>
         <tbody>
         @foreach ($attachment as $key =>$value)
          <tr>
          <td class="col-md-1">{{$key+1}}</td>
          <td class="col-md-1">{{$value->FileName}}</td>
          <td class="col-md-1 text-center" > <a href="{{URL('/documents/'.$value->FileName)}}" target="_blank" ><i class="mdi mdi-eye-outline  align-middle me-1"></i></a>   <a href="{{URL('/AttachmentDelete/'.$value->AttachmentID.'/'.$value->FileName)}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="bx bx-trash  align-middle me-1"></i></a>  </td>  
          </tr>
          @endforeach   
          </tbody>
          </table>
          @else
            <p class=" text-danger mt-2">No Attachment</p>
          @endif   

</body>

</html>