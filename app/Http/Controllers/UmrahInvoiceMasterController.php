<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Party;
use App\Models\Branch;
use App\Models\Sector;
use App\Models\Shirka;
use App\Models\Company;
use App\Models\Journal;
use App\Models\Currency;
use App\Models\Location;
use App\Models\MealPlan;
use App\Models\Packages;
use App\Models\RoomType;
use App\Models\RoomView;
use App\Models\InvoiceHotel;
use App\Models\JournalDummy;
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
use App\Models\InvoiceStatus;
use App\Models\TransportType;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Models\InvoiceTransport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\UmrahInvoicePassenger;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UmrahInvoiceMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
      */
    public function index(Request $request)
    {
        
        try{
            if ($request->ajax()) {
                $data = InvoiceMaster::all();
    
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
                                            <a href="'.route('umrah-invoice-master.edit', $row->InvoiceMasterID).'" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                         <li>
                                            <a href="javascript:void(0)" onclick="deleteBrand(' . $row->InvoiceMasterID . ')" class="dropdown-item">
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
    
            $party= Party::getPartyList(); 
            
            return view('umrah.invoice_masters.index',compact('party'));

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


      
       
        $party= Party::getPartyList('C');
        $supplier= Party::getSupplierList('VC');
        $packages = Packages::all();
        $location = Location::all();
        $sector = Sector::all();
        $transport_type = TransportType::all();
        $hotel = Hotel::all();
        $room_type = RoomType::all();
        $shirka = Shirka::all();
        $currency = Currency::all();
        $invoice_status = InvoiceStatus::all();
        $meal_plan=MealPlan::all();
        $room_view=RoomView::all();

     

        $branches = \App\Models\Branch::when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('id', session('BranchID'));
        })->get();

 
        $party_type = DB::table('party_type')->get();


        $invoice_master= new InvoiceMaster();

        return view('umrah.invoice_masters.create',compact('party','packages','location','sector','transport_type','hotel','room_type','shirka','currency','invoice_master','supplier','invoice_status','branches','party_type','meal_plan','room_view'));

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
                'Date' => 'required',
                'PartyID' => 'required',
                'package_id' => 'required',
            ],
            [
                'Date.required' => 'The Date is required.',
                'PartyID.required' => 'The Customer is required.',
            ]);



            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }


           
            // $data = $request->all();// storing request data in array


            $data = $request->only([
            'Date',
            'PartyID',
            'RefNo',
            'sub_agent',
            'package_id',
            'FlightPNR',
            'FlightNoDeparture',
            'SectorDeparture',
            'FlightDateDeparture',
            'FlightTimeDeparture',
            'FlightDateArrivalDeparture',
            'FlightTimeArrivalDeparture',
            'FlightNoReturn',
            'SectorReturn',
            'FlightDateReturn',
            'FlightDepartureTimeReturn',
            'FlightArrivalDateReturn',
            'FlightArrivalTimeReturn',
           
            'FlightNights',
        
            'ExSaleRate',
            'SaleCurrency',
            'TicketCurrency',
            'ExPurchaseRate',
            'PurchaseCurrency',

            // 'Validity',
            // 'SPO',
            // 'VisaSupplierID',
            // 'TicketSupplierID',
            // 'Status',
            // 'Remarks',
            // 'RevisedDate',
            // 'RevisedRemarks',    


             ]);

           // Merge session values
        $data = array_merge($data, [
            'UserID'   => session('UserID'),
            'BranchID' => session('BranchID'),
        ]);



            $message = null;
            
            if ($request->umrah_invoice_master_id != null) {
              
                // Fetch the existing UmrahInvoiceMaster instance and update it
                $umrahInvoiceMaster = InvoiceMaster::find($request->umrah_invoice_master_id);
                $umrahInvoiceMaster->update($data);
                $message = "Umrah Invoice Update successfully.";
            } else {
                // Create a new UmrahInvoiceMaster instance
                $umrahInvoiceMaster = InvoiceMaster::create($data);
                $message = "Umrah Invoice Create successfully.";
            }


          

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => $message,
                'umrah_invoice_master_id' => $umrahInvoiceMaster->InvoiceMasterID,
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
    
    public function getPassengerSummary($invoiceMasterId)
{
    $summary = [
        'Adult' => UmrahInvoicePassenger::where('umrah_invoice_master_id', $invoiceMasterId)->where('type', 'Adult')->count(),
        'Child' => UmrahInvoicePassenger::where('umrah_invoice_master_id', $invoiceMasterId)->where('type', 'Child')->count(),
        'Infant' => UmrahInvoicePassenger::where('umrah_invoice_master_id', $invoiceMasterId)->where('type', 'Infant')->count(),
        'Total' => UmrahInvoicePassenger::where('umrah_invoice_master_id', $invoiceMasterId)->count(),
    ];

    return $summary;
}


