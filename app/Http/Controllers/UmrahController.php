<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Carbon\Carbon;


use PDF;


class UmrahController extends Controller
{
    public function Umrah()
  {
    
    $pagetitle = 'Umrah';

      ////////////////////////////END SCRIPT ////////////////////////////////////////////////      
    $voucher_type = DB::table('v_invoice_master')->get();
    // $chartofaccount = DB::table('chartofaccount')->where('Code', 'E')->get();
    $chartofaccount = DB::table('chartofaccount')->where('ChartOfAccountID', '560110')->get();

    session()->forget('LeadID');
    session()->forget('PartyID');


    return view('umrah.umrah', compact('pagetitle','voucher_type','chartofaccount'));
  }
   

  public function ajax_umrah(Request $request)
   {
    session::put('menu', 'Umrah');
    $pagetitle = 'Umrah';
    if ($request->ajax()) {
      $query = DB::table('v_invoice_detail');
      $query->whereIn('ItemCode', ['UB','UA']);
      // Apply filters if they are present in the request
      // if ($request->has('item_name') && !empty($request->item_name)) {
      //     $query->where('ItemName', 'like', '%' . $request->item_name . '%');
      // }

      if ($request->has('party_name') && !empty($request->party_name)) {
        $query->where('PartyName', 'like', '%' . $request->party_name . '%');
      }


if ($request->has('Phone') && !empty($request->Phone)) {
    // Clean the input phone number (remove spaces)
 
    // Extract the last 9 digits from the input phone
    $last_9_digits = substr($request->Phone, -9);

    // Modify the query to match the last 9 digits
    $query->where(function ($subQuery) use ($last_9_digits) {
        $subQuery->where(DB::raw("REPLACE(Phone, ' ', '')"), 'like', '%' . $last_9_digits . '%');
    });
}




      if ($request->has('startdate') && !empty($request->startdate)) {
        $query->whereDate('Date', '>=', $request->startdate);
      }
      if ($request->has('enddate') && !empty($request->enddate)) {
        $query->whereDate('Date', '<=', $request->enddate);
      }

      if ($request->has('UserID') && !empty($request->UserID)) {
        $query->where('UserID', $request->UserID);
      }

      if ($request->has('ItemID') && !empty($request->ItemID)) {
        $query->where('ItemID', $request->ItemID);
      }

      $data = $query->orderBy('InvoiceMasterID')->get();
      $PaymentMode = DB::table('chartofaccount')->distinct()->pluck('category');

      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {


//  $editLink = '';

//                 // Check condition for ItemCode
//               if ($row->ItemCode == 'UA' || $row->ItemCode == 'UB') {
//     $editLink = '<a href="' . URL('/UmrahEdit/' . $row->InvoiceMasterID) . '" class="dropdown-item">
//         <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit Umrah Invoice
//     </a>';
// } else {
//     $editLink = '<a href="' . URL('/InvoiceEdit/' . $row->InvoiceMasterID) . '" class="dropdown-item">
//         <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit Invoice
//     </a>';
// }



    //                            <a href="' . URL('/UmrahEdit/' . $row->InvoiceMasterID) . '" class="dropdown-item">
    //     <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit Umrah Invoice
    // </a>


          $btn = '
                    <div class="d-flex align-items-center col-actions">
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-horizontal font-size-18"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(-33px, 27px);" data-popper-placement="bottom-end">
                                
                                
                                <li><a href="javascript:void(0)" onclick="openLedgerModal('.$row->PartyID.', \'' . $row->PartyName . '\')" class="dropdown-item"><i class="mdi mdi-account-details font-size-18  me-1" style="color:#FF5733;"></i> <strong> Party Ledger</strong></a></li>
                              <div class="dropdown-divider"></div>


 <a href="' . URL('/UmrahRefund/' . $row->InvoiceMasterID) . '" class="dropdown-item">
        <i class="mdi mdi-backup-restore font-size-18 text-danger me-1"></i> Umrah Refund 
    </a>                              <div class="dropdown-divider"></div>


                                 <li><a href="javascript:void(0)" onclick="loadPDF(\'' . URL('/UmrahPDFView/' . $row->InvoiceMasterID) . '\')" class="dropdown-item"><i class="mdi mdi-file-pdf font-size-16  me-1" style="color:#FF5733;"></i> View Invoice</a></li>
                              

 

    <a href="' . URL('/UmrahEdit/' . $row->InvoiceMasterID) . '" class="dropdown-item">
        <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit Invoice
    </a>



                                
                                <li><a href="' . URL('/UmrahPDF/' . $row->InvoiceMasterID) . '/download' . '" target="_blank" class="dropdown-item"><i class="mdi mdi-file-pdf-outline font-size-16  me-1" style="color:#AF0505;"></i> Download Invoice</a></li>
                                
                                <li><a href="' . URL('/UmrahPDF/' . $row->InvoiceMasterID) . '" target="_blank" class="dropdown-item"><i class="mdi mdi-eye-outline font-size-16 text-secondary me-1"></i> View PDF</a></li>
                                
                                <li><a href="javascript:void(0)" onclick="delete_invoice(' . $row->InvoiceMasterID . ')" class="dropdown-item"><i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete Invoice</a></li>
                            </ul>
                        </div>
                    </div>';
          return $btn;
        })
        ->rawColumns(['action'])
        ->with('paymentModes', $PaymentMode)
        ->make(true);
    }

