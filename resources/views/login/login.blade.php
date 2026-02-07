<!DOCTYPE html>
<html lang="en" class="h-100">


<!-- Mirrored from w3crm.w3itexpert.com/xhtml/page-register.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 03 Oct 2023 01:46:13 GMT -->
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="">
	<meta name="author" content="">
	<meta name="robots" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Extensive Accounting ERP">
	<meta property="og:title" content="Extensive Accounting Books">
	<meta property="og:description" content="Extensive Accounting Books">
	<meta property="og:image" content="images/economics.png">
	<meta name="format-detection" content="telephone=no">
	
	<!-- PAGE TITLE HERE -->
	<title>Login</title>
	
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="images/economics.png">
	<link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
   <link href="css/style.css" rel="stylesheet">

</head>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
 <script>
@if(Session::has('error'))
  toastr.options =
  {
    "closeButton" : false,
    "progressBar" : true
  }
        Command: toastr["{{session('class')}}"]("{{session('error')}}")
  @endif
</script>

<body class="vh-100">
   <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row h-100">
				<div class="col-lg-6 col-md-7 col-sm-12 mx-auto align-self-center">
					<div class="login-form">
						<div class="text-center">


							 @if (session('error'))
							
							<div class="alert alert-{{ Session::get('class') }} "  id="success-alert">
							                    
							                  <strong>{{ Session::get('error') }} </strong>
							                </div>
							
							@endif
							
					 
							

							<h3 class="title text-primary">Sign up your account</h3>
							<p>Sign in to your account to start using CRM</p>
						</div>
						<form action="{{URL('/UserVerify')}}" method="post">

							{{csrf_field()}}


							<div class="mb-4">
								<label class="mb-1 text-dark">Email</label>
								<input type="email"  name="username" class="form-control form-control" value="{{old('username')}}">
							</div>
							<div class="mb-4 position-relative">
								<label class="mb-1 text-dark">Password</label>
								<input type="password" name="password" id="dz-password" class="form-control" value="{{old('password')}}">
								<span class="show-pass eye">
								
									<i class="fa fa-eye-slash"></i>
									<i class="fa fa-eye"></i>
								
								</span>
							</div>	


						<!-- 	<div class="mb-4 position-relative">
								<label class="mb-1 text-dark">Type</label>
								 <select name="type" class="form-select">
								 	<option value="Admin">Admin</option>
								 	<option value="User">User</option>
								 	<option value="Agent">Agent</option>
								 	option
								 </select>
								 
							</div> -->
							<div class="form-row d-flex justify-content-between mt-4 mb-2">
								<div class="mb-4">
									<div class="form-check custom-checkbox mb-3">
										<input type="checkbox" class="form-check-input" id="customCheckBox1" >
										<label class="form-check-label" for="customCheckBox1">Remember my preference</label>
									</div>
								</div>
								<div class="mb-4">
									<!-- <a href="page-forgot-password.html" class="btn-link text-primary">Forgot Password?</a> -->
								</div>
							</div>
							<div class="text-center mb-4">
								<button type="submit" class="btn btn-primary btn-block">Sign In</button>
							</div>
							<h6 class="login-title"><span>Or continue with</span></h6>
							
							<div class="mb-3">
								<ul class="d-flex align-self-center justify-content-center">
									<li><a target="_blank" href="https://www.facebook.com/" class="fab fa-facebook-f btn-facebook"></a></li>
									<li><a target="_blank" href="https://www.google.com/" class="fab fa-google-plus-g btn-google-plus mx-2"></a></li>
									<li><a target="_blank" href="https://www.linkedin.com/" class="fab fa-linkedin-in btn-linkedin me-2"></a></li>
									<li><a target="_blank" href="https://twitter.com/" class="fab fa-twitter btn-twitter"></a></li>
								</ul>
							</div>
							<p class="text-center" ><p> &copy; Copyright {{date('Y')}} {{$company[0]->Name}}. All rights reserved. </p>
							</p>
						</form>
					</div>
				</div>
                <div class="col-xl-6 col-lg-6">
					<div class="pages-left h-100">
						<div class="login-content">
							<a href="index.html"><h2 style="font-weight: bolder; ">Extensive Books</h2></a>
							<a href="index.html"><img src="{{asset('assets/images/logo/logo.png')}}" class="mb-3 logo-light" alt=""></a>
							<p>"Unlock the potential of precision with our accountancy book software "</p>
						</div>
						<div class="login-media text-center">
							<img src="images/login.png" alt="">
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>

<!--**********************************
	Scripts
***********************************-->
<!-- Required vendors -->
 <script src="vendor/global/global.min.js"></script>
<script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
 <script src="js/custom.js"></script>
<script src="js/deznav-init.js"></script>
<script src="js/demo.js"></script>

<!-- <script src="js/styleSwitcher.js"></script> -->
</body>

<!-- Mirrored from w3crm.w3itexpert.com/xhtml/page-register.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 03 Oct 2023 01:46:13 GMT -->
</html>