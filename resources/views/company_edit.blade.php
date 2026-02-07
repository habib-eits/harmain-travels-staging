@extends('template.tmp')

@section('title', $pagetitle)


@section('content')

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            @if (session('error'))

            <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">

                {{ Session::get('error') }}
            </div>

            @endif

            @if (count($errors) > 0)

            <div>
                <div class="alert alert-danger p-2 border-1">
                    <p class="font-weight-bold"> There were some problems with your input.</p>
                    <ul>

                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>

                        @endforeach
                    </ul>
                </div>
            </div>

            @endif


            <div class="card">
                <div class="card-body">
                    <form action="{{URL('/CompanyUpdate')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <input type="hidden" name="CompanyID" value="{{$company[0]->CompanyID}}">
                        <div>
                            <div>

                                <h4 class="card-title">Add Supplier</h4>
                                <p class="card-title-desc"></p>
                                <p class="card-title-desc"></p>
                                <p class="card-title-desc"></p>
                                <p class="card-title-desc"></p>
                                <p class="card-title-desc"></p>



                                <?php
$CompanyID        = old('CompanyID') ? old('CompanyID') : $company[0]->CompanyID;
$Name             = old('Name') ? old('Name') : $company[0]->Name;
$Name2             = old('Name2') ? old('Name2') : $company[0]->Name2;
$TRN             = old('TRN') ? old('TRN') : $company[0]->TRN;
$Contact          = old('Contact') ? old('Contact') : $company[0]->Contact;
$Email            = old('Email') ? old('Email') : $company[0]->Email;
$Website            = old('Website') ? old('Website') : $company[0]->Website;
$Address          = old('Address') ? old('Address') : $company[0]->Address;
$Logo             = old('Logo') ? old('Logo') : $company[0]->Logo;
$BackgroundLogo   = old('BackgroundLogo') ? old('BackgroundLogo') : $company[0]->BackgroundLogo;
$Signature        = old('Signature') ? old('Signature') : $company[0]->Signature;
$DigitalSignature = old('DigitalSignature') ? old('DigitalSignature') : $company[0]->DigitalSignature;

$EstimateInvoiceTitle = old('EstimateInvoiceTitle') ? old('EstimateInvoiceTitle') : $company[0]->EstimateInvoiceTitle;
$SaleInvoiceTitle     = old('SaleInvoiceTitle') ? old('SaleInvoiceTitle') : $company[0]->SaleInvoiceTitle;
$DeliveryChallanTitle = old('DeliveryChallanTitle') ? old('DeliveryChallanTitle') : $company[0]->DeliveryChallanTitle;
$CreditNoteTitle      = old('CreditNoteTitle') ? old('CreditNoteTitle') : $company[0]->CreditNoteTitle;
$PurchaseInvoiceTitle = old('PurchaseInvoiceTitle') ? old('PurchaseInvoiceTitle') : $company[0]->PurchaseInvoiceTitle;
$DebitNoteTitle       = old('DebitNoteTitle') ? old('DebitNoteTitle') : $company[0]->DebitNoteTitle;

?>



                        <input type="hidden" value="{{$CompanyID}}" name="CompanyID">
                       <div class="mb-1 row">
                      <label for="example-email-input" class="col-md-3 col-form-label fw-bold ">Company Name</label>
                      <div class="col-md-6">
                      <input class="form-control" type="text" name="Name" value="{{$Name}}">
                      </div>
                    </div>

                      <div class="mb-1 row">
                      <label for="example-email-input" class="col-md-3 col-form-label fw-bold text-danger"> Company Name (Extra Line) </label>
                      <div class="col-md-6">
                      <input class="form-control" type="text" name="Name2" value="{{$Name2}}">
                      </div>
                    </div>


                     <div class="mb-1 row">
                      <label for="example-email-input" class="col-md-3 col-form-label fw-bold text-danger">TRN # </label>
                      <div class="col-md-6">
                      <input class="form-control" type="text" name="TRN" value="{{$TRN}}">
                      </div>
                    </div>



                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">Email</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="Email" value="{{$Email}}">
                      </div>

                    </div>


                       <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">Website</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="Website" value="{{$Website}}">
                      </div>

                    </div>



                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">Phone</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="Contact" value="{{$Contact}}">
                      </div>

                    </div>


                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">Address</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="Address" value="{{$Address}}">
                      </div>

                    </div>

                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">logo</label>
                      <div class="col-md-6">
                       <input class="form-control" type="file" name="Logo" id="Logo" value="{{$Logo}}">
                      </div>

                    </div>


                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">BackgroundLogo</label>
                      <div class="col-md-6">
                      
                      <input class="form-control" type="file" name="BackgroundLogo" id="BackgroundLogo" value="{{$BackgroundLogo}}">
                      </div>

                    </div>

                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">Signature</label>
                      <div class="col-md-6">
                         <input class="form-control" type="file" name="Signature" id="Signature">
                      </div>

                    </div>

                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">Digital Signature</label>
                      <div class="col-md-6">

                        <textarea name="DigitalSignature" id="DigitalSignature" cols="30" rows="10">
                          {{$DigitalSignature}}
                        </textarea>
 <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
                         <script>
                CKEDITOR.replace( 'DigitalSignature',{
                    height:350,
                });
        </script>
                      </div>

                    </div>


                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">EstimateInvoiceTitle</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="EstimateInvoiceTitle" value="{{$EstimateInvoiceTitle}}">
                      </div>

                    </div>



                       <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">SaleInvoiceTitle</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="SaleInvoiceTitle" value="{{$SaleInvoiceTitle}}">
                      </div>

                    </div>



                       <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">DeliveryChallanTitle</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="DeliveryChallanTitle" value="{{$DeliveryChallanTitle}}">
                      </div>

                    </div>



                       <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">CreditNoteTitle</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="CreditNoteTitle" value="{{$CreditNoteTitle}}">
                      </div>

                    </div>


                       <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">PurchaseInvoiceTitle</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="PurchaseInvoiceTitle" value="{{$PurchaseInvoiceTitle}}">
                      </div>

                    </div>


                      <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-3 col-form-label fw-bold">DebitNoteTitle</label>
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="DebitNoteTitle" value="{{$DebitNoteTitle}}">
                      </div>

                    </div>


                            </div>

                        </div>

                </div>
                <div class="card-footer bg-secondary bg-soft">
                    <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Update</button>




                    <a href="{{URL('/Company')}}" class="btn btn-outline-secondary waves-effect">Cancel</a>
                </div>
            </div>
            <!-- card end here -->
            </form>

        </div>
    </div>
</div>
@endsection