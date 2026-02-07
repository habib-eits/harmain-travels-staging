<?php

namespace App\Http\Controllers;

use App\Models\JournalDummy;
use Illuminate\Http\Request;
use App\Models\InvoiceTransport;
use App\Models\Packages;
use Yajra\DataTables\DataTables;
use App\Models\UmrahInvoiceMaster;
use Illuminate\Support\Facades\DB;
use App\Models\UmrahInvoicePassenger;
use Illuminate\Support\Facades\Validator;

class UmrahInvoiceTransportController extends Controller
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
                $data = InvoiceTransport::where('InvoiceMasterID', $request->umrah_invoice_master_id)->get();
                // $data = InvoiceTransport::all();
                // $data = InvoiceTransport::when($request->umrah_invoice_master_id, function ($query, $umrah_invoice_master_id) {
                //             return $query->where('InvoiceMasterID', $umrah_invoice_master_id);
                //         })->get();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                   

                    ->addColumn('action', function ($row) {
                        $btn = '
                                <div class="d-flex">
                                    <a href="javascript:void(0)" onclick="transportEdit(' . $row->id . ')" class="dropdown-item">
                                        <i class="bx bx-pencil font-size-16 text-secondary me-1"></i>
                                    </a>
                                </div>
                            
                        ';
    
                   
                    return $btn;
                   
                    })
    
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }
 

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
                'TransportSupplier' => 'required',
                'Quantity' => 'required',
            ],[
                'TransportSupplier.required' => 'Transport Supplier Required',
                'umrah_invoice_master_id.required' => 'Please submit Invoice Master Data First'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            // $data = $request->all();// storing request data in array

 
            $data = $request->only([
                    'TransportDate',
                    'TransportCity',
                    'Sector',
                    'VehicleType',
                    'VehicleStatus',
                    'Quantity',
                    'TransportPax',
                    'TransportPurchase',
                    'TransportSale',
                    'TransportPayable',
                    'TransportReceivable',
                    'Flight',
                    'PickupTime',
                    'PickFrom',
                    'DestinationTo',
                    'TransportBrnCode',
                    'TCN',
                    'ExRatePurchaseTransport',
                    'ExRateSaleTransport',
                ]);

            // Merge session values
            $data = array_merge($data, [
                'UserID'   => session('UserID'),
                'BranchID' => session('BranchID'),
            ]);


                $data['InvoiceMasterID'] = $request->umrah_invoice_master_id;
                $data['SupplierID'] = $request->TransportSupplier;


 
   
            if ($request->invoice_transport_id != null) {
                // Fetch the existing UmrahInvoiceMaster instance and update it
                $umrahInvoiceMaster = InvoiceTransport::find($request->invoice_transport_id);
                $umrahInvoiceMaster->update($data);
                $message = "Transport Update successfully.";
                $action="Update";
             } else {
                // Create a new UmrahInvoiceMaster instance
                $umrahInvoiceMaster = InvoiceTransport::create($data);
                $message = "Transport Create successfully.";
                $action="Create";
            }


                $totals = InvoiceTransport::where('InvoiceMasterID', $request->umrah_invoice_master_id)
                    ->selectRaw('
                        SUM(TransportPayable) as TransportPayable,
                        SUM(TransportReceivable) as TransportReceivable,
                        SUM(TransportPayable * ExRatePurchaseTransport) as forex_TransportPayable,
                        SUM(TransportReceivable * ExRateSaleTransport) as forex_TransportReceivable
                    ')
                    ->first();

            $this->JournalEntriesTransport($request,$umrahInvoiceMaster->id,$action);  


            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => $message,
                 'data' => [
                'TransportPayable' => $totals->TransportPayable,
                'TransportReceivable' => round($totals->TransportReceivable,2),
                'forex_TransportPayable' => $totals->forex_TransportPayable,
                'forex_TransportReceivable' => round($totals->forex_TransportReceivable,2),
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
            $data = InvoiceTransport::findOrFail($id);
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
            $data = InvoiceTransport::findOrFail($id);
            
            // Delete the data record
            
            $data->delete();

             $totals = InvoiceTransport::where('InvoiceMasterID', $data->InvoiceMasterID)
                    ->selectRaw('
                        SUM(TransportPayable) as TransportPayable,
                        SUM(TransportReceivable) as TransportReceivable,
                        SUM(TransportPayable * ExRatePurchaseTransport) as forex_TransportPayable,
                        SUM(TransportReceivable * ExRateSaleTransport) as forex_TransportReceivable
                    ')
                    ->first();




            JournalDummy::where('Trace', 'like', $id . '-%')->delete();

            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Delete successfully.',
                'data' => [
                'TransportPayable' => $totals->TransportPayable ?? 0,
                'TransportReceivable' => round($totals->TransportReceivable ?? 0, 2),
                'forex_TransportPayable' => $totals->forex_TransportPayable ?? 0,
                'forex_TransportReceivable' => round($totals->forex_TransportReceivable ?? 0, 2),

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
                'ticket_sale' => 'required',
                'visa_purchase' => 'required',
                'ticket_purchase' => 'required',
                'forex_purchase' => 'required',
                'forex_sale' => 'required',

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
                'ticket_sale' => $request->ticket_sale,
                'visa_purchase' => $request->visa_purchase,
                'ticket_purchase' => $request->ticket_purchase,
                'forex_purchase' => $request->forex_purchase,
                'forex_sale' => $request->forex_sale,
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

    public function JournalEntriesTransport(Request $request,$id,$action)
{   
        $packageName = Packages::where('id', $request->package_id)->pluck('name')->first();
   
        $hotel__sale =  $request->TransportReceivable;
        $hotel__purchase = $request->TransportPayable;

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
        'Dr' => $request->TransportReceivable,
        'Narration' => 'Transport Service for ' . $request->Sector . ' on ' . $request->TransportDate .
               ' | Vehicle: ' . $request->VehicleType .
               ' | Quantity: ' . $request->Quantity .
               ' | Pickup: ' . $request->PickupTime .
               ' | From: ' . $request->PickFrom .
               ' to ' . $request->DestinationTo .
               ' | BRN: ' . $request->TransportBrnCode .
               ' | TCN: ' . $request->TCN,
        
   

        
        'TransportDate'=> $request->TransportDate,
        'Sector'=> $request->Sector,
        'Vehicle'=> $request->VehicleType,
        'Quantity'=> $request->Quantity,
        'Pickup'=> $request->PickupTime,
        'PickFrom'=> $request->PickFrom,
        'Destination'=> $request->DestinationTo,
        'BRN'=> $request->TransportBrnCode,
        'TCN'=> $request->TCN,

        
        
        'Trace' => $id.'-TAR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->SaleCurrency,
        'Rate' => $request->ExRateSaleTransport,
        'ForeignAmount' => $request->TransportReceivable,
        'ForeignDebit' => ($request->TransportReceivable * $request->ExRateSaleTransport),
        ];
 
      

         // PURCHASE OF TRANSPORT
        $TransportPayable_cr = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '510109',  // PURCHASE OF TRANSPORT
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->TransportSupplier,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $request->TransportPayable,
        
        'Narration' => 'Transport Service for ' . $request->Sector . ' on ' . $request->TransportDate .
               ' | Vehicle: ' . $request->VehicleType .
               ' | Quantity: ' . $request->Quantity .
               ' | Pickup: ' . $request->PickupTime .
               ' | From: ' . $request->PickFrom .
               ' to ' . $request->DestinationTo .
               ' | BRN: ' . $request->TransportBrnCode .
               ' | TCN: ' . $request->TCN,
       
       
        'TransportDate'=> $request->TransportDate,
        'Sector'=> $request->Sector,
        'Vehicle'=> $request->VehicleType,
        'Quantity'=> $request->Quantity,
        'Pickup'=> $request->PickupTime,
        'PickFrom'=> $request->PickFrom,
        'Destination'=> $request->DestinationTo,
        'BRN'=> $request->TransportBrnCode,
        'TCN'=> $request->TCN,
        
        'Trace' => $id.'-TPOTCR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseTransport,
        'ForeignAmount' => $request->TransportPayable,
        'ForeignCredit' => ($request->TransportPayable * $request->ExRatePurchaseTransport),
        ];


        // COMISSION
        $comission = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '410104',  // REVENUE OF TRANSORT
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->TransportSupplier,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $net_commission,
        
        'Narration' => 'Transport Service for ' . $request->Sector . ' on ' . $request->TransportDate .
               ' | Vehicle: ' . $request->VehicleType .
               ' | Quantity: ' . $request->Quantity .
               ' | Pickup: ' . $request->PickupTime .
               ' | From: ' . $request->PickFrom .
               ' to ' . $request->DestinationTo .
               ' | BRN: ' . $request->TransportBrnCode .
               ' | TCN: ' . $request->TCN,
       
        'TransportDate'=> $request->TransportDate,
        'Sector'=> $request->Sector,
        'Vehicle'=> $request->VehicleType,
        'Quantity'=> $request->Quantity,
        'Pickup'=> $request->PickupTime,
        'PickFrom'=> $request->PickFrom,
        'Destination'=> $request->DestinationTo,
        'BRN'=> $request->TransportBrnCode,
        'TCN'=> $request->TCN,
        
        'Trace' => $id.'-TCOM',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseTransport,
        'ForeignAmount' => ($request->TransportReceivable-$request->TransportPayable),
        'ForeignCredit' => (($request->TransportReceivable-$request->TransportPayable) * $request->ExRatePurchaseTransport),
        ];
        
        
        // PURCHASE OF TRANSPORT
        $TransportPayable_dr = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '510109',  // PURCHASE OF TRANSPORT
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->TransportSupplier,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Dr' => $request->TransportPayable,
        
        'Narration' => 'Transport Service for ' . $request->Sector . ' on ' . $request->TransportDate .
               ' | Vehicle: ' . $request->VehicleType .
               ' | Quantity: ' . $request->Quantity .
               ' | Pickup: ' . $request->PickupTime .
               ' | From: ' . $request->PickFrom .
               ' to ' . $request->DestinationTo .
               ' | BRN: ' . $request->TransportBrnCode .
               ' | TCN: ' . $request->TCN,
       
        'TransportDate'=> $request->TransportDate,
        'Sector'=> $request->Sector,
        'Vehicle'=> $request->VehicleType,
        'Quantity'=> $request->Quantity,
        'Pickup'=> $request->PickupTime,
        'PickFrom'=> $request->PickFrom,
        'Destination'=> $request->DestinationTo,
        'BRN'=> $request->TransportBrnCode,
        'TCN'=> $request->TCN,
        
        'Trace' => $id.'-TPOTDR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseTransport,
        'ForeignAmount' => $request->TransportPayable,
        'ForeignDebit' => ($request->TransportPayable * $request->ExRatePurchaseTransport),
        ];
        
        
        
        // A/P -> PIA 
        $ticket_ap = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '210100',  // ACCOUNT PAYABLE A/P
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->TransportSupplier,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $request->TransportPayable,
        
        'Narration' => 'Transport Service for ' . $request->Sector . ' on ' . $request->TransportDate .
               ' | Vehicle: ' . $request->VehicleType .
               ' | Quantity: ' . $request->Quantity .
               ' | Pickup: ' . $request->PickupTime .
               ' | From: ' . $request->PickFrom .
               ' to ' . $request->DestinationTo .
               ' | BRN: ' . $request->TransportBrnCode .
               ' | TCN: ' . $request->TCN,
       
        'TransportDate'=> $request->TransportDate,
        'Sector'=> $request->Sector,
        'Vehicle'=> $request->VehicleType,
        'Quantity'=> $request->Quantity,
        'Pickup'=> $request->PickupTime,
        'PickFrom'=> $request->PickFrom,
        'Destination'=> $request->DestinationTo,
        'BRN'=> $request->TransportBrnCode,
        'TCN'=> $request->TCN,
        
        'Trace' => $id.'-TAP',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseTransport,
        'ForeignAmount' => $request->TransportPayable,
        'ForeignCredit' => ($request->TransportPayable * $request->ExRatePurchaseTransport),
        ];
        
        
        
        // VAT CALCULATION ON COMISSION
        $ticket_vat_payable = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '210300',  // VAT PAYABLE ON COMISSION
        'BranchID' => session()->get('BranchID'),
        'Date' => $request->Date,
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->TransportSupplier,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $vat,
        
        'Narration' => 'Transport Service for ' . $request->Sector . ' on ' . $request->TransportDate .
               ' | Vehicle: ' . $request->VehicleType .
               ' | Quantity: ' . $request->Quantity .
               ' | Pickup: ' . $request->PickupTime .
               ' | From: ' . $request->PickFrom .
               ' to ' . $request->DestinationTo .
               ' | BRN: ' . $request->TransportBrnCode .
               ' | TCN: ' . $request->TCN,
       
        'TransportDate'=> $request->TransportDate,
        'Sector'=> $request->Sector,
        'Vehicle'=> $request->VehicleType,
        'Quantity'=> $request->Quantity,
        'Pickup'=> $request->PickupTime,
        'PickFrom'=> $request->PickFrom,
        'Destination'=> $request->DestinationTo,
        'BRN'=> $request->TransportBrnCode,
        'TCN'=> $request->TCN,
        
        'Trace' => $id.'-TVATOUTPUT',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->ExRatePurchaseTransport,
        'ForeignAmount' => $request->TransportPayable,
        'ForeignCredit' => ($request->TransportPayable * $request->ExRatePurchaseTransport),
        ];




