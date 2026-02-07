@extends('template.tmp')
@section('title', 'Leads')
@section('content')



<style  >
    


.bg-primary {
    --bs-bg-opacity: 1;
    background-color: rgb(25 57 209) !important;
} 


.card-body {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.0rem 1.0rem !important;
}


.bg-primary2 {
    --bs-bg-opacity: 1;
    background-color: #008476 !important;
} 


.bg-primary3 {
    --bs-bg-opacity: 1;
    background-color: #805475 !important;
} 



.bg-primary4 {
    --bs-bg-opacity: 1;
    background-color: #2a3042 !important;
} 

</style>

<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">

 @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

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



 <div class="row">



                  



 
                                   <div class="col-sm-2">
                                        <div class="card bg-info bg-gradient  ">
                                            <div class="card-body border-primary rounded-top ">
                                                <p class="text-white mb-0"><i class="mdi mdi-account-details-outline h2 text-white align-middle mb-0 me-3"></i> Total Leads </p>

                                                <div class="row ">
                                                     
                                                      <h3 class="text-white text-center">{{($lead_summary) ? $lead_summary->Total : 0 }}</h3>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="card bg-success bg-gradient ">
                                            <div class="card-body">
                                                <p class="text-white mb-0"><i class="mdi mdi-account-question-outline h2 text-white align-middle mb-0 me-3"></i> Pending Leads </p>

                                                <div class="row">
                                                 <div class="row ">
                                                     
                                                      <h3 class="text-white text-center">{{($lead_summary ) ? $lead_summary->Pending : 0 }}</h3>
                                                    
                                                </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="card bg-primary bg-gradient ">
                                            <div class="card-body">
                                                <p class="text-white mb-0"><i class="mdi mdi-account-multiple-check-outline h2 text-white align-middle mb-0 me-3"></i> Lead Closed </p>

                                                <div class="row">
                                                  <div class="row ">
                                                     
                                                      <h3 class="text-white text-center">{{($lead_summary) ? $lead_summary->leads_won : 0}}</h3>
                                                    
                                                </div>
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>


                                          <div class="col-sm-2">
                                        <div class="card bg-danger bg-gradient">
                                            <div class="card-body  ">
                                                <p class="text-white mb-0"><i class="mdi mdi-account-off h2 text-white align-middle mb-0 me-3"></i> Leads Lost </p>

                                                <div class="row">
                                                 <div class="row ">
                                                     
                                                      <h3 class="text-white text-center">{{($lead_summary) ? $lead_summary->leads_lost : 0}}</h3>
                                                    
                                                </div>
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                     <div class="col-sm-2">
                                        <div class="card bg-warning bg-gradient">
                                            <div class="card-body  ">
                                                <p class="text-white mb-0"><i class="mdi mdi-account-multiple-minus h2 text-white align-middle mb-0 me-3"></i> Rejected Lost </p>

                                                <div class="row">
                                                 <div class="row ">
                                                     
                                                      <h3 class="text-white text-center">{{($lead_summary) ? $lead_summary->Rejected : 0}}</h3>
                                                    
                                                </div>
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>


                                       <div class="col-sm-2">
                                        <div class="card bg-secondary bg-gradient">
                                            <div class="card-body  ">
                                                <p class="text-white mb-0"><i class="mdi mdi-account-cancel h2 text-white align-middle mb-0 me-3"></i> Not Assigned </p>

                                                <div class="row">
                                                 <div class="row ">
                                                     
                                                      <h3 class="text-white text-center">{{($lead_summary) ? $leads_unassigned : 0}}</h3>
                                                    
                                                </div>
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

       <div class="col-sm-2">
                                        <div class="card bg-primary3 bg-gradient">
                                            <div class="card-body  ">
                                                <p class="text-white mb-0"><i class="mdi mdi-account-cancel h2 text-white align-middle mb-0 me-3"></i> Created Today </p>

                                                <div class="row">
                                                 <div class="row ">
                                                     
                                                      <h3 class="text-white text-center">{{($leads_created_today) ? $leads_created_today : 0}}</h3>
                                                    
                                                </div>
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>


                                        <div class="col-sm-2">
                                        <div class="card bg-primary2 bg-gradient">
                                            <div class="card-body  ">
                                                <p class="text-white mb-0"><i class="mdi mdi-account-cancel h2 text-white align-middle mb-0 me-3"></i> Lead Update Today </p>

                                                <div class="row">
                                                 <div class="row ">
                                                     
                                                      <h3 class="text-white text-center">{{($leads_updated_today) ? $leads_updated_today : 0}}</h3>
                                                    
                                                </div>
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>


                   <div class="col-sm-2">
                                        <div class="card bg-primary4 bg-gradient">
                                            <div class="card-body  ">
                                                <p class="text-white mb-0"><i class="mdi mdi-account-cancel h2 text-white align-middle mb-0 me-3"></i> Followup Today </p>

                                                <div class="row">
                                                 <div class="row ">
                                                     
                                                      <h3 class="text-white text-center">{{($followup) ? $followup : 0}}</h3>
                                                    
                                                </div>
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    

