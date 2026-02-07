<?php

namespace App\Http\Controllers;

use App\Models\Pnr;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PnrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pnrs = Pnr::with(['user', 'branch'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('pnr.index', compact('pnrs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('UserID',Session::get('UserID'))->get();
        $branches = Branch::all();
        
        return view('pnr.create', compact('users', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'branch_id' => 'required',
            'UserID' => 'required',
            'pnr' => 'required|string|max:255|unique:pnr,pnr',
            'FlightNoDeparture' => 'nullable|string|max:255',
            'SectorDeparture' => 'nullable|string|max:255',
            'FlightDateDeparture' => 'nullable|date',
            'FlightTimeDeparture' => 'nullable|string|max:255',
            'FlightDateArrivalDeparture' => 'nullable|date',
            'FlightTimeArrivalDeparture' => 'nullable|string|max:255',
            'FlightNoReturn' => 'nullable|string|max:255',
            'SectorReturn' => 'nullable|string|max:255',
            'FlightDateReturn' => 'nullable|date',
            'FlightDepartureTimeReturn' => 'nullable|string|max:255',
            'FlightArrivalDateReturn' => 'nullable|date',
            'FlightArrivalTimeReturn' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Pnr::create($request->all());

        return redirect()->route('pnr.index')
            ->with('success', 'PNR created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pnr = Pnr::with(['user', 'branch'])->findOrFail($id);
        
        return view('pnr.show', compact('pnr'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pnr = Pnr::findOrFail($id);
        $users = User::where('UserID',Session::get('UserID'))->get();
        $branches = Branch::all();
        
        return view('pnr.edit', compact('pnr', 'users', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pnr = Pnr::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
            'UserID' => 'required',
            'pnr' => 'required|string|max:255|unique:pnr,pnr,' . $id,
            'FlightNoDeparture' => 'nullable|string|max:255',
            'SectorDeparture' => 'nullable|string|max:255',
            'FlightDateDeparture' => 'nullable|date',
            'FlightTimeDeparture' => 'nullable|string|max:255',
            'FlightDateArrivalDeparture' => 'nullable|date',
            'FlightTimeArrivalDeparture' => 'nullable|string|max:255',
            'FlightNoReturn' => 'nullable|string|max:255',
            'SectorReturn' => 'nullable|string|max:255',
            'FlightDateReturn' => 'nullable|date',
            'FlightDepartureTimeReturn' => 'nullable|string|max:255',
            'FlightArrivalDateReturn' => 'nullable|date',
            'FlightArrivalTimeReturn' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pnr->update($request->all());

        return redirect()->route('pnr.index')
            ->with('success', 'PNR updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pnr = Pnr::findOrFail($id);
        $pnr->delete();

        return redirect()->route('pnr.index')
            ->with('success', 'PNR deleted successfully.');
    }

    /**
     * Get PNR by PNR number (existing method)
     */
    public function getPnr($pnr)
    {
        $pnr = Pnr::where('pnr', $pnr)->first();
        return response()->json($pnr);
    }
}