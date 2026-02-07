<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = Branch::all();
            return view('branches.index', compact('data'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    // public function create()
    // {
    //     try {
    //         return view('branches.create');
    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage())->withInput();
    //     }
    // }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255|unique:branches,name',
                'location' => 'nullable|max:255',
                'tel' => 'nullable|max:255',
                'email' => 'nullable|email|max:255|unique:branches,email',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            DB::beginTransaction();

            $data = $request->except('_token', 'logo');

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo'] = $logoPath;
            }

            Branch::create($data);

            DB::commit();
            return redirect('branches')->with('success', 'Branch Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function edit($id)
    {
        try {
            $data = Branch::findOrFail($id);
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255|unique:branches,name,' . $request->id . ',id',
                'location' => 'nullable|max:255',
                'tel' => 'nullable|max:255',
                'email' => 'nullable|email|max:255|unique:branches,email,' . $request->id . ',id',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            DB::beginTransaction();

            $branch = Branch::findOrFail($request->id);
            $data = $request->except('_token', 'id', 'logo');

            if ($request->hasFile('logo')) {
                // Delete the old logo if it exists
                if ($branch->logo && \Storage::disk('public')->exists($branch->logo)) {
                    \Storage::disk('public')->delete($branch->logo);
                }
                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo'] = $logoPath;
            }

            $branch->update($data);

            DB::commit();
            return redirect()->route('branches.index')->with('success', 'Branch updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            Branch::findOrFail($id)->delete();
            DB::commit();
            return back()->withSuccess('Branch Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
}
