<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Sector;


use App\Models\VisaTariff;
use App\Models\VisaCategory;
use Illuminate\Http\Request;
use App\Models\TransportType;
use App\Models\TransportStatus;
use Yajra\DataTables\DataTables;
use App\Models\VisaType;


class VisaTariffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Visa Tariff";
         try{
            if ($request->ajax()) {
                $data = VisaTariff::with(['party', 'supplier'])->orderBy('date_from', 'desc')->get();


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
        
        $party = Party::getPartyList();    
        $supplier = Party::getSupplierList();    
        $visa_type = VisaType::all();
        $visa_category = VisaCategory::all();
        $data = VisaTariff::with(['party', 'supplier'])->orderBy('date_from', 'desc')->get();
        


        return view('visa_tariff.index',compact('title','supplier','party','visa_type','visa_category','data'));

        }catch (\Exception $e){
             return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }


public function store(Request $request)
{

$validated = $request->validate([
    'date_from'           => 'required|date',
    'date_to'             => 'required|date',
    'visa_type'           => 'required|string',
    'PartyID'             => 'required|string',
    'SupplierID'          => 'required|string',
    'purchase_price'      => 'required|numeric',
    'sale_price'          => 'required|numeric',
    'visa_category'       => 'required|string',
], [
    'date_from.required'      => 'Please enter the start date.',
    'date_from.date'          => 'The start date must be a valid date.',
    'date_to.required'        => 'Please enter the end date.',
    'date_to.date'            => 'The end date must be a valid date.',
    'visa_type.required'      => 'Please select a visa type.',
    'visa_type.string'        => 'Visa type must be a valid string.',
    'PartyID.required'        => 'Please select a party.',
    'PartyID.string'          => 'Party must be a valid string.',
    'SupplierID.required'     => 'Please select a supplier.',
    'SupplierID.string'       => 'Supplier must be a valid string.',
    'purchase_price.required' => 'Please enter the purchase price.',
    'purchase_price.numeric'  => 'Purchase price must be a number.',
    'sale_price.required'     => 'Please enter the sale price.',
    'sale_price.numeric'      => 'Sale price must be a number.',
    'visa_category.required'  => 'Please select a visa category.',
    'visa_category.string'    => 'Visa category must be a valid string.',
]);

    try {
        $data = $request->only([
            'date_to',
            'date_from',
            
            'visa_type',
            
            'PartyID',
            'SupplierID',
            'purchase_price',
            'sale_price',
            'visa_category',
            
        ]);

        
        VisaTariff::updateOrCreate(
            ['id' => $request->id],
            $data
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
    $data = VisaTariff::findOrFail($id);
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
    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
try {
    $hotelTariff = VisaTariff::find($id);

    if (!$hotelTariff) {
        return response()->json([
            'success' => false,
            'message' => 'Record not found.',
        ], 404);
    }

    $hotelTariff->delete();

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