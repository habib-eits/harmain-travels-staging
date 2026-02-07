@extends('template.tmp')

@section('title')


@section('content')
<div class="main-content">

  <div class="page-content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">

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


@if(count($company)<=0)

<div class="card">
            <div class="card-body">
              <form action="{{URL('/SaveCompany')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div>
                  <div>

                    <h4 class="card-title">Add Company</h4>
                    <p class="card-title-desc"></p>



                    <input type="hidden" name="CompanyID">

                    <div class="mb-1 row">
                      <label for="example-email-input" class="col-md-2 col-form-label fw-bold ">Company Name</label>
                      <div class="col-md-4">
                      <input class="form-control" type="text" name="Name">
                      </div>
                    </div>


                     <div class="mb-1 row">
                      <label for="example-email-input" class="col-md-2 col-form-label fw-bold "> Name (Next Line)</label>
                      <div class="col-md-4">
                      <input class="form-control" type="text" name="Name2">
                      </div>
                    </div>

                    <div class="mb-1 row">
                      <label for="example-email-input" class="col-md-2 col-form-label fw-bold text-danger">TRN # </label>
                      <div class="col-md-4">
                      <input class="form-control" type="text" name="TRN">
                      </div>
                    </div>


                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Email</label>
                      <div class="col-md-4">
                        <input class="form-control" type="text" name="Email">
                      </div>

                    </div>


                     <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Website</label>
                      <div class="col-md-4">
                        <input class="form-control" type="text" name="Website">
                      </div>

                    </div>

                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Phone</label>
                      <div class="col-md-4">
                        <input class="form-control" type="text" name="Contact">
                      </div>

                    </div>

                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Mobile</label>
                      <div class="col-md-4">
                        <input class="form-control" type="text" name="Mobile">
                      </div>

                    </div>
                   

                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Address</label>
                      <div class="col-md-4">
                        <input class="form-control" type="text" name="Address">
                      </div>

                    </div>

                       <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Website</label>
                      <div class="col-md-4">
                        <input class="form-control" type="text" name="Website">
                      </div>

                    </div>


                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">logo</label>
                      <div class="col-md-4">
                      <input class="form-control" type="file" name="Logo" id="Logo">
                      </div>

                    </div>


                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Background Logo</label>
                      <div class="col-md-4">
                      <input class="form-control" type="file" name="BackgroundLogo" id="BackgroundLogo">
                      </div>

                    </div>

                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Signature</label>
                      <div class="col-md-4">
                        <input class="form-control" type="file" name="Signature" id="Signature">
                      </div>

                    </div>
                    
                    <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Digital Signature</label>
                      <div class="col-md-8">
                        <input class="form-control" name="DigitalSignature" type="text" name="InvoiceDueDays">
         <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
                         <script>
    CKEDITOR.replace('DigitalSignature', {
      // Define the toolbar groups as it is a more accessible solution.
      toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        },
        {
          "name": "links",
          "groups": ["links"]
        },
        {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        },
       
        {
          "name": "insert",
          "groups": ["insert"]
        },
        {
          "name": "styles",
          "groups": ["styles"]
        },
        
      ],
      // Remove the redundant buttons from toolbar groups defined above.
      removeButtons: 'Strike,Subscript,Superscript,Anchor,Styles,Specialchar,PasteFromWord'
    });
  </script>
                      </div>

                    </div>


                       <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Estimate Invoice Title</label>
                      <div class="col-md-4">
                        <input class="form-control" type="textfield" name="EstimateInvoiceTitle" id="EstimateInvoiceTitle">
                      </div>

                    </div>

                     <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Sale Invoice Title</label>
                      <div class="col-md-4">
                        <input class="form-control" type="textfield" name="SaleInvoiceTitle" id="SaleInvoiceTitle">
                      </div>

                    </div>

                
                     <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Delivery Challan Title</label>
                      <div class="col-md-4">
                        <input class="form-control" type="textfield" name="DeliveryChallanTitle" id="DeliveryChallanTitle">
                      </div>

                    </div>

                      <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Delivery Challan Title</label>
                      <div class="col-md-4">
                        <input class="form-control" type="textfield" name="DeliveryChallanTitle" id="DeliveryChallanTitle">
                      </div>

                    </div>


                      <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Credit Note Title</label>
                      <div class="col-md-4">
                        <input class="form-control" type="textfield" name="CreditNoteTitle" id="CreditNoteTitle">
                      </div>

                    </div>


                      <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Purchase Invoice</label>
                      <div class="col-md-4">
                        <input class="form-control" type="textfield" name="PurchaseInvoiceTitle" id="PurchaseInvoiceTitle">
                      </div>

                    </div>


                      <div class="mb-2 row">
                      <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Debit Note Title</label>
                      <div class="col-md-4">
                        <input class="form-control" type="textfield" name="DebitNoteTitle" id="DebitNoteTitle">
                      </div>

                    </div>




                  </div>

                </div>



 


            </div>
            <div class="card-footer bg-light bg-soft">
              <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Submit</button>




              <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
            </div>
          </div>

          @endif

          
          <!-- card end here -->
          </form>

          <div class="row">
            <div class="col-lg-12">
 <h4 class="card-title ">Company Details</h4>
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title "> </h4>
                  <!-- <p class="card-title-desc"> Add <code>.table-sm</code> to make tables more compact by cutting cell padding in half.</p>  -->

                  <div class="table-responsive">
                    <table class="table  m-0" id="student_table">
                      <thead>
                        <tr>
                          <th>Company Name</th>
                          <th>Contact</th>
                          <th>Email</th>
                          <th>Address</th>
                          <th>logo</th>
                          <th>Watermark</th>
                          <th>Signature</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($company as $data)
                        <tr>

                          <td scope="row">{{$data->Name}} {{$data->Name2}}</td>
                          <td>{{$data->Contact}}</td>
                          <td scope="row">{{$data->Email}}</td>
                          <td>{{$data->Address}}</td>
                          <td><a href="{{ URL('/documents/' . $data->Logo) }}" class="text-dark fw-medium" target="_blank"><i class="mdi mdi-file-document font-size-16 align-middle text-primary me-2"></i>
                                                        </a>
                          </td>                          
                          <td><a href="{{ URL('/documents/' . $data->BackgroundLogo) }}" target="_blank" class="text-dark fw-medium" ><i class="mdi mdi-file-document font-size-16 align-middle text-primary me-2"></i>
                                                        </a>

                                                        
                          </td> 
                          <td><a href="{{ URL('/documents/' . $data->Signature) }}" target="_blank" class="text-dark fw-medium" ><i class="mdi mdi-file-document font-size-16 align-middle text-primary me-2"></i>
                                                        </a>
                          </td>                          
                          <td>
                            <div class="d-flex gap-1">
                              <a href="{{URL('/CompanyEdit/'.$data->CompanyID)}}" class="text-secondary"  ><i class="mdi mdi-pencil font-size-15"></i></a>
                              <a href="#" class="text-secondary" onclick="delete_confirm2('CompanyDelete',{{$data->CompanyID}})"><i class="mdi mdi-delete font-size-15"></i></a>
                            
                            </div>
                          </td>

                        </tr>

                        @endforeach

                      </tbody>
                    </table>

                  </div>

                </div>
              </div>
            </div>



          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<!-- END: Content-->
<script type="text/javascript">
  $(document).ready(function() {
    $('#student_table').DataTable();
  });
</script>



@endsection