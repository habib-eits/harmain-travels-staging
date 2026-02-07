<?php

namespace App\Http\Controllers;

use DB;
use PDF;

use URL;
use File;
use Excel;
use Image;
use Session;
use DateTime;
// for excel export
use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;
// end for excel export
use App\Models\Party;

use App\Mail\SendMail;
use App\Models\Branch;
use Illuminate\Support\Arr;
use App\Models\InvoiceHotel;
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
use Illuminate\Validation\Rule;
use App\Models\InvoiceTransport;
use Yajra\DataTables\DataTables;
use App\Exports\SupplierLedgerExcel;
use Illuminate\Support\Facades\Mail;
use App\Models\UmrahInvoicePassenger;
use Illuminate\Support\Facades\Crypt;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Accounts extends Controller
{

  public function __construct()
  {
    if (session::get('UserID') == 1) {
      echo "null";
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function CheckUserRole1($userid, $tablename, $action)
  {
    // $allow= check_role(session::get('UserID'),'Petty Cash','List');

    $allow = DB::table('user_role')->where('UserID', $userid)
      ->where('Table', $tablename)
      ->where('Action', $action)
      ->get();

    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
  }

 public function Login(Request $request)
    {

    $url = $request->fullUrl();
    $config = json_decode(file_get_contents('https://xtbooks.app/saas.json'), true);
    $apiUrl = $config['api_base_url'];

    $token = "w5vHYMb5qeImA6QRuSLLWD8D1B4qUqGo";

    $response = \Illuminate\Support\Facades\Http::get($apiUrl, [
        'database_name' => env('DB_DATABASE'),
        'url' => $url,
        'client_token' => $token,
    ]);


    // Encrypt the message 'Hello, Universe'.
    // $encrypted = Crypt::encrypt('Hello, Universe');
    // echo $encrypted;
    // echo "<br>";

    // // Decrypt the $encrypted message.
    // $message   = Crypt::decrypt($encrypted);

    // echo $message;
    //         die;


    $company = DB::table('company')->where('CompanyID',1)->get();
    return view('login_1.login',compact('company'));
  }

  public function UserVerify(request $request)
  {
    //

    // dd($request->all());


     $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:2'
        ]);



    $input = $request->only(['email', 'password']);



    $username = $input['email'];
    $password =  $input['password'];

    // $data = DB::table('user')->where('Email', '=', $username)
    //   ->where('Password', '=', $password)
    //   ->where('Active', '=', 'Yes')
    //   ->get();


      

      $data=User::with(['branch:id,name'])->where('Email', '=', $username)
      ->where('Password', '=', $password)
      ->where('Active', '=', 'Yes')
      ->get(); 

 
       
    if (count($data) > 0) {
      
      Session::put('BranchID', $data[0]->branch_id);
      Session::put('BranchName', $data[0]->branch->name ?? 0);
   
      Session::put('FullName', $data[0]->FullName);
      Session::put('UserID', $data[0]->UserID);
      Session::put('Email', $data[0]->Email);
      Session::put('UserType', $data[0]->UserType);
      Session::put('Type', $data[0]->UserType);
    
           return response()->json([
                'success' => true,
                'message' => 'Welcome to Admin Panel'
            ]);


    } else {

      

           return response()->json([
                'success' => false,
                'message' => 'Email or password is invalid'
            ]);
    }

    // for staff login


  




  }


  

  public  function PettyCash()
  {

    // $data = DB::table('data')->get();

    // $id = DB::table('customer')->where('customer_id',$customer_id)->delete();

    session::put('menu', 'PettyCash');
    $pagetitle = 'Petty Cash';

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Petty Cash', 'List');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    return view('pettycash', compact('pagetitle'))->with('error', 'Logout Successfully.')->with('class', 'success');
  }

  public function ajax_pettycash(Request $request)
  {
    session::put('menu', 'PettyCash');
    $pagetitle = 'Petty Cash';
    if ($request->ajax()) {
      $data = DB::table('v_pettycash_master')->orderBy('PettyMstID')->get();
      return Datatables::of($data)
        ->addIndexColumn()

        ->addColumn('action', function ($row) {
          // if you want to use direct link instead of dropdown use this line below
          // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>

          $btn = ' 
          <div class="d-flex align-items-center col-actions">
            <a href="' . URL('/PettyCashEdit/' . $row->PettyMstID) . '"><i class="bx bx-pencil align-middle me-1 text-secondary"></i></a> 
            <a href="' . URL('/PettyCashDelete/' . $row->PettyMstID) . '"><i class="bx bx-trash align-middle me-1 text-secondary"></i></a> 
          </div>';

          //class="edit btn btn-primary btn-sm"
          // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    return view('invoice', 'pagetitle');
  }

  public  function PettyCashCreate()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Petty Cash', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    session::put('menu', 'PettyCash');
    $pagetitle = 'Petty Cash';
    $voucher_type = DB::table('voucher_type')->get();
    $items = DB::table('item')->get();
    $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,3)'), '<>', 000)->get();
    $supplier = DB::table('supplier')->get();
    $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();

    return view('pettycash_create', compact('voucher_type', 'items', 'supplier', 'vhno', 'pagetitle', 'chartofaccount'))->with('error', 'Logout Successfully.')->with('class', 'success');
  }

  public  function PettyCashSave(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Petty Cash', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());
    $invoice_mst = array(
      'PettyVoucher' => $request->input('Voucher'),
      'ChOfAcc' => $request->input('ChartOfAcc'),
      'Date' => $request->input('VHDate'),
      'Narration' => $request->input('Narration_mst'),
      'Credit' => $request->input('TotalDebit'),

    );

    // dd($invoice_mst);

    // $id= DB::table('')->insertGetId($data);

    $id = DB::table('pettycash_master')->insertGetId($invoice_mst);

    for ($i = 0; $i < count($request->ChartOfAcc2); $i++) {
      $invoice_det = array(
        'PettyMstID' => $id,
        'PettyVoucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => $request->ChartOfAcc2[$i],
        'Narration' => $request->Narration[$i],
        'Invoice' => $request->Invoice[$i],
        'RefNo' => $request->RefNo[$i],
        'Debit' => $request->Debit[$i],
        'FromChOfAcc' => $request->input('ChartOfAcc'),

      );

      // dd($invoice_det);  

      $iddd = DB::table('pettycash_detail')->insert($invoice_det);
    }

    return redirect('PettyCash')->with('error', 'Record Saved')->with('class', 'success');
  }

  public  function PettyCashEdit($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Petty Cash', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'PettyCash');
    $pagetitle = 'Petty Cash';
    $chartofaccount = DB::table('chartofaccount')->where('L3', '!=', 'L2')->where('L1', '!=', 'L2')->get();
    $pettycash_master = DB::table('pettycash_master')->where('PettyMstID', $id)->get();
    $pettycash_detail = DB::table('pettycash_detail')->where('PettyMstID', $id)->get();

    return view('pettycash_edit', compact('chartofaccount', 'pettycash_master', 'pettycash_detail', 'pagetitle'));
  }

  public  function PettyCashUpdate(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Petty Cash', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());
    $invoice_mst = array(
      'PettyVoucher' => $request->input('PettyVoucher'),
      'ChOfAcc' => $request->input('ChartOfAcc'),
      'Date' => $request->input('VHDate'),
      'Narration' => $request->input('Narration_mst'),
      'Credit' => $request->input('TotalDebit'),

    );

    // dd($invoice_mst);

    // $id= DB::table('')->insertGetId($data);

    $id = DB::table('pettycash_master')->where('PettyMstID', $request->input('PettyMstID'))->update($invoice_mst);

    $id = DB::table('pettycash_detail')->where('PettyMstID', $request->input('PettyMstID'))->delete();

    for ($i = 0; $i < count($request->ChartOfAcc2); $i++) {
      $invoice_det = array(
        'PettyMstID' => $request->input('PettyMstID'),
        'PettyVoucher' => $request->input('PettyVoucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => $request->ChartOfAcc2[$i],
        'Narration' => $request->Narration[$i],
        'Invoice' => $request->Invoice[$i],
        'RefNo' => $request->RefNo[$i],
        'Debit' => $request->Debit[$i],
        'FromChOfAcc' => $request->input('ChartOfAcc'),

      );

      // dd($invoice_det);  

      $idd = DB::table('pettycash_detail')->where('PettyMstID', $request->input('Voucher'))->insert($invoice_det);
    }

    return redirect('PettyCash')->with('error', 'Record Updated')->with('class', 'success');
  }

  // petty udate end 

  public  function JV()
  {
    session::put('menu', 'Vouchers');
    $pagetitle = 'Vouchers';

    $voucher_type = DB::table('voucher_type')->where('VoucherCode', 'JV')->get();

    $chartofaccount = DB::table('chartofaccount')->get();

    $voucher_code = match ('JV') {
      'BP' => '1',
      'BR' => '2',
      'CP' => '4',
      'CR' => '5',
      'JV' => '7',
    };

    $data = DB::table('voucher_master')
      ->select(DB::raw('LPAD(IFNULL(MAX(SUBSTR(Voucher,7)),0)+1,4,0) as vhno'))
      ->where('VoucherCodeID', $voucher_code)
      ->first();

    $vhno = 'JV' . date('ym') . $data->vhno;

    $supplier = DB::table('supplier')->get();
    $party = Party::getPartyList();

    return view('jv_create', compact('voucher_type', 'chartofaccount', 'supplier', 'vhno', 'pagetitle', 'party', 'vhno'));
  }

  public function PettyCashDelete($id)
  {

    $id = DB::table('pettycash_master')->where('PettyMstID', $id)->delete();
    $id = DB::table('pettycash_detail')->where('PettyMstID', $id)->delete();
    return redirect()->back()->with('error', 'Deleted Successfully.')->with('class', 'success');
  }

  public  function Voucher()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher', 'List');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Vouchers');
    $pagetitle = 'Vouchers';
    $voucher_type = DB::table('voucher_type')->get();
    $chartofaccount = DB::table('chartofaccount')->where('L3', '!=', 'L2')->where('L1', '!=', 'L2')->get();
    $supplier = DB::table('supplier')->get();
    $branch = Branch::getBranchList();
    $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();

    return view('voucher', compact('voucher_type', 'chartofaccount', 'supplier', 'vhno', 'pagetitle','branch'))->with('error', 'Logout Successfully.')->with('class', 'success');
  }

  public function ajax_voucher(Request $request)
  {



    session::put('menu', 'Vouchers');
    $pagetitle = 'Vouchers';
    if ($request->ajax()) {
      
          $query = DB::table('v_voucher');

      // Apply filters if they are present in the request
      // if ($request->has('item_name') && !empty($request->item_name)) {
      //     $query->where('ItemName', 'like', '%' . $request->item_name . '%');
      // }

      if ($request->has('Voucher') && !empty($request->Voucher)) {
        $query->where('Voucher', 'like', '%' . $request->Voucher . '%');
      }
      if ($request->has('VoucherType') && !empty($request->VoucherType)) {
        $query->where('VoucherTypeName', 'like', '%' . $request->VoucherType . '%');
      }

      if ($request->has('startdate') && !empty($request->startdate)) {
        $query->whereDate('Date', '>=', $request->startdate);
      }
      if ($request->has('enddate') && !empty($request->enddate)) {
        $query->whereDate('Date', '<=', $request->enddate);
      }
      if ($request->has('BranchID') && !empty($request->BranchID)) {
        $query->where('BranchID', $request->BranchID);
      }

      $data = $query->orderBy('VoucherMstID')->get();

      return Datatables::of($data)
        ->addIndexColumn()

        ->addColumn('action', function ($row) {
          // if you want to use direct link instead of dropdown use this line below
          // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>

          $btn = ' 
 
                       <div class="d-flex align-items-center col-actions">
                     
 
<a href="' . URL('/VoucherView2/' . $row->VoucherMstID) . '"><i class="font-size-18 mdi mdi-file-pdf
-outline align-middle me-1 text-secondary"></i></a> 


<a href="' . URL('/VoucherView/' . $row->VoucherMstID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a> 

<a href="' . URL('/VoucherEdit/' . $row->VoucherMstID) . '"><i class="font-size-18 mdi mdi-pencil align-middle me-1 text-secondary"></i></a> 

<a href="javascript:void(0)" onclick="delete_voucher(' . $row->VoucherMstID . ')" ><i class="font-size-18 bx bx-trash align-middle me-1 text-secondary"></i></a>

                       </div>';

          //class="edit btn btn-primary btn-sm"
          // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    return view('invoice', 'pagetitle');
  }

  public  function VoucherCreate($vouchertype)
  {

    $payment_method = 'BP';

    $voucher_code = match ($vouchertype) {
      'BP' => '1',
      'BR' => '2',
      'CP' => '4',
      'CR' => '5',
      'JV' => '7',
    };

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    if ($vouchertype == 'BR') {

      $chartofaccount1 = DB::table('chartofaccount')->where('Category', 'BANK')->get();
    } elseif ($vouchertype == 'BP') {
      $chartofaccount1 = DB::table('chartofaccount')->where('Category', 'BANK')->get();
    } elseif ($vouchertype == 'CR') {
      $chartofaccount1 = DB::table('chartofaccount')->where('Category', 'CASH')->get();
    } elseif ($vouchertype == 'CP') {
      $chartofaccount1 = DB::table('chartofaccount')->where('Category', 'CASH')->get();
    }

    $data = DB::table('voucher_master')
      ->select(DB::raw('LPAD(IFNULL(MAX(SUBSTR(Voucher,7)),0)+1,4,0) as vhno'))
      ->where('VoucherCodeID', $voucher_code)
      ->first();

    $vhno = $vouchertype . date('ym') . $data->vhno;

    session::put('menu', 'Vouchers');
    $pagetitle = 'Vouchers';

    
    $voucher_type = DB::table('voucher_type')->where('VoucherCode', $vouchertype)->get();

    $supplier = DB::table('supplier')->get();
    
    
    
    
    
    
    $branch = Branch::getBranchList();
    $party = Party::getPartyList();

 

    // $party = DB::table('party') 
    // ->when(session('UserType')!='Admin', function ($query) {
    //     return $query->where('BranchID', session('BranchID'));
    // })->orderby('PartyName')->get();

    // if(session('UserType')=='Admin'){
    //   $chooseOption = (object)[ 'PartyID' => '', 'PartyType' => 'Choose...', 'PartyName' => ''];
    //   $party->prepend($chooseOption);
    // }






    $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,3)'), '<>', 000)
      ->get();

    return view('voucher_create', compact('voucher_type', 'chartofaccount', 'chartofaccount1', 'supplier', 'vhno', 'pagetitle', 'party', 'vhno','branch'))->with('error', 'Logout Successfully.')->with('class', 'success');
  }

  public function VoucherSave(request $request)
  {


     ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    for ($i = 0; $i < count($request->ChOfAcc); $i++) {

      $check['status'] = true;
      if($request->InvoiceKnockOff[$i] == 'Yes')
      {

        
         $check = $this->knokoff_checking($request->PartyID[$i],$request->Debit[$i]);
      }

    
      if(!$check['status']){
        return response()->json([
          'success' => false,
          // 'message' => 'Knockoff amount is greater than invoice amount for PartyID '. $request->PartyID[$i]
          'message' => 'For PartyID '. $request->PartyID[$i]. ' exceeded  amount '. $check['difference'],
        ]);
      }
      
// end for
      
    }
    

     
      // invoice knockoff 

      // end of scirpt
  
 



    try {
      DB::beginTransaction();
       // your alll  queries here -->




  
    $validator = Validator::make($request->all(), [
        'Voucher' => 'required|string|max:50',
        'Narration_mst' => 'required|string|max:1000',
        'VHDate' => 'required|date',

        'ChOfAcc' => 'required|array|min:1',
        'ChOfAcc.*' => 'required|string|max:100',

        // 'SupplierID' => 'required|array|min:1',
        // 'SupplierID.*' => 'nullable|integer',

        // 'PartyID' => 'required|array|min:1',
        // 'PartyID.*' => 'nullable|integer',

        'Narration' => 'required|array|min:1',
        'Narration.*' => 'required|string|max:1000',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ]);
    }





  






    // dd($request->all());
    $voucher_mst = array(
      'VoucherCodeID' => $request->input('VoucherType'),
      'Voucher' => $request->input('Voucher'),
      'Narration' => $request->input('Narration_mst'),
      'Date' => $request->input('VHDate'),
      'BranchID' => session::get('BranchID'),

    );

    // dd($invoice_mst);

    // $id= DB::table('')->insertGetId($data);

    $id = DB::table('voucher_master')->insertGetId($voucher_mst);


    // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => 0,
      'Date' => date('Y-m-d H:i:s'), 
      'Section' => 'Voucher Created', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' => $request->input('Narration_mst'), 
      'Trace' => 301,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 



    if ((substr($request->Voucher, 0, 2) == 'BP') || ((substr($request->Voucher, 0, 2) == 'CP')))
    {

      // start for loop
      for ($i = 0; $i < count($request->ChOfAcc); $i++) {

         if($request->InvoiceKnockOff[$i] == 'Yes')
      {
          $this->invoice_knokoff_update($request->PartyID[$i],$request->Debit[$i]);
      }

        $voucher_det_dr = array(
          'VoucherMstID' => $id,
          'Voucher' => $request->input('Voucher'),
          'Date' =>  $request->input('VHDate'),
          'ChOfAcc' => $request->ChOfAcc[$i],
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID[$i],
          'Narration' => $request->Narration[$i],
          'InvoiceNo' => $request->Invoice[$i],
          'RefNo' => $request->RefNo[$i],
          'Debit' => $request->Debit[$i],
          'BranchID' => session::get('BranchID'),

        );

        $id1 = DB::table('voucher_detail')->insert($voucher_det_dr);
 
              // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Debit[$i],
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher Created', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' => $request->input('Narration_mst') . 'Invoice# ' . $request->Invoice[$i] . $request->RefNo[$i]. ' amount '. ($request->Debit[$i]) ? $request->Debit[$i] : $request->Debit[$i] , 
      'Trace' => 302,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);
     // log input 

        $voucher_det_cr = array(
          'VoucherMstID' => $id,
          'Voucher' => $request->input('Voucher'),
          'Date' =>  $request->input('VHDate'),
          'ChOfAcc' => $request->ChartOfAccount1,
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID[$i],
          'Narration' => $request->Narration[$i],
          'InvoiceNo' => $request->Invoice[$i],
          'RefNo' => $request->RefNo[$i],
          'Credit' => $request->Debit[$i],
          'BranchID' => session::get('BranchID'),

        );

        $id2 = DB::table('voucher_detail')->insert($voucher_det_cr);
 
              // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i],
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher Created', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' => $request->input('Narration_mst') . 'Invoice# ' . $request->Invoice[$i] . $request->RefNo[$i]. ' amount '. ($request->Debit[$i]) ? $request->Debit[$i] : $request->Debit[$i], 
      'Trace' => 303,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 

  }

      }
    // end for each
    else {

      // start for loop
      for ($i = 0; $i < count($request->ChOfAcc); $i++) {

        if($request->InvoiceKnockOff[$i] == 'Yes')
        {
            $this->invoice_knokoff_update($request->PartyID[$i],$request->Debit[$i]);
        }
        $voucher_det_dr = array(
          'VoucherMstID' => $id,
          'Voucher' => $request->input('Voucher'),
          'Date' =>  $request->input('VHDate'),
          'ChOfAcc' => $request->ChartOfAccount1,
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID[$i],
          'Narration' => $request->Narration[$i],
          'InvoiceNo' => $request->Invoice[$i],
          'RefNo' => $request->RefNo[$i],
          'Debit' => $request->Debit[$i],
          'BranchID' => session::get('BranchID'),

        );

        $id2 = DB::table('voucher_detail')->insert($voucher_det_dr);


                      // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i],
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher creatd', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' =>  $request->Narration[$i] . ' Invoice# ' . $request->Invoice[$i] . $request->RefNo[$i]. ' amount '. ($request->Debit[$i]) ? $request->Debit[$i] : $request->Debit[$i], 
      'Trace' => 304,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 

        $voucher_det_cr = array(
          'VoucherMstID' => $id,
          'Voucher' => $request->input('Voucher'),
          'Date' =>  $request->input('VHDate'),
          'ChOfAcc' => $request->ChOfAcc[$i],
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID[$i],
          'Narration' => $request->Narration[$i],
          'InvoiceNo' => $request->Invoice[$i],
          'RefNo' => $request->RefNo[$i],
          'Credit' => $request->Debit[$i],
          'BranchID' => session::get('BranchID'),

        );

        $id1 = DB::table('voucher_detail')->insert($voucher_det_cr);

                      // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i],
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher created', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' =>  $request->Narration[$i] . ' Invoice# ' . $request->Invoice[$i] . $request->RefNo[$i]. ' amount '. ($request->Debit[$i]) ? $request->Debit[$i] : $request->Debit[$i], 
      'Trace' => 305,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 



      }
      // end for each







    }

    // return redirect('Voucher')->with('error', 'Record Saved')->with('class', 'success');


        // queries end here  -->
        DB::commit();

        // return redirect('Invoice')
        //   ->with('error', 'Invoice Saved')
        //   ->with('class', 'success')
        //   ->with('invoiceMasterID', $InvoiceMasterID);
    
    
        // session()->flash('invoiceMasterID', $request->VHNO);
    
    
             return response()->json([
                'success' => true,
                'message' => 'Saved successfully',
                'redirect_url' => URL("/Voucher"),
                // 'invoiceMasterID' => $request->VHNO
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

  public  function VoucherEdit($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    session::put('menu', 'Vouchers');
    $pagetitle = 'Vouchers';
    $voucher_type = DB::table('voucher_type')->get();
    $chartofaccount = DB::table('chartofaccount')->where('L3', '!=', 'L2')->where('L1', '!=', 'L2')->orderby('ChartOfAccountName')->get();
    
    
    $branch = Branch::getBranchList();
    $party = Party::getPartyList();

    $voucher_master = DB::table('voucher_master')->where('VoucherMstID', $id)->get();
    $voucher_detail = DB::table('voucher_detail')->where('VoucherMstID', $id)->get();

    return view('voucher_edit', compact('voucher_type', 'chartofaccount', 'pagetitle','branch', 'voucher_master', 'voucher_detail', 'party'));
  }

  public  function VoucherUpdate(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());
    $voucher_mst = array(
      'VoucherCodeID' => $request->input('VoucherType'),
      'Voucher' => $request->input('Voucher'),
      'Narration' => $request->input('Narration_mst'),
      'Date' => $request->input('VHDate'),

    );

    // dd($invoice_mst);

    // $id= DB::table('')->insertGetId($data);

    $id = DB::table('voucher_master')->where('VoucherMstID', $request->input('VoucherMstID'))->update($voucher_mst);

    $idd = DB::table('voucher_detail')->where('VoucherMstID', $request->input('VoucherMstID'))->delete();
    $idd = DB::table('journal')->where('VoucherMstID', $request->input('VoucherMstID'))->delete();

      // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => null,
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher Updated', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' => $request->input('Narration_mst'), 
      'Trace' => 202,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );
 
    $log= DB::table('log')->insertGetId($logdata);

    // log input 



    for ($i = 0; $i < count($request->ChOfAcc); $i++) {
      $invoice_det = array(
        'VoucherMstID' => $request->input('VoucherMstID'),
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => $request->ChOfAcc[$i],
        // 'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->PartyID[$i],
        'Narration' => $request->Narration[$i],
        'InvoiceNo' => $request->Invoice[$i],
        'RefNo' => $request->RefNo[$i],
        'Debit' => $request->Debit[$i],
        'Credit' => $request->Credit[$i],
        'BranchID' => session::get('BranchID'),

      );

      // dd($invoice_det);  

          // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i],
      'Date' => date('Y-m-d H:i:s'), 
      'Section' => 'Voucher Updated', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' => $request->input('Narration_mst') . 'Invoice# ' . $request->Invoice[$i] . $request->RefNo[$i]. ' amount '. ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i], 
      'Trace' => 203,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

 
    $log= DB::table('log')->insertGetId($logdata);

    // log input 



      $iddd = DB::table('voucher_detail')->insert($invoice_det);
    }

    return redirect('Voucher')->with('error', 'Record Updated')->with('class', 'success');
  }

  public function VoucherView($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'VoucherReport');
    $pagetitle = 'Voucher Report';

    // dd($request->all());
    // dd($request->VoucherTypeID);
     // dd(  $voucher_type);

    $voucher_master = DB::table('v_voucher_master')
      // ->whereBetween('Date',array($request->StartDate,$request->EndDate))
      ->where('VoucherMstID', $id)
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
      ->get();

 

    return view('voucher_view', compact('pagetitle', 'voucher_master'));
  }
  
  
  
  public function VoucherView2($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'VoucherReport');
    $pagetitle = 'Voucher Report';

    // dd($request->all());
    // dd($request->VoucherTypeID);
     // dd(  $voucher_type);

    $voucher_master = DB::table('v_voucher_master')
      // ->whereBetween('Date',array($request->StartDate,$request->EndDate))
      ->where('VoucherMstID', $id)
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
      ->get();

 

    return view('voucher_receipt_view', compact('pagetitle', 'voucher_master'));
  }

  public  function VoucherDelete($id)
  {

      /////////////////////// USER RIGHT & CONTROL ///////////////////////////////////////////
 
try {
    $allow = check_role(session::get('UserID'), 'Voucher', 'Delete');
    if ($allow[0]->Allow == 'N') {
        return redirect()->back()->with('error', 'Your access is limited')->with('class', 'danger');
    }

    //////////////////////////// END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'VoucherReport');
    $pagetitle = 'Voucher';

    // Begin transaction
    DB::beginTransaction();

    // Fetch the voucher details
    $voucher_master = DB::table('voucher_master')->where('VoucherMstID', $id)->first();
    if (!$voucher_master) {
        DB::rollBack(); // Rollback if the voucher doesn't exist
        return redirect()->back()->with('error', 'Voucher not found')->with('class', 'danger');
    }

    // Log input
    $logdata = array(
        'UserName' => session::get('FullName'),
        'Amount' => null,
        'Date' => date('Y-m-d H:i:s'),
        'Section' => 'Voucher Deleted',
        'VHNO' => $voucher_master->Voucher,
        'Narration' => 'Voucher Deleted from journal too',
        'Trace' => 401,
        'UserID' => session::get('UserID'),
    );

    $log = DB::table('log')->insertGetId($logdata);


    
    
      
    
    DB::table('invoice_master')
    ->where('Voucher', $voucher_master->Voucher)
    ->update(['Voucher' => null]);


    // Perform deletion
    DB::table('voucher_master')->where('VoucherMstID', $id)->delete();
    DB::table('voucher_detail')->where('VoucherMstID', $id)->delete();
    DB::table('journal')->where('VoucherMstID', $id)->delete();

    // Commit transaction
    DB::commit();

    // Return success response
    return redirect('Voucher')->with('error', 'Record Deleted')->with('class', 'success');
} catch (\Exception $e) {
    // Rollback transaction on error
    DB::rollBack();
    // Handle the exception
 
   return redirect('Voucher')->with('error', 'An error occurred: ' . $e->getMessage())->with('class', 'danger');


}

 

  }

  public function GetSpecificInvoice($id)
  {
    // Fetch the invoice data by ID
 

    $invoice = DB::table('ajax_invoice')->where('InvoiceMasterID', $id)->first();
    
    // return response()->json($invoice);

     return response()->json(
          $invoice
     );

     
  }

    public function GetSpecificInvoice1($id)
  {
    // Fetch the invoice data by ID
 

    $invoice = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->first();
    
    // return response()->json($invoice);

     return response()->json(
          $invoice
     );

     
  }


 

  public  function Invoice()
  {

     session::put('menu', 'Invoice');
    $pagetitle = 'Invoice';

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'List');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////      

    session()->forget('LeadID');
    session()->forget('PartyID');

    $branch = Branch::getBranchList();
      
    $voucher_type = DB::table('v_invoice_master')->get();
    // $chartofaccount = DB::table('chartofaccount')->where('Code', 'E')->get();
    $chartofaccount = DB::table('chartofaccount')->where('ChartOfAccountID', '560110')->get();

    return view('invoice', compact('pagetitle', 'chartofaccount','branch'));
  }




  public function ajax_invoice(Request $request)
  {

    

    session::put('menu', 'Invoice');
    $pagetitle = 'Invoice';
    if ($request->ajax()) {
      $query = DB::table('v_invoice_detail')
          ->when(session('UserType') != 'SuperAdmin', function ($query) {
            return $query->where('BranchID', session('BranchID'));
            })
            ->when(session('UserType') != 'Admin', function ($query) {
            if (session('UserType') == 'User' || session('UserType') == 'Saleman' || session('UserType') == 'Agent') {
              return $query->where('UserID', session('UserID'));
            }
            });
            


//->whereNotIn('ItemCode',['UB','UA'])

       // Apply filters if they are present in the request
      // if ($request->has('item_name') && !empty($request->item_name)) {
      //     $query->where('ItemName', 'like', '%' . $request->item_name . '%');
      // }

      if ($request->has('party_name') && !empty($request->party_name)) {
        $query->where('PartyName', 'like', '%' . $request->party_name . '%');
      }
      
       


  if ($request->has('Phone') && !empty($request->Phone)) {
    // Clean the input phone number (remove spaces)
    $clean_phone = str_replace(' ', '', trim($request->Phone));

    // Extract the last 9 digits from the input phone
    $last_9_digits = substr($clean_phone, -9);

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
      
      if ($request->has('BranchID') && !empty($request->BranchID)) {
        $query->where('BranchID', $request->BranchID);
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
 <a href="' . URL('/InvoiceRefund/' . $row->InvoiceMasterID) . '" class="dropdown-item">
        <i class="mdi mdi-backup-restore font-size-18 text-danger me-1"></i> Invoice Refund
    </a>                              <div class="dropdown-divider"></div>

                                 <li><a href="javascript:void(0)" onclick="loadPDF(\'' . URL('/InvoicePDFView/' . $row->InvoiceMasterID) . '\')" class="dropdown-item"><i class="mdi mdi-file-pdf font-size-16  me-1" style="color:#FF5733;"></i> View Invoice</a></li>
                              

 

   <li><a href="' . URL('/InvoiceEdit/' . $row->InvoiceMasterID) . '" class="dropdown-item"><i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit Invoice</a></li>';

  if($row->is_active==1 && session('UserType')=='SuperAdmin') 
   {
   $btn.='
   <li><a href="' . URL('/InvoiceUnlock/' . $row->InvoiceMasterID) . '" class="dropdown-item text-success"><i class="bx bx-lock-open font-size-16 text-success me-1"></i> Invoice Unlock</a></li>';
   }
   if($row->is_active==0 && session('UserType')=='SuperAdmin')
{
  $btn.='
  <li><a href="' . URL('/Invoicelock/' . $row->InvoiceMasterID) . '" class="dropdown-item text-danger"><i class="bx bx-lock font-size-16 text-danger me-1"></i> Invoice Unlock</a></li>';
}

   $btn.='             
                                <li><a href="' . URL('/InvoicePDF/' . $row->InvoiceMasterID) . '/download' . '" target="_blank" class="dropdown-item"><i class="mdi mdi-file-pdf-outline font-size-16  me-1" style="color:#AF0505;"></i> Download Invoice</a></li>
                                
                                <li><a href="' . URL('/InvoicePDF/' . $row->InvoiceMasterID) . '" target="_blank" class="dropdown-item"><i class="mdi mdi-eye-outline font-size-16 text-secondary me-1"></i> View PDF</a></li>
                                
                                <li><a href="javascript:void(0)" onclick="delete_invoice(' . $row->InvoiceMasterID . ')" class="dropdown-item"><i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete Invoice</a></li>
                            </ul>
                        </div>
                    </div>';
          return $btn;
        })
        ->addColumn('InvoiceBalance', function ($row) {
          $InvoiceTotal= $row->InvoiceTotal;
          $InvoiceBalance = $row->InvoiceBalance;

          if($InvoiceBalance==$InvoiceTotal)
          {
            return '<span class="badge rounded-pill bg-danger"> Un-Paid : ' . number_format($InvoiceBalance, 2) . '</span>';
          }

          // $status = ($InvoiceBalance !=0) ? 'Partial Paid' : 'Full Paid';
          if($InvoiceBalance!=0) 
          {
            return '<span class="badge rounded-pill bg-warning">Partial Paid : ' . number_format($InvoiceBalance, 2) . '</span>';
          }
          else
          {
            return '<span class="badge rounded-pill bg-success">Full Paid : ' . number_format($InvoiceBalance, 2) . '</span>';
          }

          // $balance = $row->Paid1 !== null
          //     ? $row->Total - $row->Paid1
          //     : $row->Total - $row->Paid;
  
          // return '<span class="badge rounded-pill bg-primary">Balance: ' . number_format($balance, 2) . '</span>';
      })
        ->rawColumns(['action','InvoiceBalance'])
        ->with('paymentModes', $PaymentMode)
        ->make(true);
    }

    return view('invoice', compact('pagetitle'));
  }

  public function ajax_getAccountsByCategory(Request $request)
  {
    if ($request->ajax()) {
      $category = $request->get('category');
      $accounts = DB::table('chartofaccount')
        ->where('Category', strtoupper($category))
        ->get(); // Retrieve all accounts for the selected category
      return response()->json($accounts);
    }
    return response()->json([], 400); // Return an empty array and a 400 status code if the request is not AJAX
  }

  public function ajax_getVoucherNumber(Request $request)
  {
    $VoucherCodeID = $request->input('voucher_code'); // Get voucher code from request

    // Query to fetch voucher number
    $data = DB::table('voucher_master')
      ->select(DB::raw('LPAD(IFNULL(MAX(SUBSTR(Voucher,7)),0)+1,4,0) as vhno'))
      ->where('VoucherCodeID', $VoucherCodeID)
      ->first();

    $vhno = date('ym') . $data->vhno; // Combine voucher type, year, and vhno

    return response()->json(['vhno' => $vhno]); // Return vhno as JSON response
  }

  public function modelVoucherSave(Request $request)
  {
  //  dd($request->all());

    if($request->InvoiceTypeID == 1){
      $VoucherType = ($request->input('payment_mode') == 'CASH') ? 5 : 2;
      $acc_company = 'Debit';
      $acc_party = 'Credit';

    }
    elseif($request->InvoiceTypeID == 3){
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

      'PartyID' => $request->input('partyID'),
      'Narration' => $request->notes,
      'InvoiceNo' => $request->InvoiceMasterID,
      'BranchID' => session::get('BranchID'),
      // 'RefNo' => '',
      
      
      $acc_company => $request->input('amount_received'),
    );

    // dd( $voucher_det_dr);



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
      'PartyID' =>  $request->input('partyID'),
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



    $invoice = DB::table('v_inv_paid')
      ->where('InvoiceMasterID', $request->InvoiceMasterID)
      ->first();

    $updatePaidValue = DB::table('invoice_master')
      ->where('InvoiceMasterID', $request->InvoiceMasterID)
      ->update([
        'paid' => $invoice->Paid,
        'balance' => $invoice->Balance
      ]);

// log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $invoice->Paid,
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Invoice', 
      'VHNO' => $request->InvoiceMasterID, 
      'Narration' => 'invoice paid amount updated in invoice master table with paid-> '. $invoice->Paid .' and balance =' .  $invoice->Balance, 
      'Trace' => 5,
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

        'PartyID' => $request->input('partyID'),
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
        'PartyID' =>  $request->input('partyID'),
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

    // Return a success response or redirect
    return redirect()->back()->with('error', 'Payment recorded successfully!')->with('class', 'success');
  }

  public function InvoicePDFView($id, $download = null)
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
    $invoice_det = DB::table('v_invoice_detail2')->where('InvoiceMasterID', $id)->get();
    $invoice = DB::table('invoice_master')->select('total', 'paid')->where('InvoiceMasterID', $id)->first();
    $balance = $invoice->total - $invoice->paid;


    $voucher_detail = DB::table('v_voucher_detail')->where('InvoiceNo',$id)->get();

    // Load the HTML view instead of generating PDF directly
    $html = view('invoice_pdf', compact('balance', 'invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det', 'company','voucher_detail'))->render();

    return response()->json(['html' => $html], 200);
  }

  public  function InvoiceCreate()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Invoice');
    $invoice_type = DB::table('invoice_type')->whereIn('InvoiceTypeID',[1,2])->get();

    $items = DB::table('item')->where('ItemType','I')->get();
    $supplier = Party::getSupplierList();
    $party = Party::getPartyList('C');
    
  

    $saleman = User::getUserList();
     
    

    $vhno = DB::table('invoice_master')->select(DB::raw('IFNULL(max(InvoiceMasterID)+1,1) as VHNO'))->get();



    $rec_vouchers = DB::table('voucher_detail')->where('InvoiceNo',$vhno[0]->VHNO)->where('ChOfAcc',110400)->get();

 
     return view('invoice_create', compact('invoice_type', 'items', 'supplier', 'vhno', 'saleman','party','rec_vouchers'))->with('error', 'Logout Successfully.')->with('class', 'success');
  }

  public  function Ajax_Balance(request $request)
  {

    $data = array('SupplierID' => $request->input('SupplierID'));

    $supplier = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
      ->where('SupplierID', $request->SupplierID)
      ->where('ChartOfAccountID', 210100)
      ->get();

    return view('ajax_balance', compact('supplier'));
  }

  public  function Ajax_Balance_party(request $request)
  {

    $data = array('PartyID' => $request->input('PartyID'));

    $party = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
      ->where('PartyID', $request->PartyID)
      ->where('ChartOfAccountID', 110400)
      ->get();

    return $party[0]->Balance;
  }

  public  function Ajax_Ticket(request $request)
  {

    $data = array('RefNo' => $request->input('RefNo'));

    $ticket = DB::table('invoice_detail')

      ->where('RefNo', $request->RefNo)
      ->get();

    return view('ajax_ticket', compact('ticket'));
  }

  public  function InvoiceSave(request $request)
  {



    // Validate the form input
      $validator = Validator::make($request->all(), [

      'VHNO' => 'required',
      'PartyID' => 'required',
      'SalemanID' => 'required',
      'ItemID0.*' => 'required',
      'SupplierID.*' => 'required',
      // 'PaxName.*' => 'required',  // Validate each PaxName
      
      'Fare.*' => 'required', // Validate each Passport
      'ItemTotal.*' => 'required', // Validate each Passport
      'Date' => 'required', // Validate each Passport
      
      ], [
      'VHNO.required' => 'The voucher number is required.',
      'PartyID.required' => 'Please select a party.',
      'SalemanID.required' => 'Please assign a salesman.',
      'ItemID0.*.required' => 'Each item must be selected.',
      'SupplierID.*.required' => 'Each supplier must be selected.',
      'Fare.*.required' => 'Cost price is required for each item.',
      'ItemTotal.*.required' => 'Total amount is required for each item.',
      'Date.required' => 'The date is required.',
      ]);


      if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'message' => $validator->errors()->first()
          ]);
      }
                
     // dd($request->all());
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

        try {
                DB::beginTransaction();
                 // your alll  queries here -->
    
  

    if ($request->input('PaymentMode') == 'Cash') {
      $PaymentMode = '110101'; //Cash in hand
    } elseif ($request->input('PaymentMode') == 'Credit Card') {

      $PaymentMode = '110250'; //Credit Card ACCOUNT.

    } elseif ($request->input('PaymentMode') == 'ENBD Bank') {

      $PaymentMode = '110201'; //ENBD Bank

    } else {
      $PaymentMode = '110202'; //ADCB Bank
    }

    $invoice_type = DB::table('invoice_type')->where('InvoiceTypeID', $request->input('InvoiceTypeID'))->get();

    if ($request->PartyID == 1) {

      $data = array(
        'PartyName' => $request->WalkinCustomerName,
        'Phone' => $request->WalkinCustomerMobile,
        'BranchID' => session::get('BranchID'),
      );

      $party_id = DB::table('party')->insertGetId($data);

      $request->PartyID = $party_id;
    }

    $invoice_mst = array(
      // 'InvoiceMasterID' => $request->input('VHNO'),
      'InvoiceTypeID' => $request->input('InvoiceTypeID'),
      'BranchID' => session::get('BranchID'),
      'LeadID' => session::get('LeadID'),
      'Date' => $request->input('Date'),
      'PartyID' => $request->PartyID,
      'DueDate' => $request->input('DueDate'),
      'PaymentMode' => $request->input('PaymentMode'),
      'Total' => $request->input('Total'),
      'Paid' => $request->input('amountPaid'),
      'Balance' => $request->input('amountDue'),
      'UserID' => $request->input('SalemanID'),
      'Note' => $request->input('remarks'),

    );

    // $id= DB::table('')->insertGetId($data);

    $id = DB::table('invoice_master')->insertGetId($invoice_mst);
    $InvoiceMasterID = $id; // assigning for session 

    $request->merge(['VHNO' => $id]);


      // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'BranchID' => session::get('BranchID'),
      'Amount' => $request->input('Total'),
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Invoice Create', 
      'VHNO' => $InvoiceMasterID, 
      'Narration' => 'Invoice Created', 
      'Trace' => 101,
      'UserID' => session::get('UserID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 

    //  start for item array from invoice
    for ($i = 0; $i < count($request->ItemID0); $i++) {
      $invoice_det = array(
        'InvoiceMasterID' => $request->input('VHNO'),
        'ItemID' => $request->ItemID0[$i],
        'SupplierID' => $request->SupplierID[$i],
        'BranchID' => session::get('BranchID'),
        'VisaType' => $request->VisaType[$i],
        'PaxName' => $request->PaxName[$i],
        'PNR' => $request->PNR[$i],
        'Sector' => $request->Sector[$i],
        'Fare' => $request->Fare[$i],
        'RefNo' => $request->RefNo[$i],
        'Taxable' => $request->TaxAmount[$i],
        'Service' => $request->Service[$i],
         
        'Discount' => $request->Discount[$i],
        'Total' => $request->ItemTotal[$i],
        
      );
      
        $idd = DB::table('invoice_detail')->insertGetId($invoice_det);
 
      // journal entry start from here when full payment is made part 1
      if ($request->input('InvoiceTypeID') == 1) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->ItemTotal[$i],
          'Narration' => $request->PaxName[$i],

          'Trace' => 105
        );
        $id = DB::table('journal')->insertGetId($loop_AR);

        $loop_purchase_cr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Fare[$i],
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
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
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
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
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
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 109
        );

        $id = DB::table('journal')->insertGetId($loop_purchase_dr);

        // A/P - > PIA
        $loop_ap = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '210100',  // A/P - > PIA
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 110
        );

        $id = DB::table('journal')->insertGetId($loop_ap);

        // tax start from here
        // if tax is > 0 
        if ($request->TaxAmount[$i] > 0) {

          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->TaxAmount[$i],
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
          //      'Dr' => $request->TaxAmount[$i], // kamal disable this code due to net amount posted in commsion. net off 100 - tax 
          //   );

          //  $id= DB::table('journal')->insertGetId($tax_expense);

        } else {
          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => abs($request->TaxAmount[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 111
          );
          $id = DB::table('journal')->insertGetId($tax_payable);
        }

        // tax end here 

        //discount

        if ($request->Discount[$i] > 0) {

          $discount_given = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410155', // Discount Received -> commsion update chart of account
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->Discount[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 203
          );

          $id = DB::table('journal')->insertGetId($discount_given);
        }
      }
      // journal entry end here part 1

      // SALE RETURN FOR EACH ROW

      // journal entry start from here when full payment is made part 2
      if ($request->input('InvoiceTypeID') == 2) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->input('PartyID'),
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->ItemTotal[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 312
        );
        $id = DB::table('journal')->insertGetId($loop_AR);

        // Services Charges
        if ($request->Service[$i] > 0) {

          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->Service[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 313
          );

          $id = DB::table('journal')->insertGetId($comission);
        } 
        
        // else {
        //   $comission = array(
        //     'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        //     'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        //     'ChartOfAccountID' => '410101', // COMISSION 
        //     'BranchID' => session::get('BranchID'),
        //     // 'SupplierID' => $request->SupplierID[$i],
        //     'PartyID' => $request->SupplierID[$i],
        //     'InvoiceMasterID' => $request->input('VHNO'),
        //     'Date' => $request->input('Date'),
        //     'Cr' => abs($request->Service[$i]),
        //     'Narration' => $request->PaxName[$i],
        //     'Trace' => 2022
        //   );

        //   // $id = DB::table('journal')->insertGetId($comission);
        // }

        // Services Charges
        if ($request->Discount[$i] > 0) {

          $discount_rec = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // Discount Received 
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->Discount[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 314
          );

          $id = DB::table('journal')->insertGetId($discount_rec);
        } 
        
        // else {
        //   $discount_rec = array(
        //     'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        //     'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        //     'ChartOfAccountID' => '410152', // Discount Received 
        //     'BranchID' => session::get('BranchID'),
        //     // 'SupplierID' => $request->SupplierID[$i],
        //     'PartyID' => $request->SupplierID[$i],
        //     'InvoiceMasterID' => $request->input('VHNO'),
        //     'Date' => $request->input('Date'),
        //     'Cr' => abs($request->Discount[$i]),
        //     'Narration' => $request->PaxName[$i],
        //     'Trace' => 314
        //   );

        //   // $id = DB::table('journal')->insertGetId($discount_rec);
        // }

        // Purchase of Ticket - > PIA - DEBIT
        $loop_purchase_dr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Narration' => $request->PaxName[$i],
          'Dr' => $request->Fare[$i],
          'Trace' => 315
        );

        $id = DB::table('journal')->insertGetId($loop_purchase_dr);

        // Purchase of Ticket - > PIA - CREDIT
        $loop_purchase_cr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 316
        );
        $id = DB::table('journal')->insertGetId($loop_purchase_cr);

        // A/P - > PIA
        $loop_ap = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '210100',  // A/P - > PIA
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 317
        );

        $id = DB::table('journal')->insertGetId($loop_ap);
      }
      // journal entry end here part 1

      // END SALE RETURN FOR EACH ROW

    }

    // end foreach

 

    DB::commit();

    // return redirect('Invoice')
    //   ->with('error', 'Invoice Saved')
    //   ->with('class', 'success')
    //   ->with('invoiceMasterID', $InvoiceMasterID);


    session()->flash('invoiceMasterID', $request->VHNO);


         return response()->json([
            'success' => true,
            'message' => 'Umrah invoice saved successfully',
            'redirect_url' => URL("/Invoice"),
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





  

  public  function InvoiceRefund($id = null)
  {
 
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Invoice');

        try {
  
                 // your alll  queries here -->
    
             

    $invoice_type = DB::table('invoice_type')->where('InvoiceTypeID',2)->get();

    $items = DB::table('item')->get();
    $supplier = Party::getSupplierList();
    $party = Party::getPartyList('C');
    
    $saleman = User::getUserList();
    // $saleman = DB::table('user')->where('UserType','Saleman')->where('Active','Yes')->get();
    $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();

    $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
    $invoice_det = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();


// queries end here  -->
  

    return view('invoice_refund', compact('invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det', 'party', 'saleman','vhno'))->with('error', 'Logout Successfully.')->with('class', 'success');



                
            } catch (\Exception $e) {
                
                return back()->with('error', $e->getMessage())->with('class', 'danger');
            }





  }




    public  function InvoiceEdit($id = null)
  {
 
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Invoice');


    $invoice_check = DB::table('invoice_master')->where('InvoiceMasterID',$id)->where('is_active',1)->first();

 
    if($invoice_check != null){
      return redirect('Invoice')->with('error', 'Invoice is locked. Pleease contact the admin')->with('class', 'danger');
    }



        try {
  
                 // your alll  queries here -->
    
             

    $invoice_type = DB::table('invoice_type')->whereIn('InvoiceTypeID',[1,2])->get();

    $items = DB::table('item')->get();
    $supplier = Party::getSupplierList();
     
    $party = Party::getPartyList('C');
    $saleman = User::getUserList();
    // $saleman = DB::table('user')->where('UserType','Saleman')->where('Active','Yes')->get();
    $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();

    $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
    $invoice_det = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();

 

// queries end here  -->
  

    return view('invoice_edit', compact('invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det', 'party', 'saleman'))->with('error', 'Logout Successfully.')->with('class', 'success');



                
            } catch (\Exception $e) {
                
                return back()->with('error', $e->getMessage())->with('class', 'danger');
            }





  }

  public  function InvoiceUpdate(request $request)
  {

 
     // Validate the form input
     $validator = Validator::make($request->all(), [
                
      'VHNO' => 'required',
      'PartyID' => 'required',
      'SalemanID' => 'required',
      'ItemID0.*' => 'required',
      'SupplierID.*' => 'required',
      // 'PaxName.*' => 'required',  // Validate each PaxName
      
      'Fare.*' => 'required', // Validate each Passport
      'ItemTotal.*' => 'required', // Validate each Passport
      'Date' => 'required', // Validate each Passport
      
      ], [
      'VHNO.required' => 'The voucher number is required.',
      'PartyID.required' => 'Please select a party.',
      'SalemanID.required' => 'Please assign a salesman.',
      'ItemID0.*.required' => 'Each item must be selected.',
      'SupplierID.*.required' => 'Each supplier must be selected.',
      'Fare.*.required' => 'Cost price is required for each item.',
      'ItemTotal.*.required' => 'Total amount is required for each item.',
      'Date.required' => 'The date is required.',
      ]);


      if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'message' => $validator->errors()->first()
          ]);
      }

 
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////



        try {
                DB::beginTransaction();
                 // your alll  queries here -->
    
             


    if ($request->input('PaymentMode') == 'Cash') {
      $PaymentMode = '110101'; //Cash in hand
    } elseif ($request->input('PaymentMode') == 'Credit Card') {

      $PaymentMode = '110250'; //Credit Card ACCOUNT.

    } elseif ($request->input('PaymentMode') == 'ENBD Bank') {

      $PaymentMode = '110201'; //ENBD Bank

    } else {
      $PaymentMode = '110202'; //ADCB Bank
    }

    // dd($request->all());
    $invoice_mst = array(
      'InvoiceTypeID' => $request->input('InvoiceTypeID'),
      'Date' => $request->input('Date'),
      'PartyID' => $request->input('PartyID'),
      'DueDate' => $request->input('DueDate'),
      // 'PaymentMode' => $request->input('PaymentMode'),
      'Total' => $request->input('Total'),
      'Paid' => $request->input('amountPaid'),
      'Balance' => $request->input('amountDue') ,
      'UserID' => $request->input('SalemanID'),
      'Note' => $request->input('Note'),
      'Voucher' => $request->input('Voucher'),
      'BranchID' => session::get('BranchID'),

    );

    $id11 = DB::table('invoice_master')->where('InvoiceMasterID', $request->VHNO)->update($invoice_mst);

    $invoice_type = DB::table('invoice_type')->where('InvoiceTypeID', $request->input('InvoiceTypeID'))->get();

    $id1 = DB::table('invoice_detail')->where('InvoiceMasterID', $request->VHNO)->delete();

 $id2 = DB::table('journal')->where('InvoiceMasterID', $request->VHNO)
     ->whereNull('VoucherMstID')
    ->delete();

          // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $request->input('Total'),
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Invoice updated', 
      'VHNO' => $request->VHNO, 
      'Narration' => 'Invoice updated, invoice detail item deleted as programming and journal enteries deleted except the payment rec from voucher against this invoice', 
      'Trace' => 101,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 

    


  

    for ($i = 0; $i < count($request->ItemID0); $i++) {
      $invoice_det = array(
        'InvoiceMasterID' => $request->input('VHNO'),
        'ItemID' => $request->ItemID0[$i],
        'SupplierID' => $request->SupplierID[$i],
        'VisaType' => $request->VisaType[$i],
        'PaxName' => $request->PaxName[$i],
        'PNR' => $request->PNR[$i],
        'Sector' => $request->Sector[$i],
        'Fare' => $request->Fare[$i],
        'RefNo' => $request->RefNo[$i],
        'Taxable' => $request->TaxAmount[$i],
        'Service' => $request->Service[$i],
        'OPVAT' => $request->OPVAT[$i],
        'IPVAT' => $request->IPVAT[$i],
        'Discount' => $request->Discount[$i],
        'Total' => $request->ItemTotal[$i],
        'BranchID' => session::get('BranchID'),

      );

      $id = DB::table('invoice_detail')->insertGetId($invoice_det);

      // journal entry start from here when full payment is made part 1
      if ($request->input('InvoiceTypeID') == 1) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->input('PartyID'),
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->ItemTotal[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 305
        );
        $id305 = DB::table('journal')->insertGetId($loop_AR);

        $loop_purchase_cr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 306
        );
        $id306 = DB::table('journal')->insertGetId($loop_purchase_cr);

        // Services Charges
        if ($request->Service[$i] > 0) {

          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->Service[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 307
          );

          $id3077 = DB::table('journal')->insertGetId($comission);
        } else {
          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => abs($request->Service[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 307
          );

          $id307 = DB::table('journal')->insertGetId($comission);
        }

        // Purchase of Ticket - > PIA
        $loop_purchase_dr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 308
        );

        $id308 = DB::table('journal')->insertGetId($loop_purchase_dr);

        // A/P - > PIA
        $loop_ap = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '210100',  // A/P - > PIA
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 309
        );

        $id309 = DB::table('journal')->insertGetId($loop_ap);

        // tax start from here
        // if tax is > 0 
        if ($request->TaxAmount[$i] > 0) {

          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->TaxAmount[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 310
          );
          $id310 = DB::table('journal')->insertGetId($tax_payable);
        } else {

          // tax Debit
          $tax_payable = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => 210300, // TAX PAYABLES
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => abs($request->TaxAmount[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 310
          );
          $id310 = DB::table('journal')->insertGetId($tax_payable);

          // tax credit from comission
          // $tax_expense = array(
          //     'VHNO' => $invoice_type[0]->InvoiceTypeCode.$request->input('VHNO'), 
          //     'JournalType' => $invoice_type[0]->InvoiceTypeCode, 
          //     'ChartOfAccountID' => 410101, // COMISSION (TAX WILL MINUS FROM COMISSION)
          //     'SupplierID' => $request->SupplierID[$i], 
          //     'PartyID' => $request->input('PartyID'), 
          //     'InvoiceMasterID' => $request->input('VHNO'), 
          //     'Date' => $request->input('Date'),
          //     'Dr' => $request->TaxAmount[$i],  
          //     'Trace' => 311
          //   );

          //  $id= DB::table('journal')->insertGetId($tax_expense);

        }

        // tax end here 

        //discount

        if ($request->Discount[$i] > 0) {

          $discount_given = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410155', // Discount Received -> commsion update chart of account
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->Discount[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 203
          );

          $id = DB::table('journal')->insertGetId($discount_given);
        }
      }
      // journal entry end here part 1

      // SALE RETURN FOR EACH ROW

      // journal entry start from here when full payment is made part 2
      if ($request->input('InvoiceTypeID') == 2) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->input('PartyID'),
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->ItemTotal[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 312
        );
        $id = DB::table('journal')->insertGetId($loop_AR);

        // Services Charges
        if ($request->Service[$i] > 0) {

          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->Service[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 313
          );

          $id = DB::table('journal')->insertGetId($comission);
        } 
        
        // else {
        //   $comission = array(
        //     'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        //     'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        //     'ChartOfAccountID' => '410101', // COMISSION 
        //     'BranchID' => session::get('BranchID'),
        //     // 'SupplierID' => $request->SupplierID[$i],
        //     'PartyID' => $request->SupplierID[$i],
        //     'InvoiceMasterID' => $request->input('VHNO'),
        //     'Date' => $request->input('Date'),
        //     'Cr' => abs($request->Service[$i]),
        //     'Narration' => $request->PaxName[$i],
        //     'Trace' => 2022
        //   );

        //   // $id = DB::table('journal')->insertGetId($comission);
        // }

        // Services Charges
        if ($request->Discount[$i] > 0) {

          $discount_rec = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // Discount Received 
            'BranchID' => session::get('BranchID'),
            // 'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->SupplierID[$i],
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Dr' => $request->Discount[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 314
          );

          $id = DB::table('journal')->insertGetId($discount_rec);
        } 
        
        // else {
        //   $discount_rec = array(
        //     'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        //     'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        //     'ChartOfAccountID' => '410152', // Discount Received 
        //     'BranchID' => session::get('BranchID'),
        //     // 'SupplierID' => $request->SupplierID[$i],
        //     'PartyID' => $request->SupplierID[$i],
        //     'InvoiceMasterID' => $request->input('VHNO'),
        //     'Date' => $request->input('Date'),
        //     'Cr' => abs($request->Discount[$i]),
        //     'Narration' => $request->PaxName[$i],
        //     'Trace' => 314
        //   );

        //   // $id = DB::table('journal')->insertGetId($discount_rec);
        // }

        // Purchase of Ticket - > PIA - DEBIT
        $loop_purchase_dr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Narration' => $request->PaxName[$i],
          'Dr' => $request->Fare[$i],
          'Trace' => 315
        );

        $id = DB::table('journal')->insertGetId($loop_purchase_dr);

        // Purchase of Ticket - > PIA - CREDIT
        $loop_purchase_cr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 316
        );
        $id = DB::table('journal')->insertGetId($loop_purchase_cr);

        // A/P - > PIA
        $loop_ap = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '210100',  // A/P - > PIA
          'BranchID' => session::get('BranchID'),
          // 'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->SupplierID[$i],
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 317
        );

        $id = DB::table('journal')->insertGetId($loop_ap);
      }
      // journal entry end here part 1

      // END SALE RETURN FOR EACH ROW

    }
    // end for each
    $InvoiceMasterID = $request->input('VHNO');

    $invoice = DB::table('invoice_master')
      ->where('invoiceMasterID', $InvoiceMasterID)
      ->first();

