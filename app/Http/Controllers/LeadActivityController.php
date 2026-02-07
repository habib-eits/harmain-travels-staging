<?php

namespace App\Http\Controllers;

use App\Models\LeadActivity;
use App\Http\Requests\StoreLeadActivityRequest;
use App\Http\Requests\UpdateLeadActivityRequest;
use App\Models\LeadDetails;

use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Lead;
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

class LeadActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLeadActivityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
                // 'status_from' => $lead->status != 'Qualified' ? $lead->status : $lead->approved_status,
                // 'status_to' =>  $request->status != 'Qualified' ? $request->status : $request->qualified_status,
                // 'date' => Carbon::now()->format('Y-m-d'),
                'date' => $request->date,
                'description' => $request->description,
                
            ];



           $data = array('updated_at' => date('Y-m-d H:i:s') ); 
            $lead= DB::table('leads')->where('id' , '=' , $request->lead_id)->update($data);
            
            LeadActivity::create($leadDetailData);
           
            DB::commit();
            DB::table('leads')->where('id', $request->lead_id)->update(['updated_at' => now()]);
            return back()->withSuccess('Note Added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeadActivity  $leadActivity
     * @return \Illuminate\Http\Response
     */
    public function show(LeadActivity $leadActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeadActivity  $leadActivity
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activity = LeadActivity::find($id);
        return response()->json($activity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLeadActivityRequest  $request
     * @param  \App\Models\LeadActivity  $leadActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)


    {
        try {
            $request->validate([
                'lead_id' => 'required|integer',
                'description' => 'required|string',
                'date' => 'nullable|date',
            ]);

             // Find the activity
            $activity = LeadActivity::findOrFail($id);

            // Check if the activity was created within the last 2 hours
            $createdAt = Carbon::parse($activity->created_at);
            $now = Carbon::now();
            $diffInHours = $createdAt->diffInHours($now);

            if ($diffInHours > 2) {
                // Redirect or return an error response indicating that editing is not allowed
                return redirect()->back()->with('error', 'Editing activities is allowed only within 2 hours of creation.');
            }

            DB::beginTransaction();

            $leadActivity = LeadActivity::findOrFail($id);

            $leadActivity->update([
                'lead_id' => $request->lead_id,
                'date' => $request->date ?? $leadActivity->date,  // Use provided date or keep existing date
                'description' => $request->description,
            ]);


            $data = array('updated_at' => date('Y-m-d H:i:s') ); 
            $lead= DB::table('leads')->where('id' , '=' , $request->lead_id)->update($data);

            

            DB::commit();
            DB::table('leads')->where('id', $request->lead_id)->update(['updated_at' => now()]);
            return back()->withSuccess('Note Updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeadActivity  $leadActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadActivity $leadActivity)
    {
        //
    }

    public function addLeadNote(Request $request)
    {
     
        
    }



}
