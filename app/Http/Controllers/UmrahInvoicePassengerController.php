<?php

namespace App\Http\Controllers;

use App\Models\Packages;

use App\Models\JournalDummy;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\UmrahInvoiceMaster;
use Illuminate\Support\Facades\DB;
use App\Models\UmrahInvoicePassenger;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UmrahInvoicePassengerController extends Controller
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
                $data = UmrahInvoicePassenger::where('umrah_invoice_master_id', $request->umrah_invoice_master_id)->get();
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                   
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="d-flex">
                                    <a href="javascript:void(0)" onclick="passengerEdit(' . $row->id . ')" class="dropdown-item">
                                        <i class="bx bx-pencil font-size-16 text-secondary me-1"></i>
                                    </a>
                                </div>';
                    return $btn;
                   
                    })
    
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }


            $data = UmrahInvoicePassenger::where('umrah_invoice_master_id', $request->umrah_invoice_master_id)->get();
    
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
                'passenger_name' => 'required',
                'passport_no' => 'required',
                

            ],[
                'umrah_invoice_master_id.required' => 'Please submit Invoice Master Data First'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }



           // If you're adding or updating a passenger to "Head"
        if (strtolower($request->relation_type) === 'head') {
            $countHead = UmrahInvoicePassenger::where('umrah_invoice_master_id', $request->umrah_invoice_master_id)
                ->where('relation_type', 'head')
                ->when($request->umrah_invoice_passenger_id, function ($query) use ($request) {
                    // Exclude current passenger during update
                    $query->where('id', '!=', $request->umrah_invoice_passenger_id);
                })
                ->count();

            if ($countHead > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only one Head passenger is allowed per invoice. Please change this to Relation.'
                ]);
            }
        }



            $data = $request->all();// storing request data in array

             // Merge session values
            $data = array_merge($data, [
                'UserID'   => session('UserID'),
                'BranchID' => session('BranchID'),
            ]);



            if ($request->umrah_invoice_passenger_id != null) {
                // Fetch the existing UmrahInvoiceMaster instance and update it
                $umrahInvoiceMaster = UmrahInvoicePassenger::find($request->umrah_invoice_passenger_id);
                $umrahInvoiceMaster->update($data);
                $message = "Passenger Update successfully.";
                $action='Update';
            } else {
                // Create a new UmrahInvoiceMaster instance
                $umrahInvoiceMaster = UmrahInvoicePassenger::create($data);
                $message = "Passenger Create successfully.";
                $action='Create';
            }


            // find amount total from invoice section
            $totals = UmrahInvoicePassenger::where('umrah_invoice_master_id', $request->umrah_invoice_master_id)
                            ->selectRaw('
                                SUM(visa_sale) as visa_sale,
                                SUM(ticket_sale) as ticket_sale,
                                SUM(visa_purchase) as visa_purchase,
                                SUM(ticket_purchase) as ticket_purchase,
                                SUM(visa_sale * forex_sale) as forex_visa_sale,
                                SUM(ticket_sale * forex_sale) as forex_ticket_sale,
                                SUM(visa_purchase * forex_purchase) as forex_visa_purchase,
                                SUM(ticket_purchase * forex_purchase) as forex_ticket_purchase
                            ')
                            ->first();

            
            

             $this->JournalEntries($request,$umrahInvoiceMaster->id,$action);                            

            

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => $message.'-'.$umrahInvoiceMaster->id.'-'.$action,

                'data' => [
                'visa_purchase' => $totals->visa_purchase,
                'visa_sale' => $totals->visa_sale,

                'ticket_purchase' => $totals->ticket_purchase,
                'ticket_sale' => $totals->ticket_sale,

                'forex_visa_purchase' => $totals->forex_visa_purchase,
                'forex_visa_sale' => $totals->forex_visa_sale,
               
                'forex_ticket_purchase' => $totals->forex_ticket_purchase,
                'forex_ticket_sale' => $totals->forex_ticket_sale,
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
            $data = UmrahInvoicePassenger::findOrFail($id);
            
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
            $data = UmrahInvoicePassenger::findOrFail($id);
            
            // Delete the data record
            $data->delete();


            $totals = UmrahInvoicePassenger::where('umrah_invoice_master_id', $data->umrah_invoice_master_id)
                        ->selectRaw('
                            SUM(visa_sale) as visa_sale,
                            SUM(ticket_sale) as ticket_sale,
                            SUM(visa_purchase) as visa_purchase,
                            SUM(ticket_purchase) as ticket_purchase,
                            SUM(visa_sale * forex_sale) as forex_visa_sale,
                            SUM(ticket_sale * forex_sale) as forex_ticket_sale,
                            SUM(visa_purchase * forex_purchase) as forex_visa_purchase,
                            SUM(ticket_purchase * forex_purchase) as forex_ticket_purchase
                        ')
                        ->first();

             JournalDummy::where('Trace', 'like', $id . '-%')->delete();


            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Passenger Delete successfully.',
                
                'data' => [
                        'visa_purchase' => $totals->visa_purchase ?? 0,
                        'visa_sale' => $totals->visa_sale ?? 0,

                        'ticket_purchase' => $totals->ticket_purchase ?? 0,
                        'ticket_sale' => $totals->ticket_sale ?? 0,

                        'forex_visa_purchase' => $totals->forex_visa_purchase ?? 0,
                        'forex_visa_sale' => $totals->forex_visa_sale ?? 0,
                    
                        'forex_ticket_purchase' => $totals->forex_ticket_purchase ?? 0,
                        'forex_ticket_sale' => $totals->forex_ticket_sale ?? 0,
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

            $umrahInvoiceMaster = UmrahInvoiceMaster::find($request->umrah_invoice_master_id);
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

            UmrahInvoicePassenger::where('umrah_invoice_master_id',$request->umrah_invoice_master_id)
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


        public function ajax_index(Request $request)
    {




 
       
         
        
//  dd($data->invoiceMaster->Date);
                       
        try{
            if ($request->ajax()) {
                                 $data = UmrahInvoicePassenger::with([
    'invoiceMaster' => function ($query) {
        $query->select('InvoiceMasterID', 'Date', 'PartyID', 'package_id')
              ->with([
                  'party:PartyID,PartyName',
                  'package:id,name' // change id,name to actual columns in packages table
              ]);
    }
])->get()->sortByDesc('invoiceMaster.Date');
            
                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column
                   
                ->addColumn('Date',function($row){
                        return ('INV-'.$row->invoiceMaster->InvoiceMasterID ?? 'N/A') . '<br>' . (dateformatman2($row->invoiceMaster->Date) ?? '');

                })
                 
                
                ->addColumn('package_name',function($row){
                    return $row->invoiceMaster->package->name ?? '';
                })
                
                
                ->addColumn('PartyName',function($row){
                    return $row->invoiceMaster->Party->PartyName ?? '';
                })
                    ->addColumn('action', function ($row) {
                        $btn = '
                                  <div class="action-cell">
            <div class="passenger-name fw-bold mb-1">' . e($row->invoiceMaster->Party->PartyName) . '</div>
            <div class="action-links">
                <a href="#" id="viewlink"  class="viewlink" data-invoicemasterid="'.$row->umrah_invoice_master_id.'"  data-partyname="'.$row->invoiceMaster->Party->PartyName.'"  >Payment</a> |
                <a href="'.route('umrah-invoice-master.show', $row->umrah_invoice_master_id).'">PDF</a> |
                <a href="'.route('umrah-invoice-master.edit', $row->umrah_invoice_master_id).'">Edit</a> |
                 <a href="'.route('umrah_voucher_print', $row->umrah_invoice_master_id).'">Invoice</a> |
                  <a href="' . route('umrah.invoice.delete2', $row->umrah_invoice_master_id) . '" 
       onclick="return confirm(\'Are you sure you want to delete this invoice?\');">
       Delete
    </a>
            </div>
        </div>
                            
                        ';
    
                   
                    return $btn;
                   
                    })
    
                    ->rawColumns(['action','Date']) // Mark these columns as raw HTML
                    ->make(true);
            }


            $data = UmrahInvoicePassenger::with(['invoiceMaster.Party'])->get();
    
            // return view('umrah.invoice_masters.index');

        }catch (\Exception $e){

            return back()->with('flash-danger', $e->getMessage());
        }
        
        
    }


public function getTaxInclusiveAmount($amount,$tax_per)
{
    
     $taxPercent =  $tax_per;
    return round($amount * ($taxPercent / (100 + $taxPercent)), 2);
}

public function JournalEntries(Request $request,$id,$action)
{   

        $packageName = Packages::where('id', $request->package_id)->pluck('name')->first();



        $ticket__sale =  $request->ticket_sale;
        $ticket__purchase = $request->ticket_purchase;

        // Gross commission (including VAT)
        $commission = $ticket__sale - $ticket__purchase;
 
           $vat = $this->getTaxInclusiveAmount($commission, env('VAT_PERCENTAGE'));
            $net_commission = $commission - $vat;

         

 

        // A/R
        $ticket_AR = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '110400',  // A/R
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->PartyID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Dr' => $request->ticket_sale,
        
        'Narration' => 'Ticket - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | PNR: ' . $request->pnr .
               ' | Type: ' . $request->type .
               ' | Amount: ' . number_format($request->ticket_sale, 2) .
               ' ' . $request->SaleCurrency,
   

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-AR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->SaleCurrency,
        'Rate' => $request->forex_sale,
        'ForeignAmount' => $request->ticket_sale,
        'ForeignDebit' => ($request->ticket_sale * $request->forex_sale),
        ];


         // PURCHASE OF TICKET
        $ticket_purchase_cr = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '510111',  // PURCHASE OF TICKET
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        // 'PartyID' => $request->TicketSupplierID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $request->ticket_purchase,
        
        'Narration' => 'Ticket - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | PNR: ' . $request->pnr .
               ' | Type: ' . $request->type .
               ' | Amount: ' . number_format($request->ticket_sale, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-POTCR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => $request->ticket_purchase,
        'ForeignCredit' => ($request->ticket_purchase * $request->forex_purchase),
        ];


        // COMISSION
        $comission = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '410150',  // REVENUE OF TICKET
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        // 'PartyID' => $request->PartyID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $net_commission,
        
        'Narration' => 'Ticket - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | PNR: ' . $request->pnr .
               ' | Type: ' . $request->type .
               ' | Amount: ' . number_format($request->ticket_sale, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-COM',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => ($request->ticket_sale-$request->ticket_purchase),
        'ForeignCredit' => (($request->ticket_sale-$request->ticket_purchase) * $request->forex_purchase),
        ];
        
        
        // PURCHASE OF TICKET
        $ticket_purchase_dr = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '510111',  // PURCHASE OF TICKET
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        // 'PartyID' => $request->TicketSupplierID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Dr' => $request->ticket_purchase,
        
        'Narration' => 'Ticket - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | PNR: ' . $request->pnr .
               ' | Type: ' . $request->type .
               ' | Amount: ' . number_format($request->ticket_sale, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-POTDR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => $request->ticket_purchase,
        'ForeignDebit' => ($request->ticket_purchase * $request->forex_purchase),
        ];
        
        
        
        // A/P -> PIA 
        $ticket_ap = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '210100',  // ACCOUNT PAYABLE A/P
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        // 'PartyID' => $request->TicketSupplierID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $request->ticket_purchase,
        
        'Narration' => 'Ticket - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | PNR: ' . $request->pnr .
               ' | Type: ' . $request->type .
               ' | Amount: ' . number_format($request->ticket_purchase, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-AP',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => $request->ticket_purchase,
        'ForeignCredit' => ($request->ticket_purchase * $request->forex_purchase),
        ];
        
        
        
        // VAT CALCULATION ON COMISSION
        $ticket_vat_payable = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '210300',  // VAT PAYABLE ON COMISSION
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        // 'PartyID' => $request->TicketSupplierID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $vat,
        
        'Narration' => 'Ticket - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | PNR: ' . $request->pnr .
               ' | Type: ' . $request->type .
               ' | Amount: ' . number_format($request->ticket_sale, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-VATOUTPUT',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => $request->ticket_purchase,
        'ForeignCredit' => ($request->ticket_purchase * $request->forex_purchase),
        ];



// delete ticket entries
            // JournalDummy::where('Trace', $id.'-AR')->delete();  
            JournalDummy::where('Trace', $id.'-POTCR')->delete();  
            JournalDummy::where('Trace', $id.'-COM')->delete();  
            JournalDummy::where('Trace', $id.'-POTDR')->delete();  
            JournalDummy::where('Trace', $id.'-AP')->delete();  
            JournalDummy::where('Trace', $id.'-VATOUTPUT')->delete(); 



////////////////////////// VISA  ENTRY //////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

        if ($request->ticket_purchase > 0 && $request->ticket_sale > 0) {
      
  

            // ticket entries
            // JournalDummy::create($ticket_AR);
            JournalDummy::create($ticket_purchase_cr);
            JournalDummy::create($comission);
            JournalDummy::create($ticket_purchase_dr);
            JournalDummy::create($ticket_ap);
            JournalDummy::create($ticket_vat_payable);

            


    
       
        }
 
       


         $visa__sale =  $request->visa_sale;
        $visas__purchase = $request->visa_purchase;

        // Gross commission (including VAT)
        $visa_commission = $visa__sale - $visas__purchase;
 
           $visa_vat = $this->getTaxInclusiveAmount($visa_commission, env('VAT_PERCENTAGE'));
            $visa_net_commission = $visa_commission - $visa_vat;

         

        
        

        // A/R
        $visa_AR = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '110400',  // A/R
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->PartyID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Dr' => $request->visa_sale,
        
        'Narration' => 'Pax - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | Type: ' . $request->type .
               ' | PNR: ' . $request->pnr .
               ' | Amount: ' . number_format($request->visa_sale, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-visaAR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->SaleCurrency,
        'Rate' => $request->forex_sale,
        'ForeignAmount' => $request->visa_sale,
        'ForeignDebit' => ($request->visa_sale * $request->forex_sale),
        ];


         // PURCHASE OF TICKET
        $visas_purchase_cr = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        // 'PartyID' => $request->VisaSupplierID,  // that will be updated when complet voucher is save at end
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $request->visa_purchase,
        
        'Narration' => 'Pax - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | Type: ' . $request->type .
               ' | PNR: ' . $request->pnr .
               ' | Amount: ' . number_format($request->visa_purchase, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-visaPOTCR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => $request->visa_purchase,
        'ForeignCredit' => ($request->visa_purchase * $request->forex_purchase),
        ];


        // COMISSION
        $visa_entry_comission = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '410102',  // REVENUE OF VISA
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->PartyID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $visa_net_commission,
        
        'Narration' => 'Pax - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | Type: ' . $request->type .
               ' | PNR: ' . $request->pnr .
               ' | Amount: ' . number_format($request->visa_sale, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-visaCOM',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => ($request->ticket_sale-$request->ticket_purchase),
        'ForeignCredit' => (($request->ticket_sale-$request->ticket_purchase) * $request->forex_purchase),
        ];
        
        
        // PURCHASE OF TICKET
        $visas_purchase_dr = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        // 'PartyID' => $request->VisaSupplierID,  // that will be updated when complet voucher is save at end

        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Dr' => $request->visa_purchase,
        
        'Narration' => 'Pax - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | Type: ' . $request->type .
               ' | PNR: ' . $request->pnr .
               ' | Amount: ' . number_format($request->visa_purchase, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-visaPOTDR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => $request->visa_purchase,
        'ForeignDebit' => ($request->visa_purchase * $request->forex_purchase),
        ];
        
        
        
        // A/P -> PIA 
        $visa_ap = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '210100',  // ACCOUNT PAYABLE A/P
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        // 'PartyID' => $request->VisaSupplierID,  // that will be updated when complet voucher is save at end

        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $request->visa_purchase,
        
        'Narration' => 'Pax - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | Type: ' . $request->type .
               ' | PNR: ' . $request->pnr .
               ' | Amount: ' . number_format($request->visa_purchase, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-visaAP',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => $request->visa_purchase,
        'ForeignCredit' => ($request->visa_purchase * $request->forex_purchase),
        ];
        
        
        
        // VAT CALCULATION ON COMISSION
        $visa_vat_payable = [    
        'VHNO' => 'INV-' . $request->umrah_invoice_master_id,
        'JournalType' => $packageName,
        'ChartOfAccountID' => '210300',  // VAT PAYABLE ON COMISSION
        'BranchID' => session()->get('BranchID'),
        // 'SupplierID' => $request->SupplierID[$i],
        // 'PartyID' => $request->TicketSupplierID,
        'InvoiceMasterID' => $request->umrah_invoice_master_id,
        'Date' => $request->input('Date'),
        'Cr' => $visa_vat,
        
        'Narration' => 'Pax - ' . $request->passenger_name .
               ' | Passport: ' . $request->passport_no .
               ' | Visa#: ' . $request->visa_no .
               ' | Type: ' . $request->type .
               ' | PNR: ' . $request->pnr .
               ' | Amount: ' . number_format($request->visa_sale, 2) .
               ' ' . $request->SaleCurrency,
    

        'PaxName' => $request->passenger_name,
        'PassportNo' => $request->passport_no,
        'Type' => $request->type,
        'VisaNo' => $request->visa_no,
        'PNR' => $request->pnr,
        
        'Trace' => $id.'-visaVATOUTPUT',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $request->PurchaseCurrency,
        'Rate' => $request->forex_purchase,
        'ForeignAmount' => $request->ticket_purchase,
        'ForeignCredit' => ($request->ticket_purchase * $request->forex_purchase),
        ];



        
        
        
        
        
             JournalDummy::where('Trace', $id.'-visaPOTCR')->delete();  
             JournalDummy::where('Trace', $id.'-visaCOM')->delete();  
             JournalDummy::where('Trace', $id.'-visaPOTDR')->delete();  
             JournalDummy::where('Trace', $id.'-visaAP')->delete();  
             JournalDummy::where('Trace', $id.'-visaVATOUTPUT')->delete();   
             
             if ($request->visa_purchase > 0 && $request->visa_sale > 0) {
             
            // visa entries
            // JournalDummy::create($visa_AR);
            JournalDummy::create($visas_purchase_cr);
            JournalDummy::create($visa_entry_comission);
            JournalDummy::create($visas_purchase_dr);
            JournalDummy::create($visa_ap);
            JournalDummy::create($visa_vat_payable);

   


         }
    
    
}

public function voucher_power_search()

{
    return view('umrah.voucher_power_search');    
}


public function getVisaDetail(Request $request)
{
    $visa = \App\Models\VisaDetail::Where('Passport', $request->passport_no)
                ->first();

    if ($visa) {
        return response()->json([
            'visa_no' => $visa->VisaNo,
            'passport_no' => $visa->Passport,
            'passenger_name' => $visa->PaxName,
            'dob' => $visa->DOB,
            'nationality' => $visa->Nationality,
            'contact' => $visa->Contact,
            'visa_purchase' => $visa->Fare,
            'visa_sale' => $visa->Total,
            'visa_sale' => $visa->Total,
            'Gender' => $visa->Gender,
            'VisaType' => $visa->VisaType,
            'type' => $visa->PaxType,
            'relation_type' => $visa->RelationType,
            'shirka_id' => $visa->ShirkaID,
            'visa_date' => $visa->IssueDate,
        ]);
    } else {
        return response()->json(['message' => 'No record found'], 404);
    }
}



}