// queries end here  -->
    DB::commit();
    session()->flash('invoiceMasterID', $request->VHNO);

    if ($invoice->Paid == $invoice->Total) {


 

        return response()->json([
          'success' => true,
          'message' => 'invoice updated successfully',
          'redirect_url' => URL("/Invoice"),
          // 'invoiceMasterID' => $request->VHNO
         ]);
   



    } else {
      
      


        return response()->json([
          'success' => true,
          'message' => 'invoice updated successfully',
          'redirect_url' => URL("/Invoice"),
          'invoiceMasterID' => $request->VHNO
         ]);

         


    }










    
          } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);

            // return back()->with('error', $e->getMessage())->with('class', 'danger');
        }




  }

  public  function InvoiceUnlock($id)
  {

   

    

    $data = DB::table('invoice_master')->where('InvoiceMasterID', $id)->where('BranchID', session::get('BranchID'))->first();

    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $data->Total,
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Invoice Deleted', 
      'VHNO' => $id, 
      'Narration' =>  'Invoice Unlocked total amount-> '. $data->Total. ' customer id '. $data->PartyID, 
      'Trace' => 401,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input

    DB::table('invoice_master')->where('InvoiceMasterID', $id)->where('BranchID', session::get('BranchID'))->update(['is_active' => 0]);

                          // log input



    return redirect()->back()->with('error', 'Invoice Unlocked successfully')->with('class', 'success');
  }
  
  
  
  public  function Invoicelock($id)
  {

  

    

    $data = DB::table('invoice_master')->where('InvoiceMasterID', $id)->where('BranchID', session::get('BranchID'))->first();

    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $data->Total,
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Invoice Deleted', 
      'VHNO' => $id, 
      'Narration' =>  'Invoice locked total amount-> '. $data->Total. ' customer id '. $data->PartyID, 
      'Trace' => 401,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input

    DB::table('invoice_master')->where('InvoiceMasterID', $id)->where('BranchID', session::get('BranchID'))->update(['is_active' => 1]);

                          // log input



    return redirect()->back()->with('error', 'Invoice Unlocked successfully')->with('class', 'success');
  }
  
  
  public  function InvoiceDelete($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'Delete');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    DB::table('journal')->where('InvoiceMasterID', $id)->where('BranchID',session::get('BranchID'))->delete();

    DB::table('invoice_detail')->where('InvoiceMasterID', $id)->where('BranchID', session::get('BranchID'))->delete();

    $data = DB::table('invoice_master')->where('InvoiceMasterID', $id)->where('BranchID', session::get('BranchID'))->first();

    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $data->Total,
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Invoice Deleted', 
      'VHNO' => $id, 
      'Narration' =>  'Invoice Deleted total amount-> '. $data->Total. ' customer id '. $data->PartyID, 
      'Trace' => 401,
      'UserID' => session::get('UserID'),
      'BranchID' => session::get('BranchID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input

    DB::table('invoice_master')->where('InvoiceMasterID', $id)->where('BranchID', session::get('BranchID'))->delete();

                          // log input



    return redirect()->back()->with('error', 'Deleted successfully')->with('class', 'success');
  }

  public  function InvoiceView($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Invoice');
    $invoice_type = DB::table('invoice_type')->get();

    $items = DB::table('item')->get();
    $supplier = DB::table('supplier')->get();

    $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();

    $invoice_mst = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
    $invoice_det = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();

    return View('invoice_view', compact('invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det'));

    // $filename = $invoice_mst[0]->InvoiceCode.'-'.$invoice_mst[0]->Date.'-PartyCode-'.$invoice_mst[0]->PartyID;

    // $pdf = PDF::loadView ('invoice_pdf',compact('invoice_type','items','supplier','vhno','invoice_mst','invoice_det'));
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->download($filename.'.pdf');
    // return $pdf->stream();

  }

  public  function InvoicePDF($id, $download = null)
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

          $voucher_detail = DB::table('v_voucher_detail')->where('InvoiceNo',$id)->get();


    $company = DB::table('company')->where('CompanyID', 1)->get();
    $invoice_mst = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
    $invoice_det = DB::table('v_invoice_detail2')->where('InvoiceMasterID', $id)->get();
    $invoice = DB::table('invoice_master')->select('total', 'paid')->where('InvoiceMasterID', $id)->first();
    $balance = $invoice->total - $invoice->paid;

    $voucher_detail = DB::table('v_voucher_detail')->where('InvoiceNo', $id)->get();

    // return View ('invoice_pdf',compact('invoice_type','items','supplier','vhno','invoice_mst','invoice_det'));

    $filename = $invoice_mst[0]->InvoiceCode . '-' . $invoice_mst[0]->Date . '-PartyCode-' . $invoice_mst[0]->PartyID;

    $pdf = PDF::loadView('invoice_pdf', compact('balance', 'invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det', 'company','voucher_detail'));
    $pdf->setpaper('A4', 'portiate');

    if ($download == 'download') {
      return $pdf->download($filename . '.pdf');
    } else {
      return $pdf->stream();
    }
  }

  public  function Ajax_VHNO(request $request)
  {

    $d = array(

      'VocherTypeID' => $request->VocherTypeID,
      'VocherCode' => $request->VocherCode,
      'VHDate' => $request->VHDate
    );

    $data = DB::table('voucher_master')
      ->select(DB::raw('LPAD(IFNULL(MAX(SUBSTR(Voucher,7)),0)+1,4,0) as vhno'))
      ->where('VoucherCodeID', $request->VocherTypeID)
      ->get();

    // $data = DB::table('voucher_master')
    //           ->select( DB::raw('LPAD(IFNULL(MAX(SUBSTR(Voucher,7)),0)+1,4,0) as vhno'))
    //           ->where ('VoucherCodeID',$request->VocherTypeID)->where(DB::raw('DATE_FORMAT(Date,"%Y%m")'),$request->VHDate)
    //            ->get();

    return view('ajax_vhno', compact('data', 'd'));
  }

  public  function Ajax_PVHNO(request $request)
  {

    $d = array(

      'VocherCode' => 'PC',
      'VHDate' => $request->VHDate
    );

    $data = DB::table('pettycash_master')
      ->select(DB::raw('LPAD(IFNULL(MAX(SUBSTR(PettyVoucher,7)),0)+1,4,0) as vhno'))
      // ->where(DB::raw('DATE_FORMAT(Date,"%Y%m")'),$request->VHDate)
      ->get();

    return view('ajax_pvhno', compact('data', 'd'));
  }


  public function paymentSummary(){
    session::put('menu', 'PaymentSummary');
    $pagetitle = 'Payment Summary';
    $invoice_type = DB::table('invoice_type')->get();
    $item = DB::table('item')->get();
    $saleman = DB::table('saleman')->get();
    $supplier = Party::getPartyList();

    return view('reports.payment_summary', compact('pagetitle', 'invoice_type', 'supplier', 'item', 'saleman'));
    
  }

  public function paymentSummary1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Ticket Register', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Payment Summary';
    $where = array();

    if (($request->InvoiceTypeID == 1) || ($request->InvoiceTypeID == 2)) {

      $where = array('InvoiceTypeID' => $request->InvoiceTypeID);
    }

    if ($request->SupplierID > 0) {

      $where = Arr::add($where, 'SupplierID', $request->SupplierID);
    }

    if ($request->ItemID > 0) {

      $where = Arr::add($where, 'ItemID', $request->ItemID);
    }

    if ($request->UserID > 0) {

      $where = Arr::add($where, 'UserID', $request->UserID);
    }

    $cash_payments = DB::table('invoice_master')
      ->where($where)
      ->whereBetween('Date', [$request->StartDate, $request->EndDate])
      ->where('Voucher', 'LIKE', 'CR%')
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();



    $bank_payments = DB::table('invoice_master')
      ->where($where)
      ->whereBetween('Date', [$request->StartDate, $request->EndDate])
      ->where('Voucher', 'LIKE', 'BR%')
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();

  

    // $pdf = PDF::loadView ('airline_summary1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('reports.payment_summary1', compact('cash_payments','bank_payments' ,'pagetitle'));
  }

  public function Dashboard($branch_id = null)
  {

  
    session::put('menu', 'Dashboard');


    $start_date = date('Y-01-01');
    $end_date = date('Y-m-t');
    $year = date('Y');
    


    

    $pagetitle = 'Dashboard';

    // Fetch the total amount paid from the invoice_master table for the current date
    $invoice_master = DB::table('invoice_master')
      ->select(DB::raw('ifnull(sum(IFNULL(Paid,0)),0) as Paid')) // Calculate the sum of the Paid column, defaulting to 0 if null
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
      // If the user is not an Admin, filter by the user's BranchID
      return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
      // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
      return $query->where('BranchID', $branch_id);
      })
      ->where('Date', date('Y-m-d')) // Filter records for the current date
      ->get();


      

    $v_cashflow = DB::table('v_cashflow')
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
    })
    ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
      // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
      return $query->where('BranchID', $branch_id);
      })
    ->whereIn('Year', [date('Y'), date('Y') - 1])
    ->orderBy('Year')
    ->orderBy('mMonthName')
    ->get();
    $data = array();

    foreach ($v_cashflow as $key => $value) {

      $cashflow_chart[] = $value->Balance;
    }