</div>      

  <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Leads List  
                                     @if(session::get('UserType')!='Admin')
                                    <br>
                                    
                                          <a href="{{ route('lead.create') }}" id="createButton" class="ml-3 btn btn-primary text-end mt-2">Add New</a>

                                     @endif
                                        </h4>
                                       <div class="col d-flex justify-content-end">
                              @if(session::get('UserType')=='Admin')
                                 <button type="button" id="importButton" class="ml-3 btn btn-primary text-end mt-2 mr-2 " data-bs-toggle="modal" data-bs-target=".exampleModal" > Import</button>
<div class="m-2">
    
</div>

                                    <a href="{{ route('lead.create') }}" id="createButton" class="ml-3 btn btn-primary text-end mt-2">Add New</a>
                                @endif
                            </div>    
 
                                </div>
                            </div>
                        </div>



<div >
     @if(session::get('Type')=='Agent')

@endif

     @if(session::get('Type')=='Admin')
    <div class="content-wrapper">
        <div class="row" >
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 >Lead List</h5>
                            </div>
                            {{-- <!-- @role('Admin') --> --}}
                            @if(session::get('UserType')=='Admin')
                                <div class="col d-flex justify-content-end">
                                    <button type="button" class="btn btn-info mx-2 d-none" id="reassignButton"
                                        data-bs-toggle="modal" data-bs-target=".exampleModalReassign">
                                        Reassign ( <span class="countIDS"></span> )
                                    </button>
                                    <button type="button" class="btn btn-warning mr-2 d-none" id="newReassignButton"
                                        data-bs-toggle="modal" data-bs-target=".exampleModalReassignNew">
                                        Reassign as New Lead ( <span class="countIDS"></span> )
                                    </button>
                                    <button type="button" id="bulkDeleteButton" class="btn btn-danger mx-2 d-none"><i
                                            class="mdi mdi-trash-can"></i>( <span class="countIDS"></span> )</button>
                                    <button type="button" class="btn btn-secondary d-none"
                                        id="cancelCheckAllButton">X</button>
                                  
                                   
                                </div>
                                @endif
                            {{-- <!-- @endrole --> --}}


                        </div>

                        {{-- <!-- @role('Admin') --> --}}
                        @if(session::get('UserType')=='Admin')
                            <hr>
                            <div id="filterRow">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <strong class="p-0">Filter By:</strong>
                                    </div>
                                </div>
                                <form action="{{ url('leads') }}" method="GET">
                                    <div class="row mt-2">
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for=""><strong>Status</strong></label>
                                            <select name="filter_status" id="" class="form-control select2 rounded">
                                                <option value="">
                                                    {{ isset($request) && $request->filter_status ? '--Cancel--' : '--Select One--' }}
                                                </option>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->name }}"
                                                        {{ isset($request) && $request->filter_status == $status->name ? 'selected' : '' }}>
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 d-none">
                                            <label for=""><strong>Campaign</strong></label>
                                            <select name="filter_campaign_id" id="" class="form-control select2 rounded">
                                                <option value="">
                                                    {{ isset($request) && $request->filter_campaign_id ? '--Cancel--' : '--Select One--' }}
                                                </option>
                                                <option value="-1"
                                                    {{ isset($request) && $request->filter_campaign_id == -1 ? 'selected' : '' }}>
                                                    No Campaign</option>
                                                @foreach ($campaigns as $campaign)
                                                    <option value="{{ $campaign->id }}"
                                                        {{ isset($request) && $request->filter_campaign_id == $campaign->id ? 'selected' : '' }}>
                                                        {{ $campaign->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for=""><strong>Agent</strong></label>
                                            <select name="filter_agent_id" id="" class="form-control select2 rounded">
                                                <option value="">
                                                    {{ isset($request) && $request->filter_agent_id ? '--Cancel--' : '--Select One--' }}
                                                </option>
                                                <option value="-1"
                                                    {{ isset($request) && $request->filter_agent_id == -1 ? 'selected' : '' }}>
                                                    No Agent</option>
                                                @foreach ($agents as $item)
                                                    <option value="{{ $item->UserID }}"
                                                        {{ isset($request) && $request->filter_agent_id == $item->UserID ? 'selected' : '' }}>
                                                        {{ $item->FullName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for=""><strong>Last Updated</strong></label>
                                            <select name="filter_last_updated" id=""
                                                class="form-control select2 rounded">
                                                <option value="">
                                                    {{ isset($request) && $request->filter_last_updated ? '--Cancel--' : '--Select One--' }}
                                                </option>
                                                <option value="Today"
                                                    {{ isset($request) && $request->filter_last_updated == 'Today' ? 'selected' : '' }}>
                                                    Today
                                                </option>
                                                <option value="Yesterday"
                                                    {{ isset($request) && $request->filter_last_updated == 'Yesterday' ? 'selected' : '' }}>
                                                    Yesterday
                                                </option>
                                                <option value="3"
                                                    {{ isset($request) && $request->filter_last_updated == '3' ? 'selected' : '' }}>
                                                    Last 3 Days
                                                </option>
                                                <option value="week"
                                                    {{ isset($request) && $request->filter_last_updated == 'week' ? 'selected' : '' }}>
                                                    This Week
                                                </option>
                                                <option value="month"
                                                    {{ isset($request) && $request->filter_last_updated == 'month' ? 'selected' : '' }}>
                                                    This Month
                                                </option>
                                                <option value="quarter"
                                                    {{ isset($request) && $request->filter_last_updated == 'quarter' ? 'selected' : '' }}>
                                                    This Quarter
                                                </option>
                                                <option value="year"
                                                    {{ isset($request) && $request->filter_last_updated == 'year' ? 'selected' : '' }}>
                                                    This Year
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for=""><strong>Creation Date</strong></label>
                                            <select name="filter_creation_date" id=""
                                                class="form-control select2 rounded">
                                                <option value="">
                                                    {{ isset($request) && $request->filter_creation_date ? '--Cancel--' : '--Select One--' }}
                                                </option>
                                                <option value="Today"
                                                    {{ isset($request) && $request->filter_creation_date == 'Today' ? 'selected' : '' }}>
                                                    Today
                                                </option>
                                                <option value="Yesterday"
                                                    {{ isset($request) && $request->filter_creation_date == 'Yesterday' ? 'selected' : '' }}>
                                                    Yesterday
                                                </option>
                                                <option value="3"
                                                    {{ isset($request) && $request->filter_creation_date == '3' ? 'selected' : '' }}>
                                                    Last 3 Days
                                                </option>
                                                <option value="week"
                                                    {{ isset($request) && $request->filter_creation_date == 'week' ? 'selected' : '' }}>
                                                    This Week
                                                </option>
                                                <option value="month"
                                                    {{ isset($request) && $request->filter_creation_date == 'month' ? 'selected' : '' }}>
                                                    This Month
                                                </option>
                                                <option value="quarter"
                                                    {{ isset($request) && $request->filter_creation_date == 'quarter' ? 'selected' : '' }}>
                                                    This Quarter
                                                </option>
                                                <option value="year"
                                                    {{ isset($request) && $request->filter_creation_date == 'year' ? 'selected' : '' }}>
                                                    This Year
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for=""><strong>Qualified Status</strong></label>
                                            <select name="filter_Q_status" id="" class="form-control select2 rounded">
                                                <option value="">
                                                    {{ isset($request) && $request->filter_Q_status ? '--Cancel--' : '--Select One--' }}
                                                </option>
                                                @foreach ($Q_statuses as $q_status)
                                                    <option value="{{ $q_status->name }}"
                                                        {{ isset($request) && $request->filter_Q_status == $q_status->name ? 'selected' : '' }}>
                                                        {{ $q_status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

<?php 

$service = DB::table('services')->get();

 ?>
                                           <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for=""><strong>Services</strong></label>
                                            <select name="filter_service" id="" class="form-control select2 rounded">
                                                <option value="">
                                                    {{ isset($request) && $request->filter_service ? '--Cancel--' : '--Select One--' }}
                                                </option>
                                                @foreach ($service as $service)
                                                    <option value="{{ $service->id }}"
                                                        {{ isset($request) && $request->filter_service == $service->id ? 'selected' : '' }}>
                                                        {{ $service->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                              <div class="col-lg-2 col-md-4 col-sm-6">
                                          <div class="row mt-4">
                                        <div class="col d-flex justify-content-end">
                                            <button class="btn btn-success w-50 btn-sm mr-2 mx-2">Apply</button>
                                            <a class="btn btn-info btn-sm w-50" href="{{ url('leads') }}">Reset</a>
                                        </div>
                                    </div>
                                        </div>
                                      <!--   {{-- <div class="col-2">
                                            <label for="">Deal Value</label>
                                            <input type="number" name="" id="" placeholder="From"
                                                class="form-control">
                                            <input type="number" name="" id="" placeholder="To"
                                                class="form-control">
                                        </div> --}} -->
                                    </div>
                                  <!--   <div class="row mt-2">
                                        <div class="col d-flex justify-content-end">
                                            <button class="btn btn-success mr-2 mx-2">Apply</button>
                                            <a class="btn btn-info" href="{{ url('leads') }}">Reset</a>
                                        </div>
                                    </div> -->
                                </form>
                            </div>
                        {{-- <!-- @endrole --> --}}
                         @endif
                    </div>
                </div>
              
            </div>
        </div>
    </div>
</div>

@endif


<div class="card">
    <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="lead-table" class="table table-sm table-hover table-responsive w-100">
                                <thead >
                                    <tr>
                                        <th><input type="checkbox" name="" id="checkAll"></th>
                                        {{-- <th>#</th> --}}
                                        <th>Lead</th>
                                        <th>Contact #</th>
                                         <th>Agent</th>
                                        <th>Status</th>
                                        <th>Qualified Status</th>
                                        <th>Created on</th>
                                        <th>Last Updated</th>
                                        <th>Service</th>
                                         <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)


                                    <?php 

                                    $service = DB::table('services')->where('id',$item->service_id)->first();
                                    // $sub_service = DB::table('sub_services')->where('id',$item->sub_service_id)->first();
                                    $users = DB::table('user')->where('UserID',$item->agent_id)->first();
                                     ?>
                                        <tr>
                                            <td><input type="checkbox" id="check_{{ $item->id }}" class="dt-select"
                                                    name="lead_ids[]" onclick="checkValue({{ $item->id }})"
                                                    value="{{ $item->id }}"></td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->tel }}</td>
                                             <td>{{ ($users) ? $users->FullName : '' }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>{{ isset($item->approved_status) ? $item->approved_status : 'N/A' }}</td>
                                            <td>{{ isset($item->created_at) ? dmY($item->created_at) : 'N/A' }}</td>
                                            <td>{{ isset($item->updated_at) ? dmY($item->updated_at) : 'N/A' }}</td>
                                             <td>{{ ($service) ? $service->name : '' }}</td>
                                             <td>

                                                <div class="d-flex align-items-center col-actions">
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-horizontal font-size-18"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 9px; border:1px solid #e9e9e9;">
                                
                                <li><a href="{{ route('lead.show', $item->id) }}" target="_blank" class="dropdown-item text-success"><i class="mdi mdi-eye-outline font-size-16 text-success me-1"></i> View Lead </a></li>
                                 
                                <li><a href="{{ route('lead.edit', $item->id) }}" class="dropdown-item text-primary"><i class="bx bx-pencil font-size-16 text-primary me-1"></i> Edit Lead</a></li>                              
                                
                                
                                <li ><a href="javascript:void(0)" onclick="delete_confirm_n(`leadDelete`,'{{ $item->id }}')" class="dropdown-item text-danger"><i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete Lead</a></li>
                            </ul>
                        </div>
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
    
    <!-- Modal -->
    <div class="modal fade exampleModal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Leads</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        
                    </button>
                </div>
                <form action="{{ route('lead.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <span>Click <a
                                        href="{{ route('downloadFile', ['file' => 'Lead Sample CSV.csv']) }}">here</a>
                                    to download the sample CSV file.</span>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="">Upload File *</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="file"
                                                    name="file" required accept=".csv">
                                                
                                            </div>
                                        </div>
                                        <small class="ml-2 text-danger" id="selected_file">No File Selected</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- @role('Admin') --}}

        <!-- Modal -->
        <div class="modal fade exampleModalReassign" id="exampleModalReassign" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalReassignLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalReassignLabel">Reassign Leads</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for=""><strong>Select Agent</strong></label>
                                <select name="agent_id" id="reassignAgentId" class="form-select">
                                    <option value="">--Select Agent--</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->UserID }}">{{ $agent->FullName }}</option>
                                    @endforeach
                                </select>
                                <div class="p-2">
                                    <small class="text-info"><em>Selected Leads will be reassigned. Previous agent will
                                            not have access to these leads anymore</em></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="reassignSubmitButton" class="btn btn-success">Reassign</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade exampleModalReassignNew" id="exampleModalReassignNew" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalReassignNewLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalReassignNewLabel">Reassign Leads as New</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for=""><strong>Select Agent</strong></label>
                                <select name="agent_id" id="reassignNewAgentId" class="form-control">
                                    <option value="">--Select Agent--</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->UserID }}">{{ $agent->FullName }}</option>
                                    @endforeach
                                </select>
                                <div class="p-2">
                                    <small class="text-info"><em>Selected Leads will be reassigned and changed to new
                                            Leads. All the notes and folow ups will be deleted and Previous agent will
                                            not have access to these leads anymore. </em></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="reassignNewSubmitButton" class="btn btn-success">Reassign</button>
                    </div>
                </div>
            </div>
        </div>
    {{-- <!-- @endrole --> --}}
    </div>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#lead-table').DataTable({
                pageLength: 50, // Set the default number of records to display
                lengthMenu: [10, 25, 50, 100, 150, 200, 250, 300, 350, 400, 450, 500],
                columnDefs: [{
                        orderable: false,
                        targets: 0
                    } // Disable ordering for the first column (checkbox)
                ]
            });
            // $('#lead-table').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: "{{ route('lead.index') }}",
            //     pageLength: 50, // Set the default number of records to display
            //     lengthMenu: [10, 25, 50, 100, 150, 200, 250, 300, 350, 400, 450, 500],
            //     columns: [{
            //             data: 'checkbox',
            //             name: 'checkbox',
            //             orderable: false,
            //             searchable: false
            //         },
            //         {
            //             data: 'name',
            //             name: 'name'
            //         },
            //         {
            //             data: 'tel',
            //             name: 'tel'
            //         },
            //         {
            //             data: 'branch.name',
            //             name: 'branch',
            //             render: function(data, type, full, meta) {
            //                 return full.branch ? full.branch.name : 'N/A';
            //             }
            //         },
            //         {
            //             data: 'agent.name',
            //             name: 'agent',
            //             render: function(data, type, full, meta) {
            //                 return full.agent ? full.agent.name : 'N/A';
            //             }
            //         },
            //         {
            //             data: 'status',
            //             name: 'status'
            //         },
            //         {
            //             data: 'action',
            //             name: 'action',
            //             orderable: false,
            //             searchable: false
            //         },
            //     ]
            // });
            $('#file').change(function() {
                const fileInput = $(this)[0];
                if (fileInput.files.length > 0) {
                    const fileName = fileInput.files[0].name;
                    $('#selected_file').removeClass('text-danger');
                    $('#selected_file').addClass('text-success');
                    $('#selected_file').text(fileName);
                    // alert(fileName);
                }
            });

        });
    </script>
    <script>
        var checked_ids = [];
        var checked_ids_count = 0;
        $('#checkAll').click(function() {
            checked_ids = [];
            const checkAllVal = $('#checkAll:checked').val();

            if (checkAllVal == 'on') {
                $(document).find('.dt-select').prop('checked', true);
                $(document).find('.dt-select:checked').each(function() {
                    let ID = parseInt($(this).val());
                    checked_ids.push(ID);
                });
            } else {
                $(document).find('.dt-select').prop('checked', false);
                checked_ids = [];
            }
            checked_ids_count = checked_ids.length;
            // alert(checked_ids_count);
            console.log(checked_ids);
            if (checked_ids_count > 0) {
                $('.countIDS').text(checked_ids_count);
                $('#reassignButton').removeClass('d-none');
                $('#newReassignButton').removeClass('d-none');
                $('#bulkDeleteButton').removeClass('d-none');
                $('#cancelCheckAllButton').removeClass('d-none');
                $('#importButton').addClass('d-none');
                $('#createButton').addClass('d-none');
                $('#filterRow').addClass('d-none');


            } else {
                $('.countIDS').text('');
                $('#reassignButton').addClass('d-none');
                $('#newReassignButton').addClass('d-none');
                $('#bulkDeleteButton').addClass('d-none');
                $('#cancelCheckAllButton').addClass('d-none');
                $('#importButton').removeClass('d-none');
                $('#createButton').removeClass('d-none');
                $('#filterRow').removeClass('d-none');


            }
        });

        function checkValue(id) {
            // alert(id);
            var checkbox = $('.dt-select[value="' + id + '"]');
            var index = checked_ids.indexOf(id);
            if (index !== -1) {
                checked_ids.splice(index, 1); // Remove the element at the found index
                checkbox.prop('checked', false);
            } else {
                checked_ids.push(id);
                checkbox.prop('checked', true);
            }
            $('#checkAll').prop('checked', false);
            console.log(checked_ids);
            checked_ids_count = checked_ids.length;
            // alert(checked_ids_count);
            if (checked_ids_count > 0) {
                $('.countIDS').text(checked_ids_count);
                $('#reassignButton').removeClass('d-none');
                $('#newReassignButton').removeClass('d-none');
                $('#bulkDeleteButton').removeClass('d-none');
                $('#cancelCheckAllButton').removeClass('d-none');
                $('#importButton').addClass('d-none');
                $('#createButton').addClass('d-none');
                $('#filterRow').addClass('d-none');



            } else {
                $('.countIDS').text('');
                $('#reassignButton').addClass('d-none');
                $('#newReassignButton').addClass('d-none');
                $('#bulkDeleteButton').addClass('d-none');
                $('#cancelCheckAllButton').addClass('d-none');
                $('#importButton').removeClass('d-none');
                $('#createButton').removeClass('d-none');
                $('#filterRow').removeClass('d-none');


            }
        };

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
        $('#cancelCheckAllButton').click(function() {
            $('#checkAll').prop('checked', false);
            $(document).find('.dt-select').prop('checked', false);
            checked_ids = [];
            $('.countIDS').text('');
            $('#reassignButton').addClass('d-none');
            $('#newReassignButton').addClass('d-none');
            $('#bulkDeleteButton').addClass('d-none');
            $('#cancelCheckAllButton').addClass('d-none');
            $('#importButton').removeClass('d-none');
            $('#createButton').removeClass('d-none');
            $('#filterRow').removeClass('d-none');

        });
        $('#bulkDeleteButton').click(function() {
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
                    // alert('done');
                    $.ajax({
                        url: "{{ url('bulkDeleteLeads') }}",
                        type: 'POST',
                        data: {
                            ids: checked_ids,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    // position: "top-end",
                                    icon: "success",
                                    title: response.success,
                                    showConfirmButton: false,
                                    timer: 1500,
                                    timerProgressBar: true,
                                }).then((result) => {
                                    // Check if the modal was closed (timer completed)
                                    if (result.dismiss) {
                                        // Reload the page after the timer ends
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    // position: "top-end",
                                    icon: "error",
                                    title: response.error,
                                    showConfirmButton: true,
                                    // timer: 1500,
                                    // timerProgressBar: true,
                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            alert(xhr.responseText);
                            console.error(xhr.responseText);
                        }
                    })
                }
            });
        });
        $('#reassignSubmitButton').click(function() {
            let selectedAgent = $("#reassignAgentId").val();
            if (selectedAgent == '') {
                // alert('Please Select An Agent');
                // $('#exampleModalReassign').modal('show');
            } else {
                $('#exampleModalReassign').modal('hide');
                $.ajax({
                    url: "{{ url('bulkReassignLeads') }}",
                    type: 'POST',
                    data: {
                        ids: checked_ids,
                        agent_id: selectedAgent,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                // position: "top-end",
                                icon: "success",
                                title: response.success,
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                            }).then((result) => {
                                // Check if the modal was closed (timer completed)
                                if (result.dismiss) {
                                    // Reload the page after the timer ends
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                // position: "top-end",
                                icon: "error",
                                title: response.error,
                                showConfirmButton: true,
                                // timer: 1500,
                                // timerProgressBar: true,
                            })
                        }

                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        alert(xhr.responseText);
                        console.error(xhr.responseText);
                    }
                })
            }
        });
        $('#reassignNewSubmitButton').click(function() {
            let selectedAgent = $("#reassignNewAgentId").val();
            if (selectedAgent == '') {
                alert('Please Select An Agent');
                // $('#exampleModalReassign').modal('show');
            } else {
                $('#exampleModalReassign').modal('hide');
                $.ajax({
                    url: "{{ url('bulkReassignNewLeads') }}",
                    type: 'POST',
                    data: {
                        ids: checked_ids,
                        agent_id: selectedAgent,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                // position: "top-end",
                                icon: "success",
                                title: response.success,
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                            }).then((result) => {
                                // Check if the modal was closed (timer completed)
                                if (result.dismiss) {
                                    // Reload the page after the timer ends
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                // position: "top-end",
                                icon: "error",
                                title: response.error,
                                showConfirmButton: true,
                                // timer: 1500,
                                // timerProgressBar: true,
                            })
                        }

                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        alert(xhr.responseText);
                        console.error(xhr.responseText);
                    }
                })
            }
        });
    </script>

<!--     <script>
$(document).ready(function(){
    // Refresh the page after 5 minutes (300,000 milliseconds)
    setTimeout(function(){
        location.reload();
    }, 10000); // 5 minutes in milliseconds
});
</script> -->



@endsection
