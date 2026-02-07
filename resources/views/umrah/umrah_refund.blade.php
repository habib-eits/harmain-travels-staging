@extends('tmp')

@section('title', 'Invoice')


@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

<!-- Modal -->
<div class="modal fade exampleModal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add new customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

        </button>
      </div>
      <form method="post">
        <input type="hidden" name="_token" value="SF5krfPegrIS3icD2CjPx78GxfBtaQjeKBoyQ3U2">
        <div class="modal-body">

          <div class="row">
            <div class="col-12">
              <label for=""><strong>Customer : *</strong></label>
              <input type="text" class="form-control" id="PartyName" name="PartyName" required>
              <span class="error-message" id="name-error">Name is required.</span>
            </div>

            <div class="col-12 mt-2">
              <label for=""><strong>Mobile No: *</strong></label>
              <input type="text" class="form-control" id="Phone" name="Phone" required="">
              <span class="error-message" id="email-error">Phone Number is required.</span>

            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="submitButton" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>





<!-- Responsive datatable examples -->



<style type="text/css">

  .form-control
  {
  border-radius: 0 !important;
  
  
  }
  
  .select2
  {
  border-radius: 0 !important;
  width: 100% !important;
  
  }
  
  
  .swal2-popup {
  font-size: 0.8rem;
  font-weight: inherit;
  color: #5E5873;
  }
  
  .select2-container--default .select2-search--dropdown {
       padding: 1px !important; 
      background-color: #556ee6 !important;
  }
  
 
  
  </style>


<!-- Modal Structure -->
<div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-top" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Image tag that will hold the image -->
                <img id="modalImage" src="" alt="Image Preview" class="img-fluid" />
            </div>
        </div>
    </div>
</div>



<script>
  function openImageModal(imagePath) {
    // Set the image source dynamically
    $('#modalImage').attr('src', imagePath);

    // Show the modal
    $('#imageModal').modal('show');
}
</script>


<div class="main-content">

  <div class="page-content">
    <div class="container-fluid">
      <!-- start page title -->

      @if (session('error'))

      <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">

        {{ Session::get('error') }}
      </div>

      @endif

      @if (count($errors) > 0)

      <div>
        <div class="alert alert-danger p-1   border-3">
          <p class="font-weight-bold"> There were some problems with your input.</p>
          <ul>

            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>

            @endforeach
          </ul>
        </div>
      </div>

      @endif


      <?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>
      <div class="card shadow-sm">
        <div class="card-body">


          <!-- enctype="multipart/form-data" -->
          <form method="post" id="umrahForm">


<input type="hidden" name="InvoiceMasterID" value="{{request()->id}}">

            <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">


            <div class=" ">
              <div class=" ">



                <div class="row">
                  <div class="col-6"> <img src="{{asset('assets/images/logo/ft.png')}}" alt="">


                    <br>
                    <br>
                    <div class="col-6">
                         <label for="">Invoice Type</label>
                                   <select class="form-select select2" name="InvoiceTypeID" id="InvoiceTypeID" required="">
   <?php foreach ($invoice_type as $key => $value): ?>
     <option value="{{$value->InvoiceTypeID}}" {{($value->InvoiceTypeID== $invoice_mst[0]->InvoiceTypeID) ? 'selected=selected':'' }}>{{$value->InvoiceTypeCode}}-{{$value->InvoiceType}}</option>
   <?php endforeach ?>
</select> 

                      <div class="clearfix mt-1"></div>
                      <label for="">Party</label>

              <select name="PartyID" id="PartyID" class="form-select select2 mt-5" name="PartyID" required="">
 <?php foreach ($party as $key => $value): ?>
     <option value="{{$value->PartyID}}" {{($value->PartyID== $invoice_mst[0]->PartyID) ? 'selected=selected':'' }}>{{$value->PartyID}}-{{$value->PartyName}}-{{$value->Phone}}</option>
   <?php endforeach ?>
</select>



                      <span id="PartyError" style="color: red; display: none;">Please select a party</span>
                    </div>
                    <div class="clearfix mt-1"></div>
                  </div>


                  <div class="col-2"> </div>
                  <div class="col-4">


                    <div class="row">
                   <div class="col-12">
                        <div class="mb-1 row">
                          <div class="col-sm-3">
                            <label class="col-form-label" for="first-name">Invoice #</label>
                          </div>
                          <div class="col-sm-9">
                          <input type="text" id="first-name" class="form-control" name="VHNO"
                              value="{{$vhno[0]->VHNO}}" readonly="">

                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="mb-1 row">
                          <div class="col-sm-3">
                            <label class="col-form-label" for="email-id">Date</label>
                          </div>
                          <div class="col-sm-9">
                            <div class="input-group" id="datepicker21">
                              <input type="date" name="Date" class="form-control" value="{{$invoice_mst[0]->Date}}">

                            </div>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="mb-1 row">
                            <div class="col-sm-3">
                              <label class="col-form-label" for="contact-info">Due Date</label>
                            </div>
                            <div class="col-sm-9">
                              <div class="input-group" id="datepicker22">

                               <input type="date" name="DueDate" class="form-control" value="{{$invoice_mst[0]->DueDate}}">



                              </div>
                            </div>
                          </div>
                        </div>
                 

                        <div class="col-12">
                          <div class="mb-1 row">
                            <div class="col-sm-3">
                              <label class="col-form-label" for="password">Salesman </label>
                            </div>
                            <div class="col-sm-9">
                               <select name="SalemanID" id="SalemanID" class="form-select">
                  <?php foreach ($saleman as $key => $value): ?>
                    
                    <option value="{{$value->UserID}}" {{($value->UserID== $invoice_mst[0]->UserID) ? 'selected=selected':'' }}>{{$value->FullName}}</option>
                  <?php endforeach ?>
                  
                  
           
                </select>

                              <span id="SalemanError" style="color: red; display: none;">Please select a Saleman</span>
                            </div>

                          </div>
                        </div> 
                      </div> 
                    </div>
                  </div>
                  

                  <hr class="invoice-spacing">

                  <div class='text-center'>

                  </div>
                  <div class='row'>
                    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                     <table  width="100%" >
                        <thead>

   @foreach($invoice_det as $key => $value1)
            <?php $no= $key+1; ?>

                          <tr class="bg-light borde-1 border-light " style="height: 40px;" id="t_{{$no}}">
                            <th width="2%" class="p-1"><input id="check_all" type="checkbox" /></th>
                            <th width="12%">Item</th>
                             <th width="10%">PAX Name</th>
                             <th width="5%">Pick Point</th>
                            <th width="5%">Package</th>
                            <th width="5%">Cost</th>
                            <th width="5%">selling price</th>
                            
                            <th width="7%">Amount Paid</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="p-3" style="vertical-align: top;" id="tt_{{$no}}">
                            <td class="p-1 bg-light borde-1 border-light"><input class="case" type="checkbox"  id="{{$no}}" /></td>
                            <td>

                               <select name="ItemID[]" id="ItemID_{{$no}}" class="form-select form-control-sm  select2 changesNoo"   >
                                @foreach ($items as $key => $value) 
                    <option value="{{$value->ItemID}}" {{($value->ItemID== $value1->ItemID) ? 'selected=selected':'' }} >{{$value->ItemCode}}-{{$value->ItemName}}-{{$value->Percentage}}</option>
                  @endforeach
                              </select>
                             
                             </td>
                            <td>
                              <input type="text" name="PaxName[]" id="PaxName_{{$no}}" class=" form-control text-uppercase "
                                autocomplete="off" placeholder="PaxName" value="{{$value1->PaxName}}">

                               
                            </td>
                            <td>
                               <select name="PickPoint[]" id="PickPoint_{{$no}}" class="form-select">
                              <option value="Sharja" {{ ('Sharja' == $value1->PickPoint) ? 'selected=selected' : '' }}>Sharja</option>