//Bank Charges
    $bankCharges = DB::table('v_journal')
      ->where('ChartOfAccountID',560110)
      ->whereNotNull('InvoiceMasterID')
      ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m-%d")'), date('Y-m-d'))
      ->sum('Dr');




    // todays income
    $expense = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', $branch_id);
        })
      ->where('CODE', 'R')
      ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m-%d")'), date('Y-m-d'))
      ->get();

     

    $today_revenue = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', $branch_id);
        })
      ->where('CODE', 'R')
      ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m-%d")'), date('Y-m-d'))
      ->get();
      
      
      $monthly_revenue = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', $branch_id);
        })
      ->where('CODE', 'R')
      ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m")'), date('Y-m'))
      ->get();



    $r = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', $branch_id);
        })
    //   ->whereBetween('Date', array($start_date, $end_date))
      ->whereYear('Date', $year)
      ->where('CODE', 'R')
      ->get();

    $e = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance'))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', $branch_id);
        })
         ->whereYear('Date', $year)
    //   ->whereBetween('Date', array($start_date, $end_date))
      ->where('CODE', 'E')
      ->get();

    $r = ($r[0]->Balance == null) ? '0' :  $r[0]->Balance;
    $e = ($e[0]->Balance == null) ? '0' :  $e[0]->Balance;
    

    
    $profit_loss = abs($r) - abs($e);

    $cash = DB::table('v_journal')
      ->select('ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', $branch_id);
        })
      ->whereIn('Category', ['CASH','BANK','CARD'])
      // ->where('ChartOfAccountID',$request->ChartOfAccountID)
      ->groupBy('ChartOfAccountName')
      ->get();

    
      
      if (session('UserType') == 'SuperAdmin' && $branch_id != null) {
        $cash1 = DB::table('v_income_expense');
        } elseif(session('UserType') == 'SuperAdmin' && $branch_id == null)
        {
            $cash1 = DB::table('v_income_expense_all');
        }
        else {
            $cash1 = DB::table('v_income_expense');
        }

     
    
    $cash1 = $cash1
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
            return $query->where('BranchID', session('BranchID'));
        })
        ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
            return $query->where('BranchID', $branch_id);
        })
        ->whereIn('yDate', [date('Y'), date('Y') - 1])
        ->orderByDesc('yDate')
        ->orderByDesc('mDate')
        ->take(12)
        ->get()
        ->reverse();
        
      
  
    $exp_chart = DB::table('v_expense_chart')
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
    })
    ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
      // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
      return $query->where('BranchID', $branch_id);
      })
    ->where('MonthName', date('F-Y'))->get();
 
 

    $party_balance = DB::table('v_party_bal')
      ->select(DB::raw('sum(if(ISNULL(Balance),0,Balance)) as Balance'))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', $branch_id);
        })
      ->get();

 
    $ticket_register = DB::table('v_invoice_detail1')
      ->select('SalemanName', DB::raw('count(*) as TotalInvoices'), DB::raw('sum(Fare) as Fare'), DB::raw('ROUND(sum(Service), 2) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', $branch_id);
        })
      ->whereBetween('Date', array(date('Y-m-1'), date('Y-m-d')))
      ->groupBy('SalemanName')
      // ->orderBy('Date')
      ->get();
    

    $invoice_summary = DB::table('v_invoice_detail1')
      ->select(
        DB::raw('count(*) as TotalInvoices'), 
        DB::raw('sum(Fare) as Fare'), 
        DB::raw('sum(Service) as Service'), 
        DB::raw('sum(Total) as Total'), 
        DB::raw('sum(Taxable) as Taxable'),
        DB::raw('sum(Discount) as Discount'))
      ->whereBetween('Date', array(date('Y-m-1'), date('Y-m-d')))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
        // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
        return $query->where('BranchID', $branch_id);
        })
      // ->orderBy('Date')
      ->get();
    
$date1 = date_create(date('Y-m-1'));   // First day of the month
$date2 = date_create(date('Y-m-d'));   // Current date

$interval = $date1->diff($date2);
$diff = ($interval->days == 0) ? 1 : $interval->days + 1;  // Adding 1 to include today's date

$avg = ($invoice_summary[0]->Service / $diff);  // Average based on total days including today
echo $diff;