// delete transport ticket entries
            JournalDummy::where('Trace', $id.'-TAR')->delete();  
            JournalDummy::where('Trace', $id.'-TPOTCR')->delete();  
            JournalDummy::where('Trace', $id.'-TCOM')->delete();  
            JournalDummy::where('Trace', $id.'-TPOTDR')->delete();  
            JournalDummy::where('Trace', $id.'-TAP')->delete();  
            JournalDummy::where('Trace', $id.'-TVATOUTPUT')->delete();  


////////////////////////// VISA  ENTRY //////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

        if ($request->TransportPayable > 0 && $request->TransportReceivable > 0) {

         

            // ticket entries
            // JournalDummy::create($ticket_AR);
            JournalDummy::create($TransportPayable_cr);
            JournalDummy::create($comission);
            JournalDummy::create($TransportPayable_dr);
            JournalDummy::create($ticket_ap);
            JournalDummy::create($ticket_vat_payable);

            

   
            // ticket entries
            // JournalDummy::where('Trace', $id.'-TAR')->update($ticket_AR);  
            JournalDummy::where('Trace', $id.'-TPOTCR')->update($TransportPayable_cr);  
            JournalDummy::where('Trace', $id.'-TCOM')->update($comission);  
            JournalDummy::where('Trace', $id.'-TPOTDR')->update($TransportPayable_dr);  
            JournalDummy::where('Trace', $id.'-TAP')->update($ticket_ap);  
            JournalDummy::where('Trace', $id.'-TVATOUTPUT')->update($ticket_vat_payable); 
            
           
        }


   
        

    
}







}
