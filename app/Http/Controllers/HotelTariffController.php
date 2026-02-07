<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Party;
use App\Models\Location;
use App\Models\HotelTariff;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class HotelTariffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Hotel Tariffs";
         try{
            if ($request->ajax()) {
                $data = HotelTariff::latest()->get();

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
        
        $location = Location::getLocationList();    
        $hotel = Hotel::getHotelList();    
        $party = Party::getSupplierList();    
    
            return view('hotel_tariff.index',compact('title','location','hotel','party'));

        } catch (\Exception $e){
             return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }


    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    try {
        HotelTariff::updateOrCreate(
            ['id' => $request->id],
            [   
                'location'         => $request->location,
                'sr_no'            => $request->sr_no,
                'room_type'        => $request->room_type,
                'date_from'        => $request->date_from,
                'date_to'          => $request->date_to,
                'room_status'      => $request->room_status,
                'purchase_price'   => $request->purchase_price,
                'sale_price'       => $request->sale_price,
                'triple_purchase'  => $request->triple_purchase,
                'triple_sale'      => $request->triple_sale,
                'double_purchase'  => $request->double_purchase,
                'double_sale'      => $request->double_sale,
                'quint_purchase'   => $request->quint_purchase,
                'quint_sale'       => $request->quint_sale,
                'quad_purchase'    => $request->quad_purchase,
                'quad_sale'        => $request->quad_sale,
                'package_name'     => $request->package_name,
                'hotel_name'       => $request->hotel_name,
                'PartyID'          => $request->PartyID,
                'BranchID'         => $request->BranchID,
                'is_active'        => $request->is_active,
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
    $data = HotelTariff::findOrFail($id);
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
    $hotelTariff = HotelTariff::find($id);

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