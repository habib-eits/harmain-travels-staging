<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        
       
        try{
            if ($request->ajax()) {
                
                $data = Party::all();
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
                                            <a href="javascript:void(0)" onclick="editRecord(' . $row->PartyID . ')" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                         <li>
                                            <a href="javascript:void(0)" onclick="deleteRecord(' . $row->PartyID . ')" class="dropdown-item">
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
    
            return view('parties.index');

        }catch (\Exception $e){
            return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        
        
        Party::updateOrCreate([
            'PartyID' => $request->PartyID
        ],
        [
            'PartyName' => $request->PartyName,
            'TRN' => $request->TRN,
            'Address' => $request->Address,
            'Phone' => $request->Phone,
            'Mobile' => $request->Mobile,
            'Website' => $request->Website,
            'Email' => $request->Email,
            'Password' => $request->Password,
            'Active' => $request->Active,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Record added successfully.',
        ],200);
    }

  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Party::findOrFail($id);
        return response()->json($data);

    }

   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($this->isDeleteable($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Record cannot be deleted as it is associated with other records.',
            ], 400);
        }
        
        Party::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully.',
        ],200);

    }

    private function isDeleteable($id)
    {
        return DB::table('invoice_master')->where('PartyID', $id)->exists();
    }
}

