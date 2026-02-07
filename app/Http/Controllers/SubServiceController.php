<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\SubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubServiceController extends Controller
{
    public function index()
    {
        try {
            $services = Service::all();
            $data = SubService::with('service')->get();
            return view('subservices.index', compact('data', 'services'));
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
                // 'name' => 'required|max:255|unique:services,name',
                'name' => 'required|max:255',
                'service_id' => 'required'
            ]);
            SubService::create($request->except('_token'));
            DB::commit();
            return back()->withSuccess('Sub Service Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $data = SubService::findOrFail($id);
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
                // 'name' => 'required|max:255|unique:services,name',
                'name' => 'required|max:255',
                'service_id' => 'required'
            ]);
            SubService::findOrFail($request->sub_service_id)->update($request->except('_token', 'sub_service_id'));
            DB::commit();
            return back()->withSuccess('Sub Service Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            SubService::findOrFail($id)->delete();
            DB::commit();
            return back()->withSuccess('Sub Service Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
}