public function getPassengerForLedger($invoiceMasterId)
{
    
        $Adult = UmrahInvoicePassenger::where('umrah_invoice_master_id', $invoiceMasterId)->where('type', 'Adult')->count();
        $Child = UmrahInvoicePassenger::where('umrah_invoice_master_id', $invoiceMasterId)->where('type', 'Child')->count();
        $Infant = UmrahInvoicePassenger::where('umrah_invoice_master_id', $invoiceMasterId)->where('type', 'Infant')->count();
        $Total = UmrahInvoicePassenger::where('umrah_invoice_master_id', $invoiceMasterId)->count();

        

    return $Adult.'A+'.$Child.'C+'.$Infant.'I';

    //1A+0C+0I
}
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::first();
        $invoice_master = InvoiceMaster::find($id);
        $invoice_passanger = UmrahInvoicePassenger::with(['shirka'])->where('umrah_invoice_master_id',$id)->get();
        $invoice_hotel = InvoiceHotel::with(['hotel_name'])->where('InvoiceMasterID',$id)->get();
        $invoice_transport = InvoiceTransport::where('InvoiceMasterID',$id)->get();
        $summary = $this->getPassengerSummary($id);

 
         return view('umrah.umrah_voucher',compact('company','invoice_master','invoice_passanger','invoice_hotel','invoice_transport','summary'));
    }
    
    
    public function showExtra($id,$contentType)
    {
        $company = Company::first();
        $invoice_master = InvoiceMaster::find($id);
        $invoice_passanger = UmrahInvoicePassenger::with(['shirka'])->where('umrah_invoice_master_id',$id)->get();
        $invoice_hotel = InvoiceHotel::with(['hotel_name'])->where('InvoiceMasterID',$id)->get();
        $invoice_transport = InvoiceTransport::where('InvoiceMasterID',$id)->get();
 
        $summary = $this->getPassengerSummary($id);

 
         return view('umrah.umrah_voucher',compact('company','invoice_master','invoice_passanger','invoice_hotel','invoice_transport','summary'));
    }
    
    
    public function umrah_voucher_qrcode($id)
    {
        $company = Company::first();
        $invoice_master = InvoiceMaster::find($id);
     
         $invoice_passanger = UmrahInvoicePassenger::with(['shirka'])->where('umrah_invoice_master_id',$id)->get();
        $invoice_hotel = InvoiceHotel::with(['hotel_name'])->where('InvoiceMasterID',$id)->get();
        $invoice_transport = InvoiceTransport::where('InvoiceMasterID',$id)->get();
        $summary = $this->getPassengerSummary($id);

 
         return view('umrah.umrah_voucher',compact('company','invoice_master','invoice_passanger','invoice_hotel','invoice_transport','summary'));
    }
    
    
    public function umrah_voucher_invoice($id)
    {
        $company = Company::first();
        $invoice_master = InvoiceMaster::find($id);
        $invoice_passanger = UmrahInvoicePassenger::with(['shirka'])->where('umrah_invoice_master_id',$id)->get();
        $invoice_hotel = InvoiceHotel::with(['hotel_name'])->where('InvoiceMasterID',$id)->get();
        $invoice_transport = InvoiceTransport::where('InvoiceMasterID',$id)->get();
        $journal = Journal::where('InvoiceMasterID',$id)->where('ChartOfAccountID',110400)->Sum('Cr');
       
 
$balance = Journal::where('InvoiceMasterID', $id)
    ->where('ChartOfAccountID', 110400)
    ->select(DB::raw('IFNULL(SUM(Dr), 0) - IFNULL(SUM(Cr), 0) AS balance'))
    ->value('balance');

 
        $summary = $this->getPassengerSummary($id);
         
 
 
         return view('umrah.umrah_voucher_invoice',compact('company','invoice_master','invoice_passanger','invoice_hotel','invoice_transport','summary','journal','balance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // try {
        //     $data = UmrahInvoiceMaster::findOrFail($id);
        //     return view('umrah.invoice_masters.edit', compact('data'));

        // } catch (\Exception $e) {
        //     // Return a JSON response with an error message
        //     return response()->json([
        //         'message' => $e->getMessage(),
        //         'success' => false,
        //     ], 500);
        // }

         $invoice_master = InvoiceMaster::with(['hotel','transport','passanger'])->where('InvoiceMasterID',$id)->first();

          
          


        $party= Party::getPartyList();
        $supplier= Party::getSupplierList();
        $packages = Packages::all();
        $location = Location::all();
        $sector = Sector::all();
        $transport_type = TransportType::all();
        $hotel = Hotel::all();
        $room_type = RoomType::all();
        $shirka = Shirka::all();
        $currency = Currency::all();
        $invoice_status = InvoiceStatus::all();
        $meal_plan=MealPlan::all();
        $room_view=RoomView::all();
        

 

         $branches = Branch::when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('id', session('BranchID'));
    })->get();

        $party_type = DB::table('party_type')->get();



        return view('umrah.invoice_masters.create',compact('party','packages','location','sector','transport_type','hotel','room_type','invoice_master','shirka','currency','supplier','invoice_status','branches','party_type','meal_plan','room_view'));





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
            $invoice_master = InvoiceMaster::findOrFail($id);
            $VoucherNo = $invoice_master->VoucherNo;

            VoucherMaster::where('Voucher', $VoucherNo)->delete();
            VoucherDetail::where('Voucher', $VoucherNo)->delete();
            Journal::where('VHNO', $VoucherNo)->delete();

            // Delete the data record
            $invoice_master->delete();

            UmrahInvoicePassenger::where('umrah_invoice_master_id', $id)->delete();
            InvoiceHotel::where('InvoiceMasterID', $id)->delete();
            InvoiceTransport::where('InvoiceMasterID', $id)->delete();

            DB::commit();// Commit the transaction
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice Delete successfully.',
                ],200);
                
            } catch (\Exception $e) {
            
                DB::rollBack();  // Rollback the transaction if there is an error

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        
    }
    
    
    
    public function destroy2($id)
    {
        

        DB::beginTransaction();// Start a transaction

        try {
            $invoice_master = InvoiceMaster::findOrFail($id);
            $VoucherNo = $invoice_master->VoucherNo;

 
            VoucherMaster::where('Voucher', $VoucherNo)->delete();
            VoucherDetail::where('Voucher', $VoucherNo)->delete();
            Journal::where('InvoiceMasterID', $id)->delete();
            JournalDummy::where('InvoiceMasterID', $id)->delete();

            // Delete the data record
            $invoice_master->delete();

            UmrahInvoicePassenger::where('umrah_invoice_master_id', $id)->delete();
            InvoiceHotel::where('InvoiceMasterID', $id)->delete();
            InvoiceTransport::where('InvoiceMasterID', $id)->delete();

            DB::commit();// Commit the transaction
            
           return redirect()->route('umrah-invoice-master.index')->with('error', 'Deleted Successfully.')
    ->with('class', 'success');
                
            } catch (\Exception $e) {
            
                DB::rollBack();  // Rollback the transaction if there is an error

                return redirect()->route('umrah-invoice-master.index')->with('error', $e->getMessage())
    ->with('class', 'danger');
            }
        
    }

 