    return view('umrah.umrah',compact('pagetitle'));
  }

   
  public function UmrahCreate()
  {
   
   // $currentDate = Carbon::now();

   // dd($currentDate->subDays(10));




         // dd('reached');
        $pagetitle = 'Create Umrah';

        $party = DB::table('party')->get();
        $supplier = DB::table('supplier')->where('SupplierCatID',3)->get(); //3 is for umrah only
      
        $items = DB::table('item')->whereIn('ItemCode',['UB','UA'])->get();
        
        $user = DB::table('user')->get();
        
            $saleman = DB::table('user')->where('Active','Yes')->get();

             
    $invoice_type = DB::table('invoice_type')->where('InvoiceTypeCode','UI')->get();
  $vhno = DB::table('invoice_master')->select(DB::raw('IFNULL(max(InvoiceMasterID)+1,1) as VHNO'))->get();
 
        
        return view('umrah.umrah_create', compact('party', 'pagetitle', 'items', 'user','supplier','saleman','invoice_type','vhno'));
      
  }



   public  function UmrahValidate1(request $request)
   {



// if (!$request->InvoiceMasterID) {

// // Initialize an array to store responses
// $responseArray = [];

// // Start for item array from invoice
// for ($i = 0; $i < count($request->ItemID); $i++) {

//     $passportNo = $request->Passport[$i];

//     // Get the latest entry from the database
//     $latestEntry = DB::table('v_invoice_detail')
//         ->where('Passport', $passportNo)
//         ->orderBy('Date', 'desc')
//         ->first(); // Get only the latest record

//     $date = $request->input('Date');   
//     $tenDaysAgo = Carbon::parse($date)->subDays(10)->format('Y-m-d');

//     if ($latestEntry) {
//         // Compare the latest entry date with 10 days ago
//         if (Carbon::parse($latestEntry->Date) < $tenDaysAgo) {
//             // Add success and message to response array
           
//         } else {
//             // Add failure message to response array
//             return response()->json([
//                 'success' => false,
//                 'message' => "Passport ". $passportNo. " found within 10 days. Entry found on " . dateformatman2($latestEntry->Date)
//             ]);
//         }
//     } 
// }


// }




// Return all responses at once after the loop
 

 


  // Validate the form input
$validator = Validator::make($request->all(), [
 
    'ItemID.*' => 'required',
    'SupplierID.*' => 'required',
    'PaxName.*' => 'required',  // Validate each PaxName
    'Contact.*' => 'required',  // Validate each Contact
     'Fare.*' => 'required', // Validate each Passport
    'Total.*' => 'required', // Validate each Passport
    'Paid.*' => 'required', // Validate each Passport
    'DepartureDate.*' => 'required', // Validate each Passport
    'PassportFile.*' => 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:5048', // Validate image file
    'EmirateIDFileFront.*' => 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:5048', // Validate image file
    'EmirateIDFileBack.*' => 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:5048', // Validate image file
    'PictureFile.*' => 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:5048', // Validate image file

]);
             
                      
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ]);
    }
 








     // If validation passes, return success
        return response()->json([
            'success' => true,
            'message' => 'Validation passed. Proceed with upload.'
        ]);




}

  

   public  function UmrahSave(request $request)
  {

 

     
     $invoice_type = DB::table('invoice_type')->where('InvoiceTypeID',$request->InvoiceTypeID)->get();

     ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////


        try {
                DB::beginTransaction();
                 // your alll  queries here -->
    

    $companyImagePath = null;
    if ($request->hasFile('Document')) {

     $validator = Validator::make($request->all(), [
       
        'Document' => 'required|image|mimes:pdf,pdf,jpeg,png,jpg,gif|max:2048', // Validate image file
    ]);


      if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ]);
    }

       $file = $request->file('Document');
        
        // Generate a unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Define the storage path (optional: use Storage facade)
        $filePath = public_path('uploads/Document'); 
        
        // Move the file to the destination path
        $file->move($filePath, $filename);
        
        // Set the image path to be saved in the database
        $DocumentImagePath = 'uploads/Document/' . $filename;
    }

    $invoice_mst = array(
      'InvoiceMasterID' => $request->input('VHNO'),
      'InvoiceTypeID' => $request->input('InvoiceTypeID'),
      'LeadID' => session::get('LeadID'),
      'Date' => $request->input('Date'),
      'PartyID' => $request->PartyID,
      'DueDate' => $request->input('DueDate'),
      // 'PaymentMode' => $request->input('PaymentMode'),
      'Total' => $request->input('GrandTotal'),

      'Paid' => $request->input('amountPaid'),
      'Balance' => $request->input('amountDue'),
      'UserID' => $request->input('SalemanID'),
      'Note' => $request->input('remarks'),
      'Document' =>  ($request->hasFile('Document')) ? $DocumentImagePath : '',
      'BranchID' => session('BranchID'),
      'UserID' => session('UserID'),

    );

    // $id= DB::table('')->insertGetId($data);

    $id = DB::table('invoice_master')->insertGetId($invoice_mst);

    //  start for item array from invoice
    for ($i = 0; $i < count($request->ItemID); $i++) {


      // // validate passport no

      // Get the passport number from the request
      // $passportNo = $request->Passport[$i];

// $currentDate = Carbon::now(); // Get the current date
// $tenDaysAgo = Carbon::now()->subDays(11)->format('Y-m-d'); // Date 10 days ago

// $existingEntry = DB::table('v_invoice_detail')
//     ->where('Passport',  $passportNo)
//     ->whereBetween('Date', [$tenDaysAgo, $request->input('Date')])
//     ->get();

// dd($existingEntry);

//        dd($existingEntry);
//        if ($existingEntry) {
//           return response()->json([
//               'success' => false,
//               'message' => 'This passport number was used within the last 10 days.',
//           ]);
//       }


      // end of passport validation within 10 days




//     $latestEntry = DB::table('v_invoice_detail')
//       ->where('Passport', $passportNo)
//       ->orderBy('Date', 'desc')
//       ->first(); // Get only the latest record

//       $date = $request->input('Date');   
//       $tenDaysAgo = Carbon::parse($date)->subDays(10)->format('Y-m-d');

// if ($latestEntry) {
//     // Compare the latest entry date with 10 days ago
//     if (Carbon::parse($latestEntry->Date)->lt($tenDaysAgo)) {
//         dd("You can make a new invoice");
//     } else {
//         dd("Passport used within the last 10 days");
//     }
// } else {
//     dd("No previous records found, you can make a new invoice");
// }

//  dd($request->all());





         $invoice_det = array(
        'InvoiceMasterID' => $request->input('VHNO'),
        'ItemID' => $request->ItemID[$i],
        'SupplierID' => $request->SupplierID[$i],

        'VisaType' => $request->VisaType[$i],
        'PaxName' => $request->PaxName[$i],
        'Contact' => $request->Contact[$i],
        'Passport' => $request->Passport[$i],
        'PickPoint' => $request->PickPoint[$i],
        'RoomType' => $request->RoomType[$i],


        'UmrahFare' => $request->Fare[$i] ,
        'Fare' => $request->Paid[$i] - ($request->Service[$i] + $request->VAT[$i]),
        
        'Taxable' => $request->VAT[$i],
        
        'Service' => $request->Service[$i],
        
        
        'Total' => $request->Total[$i],
        'Paid' => $request->Paid[$i],
        
        'Deduction' => $request->deduction[$i] ?? 0,
        //vlg

        'PaymentInBus' => $request->PaymentInBus[$i],
        'DepartureDate' => $request->DepartureDate[$i],
        'BranchID' => session('BranchID'),
      'UserID' => session('UserID'),

      );

      // document uploading of  passport, EID, Picture

       $destinationPath = public_path('/documents');


               // Ensure we are processing the $i-th file for each item
        if (isset($request->file('PassportFile')[$i])) {
            $file = $request->file('PassportFile')[$i];

            // Generate a unique filename
            $filename = time() . '_' . $file->getClientOriginalName();

            // Define the storage path (optional: use Storage facade)
            $filePath = public_path('uploads/Document'); 

            // Move the file to the destination path
            $file->move($filePath, $filename);

            // Set the image path to be saved in the database
            $DocumentImagePath = 'uploads/Document/' . $filename;

            // Add the file path to the invoice details array
            $invoice_det = Arr::add($invoice_det, 'PassportFile', $DocumentImagePath);
        }


        // Ensure we are processing the $i-th file for each item
        if (isset($request->file('EmirateIDFileFront')[$i])) {
            $file1 = $request->file('EmirateIDFileFront')[$i];

            // Generate a unique filename
            $filename1 = time() . '_' . $file1->getClientOriginalName();

            // Define the storage path (optional: use Storage facade)
            $filePath1 = public_path('uploads/Document'); 

            // Move the file to the destination path
            $file1->move($filePath1, $filename1);

            // Set the image path to be saved in the database
            $DocumentImagePath1 = 'uploads/Document/' . $filename1;

            // Add the file path to the invoice details array
            $invoice_det = Arr::add($invoice_det, 'EmirateIDFileFront', $DocumentImagePath1);
        }


       // Ensure we are processing the $i-th file for each item
        if (isset($request->file('EmirateIDFileBack')[$i])) {
            $file2 = $request->file('EmirateIDFileBack')[$i];

            // Generate a unique filename
            $filename2 = time() . '_' . $file2->getClientOriginalName();

            // Define the storage path (optional: use Storage facade)
            $filePath2 = public_path('uploads/Document'); 

            // Move the file to the destination path
            $file2->move($filePath2, $filename2);

            // Set the image path to be saved in the database
            $DocumentImagePath2 = 'uploads/Document/' . $filename2;

            // Add the file path to the invoice details array
            $invoice_det = Arr::add($invoice_det, 'EmirateIDFileBack', $DocumentImagePath2);
        }


     // Ensure we are processing the $i-th file for each item
        if (isset($request->file('PictureFile')[$i])) {
            $file3 = $request->file('PictureFile')[$i];

            // Generate a unique filename
            $filename3 = time() . '_' . $file3->getClientOriginalName();

            // Define the storage path (optional: use Storage facade)
            $filePath3 = public_path('uploads/Document'); 

            // Move the file to the destination path
            $file3->move($filePath3, $filename3);

            // Set the image path to be saved in the database
            $DocumentImagePath3 = 'uploads/Document/' . $filename3;

            // Add the file path to the invoice details array
            $invoice_det = Arr::add($invoice_det, 'PictureFile', $DocumentImagePath3);
        }


 
      // end of document uploading

      $idd = DB::table('invoice_detail')->insertGetId($invoice_det);

      // journal entry start from here when full payment is made part 1
      if ($request->input('InvoiceTypeID') == 3) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Paid[$i] -$request->deduction[$i],
          'Narration' => $request->PaxName[$i],

          'Trace' => 105,
          'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
        );


        $id = DB::table('journal')->insertGetId($loop_AR);

        $loop_purchase_cr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Paid[$i] - ($request->Service[$i] + $request->VAT[$i]),
          'Narration' => $request->PaxName[$i],
          'Trace' => 106,
          'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
        );

        $id = DB::table('journal')->insertGetId($loop_purchase_cr);

        // Services Charges
        if ($request->Service[$i] > 0) {


          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->Service[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 107,
            'BranchID' => session('BranchID'),
            'UserID' => session('UserID'),
          );


          $id = DB::table('journal')->insertGetId($comission);

        

          // AR
        }
        ///////////////////udpatejune
        else {
          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => abs($request->Service[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 108,
            'BranchID' => session('BranchID'),
            'UserID' => session('UserID'),
          );


          $id = DB::table('journal')->insertGetId($comission);
        }
    

        // Purchase of Ticket - > PIA
        $loop_purchase_dr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Paid[$i] - ($request->Service[$i] + $request->VAT[$i]),
          'Narration' => $request->PaxName[$i],
          'Trace' => 109,
          'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
        );
        $id = DB::table('journal')->insertGetId($loop_purchase_dr);
  

        // A/P - > PIA
        $loop_ap = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '210100',  // A/P - > PIA
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Paid[$i] - ($request->Service[$i] + $request->VAT[$i]),
          'Narration' => $request->PaxName[$i],
          'Trace' => 110,
          'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
        );

        $id = DB::table('journal')->insertGetId($loop_ap);

        // tax start from here
        // if tax is > 0 
        if ($request->VAT[$i] > 0) {

          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->VAT[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 111,
            'BranchID' => session('BranchID'),
            'UserID' => session('UserID'),
          );
 
          $id = DB::table('journal')->insertGetId($tax_payable);

          // tax credit from comission
          // $tax_expense = array(
          //     'VHNO' => $invoice_type[0]->InvoiceTypeCode.$request->input('VHNO'), 
          //     'JournalType' => $invoice_type[0]->InvoiceTypeCode, 
          //     'ChartOfAccountID' => 410101, // COMISSION (TAX WILL MINUS FROM COMISSION)
          //     'SupplierID' => $request->SupplierID[$i], 
          //     'PartyID' => $request->PartyID, 
          //     'InvoiceMasterID' => $request->input('VHNO'), 
          //     'Date' => $request->input('Date'),
          //     'Trace' => 112,
          //      'Dr' => $request->VAT[$i], // kamal disable this code due to net amount posted in commsion. net off 100 - tax 
          //   );

          //  $id= DB::table('journal')->insertGetId($tax_expense);

        } else {
          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => abs($request->VAT[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 111,
            'BranchID' => session('BranchID'),
            'UserID' => session('UserID'),
          );
          $id = DB::table('journal')->insertGetId($tax_payable);
        }


        // tax end here 

        // //discount

        // if ($request->Discount[$i] > 0) {

        //   $discount_given = array(
        //     'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        //     'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        //     'ChartOfAccountID' => '410155', // Discount Received -> commsion update chart of account
        //     'SupplierID' => $request->SupplierID[$i],
        //     'PartyID' => $request->PartyID,
        //     'InvoiceMasterID' => $request->input('VHNO'),
        //     'Date' => $request->input('Date'),
        //     'Dr' => $request->Discount[$i],
        //     'Narration' => $request->PaxName[$i],
        //     'Trace' => 203
        //   );

        //   $id = DB::table('journal')->insertGetId($discount_given);
        // }
      }
      // journal entry end here part 1

      // SALE RETURN FOR EACH ROW

      // journal entry start from here when full payment is made part 2
       if ($request->input('InvoiceTypeID') == 5) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Paid[$i],
          'Narration' => $request->PaxName[$i],

          'Trace' => 105,
          'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
        );


        $id = DB::table('journal')->insertGetId($loop_AR);

        $loop_purchase_cr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Fare[$i] ,
          'Narration' => $request->PaxName[$i],
          'Trace' => 106,
          'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
        );

        $id = DB::table('journal')->insertGetId($loop_purchase_cr);

        // Services Charges
        if ($request->Service[$i] > 0) {


          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->Service[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 107,
            'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
          );


          $id = DB::table('journal')->insertGetId($comission);

        

          // AR
        }
        
        
        
        // Services Charges
        if ($request->deduction[$i] > 0) {


          // $deduction_ar = array(
          //   'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          //   'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          //   'ChartOfAccountID' => '110400',  // A/R
          //   'SupplierID' => $request->SupplierID[$i],
          //   'PartyID' => $request->PartyID,
          //   'InvoiceMasterID' => $request->input('VHNO'),
          //   'Date' => $request->input('Date'),
          //   'Dr' => $request->deduction[$i],
          //   'Narration' => $request->PaxName[$i] . ' - Deduction charges',
          //   'Trace' => 1077
          // );


          // DB::table('journal')->insertGetId($deduction_ar);

        
        
          $deduction_income = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->deduction[$i],
            'Narration' => $request->PaxName[$i] . ' - Deduction income',
            'Trace' => 1077,
            'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
          );


          DB::table('journal')->insertGetId($deduction_income);

        }
        
        
        
        
        

        // Purchase of Ticket - > PIA
        $loop_purchase_dr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Fare[$i] ,
          'Narration' => $request->PaxName[$i],
          'Trace' => 109,
          'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
        );
        $id = DB::table('journal')->insertGetId($loop_purchase_dr);
  

        // A/P - > PIA
        $loop_ap = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '210100',  // A/P - > PIA
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Fare[$i] ,
          'Narration' => $request->PaxName[$i],
          'Trace' => 110,
          'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
        );

        $id = DB::table('journal')->insertGetId($loop_ap);

        // tax start from here
        // if tax is > 0 
        if ($request->VAT[$i] > 0) {

          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->VAT[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 111,
            'BranchID' => session('BranchID'),
          'UserID' => session('UserID'),
          );
 
          $id = DB::table('journal')->insertGetId($tax_payable);

      

        }
        
        else 
        
        {
          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => abs($request->VAT[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 111,
            'BranchID' => session('BranchID'),
            'UserID' => session('UserID'),
          );
          $id = DB::table('journal')->insertGetId($tax_payable);
        }



      }
      // journal entry end here part 1

      // END SALE RETURN FOR EACH ROW

    }

    // end foreach

    // queries end here  -->
    DB::commit();

    // return redirect('Invoice')
    //   ->with('error', 'Invoice Saved')
    //   ->with('class', 'success')
    //   ->with('invoiceMasterID', $InvoiceMasterID);


    session()->flash('invoiceMasterID', $request->VHNO);


         return response()->json([
            'success' => true,
            'message' => 'Umrah invoice saved successfully',
            'redirect_url' => URL("/Umrah"),
            'invoiceMasterID' => $request->VHNO
        ]);

       
            } catch (\Exception $e) {
                DB::rollBack();

                 return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);

                // return back()->with('error', $e->getMessage())->with('class', 'danger');
            }


  }


  



 


  


  public function UmrahRefund($id)
  {
    
        // dd('reached');
        $pagetitle = 'Edit Umrah';
        $party = DB::table('party')->get();
        $supplier = DB::table('supplier')->where('SupplierCatID',3)->get(); //3 is for umrah only
      
        $items = DB::table('item')->whereIn('ItemCode',['UB','UA'])->get();
        
        $user = DB::table('user')->get();
        
       $saleman = DB::table('user')->where('Active','Yes')->get();

             
    $invoice_type = DB::table('invoice_type')->where('InvoiceTypeCode','UR')->get();

  
  $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
    $invoice_det = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();
 
         $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();
  


        return view('umrah.umrah_refund', compact('party', 'pagetitle', 'items', 'user','supplier','saleman','invoice_type','invoice_mst','invoice_det','vhno'));
      
  }



  public function UmrahEdit($id)
  {
    
        // dd('reached');
        $pagetitle = 'Edit Umrah';
        $party = DB::table('party')->get();
        $supplier = DB::table('supplier')->where('SupplierCatID',3)->get(); //3 is for umrah only
      
        $items = DB::table('item')->whereIn('ItemCode',['UB','UA'])->get();
        
        $user = DB::table('user')->get();
        
       $saleman = DB::table('user')->where('Active','Yes')->get();

             
    $invoice_type = DB::table('invoice_type')->whereIn('InvoiceTypeCode',['UI','UR'])->get();

  
  $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
    $invoice_det = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();
 
       


        return view('umrah.umrah_edit', compact('party', 'pagetitle', 'items', 'user','supplier','saleman','invoice_type','invoice_mst','invoice_det'));
      
  }


 
    

     public  function UmrahUpdate(request $request)
  {

   
     $invoice_type = DB::table('invoice_type')->where('InvoiceTypeID',$request->InvoiceTypeID)->get();

     ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////


        try {
                DB::beginTransaction();
                 // your alll  queries here -->
    
    $companyImagePath = null;
    if ($request->hasFile('Document')) {

     $validator = Validator::make($request->all(), [
       
        'Document' => 'required|image|mimes:pdf,jpeg,png,jpg,gif|max:2048', // Validate image file
    ]);


      if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ]);
    }

       $file = $request->file('Document');
        
        // Generate a unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Define the storage path (optional: use Storage facade)
        $filePath = public_path('uploads/Document'); 
        
        // Move the file to the destination path
        $file->move($filePath, $filename);
        
        // Set the image path to be saved in the database
        $DocumentImagePath = 'uploads/Document/' . $filename;

          $invoice_mst = array(
      'InvoiceMasterID' => $request->input('VHNO'),
      'InvoiceTypeID' => $request->input('InvoiceTypeID'),
      'Date' => $request->input('Date'),
      'PartyID' => $request->PartyID,
      'DueDate' => $request->input('DueDate'),
      // 'PaymentMode' => $request->input('PaymentMode'),
      'Total' => $request->input('GrandTotal'),

      'Paid' => $request->input('amountPaid'),
      'Balance' => $request->input('amountDue'),
      'UserID' => $request->input('SalemanID'),
      'Note' => $request->input('remarks'),
      'Document' =>  ($request->hasFile('Document')) ? $DocumentImagePath : '',

    );
    }

