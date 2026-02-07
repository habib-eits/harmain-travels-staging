<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Login | SUIT - Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Bootstrap Css -->
            <!-- Bootstrap Css -->
            <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />

            <!-- Icons Css -->
            <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

            <!-- App Css -->
            <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

            <!-- App js -->
            <script src="{{ asset('assets/js/plugin.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    </head>

    <body>
        <div class="account-pages my-5 pt-sm-5">
            
            <div class="container">

                <div class="row justify-content-center">
                    
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-primary-subtle">
                                <div class="row m-4">
                                    <img src="https://suit.suitportal.com/public/images/logo.svg" alt="SUIT Logo" style="width: auto; height: 100px;">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p>Sign in to continue.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body pt-0"> 
                                 
                                <div class="p-2">
                                    <form class="form-horizontal" id="loginForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control" id="username" placeholder="Enter username">
                                        </div>
                
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input type="password" name="password" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon" id="password">
                                                <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember-check">
                                            <label class="form-check-label" for="remember-check">
                                                Remember me
                                            </label>
                                        </div>
                                        
                                        <div class="mt-3 d-grid">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" id="submitForm">Log In</button>
                                        </div>
            
                                       

                                        <div class="mt-4 text-center">
                                            <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                        </div>
                                    </form>
                                </div>
            
                            </div>
                        </div>
                      

                    </div>
                </div>
            </div>
        </div>
        <!-- end account-pages -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
        
        <!-- App js -->
        <script src="{{asset('assets/js/app.js')}}"></script>
    </body>


      <script src="assets/login_1/js/jquery-3.6.0.min.js"></script>

    
  
<script>
  $(document).ready(function() {
  $('#togglePassword').on('click', function() {
    var passwordField = $('#password');
    var passwordFieldType = passwordField.attr('type');
    
    // Toggle between password and text field type
    if (passwordFieldType === 'password') {
      passwordField.attr('type', 'text');
      $('#eyeOpen').show();
      $('#eyeClosed').hide();
    } else {
      passwordField.attr('type', 'password');
      $('#eyeOpen').hide();
      $('#eyeClosed').show();
    }
  });
});

</script>


<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script>

    // Create an instance of Notyf
    let notyf = new Notyf({
        duration: 3000,
        position: {
            x: 'right',
            y: 'top',
        },
    });
</script>
<script>
    $("#loginForm").on('submit', function (e) {
        e.preventDefault();
        const btn = $("#submitForm");
        let formData = new FormData($("#loginForm")[0]);
        $.ajax({
            type: "POST",
            url: "{{URL('/UserVerify')}}",
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            beforeSend: function () {
                btn.prop('disabled', true);
                btn.html('Processing');
            },
            success: function (res) {
                if (res.success === true) {
                    btn.prop('disabled', false);
                    btn.html('Log In');
                    setTimeout(function () {
                        window.location.href = "{{URL('/Dashboard')}}";
                    }, 100);
                    notyf.success({
                        message: res.message,
                        duration: 10000
                    });
                    
                    $('error-email').html('');
                    $('.error-password').html('');

                } else {
                    btn.prop('disabled', false);
                    btn.html('Sign In');
                    notyf.error({
                        message: res.message,
                        duration: 3000
                    })

                }
            },
            error: function (e) {
                btn.prop('disabled', false);
                btn.html('Log In');
                if (e.responseJSON.errors['email']) {
                    $('.error-email').html('<small class=" error-message text-danger fira-sans-condensed-regular">' + e.responseJSON.errors['email'][0] + '</small>');
                }
                if (e.responseJSON.errors['password']) {
                    $('.error-password').html('<small class=" error-message text-danger fira-sans-condensed-regular">' + e.responseJSON.errors['password'][0] + '</small>');
                }
            }

        });
    });
</script>


</html>
