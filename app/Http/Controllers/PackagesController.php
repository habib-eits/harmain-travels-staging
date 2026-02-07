<?php

namespace App\Http\Controllers;

 
 use App\Models\Packages;
use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

 
     $data = Packages::all();
     
        $title = 'Packages';
          
         try{
            if ($request->ajax()) {
                $data = Packages::all();

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
    
             return view('packages.index',compact('title','data'));
         }catch (\Exception $e){
             return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }


    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{


         $rules = [
         
        'name'      => 'required|string|max:255',
        
         ];

        

        $validated = $request->validate($rules);

       try {
        
        $data = $request->only([
            'name','is_active'
        ]);

       $data['BranchID'] = Session::get('BranchID'); 
       $data['UserID'] = Session::get('UserID'); 

 
        $news = Packages::updateOrCreate(
            ['ID' => $request->id],
            $data
        );

        

 
        return response()->json([
            'success' => true,
            'message' => 'Record added successfully.',
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
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
    $data = Packages::findOrFail($id);
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
        $faculty = Packages::find($id);

        if (!$faculty) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found.',
            ], 404);
        }

        $faculty->delete();

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