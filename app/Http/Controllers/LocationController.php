<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Location";
        $routeName = 'location';
        try{
            if ($request->ajax()) {
                $data = Location::latest()->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                   

                    ->addColumn('action', function ($row) {
                        $btn = '
                            <div class="d-flex align-items-center col-actions">
                                <div class="dropdown">
                                    <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="javascript:void(0)" onclick="editRecord(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                         <li>
                                            <a href="javascript:void(0)" onclick="deleteRecord(' . $row->id . ')" class="dropdown-item">
                                                <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                            </a>
                                        </li>
                                       
                                       
                                    </ul>
                                </div>
                            </div>';
    
                   
                    return $btn;
                   
                    })
                    
                    
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }
    
            return view('locations.index',compact('title','routeName'));

        }catch (\Exception $e){
            dd($e->getMessage());
            return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }


    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    try {
        Location::updateOrCreate(
            ['id' => $request->id],
            [
                'location'  => $request->location,
                'BranchID'  => session('BranchID'),
                'is_active' => $request->is_active,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Record added successfully.',
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while saving the record.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}




  
    /**
     * Show the form for editing the specified resource.
     */
 


public function edit(string $id)
{
    try {
    $data = Location::findOrFail($id);
    return response()->json([
        'success' => true,
        'data'    => $data
    ], 200);

} catch (\Exception $e) {
    return response()->json([
        'success' => false,
        'message' => 'An error occurred.',
        'error'   => $e->getMessage(),
    ], 500);
}

}    

   
public function destroy(string $id)
{
    try {
        $location = Location::find($id);

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found.',
            ], 404);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully.',
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the record.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}

}