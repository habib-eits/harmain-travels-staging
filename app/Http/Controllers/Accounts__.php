<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Exports\SupplierLedgerExcel;
// for excel export
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
// end for excel export
use Illuminate\Support\Arr;

use Session;
use DB;
use URL;
use Image;
use Excel;
use File;
use PDF;
use DateTime;

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

  public function Login()
  {

    // Encrypt the message 'Hello, Universe'.
    // $encrypted = Crypt::encrypt('Hello, Universe');
    // echo $encrypted;
    // echo "<br>";

    // // Decrypt the $encrypted message.
    // $message   = Crypt::decrypt($encrypted);

    // echo $message;
    //         die;


    $company = DB::table('company')->where('CompanyID',1)->get();
    return view('login.login',compact('company'));
  }

  public function UserVerify(request $request)
  {
    //

    // dd($request->all());
    $input = $request->only(['username', 'password']);

    $username = $input['username'];
    $password =  $input['password'];

    $data = DB::table('user')->where('Email', '=', $username)
      ->where('Password', '=', $password)
      ->where('Active', '=', 'Yes')
      ->get();

     
    if (count($data) > 0) {
      Session::put('FullName', $data[0]->FullName);
      Session::put('UserID', $data[0]->UserID);
      Session::put('Email', $data[0]->Email);
      Session::put('UserType', $data[0]->UserType);
      Session::put('Type', $data[0]->UserType);
    
      return redirect('Dashboard')->with('error', 'Welcome to Falak Travel')->with('class', 'success');
    } else {

      //session::flash('error', 'Invalid username or Password. Try again'); 

      return redirect('Login')->withinput($request->all())->with('error', 'Invalid username or Password. Try again')->with('class', 'danger');
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

    $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,3)'), '<>', 000)->get();

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
    $party = DB::table('party')->get();

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
    $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();

    return view('voucher', compact('voucher_type', 'chartofaccount', 'supplier', 'vhno', 'pagetitle'))->with('error', 'Logout Successfully.')->with('class', 'success');
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

      $data = $query->orderBy('VoucherMstID')->get();

      return Datatables::of($data)
        ->addIndexColumn()

        ->addColumn('action', function ($row) {
          // if you want to use direct link instead of dropdown use this line below
          // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>

          $btn = ' 
 
                       <div class="d-flex align-items-center col-actions">
                     
 
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
    $party = DB::table('party')->orderby('PartyName')->get();

    $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,3)'), '<>', 000)
      ->get();

    return view('voucher_create', compact('voucher_type', 'chartofaccount', 'chartofaccount1', 'supplier', 'vhno', 'pagetitle', 'party', 'vhno'))->with('error', 'Logout Successfully.')->with('class', 'success');
  }

  public function VoucherSave(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher', 'Create');
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
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 




    if ((substr($request->Voucher, 0, 2) == 'BP') || ((substr($request->Voucher, 0, 2) == 'CP')))

      // start for loop
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

        );

        $id1 = DB::table('voucher_detail')->insert($voucher_det_dr);

              // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i],
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher Created', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' => $request->input('Narration_mst') . 'Invoice# ' . $request->Invoice[$i] . $request->RefNo[$i]. ' amount '. ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i], 
      'Trace' => 302,
      'UserID' => session::get('UserID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 

        $voucher_det_cr = array(
          'VoucherMstID' => $id,
          'Voucher' => $request->input('Voucher'),
          'Date' =>  $request->input('VHDate'),
          'ChOfAcc' => $request->ChartOfAccount1,
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID[$i],
          'Narration' => $request->Narration[$i],
          'InvoiceNo' => $request->Invoice[$i],
          'RefNo' => $request->RefNo[$i],
          'Credit' => $request->Debit[$i],

        );

        $id2 = DB::table('voucher_detail')->insert($voucher_det_cr);

              // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i],
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher Created', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' => $request->input('Narration_mst') . 'Invoice# ' . $request->Invoice[$i] . $request->RefNo[$i]. ' amount '. ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i], 
      'Trace' => 303,
      'UserID' => session::get('UserID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 


      }
    // end for each
    else {

      // start for loop
      for ($i = 0; $i < count($request->ChOfAcc); $i++) {

        $voucher_det_dr = array(
          'VoucherMstID' => $id,
          'Voucher' => $request->input('Voucher'),
          'Date' =>  $request->input('VHDate'),
          'ChOfAcc' => $request->ChartOfAccount1,
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID[$i],
          'Narration' => $request->Narration[$i],
          'InvoiceNo' => $request->Invoice[$i],
          'RefNo' => $request->RefNo[$i],
          'Debit' => $request->Debit[$i],

        );

        $id2 = DB::table('voucher_detail')->insert($voucher_det_dr);


                      // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i],
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher creatd', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' =>  $request->Narration[$i] . ' Invoice# ' . $request->Invoice[$i] . $request->RefNo[$i]. ' amount '. ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i], 
      'Trace' => 304,
      'UserID' => session::get('UserID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 

        $voucher_det_cr = array(
          'VoucherMstID' => $id,
          'Voucher' => $request->input('Voucher'),
          'Date' =>  $request->input('VHDate'),
          'ChOfAcc' => $request->ChOfAcc[$i],
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID[$i],
          'Narration' => $request->Narration[$i],
          'InvoiceNo' => $request->Invoice[$i],
          'RefNo' => $request->RefNo[$i],
          'Credit' => $request->Debit[$i],

        );

        $id1 = DB::table('voucher_detail')->insert($voucher_det_cr);

                      // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i],
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher created', 
      'VHNO' => $request->input('Voucher'), 
      'Narration' =>  $request->Narration[$i] . ' Invoice# ' . $request->Invoice[$i] . $request->RefNo[$i]. ' amount '. ($request->Debit[$i]) ? $request->Debit[$i] : $request->Credit[$i], 
      'Trace' => 305,
      'UserID' => session::get('UserID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 



      }
      // end for each

    }

    return redirect('Voucher')->with('error', 'Record Saved')->with('class', 'success');
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
    $supplier = DB::table('supplier')->get();
    $party = DB::table('party')->get();
    $voucher_master = DB::table('voucher_master')->where('VoucherMstID', $id)->get();
    $voucher_detail = DB::table('voucher_detail')->where('VoucherMstID', $id)->get();

    return view('voucher_edit', compact('voucher_type', 'chartofaccount', 'supplier', 'pagetitle', 'voucher_master', 'voucher_detail', 'party'));
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
    );
 
    $log= DB::table('log')->insertGetId($logdata);

    // log input 



    for ($i = 0; $i < count($request->ChOfAcc); $i++) {
      $invoice_det = array(
        'VoucherMstID' => $request->input('VoucherMstID'),
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

      ->get();

    return view('voucher_view', compact('pagetitle', 'voucher_master'));
  }

  public  function VoucherDelete($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Voucher', 'Delete');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////  

    session::put('menu', 'VoucherReport');
    $pagetitle = 'Voucher';

    $voucher_master = DB::table('voucher_master')->where('VoucherMstID',$id)->first();

    $id = DB::table('voucher_master')->where('VoucherMstID', $id)->delete();
    $id = DB::table('voucher_detail')->where('VoucherMstID', $id)->delete();
    $id = DB::table('journal')->where('VoucherMstID', $id)->delete();

                             // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => null,
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Voucher Deleted', 
      'VHNO' => $voucher_master->Voucher, 
      'Narration' =>  'Voucher Deleted from journal too', 
      'Trace' => 401,
      'UserID' => session::get('UserID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input

    return view('voucher', compact('pagetitle'))->with('error', 'Deleted Successfully.')->with('class', 'success');
  }

  public function GetSpecificInvoice($id)
  {
    // Fetch the invoice data by ID
 

    $invoice = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->first();
    
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

      
    $voucher_type = DB::table('v_invoice_master')->get();
    // $chartofaccount = DB::table('chartofaccount')->where('Code', 'E')->get();
    $chartofaccount = DB::table('chartofaccount')->where('ChartOfAccountID', '560110')->get();

    return view('invoice', compact('pagetitle', 'chartofaccount'));
  }

  public function ajax_invoice(Request $request)
  {
    session::put('menu', 'Invoice');
    $pagetitle = 'Invoice';
    if ($request->ajax()) {
      $query = DB::table('v_invoice_detail');
       // Apply filters if they are present in the request
      // if ($request->has('item_name') && !empty($request->item_name)) {
      //     $query->where('ItemName', 'like', '%' . $request->item_name . '%');
      // }

      if ($request->has('party_name') && !empty($request->party_name)) {
        $query->where('PartyName', 'like', '%' . $request->party_name . '%');
      }
      if ($request->has('Phone') && !empty($request->Phone)) {
        $query->where('Phone', 'like', '%' . $request->Phone . '%');
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

                                 <li><a href="javascript:void(0)" onclick="loadPDF(\'' . URL('/InvoicePDFView/' . $row->InvoiceMasterID) . '\')" class="dropdown-item"><i class="mdi mdi-file-pdf font-size-16  me-1" style="color:#FF5733;"></i> View Invoice</a></li>
                              

 

    <a href="' . URL('/InvoiceEdit/' . $row->InvoiceMasterID) . '" class="dropdown-item">
        <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit Invoice
    </a>



                                
                                <li><a href="' . URL('/InvoicePDF/' . $row->InvoiceMasterID) . '/download' . '" target="_blank" class="dropdown-item"><i class="mdi mdi-file-pdf-outline font-size-16  me-1" style="color:#AF0505;"></i> Download Invoice</a></li>
                                
                                <li><a href="' . URL('/InvoicePDF/' . $row->InvoiceMasterID) . '" target="_blank" class="dropdown-item"><i class="mdi mdi-eye-outline font-size-16 text-secondary me-1"></i> View PDF</a></li>
                                
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
    );

$log= DB::table('log')->insertGetId($logdata);

// log input 

   
    $voucher_mst = array(
      'VoucherCodeID' => $VoucherType,
      'Voucher' => $request->input('voucher_number'),
      'Narration' => $request->notes,
      'Date' => dateformatpc($request->Date),
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
    // Load the HTML view instead of generating PDF directly
    $html = view('invoice_pdf', compact('balance', 'invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det', 'company'))->render();

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
    $invoice_type = DB::table('invoice_type')->get();

    $items = DB::table('item')->get();
    $supplier = DB::table('supplier')->get();
    $party = DB::table('party')->get();
    
    $saleman = DB::table('user')->where('Active','Yes')->get();
    // $saleman = DB::table('user')->where('UserType','Saleman')->where('Active','Yes')->get();

    $vhno = DB::table('invoice_master')->select(DB::raw('IFNULL(max(InvoiceMasterID)+1,1) as VHNO'))->get();

    return view('invoice_create', compact('invoice_type', 'items', 'supplier', 'vhno', 'saleman','party'))->with('error', 'Logout Successfully.')->with('class', 'success');
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
        'Phone' => $request->WalkinCustomerMobile
      );

      $party_id = DB::table('party')->insertGetId($data);

      $request->PartyID = $party_id;
    }

    $invoice_mst = array(
      'InvoiceMasterID' => $request->input('VHNO'),
      'InvoiceTypeID' => $request->input('InvoiceTypeID'),
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


      // log input
    $logdata = array(
      'UserName' => session::get('FullName'), 
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


    // when full payment is made

    if (($request->input('InvoiceTypeID') == 1) && ($request->input('amountPaid') > 0)) {

      // Cash / Bank / Credit Card account -> Debit
      $data_cash = array(
        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        'ChartOfAccountID' => $PaymentMode,   // Cash / bank / credit card
        'PartyID' => $request->PartyID,

        'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
        'Date' => $request->input('Date'),
        'Dr' => $request->input('amountPaid'),
        'Trace' => 10
      );

      $journal_entry = DB::table('journal')->insertGetId($data_cash);

      // A/R -> Credit
      $data_ar = array(
        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        'ChartOfAccountID' => '110400',   //A/R
        'PartyID' => $request->PartyID,

        'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
        'Date' => $request->input('Date'),
        'Cr' => $request->input('amountPaid'),
        'Trace' => 102
      );

      $journal_entry = DB::table('journal')->insertGetId($data_ar);
    }

    // SALE RETURN

    if (($request->input('InvoiceTypeID') == 2) && ($request->input('amountPaid') > 0)) {

      // A/R DEBIT
      $SR_AR = array(
        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        'ChartOfAccountID' => 110400,   // A/R DEBIT
        'PartyID' => $request->PartyID,

        'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
        'Date' => $request->input('Date'),
        'Dr' => $request->input('amountPaid'),
        'Trace' => 103
      );

      $journal_entry = DB::table('journal')->insertGetId($SR_AR);

      // Cash  -> CREDIT
      $SR_CASH = array(
        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        'ChartOfAccountID' => $PaymentMode,   // CASH CREDIT
        'PartyID' => $request->PartyID,

        'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
        'Date' => $request->input('Date'),
        'Cr' => $request->input('amountPaid'),
        'Trace' => 104
      );

      $journal_entry = DB::table('journal')->insertGetId($SR_CASH);
    }

    // END OF SALE RETURN

    //  start for item array from invoice
    for ($i = 0; $i < count($request->ItemID); $i++) {
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

      );

      $idd = DB::table('invoice_detail')->insertGetId($invoice_det);

      // journal entry start from here when full payment is made part 1
      if ($request->input('InvoiceTypeID') == 1) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'SupplierID' => $request->SupplierID[$i],
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
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
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
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
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
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
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
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
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
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
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
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->ItemTotal[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 201
        );
        $id = DB::table('journal')->insertGetId($loop_AR);

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
            'Trace' => 202
          );

          $id = DB::table('journal')->insertGetId($comission);
        } else {
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
            'Trace' => 2022
          );

          $id = DB::table('journal')->insertGetId($comission);
        }

        // Services Charges -> commsion update chart of account
        if ($request->Discount[$i] > 0) {

          $discount_rec = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // Discount Received -> commsion update chart of account
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->Discount[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 203
          );

          $id = DB::table('journal')->insertGetId($discount_rec);
        } else {
          $discount_rec = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // Discount Received 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->PartyID,
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => abs($request->Discount[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 2033
          );

          $id = DB::table('journal')->insertGetId($discount_rec);
        }

        // Purchase of Ticket - > PIA - DEBIT
        $loop_purchase_dr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 204
        );

        $id = DB::table('journal')->insertGetId($loop_purchase_dr);

        // Purchase of Ticket - > PIA - CREDIT
        $loop_purchase_cr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Cr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 205
        );
        $id = DB::table('journal')->insertGetId($loop_purchase_cr);

        // A/P - > PIA
        $loop_ap = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '210100',  // A/P - > PIA
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->PartyID,
          'InvoiceMasterID' => $request->input('VHNO'),
          'Date' => $request->input('Date'),
          'Dr' => $request->Fare[$i],
          'Narration' => $request->PaxName[$i],
          'Trace' => 206
        );

        $id = DB::table('journal')->insertGetId($loop_ap);
      }
      // journal entry end here part 1

      // END SALE RETURN FOR EACH ROW

    }

    // end foreach

    // queries end here  -->
    DB::commit();
    return redirect('Invoice')
      ->with('error', 'Invoice Saved')
      ->with('class', 'success')
      ->with('invoiceMasterID', $InvoiceMasterID);



       
            } catch (\Exception $e) {
                DB::rollBack();
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

        try {
  
                 // your alll  queries here -->
    
             

    $invoice_type = DB::table('invoice_type')->get();

    $items = DB::table('item')->get();
    $supplier = DB::table('supplier')->get();
    $party = DB::table('party')->get();
    
    $saleman = DB::table('user')->where('Active','Yes')->get();
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
      'Balance' => $request->input('Total') - $request->input('amountPaid'),
      'UserID' => $request->input('SalemanID'),
      'Note' => $request->input('Note'),

    );

    $id11 = DB::table('invoice_master')->where('InvoiceMasterID', $request->VHNO)->update($invoice_mst);

    $invoice_type = DB::table('invoice_type')->where('InvoiceTypeID', $request->input('InvoiceTypeID'))->get();

    $id1 = DB::table('invoice_detail')->where('InvoiceMasterID', $request->VHNO)->delete();

 $id2 = DB::table('journal')->where('InvoiceMasterID', $request->VHNO)
     ->whereNotNull('SupplierID')
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
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input 

    


    // when full payment is made

    // if (($request->input('InvoiceTypeID') == 1) && ($request->input('amountPaid') > 0)) {

    //   // Cash / Bank / Credit Card account -> Debit
    //   $data_cash = array(
    //     'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
    //     'JournalType' => $invoice_type[0]->InvoiceTypeCode,
    //     'ChartOfAccountID' => $PaymentMode,   // Cash / bank / credit card
    //     'PartyID' => $request->input('PartyID'),

    //     'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
    //     'Date' => $request->input('Date'),
    //     'Dr' => $request->input('amountPaid'),
    //     'Trace' => 301
    //   );

    //   $journal_entry = DB::table('journal')->insertGetId($data_cash);

    //   // A/R -> Credit
    //   $data_ar = array(
    //     'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
    //     'JournalType' => $invoice_type[0]->InvoiceTypeCode,
    //     'ChartOfAccountID' => '110400',   //A/R
    //     'PartyID' => $request->input('PartyID'),

    //     'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
    //     'Date' => $request->input('Date'),
    //     'Cr' => $request->input('amountPaid'),
    //     'Trace' => 302
    //   );

    //   $journal_entry = DB::table('journal')->insertGetId($data_ar);
    // }

    // SALE RETURN

    if (($request->input('InvoiceTypeID') == 2) && ($request->input('amountPaid') > 0)) {

      // A/R DEBIT
      $SR_AR = array(
        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        'ChartOfAccountID' => 110400,   // A/R DEBIT
        'PartyID' => $request->input('PartyID'),

        'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
        'Date' => $request->input('Date'),
        'Dr' => $request->input('amountPaid'),
        'Trace' => 303
      );

      $journal_entry = DB::table('journal')->insertGetId($SR_AR);

      // Cash  -> CREDIT
      $SR_CASH = array(
        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
        'ChartOfAccountID' => $PaymentMode,   // CASH CREDIT
        'PartyID' => $request->input('PartyID'),

        'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
        'Date' => $request->input('Date'),
        'Cr' => $request->input('amountPaid'),
        'Trace' => 304
      );

      $journal_entry = DB::table('journal')->insertGetId($SR_CASH);
    }

    for ($i = 0; $i < count($request->ItemID); $i++) {
      $invoice_det = array(
        'InvoiceMasterID' => $request->input('VHNO'),
        'ItemID' => $request->ItemID[$i],
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

      );

      $id = DB::table('invoice_detail')->insertGetId($invoice_det);

      // journal entry start from here when full payment is made part 1
      if ($request->input('InvoiceTypeID') == 1) {

        // A/R
        $loop_AR = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '110400',  // A/R
          'SupplierID' => $request->SupplierID[$i],
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
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->input('PartyID'),
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
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->input('PartyID'),
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
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->input('PartyID'),
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
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->input('PartyID'),
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
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->input('PartyID'),
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
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->input('PartyID'),
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
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->input('PartyID'),
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
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->input('PartyID'),
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
          'SupplierID' => $request->SupplierID[$i],
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
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->input('PartyID'),
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->Service[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 313
          );

          $id = DB::table('journal')->insertGetId($comission);
        } else {
          $comission = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // COMISSION 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->input('PartyID'),
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => abs($request->Service[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 2022
          );

          $id = DB::table('journal')->insertGetId($comission);
        }

        // Services Charges
        if ($request->Discount[$i] > 0) {

          $discount_rec = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410101', // Discount Received 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->input('PartyID'),
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => $request->Discount[$i],
            'Narration' => $request->PaxName[$i],
            'Trace' => 314
          );

          $id = DB::table('journal')->insertGetId($discount_rec);
        } else {
          $discount_rec = array(
            'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
            'JournalType' => $invoice_type[0]->InvoiceTypeCode,
            'ChartOfAccountID' => '410152', // Discount Received 
            'SupplierID' => $request->SupplierID[$i],
            'PartyID' => $request->input('PartyID'),
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'Cr' => abs($request->Discount[$i]),
            'Narration' => $request->PaxName[$i],
            'Trace' => 314
          );

          $id = DB::table('journal')->insertGetId($discount_rec);
        }

        // Purchase of Ticket - > PIA - DEBIT
        $loop_purchase_dr = array(
          'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
          'JournalType' => $invoice_type[0]->InvoiceTypeCode,
          'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->input('PartyID'),
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
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->input('PartyID'),
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
          'SupplierID' => $request->SupplierID[$i],
          'PartyID' => $request->input('PartyID'),
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

    if ($invoice->Paid == $invoice->Total) {
      return redirect('Invoice')
        ->with('error', 'Invoice Updated')
        ->with('class', 'success');
    } else {
      return redirect('Invoice')
        ->with('error', 'Invoice Updated')
        ->with('class', 'success')
        ->with('invoiceMasterID', $InvoiceMasterID);
    }



    
    
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', $e->getMessage())->with('class', 'danger');
            }





  }

  public  function InvoiceDelete($id)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Invoice', 'Delete');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    DB::table('journal')->where('InvoiceMasterID', $id)->delete();

    DB::table('invoice_detail')->where('InvoiceMasterID', $id)->delete();

    $data = DB::table('invoice_master')->where('InvoiceMasterID', $id)->first();

    $logdata = array(
      'UserName' => session::get('FullName'), 
      'Amount' => $data->Total,
      'Date' =>date('Y-m-d H:i:s'), 
      'Section' => 'Invoice Deleted', 
      'VHNO' => $id, 
      'Narration' =>  'Invoice Deleted total amount-> '. $data->Total. ' customer id '. $data->PartyID, 
      'Trace' => 401,
      'UserID' => session::get('UserID'),
    );

    $log= DB::table('log')->insertGetId($logdata);

    // log input

    DB::table('invoice_master')->where('InvoiceMasterID', $id)->delete();

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

    $company = DB::table('company')->where('CompanyID', 1)->get();
    $invoice_mst = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
    $invoice_det = DB::table('v_invoice_detail2')->where('InvoiceMasterID', $id)->get();
    $invoice = DB::table('invoice_master')->select('total', 'paid')->where('InvoiceMasterID', $id)->first();
    $balance = $invoice->total - $invoice->paid;

    // return View ('invoice_pdf',compact('invoice_type','items','supplier','vhno','invoice_mst','invoice_det'));

    $filename = $invoice_mst[0]->InvoiceCode . '-' . $invoice_mst[0]->Date . '-PartyCode-' . $invoice_mst[0]->PartyID;

    $pdf = PDF::loadView('invoice_pdf', compact('balance', 'invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det', 'company'));
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
    $supplier = DB::table('supplier')->get();

    return view('payment_summary', compact('pagetitle', 'invoice_type', 'supplier', 'item', 'saleman'));
    
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

    return View('payment_summary1', compact('cash_payments','bank_payments' ,'pagetitle'));
  }

  public function Dashboard()
  {


 
    session::put('menu', 'Dashboard');
    // $encrypted = Crypt::encryptString('Hello DevDojo');
    // print_r($encrypted);

    //     echo "<br>";

    // $encrypted = crypt::decryptString($encrypted);
    // print_r($encrypted);

    //     die;

    // if(session::get('UserType')=='OM')
    //              {

    //                return redirect('Login')->with('error','Access Denied!')->with('class','success');

    //              }

    $start_date = date('Y-01-01');
    $end_date = date('Y-m-t');



    $pagetitle = 'Dashboard';

    $invoice_master = DB::table('invoice_master')
      ->select(DB::raw('ifnull(sum(IFNULL(Paid,0)),0) as Paid'))->where('Date', date('Y-m-d'))->get();

    $v_cashflow = DB::table('v_cashflow')->where('Year', date('Y'))->orderBy('mMonthName')->get();

    $data = array();

    foreach ($v_cashflow as $key => $value) {

      $cashflow_chart[] = $value->Balance;
    }

    // todays income
    $expense = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
      ->where('CODE', 'R')
      ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m-%d")'), date('Y-m-d'))
      ->get();

    $revenue = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
      ->where('CODE', 'R')
      ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m")'), date('Y-m'))
      ->get();

    $r = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
      ->whereBetween('Date', array($start_date, $end_date))
      ->where('CODE', 'R')
      ->get();

    $e = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance'))
      ->whereBetween('Date', array($start_date, $end_date))
      ->where('CODE', 'E')
      ->get();

    $r = ($r[0]->Balance == null) ? '0' :  $r[0]->Balance;
    $e = ($e[0]->Balance == null) ? '0' :  $e[0]->Balance;

    $profit_loss = abs($r) - abs($e);

    $cash = DB::table('v_journal')
      ->select('ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'))
      ->whereIn('Category', ['CASH','BANK','CARD'])
      // ->where('ChartOfAccountID',$request->ChartOfAccountID)
      ->groupBy('ChartOfAccountName')
      ->get();

    $cash1 = DB::table('v_income_expense')->where('yDate', date('Y'))->orderby('mDate')->get();
    $exp_chart = DB::table('v_expense_chart')->where('MonthName', date('F-Y'))->get();
 
    // $party_balance = DB::table('v_journal')
    //            ->select('ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'))
    //            ->whereIn('ChartOfAccountID',[110400])

    //         // ->where('ChartOfAccountID',$request->ChartOfAccountID)
    //            ->groupBy('ChartOfAccountName')
    //                  ->get(); 

    //110400

    $party_balance = DB::table('v_party_bal')
      ->select(DB::raw('sum(if(ISNULL(Balance),0,Balance)) as Balance'))
      ->get();

    $ticket_register = DB::table('v_invoice_detail1')
      ->select('SalemanName', DB::raw('count(*) as TotalInvoices'), DB::raw('sum(Fare) as Fare'), DB::raw('ROUND(sum(Service), 2) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
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
      // ->orderBy('Date')
      ->get();
    
$date1 = date_create(date('Y-m-1'));   // First day of the month
$date2 = date_create(date('Y-m-d'));   // Current date

$interval = $date1->diff($date2);
$diff = ($interval->days == 0) ? 1 : $interval->days + 1;  // Adding 1 to include today's date

$avg = ($invoice_summary[0]->Service / $diff);  // Average based on total days including today
echo $diff;
    // $invoice_summary = DB::table('v_invoice_detail1')
    //                  ->select (DB::raw('count(*) as TotalInvoices'),DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'),DB::raw('sum(Total) as Total'),DB::raw('sum(Taxable) as Taxable'),DB::raw('sum(Discount) as Discount'))
    //                     ->whereBetween('Date',array($request->StartDate,$request->EndDate))
    //                     // ->orderBy('Date')
    //                     ->get();

    // echo json_encode($ticket_register->pluck('SalemanName')); 
    // echo "<br>";

    // echo json_encode($ticket_register->pluck('TotalInvoices')); 

    //  echo "<br>";

    // echo json_encode($ticket_register->pluck('Service')); 
    // die;


  $leads_unassigned = DB::table('leads')->whereNull('agent_id')->count();
     if(session::get('UserType')=='Admin')
  {
    $lead_summary = DB::table('v_lead_summary')->first();

}
    else
    {
    $lead_summary = DB::table('v_lead_summary_user')->where('agent_id',session::get('UserID'))->first();
   } 
        
             $fourDaysAgo = Carbon::now()->subDays(4);
            
            $leadsNotUpdatedIn4Days = DB::table('leads')
            ->where('status','Pending')
            ->where('updated_at', '<', $fourDaysAgo)->count();
           
   
            $booking_payment = DB::table('v_bookings_admin')->where('amount','>',0)->where('status','Pending')->count();
            
            $agents = DB::table('user')->where('UserType' , 'Agent')->get();
            // $agents = DB::table('user')
            // ->where('UserType', 'Agent')
            // ->select('user.*', 
            //     DB::raw('(SELECT COUNT(*) FROM bookings WHERE DATE_FORMAT(start, "%Y-%m-%d") = CURDATE()) as bookings_count'),
            //     DB::raw('(SELECT COUNT(*) FROM leads WHERE leads.agent_id = user.UserID) as leads_count'),
            //     DB::raw('(SELECT COUNT(*) FROM leads WHERE status = "Pending" AND leads.agent_id = user.UserID) as pending_leads_count'),
            //     DB::raw('(SELECT COUNT(*) FROM leads WHERE approved_status = "Closed Won" AND leads.agent_id = user.UserID) as leads_won_count'),
            //     DB::raw('(SELECT COUNT(*) FROM leads WHERE approved_status = "Closed Lost" AND leads.agent_id = user.UserID) as leads_lost_count'),
            //     DB::raw('(SELECT COUNT(*) FROM leads WHERE status = "Rejected" AND leads.agent_id = user.UserID) as rejected_leads_count')
            // )
            // ->get();

            $today = Carbon::today();
        
            $leads_created_today = DB::table('leads')
          
            ->whereDate('created_at', $today)
            ->count();
    
            $leads_updated_today =  DB::table('leads')
           
            ->whereDate('updated_at', $today)
            ->count();


     $sale_report = DB::table('v_invoice_detail')
    ->select('ItemName', DB::raw('count(InvoiceMasterID) as Total'))
    ->whereBetween('date', array(date('Y-m-1'), date('Y-m-d')))
    ->groupBy('ItemName')
    ->get();


 
    $followup = DB::table('lead_details')->whereDate('follow_up_date', $today)->count();

 
     return view('dashboard', compact('pagetitle', 'v_cashflow', 'invoice_master', 'expense', 'revenue', 'profit_loss', 'cash', 'cash1', 'exp_chart', 'party_balance', 'ticket_register', 'avg','lead_summary','sale_report','leads_unassigned','leads_created_today','leads_updated_today','followup'));
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

  public  function Item()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Item/Inventory', 'List / Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    session::put('menu', 'Item');
    $pagetitle = 'Item';

    $item = DB::table('item')->get();
    return view('item', compact('pagetitle', 'item'));
  }

  public  function ItemSave(request $request)
  {
    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Item/Inventory', 'List / Create');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

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
      'ItemCode' => $request->input('ItemCode'),
      'ItemName' => $request->input('ItemName'),
      'Taxable' => $request->input('Taxable'),
      'Percentage' => $request->input('Percentage'),

    );

    $id = DB::table('item')->insertGetId($data);

    return redirect('Item')->with('error', 'Save Successfully.')->with('class', 'success');
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

    return view('item_edit', compact('pagetitle', 'item'));
  }

  public  function ItemUpdate(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Item/Inventory', 'Update');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

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
      'ItemCode' => $request->input('ItemCode'),
      'ItemName' => $request->input('ItemName'),
      'Taxable' => $request->input('Taxable'),
      'Percentage' => $request->input('Percentage'),

    );

    $id = DB::table('item')->where('ItemID', $request->input('ItemID'))->update($data);

    return redirect('Item')->with('error', 'Updated Successfully.')->with('class', 'success');
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

    $supplier = DB::table('party')->get();
    return view('party', compact('pagetitle', 'supplier'));
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
    );

    $party = DB::table('party')->insertGetId($data);

    return response()->json([
      'PartyID' => $party, 'PartyName' =>  $request->PartyName,
      'Phone' =>  $request->Phone
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

      'PartyName' => $request->input('PartyName'),
      'Address' => $request->input('Address'),
      'Phone' => $request->input('Phone'),
      'Email' => $request->input('Email'),
      'Active' => $request->input('Active'),
      'InvoiceDueDays' => $request->input('InvoiceDueDays'),

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

    session::put('menu', 'Party');
    $pagetitle = 'Party';

    $supplier = DB::table('party')->where('PartyID', $id)->get();

    return view('party_edit', compact('pagetitle', 'supplier'));
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

      'PartyName' => $request->input('PartyName'),
      'Address' => $request->input('Address'),
      'Phone' => $request->input('Phone'),
      'Email' => $request->input('Email'),
      'Active' => $request->input('Active'),
      'InvoiceDueDays' => $request->input('InvoiceDueDays'),

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

    $id = DB::table('users')->where('UserID', session::get('UserID'))->update($data);
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
    $party = DB::table('party')->get();
    $voucher_type = DB::table('voucher_type')->get();
    $chartofaccount = DB::table('chartofaccount')
      ->where('ChartOfAccountID', 110400)->get();
    return view('party_ledger', compact('pagetitle', 'party', 'voucher_type', 'chartofaccount'));
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

    $vouchertype = DB::table('voucher_type')->where('VoucherTypeID', $request->VoucherTypeID)->get();

    $where = array();

    if ($request->VoucherTypeID > 0) {

      $where = Arr::add($where, 'JournalType', $vouchertype[0]->VoucherCode);
    }

    if ($request->PartyID > 0) {

      $where = Arr::add($where, 'PartyID', $request->PartyID);
    }

    if ($request->ChartOfAccountID > 0) {

      $where = Arr::add($where, 'ChartOfAccountID', $request->ChartOfAccountID);
    }

    $sql = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
      // ->where('PartyID',$request->PartyID)
      ->where($where)
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

    $party = DB::table('party')->where('PartyID', $request->PartyID)->get();

    $journal = DB::table('v_journal')->where('PartyID', $request->PartyID)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->where($where)
      // ->orderBy('VHNO', 'asc')
      ->orderBy('Date', 'asc') // Sort by Date in ascending order
      ->orderBy('JournalID', 'asc')   // Sort by ID in ascending order
      ->get();

    //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party')); 
    // //return $pdf->download('pdfview.pdf');
    //    $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
    return view('party_ledger1', compact('journal', 'pagetitle', 'sql', 'party'));
  }



  public function ajax_party_ledger($partyid)
  {

   
    session::put('menu', 'PartyLedger');
    $pagetitle = 'Party Ledger';
 
    $sql = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
       ->where('PartyID',$partyid)
      ->where('ChartOfAccountID',110400)
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
       ->where('ChartOfAccountID',110400)
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

    $vouchertype = DB::table('voucher_type')->where('VoucherTypeID', $request->VoucherTypeID)->get();

    $where = array();

    if ($request->VoucherTypeID > 0) {

      $where = Arr::add($where, 'JournalType', $vouchertype[0]->VoucherCode);
    }

    if ($request->PartyID > 0) {

      $where = Arr::add($where, 'PartyID', $request->PartyID);
    }

    if ($request->ChartOfAccountID > 0) {

      $where = Arr::add($where, 'ChartOfAccountID', $request->ChartOfAccountID);
    }

    $sql = DB::table('journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
      // ->where('PartyID',$request->PartyID)
      ->where($where)
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

    $party = DB::table('party')->where('PartyID', $request->PartyID)->get();

    $journal = DB::table('v_journal')->where('PartyID', $request->PartyID)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->where($where)
      ->where('ChartOfAccountID', 110400)
      ->orderBy('VHNO', 'asc')
      ->get();

    //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party')); 
    // //return $pdf->download('pdfview.pdf');
    //    $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();

    $pdf = PDF::loadView('party_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'party'));
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
    $party = DB::table('party')->get();

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
    $pagetitle = 'SupplierBalance';
    return view('supplier_balance', compact('pagetitle'));
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

    $supplier = DB::table('supplier')->get();

    return view('supplier_balance1', compact('supplier', 'pagetitle'));
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

    $supplier = DB::table('supplier')->get();

    $pdf = PDF::loadView('supplier_balance1pdf', compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();

    return view('supplier_balance1pdf', compact('supplier'));
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
    $party = DB::table('party')->get();

    return view('party_list', compact('party', 'pagetitle'));
  }

  public function PartyListPDF()
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Party List', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $party = DB::table('party')->get();
    $party = DB::table('party')->get();

    $pdf = PDF::loadView('party_listPDF', compact('party', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('party_list', cocompact('party', 'pagetitle'));
  }

  public function OutStandingInvoice()
  {
    $pagetitle = 'Out Standing Invoice';
    session::put('menu', 'OutStandingInvoice');
    $party = DB::table('party')->get();
    return view('outstanding_invoice', compact('party', 'pagetitle'));
  }

  public function OutStandingInvoice1(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Outstanding Invoices', 'View');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Out Standing Invoice';
    if ($request->PartyID > 0) {
      $invoice = DB::table('v_invoice_master')->where('PartyID', $request->PartyID)->where('Balance', '>', 0)->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    } else {

      $invoice = DB::table('v_invoice_master')->where('Balance', '>', 0)->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    }

    // $pdf = PDF::loadView ('outstanding_invoice1',compact('invoice'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return view('outstanding_invoice1', compact('invoice', 'pagetitle'));
  }

  public function OutStandingInvoice1PDF(request $request)
  {

    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
    $allow = check_role(session::get('UserID'), 'Outstanding Invoices', 'PDF');
    if ($allow[0]->Allow == 'N') {
      return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
    }
    ////////////////////////////END SCRIPT ////////////////////////////////////////////////

    $pagetitle = 'Out Standing Invoice';
    if ($request->PartyID > 0) {
      $invoice = DB::table('v_invoice_master')->where('PartyID', $request->PartyID)->where('Balance', '>', 0)->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    } else {

      $invoice = DB::table('v_invoice_master')->where('Balance', '>', 0)->whereBetween('date', array($request->StartDate, $request->EndDate))->get();
    }

    $pdf = PDF::loadView('outstanding_invoice1PDF', compact('invoice', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('outstanding_invoice1', compact('invoice', 'pagetitle'));
  }

  public function PartyWiseSale()
  {

    session::put('menu', 'PartyLedger');
    $pagetitle = 'Party Ledger';
    $invoice_type = DB::table('invoice_type')->get();
    $party = DB::table('party')->get();

    return view('partywise_sale', compact('pagetitle', 'invoice_type', 'party'));
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

    if (($request->PartyID > 0) && ($request->InvoiceTypeID == 'both')) {

      $party_wise = DB::table('v_partywise_sale')->where('PartyID', $request->PartyID)->get();
    } elseif (($request->PartyID > 0) && ($request->InvoiceTypeID == 1)) {

      $party_wise = DB::table('v_partywise_sale')->where('PartyID', $request->PartyID)->where('InvoiceTypeID', 1)->get();
    } elseif (($request->PartyID > 0) && ($request->InvoiceTypeID == 2)) {

      $party_wise = DB::table('v_partywise_sale')->where('PartyID', $request->PartyID)->where('InvoiceTypeID', 2)->get();
    } elseif (($request->PartyID == 0) && ($request->InvoiceTypeID == 'both')) {

      $party_wise = DB::table('v_partywise_sale')->get();
    } elseif (($request->PartyID == 0) && ($request->InvoiceTypeID == 1)) {

      $party_wise = DB::table('v_partywise_sale')->where('InvoiceTypeID', 1)->get();
    } elseif (($request->PartyID == 0) && ($request->InvoiceTypeID == 2)) {

      $party_wise = DB::table('v_partywise_sale')->where('InvoiceTypeID', 2)->get();
    }

    //       $pdf = PDF::loadView ('partywise_sale1',compact('party_wise'));
    // //return $pdf->download('pdfview.pdf');
    //   // $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();

    return View('partywise_sale1', compact('party_wise', 'pagetitle'));
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
    if (($request->PartyID > 0) && ($request->InvoiceTypeID == 'both')) {

      $party_wise = DB::table('v_partywise_sale')->where('PartyID', $request->PartyID)->get();
    } elseif (($request->PartyID > 0) && ($request->InvoiceTypeID == 1)) {

      $party_wise = DB::table('v_partywise_sale')->where('PartyID', $request->PartyID)->where('InvoiceTypeID', 1)->get();
    } elseif (($request->PartyID > 0) && ($request->InvoiceTypeID == 2)) {

      $party_wise = DB::table('v_partywise_sale')->where('PartyID', $request->PartyID)->where('InvoiceTypeID', 2)->get();
    } elseif (($request->PartyID == 0) && ($request->InvoiceTypeID == 'both')) {

      $party_wise = DB::table('v_partywise_sale')->get();
    } elseif (($request->PartyID == 0) && ($request->InvoiceTypeID == 1)) {

      $party_wise = DB::table('v_partywise_sale')->where('InvoiceTypeID', 1)->get();
    } elseif (($request->PartyID == 0) && ($request->InvoiceTypeID == 2)) {

      $party_wise = DB::table('v_partywise_sale')->where('InvoiceTypeID', 2)->get();
    }

    $pdf = PDF::loadView('partywise_sale1PDF', compact('party_wise', 'pagetitle'));
    // //return $pdf->download('pdfview.pdf');
    //   // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return View('partywise_sale1', compact('party_wise'));
  }

  public function PartyBalance()
  {
    session::put('menu', 'PartyLedger');
    $pagetitle = 'Party Balance';

    $party = DB::table('party')->get();

    return view('party_balance', compact('pagetitle', 'party'));
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

    

   // $party = DB::table('journal')
   //  ->select('party.PartyID', 'party.PartyName', DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
   //  ->join('party', 'journal.PartyID', '=', 'party.PartyID')
   //  ->whereBetween('journal.date', [$request->StartDate, $request->EndDate])
   //  ->groupBy('party.PartyID', 'party.PartyName')
   //   ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
   //   ->get();


 // dd($party);
    if ($request->PartyID > 0) {

      $party = DB::table('journal')
    ->select('party.PartyID', 'party.PartyName', DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
    ->join('party', 'journal.PartyID', '=', 'party.PartyID')
    ->whereBetween('journal.date', [$request->StartDate, $request->EndDate])
    ->where('journal.PartyID', $request->PartyID)
    ->where('journal.ChartOfAccountID', 110400)
    ->groupBy('party.PartyID', 'party.PartyName')
      ->get();
    } else {

   $party = DB::table('journal')
    ->select('party.PartyID', 'party.PartyName', DB::raw('SUM(IFNULL(Dr, 0))  as Dr'),  DB::raw(' SUM(IFNULL(Cr, 0)) as Cr'), DB::raw('SUM(IFNULL(Dr, 0)) - SUM(IFNULL(Cr, 0)) as balance'))
    ->join('party', 'journal.PartyID', '=', 'party.PartyID')
    ->whereBetween('journal.date', [$request->StartDate, $request->EndDate])
    ->where('journal.ChartOfAccountID', 110400)
    ->groupBy('party.PartyID', 'party.PartyName')
     ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
     ->get();
    }




    return  View('party_balance1', compact('party', 'pagetitle'));
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
        ->groupBy('PartyID', 'PartyName')
        ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
        ->get();
    } else {

      $party = DB::table('v_party_balance')->select('PartyID', 'PartyName', DB::raw('sum(Dr) as Dr'), DB::raw('sum(Cr) as Cr'))
        ->whereBetween('date', array($request->StartDate, $request->EndDate))
        // ->where('PartyID',$request->PartyID)
        ->groupBy('PartyID', 'PartyName')
        ->having(DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))'), ($request->ReportType == 'C') ? '<' : '>', 0)
        ->get();
    }

    $pdf = PDF::loadView('party_balance1PDF', compact('party'));
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
    return view('party_yearly_balance', compact('pagetitle'));
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

    $party = DB::table('party')->get();

    return view('party_yearly_balance1', compact('party'));
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

    $party = DB::table('party')->get();
    $pdf = PDF::loadView('party_yearly_balance1PDF', compact('party'));
    //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();

    return view('party_yearly_balance1PDF', compact('party'));
  }

  // SUPPLIER REPORTS

  public function SupplierLedger()
  {

    session::put('menu', 'SupplierLedger');
    $pagetitle = 'Supplier Ledger';

    $supplier = DB::table('supplier')->get();

    $voucher_type = DB::table('voucher_type')->get();

    $chartofaccount = DB::table('chartofaccount')->where('ChartOfAccountID', 210100)->get();
    return view('supplier_ledger', compact('pagetitle', 'supplier', 'voucher_type', 'chartofaccount'));
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
      ->where('ChartOfAccountID', $request->ChartOfAccountID)
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

    $supplier = DB::table('supplier')->where('SupplierID', $request->SupplierID)->get();

    $journal = DB::table('v_journal')->where('SupplierID', $request->SupplierID)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->where('ChartOfAccountID', $request->ChartOfAccountID)
      ->orderBy('Date', 'asc')
      ->get();

    //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party')); 
    // //return $pdf->download('pdfview.pdf');
    //    $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
    return view('supplier_ledger1', compact('journal', 'pagetitle', 'sql', 'supplier'));
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
      ->where('SupplierID', $request->SupplierID)
      ->where('ChartOfAccountID', $request->ChartOfAccountID)
      ->where('Date', '<', $request->StartDate)
      // ->whereBetween('date',array($request->StartDate,$request->EndDate))

      ->get();

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    $supplier = DB::table('supplier')->where('SupplierID', $request->SupplierID)->get();

    $journal = DB::table('v_journal')->where('SupplierID', $request->SupplierID)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->where('ChartOfAccountID', $request->ChartOfAccountID)
      ->orderBy('Date', 'asc')
      ->get();

    $pdf = PDF::loadView('supplier_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'supplier'));
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

    return view('supplierwise_sale', compact('pagetitle', 'invoice_type', 'supplier'));
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

    if (($request->SupplierID > 0) && ($request->InvoiceTypeID == 'both')) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID > 0) && ($request->InvoiceTypeID == 1)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID > 0) && ($request->InvoiceTypeID == 2)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 'both')) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 1)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 2)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    }

    // $pdf = PDF::loadView ('supplierwise_sale1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('supplierwise_sale1', compact('supplier', 'pagetitle'));
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

    if (($request->SupplierID > 0) && ($request->InvoiceTypeID == 'both')) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID > 0) && ($request->InvoiceTypeID == 1)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID > 0) && ($request->InvoiceTypeID == 2)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 'both')) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 1)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 2)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    }

    $pdf = PDF::loadView('supplierwise_sale1pdf', compact('supplier', 'pagetitle'));
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

    return view('tax_report', compact('pagetitle', 'invoice_type', 'item'));
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


 

    
      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where($where)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
   

    return View('tax_report1', compact('invoice_detail', 'pagetitle'));
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

    if (($request->ItemID > 0) && ($request->InvoiceTypeID == 'both')) {

      $invoice_detail = DB::table('v_invoice_detail')
        ->where('ItemID', $request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->ItemID > 0) && ($request->InvoiceTypeID == 1)) {

      $invoice_detail = DB::table('v_invoice_detail')
        ->where('ItemID', $request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->ItemID > 0) && ($request->InvoiceTypeID == 2)) {

      $invoice_detail = DB::table('v_invoice_detail')
        ->where('ItemID', $request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->ItemID == 0) && ($request->InvoiceTypeID == 'both')) {

      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->where('InvoiceTypeID',$request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->ItemID == 0) && ($request->InvoiceTypeID == 1)) {

      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->ItemID == 0) && ($request->InvoiceTypeID == 2)) {

      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    }

    $pdf = PDF::loadView('tax_report1pdf', compact('invoice_detail', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();
  }

  public function SalemanInvoiceBalance()
  {

    session::put('menu', 'SalemanReport');
    $pagetitle = 'Saleman Invoice Balance';
    $invoice_type = DB::table('invoice_type')->get();
    $item = DB::table('item')->get();
    $saleman = DB::table('user')->where('UserType','Saleman')->get();

    return view('saleman_invoice_balance', compact('pagetitle', 'invoice_type', 'saleman', 'item'));
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
    ->groupBy('v_invoice_master.FullName')
    ->get();

    return view ('saleman_invoice_balance1',compact('pagetitle','invoice_master'));
    }


  public  function SalemanInvoiceList($user,$start,$end)
  {

    $pagetitle = 'Saleman Invoice Balance';

    $invoice_master = DB::table('v_invoice_master')
    ->join('v_invoice_balance', 'v_invoice_master.InvoiceMasterID', '=', 'v_invoice_balance.InvoiceMasterID')
    ->whereBetween('Date', array($start, $end))
    ->where('FullName',$user)
     ->get();

    return view ('saleman_invoice_list1',compact('pagetitle','invoice_master'));
    }



  public function SalemanReport()
  {

    session::put('menu', 'SalemanReport');
    $pagetitle = 'Saleman Report';
    $invoice_type = DB::table('invoice_type')->get();
    $item = DB::table('item')->get();
    $saleman = DB::table('user')->where('UserType','Saleman')->get();

    return view('saleman_report', compact('pagetitle', 'invoice_type', 'saleman', 'item'));
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

    if (($request->UserID > 0) && ($request->InvoiceTypeID == 'both')) {

      $invoice_detail = DB::table('v_invoice_detail')
        ->where('UserID', $request->UserID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID > 0) && ($request->InvoiceTypeID == 1)) {

      $invoice_detail = DB::table('v_invoice_detail')
        ->where('UserID', $request->UserID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID > 0) && ($request->InvoiceTypeID == 2)) {

      $invoice_detail = DB::table('v_invoice_detail')
        ->where('UserID', $request->UserID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID == 0) && ($request->InvoiceTypeID == 'both')) {

      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->where('InvoiceTypeID',$request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID == 0) && ($request->InvoiceTypeID == 1)) {

      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID == 0) && ($request->InvoiceTypeID == 2)) {

      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    }

    //       $pdf = PDF::loadView ('saleman_report1',compact('invoice_detail','pagetitle'));
    // //return $pdf->download('pdfview.pdf');
    //   // $pdf->setpaper('A4', 'portiate');
    //       return $pdf->stream();

    return View('saleman_report1', compact('invoice_detail', 'pagetitle'));
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

    $pagetitle = 'Saleman Report';

    if (($request->UserID > 0) && ($request->InvoiceTypeID == 'both')) {

      $invoice_detail = DB::table('v_invoice_detail')
        ->where('UserID', $request->UserID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID > 0) && ($request->InvoiceTypeID == 1)) {

      $invoice_detail = DB::table('v_invoice_detail')
        ->where('UserID', $request->UserID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID > 0) && ($request->InvoiceTypeID == 2)) {

      $invoice_detail = DB::table('v_invoice_detail')
        ->where('UserID', $request->UserID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID == 0) && ($request->InvoiceTypeID == 'both')) {

      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->where('InvoiceTypeID',$request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID == 0) && ($request->InvoiceTypeID == 1)) {

      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    } elseif (($request->UserID == 0) && ($request->InvoiceTypeID == 2)) {

      $invoice_detail = DB::table('v_invoice_detail')
        // ->where('ItemID',$request->ItemID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->orderBy('InvoiceMasterID')
        ->orderBy('Date')
        ->get();
    }

    $pdf = PDF::loadView('saleman_report1pdf', compact('invoice_detail'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();
  }

  public function AirlineSummary()
  {

    session::put('menu', 'AirlineSummary');
    $pagetitle = 'Airline Summary';
    $invoice_type = DB::table('invoice_type')->get();
    $supplier = DB::table('supplier')->get();

    return view('airline_summary', compact('pagetitle', 'invoice_type', 'supplier'));
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

    if (($request->SupplierID > 0) && ($request->InvoiceTypeID == 'both')) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID > 0) && ($request->InvoiceTypeID == 1)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID > 0) && ($request->InvoiceTypeID == 2)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 'both')) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 1)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 2)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    }

    // $pdf = PDF::loadView ('airline_summary1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('airline_summary1', compact('supplier', 'pagetitle'));
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

    if (($request->SupplierID > 0) && ($request->InvoiceTypeID == 'both')) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID > 0) && ($request->InvoiceTypeID == 1)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID > 0) && ($request->InvoiceTypeID == 2)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        ->where('SupplierID', $request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 'both')) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 1)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    } elseif (($request->SupplierID == 0) && ($request->InvoiceTypeID == 2)) {

      $supplier = DB::table('v_invoice_detail')
        ->select('SupplierID', 'InvoiceTypeCode', 'SupplierName', DB::raw('sum(Fare) as VHNO'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Service) as Service'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(OPVAT) as OPVAT'), DB::raw('sum(IPVAT) as IPVAT'), DB::raw('sum(Discount) as Discount'), DB::raw('sum(Total) as Total'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('InvoiceTypeID', $request->InvoiceTypeID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->groupBy('SupplierID', 'InvoiceTypeCode', 'SupplierName')
        ->get();
    }

    $pdf = PDF::loadView('airline_summary1pdf', compact('supplier', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return View('airline_summary1pdf', compact('supplier', 'pagetitle'));
  }

  public function VoucherReport()
  {
    session::put('menu', 'VoucherReport');
    $pagetitle = 'Voucher Report';
    $voucher_type = DB::table('voucher_type')->get();
    return view('voucher_report', compact('pagetitle', 'voucher_type'));
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

    $voucher_master = DB::table('voucher_master')
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->where('VoucherCodeID', $request->VoucherTypeID)

      ->get();

    return view('voucher_report1', compact('pagetitle', 'voucher_type', 'voucher_master', 'pagetitle'));
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

    // dd(  $voucher_type);

    $voucher_master = DB::table('voucher_master')
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->where('VoucherCodeID', $request->VoucherTypeID)

      ->get();

    // dd($voucher_master);

    $pdf = PDF::loadView('voucher_report1pdf', compact('pagetitle', 'voucher_type', 'voucher_master'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('voucher_report1pdf', compact('pagetitle', 'voucher_type', 'voucher_master'));
  }

  public function CashbookReport()
  {

    session::put('menu', 'CashbookReport');
    $pagetitle = 'Cashbook Report';
    $chartofaccount = DB::table('chartofaccount')
      ->whereIn('Category', ['CASH','BANK','CARD'])
      ->get();

    return view('cashbook_report', compact('pagetitle', 'chartofaccount'));
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
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->orderBy('Date', 'asc')
        ->get();
    } else {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereIn('ChartOfAccountID', [110101, 110250, 110201, 110101])
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', [110101, 110250, 110201, 110101])
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
    return view('cashbook_report1', compact('journal', 'pagetitle', 'sql'));
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
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->orderBy('Date', 'asc')
        ->get();
    } else {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereIn('ChartOfAccountID', [110101, 110250, 110201, 110101])
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', [110101, 110250, 110201, 110101])
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

    $pdf = PDF::loadView('cashbook_report1pdf', compact('journal', 'pagetitle', 'sql'));
    //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
    return view('cashbook_report1pdf', compact('journal', 'pagetitle', 'sql'));
  }

  public function DaybookReport()
  {
    session::put('menu', 'CashbookReport');
    $pagetitle = 'Cashbook Report';
    $chartofaccount = DB::table('chartofaccount')
      ->whereIn('Category', ['BANK','CASH','CARD'])
      ->get();

    return view('daybook_report', compact('pagetitle', 'chartofaccount'));
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
      ->get();

    $invoice_detail_summary = DB::table('v_invoice_detail')
      ->select(DB::raw('sum(if(ISNULL(Total),0,Total)) as Total'), DB::raw('sum(if(ISNULL(Fare),0,Fare)) as Fare'), DB::raw('sum(if(ISNULL(Service),0,Service)) as Service'))
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->get();

    if ($request->ChartOfAccountID > 0) {

      $sql = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->get();
    } else {

      $sql = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
      ->whereIn('Category', ['BANK','CASH','CARD'])
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->whereIn('Category', ['BANK','CASH','CARD'])
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('v_journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->whereIn('Category', ['BANK','CASH','CARD'])
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

    return view('daybook_report1', compact('journal', 'pagetitle', 'sql', 'invoice_detail', 'row', 'invoice_detail_summary', 'journal_summary'));
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
      ->get();

    $invoice_detail_summary = DB::table('v_invoice_detail')
      ->select(DB::raw('sum(if(ISNULL(Total),0,Total)) as Total'), DB::raw('sum(if(ISNULL(Fare),0,Fare)) as Fare'), DB::raw('sum(if(ISNULL(Service),0,Service)) as Service'))
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->get();

    if ($request->ChartOfAccountID > 0) {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->where('ChartOfAccountID', $request->ChartOfAccountID)
        ->get();
    } else {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        ->whereIn('ChartOfAccountID', [110101, 110250, 110201, 110101])
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', [110101, 110250, 110201, 110101])
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', [110101, 110250, 110201, 110101])
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

    $pdf = PDF::loadView('daybook_report1pdf', compact('journal', 'pagetitle', 'sql', 'invoice_detail', 'row', 'invoice_detail_summary', 'journal_summary'));
    // //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();
    // dd(count($invoice).'-'.count($journal));

    // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();

    return view('daybook_report1', compact('journal', 'pagetitle', 'sql', 'invoice_detail', 'row', 'invoice_detail_summary', 'journal_summary'));
  }

  public function GeneralLedger()
  {

    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';
    $chartofaccount = DB::table('chartofaccount')
      // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
      ->get();

    return view('general_ledger', compact('pagetitle', 'chartofaccount'));
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
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->get();
    } else {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->get();
    }

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    return view('general_ledger1', compact('journal', 'pagetitle', 'sql', 'journal_summary'));
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
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->get();
    } else {

      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))

        ->get();

      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->orderBy('Date', 'asc')
        ->get();

      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->get();
    }

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    $pdf = PDF::loadView('general_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'journal_summary'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('general_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'journal_summary'));
  }

  public function TrialBalance()
  {
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';
    $chartofaccount = DB::table('v_chartofaccount')

      ->get();

    return view('trial_balance', compact('pagetitle', 'chartofaccount'));
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
        ->groupBy('ChartOfAccountID', 'ChartOfAccountName')
        ->get();

      // dd(substr($request->ChartOfAccountID,0,1));

    } else {
      $trial = DB::table('v_journal')
        ->select('ChartOfAccountID', 'ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))>=0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Debit'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))<0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Credit'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->where('ChartOfAccountID',$request->ChartOfAccountID)
        ->groupBy('ChartOfAccountID', 'ChartOfAccountName')
        ->get();
    }

    return view('trial_balance1', compact('trial', 'pagetitle'));
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

        ->where('ChartOfAccountID', 'LIKE', substr($request->ChartOfAccountID, 0, 1) . '%')
        ->groupBy('ChartOfAccountID', 'ChartOfAccountName')
        ->get();

      // dd(substr($request->ChartOfAccountID,0,1));

    } else {
      $trial = DB::table('v_journal')
        ->select('ChartOfAccountID', 'ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))>=0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Debit'), DB::raw('if(sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr))<0,sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)),0) as Credit'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->where('ChartOfAccountID',$request->ChartOfAccountID)
        ->groupBy('ChartOfAccountID', 'ChartOfAccountName')
        ->get();
    }

    $pdf = PDF::loadView('trial_balance1pdf', compact('trial', 'pagetitle'));

    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('trial_balance1pdf', compact('trial', 'pagetitle'));
  }

  public function TicketRegister()
  {

    session::put('menu', 'AirlineSummary');
    $pagetitle = 'Airline Summary';
    $invoice_type = DB::table('invoice_type')->get();
    $item = DB::table('item')->get();
    $saleman = DB::table('user')->where('UserType','Saleman')->get();
    $supplier = DB::table('supplier')->get();

    return view('ticket_register', compact('pagetitle', 'invoice_type', 'supplier', 'item', 'saleman'));
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

      $where = Arr::add($where, 'SupplierID', $request->SupplierID);
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
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();

    $invoice_summary = DB::table('v_invoice_detail1')
      ->select(DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();

    // $pdf = PDF::loadView ('airline_summary1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('ticket_register1', compact('invoice_detail', 'invoice_summary', 'pagetitle'));
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

      $where = Arr::add($where, 'SupplierID', $request->SupplierID);
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
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();

    $invoice_summary = DB::table('v_invoice_detail')
      ->select(DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Service)-sum(Taxable) as Profit'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->orderBy('InvoiceMasterID')
      ->orderBy('Date')
      ->get();

    $pdf = PDF::loadView('ticket_register1pdf', compact('invoice_detail', 'invoice_summary'));
    //return $pdf->download('pdfview.pdf');
    $pdf->setpaper('A4', 'landscape');
    return $pdf->stream();

    return View('ticket_register1pdf', compact('invoice_detail', 'invoice_summary'));
  }

  public function TrialBalanceActivity()
  {
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';
    $chartofaccount = DB::table('v_chartofaccount')

      ->get();

    return view('trial_balance_activity', compact('pagetitle', 'chartofaccount'));
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

    $chartofaccount = DB::select('SELECT ChartOfAccountID,ChartOfAccountName from chartofaccount where ChartOfAccountID in (select ChartOfAccountID from journal where Date between "' . $request->StartDate . '" and "' . $request->EndDate . '") union SELECT ChartOfAccountID,ChartOfAccountName from chartofaccount where ChartOfAccountID in (select ChartOfAccountID from journal where Date < "' . $request->StartDate . '"   )');

    return view('trial_balance_activity1', compact('chartofaccount', 'pagetitle'));
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

    $pdf = PDF::loadView('trial_balance_activity1pdf', compact('chartofaccount', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return view('trial_balance_activity1pdf', compact('chartofaccount', 'pagetitle'));
  }

  public function InvoiceSummary()
  {

    session::put('menu', 'AirlineSummary');
    $pagetitle = 'Airline Summary';
    $invoice_type = DB::table('invoice_type')->get();
    $user = DB::table('user')->get();

    return view('invoice_summary', compact('pagetitle', 'invoice_type', 'user'));
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
      ->groupBy('SalemanName', 'UserID')
      ->orderBy('Date')
      ->get();

    $invoice_total = DB::table('v_invoice_detail')
      ->select(DB::raw('count(InvoiceDetailID) as Qty'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Service)-sum(Taxable) as Profit'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->where($where)
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))

      ->orderBy('Date')
      ->get();

    // $pdf = PDF::loadView ('airline_summary1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('invoice_summary1', compact('invoice_summary', 'invoice_total', 'pagetitle'));
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

    $pdf = PDF::loadView('invoice_summary1pdf', compact('invoice_summary', 'invoice_total', 'pagetitle'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    return $pdf->stream();

    return View('invoice_summary1', compact('invoice_summary', 'invoice_total', 'pagetitle'));
  }

  public  function tmp()
  {

    return view('tmp');
  }

  public  function ProfitAndLoss()
  {

    $pagetitle = 'Proft & Loss';

    return view('profit_loss', compact('pagetitle'));
  }

  public  function ProfitAndLoss1(request $request)
  {

    $pagetitle = 'Proft & Loss';

    $chartofaccountr = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "R"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');

    $chartofaccounte = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "E"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');

    //where Date between "'.$request->StartDate.'" and "'.$request->EndDate.'"
    return view('profit_loss11', compact('chartofaccountr', 'chartofaccounte', 'pagetitle'));
  }

  public  function BalanceSheet()
  {

    $pagetitle = 'Proft & Loss';

    return view('balance_sheet', compact('pagetitle'));
  }

  public  function BalanceSheet1(request $request)
  {
    $pagetitle = 'Proft & Loss';

    //profit and loss total will be used in balance sheet
    $activityr = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
      ->whereBetween('Date', array(request()->StartDate, request()->EndDate))
      ->where('CODE', 'R')
      ->get();
    $activitye = DB::table('v_journal')
      ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance'))
      ->whereBetween('Date', array(request()->StartDate, request()->EndDate))
      ->where('CODE', 'E')
      ->get();
    $profit_loss = $activityr[0]->Balance - $activitye[0]->Balance;
    // end of profit of loss balance

    $chartofaccounta = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "A"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');

    $chartofaccountl = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "L"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');
    $chartofaccountc = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "C"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');
    $chartofaccounts = DB::select('SELECT CODE,ChartOfAccountID,ChartOfAccountName from chartofaccount where  CODE = "S"  and right(L2,4)=0000 and right(L2,5)!=00000 and  ChartOfAccountID in (select L2 from v_journal )  ');

    //where Date between "'.$request->StartDate.'" and "'.$request->EndDate.'"
    return view('balance_sheet11', compact('chartofaccounta', 'chartofaccountl', 'chartofaccountc', 'chartofaccounts', 'pagetitle', 'profit_loss'));
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

    return view('balancesheet_detail', compact('company', 'journal', 'pagetitle', 'StartDate', 'EndDate'));
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

    return view('balancesheet_detail', compact('company', 'journal', 'pagetitle', 'StartDate', 'EndDate'));
  }

  public function ReconcileReport()
  {
    session::put('menu', 'GeneralLedger');
    $pagetitle = 'General Ledger';
    $chartofaccount = DB::table('chartofaccount')
      ->whereIn('Category', ['BANK', 'CARD', 'CASH'])
      ->orderBy('ChartOfAccountName', 'ASC')
      ->get();
    return view('reconcile_report', compact('pagetitle', 'chartofaccount'));
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
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))
        ->get();
      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->orderBy('Date', 'asc')
        ->get();
      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        ->whereIn('ChartOfAccountID', array($request->ChartOfAccountID, $request->ChartOfAccountID1))
        ->get();
    } else {
      $sql = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
        // ->where('SupplierID',$request->SupplierID)
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->where('Date', '<', $request->StartDate)
        // ->whereBetween('date',array($request->StartDate,$request->EndDate))
        ->get();
      $journal = DB::table('v_journal')
        // ->where('SupplierID',$request->SupplierID)
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->orderBy('Date', 'asc')
        ->get();
      $journal_summary = DB::table('journal')
        ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
        ->whereBetween('Date', array($request->StartDate, $request->EndDate))
        // ->whereIn('ChartOfAccountID',[110101,110250,110201,110101])
        ->get();
    }

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    return view('reconcile_report1', compact('journal', 'pagetitle', 'sql', 'journal_summary'));
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
    $supplier = DB::table('supplier')->get();
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
          'Trace' => 615
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
 
       $query = DB::table('v_expense_detail');

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

    $supplier = DB::table('supplier')->get();
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
      'Trace' => 614
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
          'Trace' => 615
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
          'Trace' => 617
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
          'Trace' => 615
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
      ->where('Date', '<', '2022-01-01')
      ->whereBetween('date', array('2022-01-01', '2028-12-01'))
      ->get();

    $journal = DB::table('v_journal')
      ->where('PartyID', $PartyID)
      ->whereBetween('Date', array('2022-01-01', '2028-12-01'))
      ->where('ChartOfAccountID', 110400)
      ->orderBy('Date')
      ->get();

    $company = DB::table('company')->get();

    $party = DB::table('party')->where('PartyID', $PartyID)->get();

    $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

    return View('party_sales_ledger2pdf', compact('journal', 'pagetitle', 'sql', 'party', 'company'));

    //   $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

  }

  function ExpenseReport()
  {
    $pagetitle = 'Expense';
    return view('expense.expense_report', compact('pagetitle'));
  }

  public function ExpenseReport1(request $request)
  {

    $pagetitle = 'Expense Report';

    $company = DB::table('company')->get();

    $expense_detail = DB::table('v_expense_detail')

      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
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
    return view('saleman_ticketregister', compact('pagetitle'));
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
         
         

         


    $pagetitle = 'Ticket Register';

    $invoice_detail = DB::table('v_invoice_detail')
      ->select('SalemanName', DB::raw('count(*) as TotalInvoices'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      ->groupBy('SalemanName')
      // ->orderBy('Date')
      ->get();

    $invoice_summary = DB::table('v_invoice_detail')
      ->select(DB::raw('count(*) as TotalInvoices'), DB::raw('sum(Fare) as Fare'), DB::raw('sum(Service) as Service'), DB::raw('sum(Total) as Total'), DB::raw('sum(Taxable) as Taxable'), DB::raw('sum(Discount) as Discount'))
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
      // ->orderBy('Date')
      ->get();

 
      if(count($invoice_detail)==0)
      {
        return back()->with('error', 'No invocies found for these dates')->with('class', 'danger');
      }

    // $pdf = PDF::loadView ('airline_summary1',compact('supplier'));
    //return $pdf->download('pdfview.pdf');
    // $pdf->setpaper('A4', 'portiate');
    // return $pdf->stream();

    return View('saleman_ticketregister1', compact('invoice_detail', 'invoice_summary', 'pagetitle'));

        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->with('class', 'danger');
        }



  }

  public  function ajax_party_list()
  {
    $party = DB::table('party')->get();

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


                
    return view ('itemwise_sale',compact('pagetitle','item'));
    }


  public  function ItemwiseSale2(request $request)

    {

       $pagetitle='Itemwise Sale';

         $today_sale = DB::table('v_invoice_detail22')
    ->select(
        'ItemName','ItemID',
        DB::raw('count(*) as Total'),
        DB::raw('sum(Total) as Invoice'),
        DB::raw('sum(Service) as Profit')
    )
      ->whereBetween('Date', array($request->StartDate, $request->EndDate))
     ->groupBy('ItemName','ItemID')
    ->get();

 

                
    return view ('itemwise_sale2',compact('pagetitle','today_sale'));
    }



public function InvoiceDetailList($itemid, $startdate,$enddate)
{



  $pagetitle='Item Detail Invoice Detail';

  
  
  $paymentmode = DB::table('v_invoice_detail')
    ->whereBetween('Date', [$startdate, $enddate])
    ->where('ItemID', $itemid)
    ->distinct()
    ->get('PaymentMode');

 
 

  return view ('invoice_detail_item_wise',compact('pagetitle','paymentmode'));




}


function Log()
{
  $pagetitle='Log';
  $user = DB::table('user')->get();

  return view ('log.log',compact('pagetitle','user'));

}

function Log1(request $request)
{
  $pagetitle='Log';



  $log = DB::table('log')
 ->whereBetween(DB::raw('DATE_FORMAT(Date,"%Y-%m-%d")'), [$request->StartDate,$request->EndDate])
 ->when($request->has('UserID') && $request->UserID != null, function ($query) use ($request) {
                        $query->where('UserID', $request->UserID);
                    })
  ->get();
  
  return view('log.log1',compact('pagetitle','log'));

}
  


}
