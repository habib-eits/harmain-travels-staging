<?php

namespace App\Http\Controllers;

use App\Models\QualifiedStatus;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class StatusController extends Controller
{
    public function index()
    {

         try {
            $data = Status::all();
            return view('statuses.index', compact('data',));
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            $request->validate(
                [
                    'name' => 'required|max:255|unique:statuses,name',
                ],
                [
                    'name.unique' => "The name has already been taken. Please Try another."
                ]
            );
            Status::create($request->except('_token'));
            DB::commit();
            return back()->withSuccess('Status Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $data = Status::findOrFail($id);
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
            $request->validate(
                [
                    'name' => 'required|max:255|unique:statuses,name,' . $request->status_id . ',id',
                ],
                [
                    'name.unique' => "The name has already been taken. Please Try another."
                ]
            );
            Status::findOrFail($request->status_id)->update($request->except('_token', 'status_id'));
            DB::commit();
            return back()->withSuccess('Status Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return back()->withError($e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            Status::findOrFail($id)->delete();
            DB::commit();
            return back()->withSuccess('Status Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
    // ------------------Qualified Status Methods------------
    public function qualifiedStatusIndex()
    {
        try {
            $data = QualifiedStatus::all();
            return view('statuses.qualifiedStatusIndex', compact('data',));
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
    public function qualifiedStatusStore(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            $request->validate(
                [
                    'name' => 'required|max:255|unique:qualified_statuses,name',
                ],
                [
                    'name.unique' => "The name has already been taken. Please Try another."
                ]
            );
            QualifiedStatus::create($request->except('_token'));
            DB::commit();
            return back()->withSuccess('Qualified Status Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }

    public function qualifiedStatusEdit($id)
    {
        try {
            $data = QualifiedStatus::findOrFail($id);
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function qualifiedStatusUpdate(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            $request->validate(
                [
                    'name' => 'required|max:255|unique:qualified_statuses,name,' . $request->q_status_id . ',id',
                ],
                [
                    'name.unique' => "The name has already been taken. Please Try another."
                ]
            );
            QualifiedStatus::findOrFail($request->q_status_id)->update($request->except('_token', 'q_status_id'));
            DB::commit();
            return back()->withSuccess('Qualified Status Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return back()->withError($e->getMessage());
        }
    }
    public function qualifiedStatusDelete($id)
    {
        try {
            DB::beginTransaction();
            QualifiedStatus::findOrFail($id)->delete();
            DB::commit();
            return back()->withSuccess('Qualified Status Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
}
