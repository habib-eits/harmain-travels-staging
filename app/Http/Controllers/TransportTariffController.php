<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Sector;


use Illuminate\Http\Request;
use App\Models\TransportType;
use App\Models\TransportStatus;
use App\Models\TransportTariff;
use Yajra\DataTables\DataTables;


class TransportTariffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Transport Tariff";
         try{
            if ($request->ajax()) {
                $data = TransportTariff::with(['party', 'supplier'])->orderBy('date_from', 'desc')->get();


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
        
        $party = Party::getPartyList('C');    
        $supplier = Party::getSupplierList();    
        $transport_type = TransportType::all();
        $transport_status = TransportStatus::all();
        $sector = Sector::all();
        $data = TransportTariff::with(['party', 'supplier'])->orderBy('date_from', 'desc')->get();


        return view('transport_tariff.index',compact('title','supplier','party','transport_type','transport_status','sector'));

        }catch (\Exception $e){
             return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }


public function store(Request $request)
{

$validated = $request->validate([
    'date_from'           => 'required|date',
    'date_to'             => 'required|date',
    'sector'              => 'nullable|string',
    'vehicle_type'        => 'required|string',
    'status'              => 'required|string',
    // 'PartyID'             => 'required|string',
    'SupplierID'          => 'required|string',
    'purchase_price'      => 'required|numeric',
    'sale_price'          => 'required|numeric',
    'transport_category'  => 'required|string',
], [
    'date_from.required'          => 'Please select a starting date.',
    'date_from.date'              => 'The starting date must be a valid date.',
    'date_to.required'            => 'Please select an ending date.',
    'date_to.date'                => 'The ending date must be a valid date.',
    'vehicle_type.required'       => 'Please select the vehicle type.',
    'status.required'             => 'Please select the status.',
    'PartyID.required'            => 'Please select the customer.',
    'SupplierID.required'         => 'Please select the supplier.',
    'purchase_price.required'     => 'Please enter the purchase price.',
    'purchase_price.numeric'      => 'The purchase price must be a number.',
    'sale_price.required'         => 'Please enter the sale price.',
    'sale_price.numeric'          => 'The sale price must be a number.',
    'transport_category.required' => 'Please select a transport category.',
]);


    try {
        $data = $request->only([
            'date_to',
            'date_from',
            'sector',
            'vehicle_type',
            'status',
            // 'PartyID',
            'SupplierID',
            'purchase_price',
            'sale_price',
            'transport_category',
            'is_active',
        ]);

        
        TransportTariff::updateOrCreate(
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
    $data = TransportTariff::findOrFail($id);
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
    $hotelTariff = TransportTariff::find($id);

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