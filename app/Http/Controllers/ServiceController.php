<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        try {
            $branches = Branch::all();
            $data = Service::with('branch')->get();
            return view('services.index', compact('data', 'branches'));
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
                'branch_id' => 'required'
            ]);
            Service::create($request->except('_token'));
            DB::commit();
            return back()->withSuccess('Service Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $data = Service::findOrFail($id);
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
                'branch_id' => 'required'
            ]);
            Service::findOrFail($request->service_id)->update($request->except('_token', 'service_id'));
            DB::commit();
            return back()->withSuccess('Service Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $data = Service::with('subService')->findOrFail($id);
            $data->subService()->delete();
            $data->delete();
            DB::commit();
            return back()->withSuccess('Service Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
}