<option value="Dubai" {{ ('Dubai' == $value1->PickPoint) ? 'selected=selected' : '' }}>Dubai</option>
<option value="Abu Dahbi" {{ ('Abu Dahbi' == $value1->PickPoint) ? 'selected=selected' : '' }}>Abu Dahbi</option>
<option value="Jebel Ali" {{ ('Jebel Ali' == $value1->PickPoint) ? 'selected=selected' : '' }}>Jebel Ali</option>
<option value="Al Ain" {{ ('Al Ain' == $value1->PickPoint) ? 'selected=selected' : '' }}>Al Ain</option>
<option value="Ras Al-Khaimah" {{ ('Ras Al-Khaimah' == $value1->PickPoint) ? 'selected=selected' : '' }}>Ras Al-Khaimah</option>

                                </select>

                             
                            </td>
                            <td>
                                <select name="VisaType[]" id="VisaType_{{$no}}" class="form-select">
                                  <option value="Multi" {{('Multi'== $value1->VisaType) ? 'selected=selected':'' }}>Multi</option>
                                  <option value="Umrah" {{('Umrah'== $value1->VisaType) ? 'selected=selected':'' }}>Umrah</option>
                                </select>

                               
                            </td>
                            <td>
                              <input type="number" name="Fare[]" id="Fare_{{$no}}" class=" form-control changesNo"                                 autocomplete="off"  step="0.01" placeholder="Fare" value="{{$value1->UmrahFare}}">


                            
                            <td>
                               <input type="number" name="Total[]" id="Total_{{$no}}" class=" form-control changesNo "
                                autocomplete="off"  step="0.01" placeholder="Total" value="{{$value1->Total}}">

                                
                                  
                            </td>
                      
                            <td>
                             <input type="number" name="Paid[]" id="Paid_{{$no}}" class=" form-control changesNo totalLinePrice"
                                autocomplete="off"  step="0.01" placeholder="Paid" value="0">

                            </td>
                          </tr>


                          <tr class="bg-light borde-1 border-light " style="height: 40px; font-weight: bolder;" id="ttt_{{$no}}">
                            <td></td>
                            <td>Supplier</td>
                            <td><span>Pax Contact</span><span style="float: right; padding-right: 55px;">Pax Passport</span> </td>
                            <td>Room Type</td>
                            <td>VAT</td>
                            <td>Net Profit</td>
                            <td>Payment in Bus</td>
                            <td>Departure Date</td>
                          </tr>

                        <tr class="bg-light borde-1 border-light " style="height: 40px; font-weight: bolder;" id="tttt_{{$no}}">

                            <td></td>
                            <td> <select name="SupplierID[]" id="SupplierID_{{$no}}" class="form-select select2 changesNo"  
                                onchange="ajax_balance(this.value);">
                                <option value="">Select Supplier</option>
                                @foreach ($supplier as $key => $value) 
                    <option value="{{$value->SupplierID}}" {{($value->SupplierID== $value1->SupplierID) ? 'selected=selected':'' }}>{{$value->SupplierName}}</option>
                  @endforeach
                              </select>
                      </td>
                            <td>    <div class="input-group">
                                            

                           <input type="text" class="form-control"   id="Contact_{{$no}}" name="Contact[]" placeholder="Contact"  value="{{$value1->Contact}}">


                             

                          
                            <input type="text" class="form-control"   id="Passport_{{$no}}" name="Passport[]" placeholder="Passport" value="{{$value1->Passport}}">


                                        </div></td>
                            <td>    <select name="RoomType[]" id="RoomType_{{$no}}" class="form-select">
                                  <option value="Quad" {{('Quad'== $value1->RoomType) ? 'selected=selected':'' }} >Quad </option>
                                  <option value="Triple" {{('Triple'== $value1->RoomType) ? 'selected=selected':'' }} >Triple</option>
                                  <option value="Double" {{('Double'== $value1->RoomType) ? 'selected=selected':'' }} >Double</option>
                                  <option value="Sharing" {{('Sharing'== $value1->RoomType) ? 'selected=selected':'' }} >Sharing</option>
                                </select>
                            </td>
                            <td> <input type="number" name="VAT[]" id="VAT_{{$no}}" class=" form-control vat"  
                                autocomplete="off"  step="0.01" placeholder="VAT" readonly="" value="{{$value1->Taxable}}">
                            </td>
                            <td>  <input type="number" name="Service[]" id="Service_{{$no}}" class=" form-control service "
                                autocomplete="off"  step="0.01" placeholder="Service"  readonly="" value="{{$value1->Service}}">
                            </td>
                            <td>  <input type="number" name="PaymentInBus[]" id="PaymentInBus_{{$no}}"  
                                class=" form-control changesNo" autocomplete="off"
                                step="0.01" placeholder="Payment in bus" value="{{$value1->PaymentInBus}}">
                            </td>
                            <td><input type="Date" name="DepartureDate[]" value="{{$value1->DepartureDate}}" class="form-control"></td>
                          </tr>

                                     <tr class="bg-light borde-1 border-light " style="height: 40px; font-weight: bolder;" id="file_1">
                            <td></td>
                            <td>

 
                              <div class="mt-2 mb-3">
                            <label for="PassportFile_1" class="fw-bolder">Passport </label> <i class="mdi mdi-trash-can-outline text-danger"  style="font-size: 16pt;float:right;margin-top:-7px;" onclick="removepicture('PassportFile_Old_',{{$no}});"></i>

                            <input type="file" class="form-control"  name="PassportFile[]" id="PassportFile_1">
                            <input type="hidden" class="form-control"  name="PassportFile_Old[]" id="PassportFile_Old_{{$no}}" value="{{$value1->PassportFile}}">
                            
                            <br>
                          @if($value1->PassportFile)
    @php
        // Get the file extension
        $extension = strtolower(pathinfo($value1->PassportFile, PATHINFO_EXTENSION));
    @endphp

    @if($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png')
        <!-- Display the image file (jpg, png) -->
        <img src="{{ asset('/').$value1->PassportFile }}" class="rounded avatar-sm" width="50px" height="50px" 
             onclick="openImageModal('{{ asset('/').$value1->PassportFile }}')" id="placeholder_PassportFile_Old_{{ $no }}">
    
    @elseif($extension === 'pdf')
        <!-- Display the PDF icon with a link to open the PDF in a new tab -->
        <a href="{{ asset('/').$value1->PassportFile }}" target="_blank">
            <img src="{{ asset('/pdf_icon.jpg') }}" class="rounded avatar-sm"  id="placeholder_PassportFile_Old_{{ $no }}">
        </a>
    
    @else
        <!-- If the file exists but is not an image or PDF, show a placeholder or handle it accordingly -->
        <img src="{{ asset('/placeholder.jpg') }}" class="rounded avatar-sm" width="50px" height="50px" id="placeholder_PassportFile_Old_{{ $no }}">
    @endif

@else
    <!-- If no file is available, display a placeholder image -->
    <img src="{{ asset('/placeholder.jpg') }}" class="rounded avatar-sm" width="50px" height="50px" id="placeholder_PassportFile_Old_{{ $no }}">
@endif


                            </div>



                            </td>
                           

                            <td>

                                <div class="mt-2 mb-3">

                            <label for="PassportFile_1" class="fw-bolder">Emirate ID Front</label>
                            <i class="mdi mdi-trash-can-outline text-danger" style="font-size: 16pt;float:right;margin-top:-7px;" onclick="removepicture('EmirateIDFileFront_Old_',{{$no}});"></i>
                            <input type="file" class="form-control"  name="EmirateIDFileFront[]" id="EmirateIDFileFront_1">
                          <input type="hidden" class="form-control"  name="EmirateIDFileFront_Old[]" value="{{$value1->EmirateIDFileFront}}" id="EmirateIDFileFront_Old_{{$no}}">

                              <br>
                       <!--      @if($value1->EmirateIDFileFront)
                            <img src="{{asset('/').$value1->EmirateIDFileFront}}" class="rounded avatar-sm"  width="50px" height="50px" onclick="openImageModal('{{asset('/').$value1->EmirateIDFileFront}}')" id="placeholder_EmirateIDFileFront_Old_{{$no}}">
                            @else
                            <img src="{{asset('/placeholder.jpg')}}" class="rounded avatar-sm"  width="50px" height="50px" id="placeholder_EmirateIDFileFront_Old_{{$no}}">
                            @endif
 -->

      @if($value1->EmirateIDFileFront)
    @php
        // Get the file extension
        $extension = strtolower(pathinfo($value1->EmirateIDFileFront, PATHINFO_EXTENSION));
    @endphp

    @if($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png')
        <!-- Display the image file (jpg, png) -->
        <img src="{{ asset('/').$value1->EmirateIDFileFront }}" class="rounded avatar-sm" width="50px" height="50px" 
             onclick="openImageModal('{{ asset('/').$value1->EmirateIDFileFront }}')" id="placeholder_EmirateIDFileFront_Old_{{ $no }}">
    
    @elseif($extension === 'pdf')
        <!-- Display the PDF icon with a link to open the PDF in a new tab -->
        <a href="{{ asset('/').$value1->EmirateIDFileFront }}" target="_blank">
            <img src="{{ asset('/pdf_icon.jpg') }}" class="rounded avatar-sm"  id="placeholder_EmirateIDFileFront_Old_{{ $no }}">
        </a>
    
    @else
        <!-- If the file exists but is not an image or PDF, show a placeholder or handle it accordingly -->
        <img src="{{ asset('/placeholder.jpg') }}" class="rounded avatar-sm" width="50px" height="50px" id="placeholder_EmirateIDFileFront_Old_{{ $no }}">
    @endif

@else
    <!-- If no file is available, display a placeholder image -->
    <img src="{{ asset('/placeholder.jpg') }}" class="rounded avatar-sm" width="50px" height="50px" id="placeholder_EmirateIDFileFront_Old_{{ $no }}">
@endif




                            </div>
                              


                            </td>
                              <td colspan="2">
                              
                                <div class="mt-2 mb-3">
                            <label for="PassportFile_1" class="fw-bolder">Emirate ID Back</label>
                            <i class="mdi mdi-trash-can-outline text-danger" style="font-size: 16pt;float:right;margin-top:-7px;" onclick="removepicture('EmirateIDFileBack_Old_',{{$no}});"></i>
                            <input type="file" class="form-control"  name="EmirateIDFileBack[]" id="EmirateIDFileBack_1">
                            <input type="hidden" class="form-control"  name="EmirateIDFileBack_Old[]" value="{{$value1->EmirateIDFileBack}}" id="EmirateIDFileBack_Old_{{$no}}">

                              <br>
                        <!--     @if($value1->EmirateIDFileBack)
                            <img src="{{asset('/').$value1->EmirateIDFileBack}}" class="rounded avatar-sm"  width="50px" height="50px" onclick="openImageModal('{{asset('/').$value1->EmirateIDFileBack}}')"  id="placeholder_EmirateIDFileBack_Old_{{$no}}">
                            @else
                            <img src="{{asset('/placeholder.jpg')}}" class="rounded avatar-sm"  width="50px" height="50px"  id="placeholder_EmirateIDFileBack_Old_{{$no}}">
                            @endif -->



      @if($value1->EmirateIDFileBack)
    @php
        // Get the file extension
        $extension = strtolower(pathinfo($value1->EmirateIDFileBack, PATHINFO_EXTENSION));
    @endphp

    @if($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png')
        <!-- Display the image file (jpg, png) -->
        <img src="{{ asset('/').$value1->EmirateIDFileBack }}" class="rounded avatar-sm" width="50px" height="50px" 
             onclick="openImageModal('{{ asset('/').$value1->EmirateIDFileBack }}')" id="placeholder_EmirateIDFileBack_Old_{{ $no }}">
    
    @elseif($extension === 'pdf')
        <!-- Display the PDF icon with a link to open the PDF in a new tab -->
        <a href="{{ asset('/').$value1->EmirateIDFileBack }}" target="_blank">
            <img src="{{ asset('/pdf_icon.jpg') }}" class="rounded avatar-sm"  id="placeholder_EmirateIDFileBack_Old_{{ $no }}">
        </a>
    
    @else
        <!-- If the file exists but is not an image or PDF, show a placeholder or handle it accordingly -->
        <img src="{{ asset('/placeholder.jpg') }}" class="rounded avatar-sm" width="50px" height="50px" id="placeholder_EmirateIDFileBack_Old_{{ $no }}">
    @endif

@else
    <!-- If no file is available, display a placeholder image -->
    <img src="{{ asset('/placeholder.jpg') }}" class="rounded avatar-sm" width="50px" height="50px" id="placeholder_EmirateIDFileBack_Old_{{ $no }}">
@endif



                            </div>

                            </td>
                            <td colspan="2">
                              
                                <div class="mt-2 mb-3">
                            <label for="PassportFile_1" class="fw-bolder">Picture</label>
                            <i class="mdi mdi-trash-can-outline text-danger" style="font-size: 16pt;float:right;margin-top:-7px;" onclick="removepicture('PictureFile_Old_',{{$no}});"></i>

                            <input type="file" class="form-control"  name="PictureFile[]" id="PictureFile_1">
                            <input type="hidden" class="form-control"  name="PictureFile_Old[]" value="{{$value1->PictureFile}}" id="PictureFile_Old_{{$no}}">

                              <br>
                       <!--      @if($value1->PictureFile)
                            <img src="{{asset('/').$value1->PictureFile}}" class="rounded avatar-sm"    width="50px" height="50px" onclick="openImageModal('{{asset('/').$value1->PictureFile}}')"  id="placeholder_PictureFile_Old_{{$no}}">
                            @else
                            <img src="{{asset('/placeholder.jpg')}}" class="rounded avatar-sm"  width="50px" height="50px" id="placeholder_PictureFile_Old_{{$no}}">
                            @endif -->


      @if($value1->PictureFile)
    @php
        // Get the file extension
        $extension = strtolower(pathinfo($value1->PictureFile, PATHINFO_EXTENSION));
    @endphp

    @if($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png')
        <!-- Display the image file (jpg, png) -->
        <img src="{{ asset('/').$value1->PictureFile }}" class="rounded avatar-sm" width="50px" height="50px" 
             onclick="openImageModal('{{ asset('/').$value1->PictureFile }}')" id="placeholder_PictureFile_Old_{{ $no }}">
    
    @elseif($extension === 'pdf')
        <!-- Display the PDF icon with a link to open the PDF in a new tab -->
        <a href="{{ asset('/').$value1->PictureFile }}" target="_blank">
            <img src="{{ asset('/pdf_icon.jpg') }}" class="rounded avatar-sm"  id="placeholder_PictureFile_Old_{{ $no }}">
        </a>
    
    @else
        <!-- If the file exists but is not an image or PDF, show a placeholder or handle it accordingly -->
        <img src="{{ asset('/placeholder.jpg') }}" class="rounded avatar-sm" width="50px" height="50px" id="placeholder_PictureFile_Old_{{ $no }}">
    @endif

@else
    <!-- If no file is available, display a placeholder image -->
    <img src="{{ asset('/placeholder.jpg') }}" class="rounded avatar-sm" width="50px" height="50px" id="placeholder_PictureFile_Old_{{ $no }}">
@endif



                            </div>

                            </td>
                          
                           
                            <td style="vertical-align: top;">
                              <label for="" class="fw-bolder mt-2">Deduction Charges</label>
                              <input type="number" name="deduction[]" id="deduction_{{$no}}" class="form-control changesNo" onblur="this.readonly=true;">

                            </td>                          
                            
                            
                            </tr>





@endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="row mt-1 mb-2" style="margin-left: 29px;">
                    <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                      <button class="btn btn-danger delete" type="button"><i
                          class="bx bx-trash align-middle font-medium-3 me-25"></i>Delete</button>
                      <button class="btn btn-success addmore" type="button"><i
                          class="bx bx-list-plus align-middle font-medium-3 me-25"></i> Add More</button>

                    </div>

                    <div class='col-xs-5 col-sm-3 col-md-3 col-lg-3  '>
                      <div id="result"></div>

                    </div>
                    <br>

                  </div>


                  <div class="row">

                    <div class="col-lg-8 col-12  ">
                      <h5>Notes: </h5>


                      <textarea class="form-control" rows='5' name="remarks" id="notes"
                      placeholder="Your Notes">{{$invoice_mst[0]->Note}}</textarea>


                            <div class="col-md-4">
                        <div class="mb-3">
                        <label for="basicpill-firstname-input">Attachment </label>
                        <input type="file" class="form-control" name="Document" value="" class="form-control">
                        </div>


                        <?php 
    
                        if($invoice_mst[0]->Document)
                        {


                          ?>

                          <a href="{{asset('/').$invoice_mst[0]->Document}}" target="_blank" class="btn btn-light">Downlaod File</a>

                          <?php


                        }


                         ?>

                        </div>
                        

                      <div class="mt-2">
                        <button type="submit" id="submitBtn" class="btn-disable btn btn-success w-lg float-right">Save</button>
                        <a href="{{URL('/Invoice')}}" class="btn btn-secondary w-lg float-right">Cancel</a>

                      </div>


                    </div>


                    <div class="col-lg-4 col-12 ">
                      <form class="form-inline">
                   
                      
                        <div class="form-group">

                          <label>
                            <h5>Total: &nbsp;</h5>
                          </label>
                          <div class="input-group">
                            <span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>
                            <input type="number" name="GrandTotal" id="GrandTotal" class="form-control" step="0.01" id="totalAftertax"
                              placeholder="Total" onkeypress="return IsNumeric(event);" ondrop="return false;"
                              onpaste="return false;" value="{{$invoice_mst[0]->Total}}">
                          </div>
                        </div>
                        
                        
                         <div class="form-group mt-1 d-none">
                          <label>
                            <h5>Amount Paid: &nbsp;</h5>
                          </label>
                          <div class="input-group">
                            <span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>
                             <input type="number" class="form-control" id="amountPaid" name="amountPaid"
                              placeholder="Amount Paid" onkeypress="return IsNumeric(event);" ondrop="return false;"
                              onpaste="return false;" step="0.01" value="0">

                          </div>
                        </div>

                        <div class="form-group mt-1 d-none">

                          <label>
                            <H5>Amount Due: &nbsp;</H5>
                          </label>
                          <div class="input-group">
                            <span class="input-group-text bg-light">{{env('APP_CURRENCY')}}</span>
                            <input type="number" class="form-control amountDue" name="amountDue" id="amountDue"
                              placeholder="Amount Due" onkeypress="return IsNumeric(event);" ondrop="return false;"
                              onpaste="return false;" step="0.01" value="{{$invoice_mst[0]->Balance}}">
                          </div>
                        </div>

                    </div>
                  </div>
                  <div>



                  </div>






                  <!--  <div class='row'>
          <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
            <div class="well text-center">
          <h2>Back TO Tutorial: <a href="#"> Invoice System </a> </h2>
        </div>
          </div>
        </div>   -->



                </div>
              </div>
            </div>





          </form>



        </div>
      </div>

    </div>
  </div>

</div>
</div>
</div>














<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>



<script>
  
function removepicture(location,id)
{

 if (confirm('Deleting picture ')) 
 {
  $('#'+location+id).val('');  

  $('#placeholder_'+location+id).attr('src', "{{URL('/placeholder.jpg')}}");


 }  
  
}
// EmirateIDFileBack_Old EmirateIDFileBack PictureFile
 

</script>


<script>
 




var i = $('table tr').length;

    $(".addmore").on('click', function() {
        var html = `    

       <thead>
                          <tr style="height: 40px;" id="t_${i}">
                            <th width="2%" colspan="8" /><hr></th>
                           
                          </tr>
                        </thead>



             <thead>
                          <tr class="bg-light borde-1 border-light " style="height: 40px;" id="ttttt_${i}">
                            <th width="2%" class="p-1"></th>
                            <th width="12%">Item</th>
                             <th width="10%">PAX Name</th>
                             <th width="5%">Pick Point</th>
                            <th width="5%">Package</th>
                            <th width="5%">Cost</th>
                            <th width="5%">selling price</th>
                            
                            <th width="7%">Amount Paid</th>
                          </tr>
                        </thead>
                          <tr class="p-3" style="vertical-align: top;" id="tt_${i}">
                            <td class="p-1 bg-light borde-1 border-light"><input class="case" type="checkbox" id="${i}" /></td>
                            <td>

                              <select name="ItemID[]" id="ItemID_${i}" class="form-select form-control-sm  select2 changesNoo"   >
                                 @foreach ($items as $key => $value)
                                <option  value="{{$value->ItemID}}">
                                  {{$value->ItemCode}}-{{$value->ItemName}}-{{$value->Percentage}}</option>
                                @endforeach
                              </select>
                             
                             </td>
                            <td>
                              <input type="text" name="PaxName[]" id="PaxName_${i}" class=" form-control text-uppercase "
                                autocomplete="off" placeholder="PaxName" >
                               
                            </td>
                            <td>
                               <select name="PickPoint[]" id="PickPoint_1" class="form-select">
                                  <option value="Sharja">Sharja</option>
                                  <option value="Dubai">Dubai</option>
                                  <option value="Abu Dahbi">Abu Dahbi</option>
                                  <option value="Jebel Ali">Jebel Ali</option>
                                  <option value="Al Ain">Al Ain</option>
                                  <option value="Ras Al-Khaimah">Ras Al-Khaimah</option>
                                  
                                </select>
                             
                            </td>
                            <td>
                                 <select name="VisaType[]" id="VisaType_${i}" class="form-select">
                                  <option value="Multi">Multi</option>
                                  <option value="Umrah">Umrah</option>
                                </select>
                               
                            </td>
                            <td>
                              <input type="number" name="Fare[]" id="Fare_${i}" class=" form-control changesNo"                                 autocomplete="off"  step="0.01" placeholder="Fare" value="0">

                            
                            <td>
                              <input type="number" name="Total[]" id="Total_${i}" class=" form-control changesNo "
                                autocomplete="off"  step="0.01" placeholder="Total" value="0">

                                
                                  
                            </td>
                      
                            <td>
                             <input type="number" name="Paid[]" id="Paid_${i}" class=" form-control changesNo totalLinePrice"
                                autocomplete="off"  step="0.01" placeholder="Paid" value="0">
                            </td>
                          </tr>


                          <tr class="bg-light borde-1 border-light " style="height: 40px; font-weight: bolder;" id="ttt_${i}">
                            <td></td>
                            <td>Supplier</td>
                            <td><span>Pax Contact</span><span style="float: right; padding-right: 55px;">Pax Passport</span></td>
                            <td>Room Type</td>
                            <td>VAT</td>
                            <td>Net Profit</td>
                            <td>Payment in Bus</td>
                            <td>Departure Date</td>
                          </tr>

                        <tr class="bg-light borde-1 border-light " style="height: 40px; font-weight: bolder;" id="tttt_${i}">

                            <td></td>
                            <td> <select name="SupplierID[]" id="SupplierID_${i}" class="form-select select2 changesNo"  
                                onchange="ajax_balance(this.value);">
                                <option value="">Select Supplier</option>
                                @foreach ($supplier as $key => $value)
                                <option value="{{$value->SupplierID}}">{{$value->SupplierName}}</option>
                                @endforeach
                              </select></td>
                            <td>    <div class="input-group">
                                            

                                            <input type="text" class="form-control"   id="Contact_${i}" name="Contact[]" placeholder="Contact">

                                             

                                          
                                            <input type="text" class="form-control"   id="Passport_${i}" name="Passport[]" placeholder="Passport">

                                        </div></td>
                            <td>   <select name="RoomType[]" id="RoomType_${i}" class="form-select">
                                  <option value="Quad">Quad</option>
                                  <option value="Triple">Triple</option>
                                  <option value="Double">Double</option>
                                  <option value="Sharing">Sharing</option>
                                </select></td>
                            <td> <input type="number" name="VAT[]" id="VAT_${i}" class=" form-control vat"  
                                autocomplete="off"  step="0.01" placeholder="VAT" readonly="" value="0"></td>
                            <td> <input type="number" name="Service[]" id="Service_${i}" class=" form-control service "
                                autocomplete="off"  step="0.01" placeholder="Service"  readonly="" value="0"></td>
                            <td> <input type="number" name="PaymentInBus[]" id="PaymentInBus_${i}"  
                                class=" form-control changesNo" autocomplete="off"
                                step="0.01" placeholder="Payment in bus" value="0"></td>
                            <td><input type="Date" name="DepartureDate[]" id="DepartureDate_${i}" value="" class="form-control"></td>
                          </tr>


                            <tr class="bg-light borde-1 border-light " style="height: 40px; font-weight: bolder;" id="file_${i}">
                            <td></td>
                            <td>

 
                              <div class="mt-2 mb-3">
                            <label for="PassportFile_${i}" class="fw-bolder">Passport</label>
                            <input type="file" class="form-control"  name="PassportFile[]" id="PassportFile_${i}">
                            </div>



                            </td>
                           

                            <td>

                                <div class="mt-2 mb-3">
                            <label for="PassportFile_${i}" class="fw-bolder">Emirate ID Front</label>
                            <input type="file" class="form-control"  name="EmirateIDFileFront[]" id="EmirateIDFileFront_${i}">
                            </div>
                              


                            </td>
                              <td colspan="2">
                              
                                <div class="mt-2 mb-3">
                            <label for="PassportFile_${i}" class="fw-bolder">Emirate ID Back</label>
                            <input type="file" class="form-control"  name="EmirateIDFileBack[]" id="EmirateIDFileBack_${i}">
                            </div>

                            </td>
                            <td colspan="2">
                              
                                <div class="mt-2 mb-3">
                            <label for="PassportFile_${i}" class="fw-bolder">Picture</label>
                            <input type="file" class="form-control"  name="PictureFile[]" id="PictureFile_${i}">
                            </div>

                            </td>
                          
                           
                            <td></td>
                          </tr>` ;
        $('table').append(html);
       $('#ItemID_' + i).select2();
      $('#SupplierID_' + i).select2();
        i++;



       
    });















//to check all checkboxes
$(document).on('change','#check_all',function(){
  $('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

 



$(".delete").on('click', function() {
  
  $('.case:checkbox:checked').each(function() {
    var checkboxId = $(this).attr('id');  // Get the ID of the checked checkbox
    console.log(checkboxId);              // Output the ID (e.g., c_1)
    
 

  $("#t_"+checkboxId).remove();
  $("#tt_"+checkboxId).remove();
  $("#ttt_"+checkboxId).remove();
  $("#tttt_"+checkboxId).remove();
  $("#ttttt_"+checkboxId).remove();
  $("#file_"+checkboxId).remove();


grandtotal();

 
  });
});



 

//price change
$(document).on('blur','.changesNo',function(){

 
 

   
  id_arr = $(this).attr('id');
 
  id = id_arr.split("_");

 

  Fare = $('#Fare_'+id[1]).val();
  Paid = $('#Paid_'+id[1]).val();
  Total = $('#Total_'+id[1]).val();
  deduction = $('#deduction_'+id[1]).val() || 0 ;

 console.log(deduction);  

  Service = parseFloat(Total) - parseFloat(Fare);
  // $('#Service_'+id[1]).val(Service);

  PaymentInBus = parseFloat(Total) - parseFloat(Paid);
  

  $('#PaymentInBus_'+id[1]).val(PaymentInBus);
 
 
 
  if($('#Fare_'+id[1]).val() == "")
  {
      Fare=0;
  }
   
    if($('#discount_'+id[1]).val() == "")
  {
      Discount=0;
  }


TaxAmount = ( (5*parseFloat(Service))/(100+5)  ).toFixed(2);
$('#VAT_'+id[1]).val(TaxAmount);

Service = parseFloat(Service) - parseFloat(TaxAmount);

$('#Service_'+id[1]).val(Service);

$('#Paid_'+id[1]).val(Paid - parseFloat(deduction) );


grandtotal();


 
});

function grandtotal()
{
    GrandTotal = 0 ; 
  $('.totalLinePrice').each(function(){
    if($(this).val() != '' )GrandTotal += parseFloat( $(this).val() );
  });


  $('#GrandTotal').val(GrandTotal);
 


  gservice = 0 ; 
  $('.service').each(function(){
    if($(this).val() != '' )gservice += parseFloat( $(this).val() );
  });


  vat= 0 ; 
  $('.vat').each(function(){
    if($(this).val() != '' )vat += parseFloat( $(this).val() );
  });


  $('#Paid').val(parseFloat(gservice) +  parseFloat(vat) ).toFixed(2);
}
//////////

// $(document).on(' blur','.totalLinePrice',function(){

 

//   id_arr = $(this).attr('id');
//   id = id_arr.split("_");

 
// Fare = $('#Fare_'+id[1]).val();

//   total = $('#total_'+id[1]).val();


//   Tax = $('#Taxable_'+id[1]).val();





 
// Profit = ( parseFloat(total)-parseFloat(Fare)).toFixed(2);

   

//    if($('#Taxable_'+id[1]).val() == "")
//   {
//       Tax=0;
//   }
// $('#Service_'+id[1]).val( parseFloat(Profit) - ( parseFloat(Profit/100)*parseFloat(Tax)).toFixed(2) );

//    $('#quantity_'+id[1]).val( ( parseFloat(Profit/100)*parseFloat(Tax)).toFixed(2) );
//    // Profit = (parseFloat(total)-parseFloat(Fare)).toFixed(2) ;

//     // Tax = ;

//    // Service = (parseFloat(Proft)-parseFloat(Tax)).toFixed(2) ;

//    // alert(Profit+Tax+Service);

// // $('#quantity'+id[1]).val( Tax );
// // $('#Service_'+id[1]).val( Service );


 
// });

$(document).on('change','.changesNoo',function(){

 

  id_arr = $(this).attr('id');
  id = id_arr.split("_");

val = $('#ItemID0_'+id[1]).val().split("|");


// alert($('#ItemID0_'+id[1]).val());
$('#Taxable_'+id[1]).val( val[1]  );
$('#ItemID_'+id[1]).val( val[0]  );
  
 

 
});

////////////////////////////////////////////


$(document).on('change keyup blur','#tax',function(){
  calculateTotal();
});

//total price calculation 
function calculateTotal(){
  subTotal = 0 ; total = 0; 
  $('.totalLinePrice').each(function(){
    if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
  });
  $('#subTotal').val( subTotal.toFixed(2) );
  tax = $('#tax').val();
  if(tax != '' && typeof(tax) != "undefined" ){
    taxAmount = subTotal * ( parseFloat(tax) /100 );
    $('#taxAmount').val(taxAmount.toFixed(2));
    total = subTotal + taxAmount;
  }else{
    $('#taxAmount').val(0);
    total = subTotal;
  }
  $('#totalAftertax').val( total.toFixed(2) );
  calculateAmountDue();
}

$(document).on('change keyup blur','#amountPaid',function(){
  calculateAmountDue();
});

//due amount calculation
function calculateAmountDue(){
  amountPaid = $('#amountPaid').val();
  total = $('#totalAftertax').val();
  if(amountPaid != '' && typeof(amountPaid) != "undefined" ){
    amountDue = parseFloat(total) - parseFloat( amountPaid );
    $('.amountDue').val( amountDue.toFixed(2) );
  }else{
    total = parseFloat(total).toFixed(2);
    $('.amountDue').val( total);
  }
}


//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    console.log( keyCode );
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}

//datepicker
$(function () {
  $.fn.datepicker.defaults.format = "dd-mm-yyyy";
    $('#invoiceDate').datepicker({
        startDate: '-3d',
        autoclose: true,
        clearBtn: true,
        todayHighlight: true
    });
});




</script>





<!-- ajax trigger -->
<script>
  function ajax_balance(SupplierID) {
      
       // alert($("#csrf").val());
 
$('#result').prepend('')
$('#result').prepend('<img id="theImg" src="{{asset('assets/images/ajax.gif')}}" />')
 
       var SupplierID = SupplierID;

       // alert(SupplierID);
       if(SupplierID!=""  ){
        /*  $("#butsave").attr("disabled", "disabled"); */
        // alert(SupplierID);
          $.ajax({
              url: "{{URL('/Ajax_Balance')}}",
              type: "POST",
              data: {
                  _token: $("#csrf").val(),
                   SupplierID: SupplierID,
                 
              },
              cache: false,
              success: function(data){
            

              
                    $('#result').html(data);
           
                 
                  
              }
          });
      }
      else{
          alert('Please Select Branch');
      }

      
      

  }
 
 



 

$(document).on('keyup','#Phone',function(){
  ajax_party_validate();
});




   
   function ajax_party_validate() {
      
       // alert($("#csrf").val());
 
  
       var Phone = $('#Phone').val();

       // alert(SupplierID);
       if(Phone!=""  ){
        /*  $("#butsave").attr("disabled", "disabled"); */
        // alert(SupplierID);
          $.ajax({
              url: "{{URL('/ajax_party_validate')}}",
              type: "POST",
              data: {
                  _token: $("#csrf").val(),
                   Phone: Phone,
                 
              },
              cache: false,
              success: function(data){
            

 
                if (data.total == 0) {
                            
                            $('#Phone').removeClass('border-red').addClass('border-green');
                            $('#submitButton').removeAttr('disabled');
                            

                                $('#email-error').text('validated successfully');
                                $("#email-error").css("color", "green");
                                $('#email-error').show();

                        } else {
                            $('#submitButton').attr('disabled','disabled');
                            $('#Phone').removeClass('border-green').addClass('border-red');
                            $("#email-error").css("color", "red");
                            $('#email-error').text('Phone no already exists');
                             $('#email-error').show();
                        }
                    $('#result').html(data);
              }
          });
      }
      else{
           $('#email-error').text('Phone number is required');
           $("#email-error").css("color", "red");
            $('#email-error').show();
      }

  }
 
</script>


<script>
  $(document).ready(function() {



  $('#PartyID').select2({
        allowClear: true,
        placeholder: 'This is my placeholder',    
          language: {
              noResults: function() {
                // console.log('no record ounf');
              return `<button style="width: 100%" type="button"
              class="btn btn-primary" 
              onClick='task()'>+ Add New Customer</button>
              </li>`;
              }
          },
        
          escapeMarkup: function (markup) {
              return markup;
          }
      });


      });



    
  function task()
  {
  // alert("Hello world! ");

 
            $('#PartyID').select2('close');

$('input[name="PartyName"]').focus();
$('#exampleModal').modal('show');

  }
    


</script>


<script>
  $(document).ready(function() {
        $('#submitButton').click(function() {
             var isValid = true;
            
            // Validate the name field
            var PartyName = $('#PartyName').val().trim();
            if (PartyName === '') {
                $('#name').addClass('error');
                $('#name-error').show();
                isValid = false;
            } else {
                $('#name').removeClass('error');
                $('#name-error').hide();
            }
            
            // Validate the email field
            var Phone = $('#Phone').val().trim();
             if (Phone === '') {
                $('#email').addClass('error');
                $('#email-error').show();
                isValid = false;
            } else {
                $('#email').removeClass('error');
                $('#email-error').hide();
            }
            



            // If the form is valid, make the AJAX request
            if (isValid) {

              // alert('vvv');
                $.ajax({
                    url: '{{URL("/ajax_party_save")}}',  // Replace with your server endpoint
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Laravel's CSRF token
                        PartyName: PartyName,
                        Phone: Phone,
                    },
                    success: function(response) {
                        // Handle the response from the server


                        // alert('Form submitted successfully!');




                        console.log(response.PartyID);
                        console.log(response.PartyName);
                        console.log(response.Phone);


                        $("#PartyID").append('<option value=' + response.PartyID + ' selected >' + response.PartyID +'-'+ response.PartyName+'-'+ response.Phone +'</option>');
                        
                        $('#exampleModal').modal('hide');
                        
                        checkSelection();

                    },






                    error: function(xhr, status, error) {
                        // Handle any errors
                        alert('An error occurred: ' + error);
                    }
                });
            }
        });



$(document).on('keyup','#PartyName',function(){

var isValid = true;
            
            // Validate the name field
            var nameValue = $('#PartyName').val().trim();
            if (nameValue === '') {
                $('#name').addClass('error');
                $('#name-error').show();
                isValid = false;
            } else {
                $('#name').removeClass('error');
                $('#name-error').hide();
            }


  
});   



$(document).on('keyup','#Phone',function(){

         var isValid = true;
            
            // Validate the email field
            var emailValue = $('#Phone').val().trim();
            
            if (emailValue === '') {
                $('#email').addClass('error');
                $('#email-error').show();
                isValid = false;
            } else {
                $('#email').removeClass('error');
                $('#email-error').hide();
            }

  
});   



    });


</script>


<script>
  $(document).ready(function() {
    // Initialize select2
    $('#searchField').select2();

    // Event listener for input on select2 search field
    $(document).on('input', '.select2-search__field', function() {
        // Get the value from the select2 search field
        var searchValue = $(this).val();
        
        // Assign the value to the new text field
        $('#Phone').val(searchValue);
    });
});
</script>
<script>
$(document).ready(function() {
      const $paymentModeSelect = $('#PaymentMode');
    const $salemanIDSelect = $('#SalemanID');
    const $partyIDSelect = $('#PartyID');
    const $buttons = $('.btn-disable');
    const $paymentModeError = $('#PaymentModeError');
    const $salemanError = $('#SalemanError');
    const $partyError = $('#PartyError');


    // Initialize state
    checkSelection();

    // Event listeners for select changes
    $paymentModeSelect.on('change', checkSelection);
    $salemanIDSelect.on('change', checkSelection);
    $partyIDSelect.on('change', checkSelection);
});

  </script>
<script>

function checkSelection() {
    const $paymentModeSelect = $('#PaymentMode');
    const $salemanIDSelect = $('#SalemanID');
    const $partyIDSelect = $('#PartyID');
    const $buttons = $('.btn-disable');
    const $paymentModeError = $('#PaymentModeError');
    const $salemanError = $('#SalemanError');
    const $partyError = $('#PartyError');

    // Function to check if all required fields are selected
   
        const paymentModeValue = $paymentModeSelect.val();
        const salemanIDValue = $salemanIDSelect.val();
        const partyIDValue = $partyIDSelect.val();

        // Check Payment Mode selection
        if (paymentModeValue !== "") {
            $paymentModeError.hide();
        } else {
            $paymentModeError.show();
        }

        // Check Saleman ID selection
        if (salemanIDValue !== "") {
            $salemanError.hide();
        } else {
            $salemanError.show();
        }

        // Check Party ID selection
        if (partyIDValue !== "") {
            $partyError.hide();
        } else {
            $partyError.show();
        }

        // Enable/disable buttons based on all conditions being met
        if (paymentModeValue !== "" && salemanIDValue !== "" && partyIDValue !== "") {
            $buttons.prop('disabled', false);
        } else {
            $buttons.prop('disabled', true);
        }
    }
    
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

<!-- uplaoding ajax without progress working -->
<!-- <script>
    $('#umrahForm').on('submit', function(e) {
        // alert('dd');
        e.preventDefault();
        const btn = $("#submitBtn");
        let formData = new FormData($("#umrahForm")[0]);
        $.ajax({
            type: "POST",
            url: "{{URL('/UmrahUpdate')}}",
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            enctype: "multipart/form-data",
            beforeSend: function() {
                btn.prop('disabled', true);
                btn.html('Processing');
            },
            success: function(res) {
                if (res.success === true) {
                    btn.prop('disabled', false);
                    btn.html('Save');
                    notyf.success({
                        message: res.message,
                        duration: 3000
                    });

                    $('#umrahForm')[0].reset();
                    $('html, body').animate({scrollTop: 0}, 800);

                    setTimeout(function() {
                        window.location.href = res.redirect_url;
                    }, 1000);
                } else {
                    btn.prop('disabled', false);
                    btn.html('Save');

                    notyf.error({
                        message: res.message,
                        duration: 3000
                    });
 

                }
            },
            error: function(e) {
                btn.prop('disabled', false);
                btn.html('Save');
            }
        });
    });
</script> -->
  
<!-- <script>
  $(document).ready(function() {
      $('#PartyID').select2({
          allowClear: true,
          placeholder: 'Select a party',    
          minimumInputLength: 2,  // Start searching after 2 characters
          ajax: {
              url: '{{URL("/search-party")}}',
              dataType: 'json',
              delay: 250,  // Wait 250ms before sending the request
              data: function (params) {
                  return {
                      q: params.term  // Search term from the user input
                  };
              },
              processResults: function (data) {
                  return {
                      results: $.map(data, function (item) {
                          return {
                              id: item.PartyID,
                              text: item.PartyID + '-' + item.PartyName + '-' + item.Phone
                          }
                      })
                  };
              },
              cache: true
          },
          language: {
              noResults: function() {
                return `<button style="width: 100%" type="button"
                          class="btn btn-primary" 
                          onClick='task()'>+ Add New Customer</button>`;
              }
          },
          escapeMarkup: function (markup) {
              return markup;
          }
      });
  });

  function task()
  {
  // alert("Hello world! ");

 
            $('#PartyID').select2('close');

$('input[name="PartyName"]').focus();
$('#exampleModal').modal('show');

  }


</script> -->


  <div id="progressModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Uploading Files</h5>
                <button type="button" class="bclose" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="progress" style="height: 0.925rem !important;">
                    <div id="uploadProgress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $('#umrahForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $("#submitBtn");
        let formData = new FormData($("#umrahForm")[0]);

        // Validate form on the server side before uploading
        $.ajax({
            type: "POST",
            url: "{{URL('/UmrahValidate1')}}", // Server-side validation route
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            beforeSend: function() {
                btn.prop('disabled', true);
                btn.html('Validating...');
            },
            success: function(res) {
                if (res.success) {
                    // If validation passes, proceed with the file upload
                    uploadFile(formData);
                } else {
                    // Show validation error message
                    notyf.error({
                        message: res.message,
                        duration: 3000
                    });
                    btn.prop('disabled', false);
                    btn.html('Save');
                }
            },
            error: function(e) {
                // Handle validation error
                notyf.error({
                    message: 'Validation failed. Please check the form and try again.',
                    duration: 3000
                });
                btn.prop('disabled', false);
                btn.html('Save');
            }
        });
    });

    // Function to handle file upload after validation passes
    function uploadFile(formData) {
        const btn = $("#submitBtn");

        // Show the modal for progress
        $('#progressModal').modal('show');

        $.ajax({
            type: "POST",
            url: "{{URL('/UmrahSave')}}", // Your original file upload route
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            beforeSend: function() {
                btn.prop('disabled', true);
                btn.html('Processing...');
            },
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        const percentComplete = (evt.loaded / evt.total) * 100;
                        $('#uploadProgress').css('width', percentComplete + '%');
                        $('#uploadProgress').html(Math.round(percentComplete) + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(res) {
                btn.prop('disabled', false);
                btn.html('Save');
                $('#uploadProgress').css('width', '100%');
                $('#uploadProgress').html('Upload Complete');
                notyf.success({
                    message: res.message,
                    duration: 3000
                });

                $('#umrahForm')[0].reset();
                $('html, body').animate({scrollTop: 0}, 800);

                setTimeout(function() {
                    $('#progressModal').modal('hide'); // Hide modal after processing
                    window.location.href = res.redirect_url;
                }, 1000);
            },
            error: function(e) {
                btn.prop('disabled', false);
                btn.html('Save');
                $('#uploadProgress').css('width', '0%');
                $('#uploadProgress').html('Upload Failed');
                $('#progressModal').modal('hide'); // Hide modal if error
            }
        });
    }
</script>

<!-- END: Content-->

@endsection