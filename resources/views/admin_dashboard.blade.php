@extends('template.tmp')

@section('title', $pagetitle)


@section('content')

<style id="compiled-css" type="text/css">
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 360px;
        max-width: 800px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }


.bg-primary {
    --bs-bg-opacity: 1;
    background-color: rgb(25 57 209) !important;
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
    background-color: #7b7c0b !important;
} 




    /* EOS */
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                        <div class="page-title-right ">
                            <strong
                                class="text-danger">{{session::get('UserID')}}-{{session::get('UserType')}}-{{session::get('Email')}}</strong>

                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->



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

                <div class="col-xl-8">
                    <div class="row">
                        {{-- <div class="col-sm-4">
                            <div class="card ">
                                <div class="card-body border-success border-top border-3 rounded-top shadow-sm">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0">Today's Booking</h5>
                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a  class="text-white" href="{{URL('/Booking')}}">{{($total_booking==null) ?
                                                '0' : number_format($total_booking) }} </a> </h4>

                                    </div>
                                </div>
                            </div>


                        </div> --}}

                        <div class="col-sm-4">
                            <div class="card bg-primary bg-gradient ">
                                <div class="card-body   rounded-top  shadow-sm">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0 text-white">Total Leads</h5>
                                    </div>
                                    <div class="text-white mt-0">
                                        <h4 class="text-center text-white"><a  class="text-white" href="{{URL('/leads')}}" class="text-white">{{($total_leads==null) ? '0'
                                                : number_format($total_leads) }} </a> </h4>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <div class="card bg-info bg-gradient" data-status="Closed Won" >
                                <div class="card-body   rounded-top  shadow-sm ">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0 text-white">Won Leads </h5>
                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a  class="text-white" href="javascript:;">{{($leads_won==null) ? '0' :
                                                number_format($leads_won) }}


                                            </a> </h4>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body  bg-dark bg-gradient rounded-top  shadow-sm">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0 text-white">Lost Leads </h5>
                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a  class="text-white" href="javascript:void(0);">{{($leads_lost==null) ? '0'
                                                : number_format($leads_lost) }}</a> </h4>


                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <div class="card" >
                                <div class="card-body  bg-danger bg-gradient rounded-top  shadow-sm">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0 text-white">Unassigned Leads </h5>
                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a class="text-white" 
                                                href="javascript:void(0);">{{($leads_not_assigned==null) ? '0' :
                                                number_format($leads_not_assigned) }}</a> </h4>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body  bg-success bg-gradient rounded-top  shadow-sm">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0 text-white">Rejected Leads</h5>
                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a  class="text-white" href="javascript:void(0);">{{($leads_reject==null) ?
                                                '0' : number_format($leads_reject) }}</a> </h4>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body bg-warning bg-gradient  rounded-top  shadow-sm">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0 text-white">Pending Leads</h5>
                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a  class="text-white" href="javascript:void(0);">{{($leads_pending==null) ?
                                                '0' : number_format($leads_pending) }}</a> </h4>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body  bg-secondary bg-gradient rounded-top  shadow-sm">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0 text-white">Inactive Leads <small>in Last 4 days</small></h5>

                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a class="text-white"  href="javascript:void(0);">{{($leadsNotUpdatedIn4Days==null) ?
                                                '0' : number_format($leadsNotUpdatedIn4Days) }}</a> </h4>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body bg-primary2 bg-gradient  rounded-top  shadow-sm">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0 text-white">Leads Created Today </h5>
                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a  class="text-white" href="javascript:void(0);">{{($leads_created_today==null) ?
                                                '0' : number_format($leads_created_today) }}</a> </h4>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body bg-primary3 bg-gradient  rounded-top  shadow-sm">
                                    <div class="d-flex align-items-center mb-3">

                                        <h5 class="font-size-14 mb-0 text-white">Leads Updated Today </h5>
                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a  class="text-white" href="javascript:void(0);">{{($leads_created_today==null) ?
                                                '0' : number_format($leads_updated_today) }}</a> </h4>


                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="col-sm-4">
                            <div class="card" data-status="Rejected">
                                <div class="card-body bg-primary4 bg-gradient rounded-top shadow-sm">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="font-size-14 mb-0 text-white">Followup Today</h5>
                                    </div>
                                    <div class="text-muted mt-0">
                                        <h4 class="text-center"><a  class="text-white" href="javascript:void(0);">{{ (count($followup) == 0) ? '0' : number_format(count($followup)) }}</a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>
                    <!-- end row -->
                </div>
         
                  <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Lead Overall Summary</h4>

                                        <ul class="nav nav-pills bg-light rounded" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#transactions-all-tab" role="tab">All</a>
                                            </li>

                                            @foreach($agents as $agent)
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#transactions-{{$agent->UserID}}-tab" role="tab">{{$agent->FullName}}</a>
                                            </li>
                                            @endforeach
                                          <!--   <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#transactions-sell-tab" role="tab">Sell</a>
                                            </li> -->
                                        </ul>
                                        <div class="tab-content mt-4">
                                            <div class="tab-pane active" id="transactions-all-tab" role="tabpanel">
                                                <div class="table-responsive" data-simplebar style="max-height: 330px;">
                                                    <table class="table align-middle table-nowrap">
                                                <tbody>
                                                   
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0 ">Total Leads</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0 ">{{ DB::table('leads')->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0 ">Pending leads</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0 ">{{ DB::table('leads')->where('status','Pending')->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Leads Won</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads') ->where('approved_status','Closed Won')->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Leads lost</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads') ->where('approved_status','Closed Lost')->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Unassigned Leads</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{DB::table('leads')
                                                                    ->whereNull('agent_id')
                                                                    ->count(); }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Rejected Leads</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads')->where('status','Rejected')->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>


                                                         <tr>
                                                        <td><div><h5 class="font-size-14 mb-0"> Leads Created today</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ $leads_created_today }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>


                                                          <tr>
                                                        <td><div><h5 class="font-size-14 mb-0"> Leads Updated today</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ $leads_updated_today }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>


                                                            <tr>
                                                        <td><div><h5 class="font-size-14 mb-0"> Inactive Leads in Last 4 days
</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ $leadsNotUpdatedIn4Days }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>


                                                           <tr>
                                                        <td><div><h5 class="font-size-14 mb-0"> Followuyp Leads Today
