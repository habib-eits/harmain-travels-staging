@extends('template.tmp')
@section('title', 'Service Index')
@section('content')

 

<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">



 

  <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Booking</h4>
                                       <div class="col d-flex justify-content-end">
                              

                                 <a href="{{URL('/BookingCreate/0')}}" class="btn btn-danger btn-rounded waves-effect waves-light mb-2 me-2"><i class="bx bx-plus me-1"></i> Add New</a>
                                 
                            </div>    
 
                                </div>
                            </div>
                        </div>

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



 @if (count($errors) > 0)
                                 
                            <div >
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

                         <div class="card">

                    <div class="card-body">
                            <div class="col-12">
                                <table id="service-table" class="table table-sm table-hover w-100">
                                    <thead >
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="10%">Title</th>
                                            <th width="10%">Client</th>
                                            <th width="10%">Service</th>
                                            <th width="10%">Vendor</th>
                                            <th width="10%">Agent</th>
                                            <th width="10%">Date</th>
                                            <th width="10%">Till Date</th>
                                            <th width="10%">Payment</th>
                                            <th width="5%">Payment</th>
                                            <th width="5%">Action</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($booking as $key => $item)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->PartyName }}</td>
                                                <td>{{ $item->services }}</td>
                                                <td>{{ $item->SupplierName }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->start }}</td>
                                                <td>{{ $item->end }}</td>
                                                <td>{{ $item->payment_status }}</td>
                                                 <td>

                                                    <?php 

                                                    if($item->file)
                                                    {

                                                        ?>

                                               <a href="{{ env('APP_URL'). Storage::url('app/public/uploads/'.$item->file) }}" title="" target="_blank">Pay</a>  


                                                        <?php

                                                    }

                                                     ?>



                                                        <?php 

                                                    if($item->invoice_file)
                                                    {

                                                        ?>

                                               | <a href="{{ env('APP_URL'). Storage::url('app/public/uploads/'.$item->invoice_file) }}" title="" target="_blank">Inv</a>


                                                        <?php

                                                    }

                                                     ?>
                                                   </td>
                                                    <td>{{$item->amount}}</td>
                                                
                                                <td>
                                                    <a href="{{URL('/BookingEdit/'.$item->id)}}"  >
                                                        <i class="mdi mdi-pencil  align-middle text-secondary"></i>
                                                    </a>
                                                    <a href="#"
                                                        onclick="delete_confirm_n(`BookingDelete`,'{{ $item->id }}')">
                                                        <i class="mdi mdi-trash-can  align-middle me-1 text-secondary"></i>
                                                    </a>
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
 

 

  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#service-table').DataTable({
                columnDefs: [{
                        orderable: false,
                        targets: [0,3]
                    } // Disable ordering for the first column (checkbox)
                ]
            });
        });
    </script>
    <script>
        function delete_confirm_n(url, id) {
            // alert(url);
            // alert(id);
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    url = "{{ URL::TO('/') }}/" + url + '/' + id;
                    window.location.href = url;
                }
            });

        };
 
    </script>
@endsection
