<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In - Travel ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Notyf -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Fira Sans Condensed", sans-serif;
        }
        .text-danger {
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-light">

<div class="container min-vh-100 d-flex align-items-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                     <div class="text-center mb-4">
                        <img src="{{asset('documents/'.$company->Logo)}}" width="200" class="mb-3">
                        <h3 class="fw-bold">
                            Sign in to {{$company->Name.' '.$company->Name2}}
                        </h3>
                        <p class="text-muted">Do it smart, get it faster, keep it easy</p>
                    </div>


                   
                    <form id="apiLoginForm">
                        <div id="error" class="text-danger"></div>

                        <div class="mb-3">
                            <input type="email" name="email" id="email"
                                   class="form-control form-control-lg"
                                   placeholder="Email">
                            <div class="error-email"></div>
                        </div>

                        <div class="mb-3 position-relative">
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-lg"
                                   placeholder="Password">
                            <button type="button" id="togglePassword"
                                    class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2">
                                👁
                            </button>
                            <div class="error-password"></div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label">Remember me</label>
                            </div>
                        </div>

                        <button type="submit" id="submitForm"
                                class="btn btn-success btn-lg w-100">
                            Log In
                        </button>
                    </form>
                    <p class="text-center text-muted mt-4 mb-0">
                        © {{date('Y')}} {{$company->Name.' '.$company->Name2}}
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
const notyf = new Notyf({
    duration: 3000,
    position: { x: 'right', y: 'top' }
});

// Password toggle
document.getElementById('togglePassword').onclick = () => {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
};

// Remember Me
window.onload = () => {
    const savedEmail = localStorage.getItem('email');
    if (savedEmail) {
        email.value = savedEmail;
        remember.checked = true;
    }
};


</script>
<script>

    const APP_URL = "{{ config('app.url') }}";


document.getElementById('apiLoginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    clearErrors();

     // Remember Me functionality



    axios.post(APP_URL + '/api/party/login', {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    })
    .then(function (response) {

        if(response.data.token){
            localStorage.setItem('party_token', response.data.token);

            setTimeout(() => {
                    window.location.href = APP_URL + "/api/party/ledger";
            }, 500);    

        }else{
            document.getElementById('error').innerHTML = "Please Contact Admin";
        }
    })
    .catch(function (error) {

        dispalyErrors(error.response.data.errors);
    });
    

});






function clearErrors() {
    document.querySelector('.error-email').innerHTML = '';
    document.querySelector('.error-password').innerHTML = '';
}

function dispalyErrors(errors){
    if (errors.email) {
        document.querySelector('.error-email').innerHTML = '<span class="text-danger">' + errors.email + '</span>';
    } else {
        document.querySelector('.error-email').innerHTML = '';
    }
    if (errors.password) {
        document.querySelector('.error-password').innerHTML = '<span class="text-danger">' + errors.password + '</span>';
    } else {
        document.querySelector('.error-password').innerHTML = '';
    }
}
</script>



</body>
</html>
