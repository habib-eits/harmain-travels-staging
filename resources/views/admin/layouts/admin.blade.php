<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Admin | SUIT')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- App plugin -->
    <script src="{{ asset('assets/js/plugin.js') }}"></script>


     <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
 
  
        
        <link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Sweet Alert-->
 
 
 
 
        <!-- Responsive datatable examples -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<style>
 

 .select2-container--default .select2-search--dropdown {
    padding: 5px;
    background-color: #fff;
}


.select2-container .select2-selection--single {
    background-color: #fff;
    border: 1px solid #ced4da;
    height: 38px
}
.select2-container .select2-selection--single:focus {
    outline: 0;
    border: 1px solid rgb(64, 148, 218);

}
.select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: .75rem;
    color: #495057
}
.select2-container .select2-selection--single .select2-selection__arrow {
    height: 34px;
    width: 34px;
    right: 3px
}
.select2-container .select2-selection--single .select2-selection__arrow b {
    border-color: #adb5bd transparent transparent transparent;
    border-width: 6px 6px 0 6px
}
.select2-container .select2-selection--single .select2-selection__placeholder {
    color: #495057
}
.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent #adb5bd transparent!important;
    border-width: 0 6px 6px 6px!important
}
 
.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da;
    background-color: #fff;
    color: #74788d;
    outline: 0
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #556ee6
}
.select2-container--default .select2-results__option[aria-selected=true] {
    /*background-color: #f8f9fa;*/
    /*color: #343a40*/
}
.select2-container--default .select2-results__option[aria-selected=true]:hover {
    background-color: #556ee6;
    color: #fff
}
.select2-results__option {
    padding: 6px 12px
}
.select2-container[dir=rtl] .select2-selection--single .select2-selection__rendered {
    padding-left: .75rem
}
.select2-dropdown {
    border: 1px solid rgba(0, 0, 0, .15);
    background-color: #fff;
    -webkit-box-shadow: 0 .75rem 1.5rem rgba(18, 38, 63, .03);
    box-shadow: 0 .75rem 1.5rem rgba(18, 38, 63, .03)
}
.select2-search input {
    border: 1px solid #f6f6f6
}
.select2-container .select2-selection--multiple {
    min-height: 38px;
    background-color: #fff;
    border: 1px solid #ced4da!important
}
.select2-container .select2-selection--multiple .select2-selection__rendered {
    padding: 2px .75rem
}
.select2-container .select2-selection--multiple .select2-search__field {
    border: 0;
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field::-webkit-input-placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field::-moz-placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field:-ms-input-placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field::-ms-input-placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-search__field::placeholder {
    color: #495057
}
.select2-container .select2-selection--multiple .select2-selection__choice {
    background-color: #eff2f7;
    border: 1px solid #f6f6f6;
    border-radius: 1px;
    padding: 0 7px
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #ced4da
}
.select2-container--default .select2-results__group {
    font-weight: 600
}
.select2-result-repository__avatar {
    float: left;
    width: 60px;
    margin-right: 10px
}
.select2-result-repository__avatar img {
    width: 100%;
    height: auto;
    border-radius: 2px
}
.select2-result-repository__statistics {
    margin-top: 7px
}
.select2-result-repository__forks, .select2-result-repository__stargazers, .select2-result-repository__watchers {
    display: inline-block;
    font-size: 11px;
    margin-right: 1em;
    color: #adb5bd
}
.select2-result-repository__forks .fa, .select2-result-repository__stargazers .fa, .select2-result-repository__watchers .fa {
    margin-right: 4px
}
.select2-result-repository__forks .fa.fa-flash::before, .select2-result-repository__stargazers .fa.fa-flash::before, .select2-result-repository__watchers .fa.fa-flash::before {
    content: "\f0e7";
    font-family: 'Font Awesome 5 Free'
}
.select2-results__option--highlighted .select2-result-repository__forks, .select2-results__option--highlighted .select2-result-repository__stargazers, .select2-results__option--highlighted .select2-result-repository__watchers {
    color: rgba(255, 255, 255, .8)
}
.select2-result-repository__meta {
    overflow: hidden
}



/* remove input type number arrow up and down */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number]{
    -moz-appearance: textfield;
}
</style>


    @stack('styles')
</head>

<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('admin.layouts.header')

        <!-- Left Sidebar Start -->
        {{-- @include('admin.layouts.sidebar') --}}
         @include('template.sidebar')
        <!-- Left Sidebar End -->

        <!-- Main Content Start -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © copyrights. All rights reserved. Laravel 10.0, PHP 8.1^
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Kashif Mushtaq
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- Main Content End -->

    </div>
    <!-- END layout-wrapper -->

    
        <!-- JAVASCRIPT -->
        
        <script src="{{URL('/')}}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{URL('/')}}/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="{{URL('/')}}/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{URL('/')}}/assets/libs/node-waves/waves.min.js"></script>

   
 

        <!-- App js -->
        <script src="{{URL('/')}}/assets/js/app.js"></script>

        

        <!-- form mask -->
 
        <!-- form mask init -->
 
 
        <!-- Required datatable js -->
        <script src="{{URL('/')}}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="{{URL('/')}}/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
           
    


        
 
        <!-- form repeater js -->
        <script src="{{asset('assets/libs/jquery.repeater/jquery.repeater.min.js')}}"></script>

        <script src="{{asset('assets/js/pages/form-repeater.int.js')}}"></script>


 
 
       <script src="{{asset('assets/libs/select2/js/select2.min.js')}}"></script>
 
         <!-- init js -->
        <script src="{{asset('assets/js/pages/ecommerce-select2.init.js')}}"></script> 

        
 

        <!-- toastr init -->
        <script src="{{URL('/')}}/assets/js/pages/toastr.init.js"></script>
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

    @stack('scripts')

</body>
</html>
