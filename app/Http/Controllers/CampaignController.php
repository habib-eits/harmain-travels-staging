<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function index()
    {
         try {
            $data = Campaign::all();
            return view('campaigns.index', compact('data',));
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            $request->validate([
                'name' => 'required|max:255|unique:campaigns,name',
            ]);
            Campaign::create($request->except('_token'));
            DB::commit();
            return back()->withSuccess('Campaign Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $data = Campaign::findOrFail($id);
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function update(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            $request->validate([
                'name' => 'required|max:255|unique:campaigns,name,' . $request->campaign_id . ',id',
            ]);
            Campaign::findOrFail($request->campaign_id)->update($request->except('_token', 'campaign_id'));
            DB::commit();
            return back()->withSuccess('Campaign Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            Campaign::findOrFail($id)->delete();
            DB::commit();
            return back()->withSuccess('Campaign Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
}