echo "<br>";
$average= $monthly_revenue[0]->Balance/$diff;




     $sale_report = DB::table('v_invoice_detail')
    ->select('ItemName', DB::raw('count(InvoiceMasterID) as Total'))
    ->whereBetween('date', array(date('Y-m-1'), date('Y-m-d')))
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
    })
    ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
      // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
      return $query->where('BranchID', $branch_id);
      })
    ->groupBy('ItemName')
    ->get();

 

   
    $monthly_income = DB::table('journal')
    ->select( DB::raw('sum(if(ISNULL(Cr),0,Cr)) - sum(if(ISNULL(Dr),0,Dr)) as Monthly_Income'))
    // ->whereIn('Category', ['CASH','BANK','CARD'])
    ->where('ChartOfAccountID','410101')
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
    })
    ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
      // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
      return $query->where('BranchID', $branch_id);
      })
    // ->groupBy('ChartOfAccountName')
    ->first();


    $today_sale = DB::table('v_journal')
    ->selectRaw("
        SUM(IF(ChartOfAccountID = 110101, Dr, 0)) AS CASH,
        SUM(IF(ChartOfAccountID = 110202, Dr, 0)) AS ADCB,
        SUM(IF(ChartOfAccountID = 110201, Dr, 0)) AS ENBD,
        SUM(IF(ChartOfAccountID = 110101, Dr, 0)) + 
        SUM(IF(ChartOfAccountID = 110202, Dr, 0)) + 
        SUM(IF(ChartOfAccountID = 110201, Dr, 0)) AS TOTAL_SALE
    ")
    ->where('Date', date('Y-m-d'))
    ->whereNotNull('InvoiceMasterID')
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      // If the user is not an Admin, filter by the user's BranchID
      return $query->where('BranchID', session('BranchID'));
      })
      ->when(session('UserType') == 'SuperAdmin' && $branch_id != null, function ($query) use ($branch_id) {
      // If the user is an Admin and a specific branch ID is provided, filter by that BranchID
      return $query->where('BranchID', $branch_id);
      })
    ->first();




    // pro stats

      $flight = InvoiceMaster::where('FlightNights', '>', 0)->get();
      $umrah = UmrahInvoicePassenger::all();
      $hotel = InvoiceHotel::all();
      $transport = InvoiceTransport::all();

    

   
 
     return view('dashboard', compact('pagetitle', 'v_cashflow', 'invoice_master', 'expense', 'monthly_revenue','today_revenue', 'profit_loss', 'cash', 'cash1', 'exp_chart', 'party_balance', 'ticket_register', 'avg','sale_report','average','invoice_summary','monthly_income','today_sale','bankCharges','flight','umrah','hotel','transport'));
  }


  public  function ItemWiseSale1()
    {

      $pagetitle='Item wise report';
                
      $item_detail = DB::table('v_invoice_detail')
    ->select(DB::raw('count(InvoiceMasterID) as Total'))
    ->addSelect('SalemanName')
    ->addSelect(DB::raw('sum(IF(ItemID = 7, 1, 0)) AS Approval'))
    ->addSelect(DB::raw('sum(IF(ItemID = 8, 1, 0)) AS Covid'))
    ->addSelect(DB::raw('sum(IF(ItemID = 9, 1, 0)) AS V1'))
    ->addSelect(DB::raw('sum(IF(ItemID = 10, 1, 0)) AS V2'))
    ->addSelect(DB::raw('sum(IF(ItemID = 11, 1, 0)) AS V3'))
    ->addSelect(DB::raw('sum(IF(ItemID = 12, 1, 0)) AS V4'))
    ->addSelect(DB::raw('sum(IF(ItemID = 20, 1, 0)) AS V5'))
    ->addSelect(DB::raw('sum(IF(ItemID = 21, 1, 0)) AS V6'))
    ->addSelect(DB::raw('sum(IF(ItemID = 13, 1, 0)) AS Freelancer'))
    ->addSelect(DB::raw('sum(IF(ItemID = 14, 1, 0)) AS Hotel'))
    ->addSelect(DB::raw('sum(IF(ItemID = 15, 1, 0)) AS KSA'))
    ->addSelect(DB::raw('sum(IF(ItemID = 16, 1, 0)) AS Safari'))
    ->addSelect(DB::raw('sum(IF(ItemID = 17, 1, 0)) AS Ticket'))
    ->addSelect(DB::raw('sum(IF(ItemID = 18, 1, 0)) AS Visa'))
    ->addSelect(DB::raw('sum(IF(ItemID = 19, 1, 0)) AS Umrah'))
    ->addSelect(DB::raw('sum(IF(ItemID = 24, 1, 0)) AS S1'))
    ->addSelect(DB::raw('sum(IF(ItemID = 25, 1, 0)) AS GT'))
    ->whereBetween('date', array(date('Y-m-1'), date('Y-m-d')))
    ->groupBy('SalemanName')
    ->get();

    return view ('itemwise_report1',compact('item_detail','pagetitle'));
    }
  public function ExpenseDetail()
  {

    $pagetitle = 'Expense';

    $expense = DB::table('v_journal')
      // ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance') )
      ->where('CODE', 'E')
      ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m")'), date('Y-m'))
      ->where('ChartOfAccountID', '<>', 510103)
      // ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m")'),'2023-03')
      ->orderBy('Date')
      ->get();

    return view('expense_detail', compact('pagetitle', 'expense'));
  }

  public function Item()
  {
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Item/Inventory', 'List / Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Item');
    $pagetitle = 'Item';

    // $item = DB::table('item')->get();
    $item = Item::with('branch')->get();
    
    // $branches = \App\Models\Branch::all(); // Fetch branch data from Branch model


    $branches = Branch::getBranchList();
    
    return view('item', compact('pagetitle', 'item', 'branches'));
  }

  public  function ItemSave(request $request)
  {
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Item/Inventory', 'List / Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    try {
      $this->validate(
        $request,
        [

          'ItemCode' => 'required',

          'ItemName' => 'required',

        ],
        [
          'ItemCode.required' => 'Item Code is required',
          'ItemName.required' => 'Item Name is required',

        ]
      );

      $data = array(
        'BranchID' => $request->input('BranchID'),
        'ItemCode' => $request->input('ItemCode'),
        'ItemName' => $request->input('ItemName'),
        'Taxable' => $request->input('Taxable'),
        'Percentage' => $request->input('Percentage') ?? 0,

      );

      $id = DB::table('item')->insertGetId($data);

      return redirect('Item')->with('error', 'Save Successfully.')->with('class', 'success');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->with('class', 'danger');
    }
  }

  public  function ItemEdit($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Item/Inventory', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Item');
    $pagetitle = 'Item';

    $item = DB::table('item')->where('ItemID', $id)->get();
    $branches = \App\Models\Branch::all(); // Fetch branch data from Branch model

    return view('item_edit', compact('pagetitle', 'item', 'branches'));
  }

  public  function ItemUpdate(request $request)
  {
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Item/Inventory', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    try {
      $this->validate(
        $request,
        [
          'ItemName' => 'required',
        ],
        [
          'ItemName.required' => 'Item Name is required',
        ]
      );

      $data = array(
        'ItemCode' => $request->input('ItemCode'),
        'ItemName' => $request->input('ItemName'),
        'Taxable' => $request->input('Taxable'),
        'Percentage' => $request->input('Percentage'),
      );

      $id = DB::table('item')->where('ItemID', $request->input('ItemID'))->update($data);

      return redirect('Item')->with('error', 'Updated Successfully.')->with('class', 'success');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->with('class', 'danger');
    }
  }

  public  function ItemDelete($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Item/Inventory', 'Delete');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////           

    $id = DB::table('item')->where('ItemID', $id)->delete();

    return redirect('Item')->with('error', 'Deleted Successfully')->with('class', 'success');
  }

  public  function Supplier()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Supplier', 'List / Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Supplier');
    $pagetitle = 'Supplier';

    $supplier = DB::table('v_supplier')->get();
    $supplier_category = DB::table('supplier_category')->get();
    return view('supplier', compact('pagetitle', 'supplier', 'supplier_category'));
  }

  public  function SaveSupplier(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Supplier', 'List / Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $this->validate(
      $request,
      [

        'SupplierName' => 'required',

      ],
      [
        'SupplierCatID.required' => 'Suppier Type  is required',
        'SupplierName.required' => 'Supplier Name is required',

      ]
    );

    $data = array(

      'SupplierCatID' => $request->input('SupplierCatID'),
      'SupplierName' => $request->input('SupplierName'),
      'Address' => $request->input('Address'),
      'Phone' => $request->input('Phone'),
      'Email' => $request->input('Email'),
      'Active' => $request->input('Active'),
      'InvoiceDueDays' => $request->input('InvoiceDueDays'),

    );

    $id = DB::table('supplier')->insertGetId($data);

    return redirect('Supplier')->with('error', 'Save Successfully.')->with('class', 'success');
  }

  public  function SupplierEdit($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Supplier', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Supplier');
    $pagetitle = 'Supplier';

    $supplier = DB::table('v_supplier')->where('SupplierID', $id)->get();
    $supplier_category = DB::table('supplier_category')->get();
    return view('supplier_edit', compact('pagetitle', 'supplier', 'supplier_category'));
  }

  public  function SupplierUpdate(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Supplier', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    $this->validate(
      $request,
      [

        'SupplierName' => 'required',

      ],
      [
        'SupplierCatID.required' => 'Suppier Type  is required',
        'SupplierName.required' => 'Supplier Name is required',

      ]
    );

    $data = array(

      'SupplierCatID' => $request->input('SupplierCatID'),
      'SupplierName' => $request->input('SupplierName'),
      'Address' => $request->input('Address'),
      'Phone' => $request->input('Phone'),
      'Email' => $request->input('Email'),
      'Active' => $request->input('Active'),
      'InvoiceDueDays' => $request->input('InvoiceDueDays'),

    );

    $id = DB::table('supplier')->where('SupplierID', $request->input('SupplierID'))->update($data);

    return redirect('Supplier')->with('error', 'Updated Successfully.')->with('class', 'success');
  }
  public  function SupplierDelete($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Supplier', 'Delete');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $id = DB::table('supplier')->where('SupplierID', $id)->delete();

    return redirect('Supplier')->with('error', 'Deleted Successfully')->with('class', 'success');
  }

  // parties 

  public  function Parties()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party / Customers', 'List / Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Party');
    $pagetitle = 'Parties';
    $party_type = DB::table('party_type')->get();

    $party = \App\Models\Party::with(['branch:id,name'])
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
      })
    ->get();

    $branches = \App\Models\Branch::when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('id', session('BranchID'));
    })->get();
   
    return view('party', compact('pagetitle', 'party', 'branches','party_type'));
  }




  public function ajax_party_validate(request $request)
  {

    $phone = substr($request->Phone, -9);

    $party = DB::table('party')->where('Phone', 'like', '%' . $phone . '%')->get();

    if (!$party->isEmpty()) {
      return response()->json(['total' => 1]);
    } else {

      return response()->json(['total' => 0]);
    }
  }

  public function ajax_party_save(request $request)
  {


     $data = array(
      'PartyName' =>  $request->PartyName,
      'Phone' =>  $request->Phone,
      'BranchID' =>session('BranchID'),
      'PartyType' => 'C',
    );

    $party = DB::table('party')->insertGetId($data);

    return response()->json([
      'PartyID' => $party, 'PartyName' =>  $request->PartyName,
      'Phone' =>  $request->Phone
    ]);
  }


 public function ajax_party_save2(Request $request)
{
    // ✅ Step 1: Validate the request
    $validator = Validator::make($request->all(), [
        'PartyName' => 'required|string|max:255',
        'Phone' => 'required|string|max:20|regex:/^[0-9+\s()-]+$/',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors(),
            'success' => false,
            'error'   => $validator->errors(),
        ], 422); // 422 = Unprocessable Entity
    }


    //   return response()->json([
    //         'success' => true,
    //         'message' => 'Record added successfully.',
    //     ], 200);
    // } catch (\Exception $e) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'An error occurred while saving the record.',
    //         'error'   => $e->getMessage(),
    //     ], 500);
    // }





    // ✅ Step 2: Insert data
    $data = [
        'PartyName' => $request->PartyName,
        'Phone' => $request->Phone,
        'BranchID' => session('BranchID'),
        'PartyType' => 'C',
    ];

    $partyID = DB::table('party')->insertGetId($data);

    // ✅ Step 3: Return response
    return response()->json([
        'PartyID' => $partyID,
        'PartyName' => $request->PartyName,
        'Phone' => $request->Phone,
        'success' => true,
        'message' =>'saved successfully'
    ]);
}

  public  function SaveParties(request $request)
  {
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party / Customers', 'List / Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $this->validate(
      $request,
      [
        'PartyName' => 'required',
        'Phone' => 'required|numeric|unique:party,Phone',
        'Active' => 'required',

      ],
      [
        'PartyName.required' => 'Party / Cusomter Name is required',
        'Phone.unique' => 'The phone number already exist.'

      ]
    );

    $data = array(

      'BranchID' => $request->BranchID,
      'PartyType' => $request->PartyType,
      'PartyName' => $request->PartyName,
      'Address' => $request->Address,
      'Phone' => $request->Phone,
      'Email' => $request->Email,
      'Active' => $request->Active,
      'InvoiceDueDays' => $request->InvoiceDueDays,

    );

    $id = DB::table('party')->insertGetId($data);

    return redirect('Parties')->with('error', 'Save Successfully.')->with('class', 'success');
  }

  public  function PartiesEdit($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party / Customers', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    try {
      session::put('menu', 'Party');
      $pagetitle = 'Party';
      $party_type = DB::table('party_type')->get();
      $party = DB::table('party')
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
        })
        ->where('PartyID', $id)
        ->get();

      if ($party->isEmpty()) {
          return redirect()->back()->with('error', 'Invalid Party ID')->with('class', 'danger');
      }
        
      $branches = \App\Models\Branch::when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('id', session('BranchID'));
      })->get();

      return view('party_edit', compact('pagetitle', 'party', 'branches', 'party_type'));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->with('class', 'danger');
    }
  }

  public  function PartiesUpdate(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party / Customers', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $this->validate(
      $request,
      [
        'PartyName' => 'required',

        'Active' => 'required',

      ],
      [
        'PartyName.required' => 'Party / Cusomter Name is required',

      ]
    );

    $data = array(

     'BranchID' => $request->BranchID,
      'PartyType' => $request->PartyType,
      'PartyName' => $request->PartyName,
      'Address' => $request->Address,
      'Phone' => $request->Phone,
      'Email' => $request->Email,
      'Active' => $request->Active,
      'InvoiceDueDays' => $request->InvoiceDueDays,

    );

    $id = DB::table('party')->where('PartyID', $request->input('PartyID'))->update($data);

    return redirect('Parties')->with('error', 'Updated Successfully.')->with('class', 'success');
  }
  public  function PartiesDelete($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party / Customers', 'Delete');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    try {
      DB::beginTransaction();
      $id = DB::table('party')->where('PartyID', $id)->delete();
      DB::commit();
      return redirect('Parties')->with('error', 'Deleted Successfully')->with('class', 'danger');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', $e->getMessage())->with('class', 'danger');
    }
  }

  public  function ChartOfAcc()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Chart of Account', 'List / Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'ChartOfAcc');
    $pagetitle = 'ChartOfAcc';
    $chartofaccount = DB::table('chartofaccount')->get();
    $chart = DB::table('chartofaccount')->get();

    return view('chart_of_account', compact('pagetitle', 'chartofaccount', 'chart'));
  }

  public function UserProfile()
  {

    $v_users = DB::table('user')->where('UserID', session::get('UserID'))->get();

    return  view('user_profile', compact('v_users'));
  }

  public function ChangePassword()
  {

    $v_users = DB::table('user')->where('UserID', session::get('UserID'))->get();

    return  view('change_password', compact('v_users'));
  }

  public function UpdatePassword(request $request)
  {

    $user = DB::table('user')->where('UserID', session::get('UserID'))->get();

    if ($user[0]->Password != $request->input('old_password')) {

      return redirect('ChangePassword')->with('error', 'Old password doesnot matched')->with('class', 'danger');
    }

    $this->validate($request, [

      'old_password' => 'required',
      'new_password' => 'required|min:6',
      'new_confirm_passowrd' => 'required_with:new_password|same:new_password|min:6'
    ]);
    // ,[
    //   'old_password.required' => 'Old Password is required',
    //        'new_password.required' => 'New Password is required ',
    //        'new_confirm_passowrd.required' => 'Confirm Password is required '

    // ]);

    $data = array(
      'Password' => $request->input('new_password')

    );

    $id = DB::table('user')->where('UserID', session::get('UserID'))->update($data);
    return redirect('Dashboard')->with('error', 'Password updated Successfully')->with('class', 'success');
  }

  public function Role($UserID)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'User Rights', 'Assign');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'User Rights & Control';
    $users = DB::table('user')->where('UserID', $UserID)->get();

    $role = DB::table('role')->select('Table')->distinct()->get();

    return view('role', compact('pagetitle', 'role', 'users'));
  }

  public function RoleView($UserID)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'User Rights', 'Assign');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'User Rights & Control';
    $users = DB::table('user')->where('UserID', $UserID)->get();

    $role = DB::table('role')->select('Table')->distinct()->get();

    return view('view_role', compact('pagetitle', 'role', 'users'));
  }

  public function RoleSave(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'User Rights', 'Assign');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $TableName = $request->TableName;
    $Action = $request->Action;
    $Allow = $request->Allow;

    $tot = count($request->TableName);
    // echo count($box); // count how many values in array
    for ($i = 0; $i < $tot; $i++) {
      // echo $TableName[$i] .'-' . $Action[$i] .'-'.  $Allow[$i] . "<BR>";

      $data = array(

        'UserID' => $request->UserID,
        'Table' => $TableName[$i],
        'Action' => $Action[$i],
        'Allow' => $Allow[$i],

      );

      $id = DB::table('user_role')->insertGetId($data);
    }

    return redirect('User')->with('error', 'User perission saved Successfully')->with('class', 'success');
  }

  public function RoleUpdate(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'User Rights', 'Assign');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $id = DB::table('user_role')->where('UserID', $request->UserID)->delete();

    $TableName = $request->TableName;
    $Action = $request->Action;
    $Allow = $request->Allow;

    $tot = count($request->TableName);
    // echo count($box); // count how many values in array
    for ($i = 0; $i < $tot; $i++) {
      // echo $TableName[$i] .'-' . $Action[$i] .'-'.  $Allow[$i] . "<BR>";

      $data = array(

        'UserID' => $request->UserID,
        'Table' => $TableName[$i],
        'Action' => $Action[$i],
        'Allow' => $Allow[$i],

      );

      $id = DB::table('user_role')->insertGetId($data);
    }

    return redirect('User')->with('error', 'User perission saved Successfully')->with('class', 'success');
  }

  public function SendEMail(request $request)
  {

    $email = $request->input('Email');
    // $email = ['kashif@inu.edu.pk', 'kashif_mushtaq2008@htomail.com','kashif.mushtaq2050@gmail.com'];

    $data = array(

      'Name' => $request->input('Name'),
      'Email' => $request->input('Email'),
      'Subject' => $request->input('Subject'),
      'Message' => $request->input('Message'),

    );
    Mail::to($email)->send(new SendMail($data));
    return redirect($request->input('PageLink'))->with('error', 'Email sent!')->with('class', 'success');
  }

  public function ComposeEmail($EmployeeID)
  {
    $pagetitle = 'Compose Email';

    $employee =  DB::table('v_employee')->where('EmployeeID', $EmployeeID)->get();
    return view('compose_email', compact('employee', 'pagetitle'));
  }

  public function ForgotPassword()
  {
    return view('forgot_password');
  }

  public function SendForgotEmail(request $request)
  {

    if ($request->StaffType == 'Management') {

      $username = $request->input('Email');

      $user = DB::table('users')->where('Email', '=', $username)
        ->get();

      if (count($user) > 0) {

        $email = $user[0]->Email;

        // $data = array (

        //               'Name' => $request->input('Name'),
        //               'Email' => $request->input('Email'),
        //               'Subject' => $request->input('Subject'),
        //               'Message' => $request->input('Message'),

        //      );
        //Mail::to($email) ->send(new SendMail($data));
        return redirect('EmailPin')->with('error', 'Enter Code')->with('class', 'success');
      } else {

        return redirect('ForgotPassword')->with('error', 'Invalid Email')->with('class', 'success');
      }
    } else {

      $username = $request->input('Email');

      $data = DB::table('employee')->where('Email', '=', $username)
        // ->where('Active', '=', 'Y' )
        ->get();

      if (count($data) > 0) {

        $data[0]->Email;

        return redirect('EmailPin')->with('error', 'Enter Code')->with('class', 'success');
      } else {
        //session::flash('error', 'Invalid username or Password. Try again'); 

        return redirect('ForgotPassword')->withinput($request->all())->with('error', 'Invalid Email. Try again');
      }
    }
    // for staff login
  }

  public function EmailPin()
  {
    return view('email_pin');
  }

  public function NewPassword(request $request)
  {
    $employee = DB::table('employee')->get();
  }

  public function UpdateNewPassword(request $request)
  {
    $employee = DB::table('employee')->get();
  }

  public function DepositExport($type)
  {

    // $employees = DB::table('v_property')->get();

    $fcb = DB::table('v_fcb')->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'S.NO');
    $sheet->setCellValue('B1', 'ID');
    $sheet->setCellValue('C1', 'Agent');
    $sheet->setCellValue('D1', 'FTD Amount');
    $sheet->setCellValue('E1', 'Date');
    $sheet->setCellValue('F1', 'Compliant');
    $sheet->setCellValue('G1', 'KYC Sent');
    $sheet->setCellValue('H1', 'Dialer');

    $rows = 2;
    foreach ($fcb as $key => $value) {

      $sheet->setCellValue('A' . $rows, ++$key);
      $sheet->setCellValue('B' . $rows, $value->ID);
      $sheet->setCellValue('C' . $rows, $value->FirstName);
      $sheet->setCellValue('D' . $rows, $value->FTDAmount);
      $sheet->setCellValue('E' . $rows, $value->Date);
      $sheet->setCellValue('F' . $rows, $value->Compliant);
      $sheet->setCellValue('G' . $rows, $value->KYCSent);
      $sheet->setCellValue('H' . $rows, $value->Dialer);

      $rows++;
    }

    $fileName = "Deposit." . $type;
    if ($type == 'xlsx') {
      $writer = new Xlsx($spreadsheet);
    } else if ($type == 'xls') {
      $writer = new Xls($spreadsheet);
    }
    $writer->save($fileName);
    header("Content-Type: application/vnd.ms-excel");
    return redirect(url('/') . "/" . $fileName);
  }

  public function PartyLedger()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party Ledger', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'PartyLedger');
    $pagetitle = 'Party Ledger';
    $party = Party::getPartyList('C');
    $voucher_type = DB::table('voucher_type')->get();
    $chartofaccount = DB::table('chartofaccount')
      ->where('ChartOfAccountID', 110400)->get();
    return view('reports.party_ledger', compact('pagetitle', 'party', 'voucher_type', 'chartofaccount'));
  }

  public function PartyLedger1(request $request)
  {

     ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party Ledger', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'PartyLedger');
    $pagetitle = 'Party Ledger';

    session::put('StartDate', $request->StartDate);
    session::put('EndDate', $request->EndDate);
 
    $where = array();

    if ($request->PartyID > 0) {

      $where = Arr::add($where, 'PartyID', $request->PartyID);
    }

    $sql = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
      // ->where('PartyID',$request->PartyID)
      ->whereIn('ChartOfAccountID',[210100,110400]) //  A/C PAYABLE ,  A/C RECEIVABLE. 
      ->where($where)
      ->where('Date', '<', $request->StartDate)
      ->get();

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;


    $party = DB::table('party')->where('PartyID', $request->PartyID)->get();

    $journal = DB::table('v_journal')->where('PartyID', $request->PartyID)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->whereIn('ChartOfAccountID',[210100,110400]) //  A/C PAYABLE ,  A/C RECEIVABLE. 

      ->where($where)
      // ->orderBy('VHNO', 'asc')
      ->orderBy('Date', 'asc') // Sort by Date in ascending order
      ->orderBy('JournalID', 'asc')   // Sort by ID in ascending order
      ->get();

      
    return view('reports.party_ledger1', compact('journal', 'pagetitle', 'sql', 'party'));
  }


  public function ajax_party_ledger($partyid)
  {
   
    session::put('menu', 'PartyLedger');
    $pagetitle = 'Party Ledger';
 
    $sql = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
       ->where('PartyID',$partyid)
      ->whereIn('ChartOfAccountID',[110400,210100])
      ->where('Date', '<', '2020-01-01')
      // ->whereBetween('date',array($request->StartDate,$request->EndDate))

      ->get();

    // dd($sql[0]->Balance);
    // $sql= DB::select( DB::raw( 'SET @total := '.$sql[0]->Balance.''));
    // $sql= DB::select( DB::raw( 'select @total as t'));

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    // $a = DB::select(DB::raw('select * from v_journal where PartyID = @total'));
    // $journal = DB::select(DB::raw('SELECT a.JournalID, a.ChartOfAccountID, a.*, IF(ISNULL(a.Dr),0,a.Dr) as Dr, a.Cr,sum(if(ISNULL(b.Dr),0,b.Dr)-if(ISNULL(b.Cr),0,b.Cr))+'.$sql[0]->Balance.' as Balance FROM   v_journal a,  v_journal b WHERE b.JournalID <= a.JournalID and a.PartyID='.$request->PartyID.' and b.PartyID='.$request->PartyID.' and a.ChartOfAccountID=110400 and b.ChartOfAccountID=110400 GROUP BY a.JournalID, a.ChartOfAccountID, a.Dr, a.Cr ORDER BY a.JournalID'));
    // $a = DB::table('v_journal')->where('PartyID',DB::raw( '@total'))->get();

    $party = DB::table('party')->where('PartyID', $partyid)->get();

    $journal = DB::table('v_journal')->where('PartyID', $partyid)
      ->whereBetween('Date', array('2020-01-01', date('Y-m-d')))
      ->whereIn('ChartOfAccountID',[110400,210100])
      // ->orderBy('VHNO', 'asc')
      ->orderBy('Date', 'asc') // Sort by Date in ascending order
      ->orderBy('JournalID', 'asc')   // Sort by ID in ascending order
      ->get();

    //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party')); 
    // //return $pdf->download('pdfview.pdf');
    //    $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
    return view('blank_party_ledger1', compact('journal', 'pagetitle', 'sql', 'party'));
  }

  public function PartyLedger1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party Ledger', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

