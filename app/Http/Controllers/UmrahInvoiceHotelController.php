<?php

namespace App\Http\Controllers;

use App\Models\Packages;
use App\Models\InvoiceHotel;
use App\Models\JournalDummy;
use Illuminate\Http\Request;
use App\Models\InvoiceTransport;
use Yajra\DataTables\DataTables;
use App\Models\UmrahInvoiceMaster;
use Illuminate\Support\Facades\DB;
use App\Models\UmrahInvoicePassenger;
use Illuminate\Support\Facades\Validator;

class UmrahInvoiceHotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
       
        try{
            if ($request->ajax()) {
                // $data = UmrahInvoicePassenger::all();
                $data = InvoiceHotel::with(['hotel_name'])->where('InvoiceMasterID', $request->umrah_invoice_master_id)->get();
                 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                   
                    ->addcolumn('CheckInDate',function($row){
                        return date('d/m/Y',strtotime($row->CheckInDate));
                    })
                    
                    ->addcolumn('CheckOutDate',function($row){
                        return date('d/m/Y',strtotime($row->CheckOutDate));
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                                <div class="d-flex">
                                    <a href="javascript:void(0)" onclick="hotelEdit(' . $row->id . ')" class="dropdown-item">
                                        <i class="bx bx-pencil font-size-16 text-secondary me-1"></i>
                                    </a>
                                </div>
                            
                        ';
    
 
                    return $btn;
                   
                    })
    
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }


      $data = InvoiceHotel::where('InvoiceMasterID', $request->umrah_invoice_master_id)->get();
            // return view('umrah.invoice_masters.index');

        }catch (\Exception $e){

            return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('umrah.invoice_masters.create');
        // return view('umrah.invoice_masters.passanger');
        // return view('umrah.invoice_masters.hotel_acc');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // Start a transaction
        DB::beginTransaction();

        try {

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'umrah_invoice_master_id' => 'required',
                 'Nights' => 'required|numeric|min:1',
                 'SupplierID' => 'required',

            ],[
                'umrah_invoice_master_id.required' => 'Please submit Invoice Master Data First',
                'SupplierID.required' => 'Hotel Supplier Required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            // $data = $request->all();// storing request data in array

                $data = $request->only([
                'HotelCity',
                'CheckInDate',
                'CheckOutDate',
                'Nights',
                'hotel_id',
                'RoomType',
                'RoomStatus',
                'NoOfRooms',
                'HotelPax',
                'HotelPurchase',
                'HotelSale',
                'HotelPayable',
                'HotelReceivable',
                'Origin',
                'Destination',
                'SupplierID',
                'StockStatus',
                'ExRatePurchaseHotel',
                'ExRateSaleHotel',
                'HCN_NO',
                'RoomView',
                'MealPlan',
             ]);

           // Merge session values
            $data = array_merge($data, [
                'UserID'   => session('UserID'),
                'BranchID' => session('BranchID'),
            ]);

                $data['InvoiceMasterID'] = $request->umrah_invoice_master_id;

   
            if ($request->invoice_hotel_id != null) {
                // Fetch the existing UmrahInvoiceMaster instance and update it
                $umrahInvoiceMaster = InvoiceHotel::find($request->invoice_hotel_id);
                $umrahInvoiceMaster->update($data);
                $action="Update";
                $message = "Hotel Accomodation Update successfully.";
             } else {
                // Create a new UmrahInvoiceMaster instance
                $umrahInvoiceMaster = InvoiceHotel::create($data);
                $message = "Hotel Accomodation Create successfully.";
                $action="Create";
            }


          $totals = InvoiceHotel::where('InvoiceMasterID', $request->umrah_invoice_master_id)
                    ->selectRaw('
                        SUM(HotelPayable) as HotelPayable,
                        SUM(HotelReceivable) as HotelReceivable,
                        SUM(HotelPayable * ExRatePurchaseHotel) as forex_HotelPayable,
                        SUM(HotelReceivable * ExRateSaleHotel) as forex_HotelReceivable
                    ')
                    ->first();

            $this->JournalEntriesHotel($request,$umrahInvoiceMaster->id,$action);  

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => $message,
                 'data' => [
                'HotelPayable' => $totals->HotelPayable,
                'HotelReceivable' => $totals->HotelReceivable,
                'forex_HotelPayable' => $totals->forex_HotelPayable,
                'forex_HotelReceivable' => $totals->forex_HotelReceivable,
            ],
            ],200);
        

            
        } catch (\Exception $e) {
            
            DB::rollBack();// Rollback the transaction if there's an error

            // Return a JSON response with an error message
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data = InvoiceHotel::findOrFail($id);
           return response()->json($data);

        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        DB::beginTransaction();// Start a transaction

        try {
            $data = InvoiceHotel::findOrFail($id);
            
            // Delete the data record
            $data->delete();

            
          $totals = InvoiceHotel::where('InvoiceMasterID', $data->InvoiceMasterID)
                    ->selectRaw('
                        SUM(HotelPayable) as HotelPayable,
                        SUM(HotelReceivable) as HotelReceivable,
                        SUM(HotelPayable * ExRatePurchaseHotel) as forex_HotelPayable,
                        SUM(HotelReceivable * ExRateSaleHotel) as forex_HotelReceivable
                    ')
                    ->first();

            JournalDummy::where('Trace', 'like', $id . '-%')->delete();


            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Delete successfully.',
                 'data' => [
                'HotelPayable' => $totals->HotelPayable ?? 0,
                'HotelReceivable' => $totals->HotelReceivable ?? 0,
                'forex_HotelPayable' => $totals->forex_HotelPayable ?? 0,
                'forex_HotelReceivable' => $totals->forex_HotelReceivable ?? 0,
            ],
                ],200);
                
        } catch (\Exception $e) {
          
            DB::rollBack();  // Rollback the transaction if there is an error

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
        
    }

    public function updateAllPaxRate(Request $request)
    {
        
        // Start a transaction
        DB::beginTransaction();


        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'umrah_invoice_master_id' => 'required',
                'shirka_name' => 'required',
                'visa_sale' => 'required',
                'HotelReceivable' => 'required',
                'visa_purchase' => 'required',
                'HotelPayable' => 'required',
                'ExRatePurchaseHotel' => 'required',
                'ExRateSaleHotel' => 'required',

            ],[
                'umrah_invoice_master_id.required' => 'Please submit Invoice Master Data First'
            ]);

            $umrahInvoiceMaster = InvoiceTransport::find($request->umrah_invoice_master_id);
            if($umrahInvoiceMaster)
            {
                $validator->after(function ($validator) use ($umrahInvoiceMaster) {
                    if ($umrahInvoiceMaster->passengers()->count() < 1) {
                        $validator->errors()->add('field', 'Please Add atleast one passenger');
                    }
                });
            }
           

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            InvoiceTransport::where('umrah_invoice_master_id',$request->umrah_invoice_master_id)
            ->update([
                'shirka_name' => $request->shirka_name,
                'visa_sale' => $request->visa_sale,
                'HotelReceivable' => $request->HotelReceivable,
                'visa_purchase' => $request->visa_purchase,
                'HotelPayable' => $request->HotelPayable,
                'ExRatePurchaseHotel' => $request->ExRatePurchaseHotel,
                'ExRateSaleHotel' => $request->ExRateSaleHotel,
            ]);

            
            
            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => "All Passenger Rate Updated",
            ],200);
        
        
        } catch (\Exception $e) {
            DB::rollBack();// Rollback the transaction if there's an error

            // Return a JSON response with an error message
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }


    public function getTaxInclusiveAmount($amount,$tax_per)
{
    
     $taxPercent =  $tax_per;
    return round($amount * ($taxPercent / (100 + $taxPercent)), 2);
}

    public function JournalEntriesHotel(Request $request,$id,$action)
{   

      $packageName = Packages::where('id', $request->package_id)->pluck('name')->first();

      
  
        $hotel__sale =  $request->HotelReceivable;
        $hotel__purchase = $request->HotelPayable;

        // Gross commission (including VAT)
        $commission = $hotel__sale - $hotel__purchase;
 
        $vat = $this->getTaxInclusiveAmount($commission, env('VAT_PERCENTAGE'));
        $net_commission = $commission - $vat;
     
         // A/R
        $ticket_AR = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '110400',  // A/R
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->PartyID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Dr' => $request->HotelReceivable,

        'Narration' => 'Hotel Booking - ' . $request->HotelName .
               ' in ' . $request->HotelCity .
               ' | Check-In: ' . $request->CheckInDate .
               ' | Check-Out: ' . $request->CheckOutDate .
               ' | Nights: ' . $request->Nights .
               ' | Room: ' . $request->RoomType .
               ' | Pax: ' . $request->HotelPax .
               ' | HCN: ' . $request->HCN_NO,
        
        'City' => $request->HotelCity,
        'CheckIn' => $request->CheckInDate,
        'CheckOut' => $request->CheckOutDate,
        'Nights' => $request->Nights,
        'HotelName' => $request->HotelName,
        'RoomType' => $request->RoomType,
        'PaxNumber' => $request->HotelPax,
        'HCN' => $request->HCN_NO,

        
        
        'Trace' => $id.'-HAR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->SaleCurrency,
        'Rate' => $request->ExRateSaleHotel,
        'ForeignAmount' => $request->HotelReceivable,
        'ForeignDebit' => ($request->HotelReceivable * $request->ExRateSaleHotel),
        ];
 
      

         // PURCHASE OF HOTEL
        $HotelPayable_cr = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '510107',  // PURCHASE OF HOTEL
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->SupplierID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $request->HotelPayable,
        
        'Narration' => 'Hotel Booking - ' . $request->HotelName .
               ' in ' . $request->HotelCity .
               ' | Check-In: ' . $request->CheckInDate .
               ' | Check-Out: ' . $request->CheckOutDate .
               ' | Nights: ' . $request->Nights .
               ' | Room: ' . $request->RoomType .
               ' | Pax: ' . $request->HotelPax .
               ' | HCN: ' . $request->HCN_NO,
       
       
        'City' => $request->HotelCity,
        'CheckIn' => $request->CheckInDate,
        'CheckOut' => $request->CheckOutDate,
        'Nights' => $request->Nights,
        'HotelName' => $request->HotelName,
        'RoomType' => $request->RoomType,
        'PaxNumber' => $request->HotelPax,
        'HCN' => $request->HCN_NO,
        
        'Trace' => $id.'-HPOTCR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseHotel,
        'ForeignAmount' => $request->HotelPayable,
        'ForeignCredit' => ($request->HotelPayable * $request->ExRatePurchaseHotel),
        ];


        // REVENUE OF HOTEL
        $comission = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '410103',  // REVENUE OF HOTEL
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->PartyID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $net_commission,
        
        'Narration' => 'Hotel Booking - ' . $request->HotelName .
               ' in ' . $request->HotelCity .
               ' | Check-In: ' . $request->CheckInDate .
               ' | Check-Out: ' . $request->CheckOutDate .
               ' | Nights: ' . $request->Nights .
               ' | Room: ' . $request->RoomType .
               ' | Pax: ' . $request->HotelPax .
               ' | HCN: ' . $request->HCN_NO,
       
        'City' => $request->HotelCity,
        'CheckIn' => $request->CheckInDate,
        'CheckOut' => $request->CheckOutDate,
        'Nights' => $request->Nights,
        'HotelName' => $request->HotelName,
        'RoomType' => $request->RoomType,
        'PaxNumber' => $request->HotelPax,
        'HCN' => $request->HCN_NO,
        
        'Trace' => $id.'-HCOM',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseHotel,
        'ForeignAmount' => ($request->HotelReceivable-$request->HotelPayable),
        'ForeignCredit' => (($request->HotelReceivable-$request->HotelPayable) * $request->ExRatePurchaseHotel),
        ];
        
        
        // PURCHASE OF TICKET
        $HotelPayable_dr = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '510107',  // PURCHASE OF HOTEL
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->SupplierID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Dr' => $request->HotelPayable,
        
        'Narration' => 'Hotel Booking - ' . $request->HotelName .
               ' in ' . $request->HotelCity .
               ' | Check-In: ' . $request->CheckInDate .
               ' | Check-Out: ' . $request->CheckOutDate .
               ' | Nights: ' . $request->Nights .
               ' | Room: ' . $request->RoomType .
               ' | Pax: ' . $request->HotelPax .
               ' | HCN: ' . $request->HCN_NO,
       
        'City' => $request->HotelCity,
        'CheckIn' => $request->CheckInDate,
        'CheckOut' => $request->CheckOutDate,
        'Nights' => $request->Nights,
        'HotelName' => $request->HotelName,
        'RoomType' => $request->RoomType,
        'PaxNumber' => $request->HotelPax,
        'HCN' => $request->HCN_NO,
        
        'Trace' => $id.'-HPOTDR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseHotel,
        'ForeignAmount' => $request->HotelPayable,
        'ForeignDebit' => ($request->HotelPayable * $request->ExRatePurchaseHotel),
        ];
        
        
        
        // A/P -> PIA 
        $ticket_ap = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '210100',  // ACCOUNT PAYABLE A/P
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->SupplierID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $request->HotelPayable,
        
        'Narration' => 'Hotel Booking - ' . $request->HotelName .
               ' in ' . $request->HotelCity .
               ' | Check-In: ' . $request->CheckInDate .
               ' | Check-Out: ' . $request->CheckOutDate .
               ' | Nights: ' . $request->Nights .
               ' | Room: ' . $request->RoomType .
               ' | Pax: ' . $request->HotelPax .
               ' | HCN: ' . $request->HCN_NO,
       
        'City' => $request->HotelCity,
        'CheckIn' => $request->CheckInDate,
        'CheckOut' => $request->CheckOutDate,
        'Nights' => $request->Nights,
        'HotelName' => $request->HotelName,
        'RoomType' => $request->RoomType,
        'PaxNumber' => $request->HotelPax,
        'HCN' => $request->HCN_NO,
        
        'Trace' => $id.'-HAP',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseHotel,
        'ForeignAmount' => $request->HotelPayable,
        'ForeignCredit' => ($request->HotelPayable * $request->ExRatePurchaseHotel),
        ];
        
        
        
        // VAT CALCULATION ON COMISSION
        $ticket_vat_payable = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '210300',  // VAT PAYABLE ON COMISSION
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->SupplierID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $vat,
        
        'Narration' => 'Hotel Booking - ' . $request->HotelName .
               ' in ' . $request->HotelCity .
               ' | Check-In: ' . $request->CheckInDate .
               ' | Check-Out: ' . $request->CheckOutDate .
               ' | Nights: ' . $request->Nights .
               ' | Room: ' . $request->RoomType .
               ' | Pax: ' . $request->HotelPax .
               ' | HCN: ' . $request->HCN_NO,
       
        'City' => $request->HotelCity,
        'CheckIn' => $request->CheckInDate,
        'CheckOut' => $request->CheckOutDate,
        'Nights' => $request->Nights,
        'HotelName' => $request->HotelName,
        'RoomType' => $request->RoomType,
        'PaxNumber' => $request->HotelPax,
        'HCN' => $request->HCN_NO,
        
        'Trace' => $id.'-HVATOUTPUT',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseHotel,
        'ForeignAmount' => $request->HotelPayable,
        'ForeignCredit' => ($request->HotelPayable * $request->ExRatePurchaseHotel),
        ];




