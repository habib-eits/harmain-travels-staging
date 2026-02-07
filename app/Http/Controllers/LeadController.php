<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Branch;
use App\Models\Campaign;

use App\Models\LeadDetails;
use App\Models\LeadActivity;
use App\Models\QualifiedStatus;
use App\Models\Service;
use App\Models\Status;
use App\Models\SubService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;
use Session;
use Illuminate\Support\Arr; 

class LeadController extends Controller
{
    public function index(Request $request)
    {

         // dd(session::all());
         try {

            if(session::get('Type')!='Admin')
            {   
                $id=session::get('UserID');
                $agents = DB::table('user')->where('UserID',$id)->get();

 
            
                $statuses = Status::all();
                $Q_statuses = QualifiedStatus::all();
                $campaigns = Campaign::all();
                $data = DB::table('leads')
                    ->where('agent_id', session::get('UserID'))
                    ->orwhereNull('agent_id')
                    ->orderByDesc('id')
                    ->get();


$lead_summary = DB::table('v_lead_summary_user')->where('agent_id',session::get('UserID'))->first();
              
$leads_unassigned = DB::table('leads')->whereNull('agent_id')->count();

$today = Carbon::today();
            
            $leads_created_today = DB::table('leads')
          
            ->whereDate('created_at', $today)
            ->where('agent_id',session::get('UserID'))
            ->count();
    
            $leads_updated_today =  DB::table('leads')
           
            ->whereDate('updated_at', $today)
            ->where('agent_id',session::get('UserID'))
            ->count();

                $followup = DB::table('lead_details')->whereDate('follow_up_date', $today)->where('user_id',session::get('UserID'))->count();

 

 
            }
            else
            {
 
            $lead_summary = DB::table('v_lead_summary')->first();
             $leads_unassigned = DB::table('leads')->whereNull('agent_id')->count();

       $agents = DB::table('user')->where('UserType','!=','Admin')->get();
$today = Carbon::today();
    $leads_created_today = DB::table('leads')
          
            ->whereDate('created_at', $today)
             ->count();
    
            $leads_updated_today =  DB::table('leads')
           
            ->whereDate('updated_at', $today)
             ->count();

            $followup = DB::table('lead_details')->whereDate('follow_up_date', $today)->count();



                $statuses = Status::all();
                $Q_statuses = QualifiedStatus::all();
                $campaigns = Campaign::all();
                // $data = Lead::with('branch', 'agent', 'campaign')
                 $data = DB::table('leads')
                    ->when($request->has('filter_status') && $request->filter_status != null, function ($query) use ($request) {
                        $query->where('status', $request->filter_status);
                    })
                    ->when($request->has('filter_agent_id') && $request->filter_agent_id != null, function ($query) use ($request) {
                        if ($request->filter_agent_id == '-1') {
                            $query->whereNull('agent_id');
                        } else {
                            $query->where('agent_id', $request->filter_agent_id);
                        }
                    })
                    ->when($request->has('filter_campaign_id') && $request->filter_campaign_id != null, function ($query) use ($request) {
                        if ($request->filter_campaign_id == '-1') {
                            $query->whereNull('campaign_id');
                        } else {
                            $query->where('campaign_id', $request->filter_campaign_id);
                        }
                    })
                    ->when($request->has('filter_last_updated') && $request->filter_last_updated != null, function ($query) use ($request) {
                        $updatedAt = $request->filter_last_updated;
                        if ($updatedAt == 'Today') {
                            $minUdate = Carbon::now()->format('Y-m-d');
                            $maxUdate = Carbon::now()->format('Y-m-d');
                        } elseif ($updatedAt == 'Yesterday') {
                            $minUdate = Carbon::now()->subDay()->format('Y-m-d');
                            $maxUdate = Carbon::now()->subDay()->format('Y-m-d');
                        } elseif ($updatedAt == '3') {
                            $minUdate = Carbon::now()->subDays(3)->format('Y-m-d');
                            $maxUdate = Carbon::now()->subDay()->format('Y-m-d');
                        } elseif ($updatedAt == 'week') {
                            $minUdate = Carbon::now()->startOfWeek()->format('Y-m-d');
                            $maxUdate = Carbon::now()->endOfWeek()->format('Y-m-d');
                        } elseif ($updatedAt == 'month') {
                            $minUdate = Carbon::now()->startOfMonth()->format('Y-m-d');
                            $maxUdate = Carbon::now()->endOfMonth()->format('Y-m-d');
                        } elseif ($updatedAt == 'quarter') {
                            $minUdate = Carbon::now()->startOfQuarter()->format('Y-m-d');
                            $maxUdate = Carbon::now()->endOfQuarter()->format('Y-m-d');
                        } elseif ($updatedAt == 'year') {
                            $minUdate = Carbon::now()->startOfYear()->format('Y-m-d');
                            $maxUdate = Carbon::now()->endOfYear()->format('Y-m-d');
                        }
                        $query->whereBetween(DB::raw('DATE(updated_at)'), [$minUdate, $maxUdate]);
                    })
                    ->when($request->has('filter_creation_date') && $request->filter_creation_date != null, function ($query) use ($request) {
                        $createdAt = $request->filter_creation_date;
                        if ($createdAt == 'Today') {
                            $minCdate = Carbon::now()->format('Y-m-d');
                            $maxCdate = Carbon::now()->format('Y-m-d');
                        } elseif ($createdAt == 'Yesterday') {
                            $minCdate = Carbon::now()->subDay()->format('Y-m-d');
                            $maxCdate = Carbon::now()->subDay()->format('Y-m-d');
                        } elseif ($createdAt == '3') {
                            $minCdate = Carbon::now()->subDays(3)->format('Y-m-d');
                            $maxCdate = Carbon::now()->subDay()->format('Y-m-d');
                        } elseif ($createdAt == 'week') {
                            $minCdate = Carbon::now()->startOfWeek()->format('Y-m-d');
                            $maxCdate = Carbon::now()->endOfWeek()->format('Y-m-d');
                        } elseif ($createdAt == 'month') {
                            $minCdate = Carbon::now()->startOfMonth()->format('Y-m-d');
                            $maxCdate = Carbon::now()->endOfMonth()->format('Y-m-d');
                        } elseif ($createdAt == 'quarter') {
                            $minCdate = Carbon::now()->startOfQuarter()->format('Y-m-d');
                            $maxCdate = Carbon::now()->endOfQuarter()->format('Y-m-d');
                        } elseif ($createdAt == 'year') {
                            $minCdate = Carbon::now()->startOfYear()->format('Y-m-d');
                            $maxCdate = Carbon::now()->endOfYear()->format('Y-m-d');
                        }
                        $query->whereBetween(DB::raw('DATE(created_at)'), [$minCdate, $maxCdate]);
                    })
                    ->when($request->has('filter_Q_status') && $request->filter_Q_status != null, function ($query) use ($request) {
                        $query->where('approved_status', $request->filter_Q_status);
                    })

                     ->when($request->has('filter_service') && $request->filter_service != null, function ($query) use ($request) {
                        $query->where('service_id', $request->filter_service);
                    })
                    ->orderByDesc('id')
                    ->get();

                    }

                 return view('leads.index', compact('agents', 'data', 'request', 'Q_statuses', 'statuses', 'campaigns','lead_summary','leads_unassigned','leads_created_today','leads_updated_today','followup'));
            // } else {
            
            //     $data = Lead::with('branch', 'agent', 'campaign')
            //         ->where('agent_id', Auth::user()->id)
            //         ->orderByDesc('id')
            //         ->get();
            //     return view('leads.index', compact('data'));
            

            // return view('leads.index', compact('data'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function create()
    {


        try {
            $branches = Branch::all();
            $services = Service::all();
            $subServices = SubService::all();

            $channel = DB::table('channel')->get();

            if(session::get('Type')!='Admin')
            {
            $agents = DB::table('user')->where('UserID', session::get('UserID'))->get();
            }    
            else
            {
            // $agents = User::where('role', '!=', 'Admin')->get();
            $agents = DB::table('user')->where('UserType', '!=','Admin')->get();


            }
            $campaigns = Campaign::all();
            return view('leads.create', compact('branches', 'agents', 'services', 'subServices', 'campaigns','channel'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function store(Request $request)
    {

         
         try {
            DB::beginTransaction();
            // $request->validate(
            //     [
            //         'name' => 'required|max:255',
            //         'tel' => 'required|max:255',
            //         'other_tel' => 'nullable|max:255',
            //         'bussiness_details' => 'nullable|max:255',
            //         'service' => 'nullable|max:255',
            //         'channel' => 'nullable|max:255',
            //         'amount' => 'nullable|numeric|regex:/^\d{1,18}(\.\d{1,3})?$/',
            //     ],
            //     [
            //         'amount.regex' => 'Please add a valid amount i-e number with max 18 digits (quintillion) and upto 3 decimal points.',
            //     ]
            // );
            // dd($request->all());


                $leadData = array(
                'name' => $request->name,
                'tel' => $request->tel,
                'other_tel' => $request->other_tel,
                'business_details' => $request->business_details,
                'service' => $request->service,
                'channel' => $request->channel,
                'campaign_id' => $request->campaign_id,
                'branch_id' => $request->branch_id,
                'agent_id' => $request->agent_id,
                'service_id' => $request->service_id,
                'sub_service_id' => $request->sub_service_id,
                'currency' => $request->currency,
                'amount' => $request->amount,
                // 'status' => isset($request->status) ? $request->status : $lead->status,
                // 'approved_status' => !isset($request->status) ? $request->qualified_status : ($request->status == 'Qualified' ? $request->qualified_status : null),
                'created_at' => date('Y-m-d H:i:s'),

            );


 


            $data = array(
                            'PartyName' => $request->name, 
                            'Phone' => $request->tel, 
                            'Address' => $request->business_details,
                            
                            );
            

            $party = DB::table('party')->where('PartyName',$request->name)->get(); 

            if(count($party)==0)
            {

            $partyid= DB::table('party')->insertGetId($data);
            }
            else
            {
                $partyid = $party[0]->PartyID;;
            }


 
            $leadData = Arr::add($leadData, 'partyid', $partyid);


    

            $id_save= DB::table('leads')->insertGetId($leadData);
            
            
            



            DB::commit();
            return redirect('leads')->withSuccess('Lead Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
             dd($e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }


    public function show($id)
    {


        try {
            $lead = DB::table('leads')->where('id',$id)->first();
            $leadDetails = DB::table('lead_details')->where('lead_id',$id)->get();
            $leadActivity = DB::table('lead_activities')->where('lead_id',$id)->get();
              
            return view('leads.view', compact('lead','leadDetails','leadActivity'));
        } catch (\Exception $e) {
            // DB::rollBack();
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function edit($id)
    {
 

        try {
            $lead = Lead::findOrFail($id);
            $branches = Branch::all();
            $statuses = Status::all();
            $Q_statuses = QualifiedStatus::all();
            $campaigns = Campaign::all();
            $channel = DB::table('channel')->get();
            $lead_activities = LeadActivity::where('lead_id', $id)
            ->orderBy('updated_at','desc')    
            ->get();

 

            if(session::get('Type')!='Admin')
            {

                    if ($lead->branch_id != null) {
                        $agents = DB::table('user')
                            ->where('UserID', session::get('UserID'))
                            // ->where('branch_id', $lead->branch_id)
                            ->get();
                            $services = Service::where('branch_id', $lead->branch_id)->get();
                            } else {
                                $agents = DB::table('user')->where('UserType','!=', 'Admin')->get();
                                $services = Service::all();
                            }


            }

            else

            {   

                       if ($lead->branch_id != null) {
                        $agents = DB::table('user')->where('UserType', '!=', 'Admin')
                            // ->where('branch_id', $lead->branch_id)
                            ->get();
                            $services = Service::where('branch_id', $lead->branch_id)->get();
                            } else {
                                $agents = DB::table('user')->where('UserType', '!=', 'Admin')->get();
                                $services = Service::all();
                            }


            }    
         


            if ($lead->service_id != null) {
                $subServices = SubService::where('service_id', $lead->service_id)->get();
            } else {
                $subServices = SubService::all();
            }


             return view('leads.edit', compact('lead','lead_activities' ,'branches', 'agents', 'services', 'subServices', 'statuses', 'Q_statuses', 'campaigns','channel'));
        } catch (\Exception $e) {
            // DB::rollBack();
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function update(Request $request, $id)
    {
         // dump($id);
         try {
            DB::beginTransaction();
            $request->validate(
                [
                    'name' => 'required|max:255',
                    'tel' => 'required|max:255',
                    'other_tel' => 'nullable|max:255',
                    'bussiness_details' => 'nullable|max:255',
                    'service' => 'nullable|max:255',
                    'channel' => 'nullable|max:255',
                    'amount' => 'nullable|numeric|regex:/^\d{1,18}(\.\d{1,3})?$/',
                ],
                [
                    'amount.regex' => 'Please add a valid amount i-e number with max 18 digits (quintillion) and upto 3 decimal points.',
                ]
            );
            $lead = Lead::findOrFail($id);
       
            if ($request->notes != null || $request->follow_up_date != null) {
                $leadDetailData = [
                    'lead_id' => $id,
                    // 'user_id' => Auth::user()->id, org
                    'user_id' => session::get('UserID'),
                    'status_from' => $lead->status != 'Qualified' ? $lead->status : $lead->approved_status,
                    'status_to' =>  isset($request->status) && $request->status != 'Qualified' ? $request->status : $request->qualified_status,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'follow_up_date' => $request->follow_up_date != null ? $request->follow_up_date : null,
                    'notes' => $request->notes != null ? $request->notes : null,
                ];
                LeadDetails::create($leadDetailData);
            }

            $leadData = [
                'name' => $request->name,
                'tel' => $request->tel,
                'other_tel' => $request->other_tel,
                'business_details' => $request->business_details,
                'service' => $request->service,
                'channel' => $request->channel,
                'campaign_id' => $request->campaign_id,
                'branch_id' => $request->branch_id,
                'agent_id' => $request->agent_id,
                'service_id' => $request->service_id,
                'sub_service_id' => $request->sub_service_id,
                'currency' => $request->currency,
                'amount' => $request->amount,
                'status' => isset($request->status) ? $request->status : $lead->status,
                'approved_status' => !isset($request->status) ? $request->qualified_status : ($request->status == 'Qualified' ? $request->qualified_status : null),
                // 'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),

            ];


             $data = array(
                            'PartyName' => $request->name, 
                            'Phone' => $request->tel, 
                            
                            );
            

            $party = DB::table('party')->where('PartyName',$request->name)->get(); 

            if(count($party)==0)
            {

            $partyid= DB::table('party')->insertGetId($data);
            }
            else
            {
                $partyid = $party[0]->PartyID;
            }


            Arr::add($leadData, 'partyid', $partyid);

            Lead::findOrFail($id)->update($leadData);

            DB::commit();


            $item = DB::table('services')->select('code')->where('id',$request->service_id)->first();
 
               if( ($request->qualified_status=='Closed Won') && ($request->action==1)  && ($item->code!='U')  && ($item->code!='U') )
            {
                session::put('LeadID',$id);
                session::put('PartyID',$partyid);
            return redirect('InvoiceCreate/')->withSuccess('Lead Won Successfully. Now create invoice')->withInput();
            }
            elseif( ($request->qualified_status=='Closed Won') && ($request->action==1)  && ($item->code=='U'  || $item->code=='U') )
            {
                   session::put('LeadID',$id);
                   session::put('PartyID',$partyid);
            return redirect('UmrahCreate/')->withSuccess('Lead Won Successfully. Now create invoice')->withInput();
            }
            else
            {
            return redirect('leads')->withSuccess('Lead Updated Successfully');
            } 
 

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $lead = Lead::with('leadDetails')->findOrFail($id);
            $lead->leadDetails()->delete();
            $lead->delete();
            DB::commit();
            return back()->with('error', 'Lead Data Deleated Successfully')->where('class','succuess')->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function downloadFile($file)
    {
        try {
            $filePath = public_path('document/' . $file);
            if (File::exists($filePath)) {
                return response()->download($filePath, $file);
            } else {
                return back()->with('error', 'File not found.');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function import(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $request->validate([
                'file' => 'required|mimes:csv'
            ]);
            $insert_data = 0;
            set_time_limit(0);
            ini_set('max_execution_time', 36000);
            if (!isset($request->file)) {
                return redirect()->back()->with('error', 'Please Upload a file');
            } else {
                $path = $request->file('file')->getRealPath();
                $mim_type = $request->file('file')->getMimeType();
                if ($mim_type != "text/csv") {
                    return redirect()->back()->with('error', 'Please Upload CSV File Only');
                } else {
                    $records = array_map('str_getcsv', file($path));
                    $fields = $records[0];
                    // dump($fields);
                    if (!count($records) > 0) {
                        return redirect()->back()->with('error', 'you have no record in csv!');
                    } elseif (count($fields) != 12) {
                        return redirect()->back()->with('error', 'The CSV file do not match with the sample of given Csv!');
                    } elseif (count($fields) == 12) {
                        // $field_check = ['Name', 'Mobile No', 'Other No', 'Business Details', 'Service', 'Channel', 'Branch Name', 'Agent Name', 'Branch Service', 'Branch Sub Service', 'Currency', 'Quoted Amount'];
                        // foreach ($fields as $key => $record) {
                        //     if ($record != $field_check[$key]) {
                        //         return redirect()->back()->with('error', 'Your Column headers do not match with the sample of given Csv!');
                        //     }
                        // }
                        array_shift($records);
                        // dd($records);
                        foreach ($records as $key => $record) {
                            // dd($employee);
                            if ($record[0] == '') {
                                return back()->with('error', 'Import Interrupted. The name is required. on line ' . $insert_data + 2);
                            } elseif ($record[1] == '') {
                                return back()->with('error', 'Import Interrupted. The Mobile Number is required. on line ' . $insert_data + 2);
                            } else {
                                if ($record[6] != '') {
                                    $branch = Branch::where('name', $record[6])->first();
                                    if (!$branch) {
                                        return back()->with('error', 'Import Interrupted. No Branch Found for the name ' . $record[6] . '. on line ' . $insert_data + 2);
                                    } else {
                                        $branch_id = $branch->id;
                                    }
                                } else {
                                    $branch_id = null;
                                }
                                if ($record[7] != '') {
                                    if ($record[6] != '') {
                                        $agent = User::where('role', '!=', 'Admin')->where('name', $record[7])->where('branch_id', $branch_id)->first();
                                        if (!$agent) {
                                            return back()->with('error', 'Import Interrupted. No Agent Found for the name ' . $record[7] . ' in the branch ' . $record[6] . '. on line ' . $insert_data + 2);
                                        } else {
                                            $agent_id = $agent->id;
                                        }
                                    } else {
                                        $agent = User::where('role', '!=', 'Admin')->where('name', $record[7])->first();
                                        if (!$agent) {
                                            return back()->with('error', 'Import Interrupted. No Agent Found for the name ' . $record[7] . '. on line ' . $insert_data + 2);
                                        } else {
                                            $agent_id = $agent->id;
                                        }
                                    }
                                } else {
                                    $agent_id = null;
                                }
                                $data = [
                                    'name' =>  $record[0],
                                    'tel' =>  $record[1],
                                    'other_tel' =>  $record[2] != '' ? $record[2] : null,
                                    'business_details' =>  $record[3] != '' ? $record[3] : null,
                                    'service' =>  $record[4] != '' ? $record[4] : null,
                                    'channel' =>  $record[5] != '' ? $record[5] : null,
                                    'branch_id' =>  $record[6] != '' ? $branch_id : null,
                                    'agent_id' =>  $record[7] != '' ? $agent_id : null,
                                    // 'service_id' =>  $record[8] != '' ? $record[8] : null,
                                    // 'sub_service_id' =>  $record[9] != '' ? $record[9] : null,
                                    'service_id' =>  null,
                                    'sub_service_id' => null,
                                    'currency' =>  $record[10] != '' ? $record[10] : null,
                                    'amount' =>  $record[11] != '' ? $record[11] : null,
                                ];
                                Lead::create($data);
                                $insert_data++;
                            }
                        }
                    }
                }
            }
            DB::commit();
            return back()->withSuccess('Data Imported successfully. ' . $insert_data . ' Rows Imported.');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function addLeadNote(Request $request)
    {
     
        try {
            $request->validate([
                // 'notes' => 'required'
            ]);
            DB::beginTransaction();
            $lead = Lead::findOrFail($request->lead_id);
            $leadDetailData = [
                'lead_id' => $request->lead_id,
                // 'user_id' => Auth::user()->id,
                'status_from' => $lead->status != 'Qualified' ? $lead->status : $lead->approved_status,
                // 'status_to' =>  $request->status != 'Qualified' ? $request->status : $request->qualified_status,
                'date' => Carbon::now()->format('Y-m-d'),
                'follow_up_date' => $request->follow_up_date,
                'notes' => $request->notes
            ];
            LeadDetails::create($leadDetailData);
            DB::commit();
            return back()->withSuccess('Note Added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function bulkDeleteLeads(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $ids = $request->ids;
            // dd($ids);
            foreach ($ids as $id) {
                // dump($id);
                $data = Lead::with('leadDetails')
                    ->findOrFail($id);
                $data->leadDetails()->delete();
                $data->delete();
                // dump($data);
            }
            DB::commit();
            // dd('end');
            return response()->json(['success' => 'Leads Data Deleted Successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function bulkReassignLeads(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $agent = User::findOrFail($request->agent_id);
            $branch = Branch::findOrFail($agent->branch_id);
            $ids = $request->ids;
            foreach ($ids as $id) {
                Lead::findOrFail($id)->update([
                    'branch_id' => $branch->id,
                    'agent_id' => $agent->id,
                ]);
            }
            DB::commit();
            return response()->json(['success' => 'Leads Reassigned Successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function bulkReassignNewLeads(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $agent = User::findOrFail($request->agent_id);
            $branch = Branch::findOrFail($agent->branch_id);
            $ids = $request->ids;
            foreach ($ids as $id) {
                $data = Lead::with('leadDetails')
                    ->findOrFail($id);
                $data->leadDetails()->delete();
                Lead::findOrFail($id)->update([
                    'branch_id' => $branch->id,
                    'agent_id' => $agent->id,
                    'status' => 'Pending',
                    'approved_status' => NULL
                ]);
            }
            DB::commit();
            return response()->json(['success' => 'Leads Reassigned Successfully As New']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }


     public function fetchLeads(Request $request)
    {
        $status = $request->input('status');
        $leads = DB::table('leads')->where('status', $status)->get();
        return response()->json(['leads' => $leads]);
    }



}