</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ count($followup) }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                                </div>
                                            </div>

                                             @foreach($agents as $agent)






                                            <div class="tab-pane" id="transactions-{{$agent->UserID}}-tab" role="tabpanel">
                                                <div class="table-responsive" data-simplebar style="max-height: 330px;">
                                             <table class="table align-middle table-nowrap">
                                                    <tbody>
                                                        
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Total Leads</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads')->where('agent_id',$agent->UserID)->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Pending leads</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads')->where('agent_id',$agent->UserID)->where('status','Pending')->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Leads Won</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads')->where('agent_id',$agent->UserID) ->where('approved_status','Closed Won')->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Leads lost</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads')->where('agent_id',$agent->UserID) ->where('approved_status','Closed Lost')->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Rejected Leads</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads')->where('agent_id',$agent->UserID)->where('status','Rejected')->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                                
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Leads Created Today</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads')->whereDate('created_at', $today)->where('agent_id',$agent->UserID)->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5 class="font-size-14 mb-0">Leads Updated Today</h5></div></td>
                                                        <td>
                                                            <div class="text-end">
                                                                <h5 class="font-size-14 mb-0">{{ DB::table('leads')->whereDate('updated_at', $today)->where('agent_id',$agent->UserID)->count() }}</h5>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    @php
                                                        // Create a DateTime object for the current date and time
                                                        $currentDate = new DateTime();

                                                        // Subtract 4 days from the current date
                                                        $currentDate->modify('-4 days');

                                                        // Get the date 4 days ago in the desired format
                                                        $fourDaysAgo = $currentDate->format('Y-m-d');
                                                    @endphp 

                                                        <tr>
                                                            <td><div><h5 class="font-size-14 mb-0">Inactive Leads in Last 4 days</div></td>
                                                            <td>
                                                                <div class="text-end">
                                                                    <h5 class="font-size-14 mb-0">
                                                                        {{ DB::table('leads') ->where('status','Pending')
                                                                        ->where('agent_id',$agent->UserID)
                                                                        ->where('updated_at', '<', $fourDaysAgo)   
                                                                        ->count() }}</h5>
                                                                </div>
                                                            </td>
                                                        </tr>



                                                           <tr>
                                                            <td><div><h5 class="font-size-14 mb-0">Follow Leads Today</div></td>
                                                            <td>
                                                                <div class="text-end">
                                                                    <h5 class="font-size-14 mb-0">
                                                                        {{                 $followup = DB::table('lead_details')->whereDate('follow_up_date', $today)->where('user_id',$agent->UserID)->count();
 }}</h5>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                </div>
                                            </div>
                                            @endforeach

                                    <!--         <div class="tab-pane" id="transactions-sell-tab" role="tabpanel">
                                                <div class="table-responsive" data-simplebar style="max-height: 330px;">
                                                    <table class="table align-middle table-nowrap">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 50px;">
                                                                    <div class="font-size-22 text-danger">
                                                                        <i class="bx bx-up-arrow-circle"></i>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div>
                                                                        <h5 class="font-size-14 mb-1">Buy BTC</h5>
                                                                        <p class="text-muted mb-0">14 Mar, 2020</p>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="text-end">
                                                                        <h5 class="font-size-14 mb-0">0.016 BTC</h5>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="text-end">
                                                                        <h5 class="font-size-14 text-muted mb-0">$125.20</h5>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div> -->
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

               
            </div>


 
