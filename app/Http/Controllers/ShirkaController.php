<?php

namespace App\Http\Controllers;

 
use App\Models\Shirka;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class ShirkaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

 
     $data = Shirka::all();
     
        $title = 'Shirka';
          
         try{
            if ($request->ajax()) {
                $data = Shirka::all();

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
    
             return view('shirka.index',compact('title','data'));
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
         
        'shirka_name'      => 'required|string|max:255',
        
         ];

        // Check if ID is present and greater than 0 (i.e., editing)
        if ($request->input('id') > 0) {
            $rules['logo'] = 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048';
        } else {
            $rules['logo'] = 'required|file|mimes:jpeg,png,jpg,webp|max:2048';
        }

        $validated = $request->validate($rules);

       try {
        
        $data = $request->only([
            'shirka_name',
        ]);

         
        // File uploads
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('shirka_logo', 'public');
        }
        

       

        $news = Shirka::updateOrCreate(
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
    $data = Shirka::findOrFail($id);
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
        $faculty = Shirka::find($id);

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