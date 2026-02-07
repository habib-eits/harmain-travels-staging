<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In - Travel ERP</title>
    <link rel="stylesheet" href="assets/login_1/css/slick.css">
    <link rel="stylesheet" href="assets/login_1/css/aos.css">
    <link rel="stylesheet" href="assets/login_1/css/output.css">
    <link rel="stylesheet" href="assets/login_1/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="shortcut icon" href="{{ URL('/assets/images/favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">


    <style>
        <style>.notyf .notyf--error {
            background-color: red !important;
            /* Enforce red background */
            color: white !important;
            /* Enforce white text color */
        }

        .text-danger {
            color: red;
            font-weight: bolder;
        }

        .fira-sans-condensed-thin {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 100;
            font-style: normal;
        }

        .fira-sans-condensed-extralight {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 200;
            font-style: normal;
        }

        .fira-sans-condensed-light {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 300;
            font-style: normal;
        }

        .fira-sans-condensed-regular {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .fira-sans-condensed-medium {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 500;
            font-style: normal;
        }

        .fira-sans-condensed-semibold {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 500;
            font-style: normal;
            font-size: 12pt;
        }

        .fira-sans-condensed-bold {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 700;
            font-style: normal;
        }

        .fira-sans-condensed-extrabold {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 800;
            font-style: normal;
        }

        .fira-sans-condensed-black {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 700;
            font-style: normal;
            font-size: 32pt;
            line-height: 35pt;

        }

        .fira-sans-condensed-thin-italic {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 100;
            font-style: italic;
        }

        .fira-sans-condensed-extralight-italic {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 200;
            font-style: italic;
        }

        .fira-sans-condensed-light-italic {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 300;
            font-style: italic;
        }

        .fira-sans-condensed-regular-italic {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 400;
            font-style: italic;
        }

        .fira-sans-condensed-medium-italic {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 500;
            font-style: italic;
        }

        .fira-sans-condensed-semibold-italic {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 600;
            font-style: italic;
        }

        .fira-sans-condensed-bold-italic {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 700;
            font-style: italic;
        }

        .fira-sans-condensed-extrabold-italic {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 800;
            font-style: italic;
        }

        .fira-sans-condensed-black-italic {
            font-family: "Fira Sans Condensed", sans-serif;
            font-weight: 900;
            font-style: italic;
        }
    </style>



</head>

<body>
    <section class="bg-white dark:bg-darkblack-500">
        <div class="flex flex-col lg:flex-row justify-between min-h-screen">
            <!-- Left -->
            <div class="lg:w-1/2 px-5 xl:pl-12 pt-5">
                <header>
                    <a href="{{ URL('/') }}" class="">
                        <img src="{{ asset('documents/' . $company[0]->Logo) }}" class="block dark:hidden" alt="Logo"
                            style="width: 250px;">
                        <img src="assets/login_1/images/logo/logo-white.svg" class="hidden dark:block" alt="Logo">
                    </a>
                </header>
                <div class="max-w-[450px] m-auto pt-24 pb-16">
                    <header class="text-center mb-8">
                        <h2
                            class="text-bgray-900 dark:text-white text-4xl font-semibold font-poppins mb-2 fira-sans-condensed-black">
                            Sign in to {{ $company[0]->Name . ' ' . $company[0]->Name2 }}
                        </h2>
                        <p
                            class="font-urbanis text-base font-medium text-bgray-600 dark:text-bgray-50 fira-sans-condensed-semibold">
                            Do it smart, get it faster, keep it easy
                        </p>
                    </header>


                    <form method="post" id="loginForm">
                        @csrf
                        <div class="mb-4">
                            <input type="text"
                                class=" fira-sans-condensed-regular text-bgray-800 text-base border border-bgray-300 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base"
                                placeholder="email" name="email" id="email">
                            <div class="error-email"></div>
                        </div>
                        <div class="mb-6 relative">
                            <input type="password" id="password" name="password"
                                class="fira-sans-condensed-regular text-bgray-800 text-base border border-bgray-300 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base"
                                placeholder="Password">
                            <div class="error-password"></div>
                            <button type="button" id="togglePassword" class="absolute top-4 right-4 bottom-4">
                                <svg id="eyeOpen" style="display:none;" width="22" height="20"
                                    viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11 3C15 3 18.333 5.333 21 10C18.333 14.667 15 17 11 17C7 17 3.667 14.667 1 10C3.667 5.333 7 3 11 3ZM11 14C12.657 14 14 12.657 14 11C14 9.343 12.657 8 11 8C9.343 8 8 9.343 8 11C8 12.657 9.343 14 11 14Z"
                                        stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                                <svg id="eyeClosed" width="22" height="20" viewBox="0 0 22 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 1L20 19" stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M9.58445 8.58704C9.20917 8.96205 8.99823 9.47079 8.99805 10.0013C8.99786 10.5319 9.20844 11.0408 9.58345 11.416C9.95847 11.7913 10.4672 12.0023 10.9977 12.0024C11.5283 12.0026 12.0372 11.7921 12.4125 11.417"
                                        stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M8.363 3.36506C9.22042 3.11978 10.1082 2.9969 11 3.00006C15 3.00006 18.333 5.33306 21 10.0001C20.222 11.3611 19.388 12.5241 18.497 13.4881M16.357 15.3491C14.726 16.4491 12.942 17.0001 11 17.0001C7 17.0001 3.667 14.6671 1 10.0001C2.369 7.60506 3.913 5.82506 5.632 4.65906"
                                        stroke="#718096" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex justify-between mb-7">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox"
                                    class="w-5 h-5 dark:bg-darkblack-500 focus:ring-transparent rounded-full border border-bgray-300 focus:accent-success-300 text-success-300"
                                    name="remember" id="remember">
                                <label for="remember"
                                    class="text-bgray-900 dark:text-white text-base font-semibold ">Remember me</label>
                            </div>
                            <div>
                                <!-- <a href="#" data-target="#multi-step-modal" class="modal-open text-success-300 font-semibold text-base underline">Forgot Password?</a> -->
                            </div>
                        </div>


                       <button
                          id="submitForm"
                          type="submit"
                          style="
                            padding: 14px;
                            width: 100%;
                            background: #22c55e;
                            color: #ffffff;
                            font-weight: bold;
                            border: none;
                            border-radius: 8px;
                            cursor: pointer;
                          ">
                          Log In
                        </button>

                        <div style="margin-top: 10px; text-align: center;">
                          <a href="{{ env('APP_URL') }}/api/party/login"
                            style="
                              display: inline-block;
                              padding: 14px;
                              width: 100%;
                              font-weight: bold;
                              background: navy;
                              color: #ffffff;
                              font-size: 14px;
                              text-decoration: none;
                              border-radius: 5px;
                            ">
                            Party Login
                          </a>
                        </div>




                    </form>

                    <p class="text-center d-none text-bgray-900 dark:text-bgray-50 text-base font-medium pt-0">


                    </p>

                    <p class="text-bgray-600 dark:text-white text-center text-sm mt-6 fira-sans-condensed-semibold">
                        @ {{ date('Y') }} {{ $company[0]->Name . ' ' . $company[0]->Name2 }}. All Right Reserved.
                    </p>
                </div>
            </div>

           
            <!-- Right -->
            <div class="lg:w-1/2 lg:block hidden bg-[#F6FAFF] dark:bg-darkblack-600 p-8 relative">
                <ul>
                    <li class="absolute top-10 left-8">
                        <img src="assets/login_1/images/shapes/square.svg" alt="">
                    </li>
                    <li class="absolute right-12 top-14">
                        <img src="assets/login_1/images/shapes/vline.svg" alt="">
                    </li>
                    <li class="absolute bottom-7 left-8">
                        <img src="assets/login_1/images/shapes/dotted.svg" alt="">
                    </li>
                </ul>
                <div class="">
                    <img src="assets/login_1/images/illustration/signin.svg" alt="">
                </div>
                <div>
                    <div class="text-center max-w-lg px-1.5 m-auto">
                        <h3
                            class="text-bgray-900 dark:text-white font-semibold font-popins text-4xl mb-0 fira-sans-condensed-black">
                            Speedy, Easy & Reliable
                        </h3>
                        <!--     <p class="text-bgray-600 dark:text-bgray-50 text-sm font-medium">
                BankCo. help you set saving goals, earn cash back offers, Go to
                disclaimer for more details and get paychecks up to two days
                early. Get a
                <span class="text-success-300 font-bold">$20</span> bonus when
                you receive qualifying direct deposits
              </p> -->
                    </div>
                </div>
            </div>
            
        </div>
        
    </section>
            


    <!--scripts -->

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

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
        $("#loginForm").on('submit', function(e) {
            e.preventDefault();
            const btn = $("#submitForm");
            let formData = new FormData($("#loginForm")[0]);
            $.ajax({
                type: "POST",
                url: "{{ URL('/UserVerify') }}",
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                beforeSend: function() {
                    btn.prop('disabled', true);
                    btn.html('Processing');
                },
                success: function(res) {
                    if (res.success === true) {
                        btn.prop('disabled', false);
                        btn.html('Log In');
                        setTimeout(function() {
                            window.location.href = "{{ URL('/Dashboard') }}";
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
                error: function(e) {
                    btn.prop('disabled', false);
                    btn.html('Log In');
                    if (e.responseJSON.errors['email']) {
                        $('.error-email').html(
                            '<small class=" error-message text-danger fira-sans-condensed-regular">' +
                            e.responseJSON.errors['email'][0] + '</small>');
                    }
                    if (e.responseJSON.errors['password']) {
                        $('.error-password').html(
                            '<small class=" error-message text-danger fira-sans-condensed-regular">' +
                            e.responseJSON.errors['password'][0] + '</small>');
                    }
                }

            });
        });
    </script>


    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const username = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('remember').checked;

            // Here you would typically send the credentials to your server for authentication

            // For demonstration, we're just logging the values
            console.log('Username:', username);
            console.log('Password:', password);

            // If 'Remember Me' is checked, store the username in local storage
            if (rememberMe) {
                localStorage.setItem('username', username);
            } else {
                localStorage.removeItem('username');
            }

            // Clear the form
            this.reset();
        });

        // On page load, check if a username is stored and populate the input
        window.onload = function() {
            const storedUsername = localStorage.getItem('username');
            if (storedUsername) {
                document.getElementById('username').value = storedUsername;
                document.getElementById('remember').checked = true;
            }
        };
    </script>

</body>

</html>