<?php 

$followup_detail = DB::table('v_followup')->whereDate('follow_up_date', $today)->get();


 ?>


<h4>    TODAY'S FOLLOW UP </h4>

<div class="card">
    <div class="card-body">
         @if(count($followup_detail)>0)        
          <table class="table  table-bordered table-sm align-middle mb-0">
          <tbody><tr class="bg-light">
          <th width="2%">S.No</th>
          <th width="10%">Name</th>
          <th width="10%">Tel</th>
          <th width="10%">Service</th>
          <th width="10%">Agent</th>
          <th width="10%">Followup Date</th>
          <th width="10%">Created Date</th>
          </tr>
          </tbody>
          <tbody>
          @foreach ($followup_detail as $key =>$value)
           <tr>
           <td >{{$key+1}}</td>
           <td >{{$value->name}}</td>
           <td >{{$value->tel}}</td>
           <td >{{$value->ServiceName}}</td>
           <td >{{$value->FullName}}</td>
           <td >{{dateformatman2($value->follow_up_date)}}</td>
           <td >{{dateformatman22($value->created_at)}}</td>
           </tr>
           @endforeach   
           </tbody>
           </table>
           @else
             <p class=" text-danger">No data found</p>
           @endif     
    </div>
</div>

<div    class="row">


    <div class="col-md-6">
 
<h4>    TODAY'S ITEMWISE LEADS </h4>