session::put('menu', 'PartyLedger');
    $pagetitle = 'Party Ledger';

    session::put('StartDate', $request->StartDate);
    session::put('EndDate', $request->EndDate);
 
    $where = array();

    if ($request->PartyID > 0) {

      $where = Arr::add($where, 'PartyID', $request->PartyID);
    }

    $sql = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
      // ->where('PartyID',$request->PartyID)
      ->whereIn('ChartOfAccountID',[210100,110400]) //  A/C PAYABLE ,  A/C RECEIVABLE. 
      ->where($where)
      ->where('Date', '<', $request->StartDate)
      ->get();

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;


    $party = DB::table('party')->where('PartyID', $request->PartyID)->get();

    $journal = DB::table('v_journal')->where('PartyID', $request->PartyID)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->whereIn('ChartOfAccountID',[210100,110400]) //  A/C PAYABLE ,  A/C RECEIVABLE. 

      ->where($where)
      // ->orderBy('VHNO', 'asc')
      ->orderBy('Date', 'asc') // Sort by Date in ascending order
      ->orderBy('JournalID', 'asc')   // Sort by ID in ascending order
      ->get();

    $pdf = PDF::loadView('reports.party_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'party'));
    //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
    // return view ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party')); 
  }

  public  function AdjustmentBalance()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Adjustment Balance', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    $pagetitle = 'AdjustmentBalance';

    session::put('menu', 'AdjustmentBalance');
    $voucher_type = DB::table('voucher_type')->where('VoucherTypeID', 7)->get();
    $party = Party::getPartyList('C');

    return view('adjust_balance', compact('voucher_type', 'pagetitle', 'party'));
  }

  public function AdjustmentBalanceSave(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Adjustment Balance', 'Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'AdjustmentBalance');
    $pagetitle = 'AdjustmentBalance';
    list($InvoiceTypeID, $InvoiceTypeCode) = explode("-", $request->InvoiceType1);

    // dd($request->all());
    $voucher_mst = array(
      'VoucherCodeID' => $InvoiceTypeID,
      'Voucher' => $request->input('Voucher'),
      'Narration' => $request->input('Narration'),
      'Date' => $request->input('VHDate'),

    );

    // dd($invoice_mst);

    // $id= DB::table('')->insertGetId($data);

    $id = DB::table('voucher_master')->insertGetId($voucher_mst);

    if ($request->CustomType == 1) //discount allowed
    {
      $DISCOUNT_ALLOWED = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 510104, // discount allowed
        'PartyID' => $request->PartyID,
        'Narration' => 'Discount allowed',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );

      $AR = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 110400, //A/R
        'PartyID' => $request->PartyID,
        'Narration' => 'Discount allowed',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id2 = DB::table('voucher_detail')->insert($DISCOUNT_ALLOWED);
      $id1 = DB::table('voucher_detail')->insert($AR);
    } elseif ($request->CustomType == 2) { //discount received

      $AR = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 110400,
        'PartyID' => $request->PartyID,
        'Narration' => 'Discount received',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );

      $DISCOUNT_REC = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 410152,
        'PartyID' => $request->PartyID,
        'Narration' => 'Discount received',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id1 = DB::table('voucher_detail')->insert($AR);
      $id2 = DB::table('voucher_detail')->insert($DISCOUNT_REC);
    } elseif ($request->CustomType == 3) { //Increase receivable

      $AR = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 110400, //A/R
        'PartyID' => $request->PartyID,
        'Narration' => 'Increase receivable',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );

      $INCREASE_REC = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 210103, //Balance adjustment
        'PartyID' => $request->PartyID,
        'Narration' => 'Increase receivable',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id1 = DB::table('voucher_detail')->insert($AR);
      $id2 = DB::table('voucher_detail')->insert($INCREASE_REC);
    } elseif ($request->CustomType == 4) { //Decrease receivable

      $DECREASE_REC = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 210103, //Balance adjustment
        'PartyID' => $request->PartyID,
        'Narration' => 'Decrease receivable',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );

      $AR = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 110400, //A/R
        'PartyID' => $request->PartyID,
        'Narration' => 'Decrease receivable',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id2 = DB::table('voucher_detail')->insert($DECREASE_REC);
      $id1 = DB::table('voucher_detail')->insert($AR);
    } elseif ($request->CustomType == 5) { //Increased payable

      $BALANCE_ADJ = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 210103, //Balance adjustment
        'SupplierID' => $request->SupplierID,
        'Narration' => 'Increased payable',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );

      $AP = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 210100,  // A/P
        'SupplierID' => $request->SupplierID,
        'Narration' => 'Increased payable',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id1 = DB::table('voucher_detail')->insert($AP);
      $id2 = DB::table('voucher_detail')->insert($BALANCE_ADJ);
    } elseif ($request->CustomType == 6) { // Decrease payable

      $AP = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 110400, // A/P
        'SupplierID' => $request->SupplierID,
        'Narration' => 'Decrease payable',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );

      $BALANCE_ADJ = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 210103, //Balance adjustment
        'SupplierID' => $request->SupplierID,
        'Narration' => 'Decrease payable',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id1 = DB::table('voucher_detail')->insert($AP);
      $id2 = DB::table('voucher_detail')->insert($BALANCE_ADJ);
    } elseif ($request->CustomType == 7) { // Fee charged / billed increased

      $AR = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 110400, //A/R
        'PartyID' => $request->PartyID,
        'Narration' => 'Fee charged / billed increased',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );

      $FEE_CHARGED = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 560111, //Fee charged
        'PartyID' => $request->PartyID,
        'Narration' => 'Fee charged / billed increased',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id1 = DB::table('voucher_detail')->insert($AR);
      $id2 = DB::table('voucher_detail')->insert($FEE_CHARGED);
    } elseif ($request->CustomType == 8) { // Fee charged / billed decreased

      $FEE_CHARGED = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 560111, //Fee charged
        'PartyID' => $request->PartyID,
        'Narration' => 'Fee charged / billed increased',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );
      $AR = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 110400, //AR
        'PartyID' => $request->PartyID,
        'Narration' => 'Fee charged / billed increased',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id2 = DB::table('voucher_detail')->insert($FEE_CHARGED);
      $id1 = DB::table('voucher_detail')->insert($AR);
    } elseif ($request->CustomType == 9) { //Fee bill / paid increase

      $FEE_CHARGED = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 560111, //Fee charged
        'SupplierID' => $request->SupplierID,
        'Narration' => 'Fee bill / paid increase',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );

      $AP = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 210100, //AP
        'SupplierID' => $request->SupplierID,
        'Narration' => 'Fee bill / paid increase',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id2 = DB::table('voucher_detail')->insert($FEE_CHARGED);
      $id1 = DB::table('voucher_detail')->insert($AP);
    } else  //Fee bill / paid decrease  (10)
    {

      $AP = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 210100, //AP
        'SupplierID' => $request->SupplierID,
        'Narration' => 'Fee bill / paid decreased',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Debit' => $request->Amount,

      );

      $FEE_CHARGED = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => 560111, //Fee charged
        'SupplierID' => $request->SupplierID,
        'Narration' => 'Fee bill / paid decreased',
        'InvoiceNo' => null,
        'RefNo' => null,
        'Credit' => $request->Amount,

      );

      $id1 = DB::table('voucher_detail')->insert($AP);
      $id2 = DB::table('voucher_detail')->insert($FEE_CHARGED);
    }

    // echo "<pre>";
    // print_r($voucher_det);  

    // $idd= DB::table('voucher_detail')->insert($voucher_det);

    return redirect('Voucher')->with('error', 'Saved Successfully')->with('class', 'success');
  }

 public function SupplierBalance()
  {

    session::put('menu', 'SupplierBalance');
    $pagetitle = 'Supplier Balance';
    $branches = Branch::getBranchList();
    return view('reports.supplier_balance', compact('pagetitle','branches'));
  }

  public  function SupplierBalance1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Supplier Balance', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'SupplierBalance');
    $pagetitle = 'Supplier Balance';

    $supplier = Party::getSupplierList();


 
    
    
    
    $where = array();
    
    $where = [];
    
    
    if ($request->BranchID > 0) {
      $where['journal.BranchID'] = $request->BranchID;
    }
    
  
if ($request->ReportType == 'D' || $request->ReportType == 'C') {

  
  $supplier = DB::table('journal')
   ->select('party.PartyID', 'party.PartyName', 'branches.name as BranchName', DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
   ->join('party', 'journal.PartyID', '=', 'party.PartyID')
   ->leftjoin('branches', 'journal.BranchID', '=', 'branches.id')
   ->whereBetween('journal.Date', [$request->StartDate, $request->EndDate])
   ->whereIn('journal.ChartOfAccountID', [210100, 110400]) //  A/C PAYABLE ,  A/C RECEIVABLE.
   ->where($where)
   ->groupBy('party.PartyID', 'party.PartyName', 'branches.name')
   ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
   ->get();
}

else
{


  $supplier = DB::table('journal')
  ->select('party.PartyID', 'party.PartyName', 'branches.name as BranchName',DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
  ->join('party', 'journal.PartyID', '=', 'party.PartyID')
  ->leftjoin('branches', 'journal.BranchID', '=', 'branches.id')
  ->whereBetween('journal.Date', [$request->StartDate, $request->EndDate])
  ->whereIn('journal.ChartOfAccountID', [210100, 110400]) //  A/C PAYABLE ,  A/C RECEIVABLE.
  ->where($where)
  ->groupBy('party.PartyID', 'party.PartyName')
  //  ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
   ->get();

} 

 




    return view('reports.supplier_balance1', compact('supplier', 'pagetitle'));
  }

  public  function SupplierBalance1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Supplier Balance', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    session::put('menu', 'SupplierBalance');
    $pagetitle = 'SupplierBalance';

 


    $where = array();
    
    $where = [];
    
    
    if ($request->BranchID > 0) {
      $where['journal.BranchID'] = $request->BranchID;
    }
    
  
if ($request->ReportType == 'D' || $request->ReportType == 'C') {

  
  $supplier = DB::table('journal')
   ->select('party.PartyID', 'party.PartyName', 'branches.name as BranchName', DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
   ->join('party', 'journal.PartyID', '=', 'party.PartyID')
   ->leftjoin('branches', 'journal.BranchID', '=', 'branches.id')
   ->whereBetween('journal.Date', [$request->StartDate, $request->EndDate])
   ->whereIn('journal.ChartOfAccountID', [210100, 110400]) //  A/C PAYABLE ,  A/C RECEIVABLE.
   ->where($where)
   ->groupBy('party.PartyID', 'party.PartyName', 'branches.name')
   ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
   ->get();
}

else
{


  $supplier = DB::table('journal')
  ->select('party.PartyID', 'party.PartyName', 'branches.name as BranchName',DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
  ->join('party', 'journal.PartyID', '=', 'party.PartyID')
  ->leftjoin('branches', 'journal.BranchID', '=', 'branches.id')
  ->whereBetween('journal.Date', [$request->StartDate, $request->EndDate])
  ->whereIn('journal.ChartOfAccountID', [210100, 110400]) //  A/C PAYABLE ,  A/C RECEIVABLE.
  ->where($where)
  ->groupBy('party.PartyID', 'party.PartyName')
  //  ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
   ->get();

} 





    $pdf = PDF::loadView('reports.supplier_balance1pdf', compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('reports.supplier_balance1pdf', compact('supplier'));
  }

  public function PartyList()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party List', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Party List';
    $party = Party::getPartyList('C');

    return view('reports.party_list', compact('party', 'pagetitle'));
  }

  public function PartyListPDF()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party List', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    $pagetitle = 'Party List';

    $party = Party::getPartyList('C');
    $party = Party::getPartyList('C');

    $pdf = PDF::loadView('reports.party_listPDF', compact('party', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('reports.party_list', cocompact('party', 'pagetitle'));
  }

  public function OutStandingInvoice()
  {
    $pagetitle = 'Out Standing Invoice';
    session::put('menu', 'OutStandingInvoice');
    $party = Party::getPartyList('C');
    return view('reports.outstanding_invoice', compact('party', 'pagetitle'));
  }

  public function OutStandingInvoice1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Outstanding Invoices', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////


    $party = DB::table('journal')
    ->select(DB::raw('SUM(IFNULL(Cr, 0)) as balance'))
    ->join('party', 'journal.PartyID', '=', 'party.PartyID')
    // ->whereBetween('journal.date', [$request->StartDate, $request->EndDate])
    ->where('journal.PartyID', $request->PartyID)
    ->where('journal.ChartOfAccountID', 110400)
    ->first();


     
    $inputAmount=$party->balance;

      //  dd($inputAmount);

     $invoices = DB::table('invoice_master')
    ->where('Total', '>', 'Paid1')
    ->where('PartyID',$request->PartyID)
    ->orderBy('Date')
    ->get();


    if($inputAmount > $invoices->sum('Paid1') )
    {

      // dd(   $invoices);

     foreach ($invoices as $invoice) {
      $remaining = $invoice->Total ; //- $invoice->Paid1
  
      if ($inputAmount <= 0) break;
  
      $payNow = min($inputAmount, $remaining);
      
      $newPaid = $invoice->Paid1 + $payNow;
      // dd($newPaid);
       // Update the invoice
      DB::table('invoice_master')
      ->where('PartyID',$request->PartyID)
  
          ->where('InvoiceMasterID', $invoice->InvoiceMasterID)
          ->update([
              'Paid1' => $newPaid
          ]);
  
      $inputAmount -= $payNow;
    }

    }




    $pagetitle = 'Out Standing Invoice';
    if ($request->PartyID > 0) {
      $invoice = DB::table('v_invoice_master')->where('PartyID', $request->PartyID)->where('Total', '>', 'Paid1')->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    } else {

      $invoice = DB::table('v_invoice_master')->where('Total', '>', 'Paid1')->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    }


    if ($request->PartyID > 0) {
      $invoice_umrah = DB::table('v_umrah_invoice_master_balance')->where('PartyID', $request->PartyID)->where('Balance', '>', 0)->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    } else {
      $invoice_umrah = DB::table('v_umrah_invoice_master_balance')->where('Balance', '>', 0)->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    }





    

 

    return view('reports.outstanding_invoice1', compact('invoice', 'pagetitle','invoice_umrah'));
  }

  public function OutStandingInvoice1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Outstanding Invoices', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $party = DB::table('journal')
    ->select(DB::raw('SUM(IFNULL(Cr, 0)) as balance'))
    ->join('party', 'journal.PartyID', '=', 'party.PartyID')
    // ->whereBetween('journal.date', [$request->StartDate, $request->EndDate])
    ->where('journal.PartyID', $request->PartyID)
    ->where('journal.ChartOfAccountID', 110400)
    ->first();

     
    $inputAmount=$party->balance;

     dd($inputAmount);

     $invoices = DB::table('invoice_master')
    ->where('Total', '>', 'Paid1')
    ->where('PartyID',$request->PartyID)
    ->orderBy('Date')
    ->get();


     foreach ($invoices as $invoice) {
      $remaining = $invoice->Total - $invoice->Paid1;
  
      if ($inputAmount <= 0) break;
  
      $payNow = min($inputAmount, $remaining);
      $newPaid = $invoice->Paid + $payNow;
  
      // Update the invoice
      DB::table('invoice_master')
      ->where('PartyID',$request->PartyID)
  
          ->where('InvoiceMasterID', $invoice->InvoiceMasterID)
          ->update([
              'Paid1' => $newPaid
          ]);
  
      $inputAmount -= $payNow;
  }

    $pagetitle = 'Out Standing Invoice';
    if ($request->PartyID > 0) {
      $invoice = DB::table('v_invoice_master')->where('PartyID', $request->PartyID)->whereColumn('Total', '>', 'Paid1')->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    } else {

      $invoice = DB::table('v_invoice_master')->whereColumn('Total', '>', 'Paid1')->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    }

    $pdf = PDF::loadView('reports.outstanding_invoice1PDF', compact('invoice', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('reports.outstanding_invoice1', compact('invoice', 'pagetitle'));
  }

  public function PartyWiseSale()
  {

    session::put('menu', 'PartyLedger');
    $pagetitle = 'Party Ledger';
  $invoice_type = DB::table('invoice_type')->whereIn('InvoiceTypeID',[1,2])->get();
  $party = Party::getPartyList();
  $branches = Branch::getBranchList();

    return view('reports.partywise_sale', compact('pagetitle', 'invoice_type', 'party','branches'));
  }

  public function PartyWiseSale1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party Wise Sale', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Party wise sale';

    $query = DB::table('v_partywise_sale');

    $sale = DB::table('v_umrah_invoice_master')
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when($request->PartyID > 0, function ($query) use ($request) {
        return $query->where('PartyID', $request->PartyID);
    })
    ->get();

    


    if ($request->PartyID > 0) {
      $query->where('PartyID', $request->PartyID);
    }

    if ($request->BranchID > 0) {
      $query->where('BranchID', $request->BranchID);
    }
    
    if ($request->InvoiceTypeID == 1 || $request->InvoiceTypeID == 2) {
        $query->where('InvoiceTypeID', $request->InvoiceTypeID);
    } elseif ($request->InvoiceTypeID === 'both') {
        $query->whereIn('InvoiceTypeID', [1, 2]);
    }
    
    $party_wise = $query->get();
    
 
    //       $pdf = PDF::loadView ('partywise_sale1',compact('party_wise'));
    // //return $pdf->download('pdfview.pdf');
    //   // $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();




    return View('reports.partywise_sale1', compact('party_wise', 'pagetitle','sale'));
  }

  public function PartyWiseSale1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party Wise Sale', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Party wise sale';
    $query = DB::table('v_partywise_sale');

    if ($request->PartyID > 0) {
        $query->where('PartyID', $request->PartyID);
    }
    
    if ($request->InvoiceTypeID == 1 || $request->InvoiceTypeID == 2) {
        $query->where('InvoiceTypeID', $request->InvoiceTypeID);
    } elseif ($request->InvoiceTypeID === 'both') {
        $query->whereIn('InvoiceTypeID', [1, 2]);
    }
    
    $party_wise = $query->get();
    

    $pdf = PDF::loadView('reports.partywise_sale1PDF', compact('party_wise', 'pagetitle'));
    // //return $pdf->download('pdfview.pdf');
    //   // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return View('partywise_sale1', compact('party_wise'));
  }

  public function PartyBalance()
  {
    session::put('menu', 'PartyLedger');
    $pagetitle = 'Party Balance';

    $party = Party::getPartyList('C');
    $branches = Branch::getBranchList();

    return view('reports.party_balance', compact('pagetitle', 'party','branches'));
  }

  public function PartyBalance1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party Balance', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Party Balance';

    

    $where = array();

    $where = [];

    if ($request->PartyID > 0) {
      $where['journal.PartyID'] = $request->PartyID;
  }
   
 


   
  if ($request->ReportType == 'D' || $request->ReportType == 'C') {
   
     $party = DB::table('journal')
      ->select('party.PartyID', 'party.PartyName', DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
      ->join('party', 'journal.PartyID', '=', 'party.PartyID')
      ->whereBetween('journal.date', [$request->StartDate, $request->EndDate])
      ->whereIn('ChartOfAccountID', [110400,210100])
      ->when($request->BranchID > 0, function ($query) use ($request) {
        return $query->where('journal.BranchID', $request->BranchID);
    })
      ->where($where)
      ->orderby('party.PartyName')
      ->groupBy('party.PartyID', 'party.PartyName')
       ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
       ->get();
  
  }
  
  else
  {
  
  
       $party = DB::table('journal')
      ->select('party.PartyID', 'party.PartyName', DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
      ->join('party', 'journal.PartyID', '=', 'party.PartyID')
      ->whereBetween('journal.date', [$request->StartDate, $request->EndDate])
            ->whereIn('ChartOfAccountID', [110400,210100])
            ->when($request->BranchID > 0, function ($query) use ($request) {
              return $query->where('journal.BranchID', $request->BranchID);
          })
      ->where($where)
      ->orderby('party.PartyName')
      ->groupBy('party.PartyID', 'party.PartyName')
       // ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
       ->get();
  
  }




    return  View('reports.party_balance1', compact('party', 'pagetitle'));
  }

  public function PartyBalance1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party Balance', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Party Balance';

    if ($request->PartyID > 0) {

      $party = DB::table('v_party_balance')->select('PartyID', 'PartyName', DB::raw('sum(Dr) as Dr'), DB::raw('sum(Cr) as Cr'))
        ->whereBetween('date', array($request->StartDate, $request->EndDate))
        ->where('PartyID', $request->PartyID)
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('journal.BranchID', $request->BranchID);
      })
        ->groupBy('PartyID', 'PartyName')
        ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
        ->get();
    } else {

      $party = DB::table('v_party_balance')->select('PartyID', 'PartyName', DB::raw('sum(Dr) as Dr'), DB::raw('sum(Cr) as Cr'))
        ->whereBetween('date', array($request->StartDate, $request->EndDate))
        // ->where('PartyID',$request->PartyID)
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('journal.BranchID', $request->BranchID);
      })
        ->groupBy('PartyID', 'PartyName')
        ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
        ->get();
    }

    $pdf = PDF::loadView('reports.party_balance1PDF', compact('party'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return  View('party_balance1PDF', compact('party', 'pagetitle'));
  }

  public function PartyBalanceList()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party Balance', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Party Balance';

    $party = DB::table('v_party_balance')->select('PartyID', 'PartyName', DB::raw('sum(Dr) as Dr'), DB::raw('sum(Cr) as Cr'))
      // ->whereBetween('date',array($request->StartDate,$request->EndDate))
      // ->where('PartyID',$request->PartyID)
      ->groupBy('PartyID', 'PartyName')
      ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), '>', 0)
      ->orderByDesc(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'))
      ->get();

    return  View('party_balance1', compact('party', 'pagetitle'));
  }

  public function PartyYearlyBalance()
  {
    session::put('menu', 'SupplierBalance');
    $pagetitle = 'SupplierBalance';
    return view('reports.party_yearly_balance', compact('pagetitle'));
  }

  public  function PartyYearlyBalance1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Yearly Report', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'SupplierBalance');
    $pagetitle = 'SupplierBalance';

    $party = Party::getPartyList('C');

    return view('reports.party_yearly_balance1', compact('party'));
  }

  public  function PartyYearlyBalance1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Yearly Report', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'SupplierBalance');
    $pagetitle = 'SupplierBalance';

    $party = Party::getPartyList('C');
    $pdf = PDF::loadView('reports.party_yearly_balance1PDF', compact('party'));
    //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();

    return view('reports.party_yearly_balance1PDF', compact('party'));
  }

  // SUPPLIER REPORTS

  public function SupplierLedger()
  {

    session::put('menu', 'SupplierLedger');
    $pagetitle = 'Supplier Ledger';

    $supplier = Party::getSupplierList('V');

    

 
     return view('reports.supplier_ledger', compact('pagetitle', 'supplier'));
  }

  public function SupplierLedger1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Supplier Ledger', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());

    session::put('menu', 'SupplierLedger');
    $pagetitle = 'Supplier Ledger';

    $sql = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
      ->where('SupplierID', $request->SupplierID)
      ->whereIn('ChartOfAccountID', [110400,210100])
      ->where('Date', '<', $request->StartDate)
      // ->whereBetween('date',array($request->StartDate,$request->EndDate))

      ->get();

    // dd($sql[0]->Balance);
    // $sql= DB::select( DB::raw( 'SET @total := '.$sql[0]->Balance.''));
    // $sql= DB::select( DB::raw( 'select @total as t'));

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    // $a = DB::select(DB::raw('select * from v_journal where PartyID = @total'));
    // $journal = DB::select(DB::raw('SELECT a.JournalID, a.ChartOfAccountID, a.*, IF(ISNULL(a.Dr),0,a.Dr) as Dr, a.Cr,sum(if(ISNULL(b.Dr),0,b.Dr)-if(ISNULL(b.Cr),0,b.Cr))+'.$sql[0]->Balance.' as Balance FROM   v_journal a,  v_journal b WHERE b.JournalID <= a.JournalID and a.PartyID='.$request->PartyID.' and b.PartyID='.$request->PartyID.' and a.ChartOfAccountID=110400 and b.ChartOfAccountID=110400 GROUP BY a.JournalID, a.ChartOfAccountID, a.Dr, a.Cr ORDER BY a.JournalID'));
    // $a = DB::table('v_journal')->where('PartyID',DB::raw( '@total'))->get();

    $supplier = DB::table('party')->where('PartyID', $request->SupplierID)->get();

    $journal = DB::table('v_journal')->where('PartyID', $request->SupplierID)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->whereIn('ChartOfAccountID', [110400,210100] )
      ->orderBy('Date', 'asc')
      ->get();

    //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party')); 
    // //return $pdf->download('pdfview.pdf');
    //    $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
    return view('reports.supplier_ledger1', compact('journal', 'pagetitle', 'sql', 'supplier'));
  }

  function SupplierLedgerExcelExport(request $request)
  {

    return Excel::download(new SupplierLedgerExcel($request->SupplierID, $request->StartDate, $request->EndDate), 'supplierledger.xlsx');
  }

  public function SupplierLedger1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Supplier Ledger', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());

    session::put('menu', 'SupplierLedger');
    $pagetitle = 'Supplier Ledger';

    $sql = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
      ->where('PartyID', $request->SupplierID)
      ->whereIn('ChartOfAccountID', [110400,210100] )
      ->where('Date', '<', $request->StartDate)
      // ->whereBetween('date',array($request->StartDate,$request->EndDate))

      ->get();

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    $supplier = DB::table('party')->where('PartyID', $request->SupplierID)->get();

    $journal = DB::table('v_journal')->where('PartyID', $request->SupplierID)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->whereIn('ChartOfAccountID', [110400,210100] )

      ->orderBy('Date', 'asc')
      ->get();

    $pdf = PDF::loadView('reports.supplier_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'supplier'));
    // //return $pdf->download('pdfview.pdf');
    //    $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
    return view('supplier_ledger1', compact('journal', 'pagetitle', 'sql', 'supplier'));
  }

  public function SupplierWiseSale()
  {
    session::put('menu', 'SupplierLedger');
    $pagetitle = 'Supplier Ledger';
    $invoice_type = DB::table('invoice_type')->get();
    $supplier = DB::table('supplier')->get();
    $branches = Branch::getBranchList();

    return view('reports.supplierwise_sale', compact('pagetitle', 'invoice_type', 'supplier','branches'));
  }

  public function SupplierWiseSale1(request $request)
  {


     ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Sales Report', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Supplier wise sale';

    $query = DB::table('v_invoice_detail')
    ->select(
        'SupplierID', 
        'InvoiceTypeCode', 
        'SupplierName', 
        DB::raw('sum(Fare) as VHNO'), 
        DB::raw('sum(Taxable) as Taxable'), 
        DB::raw('sum(Service) as Service'), 
        DB::raw('sum(Fare) as Fare'), 
        DB::raw('sum(OPVAT) as OPVAT'), 
        DB::raw('sum(IPVAT) as IPVAT'), 
        DB::raw('sum(Discount) as Discount'), 
        DB::raw('sum(Total) as Total')
    )
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
      });

