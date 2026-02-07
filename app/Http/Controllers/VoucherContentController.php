<?php

namespace App\Http\Controllers;

 
 use Session;
use Illuminate\Http\Request;
use App\Models\VoucherContent;
use Yajra\DataTables\DataTables;


class VoucherContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

 
     $data = VoucherContent::all();
     
        $title = 'Voucher Content';
          
         try{
            if ($request->ajax()) {
                $data = VoucherContent::all();

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
    
             return view('voucher_content.index',compact('title','data'));
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
         
        'description'      => 'required|string',
        'contact1'      => 'required|string|max:255',
        'contact3'      => 'required|string|max:255',
        
         ];

        

        $validated = $request->validate($rules);

       try {
        
        $data = $request->only([
            'description','contact1','contact2','contact3','contact4','contact5','contact6'
        ]);

       $data['BranchID'] = Session::get('BranchID'); 
 
 
        $news = VoucherContent::updateOrCreate(
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
    $data = VoucherContent::findOrFail($id);
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
        $faculty = VoucherContent::find($id);

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