<div class="card " style="border-top:  4px solid #048F98 !important;">

    


    <div class="card-body p-0 bg-white bg-gradient bg-soft">
         @if(count($today_lead_summary) > 0)        
    <table class="table table-bordered table-sm align-middle mb-0">
        <thead>
            <tr class="bg-secondary bg-soft">
                <th width="2%">S.No</th>
                <th width="10%">Agent</th>
                <th width="10%">Ticket</th>
                <th width="10%">Umran Bus</th>
                <th width="10%">Visit Visa</th>
                <th width="10%">MultiVisa</th>
                <th width="10%">A2A</th>
                <th width="10%">GT</th>
                <th width="10%">UmrahByAir</th>
                <th width="10%">Safari</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTicket = 0;
                $totalUmrahByBus = 0;
                $totalHotelBooking = 0;
                $totalVisitVisa = 0;
                $totalMultiVisa = 0;
                $totalA2A = 0;
                $totalGT = 0;
                $totalUmrahByAir = 0;
                $totalDesertSafari = 0;
            @endphp

            @foreach ($today_lead_summary as $key => $value)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $value->FullName }}</td>
                    <td>{{ $value->Ticket }}</td>
                    <td>{{ $value->UmrahByBus }}</td>
                    <td>{{ $value->VisitVisa }}</td>
                    <td>{{ $value->MultiVisa }}</td>
                    <td>{{ $value->A2A }}</td>
                    <td>{{ $value->GT }}</td>
                    <td>{{ $value->UmrahByAir }}</td>
                    <td>{{ $value->DesertSafari }}</td>
                </tr>
                @php
                    $totalTicket += $value->Ticket;
                    $totalUmrahByBus += $value->UmrahByBus;
                    $totalHotelBooking += $value->HotelBooking;
                    $totalVisitVisa += $value->VisitVisa;
                    $totalMultiVisa += $value->MultiVisa;
                    $totalA2A += $value->A2A;
                    $totalGT += $value->GT;
                    $totalUmrahByAir += $value->UmrahByAir;
                    $totalDesertSafari += $value->DesertSafari;
                @endphp
            @endforeach

            <tr  >
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>{{ $totalTicket }}</strong></td>
                <td><strong>{{ $totalUmrahByBus }}</strong></td>
                <td><strong>{{ $totalVisitVisa }}</strong></td>
                <td><strong>{{ $totalMultiVisa }}</strong></td>
                <td><strong>{{ $totalA2A }}</strong></td>
                <td><strong>{{ $totalGT }}</strong></td>
                <td><strong>{{ $totalUmrahByAir }}</strong></td>
                <td><strong>{{ $totalDesertSafari }}</strong></td>
            </tr>
        </tbody>
    </table>
@else
    <p class="text-danger text-center p-2">No data found</p>
@endif
    
    </div>
</div>

    </div>




    <div class="col-md-6">
        
        
<h4>    YESTERDAY ITEMWISE LEADS </h4>

<div class="card " style="border-top:  4px solid #048F98 !important;">

    <div class="card-body p-0 bg-white bg-gradient bg-soft">
         @if(count($yesterday_lead_summary)>0)        
          
    <table class="table table-bordered table-sm align-middle mb-0">
        <thead>
                        <tr class="bg-secondary bg-soft">

                <th width="2%">S.No</th>
                <th width="10%">Agent</th>
                <th width="10%">Ticket</th>
                <th width="10%">Umran Bus</th>
                <th width="10%">Visit Visa</th>
                <th width="10%">MultiVisa</th>
                <th width="10%">A2A</th>
                <th width="10%">GT</th>
                <th width="10%">UmrahByAir</th>
                <th width="10%">Safari</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTicket = 0;
                $totalUmrahByBus = 0;
                $totalHotelBooking = 0;
                $totalVisitVisa = 0;
                $totalMultiVisa = 0;
                $totalA2A = 0;
                $totalGT = 0;
                $totalUmrahByAir = 0;
                $totalDesertSafari = 0;
            @endphp

            @foreach ($yesterday_lead_summary as $key => $value)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $value->FullName }}</td>
                    <td>{{ $value->Ticket }}</td>
                    <td>{{ $value->UmrahByBus }}</td>
                    <td>{{ $value->VisitVisa }}</td>
                    <td>{{ $value->MultiVisa }}</td>
                    <td>{{ $value->A2A }}</td>
                    <td>{{ $value->GT }}</td>
                    <td>{{ $value->UmrahByAir }}</td>
                    <td>{{ $value->DesertSafari }}</td>
                </tr>
                @php
                    $totalTicket += $value->Ticket;
                    $totalUmrahByBus += $value->UmrahByBus;
                    $totalHotelBooking += $value->HotelBooking;
                    $totalVisitVisa += $value->VisitVisa;
                    $totalMultiVisa += $value->MultiVisa;
                    $totalA2A += $value->A2A;
                    $totalGT += $value->GT;
                    $totalUmrahByAir += $value->UmrahByAir;
                    $totalDesertSafari += $value->DesertSafari;
                @endphp
            @endforeach

                        <tr  >

                <td colspan="2"><strong>Total</strong></td>
                <td><strong>{{ $totalTicket }}</strong></td>
                <td><strong>{{ $totalUmrahByBus }}</strong></td>
                <td><strong>{{ $totalVisitVisa }}</strong></td>
                <td><strong>{{ $totalMultiVisa }}</strong></td>
                <td><strong>{{ $totalA2A }}</strong></td>
                <td><strong>{{ $totalGT }}</strong></td>
                <td><strong>{{ $totalUmrahByAir }}</strong></td>
                <td><strong>{{ $totalDesertSafari }}</strong></td>
            </tr>
        </tbody>
    </table>