if ($request->SupplierID > 0) {
    $query->where('SupplierID', $request->SupplierID);
}

if ($request->BranchID > 0) {
  $query->where('BranchID', $request->BranchID);
}

if ($request->InvoiceTypeID !== 'both') {
    $query->where('InvoiceTypeID', $request->InvoiceTypeID);
}

$supplier = $query->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')->get();



    return View('reports.supplierwise_sale1', compact('supplier', 'pagetitle'));
  }

  public function SupplierWiseSale1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Sales Report', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    $pagetitle = 'Supplier wise sale';

    $query = DB::table('v_invoice_detail')
    ->select(
        'SupplierID', 
        'InvoiceTypeCode', 
        'SupplierName', 
        DB::raw('sum(Fare) as VHNO'), 
        DB::raw('sum(Taxable) as Taxable'), 
        DB::raw('sum(Service) as Service'), 
        DB::raw('sum(Fare) as Fare'), 
        DB::raw('sum(OPVAT) as OPVAT'), 
        DB::raw('sum(IPVAT) as IPVAT'), 
        DB::raw('sum(Discount) as Discount'), 
        DB::raw('sum(Total) as Total')
    )
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
      });

if ($request->SupplierID > 0) {
    $query->where('SupplierID', $request->SupplierID);
}

if ($request->BranchID > 0) {
  $query->where('BranchID', $request->BranchID);
}

if ($request->InvoiceTypeID !== 'both') {
    $query->where('InvoiceTypeID', $request->InvoiceTypeID);
}

$supplier = $query->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')->get();


    $pdf = PDF::loadView('reports.supplierwise_sale1pdf', compact('supplier', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return View('supplierwise_sale1', compact('supplier'));
  }

  public function TaxReport()
  {

    session::put('menu', 'TaxReport');
    $pagetitle = 'Tax Report';
    $invoice_type = DB::table('invoice_type')->get();
    $item = DB::table('item')->get();
    $branches = Branch::getBranchList();

    return view('reports.tax_report', compact('pagetitle', 'invoice_type', 'item','branches'));
  }

  public function TaxReport1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Tax Report', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Tax Report';


      $where = array();
      if ($request->ItemID > 0) 
      {
         $where = Arr::add($where, 'ItemID', $request->ItemID);


      }


       if ($request->InvoiceTypeID > 0) 
      {
         $where = Arr::add($where, 'InvoiceTypeID', $request->InvoiceTypeID);


      }

      if ($request->BranchID > 0) {
         $where = Arr::add($where, 'BranchID', $request->BranchID);
    }
 

    
      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where($where)
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
   

    return View('reports.tax_report1', compact('invoice_detail', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');

  }

  public function TaxReport1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Tax Report', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Tax Report';

    $query = DB::table('v_invoice_detail')
        ->whereBetween('Date', [$request->StartDate, $request->EndDate])
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date');

    if ($request->ItemID > 0) {
        $query->where('ItemID', $request->ItemID);
    }

    // Check if InvoiceTypeID is set and not empty
    if ($request->InvoiceTypeID > 0) {
        $query->where('InvoiceTypeID', $request->InvoiceTypeID);
    }

    if ($request->BranchID > 0) {
      $query->where('BranchID', $request->BranchID);
  }



    $invoice_detail = $query->get();

 

    $pdf = PDF::loadView('reports.tax_report1pdf', compact('invoice_detail', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();
  }

  public function SalemanInvoiceBalance()
  {

    session::put('menu', 'SalemanReport');
    $pagetitle = 'Saleman Invoice Balance';
    $invoice_type = DB::table('invoice_type')->whereIn('InvoiceTypeID',[1,2])->get();
    $item = DB::table('item')->get();
    $saleman = User::getUserList();


    return view('reports.saleman_invoice_balance', compact('pagetitle', 'invoice_type', 'saleman', 'item'));
  }

  public  function SalemanInvoiceBalance1(request $request)
    {

    $pagetitle = 'Saleman Invoice Balance';

    $invoice_master = DB::table('v_invoice_master')
    ->select('v_invoice_master.FullName', 
        DB::raw('SUM(v_invoice_master.Total) as Total'), 
        DB::raw('SUM(v_invoice_balance.Balance) as Balance'))
    ->join('v_invoice_balance', 'v_invoice_master.InvoiceMasterID', '=', 'v_invoice_balance.InvoiceMasterID')
    ->whereBetween('Date', array($request->StartDate, $request->EndDate))
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
      })
    ->groupBy('v_invoice_master.FullName')
    ->get();

    return view ('reports.saleman_invoice_balance1',compact('pagetitle','invoice_master'));
    }


  public  function SalemanInvoiceList($user,$start,$end)
  {

    $pagetitle = 'Saleman Invoice Balance';

    $invoice_master = DB::table('v_invoice_master')
    ->join('v_invoice_balance', 'v_invoice_master.InvoiceMasterID', '=', 'v_invoice_balance.InvoiceMasterID')
    ->whereBetween('Date', array($start, $end))
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
      })
    ->where('FullName',$user)
     ->get();

    return view ('reports.saleman_invoice_list1',compact('pagetitle','invoice_master'));
    }



  public function SalemanReport()
  {

    session::put('menu', 'SalemanReport');
    $pagetitle = 'Saleman Report';
    $invoice_type = DB::table('invoice_type')->whereIn('InvoiceTypeID',[1,2])->get();
    $item = DB::table('item')->get();
    $saleman = User::getUserList('Saleman');
    $branches = Branch::getBranchList();


    return view('reports.saleman_report', compact('pagetitle', 'invoice_type', 'saleman', 'item','branches'));
  }

  public function SalemanReport1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Sale Man Report', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    $pagetitle = 'Saleman Report';

    $query = DB::table('v_invoice_detail')
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
      })
    ->orderBy('InvoiceMasterID')
    ->orderBy('Date');

if ($request->UserID > 0) {
    $query->where('UserID', $request->UserID);
}

if ($request->BranchID > 0) {
  $query->where('BranchID', $request->BranchID);
}

if (in_array($request->InvoiceTypeID, [1, 2])) {
    $query->where('InvoiceTypeID', $request->InvoiceTypeID);
}

$invoice_detail = $query->get();


    //       $pdf = PDF::loadView ('saleman_report1',compact('invoice_detail','pagetitle'));
    // //return $pdf->download('pdfview.pdf');
    //   // $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();

    return View('reports.saleman_report1', compact('invoice_detail', 'pagetitle'));
  }

  public function SalemanReport1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Sale Man Report', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    $pagetitle = 'Saleman Report';

    $query = DB::table('v_invoice_detail')
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
      })
    ->orderBy('InvoiceMasterID')
    ->orderBy('Date');

if ($request->UserID > 0) {
    $query->where('UserID', $request->UserID);
}

if ($request->BranchID > 0) {
  $query->where('BranchID', $request->BranchID);
}


if (in_array($request->InvoiceTypeID, [1, 2])) {
    $query->where('InvoiceTypeID', $request->InvoiceTypeID);
}

$invoice_detail = $query->get();

    $pdf = PDF::loadView('reports.saleman_report1pdf', compact('invoice_detail'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();
  }

  public function AirlineSummary()
  {

    session::put('menu', 'AirlineSummary');
    $pagetitle = 'Airline Summary';
    $invoice_type = DB::table('invoice_type')->whereIn('InvoiceTypeID',[1,2])->get();
    $supplier = Party::getSupplierList();
    $branches = Branch::getBranchList();

    return view('reports.airline_summary', compact('pagetitle', 'invoice_type', 'supplier','branches'));
  }

  public function AirlineSummary1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Airline Summary', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    $pagetitle = 'Airline Summary';

    $query = DB::table('v_invoice_detail')
    ->select(
        'SupplierID',
        'InvoiceTypeCode',
        'SupplierName',
        DB::raw('sum(Fare) as VHNO'),
        DB::raw('sum(Taxable) as Taxable'),
        DB::raw('sum(Service) as Service'),
        DB::raw('sum(Fare) as Fare'),
        DB::raw('sum(OPVAT) as OPVAT'),
        DB::raw('sum(IPVAT) as IPVAT'),
        DB::raw('sum(Discount) as Discount'),
        DB::raw('sum(Total) as Total')
    )
    ->whereBetween('Date', [$request->StartDate, $request->EndDate]);

if ($request->SupplierID > 0) {
    $query->where('PartyID', $request->PartyID);
}

if ($request->BranchID > 0) {
    $query->where('BranchID', $request->BranchID);
}

if (in_array($request->InvoiceTypeID, [1, 2])) {
    $query->where('InvoiceTypeID', $request->InvoiceTypeID);
}

$supplier = $query
    ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
    ->get();


    // $pdf = PDF::loadView ('airline_summary1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('reports.airline_summary1', compact('supplier', 'pagetitle'));
  }

  public function AirlineSummary1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Airline Summary', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Airline Summary';

    $query = DB::table('v_invoice_detail')
    ->select(
        'SupplierID',
        'InvoiceTypeCode',
        'SupplierName',
        DB::raw('sum(Fare) as VHNO'),
        DB::raw('sum(Taxable) as Taxable'),
        DB::raw('sum(Service) as Service'),
        DB::raw('sum(Fare) as Fare'),
        DB::raw('sum(OPVAT) as OPVAT'),
        DB::raw('sum(IPVAT) as IPVAT'),
        DB::raw('sum(Discount) as Discount'),
        DB::raw('sum(Total) as Total')
    )
    ->whereBetween('Date', [$request->StartDate, $request->EndDate]);

if ($request->SupplierID > 0) {
    $query->where('PartyID', $request->PartyID);
}

if ($request->BranchID > 0) {
  $query->where('BranchID', $request->BranchID);
}



if (in_array($request->InvoiceTypeID, [1, 2])) {
    $query->where('InvoiceTypeID', $request->InvoiceTypeID);
}