public function umrahSavePayment(Request $request)
{

         // Start a transaction
        DB::beginTransaction();

        try {


        $validator = Validator::make($request->all(), [
        'InvoiceType' => 'required|integer',
        'customer_name' => 'required|string|max:255',
        'InvoiceMasterID' => 'required|integer',
        'amount_received' => 'required|numeric|min:0.01',
        'ChartOfAccountID' => 'required|integer',
        'Date' => 'required|date',
        'payment_mode' => 'required|string|max:50',
        'PartyID' => 'required|integer',

        'Total' => 'nullable|numeric',
        'Balance' => 'nullable|numeric',
        'bank_charges' => 'nullable|numeric',
        'selectedAccountName' => 'nullable|string|max:255',
        'voucher_number' => 'nullable|string|max:50',
        'notes' => 'nullable|string|max:1000',

        'file' => 'nullable|array',
        'file.*' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 422);
    }

    $validated = $validator->validated();

    // Save your payment logic here...

    // return response()->json([
    //     'success' => true,
    //     'message' => 'Payment saved successfully!',
    //     'data' => $validated,
    // ], 200);


    
    
    if($request->InvoiceType == 1){
      $VoucherType = ($request->input('payment_mode') == 'CASH') ? 5 : 2;
      $acc_company = 'Debit';
      $acc_party = 'Credit';

    }
    elseif($request->InvoiceType == 3){
  $VoucherType = ($request->input('payment_mode') == 'CASH') ? 5 : 2;
      $acc_company = 'Debit';
      $acc_party = 'Credit';
    }
  else
    {
      $VoucherType = ($request->input('payment_mode') == 'CASH') ? 4 : 1;
      $acc_company = 'Credit';
      $acc_party = 'Debit';
    }

    $invoice_mst = array(
     
      'PaymentMode' => $request->input('payment_mode'),
      'Voucher' => $request->input('voucher_number'),
      'Note' => $request->input('selectedAccountName'),      

    );
     $id = DB::table('invoice_master')->where('InvoiceMasterID',  $request->InvoiceMasterID)->update($invoice_mst);


// log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $request->input('amount_received'),
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Invoice', 
      'VHNO' => $request->InvoiceMasterID, 
      'Narration' => 'Invoice Payment from invoice popup having' . ' vhno-> ' . $request->input('voucher_number'). ' voucher , payment mode and notes updated.' , 
      'Trace' => 1,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

$log= DB::table('log')->insertGetId($logdata);

// log input 
 
 $voucher_mst = array(
      'VoucherCodeID' => $VoucherType,
      'Voucher' => $request->input('voucher_number'),
      'Narration' => $request->notes,
      'Amount' => $request->input('amount_received'),
      'Date' => dateformatpc($request->Date),
      'BranchID' => session::get('BranchID'),
    );

    $id = DB::table('voucher_master')->insertGetId($voucher_mst);


    // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
       'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher', 
      'VHNO' =>  $request->InvoiceMasterID, 
      'Narration' => 'voucher master created from invoice popup '. $request->input('voucher_number'), 
      'Trace' => 2,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

$log= DB::table('log')->insertGetId($logdata);


// log input 


  

    $voucher_det_dr = array(
      'VoucherMstID' => $id,
      'Voucher' =>  $request->input('voucher_number'),
      'Date' => dateformatpc($request->Date),
      'ChOfAcc' => $request->input('deposit_to'),

      'PartyID' => $request->input('PartyID'),
      'Narration' => $request->notes,
      'InvoiceNo' => $request->InvoiceMasterID,
      'BranchID' => session::get('BranchID'),
      // 'RefNo' => '',
      
      
      $acc_company => $request->input('amount_received'),
    );

    //  dd( $voucher_det_dr);

 

    $id1 = DB::table('voucher_detail')->insert($voucher_det_dr);
    // dd('Done');


        // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $request->input('amount_received'),
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher', 
      'VHNO' => $request->InvoiceMasterID, 
      'Narration' => 'Invoice Payment from invoice popup voucher created having' . 'vhno-> ' . $request->input('voucher_number') . ' amount value -> '.   $request->input('amount_received') .'-> '. $request->input('deposit_to') .' '.  $acc_company.' action ', 
      'Trace' => 3,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );
 
$log= DB::table('log')->insertGetId($logdata);

// log input 




    $voucher_det_cr = array(
      'VoucherMstID' => $id,
      'Voucher' =>  $request->input('voucher_number'),
      'Date' =>  dateformatpc($request->Date),
      'ChOfAcc' => 110400,
      // 'SupplierID' => '',
      'PartyID' =>  $request->input('PartyID'),
      'Narration' => $request->notes,
      'InvoiceNo' => $request->InvoiceMasterID,
      // 'RefNo' => '',
      $acc_party => $request->input('amount_received'),
      'BranchID' => session::get('BranchID'),

    );
 
  
    $id2 = DB::table('voucher_detail')->insert($voucher_det_cr);

    // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $request->input('amount_received'),
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher', 
      'VHNO' => $request->InvoiceMasterID, 
      'Narration' => 'Invoice Payment from invoice popup voucher created having' . 'vhno-> ' . $request->input('voucher_number') . ' amount value -> '.   $request->input('amount_received') .'-> 110400'. $acc_party .' action ', 
      'Trace' => 4,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

$log= DB::table('log')->insertGetId($logdata);

// log input 





    if ($request->bank_charges > 0) {

      $voucher_det_dr_bc = array(
        'VoucherMstID' => $id,
        'Voucher' =>  $request->input('voucher_number'),
        'Date' => dateformatpc($request->Date),
        'ChOfAcc' => $request->ChartOfAccountID,

        'PartyID' => $request->input('PartyID'),
        'Narration' => $request->notes . 'Bank Charges',
        'InvoiceNo' => $request->InvoiceMasterID,
        // 'RefNo' => '',
        'Debit' => $request->bank_charges,
        'BranchID' => session::get('BranchID'),
      );

      // dd( $voucher_det_dr);

      $id1 = DB::table('voucher_detail')->insert($voucher_det_dr_bc);
      // dd('Done');

          // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $request->bank_charges,
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher', 
      'VHNO' => $request->input('voucher_number'), 
      'Narration' => 'Bank charges Payment from invoice popup voucher created having' . 'vhno-> ' . $request->input('voucher_number') . ' amount value -> '.   $request->bank_charges .'-> '.' DR -chart of account '. $request->bank_charges, 
      'Trace' => 6,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 



      $voucher_det_cr_bank_charges = array(
        'VoucherMstID' => $id,
        'Voucher' =>  $request->input('voucher_number'),
        'Date' =>  dateformatpc($request->Date),
        'ChOfAcc' => $request->input('deposit_to'),
        // 'SupplierID' => '',
        'PartyID' =>  $request->input('PartyID'),
        'Narration' => $request->notes . 'Bank Charges',
        'InvoiceNo' => $request->InvoiceMasterID,
        // 'RefNo' => '',
        'Credit' => $request->bank_charges,
        'BranchID' => session::get('BranchID'),

      );
      $id2 = DB::table('voucher_detail')->insert($voucher_det_cr_bank_charges);

    // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $request->bank_charges,
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher', 
      'VHNO' => $request->input('voucher_number'), 
      'Narration' => 'Bank charges Payment from invoice popup voucher created having' . 'vhno-> ' . $request->input('voucher_number') . ' amount value -> '.   $request->bank_charges .'-> '.' CR -chart of account '. $request->bank_charges, 
      'Trace' => 7,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 


    }


       // Handle file uploads
    if ($request->hasFile('file')) {
      foreach ($request->file('file') as $file) {
        $filePath = $file->store('uploads', 'public');
        // Save file paths or additional logic here
      }
    }





     DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => 'Saved',
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

 


 


  public function ajax_umrah_invoice_balance($id)
  {

    $invoice_master = InvoiceMaster::find($id);

 
    // $balance = Journal::where('InvoiceMasterID', $id)->whereIn('ChartOfAccountID', [110400, 210100])->where('PartyID',$invoice_master->PartyID)
    $balance = Journal::where('InvoiceMasterID', $id)->where('ChartOfAccountID', 110400)->where('PartyID',$invoice_master->PartyID)
    ->selectRaw('COALESCE(SUM(Dr),0)  as Invoice')
    ->selectRaw('COALESCE(SUM(Cr),0)  as Paid')
    ->selectRaw('COALESCE(SUM(Dr),0) - COALESCE(SUM(Cr),0) as balance')
    ->first();

    $data= array(

      'PartyID' => $invoice_master->PartyID,
      'Total' =>$balance->Invoice,
      'Balance' => $balance->balance
    );


     return response()->json([
                'success' => true,
                'message' => 'Payment saved successfully!',
                'data' => $data,
            ], 200);

  }

public function partyRefNumber($partyID)
{

    $party=Party::find($partyID);
    $code=getInitials($party->PartyName);
    
}

public function umrah_invoice_voucher(Request $request)
    {
      
  
          // Start a transaction
        DB::beginTransaction();

        try {

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'umrah_invoice_master_id' => 'required',
                'Validity' => 'required',
                'VisaSupplierID' => 'required',
                'TicketSupplierID' => 'required',
                'InvoiceStatus' => 'required',
            ],
            // [
            //     'Date.required' => 'The Date is required.',
            //     'PartyID.required' => 'The Customer is required.',
            // ]
            // 
             );

              

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }


           
            // $data = $request->all();// storing request data in array

            $data = $request->only([
            'umrah_invoice_master_id',
            'Validity',
            'CareOf',
            'VisaSupplierID',
            'TicketSupplierID',
            'InvoiceStatus',
            'Remarks',
            'RevisedDate',
            'RevisedRemarks',   

             ]);

            $message = null;
            
            if ($request->umrah_invoice_master_id != null) {
              
                // Fetch the existing UmrahInvoiceMaster instance and update it
                $umrahInvoiceMaster = InvoiceMaster::find($request->umrah_invoice_master_id);
                $umrahInvoiceMaster->update($data);
                $message = "Umrah Invoice Update successfully.";
            } else {
                // Create a new UmrahInvoiceMaster instance
                $umrahInvoiceMaster = InvoiceMaster::create($data);
                $message = "Umrah Invoice Create successfully.";
            }

 
            if($request->InvoiceStatus=='APPROVED')
            {
                $this->JournalEntries($request->umrah_invoice_master_id,$request->VisaSupplierID,$request->TicketSupplierID);
            }
            else
            {
                $this->RemoveJournalEntries($request->umrah_invoice_master_id);
            }

            DB::commit();// Commit the transaction

            // Return a JSON response with a success message
            return response()->json([
                'success' => true,
                'message' => $message,
                'umrah_invoice_master_id' => $umrahInvoiceMaster->InvoiceMasterID,
                'redirect_url' => route('umrah-invoice-master.index'),
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


 public function JournalEntries($id, $visa_supplierID, $ticket_supplierID)
{
    
    DB::beginTransaction();

    try {
        // Step 1: Remove previous Journal Entries
        Journal::where('InvoiceMasterID', $id)->delete();

        // Step 2: Update PartyID in JournalDummy for visa entries
        $visa_data = ['PartyID' => $visa_supplierID];
        JournalDummy::where('InvoiceMasterID', $id)->where('Trace', 'like', '%-visaPOTCR')->update($visa_data);
        JournalDummy::where('InvoiceMasterID', $id)->where('Trace', 'like', '%-visaPOTDR')->update($visa_data);
        JournalDummy::where('InvoiceMasterID', $id)->where('Trace', 'like', '%-visaAP')->update($visa_data);

        // Step 3: Update PartyID in JournalDummy for ticket entries
        $ticket_data = ['PartyID' => $ticket_supplierID];
        JournalDummy::where('InvoiceMasterID', $id)->where('Trace', 'like', '%-POTCR')->update($ticket_data);
        JournalDummy::where('InvoiceMasterID', $id)->where('Trace', 'like', '%-POTDR')->update($ticket_data);
        JournalDummy::where('InvoiceMasterID', $id)->where('Trace', 'like', '%-AP')->update($ticket_data);


        // "Umrah Voucher MUHAMMAD UMAIR SHAFI EG5181352 All Packages 1A+0C+0I CR(U)-2"

        
        $master = InvoiceMaster::with('party','package')->find($id);
        $pax_dr = UmrahInvoicePassenger::where('umrah_invoice_master_id', $id)
        ->sum(DB::raw('visa_sale + ticket_sale'));
        
        $transport_dr = InvoiceTransport::where('InvoiceMasterID', $id)
        ->sum('TransportReceivable');
        
        $hotel_dr = InvoiceHotel::where('InvoiceMasterID', $id)
        ->sum('HotelReceivable');
        
        $Dr = $pax_dr + $transport_dr + $hotel_dr;
        
        // $this->getInvoiceNarration($id); // narration for ledger
        
        
        // A/R
        $visa_AR = [    
            'VHNO' => 'INV-' . $id,
            'JournalType' => $master->package->name,
            'ChartOfAccountID' => '110400',  // A/R
            'BranchID' => session()->get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $master->party->PartyID,
        'InvoiceMasterID' => $id,
        'Date' => $master->Date,
        'Dr' => $Dr,
        
        'Narration' => $this->getInvoiceNarration($id),
        
        
        // 'PaxName' => $request->passenger_name,
        // 'PassportNo' => $request->passport_no,
        // 'Type' => $request->type,
        // 'VisaNo' => $request->visa_no,
        // 'PNR' => $request->pnr,
        
        'Trace' => $id.'AR',
        'UserID' => session()->get('UserID'),
        
        'Currency' => $master->SaleCurrency,
        'Rate' => $master->forex_sale,
        'ForeignAmount' => $master->visa_sale,
        'ForeignDebit' => ($master->visa_sale * $master->forex_sale),
    ];
    
   

        Journal::create($visa_AR);










        // Step 4: Copy updated data from JournalDummy to Journal
        $columns = Schema::getColumnListing('journal_dummy');
        $columns = array_diff($columns, ['JournalID']); // exclude primary key

        $records = DB::table('journal_dummy')
            ->select($columns)
            ->where('InvoiceMasterID', $id) // only current ID
            ->get();

        $data = [];
        foreach ($records as $record) {
            $data[] = (array) $record;
        }

        if (!empty($data)) {
            DB::table('journal')->insert($data);
        }

        // Commit transaction if everything is successful
        DB::commit();

    } catch (\Exception $e) {
        DB::rollBack();
        throw $e; // Let Laravel handle the exception (or log it)
    }
}
    
    
  public function RemoveJournalEntries($id)
{
    if (!empty($id)) {
        DB::transaction(function () use ($id) {
            Journal::where('InvoiceMasterID', $id)->delete();
        });
    }
}


public function getInvoiceNarration($InvoiceMasterID)
{

    $master = InvoiceMaster::with('party','package')->find($InvoiceMasterID);
    $passanger = UmrahInvoicePassenger::where('umrah_invoice_master_id', $InvoiceMasterID)
    ->where('relation_type', 'head')
    ->first();
    
    $summary = $this->getPassengerForLedger($InvoiceMasterID);
    
    // $narration="Voucher ". $master->party->PartyName. " ". $passanger->passport_no. " ". $passanger->passenger_name ." ". $master->package->name. " ".$summary;
    

     $narration= 'Pax: '.$passanger->passenger_name. " - Passport# ". $passanger->passport_no. " - (".$summary.")";
    
    return strtoupper($narration);


}

     
  
}



 