@else
    <p class="text-danger text-center p-2">No data found</p>
@endif
    
    </div>
</div>

    </div>
    
</div>


<div    class="row">


    <div class="col-md-6">
        

<h4>    LAST WEEK ITEMWISE LEADS </h4>

<div class="card " style="border-top:  4px solid #048F98 !important;">

    <div class="card-body p-0 bg-white bg-gradient bg-soft">
         @if(count($week_lead_summary)>0)        
     <table class="table table-bordered table-sm align-middle mb-0">
        <thead>
                        <tr class="bg-secondary bg-soft">

                <th width="2%">S.No</th>
                <th width="10%">Agent</th>
                <th width="10%">Ticket</th>
                <th width="10%">Umran Bus</th>
                <th width="10%">Visit Visa</th>
                <th width="10%">MultiVisa</th>
                <th width="10%">A2A</th>
                <th width="10%">GT</th>
                <th width="10%">UmrahByAir</th>
                <th width="10%">Safari</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTicket = 0;
                $totalUmrahByBus = 0;
                $totalHotelBooking = 0;
                $totalVisitVisa = 0;
                $totalMultiVisa = 0;
                $totalA2A = 0;
                $totalGT = 0;
                $totalUmrahByAir = 0;
                $totalDesertSafari = 0;
            @endphp

            @foreach ($week_lead_summary as $key => $value)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $value->FullName }}</td>
                    <td>{{ $value->Ticket }}</td>
                    <td>{{ $value->UmrahByBus }}</td>
                    <td>{{ $value->VisitVisa }}</td>
                    <td>{{ $value->MultiVisa }}</td>
                    <td>{{ $value->A2A }}</td>
                    <td>{{ $value->GT }}</td>
                    <td>{{ $value->UmrahByAir }}</td>
                    <td>{{ $value->DesertSafari }}</td>
                </tr>
                @php
                    $totalTicket += $value->Ticket;
                    $totalUmrahByBus += $value->UmrahByBus;
                    $totalHotelBooking += $value->HotelBooking;
                    $totalVisitVisa += $value->VisitVisa;
                    $totalMultiVisa += $value->MultiVisa;
                    $totalA2A += $value->A2A;
                    $totalGT += $value->GT;
                    $totalUmrahByAir += $value->UmrahByAir;
                    $totalDesertSafari += $value->DesertSafari;
                @endphp
            @endforeach

                        <tr  >

                <td colspan="2"><strong>Total</strong></td>
                <td><strong>{{ $totalTicket }}</strong></td>
                <td><strong>{{ $totalUmrahByBus }}</strong></td>
                <td><strong>{{ $totalVisitVisa }}</strong></td>
                <td><strong>{{ $totalMultiVisa }}</strong></td>
                <td><strong>{{ $totalA2A }}</strong></td>
                <td><strong>{{ $totalGT }}</strong></td>
                <td><strong>{{ $totalUmrahByAir }}</strong></td>
                <td><strong>{{ $totalDesertSafari }}</strong></td>
            </tr>
        </tbody>
    </table>
@else
    <p class="text-danger text-center p-2">No data found</p>
@endif
  
    </div>
</div>

    </div>




    <div class="col-md-6">
        
        
<h4>    4 WEEKS ITEMWISE LEADS </h4>