$supplier = $query
    ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
    ->get();

    $pdf = PDF::loadView('reports.airline_summary1pdf', compact('supplier', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return View('reports.airline_summary1pdf', compact('supplier', 'pagetitle'));
  }

  public function VoucherReport()
  {
    session::put('menu', 'VoucherReport');
    $pagetitle = 'Voucher Report';
    $voucher_type = DB::table('voucher_type')->get();
    $branches = Branch::getBranchList();
    return view('reports.voucher_report', compact('pagetitle', 'voucher_type','branches'));
  }

  public function VoucherReport1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher Report', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'VoucherReport');
    $pagetitle = 'Voucher Report';

    // dd($request->all());
    // dd($request->VoucherTypeID);

    if ($request->VoucherTypeID == 0) {

      session::put('menu', 'VoucherReport');
      $pagetitle = 'Voucher Report';

      return redirect('VoucherReport')->with('error', 'Please select voucher type')->with('class', 'danger');
    }

    $voucher_type = DB::table('voucher_type')
      ->where('VoucherTypeID', $request->VoucherTypeID)
      ->get();

      $where=[];
      if ($request->BranchID > 0) {
            $where = Arr::add($where, 'BranchID', $request->BranchID);
       }
   

    $voucher_master = DB::table('voucher_master')
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->where('VoucherCodeID', $request->VoucherTypeID)
->where($where)
      ->get();

    return view('reports.voucher_report1', compact('pagetitle', 'voucher_type', 'voucher_master', 'pagetitle'));
  }

  public function VoucherReport1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher Report', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    session::put('menu', 'VoucherReport');
    $pagetitle = 'Voucher Report';

    // dd($request->all());
    // dd($request->VoucherTypeID);

    $voucher_type = DB::table('voucher_type')
      ->where('VoucherTypeID', $request->VoucherTypeID)
      ->get();

      $where=[];
      if ($request->BranchID > 0) {
            $where = Arr::add($where, 'BranchID', $request->BranchID);
       }
   

    $voucher_master = DB::table('voucher_master')
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->where('VoucherCodeID', $request->VoucherTypeID)
      ->where($where)
      ->get();

    // dd($voucher_master);

    $pdf = PDF::loadView('reports.voucher_report1pdf', compact('pagetitle', 'voucher_type', 'voucher_master'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('reports.voucher_report1pdf', compact('pagetitle', 'voucher_type', 'voucher_master'));
  }

  public function CashbookReport()
  {

    session::put('menu', 'CashbookReport');
    $pagetitle = 'Cashbook Report';
    $chartofaccount = DB::table('chartofaccount')
      ->whereIn('Category', ['CASH','BANK','CARD'])
      ->get();

      $branches = Branch::getBranchList();

    return view('reports.cashbook_report', compact('pagetitle', 'chartofaccount','branches'));
  }

  public function CashbookReport1(request $request)
  {

     ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Cash Book', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());

    session::put('menu', 'CashbookReport');
    $pagetitle = 'Cashbook Report';

    if ($request->ChartOfAccountID > 0) {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->where('Date', '<', $request->StartDate)
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
      })
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
      })
        ->orderBy('Date', 'asc')
        ->get();
    } else {

      $sql = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereIn('Category', ['CASH','BANK','CARD'])
        ->where('Date', '<', $request->StartDate)
        
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
      })

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
      })
        ->whereIn('Category', ['CASH','BANK','CARD'])
        ->orderBy('Date', 'asc')
        ->get();
    }

    // dd($sql[0]->Balance);
    // $sql= DB::select( DB::raw( 'SET @total := '.$sql[0]->Balance.''));
    // $sql= DB::select( DB::raw( 'select @total as t'));

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    // $a = DB::select(DB::raw('select * from v_journal where PartyID = @total'));
    // $journal = DB::select(DB::raw('SELECT a.JournalID, a.ChartOfAccountID, a.*, IF(ISNULL(a.Dr),0,a.Dr) as Dr, a.Cr,sum(if(ISNULL(b.Dr),0,b.Dr)-if(ISNULL(b.Cr),0,b.Cr))+'.$sql[0]->Balance.' as Balance FROM   v_journal a,  v_journal b WHERE b.JournalID <= a.JournalID and a.PartyID='.$request->PartyID.' and b.PartyID='.$request->PartyID.' and a.ChartOfAccountID=110400 and b.ChartOfAccountID=110400 GROUP BY a.JournalID, a.ChartOfAccountID, a.Dr, a.Cr ORDER BY a.JournalID'));
    // $a = DB::table('v_journal')->where('PartyID',DB::raw( '@total'))->get();

    // $supplier = DB::table('supplier')->where('SupplierID',$request->SupplierID)->get();

    //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party')); 
    // //return $pdf->download('pdfview.pdf');
    //    $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
    return view('reports.cashbook_report1', compact('journal', 'pagetitle', 'sql'));
  }

  public function CashbookReport1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Cash Book', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    // dd($request->all());

    session::put('menu', 'CashbookReport');
    $pagetitle = 'Cashbook Report';

    
    if ($request->ChartOfAccountID > 0) {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->where('Date', '<', $request->StartDate)
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
      })
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
      })
        ->orderBy('Date', 'asc')
        ->get();
    } else {

      $sql = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereIn('Category', ['CASH','BANK','CARD'])
        ->where('Date', '<', $request->StartDate)
        
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
      })

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
      })
        ->whereIn('Category', ['CASH','BANK','CARD'])
        ->orderBy('Date', 'asc')
        ->get();
    }

    // dd($sql[0]->Balance);
    // $sql= DB::select( DB::raw( 'SET @total := '.$sql[0]->Balance.''));
    // $sql= DB::select( DB::raw( 'select @total as t'));

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    // $a = DB::select(DB::raw('select * from v_journal where PartyID = @total'));
    // $journal = DB::select(DB::raw('SELECT a.JournalID, a.ChartOfAccountID, a.*, IF(ISNULL(a.Dr),0,a.Dr) as Dr, a.Cr,sum(if(ISNULL(b.Dr),0,b.Dr)-if(ISNULL(b.Cr),0,b.Cr))+'.$sql[0]->Balance.' as Balance FROM   v_journal a,  v_journal b WHERE b.JournalID <= a.JournalID and a.PartyID='.$request->PartyID.' and b.PartyID='.$request->PartyID.' and a.ChartOfAccountID=110400 and b.ChartOfAccountID=110400 GROUP BY a.JournalID, a.ChartOfAccountID, a.Dr, a.Cr ORDER BY a.JournalID'));
    // $a = DB::table('v_journal')->where('PartyID',DB::raw( '@total'))->get();

    // $supplier = DB::table('supplier')->where('SupplierID',$request->SupplierID)->get();

    $pdf = PDF::loadView('reports.cashbook_report1pdf', compact('journal', 'pagetitle', 'sql'));
    //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
    return view('reports.cashbook_report1pdf', compact('journal', 'pagetitle', 'sql'));
  }

  public function DaybookReport()
  {
    session::put('menu', 'CashbookReport');
    $pagetitle = 'Cashbook Report';
    $chartofaccount = DB::table('chartofaccount')
      ->whereIn('Category', ['BANK','CASH','CARD'])
      ->get();

    return view('reports.daybook_report', compact('pagetitle', 'chartofaccount'));
  }

  public function DaybookReport1(request $request)
  {
     ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Day Book', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());

    session::put('menu', 'CashbookReport');
    $pagetitle = 'Cashbook Report';

    $invoice_detail = DB::table('v_invoice_detail')
      ->whereBetween('date', array($request->StartDate, $request->EndDate))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
      ->get();

    $invoice_detail_summary = DB::table('v_invoice_detail')
      ->select(DB::raw('sum(if(ISNULL(Total),0,Total)) as Total'), DB::raw('sum(if(ISNULL(Fare),0,Fare)) as Fare'), DB::raw('sum(if(ISNULL(Service),0,Service)) as Service'))
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
      ->get();

    if ($request->ChartOfAccountID > 0) {

      $sql = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->get();
    } else {

      $sql = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
      ->whereIn('Category', ['BANK','CASH','CARD'])
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->whereIn('Category', ['BANK','CASH','CARD'])
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->whereIn('Category', ['BANK','CASH','CARD'])
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
        ->get();
    }

    // dd($sql[0]->Balance);
    // $sql= DB::select( DB::raw( 'SET @total := '.$sql[0]->Balance.''));
    // $sql= DB::select( DB::raw( 'select @total as t'));

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    // $a = DB::select(DB::raw('select * from v_journal where PartyID = @total'));
    // $journal = DB::select(DB::raw('SELECT a.JournalID, a.ChartOfAccountID, a.*, IF(ISNULL(a.Dr),0,a.Dr) as Dr, a.Cr,sum(if(ISNULL(b.Dr),0,b.Dr)-if(ISNULL(b.Cr),0,b.Cr))+'.$sql[0]->Balance.' as Balance FROM   v_journal a,  v_journal b WHERE b.JournalID <= a.JournalID and a.PartyID='.$request->PartyID.' and b.PartyID='.$request->PartyID.' and a.ChartOfAccountID=110400 and b.ChartOfAccountID=110400 GROUP BY a.JournalID, a.ChartOfAccountID, a.Dr, a.Cr ORDER BY a.JournalID'));
    // $a = DB::table('v_journal')->where('PartyID',DB::raw( '@total'))->get();

    // $supplier = DB::table('supplier')->where('SupplierID',$request->SupplierID)->get();

    //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party')); 
    // //return $pdf->download('pdfview.pdf');
    //    $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();
    // dd(count($invoice).'-'.count($journal));

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();

    if (count($invoice_detail) > count($journal)) {
      $row = count($invoice_detail);
    } else {
      $row = count($journal);
    }

    return view('reports.daybook_report1', compact('journal', 'pagetitle', 'sql', 'invoice_detail', 'row', 'invoice_detail_summary', 'journal_summary'));
  }

  public function DaybookReport1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Day Book', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());

    session::put('menu', 'CashbookReport');
    $pagetitle = 'Cashbook Report';

    $invoice_detail = DB::table('v_invoice_detail')
      ->whereBetween('date', array($request->StartDate, $request->EndDate))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
      ->get();

    $invoice_detail_summary = DB::table('v_invoice_detail')
      ->select(DB::raw('sum(if(ISNULL(Total),0,Total)) as Total'), DB::raw('sum(if(ISNULL(Fare),0,Fare)) as Fare'), DB::raw('sum(if(ISNULL(Service),0,Service)) as Service'))
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
      ->get();

    if ($request->ChartOfAccountID > 0) {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->get();
    } else {

      $sql = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
              ->whereIn('Category', ['BANK','CASH','CARD'])
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
              ->whereIn('Category', ['BANK','CASH','CARD'])
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
              ->whereIn('Category', ['BANK','CASH','CARD'])
        ->when(session('UserType') != 'SuperAdmin', function ($query) {
          return $query->where('BranchID', session('BranchID'));
          })
        ->get();
    }

    // dd($sql[0]->Balance);
    // $sql= DB::select( DB::raw( 'SET @total := '.$sql[0]->Balance.''));
    // $sql= DB::select( DB::raw( 'select @total as t'));

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    // $a = DB::select(DB::raw('select * from v_journal where PartyID = @total'));
    // $journal = DB::select(DB::raw('SELECT a.JournalID, a.ChartOfAccountID, a.*, IF(ISNULL(a.Dr),0,a.Dr) as Dr, a.Cr,sum(if(ISNULL(b.Dr),0,b.Dr)-if(ISNULL(b.Cr),0,b.Cr))+'.$sql[0]->Balance.' as Balance FROM   v_journal a,  v_journal b WHERE b.JournalID <= a.JournalID and a.PartyID='.$request->PartyID.' and b.PartyID='.$request->PartyID.' and a.ChartOfAccountID=110400 and b.ChartOfAccountID=110400 GROUP BY a.JournalID, a.ChartOfAccountID, a.Dr, a.Cr ORDER BY a.JournalID'));
    // $a = DB::table('v_journal')->where('PartyID',DB::raw( '@total'))->get();

    // $supplier = DB::table('supplier')->where('SupplierID',$request->SupplierID)->get();

    if (count($invoice_detail) > count($journal)) {
      $row = count($invoice_detail);
    } else {
      $row = count($journal);
    }

    $pdf = PDF::loadView('reports.daybook_report1pdf', compact('journal', 'pagetitle', 'sql', 'invoice_detail', 'row', 'invoice_detail_summary', 'journal_summary'));
    // //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();
    // dd(count($invoice).'-'.count($journal));

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();

    return view('reports.daybook_report1', compact('journal', 'pagetitle', 'sql', 'invoice_detail', 'row', 'invoice_detail_summary', 'journal_summary'));
  }

  public function GeneralLedger()
  {

    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';
    $chartofaccount = DB::table('chartofaccount')
      // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
      ->get();
      $branches = Branch::getBranchList();

    return view('reports.general_ledger', compact('pagetitle', 'chartofaccount','branches'));
  }

  public function GeneralLedger1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'General Ledger', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());

    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';

    if ($request->ChartOfAccountID > 0) {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->where('Date', '<', $request->StartDate)
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
         ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
         ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        ->get();
    } else {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
         ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->where('SupplierID',$request->SupplierID)
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
         ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
         ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->get();
    }

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    return view('reports.general_ledger1', compact('journal', 'pagetitle', 'sql', 'journal_summary'));
  }

  public function GeneralLedger1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'General Ledger', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());

    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';

    if ($request->ChartOfAccountID > 0) {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->get();
    } else {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->where('SupplierID',$request->SupplierID)
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->get();
    }

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    $pdf = PDF::loadView('reports.general_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'journal_summary'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('reports.general_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'journal_summary'));
  }

  public function TrialBalance()
  {
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';
    $chartofaccount = DB::table('v_chartofaccount')->get();
    $branches = Branch::getBranchList();

    return view('reports.trial_balance', compact('pagetitle', 'chartofaccount','branches'));
  }

  public function TrialBalance1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Trial Balance', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'Trial Balance';

    if ($request->ChartOfAccountID > 0) {

      $trial = DB::table('v_journal')
        ->select('ChartOfAccountID', 'ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))>=0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Debit'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))<0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Credit'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))

        ->where('ChartOfAccountID', 'LIKE', substr($request->ChartOfAccountID, 0, 1) . '%')
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        ->groupBy('ChartOfAccountID', 'ChartOfAccountName')
        ->get();

      // dd(substr($request->ChartOfAccountID,0,1));

    } else {
      $trial = DB::table('v_journal')
        ->select('ChartOfAccountID', 'ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))>=0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Debit'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))<0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Credit'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->where('ChartOfAccountID',$request->ChartOfAccountID)
        ->groupBy('ChartOfAccountID', 'ChartOfAccountName')
        ->get();
    }

    return view('reports.trial_balance1', compact('trial', 'pagetitle'));
  }

  public function TrialBalance1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Trial Balance', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'Trial Balance';

    if ($request->ChartOfAccountID > 0) {

      $trial = DB::table('v_journal')
        ->select('ChartOfAccountID', 'ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))>=0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Debit'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))<0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Credit'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })

        ->where('ChartOfAccountID', 'LIKE', substr($request->ChartOfAccountID, 0, 1) . '%')
        ->groupBy('ChartOfAccountID', 'ChartOfAccountName')
        ->get();

      // dd(substr($request->ChartOfAccountID,0,1));

    } else {
      $trial = DB::table('v_journal')
        ->select('ChartOfAccountID', 'ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))>=0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Debit'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))<0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Credit'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->where('ChartOfAccountID',$request->ChartOfAccountID)
        ->groupBy('ChartOfAccountID', 'ChartOfAccountName')
        ->get();
    }

    $pdf = PDF::loadView('reports.trial_balance1pdf', compact('trial', 'pagetitle'));

    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('reports.trial_balance1pdf', compact('trial', 'pagetitle'));
  }

  public function TicketRegister()
  {

    session::put('menu', 'AirlineSummary');
    $pagetitle = 'Airline Summary';
    $invoice_type = DB::table('invoice_type')->get();
    $item = DB::table('item')->get();
    $saleman = User::getUserList('Saleman');
    
     $supplier = Party::getSupplierList();
     
     return view('reports.ticket_register', compact('pagetitle', 'invoice_type', 'supplier', 'item', 'saleman'));
  }

  public function TicketRegister1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Ticket Register', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Ticket Register';
    $where = array();

    if (($request->InvoiceTypeID == 1) || ($request->InvoiceTypeID == 2)) {

      $where = array('InvoiceTypeID' => $request->InvoiceTypeID);
    }

    if ($request->SupplierID > 0) {

      $where = Arr::add($where, 'PartyID', $request->PartyID);
    }

    if ($request->ItemID > 0) {

      $where = Arr::add($where, 'ItemID', $request->ItemID);
    }

    if ($request->UserID > 0) {

      $where = Arr::add($where, 'UserID', $request->UserID);
    }

    $invoice_detail = DB::table('v_invoice_detail')
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })  
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();

    $invoice_summary = DB::table('v_invoice_detail1')
      ->select(DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })  
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();

    // $pdf = PDF::loadView ('airline_summary1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('reports.ticket_register1', compact('invoice_detail', 'invoice_summary', 'pagetitle'));
  }

  public function TicketRegister1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Ticket Register', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $where = array();

    if ($request->InvoiceTypeID > 0) {

      $where = array('InvoiceTypeID' => $request->InvoiceTypeID);
    }

    if ($request->SupplierID > 0) {

      $where = Arr::add($where, 'PartyID', $request->PartyID);
    }

    if ($request->ItemID > 0) {

      $where = Arr::add($where, 'ItemID', $request->ItemID);
    }

    if ($request->UserID > 0) {

      $where = Arr::add($where, 'UserID', $request->UserID);
    }

    $invoice_detail = DB::table('v_invoice_detail')
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
      })      
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();

    $invoice_summary = DB::table('v_invoice_detail')
      ->select(DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Service)-sum(Taxable) as Profit'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();

    $pdf = PDF::loadView('reports.ticket_register1pdf', compact('invoice_detail', 'invoice_summary'));
    //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();

    return View('reports.ticket_register1pdf', compact('invoice_detail', 'invoice_summary'));
  }

  public function TrialBalanceActivity()
  {
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';
    $chartofaccount = DB::table('v_chartofaccount')

      ->get();

    return view('reports.trial_balance_activity', compact('pagetitle', 'chartofaccount'));
  }

  public function TrialBalanceActivity1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Trial with Activity', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'Trial Balance';

    $chartofaccount = DB::table('chartofaccount')
      ->select('ChartOfAccountID', 'ChartOfAccountName')

      ->whereIn('ChartOfAccountID', function ($query) use ($request) {
        $query->select('ChartOfAccountID')
          ->from('journal')
          ->whereBetween('Date', [$request->StartDate, $request->EndDate])
          ->when(session('UserType') != 'SuperAdmin', function ($query) {
            return $query->where('BranchID', session('BranchID'));
            });
      })
      ->orWhereIn('ChartOfAccountID', function ($query) use ($request) {
        $query->select('ChartOfAccountID')
          ->from('journal')
          ->where('Date', '<', $request->StartDate);
      })
      ->get();

    return view('reports.trial_balance_activity1', compact('chartofaccount', 'pagetitle'));
  }

  public function TrialBalanceActivity1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Trial with Activity', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    // dd($request->all());
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'Trial Balance';

    $chartofaccount = DB::select('SELECT ChartOfAccountID,ChartOfAccountName from chartofaccount where ChartOfAccountID in (select ChartOfAccountID from journal where Date between "' . $request->StartDate . '" and "' . $request->EndDate . '") union SELECT ChartOfAccountID,ChartOfAccountName from chartofaccount where ChartOfAccountID in (select ChartOfAccountID from journal where Date < "' . $request->StartDate . '"   )');

    $pdf = PDF::loadView('reports.trial_balance_activity1pdf', compact('chartofaccount', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('reports.trial_balance_activity1pdf', compact('chartofaccount', 'pagetitle'));
  }

  public function InvoiceSummary()
  {

    session::put('menu', 'AirlineSummary');
    $pagetitle = 'Airline Summary';
    $invoice_type = DB::table('invoice_type')->whereIn('InvoiceTypeID',[1,2])->get();

    $user = User::getUserList('Saleman');

    return view('reports.invoice_summary', compact('pagetitle', 'invoice_type', 'user'));
  }

  public function InvoiceSummary1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice Summary', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Invoice Summary';

    $where = array();

  


      $where = array();
      if ($request->UserID > 0) 
      {
         $where = Arr::add($where, 'UserID', $request->UserID);


      }


       if ($request->InvoiceTypeID > 0) 
      {
         $where = Arr::add($where, 'InvoiceTypeID', $request->InvoiceTypeID);


      }






    $invoice_summary = DB::table('v_invoice_detail')
      ->select('SalemanName', 'UserID', DB::raw('count(InvoiceDetailID) as Qty'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Service)-sum(Taxable) as Profit'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
      ->groupBy('SalemanName', 'UserID')
      ->orderBy('Date')
      ->get();

    $invoice_total = DB::table('v_invoice_detail')
      ->select(DB::raw('count(InvoiceDetailID) as Qty'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Service)-sum(Taxable) as Profit'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        })
      ->orderBy('Date')
      ->get();

    // $pdf = PDF::loadView ('airline_summary1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('reports.invoice_summary1', compact('invoice_summary', 'invoice_total', 'pagetitle'));
  }

  public function InvoiceSummary1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice Summary', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Invoice Summary';

    $where = array();

    if ($request->InvoiceTypeID > 0) {

      $where = array('InvoiceTypeID' => $request->InvoiceTypeID);
    }

    if ($request->UserID > 0) {

      $where = Arr::add($where, 'UserID', $request->UserID);
    }

    $invoice_summary = DB::table('v_invoice_detail')
      ->select('SalemanName', 'UserID', DB::raw('count(InvoiceDetailID) as Qty'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Service)-sum(Taxable) as Profit'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->groupBy('SalemanName', 'UserID')
      ->orderBy('Date')
      ->get();

    $invoice_total = DB::table('v_invoice_detail')
      ->select(DB::raw('count(InvoiceDetailID) as Qty'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Service)-sum(Taxable) as Profit'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))

      ->orderBy('Date')
      ->get();

    $pdf = PDF::loadView('reports.invoice_summary1pdf', compact('invoice_summary', 'invoice_total', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return View('reports.invoice_summary1', compact('invoice_summary', 'invoice_total', 'pagetitle'));
  }

  public  function tmp()
  {

    return view('tmp');
  }

  public  function ProfitAndLoss()
  {

    $pagetitle = 'Proft & Loss';

    return view('reports.profit_loss', compact('pagetitle'));
  }

  public  function ProfitAndLoss1(request $request)
  {

    $pagetitle = 'Proft & Loss';

    $chartofaccountr = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "R"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');

    $chartofaccounte = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "E"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');

    //where Date between "'.$request->StartDate.'" and "'.$request->EndDate.'"
    return view('reports.profit_loss11_ok', compact('chartofaccountr', 'chartofaccounte', 'pagetitle'));
  }



  public function ProfitAndLoss11111(Request $request)
{
    $pagetitle = 'Profit & Loss';

    $startDate = $request->StartDate ?? date('Y-m-01');
    $endDate = $request->EndDate ?? date('Y-m-d');

    // Get all months between start and end date
    $months = $this->getMonthsBetweenDates($startDate, $endDate);
    
    $accounts = DB::table('chartofaccount')->where('CODE', 'A')->where('Level', 3)->get();

    $data = [];
    foreach ($accounts as $account) {
        $accountData = [
            'name' => $account->ChartOfAccountName,
            'monthly_balances' => []
        ];

        // Get balance for each month
        foreach ($months as $month) {
            $monthStart = $month['start'];
            $monthEnd = $month['end'];

            $query = DB::table('journal')
                ->where('ChartOfAccountID', $account->ChartOfAccountID)
                ->whereBetween('Date', [$monthStart, $monthEnd])
                ->selectRaw('
                    SUM(COALESCE(DR, 0)) as debit,
                    SUM(COALESCE(CR, 0)) as credit,
                    SUM(COALESCE(DR, 0) - COALESCE(CR, 0)) as balance
                ')
                ->first();

            $accountData['monthly_balances'][$month['label']] = $query->balance ?? 0;
        }

        $data[] = $accountData;
    }


    return view('reports.profit_loss11', compact('pagetitle', 'data', 'months'));
}

public function getMonthsBetweenDates($startDate, $endDate)
{

 
    $months = [];
    $start = Carbon::parse($startDate);
    $end = Carbon::parse($endDate);

    while ($start->lte($end)) {
        $months[] = [
            'label' => $start->format('M Y'), // Jan 2025
            'start' => $start->copy()->startOfMonth()->format('Y-m-d'),
            'end' => $start->copy()->endOfMonth()->format('Y-m-d')
        ];
        $start->addMonth();
    }

    return $months;
}

  public  function BalanceSheet()
  {

    $pagetitle = 'Balance Sheet';

    return view('reports.balance_sheet', compact('pagetitle'));
  }

  public  function BalanceSheet1(request $request)
  {
    $pagetitle = 'Balance Sheet';

    //profit and loss total will be used in balance sheet
    $activityr = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
      ->whereBetween('Date', array(request()->StartDate, request()->EndDate))
      ->where('CODE', 'R')
      ->when($request->BranchID > 0, function ($query) use ($request) {
        return $query->where('BranchID', $request->BranchID);
        })
      ->get();
    $activitye = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance'))
      ->whereBetween('Date', array(request()->StartDate, request()->EndDate))
      ->where('CODE', 'E')
      ->when($request->BranchID > 0, function ($query) use ($request) {
        return $query->where('BranchID', $request->BranchID);
        })
      ->get();
    $profit_loss = $activityr[0]->Balance - $activitye[0]->Balance;
    // end of profit of loss balance

    $chartofaccounta = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "A"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');

    $chartofaccountl = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "L"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');
    $chartofaccountc = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "C"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');
    $chartofaccounts = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "S"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');

    //where Date between "'.$request->StartDate.'" and "'.$request->EndDate.'"
    return view('reports.balance_sheet11', compact('chartofaccounta', 'chartofaccountl', 'chartofaccountc', 'chartofaccounts', 'pagetitle', 'profit_loss'));
  }

  public function checkUserRole($UserID)
  {

    $role = DB::table('user_role')->where('UserID', $UserID)->get();

    if (count($role) > 0) {

      return redirect('RoleView/' . $UserID)->with('error', '$2 updated Successfully')->with('class', 'success');
    } else {

      return redirect('Role/' . $UserID)->with('error', '$2 updated Successfully')->with('class', 'success');
    }
  }

  public  function Logout()
  {
    Session::flush(); // removes all session data
    return redirect('/')->with('error', 'Logout Successfully.')->with('class', 'success');
  }

  public  function BalanceSheetDetail($ChartOfAccountID, $StartDate, $EndDate)
  {

    $pagetitle = 'Journal Detail';
    $company = DB::table('company')->get();
    $journal = DB::table('v_journal')
      ->where('ChartOfAccountID', $ChartOfAccountID)
      ->whereBetween('Date', array($StartDate, $EndDate))
      ->orderBy('Date', 'asc')
      ->get();

    return view('reports.balancesheet_detail', compact('company', 'journal', 'pagetitle', 'StartDate', 'EndDate'));
  }

  public  function JournalEntries($ChartOfAccountID, $StartDate, $EndDate)
  {

    $pagetitle = 'Journal Detail';
    $company = DB::table('company')->get();
    $journal = DB::table('v_journal')
      ->where('ChartOfAccountID', $ChartOfAccountID)
      ->whereBetween('Date', array($StartDate, $EndDate))
      ->orderBy('Date', 'asc')
      ->get();

    return view('reports.balancesheet_detail', compact('company', 'journal', 'pagetitle', 'StartDate', 'EndDate'));
  }

  public function ReconcileReport()
  {
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';
    $chartofaccount = DB::table('chartofaccount')
      ->whereIn('Category', ['BANK', 'CARD', 'CASH'])
      ->orderBy('ChartOfAccountName', 'ASC')
      ->get();
      $branches = Branch::getBranchList();
    return view('reports.reconcile_report', compact('pagetitle', 'chartofaccount','branches'));
  }

  public function ReconcileReport1(request $request)
  {
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    

    ////////////////////////////END SCRIPT ////////////////////////////////////////////////
    // dd($request->all());

    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';

    if ($request->ChartOfAccountID > 0) {
      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->where('Date', '<', $request->StartDate)
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))
        ->get();
      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        ->orderBy('Date', 'asc')
        ->get();
      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        ->get();
    } else {
      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->where('Date', '<', $request->StartDate)
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))
        ->get();
      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->orderBy('Date', 'asc')
        ->get();
      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->when($request->BranchID > 0, function ($query) use ($request) {
          return $query->where('BranchID', $request->BranchID);
          })
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->get();
    }

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    return view('reports.reconcile_report1', compact('journal', 'pagetitle', 'sql', 'journal_summary'));
  }

  public function ReconcileUpdate($status, $id)
  {

    $data = array('BankReconcile' => $status);

    $id = DB::table('journal')->where('JournalID', $id)->update($data);

    return 'Update Done';
  }

  public  function Ajax_ReconcileStatus(request $request)
  {

    $ids =  $request->input('ids');
    $data = array(
      'BankReconcile' => $request->input('status'),
    );

    $id = DB::table('journal')->whereIn('JournalID', $ids)->update($data);

    $status = 'Success';
    $message = 'Update Done';

    return response()->json(['success' => $status, 'message' => $message]);
  }

  // ===============Expense Section function==================
  public  function ExpenseCreate()
  {
    $pagetitle = 'Expense';

    session::put('menu', 'Expense');
    $chartofaccont = DB::table('chartofaccount')->get();
    $items = DB::table('chartofaccount')->where('Level', '3')->get();
    // $items = DB::table('chartofaccount')->where(DB::raw('right(L3,3)'),'<>',000)->get();

    $item = json_encode($items);
    // dd($item);
    // $supplier = DB::table('supplier')->get();
    $supplier= Party::getSupplierList();
    $tax = DB::table('tax')->get();

    // $tax = DB::table('tax')->get();
    $user = DB::table('user')->get();
    $invoice_type = DB::table('invoice_type')->get();

    $vhno = DB::table('expense_master')
      ->select(DB::raw('LPAD(IFNULL(MAX(right(ExpenseNo,5)),0)+1,5,0) as VHNO '))->whereIn(DB::raw('left(ExpenseNo,3)'), ['EXP'])->get();

    session::put('VHNO', 'EXP-' . $vhno[0]->VHNO);

    return view('expense.expensecreate', compact('invoice_type', 'chartofaccont', 'tax', 'items', 'vhno', 'supplier', 'pagetitle', 'item', 'user'));
  }

  public function ExpenseSave(Request $request)

  {

    session::put('menu', 'Expense');
    $pagetitle = 'Invoice';

    $expense_mst = array(
      'ExpenseNo' => $request->ExpenseNo,
      'Date' => $request->Date,
      'ChartOfAccountID' => $request->ChartOfAccountID_From,
      'SupplierID' => $request->SupplierID,
      'ReferenceNo' => $request->ReferenceNo,
      'Tax' => $request->grandtotaltax,
      'GrantTotal' => $request->Grandtotal,
      'SubTotal' => $request->SubTotal,
      'BranchID' => session('BranchID'),

      'Paid' => $request->amountPaid,
    );
    // dd($challan_mst);
    // $id= DB::table('')->insertGetId($data);

    $ExpenseMasterID = DB::table('expense_master')->insertGetId($expense_mst);
    // dd($ExpenseMasterID);

    // JOURNAL ENTRY 
    //bank debit
    $bank_cash = array(
      'VHNO' => $request->ExpenseNo,
      'ChartOfAccountID' => $request->ChartOfAccountID_From,   // Cash / bank / credit card
      'SupplierID' => $request->input('SupplierID'),
      'ExpenseMasterID' => $ExpenseMasterID,
      
      'Date' => $request->input('Date'),

      'Cr' => $request->Grandtotal,
      'Narration' => $request->ReferenceNo,
      'BranchID' => session('BranchID'),

      'Trace' => 614
    );
    $journal_entry = DB::table('journal')->insertGetId($bank_cash);

    //  start for item array from invoice
    for ($i = 0; $i < count($request->ItemID0); $i++) {
      $expense_detail = array(
        'ExpenseMasterID' =>  $ExpenseMasterID,
        'ChartOfAccountID' => $request->ChartOfAccountID[$i],
        'Notes' => $request->Description[$i],
        'TaxPer' => $request->Tax[$i],
        'Tax' => $request->TaxVal[$i],
        'Amount' => $request->Amount[$i],
        'AmountAfterTax' => $request->AmountAfterTax[$i],
        'BranchID' => session('BranchID'),


      );

      $id = DB::table('expense_detail')->insertGetId($expense_detail);

      if ($request->Tax[$i] > 0) {

        // A/P debit
        $ar_payment = array(
          'VHNO' => $request->ExpenseNo,
          'ChartOfAccountID' => $request->ChartOfAccountID[$i],  // chart of account direct debit
          'SupplierID' => $request->input('SupplierID'),
          'ExpenseMasterID' => $ExpenseMasterID,
          'Date' => $request->input('Date'),
          'Dr' => $request->Amount[$i],
          'Narration' => $request->Description[$i],
          'BranchID' => session('BranchID'),

          'Trace' => 615
        );

        $journal_entry1 = DB::table('journal')->insertGetId($ar_payment);

        //tax grandtotaltax

        // Tax Payable debit
        $ar_payment = array(
          'VHNO' => $request->ExpenseNo,
          'ChartOfAccountID' => 110606,  // VAT 
          'SupplierID' => $request->input('SupplierID'),
          'ExpenseMasterID' => $ExpenseMasterID,
          'Date' => $request->input('Date'),
          'Dr' => $request->TaxVal[$i],
          'Narration' => $request->Description[$i],
          'BranchID' => session('BranchID'),

          'Trace' => 617
        );

        $journal_entry11 = DB::table('journal')->insertGetId($ar_payment);
      } else {

        // debit entry
        $ar_payment = array(
          'VHNO' => $request->ExpenseNo,
          'ChartOfAccountID' => $request->ChartOfAccountID[$i],
          'SupplierID' => $request->input('SupplierID'),
          'ExpenseMasterID' => $ExpenseMasterID,
          'Date' => $request->input('Date'),
          'Dr' => $request->AmountAfterTax[$i],
          'Narration' => $request->Description[$i],
          'BranchID' => session('BranchID'),

          'Trace' => 615
        );

        $journal_entry1 = DB::table('journal')->insertGetId($ar_payment);
      }
    }

    // end payment received

    // END OF JOURNAL ENTRY

    return view('expense.expense', compact('pagetitle'));
  }

  public  function Expense()
  {
    session::put('menu', 'Expense');
    $pagetitle = 'Invoice';

    return view('expense.expense', compact('pagetitle'));
  }

  public function ajax_Expense(Request $request)

  {
    session::put('menu', 'Expense');
    $pagetitle = 'Expense';
    if ($request->ajax()) {
 
       $query = DB::table('v_expense_detail')
       ->when(session('UserType') != 'SuperAdmin', function ($query) {
        return $query->where('BranchID', session('BranchID'));
        });

      // Apply filters if they are present in the request
      // if ($request->has('item_name') && !empty($request->item_name)) {
      //     $query->where('ItemName', 'like', '%' . $request->item_name . '%');
      // }

      if ($request->has('ReferenceNo') && !empty($request->ReferenceNo)) {
        $query->where('ReferenceNo', 'like', '%' . $request->ReferenceNo . '%');
      }
      if ($request->has('ChartOfAccountID') && !empty($request->ChartOfAccountID)) {
        $query->where('ChartOfAccountID', 'like', '%' . $request->ChartOfAccountID . '%');
      }

      if ($request->has('startdate') && !empty($request->startdate)) {
        $query->whereDate('Date', '>=', $request->startdate);
      }
      if ($request->has('enddate') && !empty($request->enddate)) {
        $query->whereDate('Date', '<=', $request->enddate);
      }


      $data = $query->orderBy('Date','desc')->get();

        

      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          // if you want to use direct link instead of dropdown use this line below
          // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>

          $btn = '

            <div class="d-flex align-items-center col-actions">

            
            <a href="' . URL('/ExpenseViewPDF/' . $row->ExpenseMasterID) . '"><i class="font-size-18 me-1 mdi mdi-file-pdf-outline align-middle me-1 text-secondary"></i></a>
            <a href="' . URL('/ExpenseEdit/' . $row->ExpenseMasterID) . '"><i class="font-size-18 bx bx-pencil align-middle me-1 text-secondary"></i></a>

            <a href="javascript:void(0)" onclick="delete_invoice(' . $row->ExpenseMasterID . ')" ><i class="font-size-18 bx bx-trash align-middle me-1 text-secondary"></i></a>

            </div>
            ';

          //class="edit btn btn-primary btn-sm"
          // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    return view('expense.expense', 'pagetitle');
  }

  public function  ExpenseEdit($id)
  {

    $pagetitle = 'Expense';

    session::put('menu', 'Expense');

    $chartofaccount = DB::table('chartofaccount')->get();
    // $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(L3,3)'),'<>',000)->get();

    // $supplier = DB::table('supplier')->get();
    $supplier= Party::getSupplierList();
    $tax = DB::table('tax')->get();

    // $tax = DB::table('tax')->get();
    $user = DB::table('user')->get();

    $items = DB::table('chartofaccount')->where('Level', '3')->get();

    $expense_master = DB::table('expense_master')->where('ExpenseMasterID', $id)->get();

    session::put('VHNO', $expense_master[0]->ExpenseNo);

    $expense_detail = DB::table('expense_detail')->where('ExpenseMasterID', $id)->get();

    return view('expense.expense_edit', compact('tax', 'supplier', 'pagetitle', 'expense_master', 'chartofaccount', 'expense_detail', 'items'));
  }

  public function ExpenseUpdate(request $request)
  {

    session::put('menu', 'Expense');
    $pagetitle = 'Invoice';

    $expense_mst = array(
      'ExpenseNo' => $request->ExpenseNo,
      'Date' => $request->Date,
      'ChartOfAccountID' => $request->ChartOfAccountID_From,
      'SupplierID' => $request->SupplierID,
      'ReferenceNo' => $request->ReferenceNo,
      'Tax' => $request->grandtotaltax,
      'SubTotal' => $request->SubTotal,
      'GrantTotal' => $request->Grandtotal,
      'Paid' => $request->amountPaid,
       'BranchID' => session('BranchID'),
    );
    // dd($challan_mst);
    // $id= DB::table('')->insertGetId($data);

    $ExpenseMasterID = DB::table('expense_master')->where('ExpenseMasterID', $request->ExpenseMasterID)->update($expense_mst);
    // dd($InvoiceMasterID);

    $idd = DB::table('expense_detail')->where('ExpenseMasterID', $request->ExpenseMasterID)->delete();

    $id2 = DB::table('journal')->where('ExpenseMasterID', $request->ExpenseMasterID)->delete();

    // JOURNAL ENTRY 
    //bank debit
    $bank_cash = array(
      'VHNO' => $request->ExpenseNo,
      'ChartOfAccountID' => $request->ChartOfAccountID_From,   // Cash / bank / credit card
      'SupplierID' => $request->input('SupplierID'),
      'ExpenseMasterID' => $request->ExpenseMasterID,

      'Date' => $request->input('Date'),

      'Cr' => $request->Grandtotal,
      'Narration' => $request->ReferenceNo,
      'Trace' => 614,
       'BranchID' => session('BranchID'),
    );
    $journal_entry = DB::table('journal')->insertGetId($bank_cash);

    //  start for item array from invoice
    for ($i = 0; $i < count($request->ItemID0); $i++) {
      $expense_detail = array(
        'ExpenseMasterID' =>  $request->ExpenseMasterID,
        'ChartOfAccountID' => $request->ChartOfAccountID[$i],
        'Notes' => $request->Description[$i],
        'TaxPer' => $request->Tax[$i],
        'Tax' => $request->TaxVal[$i],
        'Amount' => $request->Amount[$i],
        'AmountAfterTax' => $request->AmountAfterTax[$i],
         'BranchID' => session('BranchID'),

      );

      $id = DB::table('expense_detail')->insertGetId($expense_detail);

      if ($request->Tax[$i] > 0) {

        // A/P debit
        $ar_payment = array(
          'VHNO' => $request->ExpenseNo,
          'ChartOfAccountID' => $request->ChartOfAccountID[$i],  // chart of account direct debit
          'SupplierID' => $request->input('SupplierID'),
          'ExpenseMasterID' => $request->ExpenseMasterID,
          'Date' => $request->input('Date'),
          'Dr' => $request->Amount[$i],
          'Narration' => $request->Description[$i],
          'Trace' => 615,
           'BranchID' => session('BranchID'),
        );

        $journal_entry1 = DB::table('journal')->insertGetId($ar_payment);

        //tax grandtotaltax

        // Tax Payable debit
        $ar_payment = array(
          'VHNO' => $request->ExpenseNo,
          'ChartOfAccountID' => 110606,  // VAT 
          'SupplierID' => $request->input('SupplierID'),
          'ExpenseMasterID' => $request->ExpenseMasterID,
          'Date' => $request->input('Date'),
          'Dr' => $request->TaxVal[$i],
          'Narration' => $request->Description[$i],
          'Trace' => 617,
           'BranchID' => session('BranchID'),
        );

        $journal_entry11 = DB::table('journal')->insertGetId($ar_payment);
      } else {

        // debit entry
        $ar_payment = array(
          'VHNO' => $request->ExpenseNo,
          'ChartOfAccountID' => $request->ChartOfAccountID[$i],
          'SupplierID' => $request->input('SupplierID'),
          'ExpenseMasterID' => $request->ExpenseMasterID,
          'Date' => $request->input('Date'),
          'Dr' => $request->AmountAfterTax[$i],
          'Narration' => $request->Description[$i],
          'Trace' => 615,
           'BranchID' => session('BranchID'),
        );

        $journal_entry1 = DB::table('journal')->insertGetId($ar_payment);
      }
    }

    // end payment received

    // END OF JOURNAL ENTRY

    return view('expense.expense', compact('pagetitle'));
  }

  public function ExpenseView($id)
  {

    $pagetitle = 'Expense View ';
    $company = DB::table('company')->get();
    $expense_master = DB::table('v_expense')->where('ExpenseMasterID', $id)->get();
    $expense_detail = DB::table('v_expense_detail')->where('ExpenseMasterID', $id)->get();
    $journal = DB::table('journal')->where('ExpenseMasterID', $id)->get();

    $pdf = PDF::loadView('expense.expense_view_pdf', compact('expense_master', 'expense_detail', 'pagetitle', 'company'));
    $pdf->set_option('isPhpEnabled', true);
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();
  }

  public function ExpenseViewPDF($id)
  {

    $pagetitle = 'Expense View ';
    $company = DB::table('company')->get();
    $expense_master = DB::table('v_expense')->where('ExpenseMasterID', $id)->get();
    $expense_detail = DB::table('v_expense_detail')->where('ExpenseMasterID', $id)->get();
    $journal = DB::table('journal')->where('ExpenseMasterID', $id)->get();

     
    $pdf = PDF::loadView('expense.expense_view_pdf', compact('expense_master', 'expense_detail', 'pagetitle', 'company'));
    $pdf->set_option('isPhpEnabled', true);
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();
  }

  public function ExpenseDelete($id)
  {

    $id = DB::table('expense_master')->where('ExpenseMasterID', $id)->delete();
    $id2 = DB::table('expense_detail')->where('ExpenseMasterID', $id)->delete();
    $id3 = DB::table('journal')->where('ExpenseMasterID', $id)->delete();

    return redirect('Expense')->with('error', 'Deleted Successfully')->with('class', 'success');
  }

  public function Salesman()
  {

    $pagetitle = 'Salesman';

    $salesman = DB::table('saleman')->get();

    return view('salesman.salesman', compact('salesman', 'pagetitle'));
  }

  public function SalesmanSave(request $request)
  {

    $data = array(

      'SalemanName' => $request->SalemanName,
      'Mobile' => $request->Mobile,
      'Address' => $request->Address,

    );

    $id = DB::table('saleman')->insertGetId($data);

    return redirect('Salesman')->with('error', 'DATA SAVED')->with('class', 'success');
  }

  public function SalesmanEdit($id)
  {

    $pagetitle = 'Salesman';

    $salesman = DB::table('saleman')->where('SalemanID', $id)->get();

    return view('salesman.salesman_edit', compact('salesman', 'pagetitle'));
  }

  public function SalesmanUpdate(request $request)
  {

    $data = array(

      'SalemanName' => $request->SalemanName,
      'Mobile' => $request->Mobile,
      'Address' => $request->Address,

    );

    $id = DB::table('saleman')->where('SalemanID', '=', $request->SalemanID)->update($data);

    return redirect('Salesman')->with('error', 'DATA UPDATED')->with('class', 'success');
  }

  public  function SalesmanDelete($id)
  {

    $id = DB::table('saleman')->where('SalemanID', $id)->delete();

    return redirect('Salesman')->with('error', 'Deleted Successfully.')->with('class', 'success');
  }

  public function JVSave(request $request)
  {

    // dd($request->all());
    $voucher_mst = array(
      'VoucherCodeID' => $request->input('VoucherType'),
      'Voucher' => $request->input('Voucher'),
      'Narration' => $request->input('Narration_mst'),
      'Date' => $request->input('VHDate'),

    );

    // dd($invoice_mst);

    // $id= DB::table('')->insertGetId($data);

    $id = DB::table('voucher_master')->insertGetId($voucher_mst);

    for ($i = 0; $i < count($request->ChOfAcc); $i++) {

      $voucher_det_dr = array(
        'VoucherMstID' => $id,
        'Voucher' => $request->input('Voucher'),
        'Date' =>  $request->input('VHDate'),
        'ChOfAcc' => $request->ChOfAcc[$i],
        'SupplierID' => $request->SupplierID[$i],
        'PartyID' => $request->PartyID[$i],
        'Narration' => $request->Narration[$i],
        'InvoiceNo' => $request->Invoice[$i],
        'RefNo' => $request->RefNo[$i],
        'Debit' => $request->Debit[$i],
        'Credit' => $request->Credit[$i],

      );

      $id2 = DB::table('voucher_detail')->insert($voucher_det_dr);


    // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i],
      'Date' => date('Y-m-d H:i:s'), 
      'Section' => 'JV Created', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' => $request->input('Narration_mst'), 
      'Trace' => 3301,
      'UserID' => session::get('UserID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 


    }
    // end for each

    return redirect('Voucher')->with('error', 'Record Saved')->with('class', 'success');
  }

  public function PartySalesLedger3($PartyID)
  {

     $pagetitle = 'Party Sale Ledger';

    $sql = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
      ->where('PartyID', $PartyID)
      ->where('ChartOfAccountID', 110400)
      // ->where('Date', '<', $StartDate)
      // ->whereBetween('date', array($StartDate, $EndDate))
      ->get();

    $journal = DB::table('v_journal')
      ->where('PartyID', $PartyID)
      // ->whereBetween('Date', array($StartDate, $EndDate))
      ->where('ChartOfAccountID', 110400)
      ->orderBy('Date')
      ->get();

    $company = DB::table('company')->get();

    $party = DB::table('party')->where('PartyID', $PartyID)->get();

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    return View('reports.party_sales_ledger2pdf', compact('journal', 'pagetitle', 'sql', 'party', 'company'));

    //   $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

  }

  function ExpenseReport()
  {
    $pagetitle = 'Expense';
    $branches = Branch::getBranchList();

    return view('expense.expense_report', compact('pagetitle','branches'));
  }

  public function ExpenseReport1(request $request)
  {

    $pagetitle = 'Expense Report';

    $company = DB::table('company')->get();

     $expense_detail = DB::table('v_expense_detail')

      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when($request->BranchID > 0, function ($query) use ($request) {
        return $query->where('BranchID', $request->BranchID);
        })
      ->orderBy('Date')
      ->get();

    return View('expense.expense_report1', compact('expense_detail', 'pagetitle', 'company'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
  }

  // dummy test function
  public function upload1(Request $request)
  {
    $request->validate([
      'file' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->file('file')->isValid()) {
      $path = $request->file('file')->store('uploads');
      return response()->json(['path' => $path], 200);
    }

    return response()->json(['error' => 'Invalid file upload.'], 400);
  }

  function Attachment($vhno = null)
  {

    if ($vhno != '') {
      session::put('vhno', $vhno);
    }

    $attachment = DB::table('attachment')->where('InvoiceNo', session::get('vhno'))->get();

    return view('attachment', compact('attachment'));
  }

  function AttachmentSave(Request $request)
  {

    if ($request->hasfile('filenames')) {
      foreach ($request->file('filenames') as $file) {
        $name = rand(0, 999999) . time() . '.' . $file->extension();
        $file->move(public_path() . '/documents/', $name);
        $data[] = $name;

        $fileData = array(
          'InvoiceNo' => $request->InvoiceNo,
          'FileName' =>  $name

        );
        // dd($fileData);
        $fileid = DB::table('attachment')->insertGetId($fileData);
      }
    }

    return back()->with('success', 'Data Your files has been successfully added');
  }

  public function AttachmentRead()
  {
    $directory = 'documents';
    $files_info = [];

    $file_name = session::get('VHNO');;

    $image = DB::table('attachment')->where('InvoiceNo', $file_name)->get();

    // Read files
    foreach ($image as $file) {

      //  $filename = $file->getFilename();
      //  $size = $file->getSize(); // Bytes
      //  $sizeinMB = round($size / (1000 * 1024), 2);// MB

      //  if($sizeinMB <= 2){ // Check file size is <= 2 MB
      $files_info[] = array(
        "name" => $file->FileName,
        "size" => 12,
        "path" => url($directory . '/' . $file->FileName)
      );
      //  }
      //   }
    }
    return response()->json($files_info);
  }

  public function AttachmentDelete($id, $filename)
  {
    $id =  $id;
    $filename =  $filename;
    DB::table('attachment')->where('AttachmentID', $id)->delete();
    $path = public_path() . '/documents/' . $filename;
    if (file_exists($path)) {
      unlink($path);
    }
    return back()->with('error', 'File Deleted')->with('class', 'success');
  }

  public function SalemanTicketRegister()
  {

    $pagetitle = 'Ticket Register';
    $branches = Branch::getBranchList();
    return view('reports.saleman_ticketregister', compact('pagetitle','branches'));
  }

  public function SalemanTicketRegister1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Ticket Register', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////


    try {
         
         
      $where = [];
         

      

    $pagetitle = 'Ticket Register';

    $invoice_detail = DB::table('v_invoice_detail')
      ->select('SalemanName', DB::raw('count(DISTINCT InvoiceMasterID) as TotalInvoices'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when($request->BranchID > 0, function ($query) use ($request) {
        return $query->where('BranchID', $request->BranchID);
        })
      ->groupBy('SalemanName')
      // ->orderBy('Date')
      ->get();

    $invoice_summary = DB::table('v_invoice_detail')
      ->select(DB::raw('count(*) as TotalInvoices'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->when($request->BranchID > 0, function ($query) use ($request) {
        return $query->where('BranchID', $request->BranchID);
        })
      // ->orderBy('Date')
      ->get();

 
      if(count($invoice_detail)<=0)
      {
        return back()->with('error', 'No invocies found for these dates')->with('class', 'danger');
      }

    // $pdf = PDF::loadView ('airline_summary1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('reports.saleman_ticketregister1', compact('invoice_detail', 'invoice_summary', 'pagetitle'));

        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->with('class', 'danger');
        }



  }

  public  function ajax_party_list()
  {
    $party = Party::getPartyList('C');

    return response()->json(['party' => $party]);
  }




public function query()
{


  $voucher_detail = DB::table('voucher_detail')->whereNotNull('InvoiceNo')->get();

  return view ('query',compact('voucher_detail'));

  // end of controller
}



  public  function ItemwiseSale()

    {


      $pagetitle='Itemwise Sale';
      $item = DB::table('item')->get();
      $branches = Branch::getBranchList();


                
    return view ('reports.itemwise_sale',compact('pagetitle','item','branches'));
    }


  public  function ItemwiseSale2(request $request)

    {

       $pagetitle='Itemwise Sale';

         $today_sale = DB::table('v_invoice_detail22')
    ->select(
        'ItemName','ItemID',
        DB::raw('count(*) as Total'),
        DB::raw('sum(Total) as Invoice'),
        DB::raw('sum(Service) as Profit'),
        DB::raw('sum(Service)/sum(Total) as Percentage')
    )
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
     
      ->when($request->BranchID > 0, function ($query) use ($request) {
        return $query->where('BranchID', $request->BranchID);
        })
      ->groupBy('ItemName','ItemID')
      ->get();

 

                
    return view ('reports.itemwise_sale2',compact('pagetitle','today_sale'));
    }



public function InvoiceDetailList($itemid, $startdate,$enddate)
{



  $pagetitle='Item Detail Invoice Detail';

  
  
  $paymentmode = DB::table('v_invoice_detail')
    ->whereBetween('Date', [$startdate, $enddate])
    ->where('ItemID', $itemid)
    ->when(session('UserType') != 'SuperAdmin', function ($query) {
      return $query->where('BranchID', session('BranchID'));
      })
    ->distinct()
    ->get('PaymentMode');

 
 

  return view ('reports.invoice_detail_item_wise',compact('pagetitle','paymentmode'));




}


function Log()
{



// $expense_master = DB::table('expense_master')->get();

// foreach($expense_master as $value)
// {
//   $sql = "update journal set ExpenseMasterID =".$value->ExpenseMasterID." where VHNO = ".$value->ExpenseNo." ";
//   echo $sql.";<br>";
// }




  $pagetitle='Log';
  $user = User::getUserList();

  return view ('log.log',compact('pagetitle','user'));

}

function Log1(request $request)
{
  $pagetitle='Log';

  $log = DB::table('log')
 ->whereBetween(DB::raw('DATE_FORMAT(Date,"%Y-%m-%d")'), [$request->StartDate,$request->EndDate])
 ->when(session('UserType') != 'SuperAdmin', function ($query) {
   return $query->where('BranchID', session('BranchID'));
   })
 ->when($request->has('UserID') && $request->UserID != null, function ($query) use ($request) {
                        $query->where('UserID', $request->UserID);
                    })
  ->get();
  
  return view('log.log1',compact('pagetitle','log'));

}






  

public function invoice_knokoff_update($partyID,$inputAmount)
{
  
 
  $invoice_paid = DB::table('invoice_master')
  ->where('PartyID',$partyID)
    ->whereColumn('Total', '>', 'Paid')
    ->sum('Paid');
    
    $invoice_total = DB::table('invoice_master')
    ->where('PartyID',$partyID)
    ->whereColumn('Total', '>', 'Paid')
    ->sum('Total');

    // $differnce = $invoice_total - $invoice_paid;

    // if($inputAmount > $differnce  )
    // {
    //   dd ('Invoice Amount is greater than input amount' , $differnce  -$inputAmount);
    // }


    // Get all unpaid invoices (order by date)
    $invoices = DB::table('invoice_master')
    // ->where('PartyID', $partyID)
    // ->where('InvoiceTypeID', 3)
    ->whereColumn('Total', '>', 'Paid')
    ->where('PartyID',$partyID)

    ->orderBy('Date')
    ->get();

    foreach ($invoices as $invoice) {
    $remaining = $invoice->Total - $invoice->Paid;

    if ($inputAmount <= 0) break;

    $payNow = min($inputAmount, $remaining);
    $newPaid = $invoice->Paid + $payNow;

    // Update the invoice
    DB::table('invoice_master')
    ->where('PartyID',$partyID)

        ->where('InvoiceMasterID', $invoice->InvoiceMasterID)
        ->update([
            'Paid' => $newPaid
        ]);

    $inputAmount -= $payNow;
}

}

public function knokoff_checking($partyID,$inputAmount)
{
  
  

  $invoice_paid = DB::table('invoice_master')
  ->where('PartyID',$partyID)
    ->whereColumn('Total', '>', 'Paid')
    ->sum('Paid');
    
    $invoice_total = DB::table('invoice_master')
    ->where('PartyID',$partyID)
    ->whereColumn('Total', '>', 'Paid')
    ->sum('Total');

    $difference = $invoice_total - $invoice_paid;
    
    $data = [
      'status' => true,
    ];

    if($inputAmount > $difference  )
    {
      $data = [
        'status' => false,
        'difference' => $difference - $inputAmount,
      ];

    }
    
    return $data;
    

    
    
    
}


 



//  end of controller






}