else
{


    $invoice_mst = array(
      'InvoiceMasterID' => $request->input('VHNO'),
      'InvoiceTypeID' => $request->input('InvoiceTypeID'),
      'Date' => $request->input('Date'),
      'PartyID' => $request->PartyID,
      'DueDate' => $request->input('DueDate'),
      // 'PaymentMode' => $request->input('PaymentMode'),
      'Total' => $request->input('GrandTotal'),

      'Paid' => $request->input('amountPaid'),
      'Balance' => $request->input('amountDue'),
      'UserID' => $request->input('SalemanID'),
      'Note' => $request->input('remarks'),
 
    );

    
}
    


    

    $master= DB::table('invoice_master')->where('InvoiceMasterID' , $request->VHNO)->update($invoice_mst);
    
    

   
  $detail = DB::table('invoice_detail')->where('InvoiceMasterID',$request->VHNO)->delete();
  $detail1 = DB::table('invoice_detail')->where('InvoiceMasterID',$request->VHNO)->get();
  
  // delete all the invoice data but done delete the payment section related to invoice
 $id2 = DB::table('journal')->where('InvoiceMasterID', $request->VHNO)
     ->whereNotNull('SupplierID')
    ->delete();  
 
 
  

     

    //  start for item array from invoice
    for ($i = 0; $i < count($request->ItemID); $i++) {
      $invoice_det = array(
        'InvoiceMasterID' => $request->input('VHNO'),
        'ItemID' => $request->ItemID[$i],
        'SupplierID' => $request->SupplierID[$i],

        'VisaType' => $request->VisaType[$i],
        'PaxName' => $request->PaxName[$i],
        'Contact' => $request->Contact[$i],
        'Passport' => $request->Passport[$i],
        'PickPoint' => $request->PickPoint[$i],
        'RoomType' => $request->RoomType[$i],
        'UmrahFare' => $request->Fare[$i] ,


        'Deduction' => $request->deduction[$i],

     
        'UmrahFare' => $request->Fare[$i] ,
        'Fare' => $request->Paid[$i] - ($request->Service[$i] + $request->VAT[$i]),
        
        'Taxable' => $request->VAT[$i],
        
        'Service' => $request->Service[$i],
        
        
        'Total' => $request->Total[$i],
        'Paid' => $request->Paid[$i],

        'PaymentInBus' => $request->PaymentInBus[$i],
        'DepartureDate' => $request->DepartureDate[$i],

      );


               // Ensure we are processing the $i-th file for each item
        if (isset($request->file('PassportFile')[$i])) {
            $file = $request->file('PassportFile')[$i];

            // Generate a unique filename
            $filename = time() . '_' . $file->getClientOriginalName();

            // Define the storage path (optional: use Storage facade)
            $filePath = public_path('uploads/Document'); 

            // Move the file to the destination path
            $file->move($filePath, $filename);

            // Set the image path to be saved in the database
            $DocumentImagePath = 'uploads/Document/' . $filename;

            // Add the file path to the invoice details array
            $invoice_det = Arr::add($invoice_det, 'PassportFile', $DocumentImagePath);
        }
        else
        {
          $invoice_det = Arr::add($invoice_det, 'PassportFile', $request->PassportFile_Old[$i]); 
        }


        // Ensure we are processing the $i-th file for each item
        if (isset($request->file('EmirateIDFileFront')[$i])) {
            $file1 = $request->file('EmirateIDFileFront')[$i];

            // Generate a unique filename
            $filename1 = time() . '_' . $file1->getClientOriginalName();

            // Define the storage path (optional: use Storage facade)
            $filePath1 = public_path('uploads/Document'); 

            // Move the file to the destination path
            $file1->move($filePath1, $filename1);

            // Set the image path to be saved in the database
            $DocumentImagePath1 = 'uploads/Document/' . $filename1;

            // Add the file path to the invoice details array
            $invoice_det = Arr::add($invoice_det, 'EmirateIDFileFront', $DocumentImagePath1);
        }
        else
        {
          $invoice_det = Arr::add($invoice_det, 'EmirateIDFileFront', $request->EmirateIDFileFront_Old[$i]);
        }


       // Ensure we are processing the $i-th file for each item
        if (isset($request->file('EmirateIDFileBack')[$i])) {
            $file2 = $request->file('EmirateIDFileBack')[$i];

            // Generate a unique filename
            $filename2 = time() . '_' . $file2->getClientOriginalName();

            // Define the storage path (optional: use Storage facade)
            $filePath2 = public_path('uploads/Document'); 

            // Move the file to the destination path
            $file2->move($filePath2, $filename2);

            // Set the image path to be saved in the database
            $DocumentImagePath2 = 'uploads/Document/' . $filename2;

            // Add the file path to the invoice details array
            $invoice_det = Arr::add($invoice_det, 'EmirateIDFileBack', $DocumentImagePath2);
        }
        else
        {
         $invoice_det = Arr::add($invoice_det, 'EmirateIDFileBack', $request->EmirateIDFileBack_Old[$i]); 
        }


     // Ensure we are processing the $i-th file for each item
        if (isset($request->file('PictureFile')[$i])) {
            $file3 = $request->file('PictureFile')[$i];

            // Generate a unique filename
            $filename3 = time() . '_' . $file3->getClientOriginalName();

            // Define the storage path (optional: use Storage facade)
            $filePath3 = public_path('uploads/Document'); 

            // Move the file to the destination path
            $file3->move($filePath3, $filename3);

            // Set the image path to be saved in the database
            $DocumentImagePath3 = 'uploads/Document/' . $filename3;

            // Add the file path to the invoice details array
            $invoice_det = Arr::add($invoice_det, 'PictureFile', $DocumentImagePath3);
        }
        else
        {
          $invoice_det = Arr::add($invoice_det, 'PictureFile', $request->PictureFile_Old[$i]); 
        }


      $idd = DB::table('invoice_detail')->insertGetId($invoice_det);

      // journal entry start from here when full payment is made part 1
      if ($request->input('InvoiceTypeID') == 3) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Paid[$i],
          'Narration' => $request->PaxName[$i],

          'Trace' => 105
        );


        $id = DB::table('journal')->insertGetId($loop_AR);

        $loop_purchase_cr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Paid[$i] - ($request->Service[$i] + $request->VAT[$i]),
          'Narration' => $request->PaxName[$i],
          'Trace' => 106
        );

        $id = DB::table('journal')->insertGetId($loop_purchase_cr);

        // Services Charges
        if ($request->Service[$i] > 0) {


          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->Service[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 107
          );


          $id = DB::table('journal')->insertGetId($comission);

        

          // AR
        }
        ///////////////////udpatejune
        else {
          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => abs($request->Service[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 108
          );


          $id = DB::table('journal')->insertGetId($comission);
        }
    

        // Purchase of Ticket - > PIA
        $loop_purchase_dr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Paid[$i] - ($request->Service[$i] + $request->VAT[$i]),
          'Narration' => $request->PaxName[$i],
          'Trace' => 109
        );
        $id = DB::table('journal')->insertGetId($loop_purchase_dr);
  

        // A/P - > PIA
        $loop_ap = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '210100',  // A/P - > PIA
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Paid[$i] - ($request->Service[$i] + $request->VAT[$i]),
          'Narration' => $request->PaxName[$i],
          'Trace' => 110
        );

        $id = DB::table('journal')->insertGetId($loop_ap);

        // tax start from here
        // if tax is > 0 
        if ($request->VAT[$i] > 0) {

          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->VAT[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 111
          );
 
          $id = DB::table('journal')->insertGetId($tax_payable);

          // tax credit from comission
          // $tax_expense = array(
          //     'VHNO' => $invoice_type[0]->InvoiceTypeCode.$request->input('VHNO'), 
          //     'JournalType' => $invoice_type[0]->InvoiceTypeCode, 
          //     'ChartOfAccountID' => 410101, // COMISSION (TAX WILL MINUS FROM COMISSION)
          //     'SupplierID' => $request->SupplierID[$i], 
          //     'PartyID' => $request->PartyID, 
          //     'InvoiceMasterID' => $request->input('VHNO'), 
          //     'Date' => $request->input('Date'),
          //     'Trace' => 112,
          //      'Dr' => $request->VAT[$i], // kamal disable this code due to net amount posted in commsion. net off 100 - tax 
          //   );

          //  $id= DB::table('journal')->insertGetId($tax_expense);

        } else {
          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => abs($request->VAT[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 111
          );
          $id = DB::table('journal')->insertGetId($tax_payable);
        }


        // tax end here 

        // //discount

        // if ($request->Discount[$i] > 0) {

        //   $discount_given = array(
        //     'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        //     'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        //     'ChartOfAccountID' => '410155', // Discount Received -> commsion update chart of account
        //     'SupplierID' => $request->SupplierID[$i],
        //     'PartyID' => $request->PartyID,
        //     'InvoiceMasterID' => $request->input('VHNO'),
        //     'Date' => $request->input('Date'),
        //     'Dr' => $request->Discount[$i],
        //     'Narration' => $request->PaxName[$i],
        //     'Trace' => 203
        //   );

        //   $id = DB::table('journal')->insertGetId($discount_given);
        // }
      }
      // journal entry end here part 1

      // SALE RETURN FOR EACH ROW

      // journal entry start from here when full payment is made part 2
      if ($request->input('InvoiceTypeID') == 5) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Paid[$i],
          'Narration' => $request->PaxName[$i],

          'Trace' => 105
        );


        $id = DB::table('journal')->insertGetId($loop_AR);

        $loop_purchase_cr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Paid[$i] - ($request->Service[$i] + $request->VAT[$i]),
          'Narration' => $request->PaxName[$i],
          'Trace' => 106
        );

        $id = DB::table('journal')->insertGetId($loop_purchase_cr);

        // Services Charges
        if ($request->Service[$i] > 0) {


          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->Service[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 107
          );


          $id = DB::table('journal')->insertGetId($comission);

        

          // AR
        }
        ///////////////////udpatejune
        else {
          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => abs($request->Service[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 108
          );


          $id = DB::table('journal')->insertGetId($comission);
        }
        

        // Services Charges
        if ($request->deduction[$i] > 0) {


          // $deduction_ar = array(
          //   'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          //   'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          //   'ChartOfAccountID' => '110400',  // A/R
          //   'SupplierID' => $request->SupplierID[$i],
          //   'PartyID' => $request->PartyID,
          //   'InvoiceMasterID' => $request->input('VHNO'),
          //   'Date' => $request->input('Date'),
          //   'Dr' => $request->deduction[$i],
          //   'Narration' => $request->PaxName[$i] . ' - Deduction charges',
          //   'Trace' => 1077
          // );


          // DB::table('journal')->insertGetId($deduction_ar);

        
        
          $deduction_income = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->deduction[$i],
            'Narration' => $request->PaxName[$i] . ' - Deduction income',
            'Trace' => 1077
          );


          DB::table('journal')->insertGetId($deduction_income);

        }
        

        // Purchase of Ticket - > PIA
        $loop_purchase_dr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => ($request->Paid[$i]+$request->deduction[$i]) - ($request->Service[$i] + $request->VAT[$i]),
          'Narration' => $request->PaxName[$i],
          'Trace' => 109
        );
        $id = DB::table('journal')->insertGetId($loop_purchase_dr);
  

        // A/P - > PIA
        $loop_ap = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '210100',  // A/P - > PIA
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => ($request->Paid[$i]+$request->deduction[$i]) - ($request->Service[$i] + $request->VAT[$i]),
          'Narration' => $request->PaxName[$i],
          'Trace' => 110
        );

        $id = DB::table('journal')->insertGetId($loop_ap);

        // tax start from here
        // if tax is > 0 
        if ($request->VAT[$i] > 0) {

          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->VAT[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 111
          );
 
          $id = DB::table('journal')->insertGetId($tax_payable);

          // tax credit from comission
          // $tax_expense = array(
          //     'VHNO' => $invoice_type[0]->InvoiceTypeCode.$request->input('VHNO'), 
          //     'JournalType' => $invoice_type[0]->InvoiceTypeCode, 
          //     'ChartOfAccountID' => 410101, // COMISSION (TAX WILL MINUS FROM COMISSION)
          //     'SupplierID' => $request->SupplierID[$i], 
          //     'PartyID' => $request->PartyID, 
          //     'InvoiceMasterID' => $request->input('VHNO'), 
          //     'Date' => $request->input('Date'),
          //     'Trace' => 112,
          //      'Dr' => $request->VAT[$i], // kamal disable this code due to net amount posted in commsion. net off 100 - tax 
          //   );

          //  $id= DB::table('journal')->insertGetId($tax_expense);

        } else {
          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => abs($request->VAT[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 111
          );
          $id = DB::table('journal')->insertGetId($tax_payable);
        }


        // tax end here 

        // //discount

        // if ($request->Discount[$i] > 0) {

        //   $discount_given = array(
        //     'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        //     'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        //     'ChartOfAccountID' => '410155', // Discount Received -> commsion update chart of account
        //     'SupplierID' => $request->SupplierID[$i],
        //     'PartyID' => $request->PartyID,
        //     'InvoiceMasterID' => $request->input('VHNO'),
        //     'Date' => $request->input('Date'),
        //     'Dr' => $request->Discount[$i],
        //     'Narration' => $request->PaxName[$i],
        //     'Trace' => 203
        //   );

        //   $id = DB::table('journal')->insertGetId($discount_given);
        // }
      }
      // journal entry end here part 1

      // END SALE RETURN FOR EACH ROW

    }

    // end foreach

    // queries end here  -->
    DB::commit();

    // return redirect('Invoice')
    //   ->with('error', 'Invoice Saved')
    //   ->with('class', 'success')
    //   ->with('invoiceMasterID', $InvoiceMasterID);


         return response()->json([
            'success' => true,
            'message' => 'Umrah invoice updated successfully',
            'redirect_url' => URL("/Umrah"),
            'invoiceMasterID' => $request->VHNO
        ]);

       
            } catch (\Exception $e) {
                DB::rollBack();

                 return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);

                // return back()->with('error', $e->getMessage())->with('class', 'danger');
            }


  }


  
  public  function UmrahReport()

    {


      $pagetitle='Umrah Sale Report';
      

$item = DB::table('item')->where('ItemCode', 'like', 'U%')->get();


                
    return view ('umrah.umrah_report',compact('pagetitle','item'));
    }


    public  function UmrahReport1 (request $request)

    {





       $pagetitle='Umrah Sale Report';


 
    
  $query = DB::table('supplier')
    ->where('SupplierCatID', 3)
    ->whereIn('SupplierID', function($subQuery) use ($request) {
        $subQuery->select('SupplierID')
                 ->from('v_invoice_detail_umrah')
                 ->whereBetween($request->Type, [$request->StartDate, $request->EndDate]);

        if ($request->ItemID > 0) {
            $subQuery->where('ItemID', $request->ItemID);
        }

        if ($request->UserID > 0) {
            $subQuery->where('UserID', $request->UserID);
        }
    });

$supplier = $query->get();







$item = DB::table('item')->where('ItemCode', 'like', 'U%')->get();


 
                 
    return view ('umrah.umrah_report1',compact('pagetitle','supplier','item'));
    }



  public  function UmrahReport1PDF($supplierid,$startdate,$enddate,$type,$itemid=null)
    {
                
       $pagetitle='PDF Report';


$customOrder = ['Sharja', 'Dubai', 'Abu Dahbi', 'Jebel Ali'];

$invoice_detail = DB::table('v_invoice_detail_umrah')
    ->where('SupplierID', $supplierid)
    ->whereIn('ItemCode', ['UA', 'UB'])
     ->when($itemid, function ($query, $itemid) {
            return $query->where('ItemID', $itemid);
        })
    ->whereBetween($type, [$startdate, $enddate])
    ->orderByRaw('FIELD(PickPoint, ' . implode(',', array_map(function ($item) {
        return "'" . $item . "'";
    }, $customOrder)) . ')')
    ->orderBy('InvoiceMasterID')

    ->get();


 

// $invoice_detail = DB::table('v_invoice_detail_umrah')->where('SupplierID',$supplierid)->whereIn('ItemCode',['UA','UB'])->whereBetween($type,[$startdate,$enddate])->orderby('InvoiceMasterID')->get();


  $supplier = DB::table('supplier')->where('SupplierID',$supplierid)->first();

      $pdf = PDF::loadView ('umrah.umrah_report1pdf',compact('pagetitle','invoice_detail','supplier'));
      $pdf->set_option('isPhpEnabled',true);
        $pdf->setpaper('A4', 'landscape');
      return $pdf->download($supplier->SupplierID.'-'.$supplier->SupplierName.'.pdf');
          // return $pdf->stream();


     }



  public  function UmrahReport2PDF($supplierid,$startdate,$enddate,$type,$itemid=null)
    {
                
       $pagetitle='PDF Report';


$customOrder = ['Sharja', 'Dubai', 'Abu Dahbi', 'Jebel Ali'];

$invoice_detail = DB::table('v_invoice_detail_umrah')
    ->where('SupplierID', $supplierid)
    ->whereIn('ItemCode', ['UA', 'UB'])
    ->when($itemid, function ($query, $itemid) {
            return $query->where('ItemID', $itemid);
        })
    ->whereBetween($type, [$startdate, $enddate])
    ->orderByRaw('FIELD(PickPoint, ' . implode(',', array_map(function ($item) {
        return "'" . $item . "'";
    }, $customOrder)) . ')')
    ->orderBy('InvoiceMasterID')
    ->get();

   $supplier = DB::table('supplier')->where('SupplierID',$supplierid)->first();

      $pdf = PDF::loadView ('umrah.umrah_report2pdf',compact('pagetitle','invoice_detail','supplier'));
      $pdf->set_option('isPhpEnabled',true);
        $pdf->setpaper('A4', 'landscape');
      return $pdf->download($supplier->SupplierID.'-'.$supplier->SupplierName.'.pdf');
          // return $pdf->stream();


     }







