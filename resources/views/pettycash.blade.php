@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')
<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">

      <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Petty Cash</h4>
                                         <a href="{{URL('/PettyCashCreate')}}"  class="btn btn-sm btn-primary w-md float-right  "><i class="bx bx-plus"></i> Add New</a>

                                    
                                </div>
                            </div>
                        </div>





          <div class="row">
  <div class="col-12">
  
  @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

 @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger pt-3 pl-0   border-3">
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
          <table id="student_table" class="table table-striped " style="width:100%">
        <thead>
            <tr>
                <th>VHNO</th>
                <th>Petty#</th>
                <th>ChOfAcc</th>
                <th>Dat</th>
                <th>Narration</th>
                <th>Credit</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
      </div>
  </div>
  
  </div>
</div>

        </div>
      
<script type="text/javascript">
$(document).ready(function() {
     $('#student_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "{{ url('ajax_pettycash') }}",
        "columns":[
            { "data": "PettyMstID" },
            { "data": "PettyVoucher" },
            { "data": "ChartOfAccountName" },
            { "data": "Date" },
            { "data": "Narration" },
            { "data": "Credit" },
            { "data": "action" },
        ]
     });
});
</script>
 
  @endsection