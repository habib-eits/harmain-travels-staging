@extends('template.tmp')

@section('title', 'page title...')
 

@section('content')

 <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Form Layouts</h4>

                                    <div class="page-title-right">
                                        <div class="page-title-right">
                                         <!-- button will appear here -->ss
                                    </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Form Grid Layout</h4>

                                        <form>
                                            <div class="mb-3">
                                                <label for="formrow-firstname-input" class="form-label">First name</label>
                                                <input type="text" class="form-control" id="formrow-firstname-input">
                                            </div>

                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="formrow-email-input" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="formrow-email-input">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="formrow-password-input" class="form-label">Password</label>
                                                        <input type="password" class="form-control" id="formrow-password-input">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputCity" class="form-label">City</label>
                                                        <input type="text" class="form-control" id="formrow-inputCity">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputState" class="form-label">State</label>
                                                        <select id="formrow-inputState" class="form-select">
                                                            <option selected>Choose...</option>
                                                            <option>...</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="formrow-inputZip" class="form-label">Zip</label>
                                                        <input type="text" class="form-control" id="formrow-inputZip">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="gridCheck">
                                                    <label class="form-check-label" for="gridCheck">
                                                      Check me out
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-primary w-md">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                           
                        </div>
                        <!-- end row -->

                      

                       

                         
                     
                        
                    </div> <!-- container-fluid -->
                </div>


  @endsection