public function listPrinters()
{
   $printerName = "doPDF v7";  // Set to your desired printer name
    $url = "https://extbooks.com/Falak-Travel/public/testfile.pdf";  // The URL to your PDF file

    // PowerShell command to open the URL in Edge and print
    // $command = "powershell.exe -Command \"Start-Process 'msedge.exe' -ArgumentList '--kiosk-printing $url'\"";

   $command = "powershell.exe -Command \"Get-Content '$url' | Out-Printer -Name '$printerName'\"";


    // Execute the shell command
    $output = shell_exec($command);

    // Optional: Debug the output if needed
    // return response()->json($output);
 }




 public function UmrahPDFView($id, $download = null)
  {
    // Check user access rights (assuming `check_role` function exists)
    $allow = check_role(session::get('UserID'), 'Invoice', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return response()->json(['error' => 'You access is limited'], 403);
    }

    session::put('menu', 'Invoice');
    $invoice_type = DB::table('invoice_type')->get();
    $items = DB::table('item')->get();
    $supplier = DB::table('supplier')->get();
    $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();
    $company = DB::table('company')->where('CompanyID', 1)->get();
    $invoice_mst = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
    $invoice_det = DB::table('v_inv_detail_umrah')->where('InvoiceMasterID', $id)->get();
    $invoice = DB::table('invoice_master')->select('total', 'paid')->where('InvoiceMasterID', $id)->first();
    $balance = $invoice_mst[0]->Total - $invoice_mst[0]->Paid;
     // Load the HTML view instead of generating PDF directly
    $html = view('umrah.umran_view', compact('balance', 'invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det', 'company'))->render();

    return response()->json(['html' => $html], 200);
  }

  public  function UmrahPDF($id, $download = null)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Invoice');
    $invoice_type = DB::table('invoice_type')->get();

    $items = DB::table('item')->get();
    $supplier = DB::table('supplier')->get();

    $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();

    $company = DB::table('company')->where('CompanyID', 1)->get();
    $invoice_mst = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
    $invoice_det = DB::table('v_inv_detail_umrah')->where('InvoiceMasterID', $id)->get();
    $invoice = DB::table('invoice_master')->select('total', 'paid')->where('InvoiceMasterID', $id)->first();

 
    $balance = $invoice_mst[0]->Total - $invoice_mst[0]->Paid;

 
    // return View ('invoice_pdf',compact('invoice_type','items','supplier','vhno','invoice_mst','invoice_det'));

    $filename = $invoice_mst[0]->InvoiceCode . '-' . $invoice_mst[0]->Date . '-PartyCode-' . $invoice_mst[0]->PartyID;

    $pdf = PDF::loadView('umrah.umrah_pdf', compact('balance', 'invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det', 'company'));
    $pdf->setpaper('A4', 'portiate');

    if ($download == 'download') {
      return $pdf->download($filename . '.pdf');
    } else {
      return $pdf->stream();
    }
  }





}
