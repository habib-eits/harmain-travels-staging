<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attachment</title>


  <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Lightbox css -->
        <link href="{{asset('assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />



</head>
<body>
    <div class="card-body">
        
                                        <h4 class="card-title">Attachments</h4>
                                        
        

<?php 

 

$attachment = DB::table('attachment')->where('InvoiceNo',session::get('VHNO'))->get();
 ?>

                                        <div class="popup-gallery d-flex flex-wrap">
@if(count($attachment)>0)
@foreach($attachment as $value)
 
                                            <a href="{{asset('documents/'.$value->FileName)}}" title="Project 1">
                                                <div class="img-fluid">
                                                    <img src="{{asset('documents/'.$value->FileName)}}" alt="" width="120">
                                                </div>
                                            </a>


                                            @endforeach
  @else
<p class="text-danger">No attachment</p>
  @endif                                      


                                        </div>
        
                                    </div>

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

        <!-- Magnific Popup-->
        <script src="{{asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>

        <!-- lightbox init js-->
        <script src="{{asset('assets/js/pages/lightbox.init.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('assets/js/app.js')}}"></script>




</body>



</html>