// delete hotel ticket entries
            // JournalDummy::where('Trace', $id.'-HAR')->delete();  
            JournalDummy::where('Trace', $id.'-HPOTCR')->delete();  
            JournalDummy::where('Trace', $id.'-HCOM')->delete();  
            JournalDummy::where('Trace', $id.'-HPOTDR')->delete();  
            JournalDummy::where('Trace', $id.'-HAP')->delete();  
            JournalDummy::where('Trace', $id.'-HVATOUTPUT')->delete();  


////////////////////////// VISA  ENTRY //////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

        if ($request->HotelPayable > 0 && $request->HotelReceivable > 0) {

 
         

            // ticket entries
            // JournalDummy::create($ticket_AR);
            JournalDummy::create($HotelPayable_cr);
            JournalDummy::create($comission);
            JournalDummy::create($HotelPayable_dr);
            JournalDummy::create($ticket_ap);
            JournalDummy::create($ticket_vat_payable);

            


        }
     
        // {   
        //     // ticket entries
        //     // JournalDummy::where('Trace', $id.'-HAR')->update($ticket_AR);  
        //     JournalDummy::where('Trace', $id.'-HPOTCR')->update($HotelPayable_cr);  
        //     JournalDummy::where('Trace', $id.'-HCOM')->update($comission);  
        //     JournalDummy::where('Trace', $id.'-HPOTDR')->update($HotelPayable_dr);  
        //     JournalDummy::where('Trace', $id.'-HAP')->update($ticket_ap);  
        //     JournalDummy::where('Trace', $id.'-HVATOUTPUT')->update($ticket_vat_payable); 
            
     
        
    


 

    
}


}