<div class="card " style="border-top:  4px solid #048F98 !important;">

    <div class="card-body p-0 bg-white bg-gradient bg-soft">
         @if(count($month_lead_summary)>0)        
     <table class="table table-bordered table-sm align-middle mb-0">
        <thead>
                        <tr class="bg-secondary bg-soft">

                <th width="2%">S.No</th>
                <th width="10%">Agent</th>
                <th width="10%">Ticket</th>
                <th width="10%">Umran Bus</th>
                <th width="10%">Visit Visa</th>
                <th width="10%">MultiVisa</th>
                <th width="10%">A2A</th>
                <th width="10%">GT</th>
                <th width="10%">UmrahByAir</th>
                <th width="10%">Safari</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTicket = 0;
                $totalUmrahByBus = 0;
                $totalHotelBooking = 0;
                $totalVisitVisa = 0;
                $totalMultiVisa = 0;
                $totalA2A = 0;
                $totalGT = 0;
                $totalUmrahByAir = 0;
                $totalDesertSafari = 0;
            @endphp

            @foreach ($month_lead_summary as $key => $value)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $value->FullName }}</td>
                    <td>{{ $value->Ticket }}</td>
                    <td>{{ $value->UmrahByBus }}</td>
                    <td>{{ $value->VisitVisa }}</td>
                    <td>{{ $value->MultiVisa }}</td>
                    <td>{{ $value->A2A }}</td>
                    <td>{{ $value->GT }}</td>
                    <td>{{ $value->UmrahByAir }}</td>
                    <td>{{ $value->DesertSafari }}</td>
                </tr>
                @php
                    $totalTicket += $value->Ticket;
                    $totalUmrahByBus += $value->UmrahByBus;
                    $totalHotelBooking += $value->HotelBooking;
                    $totalVisitVisa += $value->VisitVisa;
                    $totalMultiVisa += $value->MultiVisa;
                    $totalA2A += $value->A2A;
                    $totalGT += $value->GT;
                    $totalUmrahByAir += $value->UmrahByAir;
                    $totalDesertSafari += $value->DesertSafari;
                @endphp
            @endforeach

                        <tr  >

                <td colspan="2"><strong>Total</strong></td>
                <td><strong>{{ $totalTicket }}</strong></td>
                <td><strong>{{ $totalUmrahByBus }}</strong></td>
                <td><strong>{{ $totalVisitVisa }}</strong></td>
                <td><strong>{{ $totalMultiVisa }}</strong></td>
                <td><strong>{{ $totalA2A }}</strong></td>
                <td><strong>{{ $totalGT }}</strong></td>
                <td><strong>{{ $totalUmrahByAir }}</strong></td>
                <td><strong>{{ $totalDesertSafari }}</strong></td>
            </tr>
        </tbody>
    </table>
@else
    <p class="text-danger text-center p-2">No data found</p>
@endif
    
    </div>
</div>

    </div>
    
</div>


            {{-- <script src="https://code.highcharts.com/highcharts.js"></script>
            <script src="https://code.highcharts.com/modules/series-label.js"></script>
            <script src="https://code.highcharts.com/modules/exporting.js"></script>
            <script src="https://code.highcharts.com/modules/export-data.js"></script>
            <script src="https://code.highcharts.com/modules/accessibility.js"></script> --}}





        </div>
        <!-- end row -->
    </div>
</div>



</div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
<!-- Modal -->
<div class="modal fade" id="leadsModal" tabindex="-1" aria-labelledby="leadsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leadsModalLabel">Leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="leadsList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</div>
</div>
<script>
    var fetchLeadsUrl = "{{ route('fetch-leads') }}";
</script>

<script>
    $(document).ready(function() {
    $('.card').on('click', function() {
        var status = $(this).data('status');
        fetchLeads(status);
    });

    function fetchLeads(status) {
        $.ajax({
            url: fetchLeadsUrl,
            method: 'GET',
            data: { status: status },
            success: function(response) {
                var leadsHtml = '<ul class="list-group">';
                if(response.leads.length > 0) {
                    response.leads.forEach(function(lead) {
                        leadsHtml += '<li class="list-group-item">' + lead.name + '</li>';
                    });
                } else {
                    leadsHtml += '<li class="list-group-item">No leads found.</li>';
                }
                leadsHtml += '</ul>';
                $('#leadsList').html(leadsHtml);
                $('#leadsModalLabel').text(capitalizeFirstLetter(status) + ' Leads');
                $('#leadsModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error fetching leads:', xhr.responseText);
            }
        });
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
});

</script>




@endsection