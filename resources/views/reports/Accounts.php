<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
// for API data receiving from http source
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
// use Datatables;
use Yajra\DataTables\DataTables;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
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
use File;
use PDF;
use Illuminate\Support\Facades\Hash;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports\ExcelLedger;
use App\Exports\PartyBalanceExcel;

use App\Exports\SalemanExport;
use App\Exports\PartyLedgerExcel;

class Accounts extends Controller
{

    public function __construct()
    {
        if (session::get('UserID') == 1) {
            echo  "null";
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */



    public  function CreateHashPassword($password)
    {
                
                echo "<pre>";
     print_r( Hash::make($password));    

    
    }


    public function CheckUserRole1($userid, $tablename, $action)
    {
        // $allow= check_role(session::get('UserID'),'Petty Cash','List');
        $allow = DB::table('user_role')->where('UserID', $userid)
            ->where('Table', $tablename)
            ->where('Action', $action)
            ->get();
        if ($allow == 0) {
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


        $company = DB::table('company')->get();
        return view('login.login', compact('company'));
    }
    public function UserVerify(request $request)
    {
        //

        $input = $request->only(['username', 'password','type']);


        $username = $input['username'];
        $password =  $input['password'];
        $type =  $input['type'];

        $company = DB::table('company')->get();





        if($request->type!='Agent')
        {

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
            Session::put('Currency', $company[0]->Currency);
            Session::put('CompanyName', $company[0]->Name . ' ' . $company[0]->Name2);



            return redirect('Dashboard')->with('error', 'Welcome to ' . session::get('CompanyName') . ' Software')->with('class', 'success');
                } else {
                    //session::flash('error', 'Invalid username or Password. Try again');
                    return redirect('Login')->withinput($request->all())->with('error', 'Invalid username or Password. Try again')->with('class', 'danger');
                }   




        }
        else
        {
             
            $data = DB::table('users')
                        ->where('email', '=', $username)
                        ->where('password', '=', $password)                        
                        ->get();

         

              if (count($data) > 0) {
            Session::put('FullName', $data[0]->name);
            Session::put('UserID', $data[0]->id);
            Session::put('Email', $data[0]->email);
            Session::put('UserType', $data[0]->role);
            Session::put('Type', $data[0]->role);
            Session::put('Currency', $company[0]->Currency);
            Session::put('CompanyName', $company[0]->Name . ' ' . $company[0]->Name2);



            return redirect('Dashboard')->with('error', 'Welcome to ' . session::get('CompanyName') . ' Software')->with('class', 'success');
        } else {
            //session::flash('error', 'Invalid username or Password. Try again');
            return redirect('Login')->withinput($request->all())->with('error', 'Invalid username or Password. Try again')->with('class', 'success');
        }            


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
        if ($allow == 0) {
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
  <a href="' . URL('/PettyDelete/' . $row->PettyMstID) . '"><i class="bx bx-trash align-middle me-1 text-secondary"></i></a>





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
        if ($allow == 0) {
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

        session::get('VHNO', $vhno[0]->VHNO);

        return view('pettycash_create', compact('voucher_type', 'items', 'supplier', 'vhno', 'pagetitle', 'chartofaccount'))->with('error', 'Logout Successfully.')->with('class', 'success');
    }
    public  function PettyCashSave(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Petty Cash', 'Create');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////

        // dd($request->all());
        $invoice_mst = array(
            'PettyVoucher' => $request->input('Voucher'),
            'ChOfAcc' => $request->input('ChartOfAcc'),
            'Date' => $request->input('ToDate'),
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
                'Date' =>  $request->input('ToDate'),
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
        if ($allow == 0) {
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////

        // dd($request->all());
        $invoice_mst = array(
            'PettyVoucher' => $request->input('PettyVoucher'),
            'ChOfAcc' => $request->input('ChartOfAcc'),
            'Date' => $request->input('ToDate'),
            'Narration' => $request->input('Narration_mst'),
            'Credit' => $request->input('TotalDebit'),

        );
        // dd($invoice_mst);
        // $id= DB::table('')->insertGetId($data);

        $id1 = DB::table('pettycash_master')->where('PettyMstID', $request->input('PettyMstID'))->update($invoice_mst);
        $id2 = DB::table('pettycash_detail')->where('PettyMstID', $request->input('PettyMstID'))->delete();
        for ($i = 0; $i < count($request->ChartOfAcc2); $i++) {
            $invoice_det = array(
                'PettyMstID' => $request->input('PettyMstID'),
                'PettyVoucher' => $request->input('PettyVoucher'),
                'Date' =>  $request->input('ToDate'),
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

        $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,5)'), '<>', 00000)->get();
        $supplier = DB::table('supplier')->get();
        $party = DB::table('party')->get();
        $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();



        return view('jv_create', compact('voucher_type', 'chartofaccount', 'supplier', 'vhno', 'pagetitle', 'party'))->with('error', 'Logout Successfully.')->with('class', 'success');
    }
    public  function Voucher()
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        // $allow = check_role(session::get('UserID'), 'Voucher', 'List');
        // if ($allow == 0) {
        //     return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        // }
        // ////////////////////////////END SCRIPT ////////////////////////////////////////////////
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
            $data = DB::table('v_voucher')->orderBy('VoucherMstID')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>
                    $btn = '

<div class="d-flex align-items-center col-actions">


<a href="' . URL('/VoucherView/' . $row->VoucherMstID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/VoucherEdit/' . $row->VoucherMstID) . '"><i class="font-size-18 mdi mdi-pencil align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/VoucherDelete/' . $row->VoucherMstID) . '"><i class="font-size-18 mdi mdi-trash-can-outline align-middle me-1 text-secondary"></i></a>




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
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        // $allow = check_role(session::get('UserID'), 'Voucher', 'Create');
        // if ($allow == 0) {
        //     return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        // }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////

        if ($vouchertype == 'BR') {
            $chartofaccount1 = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,3)'), '<>', 000)
                ->whereIn('Category', ['BANK'])->get();
        } elseif ($vouchertype == 'BP') {
            $chartofaccount1 = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,3)'), '<>', 000)
                ->whereIn('Category', ['BANK'])->get();
        } elseif ($vouchertype == 'CR') {
            $chartofaccount1 = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,3)'), '<>', 000)
                ->whereIn('Category', ['CASH'])->get();
        } elseif ($vouchertype == 'CP') {
            $chartofaccount1 = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,3)'), '<>', 000)
                ->whereIn('Category', ['CASH'])->get();
        }
        session::put('menu', 'Vouchers');
        $pagetitle = 'Vouchers';
        $voucher_type = DB::table('voucher_type')->where('VoucherCode', $vouchertype)->get();
         // dd($voucher_type[0]->VoucherTypeID);

            $vhno = DB::table('voucher_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(SUBSTR(Voucher,7)),0)+1,4,0) as vhno'))
            ->where('VoucherCodeID', $voucher_type[0]->VoucherTypeID)
            ->get();

 // dd( $vhno[0]->vhno);

        $supplier = DB::table('supplier')->get();
        $party = DB::table('party')->get();
        $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();

        $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,3)'), '<>', 000)
            ->get();

        return view('voucher_create', compact('voucher_type', 'chartofaccount', 'chartofaccount1', 'supplier', 'vhno', 'pagetitle', 'party'))->with('error', 'Logout Successfully.')->with('class', 'success');
    }
    public function VoucherSave(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        // $allow = check_role(session::get('UserID'), 'Voucher', 'Create');
        // if ($allow == 0) {
        //     return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        // }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        // dd($request->all());
        $voucher_mst = array(
            'VoucherCodeID' => $request->input('VoucherType'),
            'Voucher' => $request->input('Voucher'),
            'Narration' => $request->input('Narration_mst'),
            'Date' => $request->input('VHDate'),

        );
        $id = DB::table('voucher_master')->insertGetId($voucher_mst);
        if ((substr($request->Voucher, 0, 2) == 'BP') || ((substr($request->Voucher, 0, 2) == 'CP'))) {
            // start for loop
            for ($i = 0; $i < count($request->ChOfAcc); $i++) {
                $voucher_det_dr = array(
                    'VoucherMstID' => $id,
                    'Voucher' => $request->input('Voucher'),
                    'Date' =>  $request->input('VHDate'),
                    'ChOfAcc' => $request->ChOfAcc[$i],
                    'SupplierID' => $request->SupplierID[$i],
                    'PartyID' => $request->PartyID[$i],
                    'Narration' => $request->Narration[$i] . $request->input('Narration_mst'),
                    'InvoiceNo' => $request->Invoice[$i],
                    'RefNo' => $request->RefNo[$i],
                    'Debit' => $request->Debit[$i],
                );
                $id1 = DB::table('voucher_detail')->insert($voucher_det_dr);
                $voucher_det_cr = array(
                    'VoucherMstID' => $id,
                    'Voucher' => $request->input('Voucher'),
                    'Date' =>  $request->input('VHDate'),
                    'ChOfAcc' => $request->ChartOfAccount1,
                    'SupplierID' => $request->SupplierID[$i],
                    'PartyID' => $request->PartyID[$i],
                    'Narration' => $request->Narration[$i] . $request->input('Narration_mst'),
                    'InvoiceNo' => $request->Invoice[$i],
                    'RefNo' => $request->RefNo[$i],
                    'Credit' => $request->Debit[$i],
                );
                $id2 = DB::table('voucher_detail')->insert($voucher_det_cr);
            }
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
                    'Narration' => $request->Narration[$i] . $request->input('Narration_mst'),
                    'InvoiceNo' => $request->Invoice[$i],
                    'RefNo' => $request->RefNo[$i],
                    'Debit' => $request->Debit[$i],
                );
                $id2 = DB::table('voucher_detail')->insert($voucher_det_dr);
                $voucher_det_cr = array(
                    'VoucherMstID' => $id,
                    'Voucher' => $request->input('Voucher'),
                    'Date' =>  $request->input('VHDate'),
                    'ChOfAcc' => $request->ChOfAcc[$i],
                    'SupplierID' => $request->SupplierID[$i],
                    'PartyID' => $request->PartyID[$i],
                    'Narration' => $request->Narration[$i] . $request->input('Narration_mst'),
                    'InvoiceNo' => $request->Invoice[$i],
                    'RefNo' => $request->RefNo[$i],
                    'Credit' => $request->Debit[$i],
                );
                $id1 = DB::table('voucher_detail')->insert($voucher_det_cr);
            }
            // end for each
        }
        return redirect('Voucher')->with('error', 'Record Saved')->with('class', 'success');
    }


    public function JVSave(request $request)
    {

        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Voucher', 'Create');
        if ($allow == 0) {
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




        // start for loop
        for ($i = 0; $i < count($request->ChOfAcc); $i++) {


            $data = array(
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


            $id1 = DB::table('voucher_detail')->insert($data);
        }
        // end for each




        return redirect('Voucher')->with('error', 'Record Saved')->with('class', 'success');
    }

    public  function VoucherEdit($id)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Voucher', 'Update');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////


        // Get the invoice details
        $invoice_mst = DB::table('voucher_master')->where('VoucherMstID', $id)->first();
        
        // Check if invoice exists
        if (!$invoice_mst) {
            return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
        }

        // Check if invoice belongs to a previous year
        $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
         $current_year = date('Y');

        // if ($invoice_year < $current_year) {
        //     return redirect()->back()->with('error', 'You cannot edit an invoice from a previous year.')->with('class', 'danger');
        // }

        //  if ($invoice_year < $current_year) {
        // if (env('ENV_ENABLE_EDIT') !== 'YES') {
        //     return redirect()->back()
        //         ->with('error', 'You cannot edit an invoice from a previous year.')
        //         ->with('class', 'danger');
        //     }
        // }



        session::put('menu', 'Vouchers');
        $pagetitle = 'Vouchers';
        $voucher_type = DB::table('voucher_type')->get();
        $chartofaccount = DB::table('chartofaccount')->where('L3', '!=', 'L2')->where('L1', '!=', 'L2')->get();
        $supplier = DB::table('supplier')->get();
        $party = DB::table('party')->get();
        $voucher_master = DB::table('voucher_master')->where('VoucherMstID', $id)->get();
        $voucher_detail = DB::table('voucher_detail')->where('VoucherMstID', $id)->get();

        session::put('VHNO', $voucher_master[0]->Voucher);

        return view('voucher_edit', compact('voucher_type', 'chartofaccount', 'supplier', 'pagetitle', 'voucher_master', 'voucher_detail', 'party'));
    }

    public  function VoucherUpdate(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Voucher', 'Update');
        if ($allow == 0) {
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
        $idd1 = DB::table('journal')->where('VoucherMstID', $request->input('VoucherMstID'))->delete();
        for ($i = 0; $i < count($request->ChOfAcc); $i++) {
            $invoice_det = array(
                'VoucherMstID' => $request->input('VoucherMstID'),
                'Voucher' => $request->input('Voucher'),
                'Date' =>  $request->input('VHDate'),
                'ChOfAcc' => $request->ChOfAcc[$i],
                'SupplierID' => $request->SupplierID[$i],
                'PartyID' => $request->PartyID[$i],
                'Narration' => $request->Narration[$i] .  $request->input('Narration_mst'),
                'InvoiceNo' => $request->Invoice[$i],
                'RefNo' => $request->RefNo[$i],
                'Debit' => $request->Debit[$i],
                'Credit' => $request->Credit[$i],



            );
            // dd($invoice_det);
            $iddd = DB::table('voucher_detail')->insert($invoice_det);
        }




        return redirect('Voucher')->with('error', 'Record Updated')->with('class', 'success');
    }
    public function VoucherView($id)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        // $allow = check_role(session::get('UserID'), 'Voucher', 'View');
        // if ($allow == 0) {
        //     return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        // }
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



        $company = DB::table('company')->get();

        return view('voucher_view', compact('pagetitle', 'voucher_master', 'company'));
    }
    public  function VoucherDelete($id)
    {

        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Voucher', 'Delete');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////


          // Get the invoice details
          $invoice_mst = DB::table('voucher_master')->where('VoucherMstID', $id)->first();
        
          // Check if invoice exists
          if (!$invoice_mst) {
              return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
          }
  
          // Check if invoice belongs to a previous year
          $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
           $current_year = date('Y');
  
          if ($invoice_year < $current_year) {
              return redirect()->back()->with('error', 'You cannot delete an invoice from a previous year.')->with('class', 'danger');
          }



        session::put('menu', 'VoucherReport');
        $pagetitle = 'Voucher';
        $id1 = DB::table('voucher_master')->where('VoucherMstID', $id)->delete();
        $id2 = DB::table('voucher_detail')->where('VoucherMstID', $id)->delete();
        $id3 = DB::table('journal')->where('VoucherMstID', $id)->delete();
        return view('voucher', compact('pagetitle'))->with('error', 'Deleted Successfully.')->with('class', 'success');
    }

    public  function Invoice()
    {
        session::put('menu', 'Invoice');
        $pagetitle = 'Invoice';
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        // $allow = check_role(session::get('UserID'), 'Invoice', 'List');
        // if ($allow == 0) {
        //     return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        // }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////


        return view('invoice.invoice', compact('pagetitle'));
    }
    public function ajax_invoice(Request $request)

    {
        session::put('menu', 'Invoice');
        $pagetitle = 'Invoice';
        if ($request->ajax()) {
            $data = DB::table('v_invoice_master')
                ->where('InvoiceType', 'Invoice')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>


                    //a href="' . URL('/SaleInvoiceViewPDF/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a>

                     $btn = '

                       <div class="d-flex align-items-center col-actions">';

               $btn .= '<a href="' . URL('/DNCreateAuto/' . $row->InvoiceMasterID) . '">DN</a>';

                    $btn .= '<div class="d-flex align-items-center col-actions">

                    



<a  target="_blank" href="' . URL('/SaleInvoiceViewPDF/' . $row->InvoiceMasterID) . '/'.$row->BranchID.'"><i class="font-size-18 me-1 mdi mdi-file-pdf-outline align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/SaleInvoiceEdit/' . $row->InvoiceMasterID) . '"><i class="font-size-18 bx bx-pencil align-middle me-1 text-secondary"></i></a>


<a href="javascript:void(0)" onclick="delete_invoice(' . $row->InvoiceMasterID . ')" ><i class="font-size-18 bx bx-trash text-danger align-middle me-1 text-secondary"></i></a>


</div>
';

                    //class="edit btn btn-primary btn-sm"
                    // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('invoice.invoice', 'pagetitle');
    }
    public  function InvoiceCreate()
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'Create');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////

        session::put('menu', 'Invoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
         $party = DB::table('party')->get();
 
        $vhno = DB::table('invoice_master')->select(DB::raw('IFNULL(max(InvoiceMasterID)+1,1) as VHNO'))->get();



        return view('invoice_create', compact('invoice_type', 'items', 'vhno', 'party'))->with('error', 'Logout Successfully.')->with('class', 'success');
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
    public  function InvoiceSave(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'Create');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        // dd($request->all());
        if ($request->input('PaymentMode') == 'Cash') {
            $PaymentMode = '110101'; //Cash in hand
        } elseif ($request->input('PaymentMode') == 'Credit Card') {

            $PaymentMode = '110250'; //Credit Card ACCOUNT.
        } else {
            $PaymentMode = '110201'; //ENBD BANK
        }
        $invoice_type = DB::table('invoice_type')->where('InvoiceTypeID', $request->input('InvoiceTypeID'))->get();

        // dd($request->all());
        $invoice_mst = array(
            'InvoiceMasterID' => $request->input('VHNO'),
            'InvoiceTypeID' => $request->input('InvoiceTypeID'),
            'Date' => $request->input('Date'),
            'PartyID' => $request->input('PartyID'),
            'DueDate' => $request->input('DueDate'),
            'PaymentMode' => $request->input('PaymentMode'),
            'Total' => $request->input('Total'),
            'Paid' => $request->input('amountPaid'),
            'Balance' => $request->input('amountDue'),
            'UserID' => session::get('UserID'),
        );
        // $id= DB::table('')->insertGetId($data);

        $id = DB::table('invoice_master')->insertGetId($invoice_mst);
        // when full payment is made
        if (($request->input('InvoiceTypeID') == 1) && ($request->input('amountPaid') > 0)) {
            // Cash / Bank / Credit Card account -> Debit
            $data_cash = array(
                'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                'ChartOfAccountID' => $PaymentMode,   // Cash / bank / credit card
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
            );
            $journal_entry = DB::table('journal')->insertGetId($data_cash);
            // A/R -> Credit
            $data_ar = array(
                'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                'ChartOfAccountID' => '110400',   //A/R
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
                'Date' => $request->input('Date'),
                'Cr' => $request->input('amountPaid'),
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
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
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
            );
            $journal_entry = DB::table('journal')->insertGetId($SR_CASH);
        }
        // END OF SALE RETURN

        //  start for item array from invoice
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


            $idd = DB::table('invoice_detail')->insertGetId($invoice_det);
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
                );
                $id = DB::table('journal')->insertGetId($loop_AR);
                $loop_purchase_cr = array(
                    'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                    'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                    'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
                    'SupplierID' => $request->SupplierID[$i],
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $request->input('VHNO'),
                    'Date' => $request->input('Date'),
                    'Cr' => $request->Fare[$i],
                );
                $id = DB::table('journal')->insertGetId($loop_purchase_cr);
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
                    );
                    $id = DB::table('journal')->insertGetId($comission);
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
                );
                $id = DB::table('journal')->insertGetId($loop_purchase_dr);
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
                        'PartyID' => $request->input('PartyID'),
                        'InvoiceMasterID' => $request->input('VHNO'),
                        'Date' => $request->input('Date'),
                        'Cr' => $request->TaxAmount[$i],
                    );
                    $id = DB::table('journal')->insertGetId($tax_payable);
                    // tax credit from comission
                    $tax_expense = array(
                        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                        'ChartOfAccountID' => 410101, // COMISSION (TAX WILL MINUS FROM COMISSION)
                        'SupplierID' => $request->SupplierID[$i],
                        'PartyID' => $request->input('PartyID'),
                        'InvoiceMasterID' => $request->input('VHNO'),
                        'Date' => $request->input('Date'),
                        'Dr' => $request->TaxAmount[$i],
                    );
                    $id = DB::table('journal')->insertGetId($tax_expense);
                }
                // tax end here

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
                    );
                    $id = DB::table('journal')->insertGetId($comission);
                }
                // Services Charges
                if ($request->Discount[$i] > 0) {
                    $discount_rec = array(
                        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                        'ChartOfAccountID' => '410152', // Discount Received
                        'SupplierID' => $request->SupplierID[$i],
                        'PartyID' => $request->input('PartyID'),
                        'InvoiceMasterID' => $request->input('VHNO'),
                        'Date' => $request->input('Date'),
                        'Cr' => $request->Discount[$i],
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
                    'Dr' => $request->Fare[$i],
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
                );
                $id = DB::table('journal')->insertGetId($loop_ap);
            }
            // journal entry end here part 1
            // END SALE RETURN FOR EACH ROW
        }

        // end foreach



        return redirect('Invoice')->with('error', 'Invoice Saved')->with('class', 'success');
    }
    public  function InvoiceEdit($id)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'Update');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////




        session::put('menu', 'Invoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $supplier = DB::table('supplier')->get();

        $vhno = DB::table('invoice_master')->select(DB::raw('max(InvoiceMasterID)+1 as VHNO'))->get();

        $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_det = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();

        return view('invoice_edit', compact('invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det'))->with('error', 'Logout Successfully.')->with('class', 'success');
    }
    public  function InvoiceUpdate(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'Update');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        if ($request->input('PaymentMode') == 'Cash') {
            $PaymentMode = '110101'; //Cash in hand
        } elseif ($request->input('PaymentMode') == 'Credit Card') {

            $PaymentMode = '110250'; //Credit Card ACCOUNT.
        } else {
            $PaymentMode = '110201'; //ENBD BANK
        }

        // dd($request->all());
        $invoice_mst = array(
            'InvoiceTypeID' => $request->input('InvoiceTypeID'),
            'InvoiceMasterID' => $request->input('VHNO'),
            'Date' => $request->input('Date'),
            'PartyID' => $request->input('PartyID'),
            'DueDate' => $request->input('DueDate'),
            'PaymentMode' => $request->input('PaymentMode'),
            'Total' => $request->input('Total'),
            'Paid' => $request->input('amountPaid'),
            'Balance' => $request->input('amountDue'),
            'UserID' => session::get('UserID'),
        );
        // $id= DB::table('')->insertGetId($data);

        $id1 = DB::table('invoice_master')->where('InvoiceMasterID', $request->VHNO)->update($invoice_mst);
        $invoice_type = DB::table('invoice_type')->where('InvoiceTypeID', $request->input('InvoiceTypeID'))->get();
        $id2 = DB::table('invoice_detail')->where('InvoiceMasterID', $request->VHNO)->delete();
        $id3 = DB::table('journal')->where('InvoiceMasterID', $request->VHNO)->delete();


        // when full payment is made
        if (($request->input('InvoiceTypeID') == 1) && ($request->input('amountPaid') > 0)) {
            // Cash / Bank / Credit Card account -> Debit
            $data_cash = array(
                'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                'ChartOfAccountID' => $PaymentMode,   // Cash / bank / credit card
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
            );
            $journal_entry = DB::table('journal')->insertGetId($data_cash);
            // A/R -> Credit
            $data_ar = array(
                'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                'ChartOfAccountID' => '110400',   //A/R
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
                'Date' => $request->input('Date'),
                'Cr' => $request->input('amountPaid'),
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
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $request->input('VHNO'), #7A7A7A
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
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
                );
                $id = DB::table('journal')->insertGetId($loop_AR);
                $loop_purchase_cr = array(
                    'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                    'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                    'ChartOfAccountID' => '510103',  // PURCHASE OF TICKET
                    'SupplierID' => $request->SupplierID[$i],
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $request->input('VHNO'),
                    'Date' => $request->input('Date'),
                    'Cr' => $request->Fare[$i],
                );
                $id = DB::table('journal')->insertGetId($loop_purchase_cr);
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
                    );
                    $id = DB::table('journal')->insertGetId($comission);
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
                );
                $id = DB::table('journal')->insertGetId($loop_purchase_dr);
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
                        'PartyID' => $request->input('PartyID'),
                        'InvoiceMasterID' => $request->input('VHNO'),
                        'Date' => $request->input('Date'),
                        'Cr' => $request->TaxAmount[$i],
                    );
                    $id = DB::table('journal')->insertGetId($tax_payable);
                    // tax credit from comission
                    $tax_expense = array(
                        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                        'ChartOfAccountID' => 410101, // COMISSION (TAX WILL MINUS FROM COMISSION)
                        'SupplierID' => $request->SupplierID[$i],
                        'PartyID' => $request->input('PartyID'),
                        'InvoiceMasterID' => $request->input('VHNO'),
                        'Date' => $request->input('Date'),
                        'Dr' => $request->TaxAmount[$i],
                    );
                    $id = DB::table('journal')->insertGetId($tax_expense);
                }
                // tax end here

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
                    );
                    $id = DB::table('journal')->insertGetId($comission);
                }
                // Services Charges
                if ($request->Discount[$i] > 0) {
                    $discount_rec = array(
                        'VHNO' => $invoice_type[0]->InvoiceTypeCode . $request->input('VHNO'),
                        'JournalType' => $invoice_type[0]->InvoiceTypeCode,
                        'ChartOfAccountID' => '410152', // Discount Received
                        'SupplierID' => $request->SupplierID[$i],
                        'PartyID' => $request->input('PartyID'),
                        'InvoiceMasterID' => $request->input('VHNO'),
                        'Date' => $request->input('Date'),
                        'Cr' => $request->Discount[$i],
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
                    'Dr' => $request->Fare[$i],
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
                );
                $id = DB::table('journal')->insertGetId($loop_ap);
            }
            // journal entry end here part 1
            // END SALE RETURN FOR EACH ROW
        }
        // end for each




        return redirect('Invoice')->with('error', 'Invoice Updated')->with('class', 'success');
    }

    public  function InvoiceDelete($id)
    {

        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'Delete');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////


        
         // Get the invoice details
         $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->first();
        
         // Check if invoice exists
         if (!$invoice_mst) {
             return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
         }
 
         // Check if invoice belongs to a previous year
         $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
          $current_year = date('Y');
 
         if ($invoice_year < $current_year) {
             return redirect()->back()->with('error', 'You cannot delete an invoice from a previous year.')->with('class', 'danger');
         }

        $id1 = DB::table('invoice_master')->where('InvoiceMasterID', $id)->delete();
        $id2 = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->delete();
        $id3 = DB::table('journal')->where('InvoiceMasterID', $id)->delete();

        return redirect('Invoice')->with('error', 'Invoice Deleted')->with('class', 'success');
    }
    public  function InvoiceView($id)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'View');
        if ($allow == 0) {
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
    public  function InvoicePDF($id)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'PDF');
        if ($allow == 0) {
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
        // return View ('invoice_pdf',compact('invoice_type','items','supplier','vhno','invoice_mst','invoice_det'));
        $filename = $invoice_mst[0]->InvoiceCode . '-' . $invoice_mst[0]->Date . '-PartyCode-' . $invoice_mst[0]->PartyID;
        $pdf = PDF::loadView('invoice_pdf', compact('invoice_type', 'items', 'supplier', 'vhno', 'invoice_mst', 'invoice_det'));
        $pdf->setpaper('A4', 'portiate');
        return $pdf->download($filename . '.pdf');
        // return $pdf->stream();
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


    public  function ajax_invoice_vhno(request $request)
    {

         

        $d = array(
            'BranchID' => $request->BranchID,
             'InvoiceType' => $request->InvoiceTyp,

        );

        $branch = DB::table('branch')->select('BranchCode')->where('BranchID',$request->BranchID)->first();

        $data = DB::table('invoice_master')
                ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))->where('BranchID',$request->BranchID)
                ->where('InvoiceType',$request->InvoiceTyp)->get();

 
// $data = DB::select('select LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO  from invoice_master
// where InvoiceType=".$request->InvoiceTyp." and BranchID=".$request->BranchID."');


        



        // if ($request->InvoiceType == 'Invoice') {
        //     $d = 'INV';

        //     $data = DB::table('invoice_master')
        //         ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))->whereIn(DB::raw('left(InvoiceNo,3)'), ['INV'])->get();
        // } elseif ($request->InvoiceType == 'Tax Invoice') {
        //     $d = 'TAX';

        //     $data = DB::table('invoice_master')
        //         ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))->whereIn(DB::raw('left(InvoiceNo,3)'), ['TAX'])->get();
        // } else {
        //     $d = 'PRO';

        //     $data = DB::table('invoice_master')
        //         ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))->where(DB::raw('left(InvoiceNo,3)'), 'PRO')->get();
        // }



        if($request->InvoiceTyp=='Job')
        {
              $data = DB::table('job')
                ->select(DB::raw('LPAD(IFNULL(MAX(right(JobNo,5)),0)+1,5,0) as VHNO '))
                ->where('BranchID',$request->BranchID)
                ->get();
       

        $vhno = $branch->BranchCode.'/J0B/'.date('Y').'-'.$data[0]->VHNO;



        return array('vhno' => $vhno);

        }

        if($request->InvoiceTyp=='Invoice')
        {
              $data = DB::table('invoice_master')
                ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
                ->where('BranchID',$request->BranchID)
                ->get();
       

        $vhno = $branch->BranchCode.'/INV/'.date('Y').'-'.$data[0]->VHNO;



        return array('vhno' => $vhno);

        }

         if($request->InvoiceTyp=='Delivery Note')
        {
              $data = DB::table('invoice_master')
                ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
                ->where('BranchID',$request->BranchID)
                ->get();
       

        $vhno = $branch->BranchCode.'/DO/'.date('Y').'-'.$data[0]->VHNO;



        return array('vhno' => $vhno);

        }


    if($request->InvoiceTyp=='PO')
        {
              $data = DB::table('invoice_master')
                ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
                ->where('BranchID',$request->BranchID)
                ->get();
       

        $vhno = $branch->BranchCode.'/PO/'.date('Y').'-'.$data[0]->VHNO;



        return array('vhno' => $vhno);

        }



        if($request->InvoiceTyp=='PR')
        {
              $data = DB::table('invoice_master')
                ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
                ->where('BranchID',$request->BranchID)
                ->get();
       

        $vhno = $branch->BranchCode.'/PR/'.date('Y').'-'.$data[0]->VHNO;



        return array('vhno' => $vhno);

        }





     }




    public function Dashboard()
    {
        session::put('menu', 'Dashboard');



        $total_booking = DB::table('bookings')
        ->where(DB::raw('DATE_FORMAT(start,"%Y-%m-%d")'), date('Y-m-d'))
        ->count();


        $total_leads = DB::table('leads')
        // ->where(DB::raw('DATE_FORMAT(start,"%Y-%m-%d")'), date('Y-m-d'))
        ->where('agent_id',session::get('UserID'))
        ->count();

          $leads_reject = DB::table('leads')
        // ->where(DB::raw('DATE_FORMAT(start,"%Y-%m-%d")'), date('Y-m-d'))
        ->where('agent_id',session::get('UserID'))
        ->where('status','Rejected')
        ->count();


         $leads_won = DB::table('leads')
        // ->where(DB::raw('DATE_FORMAT(start,"%Y-%m-%d")'), date('Y-m-d'))
        ->where('agent_id',session::get('UserID'))
        ->where('approved_status','Closed Won')
        ->count();
       
        $leads_lost = DB::table('leads')
        // ->where(DB::raw('DATE_FORMAT(start,"%Y-%m-%d")'), date('Y-m-d'))
        ->where('agent_id',session::get('UserID'))
        ->where('approved_status','Closed Lost')
        ->count();

        $leads_new = DB::table('leads')
        // ->where(DB::raw('DATE_FORMAT(start,"%Y-%m-%d")'), date('Y-m-d'))
        ->where('agent_id',session::get('UserID'))
        ->where('status','Pending')
        ->count();
       

        // dd( storage_path());

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
        $pagetitle = 'Dashboard';

        $invoice_master = DB::table('invoice_master')
            ->select(DB::raw('ifnull(sum(IFNULL(Grandtotal,0)),0) as Paid'))->where('Date', date('Y-m-d'))->get();


        $sale = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Total'))
            ->WhereIn('ChartOfAccountID', [410175, 410100])
            ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m")'), date('Y-m'))
            ->get();


        $v_cashflow = DB::table('v_cashflow')->get();
        $data = array();

        foreach ($v_cashflow as $key => $value) {

            $cashflow_chart[] = $value->Balance;
        }

        $expense = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance'))
            ->where('CODE', 'E')
            ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m")'), date('Y-m'))
            ->get();

        $revenue = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
            ->where('CODE', 'R')
            ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m")'), date('Y-m'))
            ->get();


        $r = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Cr),0,Cr))-sum(if(ISNULL(Dr),0,Dr)) as Balance'))
            ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m")'), date('Y-m'))
            ->where('CODE', 'R')
            ->get();


        $e = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr))-sum(if(ISNULL(Cr),0,Cr)) as Balance'))
            ->where(DB::raw('DATE_FORMAT(Date,"%Y-%m")'), date('Y-m'))
            ->where('CODE', 'E')
            ->get();


        $r = ($r[0]->Balance == null) ? '0' :  $r[0]->Balance;
        $e = ($e[0]->Balance == null) ? '0' :  $e[0]->Balance;

        $profit_loss = $r - $e;


        $cash = DB::table('v_journal')
            ->select('ChartOfAccountName',  DB::raw('sum(if(ISNULL(Dr),0,Dr)) - sum(if(ISNULL(Cr),0,Cr)) as Balance'))
            ->whereIn('ChartOfAccountID', [110101, 110200, 110201, 110250])
            // ->where('ChartOfAccountID',$request->ChartOfAccountID)
            ->groupBy('ChartOfAccountName')
            ->get();


        $cash1 = DB::table('v_rev_exp_chart')->get();
        // $exp_chart = DB::table('v_expense_chart')->where('MonthName','February-2022')->get();



        // 'exp_chart'
        return view('dashboard', compact('pagetitle', 'v_cashflow', 'invoice_master', 'expense', 'revenue', 'profit_loss', 'cash', 'cash1', 'sale','total_booking','total_leads','leads_won','leads_lost','leads_new','leads_reject'));
    }


    public  function Item()
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////

        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        session::put('menu', 'Item');
        $pagetitle = 'Item';
        $item = DB::table('item')->get();
        $unit = DB::table('unit')->get();
        $item_type = DB::table('item_type')->get();
        $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,4)'), 00000)->where(DB::raw('right(ChartOfAccountID,5)'), '!=', 00000)->get();
        return view('item', compact('pagetitle', 'item', 'unit', 'chartofaccount','item_type'));
    }



    public  function ItemSave(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Item/Inventory', 'List / Create');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $this->validate(
            $request,
            [

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
            'UnitName' => $request->input('UnitName'),
            'UnitQty' => $request->input('UnitQty'),
            
            'Taxable' => $request->input('Taxable'),
            'Percentage' => $request->input('Percentage'),
            'CostPrice' => $request->input('CostPrice'),
            'SellingPrice' => $request->input('SellingPrice'),

        );



        $id = DB::table('item')->insertGetId($data);

        return redirect('Item')->with('error', 'Save Successfully.')->with('class', 'success');
    }
    public  function ItemEdit($id)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Item/Inventory', 'Update');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        session::put('menu', 'Item');
        $pagetitle = 'Item';

        $item = DB::table('item')->where('ItemID', $id)->get();
        $unit = DB::table('unit')->get();
        $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(ChartOfAccountID,4)'), 00000)->where(DB::raw('right(ChartOfAccountID,5)'), '!=', 00000)->get();

        return view('item_edit', compact('pagetitle', 'item', 'unit', 'chartofaccount'));
    }

    public  function ItemUpdate(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Item/Inventory', 'Update');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
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
            'CostPrice' => $request->input('CostPrice'),
            'SellingPrice' => $request->input('SellingPrice'),
             'UnitName' => $request->input('UnitName'),
            'UnitQty' => $request->input('UnitQty'),
            

        );


        $id = DB::table('item')->where('ItemID', $request->input('ItemID'))->update($data);




        return redirect('Item')->with('error', 'Updated Successfully.')->with('class', 'success');
    }
    public  function ItemDelete($id)
    {

        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Item/Inventory', 'Delete');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////

        $id = DB::table('item')->where('ItemID', $id)->delete();
        return redirect('Item')->with('error', 'Deleted Successfully')->with('class', 'success');
    }
    public  function Supplier()
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        // $allow = check_role(session::get('UserID'), 'Supplier', 'List / Create');
        // if ($allow == 0) {
        //     return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        // }
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $this->validate(
            $request,
            [

                'SupplierName' => 'required',


            ],
            [
                'SupplierName.required' => 'Supplier Name is required',



            ]
        );
        $data = array(

            'SupplierName' => $request->input('SupplierName'),
            'TRN' => $request->input('TRN'),
            'Address' => $request->input('Address'),
            'Mobile' => $request->input('Mobile'),
            'Phone' => $request->input('Phone'),
            'Email' => $request->input('Email'),
            'Website' => $request->input('Website'),
            'Active' => $request->input('Active'),
            'InvoiceDueDays' => $request->input('InvoiceDueDays'),



        );

        $id = DB::table('supplier')->insertGetId($data);




        return redirect('Supplier')->with('error', 'Save Successfully.')->with('class', 'success');
    }


    public function SupplierDetail($id=null)
    {

 
        if($id)
        {
            session::put('SupplierID',$id);
        }
        $pagetitle='Supplier Documents';
        $supplier_detail = DB::table('supplier_detail')->where('SupplierID',session::get('SupplierID',$id))->orderby('Type')->get();

         return view ('supplier_detail',compact('pagetitle','supplier_detail'));
    }



    public function SupplierDetailSave(request $request)
    {

        if ($request->file('filename')!=null)
                    {
              
                 $this->validate($request, [
    
                       // 'file' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,pdf|max:1000',
                         'filename' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,docx|max:2048',
    
                    ] );
    
                 $file = $request->file('filename');
                 $input['filename'] = time().'.'.$file->extension();
                 
                 
    
    
    
                 $storagePath = 'public/uploads';
                 $path = $file->store($storagePath);
                 // $file->move($destinationPath, $input['filename']);
                $filename = basename($path);

                    // $destinationPath = public_path('/images');
                    // $image->move($destinationPath, $input['imagename']);
    
                   // $input['filename']===========is final data in it.
                  
    
                 
    
               $data = array(
            'SupplierID' => session::get('SupplierID'), 
            'DocumentTitle' => $request->DocumentTitle, 
            'Type' => 'File', 
        
              "File" =>  $filename,

        );
    
                  
    
      }
      else
      {

          $data = array(
            'SupplierID' => session::get('SupplierID'), 
            'DocumentTitle' => $request->DocumentTitle, 
            'DocumentDetail' => $request->DocumentDetail, 
            'Type' => 'Text', 
        
    );

      }
                
               
    

        $supplierdetail= DB::table('supplier_detail')->insertGetId($data);
        
     return redirect ('SupplierDetail')->with('error', 'saved successfully.')->with('class','success');

    }



    public  function SupplierEdit($id)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Supplier', 'Update');
        if ($allow == 0) {
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $this->validate(
            $request,
            [

                'SupplierName' => 'required',


            ],
            [

                'SupplierName.required' => 'Supplier Name is required',

            ]
        );


        $data = array(

            'SupplierName' => $request->input('SupplierName'),
            'TRN' => $request->input('TRN'),
            'Address' => $request->input('Address'),
            'Mobile' => $request->input('Mobile'),
            'Phone' => $request->input('Phone'),
            'Email' => $request->input('Email'),
            'Website' => $request->input('Website'),
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////

        $id = DB::table('supplier')->where('SupplierID', $id)->delete();
        return redirect('Supplier')->with('error', 'Deleted Successfully')->with('class', 'success');
    }


        public  function SupplierDetailDelete($id)
    {
        

        $idd = DB::table('supplier_detail')->where('SupplierDetailID', $id)->delete();
        return redirect('SupplierDetail')->with('error', 'Deleted Successfully')->with('class', 'success');
    }
    // parties
    public  function Parties()
    {

        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
  
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        session::put('menu', 'Party');
        $pagetitle = 'Parties';

        $supplier = DB::table('party')->get();
        return view('party', compact('pagetitle', 'supplier'));
    }
    public  function SaveParties(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Party / Customers', 'List / Create');
        if ($allow == 0) {
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
            'TRN' => $request->input('TRN'),
            'Address' => $request->input('Address'),
            'City' => $request->input('City'),
            'Mobile' => $request->input('Mobile'),
            'Phone' => $request->input('Phone'),
            'Email' => $request->input('Email'),
            'Website' => $request->input('Website'),
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
            'TRN' => $request->input('TRN'),
            'Address' => $request->input('Address'),
            'City' => $request->input('City'),
            'Mobile' => $request->input('Mobile'),
            'Phone' => $request->input('Phone'),
            'Email' => $request->input('Email'),
            'Website' => $request->input('Website'),
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////


        $id = DB::table('party')->where('PartyID', $id)->delete();
        return redirect('Parties')->with('error', 'Deleted Successfully')->with('class', 'success');
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        // $allow = check_role(session::get('UserID'), 'Party Ledger', 'View');
        // if ($allow == 0) {
        //     return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        // }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        session::put('menu', 'PartyLedger');
        $pagetitle = 'Party Ledger';
        $party = DB::table('party')->get();
        $voucher_type = DB::table('voucher_type')->get();
        $chartofaccount = DB::table('chartofaccount')
            ->whereIn('ChartOfAccountID', [110400, 210101])->get();
        return view('party_ledger', compact('pagetitle', 'party', 'voucher_type', 'chartofaccount'));
    }
    public function PartyLedger1(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Party Ledger', 'View');
        if ($allow == 0) {
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
            ->orderBy('Date', 'asc')
            ->get();
        //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party'));
        // //return $pdf->download('pdfview.pdf');
        //    $pdf->setpaper('A4', 'portiate');
        //       return $pdf->stream();

        // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
        return view('party_ledger1', compact('journal', 'pagetitle', 'sql', 'party'));
    }
    public function PartyLedger1PDF(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Party Ledger', 'PDF');
        if ($allow == 0) {
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
            ->orderBy('Date', 'asc')
            ->get();
        //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party'));
        // //return $pdf->download('pdfview.pdf');
        //    $pdf->setpaper('A4', 'portiate');
        //       return $pdf->stream();

        $pdf = PDF::loadView('party_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'party'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();

        // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
        // return view ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party'));
    }



    public function PartySalesLedger1PDF(request $request)
    {

        $pagetitle = 'Party Sale Ledger';

        $sql = DB::table('journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
            ->where('PartyID', $request->PartyID)
            ->where('ChartOfAccountID', $request->ChartOfAccountID)
            ->where('Date', '<', $request->StartDate)
            // ->whereBetween('date',array($request->StartDate,$request->EndDate))
            ->get();


        $journal = DB::table('v_journal')
            ->where('PartyID', $request->PartyID)
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->get();

        $company = DB::table('company')->get();
        $party = DB::table('party')->where('PartyID', $request->PartyID)->get();

        $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

        $pdf = PDF::loadView('party_sales_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'party', 'company'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }


    public function PartySalesLedger2PDF(request $request)
    {

        $pagetitle = 'Party Sale Ledger';

        $sql = DB::table('journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
            ->where('PartyID', $request->PartyID)
            ->where('ChartOfAccountID', $request->ChartOfAccountID)
            ->where('Date', '<', $request->StartDate)
            // ->whereBetween('date',array($request->StartDate,$request->EndDate))
            ->get();


        $journal = DB::table('v_journal')
            ->where('PartyID', $request->PartyID)
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->where('ChartOfAccountID', $request->ChartOfAccountID)
            ->get();



        $company = DB::table('company')->get();

        $party = DB::table('party')->where('PartyID', $request->PartyID)->get();

        $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

        $pdf = PDF::loadView('party_sales_ledger2pdf', compact('journal', 'pagetitle', 'sql', 'party', 'company'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }


    public function PartyLedgerAccount1PDF(request $request)
    {

        $pagetitle = 'Party Sale Ledger';

        $sql = DB::table('journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
            ->where('PartyID', $request->PartyID)
            ->where('ChartOfAccountID', $request->ChartOfAccountID)
            ->where('Date', '<', $request->StartDate)
            // ->whereBetween('date',array($request->StartDate,$request->EndDate))
            ->get();


        $journal = DB::table('v_journal')
            ->where('PartyID', $request->PartyID)
            ->where('ChartOfAccountID', $request->ChartOfAccountID)

            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->get();

        $party = DB::table('party')->where('PartyID', $request->PartyID)->get();

        $company = DB::table('company')->get();

        $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

        $pdf = PDF::loadView('party_ledger_account1pdf', compact('journal', 'pagetitle', 'sql', 'party', 'company'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }


    public  function AdjustmentBalance()
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Adjustment Balance', 'Create');
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Tax Report';
        $party = DB::table('party')->get();
        $party = DB::table('party')->get();
        $pdf = PDF::loadView('party_listPDF', compact('party', 'pagetitle'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'landscape');
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Out Standing Invoice';
        if ($request->PartyID > 0) {
            $invoice = DB::table('v_invoice_balance')->where('PartyID', $request->PartyID)->where('BALANCE', '>', 0)->get();
        } else {
            $invoice = DB::table('v_invoice_balance')->where('BALANCE', '>', 0)->get();
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Out Standing Invoice';
        if ($request->PartyID > 0) {
            $invoice = DB::table('v_invoice_master')->where('PartyID', $request->PartyID)->where('Balance', '>', 0)->get();
        } else {
            $invoice = DB::table('v_invoice_master')->where('Balance', '>', 0)->get();
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
        $branch = DB::table('branch')->get();

        return view('partywise_sale', compact('pagetitle', 'invoice_type', 'party','branch'));
    }
    public function PartyWiseSale1(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Party Wise Sale', 'View');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Party wise sale';

        if (($request->PartyID > 0)) {

            $party_wise = DB::table('v_partywise_sale')->where('PartyID', $request->PartyID)
                ->whereBetween('date', array($request->StartDate, $request->EndDate))
                ->get();
        } else {
            $party_wise = DB::table('v_partywise_sale')
                ->whereBetween('date', array($request->StartDate, $request->EndDate))

                ->get();
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Party wise sale';
        if (($request->PartyID > 0)) {

            $party_wise = DB::table('v_partywise_sale')->where('PartyID', $request->PartyID)
                ->whereBetween('date', array($request->StartDate, $request->EndDate))
                ->get();
        } else {
            $party_wise = DB::table('v_partywise_sale')
                ->whereBetween('date', array($request->StartDate, $request->EndDate))

                ->get();
        }


        $pdf = PDF::loadView('partywise_sale1PDF', compact('party_wise', 'pagetitle'));
        // //return $pdf->download('pdfview.pdf');
        //   // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Party Balance';


        $party = DB::table('party')->get();


        return  View('party_balance1', compact('party', 'pagetitle'));
    }


    public function PartyBalanceAreawise2PDF(request $request)
    {

        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Party Balance', 'View');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Party Balance';




        $city = DB::table('party')->select('City')->distinct()->whereNotNull('City')->get();


        $pdf = PDF::loadView('party_balance_area2pdf', compact('city', 'pagetitle'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();



        return  View('party_balance_area2pdf', compact('city', 'pagetitle'));
    }


    public function PartySaleItemWise(request $request)
    {

        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Party Balance', 'View');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Party Balance';




        $city = DB::table('party')->select('City')->distinct()->whereNotNull('City')->get();


        $pdf = PDF::loadView('party_sale_item_wisepdf', compact('city', 'pagetitle'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();



        return  View('party_sale_item_wisepdf', compact('city', 'pagetitle'));
    }




    public function PartyBalance1PDF(request $request)
    {

        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Party Balance', 'PDF');
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
    public function SupplierLedger1PDF(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Supplier Ledger', 'PDF');
        if ($allow == 0) {
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
        $pdf = PDF::loadView('supplier_ledger1pdf', compact('journal', 'pagetitle', 'sql', 'supplier'));
        // //return $pdf->download('pdfview.pdf');
        //    $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();

        // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
        return view('supplier_ledger1', compact('journal', 'pagetitle', 'sql', 'supplier'));
    }


    public function SupplierBillLedger2PDF(request $request)
    {

        $pagetitle = 'Party Sale Ledger';

        $sql = DB::table('journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
            ->where('SupplierID', $request->SupplierID)
            ->where('ChartOfAccountID', $request->ChartOfAccountID)
            ->where('Date', '<', $request->StartDate)
            // ->whereBetween('date',array($request->StartDate,$request->EndDate))
            ->get();


        $journal = DB::table('v_journal')
            ->where('SupplierID', $request->SupplierID)
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->where('ChartOfAccountID', $request->ChartOfAccountID)
            ->get();



        $company = DB::table('company')->get();

        $supplier = DB::table('supplier')->where('SupplierID', $request->SupplierID)->get();

        $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;

        $pdf = PDF::loadView('supplier_bill_ledger2pdf', compact('journal', 'pagetitle', 'sql', 'supplier', 'company'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Supplier wise sale';

        if (($request->SupplierID > 0)) {


            $invoice_master = DB::table('v_invoice_master_supplier')
                ->where('InvoiceNo', 'like', 'BIL%')
                ->where('SupplierID', $request->SupplierID)

                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                // ->groupBy('SupplierID','SupplierName')
                ->get();
        } else {
            $invoice_master = DB::table('v_invoice_master_supplier')

                ->where('InvoiceNo', 'like', 'BIL%')
                // ->where('SupplierID',$request->SupplierID)
                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                // ->groupBy('SupplierID' ,'SupplierName')
                ->get();
        }




        return View('supplierwise_sale1', compact('invoice_master', 'pagetitle'));
    }


    public function SupplierWiseSale1PDF(request $request)
    {

        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Sales Report', 'PDF');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Supplier wise sale';
        if (($request->SupplierID > 0)) {


            $invoice_master = DB::table('v_invoice_master_supplier')
                ->where('InvoiceNo', 'like', 'BIL%')
                ->where('SupplierID', $request->SupplierID)

                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                // ->groupBy('SupplierID','SupplierName')
                ->get();
        } else {
            $invoice_master = DB::table('v_invoice_master_supplier')

                ->where('InvoiceNo', 'like', 'BIL%')
                // ->where('SupplierID',$request->SupplierID)
                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                // ->groupBy('SupplierID' ,'SupplierName')
                ->get();
        }



        $pdf = PDF::loadView('supplierwise_sale1pdf', compact('invoice_master', 'pagetitle'));
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Tax Report';


        $company = DB::table('company')->get();

        $invoice_master = DB::table('v_invoice_master')
            ->where('InvoiceNo', 'like', '%TAX%')
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->orderBy('InvoiceMasterID')
            ->orderBy('Date')
            ->get();



        return View('tax_report1', compact('invoice_master', 'pagetitle', 'company'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');



    }
    public function TaxReport1PDF(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Tax Report', 'PDF');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Tax Report';
        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master')
            ->where('InvoiceNo', 'like', '%TAX%')
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->whereNotNull('PartyID')
            ->orderBy('InvoiceMasterID')
            ->orderBy('Date')
            ->get();

        $pdf = PDF::loadView('tax_report1pdf', compact('invoice_master', 'pagetitle', 'company'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }



    ///////////

    public function TaxOverallReport()
    {

        session::put('menu', 'TaxReport');
        $pagetitle = 'Tax Report';


        return view('tax_overall_report', compact('pagetitle'));
    }
    public function TaxOverallReport1(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Tax Report', 'View');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Tax Report';


        $company = DB::table('company')->get();


        


            $output_vat = DB::table('v_invoice_both')
                ->where('PartyID', '<>', 'null')
                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                ->orderBy('InvoiceMasterID')
                ->orderBy('Date')
                ->get();
     


          
            $input_vat = DB::table('v_invoice_both')
                ->where('SupplierID', '<>', 'null')

                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                ->orderBy('InvoiceMasterID')
                ->orderBy('Date')
                ->get();
       

        return View('tax_overall_report1', compact('output_vat','input_vat', 'pagetitle', 'company'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');



    }
    public function TaxOverallReport1PDF(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Tax Report', 'PDF');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Tax Report';


        $company = DB::table('company')->get();


        


            $output_vat = DB::table('v_invoice_both')
                ->where('PartyID', '<>', 'null')
                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                ->orderBy('InvoiceMasterID')
                ->orderBy('Date')
                ->get();
     


          
            $input_vat = DB::table('v_invoice_both')
                ->where('SupplierID', '<>', 'null')

                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                ->orderBy('InvoiceMasterID')
                ->orderBy('Date')
                ->get();

        $pdf = PDF::loadView('tax_overall_report1pdf', compact('output_vat','input_vat', 'pagetitle', 'company'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }


    //////////////



    public function SalemanReport()
    {
        session::put('menu', 'SalemanReport');
        $pagetitle = 'Saleman Report';
        $invoice_type = DB::table('invoice_type')->get();
        $item = DB::table('item')->get();
        $user = DB::table('user')->get();

        return view('saleman_report', compact('pagetitle', 'invoice_type', 'user', 'item'));
    }
    public function SalemanReport1(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Sale Man Report', 'View');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Saleman Report';

        if (($request->UserID > 0)) {


            $invoice_master = DB::table('v_invoice_master')
                ->where('UserID', $request->UserID)
                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                ->orderBy('Date')
                ->get();
        } else {

            $invoice_master = DB::table('v_invoice_master')
                // ->where('ItemID',$request->ItemID)
                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                ->orderBy('InvoiceMasterID')
                ->orderBy('Date')
                ->get();
        }

        //       $pdf = PDF::loadView ('saleman_report1',compact('invoice_master','pagetitle'));
        // //return $pdf->download('pdfview.pdf');
        //   // $pdf->setpaper('A4', 'portiate');
        //       return $pdf->stream();
        return View('saleman_report1', compact('invoice_master', 'pagetitle'));
    }
    public function SalemanReport1PDF(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Sale Man Report', 'PDF');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Saleman Report';

        if (($request->UserID > 0)) {


            $invoice_master = DB::table('v_invoice_master')
                ->where('UserID', $request->UserID)
                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                ->orderBy('Date')
                ->get();
        } else {

            $invoice_master = DB::table('v_invoice_master')
                // ->where('ItemID',$request->ItemID)
                ->whereBetween('Date', array($request->StartDate, $request->EndDate))
                ->orderBy('InvoiceMasterID')
                ->orderBy('Date')
                ->get();
        }

        $pdf = PDF::loadView('saleman_report1pdf', compact('invoice_master'));
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
            ->whereIn('Category', ['BANK','CASH'])
            ->get();
        return view('cashbook_report', compact('pagetitle', 'chartofaccount'));
    }
    public function CashbookReport1(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Cash Book', 'View');
        if ($allow == 0) {
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
        if ($allow == 0) {
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
            ->whereIn('Category', ['BANK','CASH'])
            ->get();
        return view('daybook_report', compact('pagetitle', 'chartofaccount'));
    }


    public function DaybookReport1(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Day Book', 'View');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT /////////////////////////////////////////////////

        session::put('menu', 'CashbookReport');
        $pagetitle = 'Cashbook Report';

        $invoice_master = DB::table('v_invoice_master')
            ->where(DB::raw('left(Invoiceno,3)'), 'INV')
            ->orwhere(DB::raw('left(Invoiceno,3)'), 'TAX')
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->get();
        $invoice_master_summary = DB::table('invoice_master')
            ->select(DB::raw('sum(if(ISNULL(SubTotal),0,SubTotal)) as SubTotal'), DB::raw('sum(if(ISNULL(Tax),0,Tax)) as Tax'), DB::raw('sum(if(ISNULL(GrandTotal),0,GrandTotal)) as GrandTotal'), DB::raw('sum(if(ISNULL(DiscountAmount),0,DiscountAmount)) as Discount'))
            ->where(DB::raw('left(Invoiceno,3)'), 'INV')
            ->orwhere(DB::raw('left(Invoiceno,3)'), 'TAX')
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

        //          $pdf = PDF::loadView ('party_ledger1pdf',compact('journal','pagetitle','sql' ,'party'));
        // //return $pdf->download('pdfview.pdf');
        //    $pdf->setpaper('A4', 'portiate');
        //       return $pdf->stream();
        // dd(count($invoice).'-'.count($journal));
        // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
        if (count($invoice_master) > count($journal)) {
            $row = count($invoice_master);
        } else {
            $row = count($journal);
        }
        return view('daybook_report1', compact('journal', 'pagetitle', 'sql', 'invoice_master', 'row', 'invoice_master_summary', 'journal_summary'));
    }
    public function DaybookReport1PDF(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Day Book', 'PDF');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        // dd($request->all());

        session::put('menu', 'CashbookReport');
        $pagetitle = 'Cashbook Report';

        $invoice_master = DB::table('v_invoice_master')
            ->where(DB::raw('left(Invoiceno,2)'), 'CN')
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->get();
        $invoice_master_summary = DB::table('invoice_master')
            ->select(DB::raw('sum(if(ISNULL(SubTotal),0,SubTotal)) as SubTotal'), DB::raw('sum(if(ISNULL(Tax),0,Tax)) as Tax'), DB::raw('sum(if(ISNULL(GrandTotal),0,GrandTotal)) as GrandTotal'), DB::raw('sum(if(ISNULL(DiscountAmount),0,DiscountAmount)) as Discount'))
            ->where(DB::raw('left(Invoiceno,2)'), 'CN')
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
        if (count($invoice_master) > count($journal)) {
            $row = count($invoice_master);
        } else {
            $row = count($journal);
        }

        $pdf = PDF::loadView('daybook_report1pdf', compact('journal', 'pagetitle', 'sql', 'invoice_master', 'row', 'invoice_master_summary', 'journal_summary'));
        // //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'landscape');
        return $pdf->stream();
        // dd(count($invoice).'-'.count($journal));
        // $journal = DB::table('v_journal')->where('PartyID',1002)->where('ChartOfAccountID',110400)->get();
        // return view ('daybook_report1',compact('journal','pagetitle','sql' ,'invoice_detail','row','invoice_detail_summary','journal_summary'));
    }
    public function GeneralLedger()
    {
        session::put('menu', 'GeneralLedger');
        $pagetitle = 'General Ledger';
        $chartofaccount = DB::table('chartofaccount')
            // ->whereIn('Category', ['BANK','CASH'])
        ->where('Level',3)
            ->get();
        return view('general_ledger', compact('pagetitle', 'chartofaccount'));
    }
    public function GeneralLedger1(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'General Ledger', 'View');
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        $user = DB::table('user')->get();
        $supplier = DB::table('supplier')->get();

        return view('ticket_register', compact('pagetitle', 'invoice_type', 'supplier', 'item', 'user'));
    }
    public function TicketRegister1(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Ticket Register', 'View');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////

        $pagetitle = 'Ticket Register';
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
        if ($allow == 0) {
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

        $pdf = PDF::loadView('ticket_register1PDF', compact('invoice_detail', 'invoice_summary'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'landscape');
        return $pdf->stream();
        return View('ticket_register1PDF', compact('invoice_detail', 'invoice_summary'));
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
        if ($allow == 0) {
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
        if ($allow == 0) {
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
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////
        $pagetitle = 'Invoice Summary';
        $where = array();
        if ($request->InvoiceTypeID > 0) {
            $where = array('InvoiceType' => $request->InvoiceTypeID);
        }
        if ($request->UserID > 0) {
            $where = Arr::add($where, 'UserID', $request->UserID);
        }




        $invoice_summary = DB::table('v_invoice_master')
            ->where($where)
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->orderBy('Date')
            ->get();

        $invoice_total = DB::table('v_invoice_master')
            ->select(DB::raw('count(InvoiceMasterID) as Qty'), DB::raw('sum(Tax) as Tax'), DB::raw('sum(DiscountAmount) as Discount'), DB::raw('sum(Total) as Total'), DB::raw('sum(GrandTotal) as GrandTotal'), DB::raw('sum(Paid) as Paid'), DB::raw('sum(Balance) as Balance'))
            ->where($where)
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
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
        if ($allow == 0) {
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




        $invoice_summary = DB::table('v_invoice_master')
            ->where($where)
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->groupBy('FullName', 'UserID')
            ->orderBy('Date')
            ->get();

        $invoice_total = DB::table('v_invoice_master')
            ->select(DB::raw('count(InvoiceMasterID) as Qty'), DB::raw('sum(Tax) as Tax'), DB::raw('sum(DiscountAmount) as Discount'), DB::raw('sum(Total) as Total'), DB::raw('sum(GrandTotal) as GrandTotal'), DB::raw('sum(Paid) as Paid'), DB::raw('sum(Balance) as Balance'))
            ->where($where)
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
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


    public function BalanceSheet()
    {
        $pagetitle = 'Balance Sheet';
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



    public  function BalanceSheetDetail($ChartOfAccountID, $StartDate, $EndDate)
    {

        $pagetitle = 'Journal Detail';
        $company = DB::table('company')->get();
        $journal = DB::table('v_journal')
            ->where('ChartOfAccountID', $ChartOfAccountID)
            ->whereBetween('Date', array($StartDate, $EndDate))
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
            ->get();



        return view('balancesheet_detail', compact('company', 'journal', 'pagetitle', 'StartDate', 'EndDate'));
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

    public function Payment()
    {
        $pagetitle = 'Payment';

        $payment = DB::table('v_payment')->get();

        return view('ebooks.payment', compact('payment', 'pagetitle'));
    }
    public function ajax_payment(Request $request)
    {
        session::put('menu', 'Vouchers');
        $pagetitle = 'Vouchers';
        if ($request->ajax()) {
            $data = DB::table('v_payment')->orderBy('PaymentMasterID')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>
                    $btn = '

                       <div class="d-flex align-items-center col-actions">


<a href="' . URL('/PaymentViewPDF/' . $row->PaymentMasterID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/PaymentEdit/' . $row->PaymentMasterID) . '"><i class="font-size-18 mdi mdi-pencil align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/PaymentDelete/' . $row->PaymentMasterID) . '"><i class="font-size-18 mdi mdi-trash-can-outline align-middle me-1 text-secondary"></i></a>




                       </div>';

                    //class="edit btn btn-primary btn-sm"
                    // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ebooks.payment', 'pagetitle');
    }
    public function PaymentCreate()
    {




        $pagetitle = 'Create Payment';
        $party = DB::table('party')->get();
        $chartofacc = DB::table('v_chartofaccount_mini')->get();
        $payment_mode = DB::table('payment_mode')->get();
        $payment = DB::table('payment_master')->select(DB::raw('ifnull(max(PaymentMasterID)+1,1) as PaymentMasterID'))->get();

        return view('ebooks.payment_create', compact('chartofacc', 'party', 'pagetitle', 'payment', 'payment_mode'));
    }

    public  function Ajax_PartyInvoices($id)
    {




        $party = DB::table('party')->get();



        $payment_mode = DB::table('payment_mode')->get();


        $invoice_balance = DB::table('v_invoice_balance')->where('PartyID', $id)->where('BALANCE', '>', 0)->get();

        $invoice_party_balance = DB::table('v_invoice_party_balance')->where('PartyID', $id)->get();


        return view('ebooks.ajax_party_invoice', compact('party', 'payment_mode', 'invoice_balance', 'invoice_party_balance'));
        // return response()->json(['success'=>'Got Simple Ajax Request.']);

    }


    public function PaymentSave(request $request)
    {


        // dd($request->all());
        // $employee = DB::table('employee')->get();
        // return view ('');
 



        if ($request->hasfile('UploadSlip')) {

            $this->validate($request, [

                // 'file' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,pdf|max:1000',
                'UploadSlip' => 'image|mimes:jpeg,png,jpg,gif,pdf,doc,docx',

            ]);




            $file = $request->file('UploadSlip');
            $input['filename'] = time() . '.' . $file->extension();

            $destinationPath = public_path('/uploads');

            $file->move($destinationPath, $input['filename']);

            // $destinationPath = public_path('/images');
            // $image->move($destinationPath, $input['imagename']);

            // $input['filename']===========is final data in it.


            $payment_master = array(
                'PaymentMasterID' => $request->PaymentMasterID,
                'PartyID' => $request->PartyID,
                'PaymentAmount' => $request->PaymentAmount,
                'PaymentDate' => $request->PaymentDate,
                'PaymentMode' => $request->PaymentMode,
                'ChartOfAccountID' => $request->ChartOfAccountID,
                'ReferenceNo' => $request->ReferenceNo,
                'Notes' => $request->Notes,
                'File' => $input['filename'],
            );
        } else {

            $payment_master = array(
                'PaymentMasterID' => $request->PaymentMasterID,
                'PartyID' => $request->PartyID,
                'PaymentAmount' => $request->PaymentAmount,
                'PaymentDate' => $request->PaymentDate,
                'PaymentMode' => $request->PaymentMode,
                'ChartOfAccountID' => $request->ChartOfAccountID,
                'ReferenceNo' => $request->ReferenceNo,
                'Notes' => $request->Notes,
            );
        }
        // end of if else used for to  check file uploaded or not

        $id = DB::table('payment_master')->insertGetId($payment_master);

        if ($request->input('PaymentMode') == 'Cash') {
            $PaymentMode = '110101'; //Cash in hand
            $JournalType = 'CR';
        } elseif ($request->input('PaymentMode') == 'Credit Card') {

            $PaymentMode = '110250'; //Credit Card ACCOUNT.
            $JournalType = 'BR';
        } else {
            $PaymentMode = '110201'; //ENBD BANK
            $JournalType = 'BR';
        }


        //  start for item array from invoice
        for ($i = 0; $i < count($request->InvoiceMasterID); $i++) {


            if ($request->Amount[$i] > 0) {


                $payment_detail = array(
                    'PaymentMasterID' => $request->PaymentMasterID,
                    'PaymentDate' => $request->PaymentDate,
                    'InvoiceMasterID' => $request->InvoiceMasterID[$i],
                    'Payment' => $request->Amount[$i],


                );



                $id = DB::table('payment_detail')->insertGetId($payment_detail);


                // payment of invoice

                // payment received


                //bank debit
                $bank_cash = array(
                    'VHNO' => 'PAY-' . $request->input('PaymentMasterID'),
                    'ChartOfAccountID' => $request->ChartOfAccountID,   // Cash / bank / credit card
                    'JournalType' => $JournalType,
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $request->InvoiceMasterID[$i],
                    'PaymentMasterID' => $request->PaymentMasterID,
                    'Date' => $request->PaymentDate,

                    'Dr' => $request->Amount[$i],
                    'Narration' => 'Payment made with payment refno ' . $request->PaymentMasterID . '',
                    'Trace' => 514
                );
                $journal_entry = DB::table('journal')->insertGetId($bank_cash);

                // A/R credit
                $ar_payment = array(
                    'VHNO' => 'PAY-' . $request->input('PaymentMasterID'),
                    'ChartOfAccountID' => 110400,  // AR Customer
                    'JournalType' => $JournalType,
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $request->InvoiceMasterID[$i],
                    'PaymentMasterID' => $request->PaymentMasterID,
                    'Date' => $request->PaymentDate,
                    'Cr' => $request->Amount[$i],
                    'Narration' => 'Payment made with payment refno ' . $request->PaymentMasterID . '',
                    'Trace' => 515
                );

                $journal_entry = DB::table('journal')->insertGetId($ar_payment);
                // end payment received

                // end of invoice payment





            }
        }



        return redirect('Payment')->with('error', 'Saved Successfully')->with('class', 'success');
    }



    public function PaymentDelete($id)
    {


        // Get the invoice details
        $invoice_mst = DB::table('payment_master')->where('PaymentMasterID', $id)->first();
        
        // Check if invoice exists
        if (!$invoice_mst) {
            return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
        }

        // Check if invoice belongs to a previous year
        $invoice_year = date('Y', strtotime($invoice_mst->PaymentDate)); // Assuming 'date' is the invoice date field
         $current_year = date('Y');

        // if ($invoice_year < $current_year) {
        //     return redirect()->back()->with('error', 'You cannot delete payment from a previous year.')->with('class', 'danger');
        // }




        $id1 = DB::table('payment_master')->where('PaymentMasterID', $id)->delete();

        $id2 = DB::table('payment_detail')->where('PaymentMasterID', $id)->delete();
        $id3 = DB::table('journal')->where('VHNO', 'PAY-' . $id)->delete();

        return redirect('Payment')->with('error', 'Deleted Successfully')->with('class', 'success');
    }


    public function DeliveryChallan()
    {
        $pagetitle = 'All Delivery Challans';




        return view('delivery_note.delivery_note', compact('pagetitle'));
    }
    public function ajax_deliverychallan(Request $request)
    {
        session::put('menu', 'Vouchers');
        $pagetitle = 'Delivery Challan';
        if ($request->ajax()) {
            $data = DB::table('v_invoice_master')->orderBy('InvoiceMasterID')->where('InvoiceType','Delivery Note')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>
                    $btn = '

                       <div class="d-flex align-items-center col-actions">

                       <a href="' . URL('/createInvoice/' . $row->InvoiceMasterID) . '"><span class="font-size-14 align-middle me-1 text-secondary">INV</span></a>
<a href="' . URL('/DeliveryChallanView/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/DeliveryChallanEdit/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-pencil align-middle me-1 text-secondary"></i></a>
<a target="_blank" href="' . URL('/DeliveryChallanViewPDF/' . $row->InvoiceMasterID) .'/'. $row->BranchID. '"><i class="font-size-18 mdi mdi-file-pdf-outline align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/DeliveryChallanDelete/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-trash-can-outline align-middle me-1 text-secondary"></i></a>




                       </div>';

                    //class="edit btn btn-primary btn-sm"
                    // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('delivery_note.delivery_note', 'pagetitle');
    }
    public function DeliveryChallanCreate()
    {
        $pagetitle = 'Create Payment';
        $party = DB::table('party')->get();

        $items = DB::table('item')->get();
        $item = json_encode($items);
        // dd($item);
        $user = DB::table('user')->get();
        $job = DB::table('job')->get();
        $unit = DB::table('unit')->get();
        $branch = DB::table('branch')->get();
        $tax = DB::table('tax')->get();
       


 
          
        return view('delivery_note.delivery_note_create', compact('party', 'pagetitle', 'items', 'item', 'user', 'job','unit','branch','tax'));
    }

    public  function DeliveryChallanSave(request $request)
    {
        // dd($request->all());
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        // $allow= check_role(session::get('UserID'),'Invoice','Create');
        // if($allow[0]->Allow=='N')
        // {
        //   return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
        // }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////



        // dd($request->all());
        $challan_mst = array(
            'InvoiceNo' => $request->input('InvoiceNo'),
            'PartyID' => $request->input('PartyID'),
            'BranchID' => $request->input('BranchID'),
            
            'ReferenceNo' => $request->input('ReferenceNo'),
            'Date' => $request->input('Date'),
            
            
            
            
            'UserID' => session::get('UserID'),
            'InvoiceType' => 'Delivery Note',
            'JobID' => $request->JobID,
            
            'DriverName' => $request->DriverName,
            'DriverContact' => $request->DriverContact,
            'VehicleNumber' => $request->VehicleNumber,


        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);

        $InvoiceMasterID = DB::table('invoice_master')->insertGetId($challan_mst);

        // when full payment is made


        // END OF SALE RETURN

        //  start for item array from invoice
        for ($i = 0; $i < count($request->ItemID); $i++) {
            $challan_det = array(
                'InvoiceMasterID' =>  $InvoiceMasterID,
                'InvoiceNo' => $request->input('InvoiceNo'),
                'Date' => $request->input('Date'),
                
                'ItemID' => $request->ItemID[$i],
                'Description' => $request->Description[$i],
                'PartyID' => $request->input('PartyID'),
                'UnitName' => $request->UnitName[$i],
                'UnitQty' => $request->UnitQty[$i],
                'Qty' => $request->Qty[$i],
                

            );

            $id = DB::table('invoice_detail')->insertGetId($challan_det);
        }


        // end foreach


        return redirect('DeliveryChallan')->with('error', 'Saved successfully')->with('class', 'success');
    }
    public function DeliveryChallanView($id)
    {
        $pagetitle = 'Delivery Challan';
        $challan = DB::table('v_delivery_challan')->where('ChallanMasterID', $id)->get();
        $challan_detail = DB::table('v_challan_detail')->where('ChallanMasterID', $id)->get();
        $company = DB::table('company')->get();

        session()->forget('VHNO');

        Session::put('VHNO', $challan[0]->ChallanNo);



        return view('ebooks.deliverychallan_view', compact('challan', 'pagetitle', 'company', 'challan_detail', 'company'));
    }

    public function DeliveryChallanViewPDF($id,$brancid)
    {

        $pagetitle = 'Challan Note';
        $invoice_master = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();
        $company = DB::table('company')->get();
        // return view('ebooks.challan_view_pdf',compact('challan','pagetitle','company','challan_detail','company'));
        $pdf = PDF::loadView('delivery_note.delivery_note_view_pdf', compact('invoice_master', 'pagetitle', 'company', 'invoice_detail', 'company'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }






    public function DeliveryChallanEdit($id)
    {   

        // Get the invoice details
        $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->first();
        
        // Check if invoice exists
        if (!$invoice_mst) {
            return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
        }

        // Check if invoice belongs to a previous year
        $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
         $current_year = date('Y');

        if ($invoice_year < $current_year) {
            return redirect()->back()->with('error', 'You cannot edit an invoice from a previous year.')->with('class', 'danger');
        }

        $pagetitle = 'delivery Note';
        $party = DB::table('party')->get();

        $items = DB::table('item')->get();
        $item = json_encode($items);
        // dd($item);

            $tax = DB::table('tax')->get();

        $user = DB::table('user')->get();
     
         $invoice_master = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();

        $unit = DB::table('unit')->get();
    
    $job = DB::table('job')->get();
    $branch = DB::table('branch')->get();
    


        return view('delivery_note.delivery_note_edit', compact('party', 'pagetitle', 'invoice_master', 'items', 'item', 'user', 'party','invoice_detail','job','branch','unit','tax'));
    }
    public  function DeliveryChallanUpdate(request $request)
    {
        // dd($request->all());
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        // $allow= check_role(session::get('UserID'),'Invoice','Create');
        // if($allow[0]->Allow=='N')
        // {
        //   return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
        // }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////



 
        $challan_mst = array(
            'InvoiceNo' => $request->input('InvoiceNo'),
            'PartyID' => $request->input('PartyID'),
            'BranchID' => $request->input('BranchID'),
            
            'ReferenceNo' => $request->input('ReferenceNo'),
            'Date' => $request->input('Date'),
            
            
            
            
            'UserID' => session::get('UserID'),
            'InvoiceType' => 'Delivery Note',
            'JobID' => $request->JobID,
            
            'DriverName' => $request->DriverName,
            'DriverContact' => $request->DriverContact,
            'VehicleNumber' => $request->VehicleNumber,

        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);

        $invoice_master = DB::table('invoice_master')->where('InvoiceMasterID', $request->InvoiceMasterID)->update($challan_mst);
        $challanmasterdelete = DB::table('invoice_detail')->where('InvoiceMasterID', $request->InvoiceMasterID)->delete();




        // when full payment is made


        // END OF SALE RETURN

        //  start for item array from invoice
          for ($i = 0; $i < count($request->ItemID); $i++) {
            $challan_det = array(
                'InvoiceMasterID' =>  $request->InvoiceMasterID,
                'InvoiceNo' => $request->input('InvoiceNo'),
                'Date' => $request->input('Date'),
                 'ItemID' => $request->ItemID[$i],
                'Description' => $request->Description[$i],
                'PartyID' => $request->input('PartyID'),
                'UnitName' => $request->UnitName[$i],
                'UnitQty' => $request->UnitQty[$i],
                'Qty' => $request->Qty[$i],
                

            );

            $id = DB::table('invoice_detail')->insertGetId($challan_det);
        }


        // end foreach


        return redirect('DeliveryChallan')->with('error', 'Challan Saved')->with('class', 'success');
    }
    public function DeliveryChallanDelete($id)
    {

        $pagetitle = 'Delivery Challan';
        $id = DB::table('challan_master')->where('ChallanMasterID', $id)->delete();
        $id = DB::table('challan_detail')->where('ChallanMasterID', $id)->delete();




        return redirect('DeliveryChallan')->with('error', 'Deleted Successfully')->with('class', 'success');
    }
    // sale invoice
    public  function SalesInvoiceCreate()
    {




   



        $pagetitle = 'Sales Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $item = json_encode($items);
        // dd($item);
        $party = DB::table('party')->get();

        // $tax = DB::table('tax')->get();
        $user = DB::table('user')->get();
        $invoice_type = DB::table('invoice_type')->get();
        $branch = DB::table('branch')->get();
        $job = DB::table('job')->get();
        $po = DB::table('invoice_master')->select('InvoiceNo')->where('InvoiceType','PO')->get();

        $payment_mode = DB::table('payment_mode')->get();

        $tax = DB::table('tax')->where('Section', 'Invoice')->get();
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))->whereIn(DB::raw('left(InvoiceNo,3)'), ['TAX'])->get();

        $chartofaccount = DB::table('chartofaccount')->whereNotNull('Category')->get();
        session::put('VHNO', 'TAX-' . $vhno[0]->VHNO);
        $job_number = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(JobNo,5)),0)+1,5,0) as JobNo'))
            ->orderBy('JobNo', 'desc')
            ->first();

            $unit = DB::table('unit')->get();

        return view('invoice.sale_invoice_create', compact('invoice_type', 'items', 'vhno', 'party', 'pagetitle', 'item', 'user', 'tax', 'chartofaccount', 'payment_mode', 'job_number','unit','job','po','branch'));
    }







    public  function SaleInvoiceSave(request $request)
    {
        $invoice_mst = array(
            'InvoiceNo' => $request->input('InvoiceNo'),
            'BranchID' => $request->input('BranchID'),
            'InvoiceType' => 'Invoice',
            'PartyID' => $request->input('PartyID'),
            'JobID' => $request->input('JobID'),
             'ReferenceNo' => $request->input('ReferenceNo'),
                     'Date' => $request->input('Date'),
            'DueDate' => $request->input('DueDate'),
             'SubTotal' => $request->input('SubTotal'),
            'DiscountAmount' => $request->input('DiscountAmount'),
            'DiscountPer' => $request->input('DiscountPer'),

            'TaxType' => $request->input('TaxType'),
            'TaxPer' => $request->input('grandtotaltax') == 0 ? 0 : 5,
            'Tax' => $request->input('grandtotaltax'),
            'Shipping' => $request->input('Shipping'),
            'Total' => $request->input('Total'),
            'GrandTotal' => $request->input('Grandtotal'),
            'CustomerNotes' => $request->input('CustomerNotes'),
            'DescriptionNotes' => $request->input('DescriptionNotes'),
            'UserID' => Session::get('UserID'),
 
        );
        $InvoiceMasterID = DB::table('invoice_master')->insertGetId($invoice_mst);
        // END OF SALE RETURN
        //  start for item array from invoice
        for ($i = 0; $i < count($request->ItemID); $i++) {
            $invoice_det = array(
                'InvoiceMasterID' =>  $InvoiceMasterID,
                'InvoiceNo' => $request->input('InvoiceNo'),
                'Date' => $request->input('Date'),
                'ItemID' => $request->ItemID[$i],
                'Description' => $request->Description[$i],
                 'TaxPer' => $request->Tax[$i],
                 'Tax' => $request->TaxVal[$i],
                'LS' => $request->LS[$i],
                'Qty' => $request->Qty[$i],
                'Rate' => $request->Price[$i],
                'Total' => $request->ItemTotal[$i],
                // 'Discount' => $request->Discount[$i],
                // 'DiscountType' => $request->DiscountType[$i],
                'Gross' => $request->Gross[$i],
                // 'DiscountAmountItem' => $request->DiscountAmountItem[$i],

                 'TaxPer' => $request->Tax[$i],
                 'Tax' => $request->TaxVal[$i],


                'UnitName' => $request->UnitName[$i],
                'UnitQty' => $request->UnitQty[$i],
            );

            $id = DB::table('invoice_detail')->insertGetId($invoice_det);
        }
        // end foreach
        // Journal Entries
        // 1. A/R
        // A/R -> Debit
        if ($request->InvoiceType != "Proforma Invoice") {
            $data_ar = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110400',   //A/R
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('Grandtotal'),
                'Trace' => 123, // for debugging for reverse engineering
            );
            $journal_entry = DB::table('journal')->insertGetId($data_ar);
            // 2. Sale discount
            // Sales-Discount -> Debit
            if ($request->input('DiscountAmount') > 0) { // if dis is given
                $data_saledis = array(
                    'VHNO' => $request->input('InvoiceNo'),
                    'ChartOfAccountID' => '410155',   //Sales-Discount
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                    'Narration' => $request->input('Subject'),
                    'Date' => $request->input('Date'),
                    'Dr' => $request->input('DiscountAmount'),
                    'Trace' => 1234, // for debugging for reverse engineering
                );
                $journal_entry = DB::table('journal')->insertGetId($data_saledis);
            }
            // 3. sales
            // Sales -> Credit
            $data_sale = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '410100',   //Sales
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('SubTotal'),
                'Trace' => 12345, // for debugging for reverse engineering
            );
            $journal_entry = DB::table('journal')->insertGetId($data_sale);
            // 4. Tax -> VAT-OUTPUT TAX -> tax payable
            // VAT-OUTPUT TAX -> Credit
            if ($request->input('grandtotaltax') > 0) { // if tax item is present in invoice
                $data_vat_out = array(
                    'VHNO' => $request->input('InvoiceNo'),
                    'ChartOfAccountID' => '210300',   //VAT-OUTPUT TAX ->tax payable
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                    'Narration' => $request->input('Subject'),
                    'Date' => $request->input('Date'),
                    'Cr' => $request->input('grandtotaltax'),
                    'Trace' => 12346, // for debugging for reverse engineering
                );
                $journal_entry = DB::table('journal')->insertGetId($data_vat_out);
            }
            // 5. shipping charges
            // SHIPPING -> Credit
            if ($request->input('Shipping') > 0) { // if tax item is present in invoice
                $data_shipping = array(
                    'VHNO' => $request->input('InvoiceNo'),
                    'ChartOfAccountID' => '500100',   //shipping
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                    'Narration' => $request->input('Subject'),
                    'Date' => $request->input('Date'),
                    'Cr' => $request->input('Shipping'),
                    'Trace' => 123467, // for debugging for reverse engineering
                );
                $journal_entry = DB::table('journal')->insertGetId($data_shipping);
            }
            // when payment is made by party
            // if($request->input('amountPaid')>0)
            // {
            // // 5. Cash/Bank ->Debit
            // $data_cash_bank = array(
            // 'VHNO' => $request->input('InvoiceNo'),
            // 'ChartOfAccountID' => '110201',   //bank / cash Debit
            // 'PartyID' => $request->input('PartyID'),
            // 'InvoiceMasterID' =>$InvoiceMasterID, #7A7A7A
            // 'Narration' => $request->input('Subject'),
            // 'Date' => $request->input('Date'),
            // 'Dr' => $request->input('amountPaid'),
            // 'Trace' => 1234678, // for debugging for reverse engineering
            // );
            // $journal_entry= DB::table('journal')->insertGetId($data_cash_bank);
            // // 5. Acc Receivable  ->Credit
            // $data_ar_credit = array(
            // 'VHNO' => $request->input('InvoiceNo'),
            // 'ChartOfAccountID' => '110400',   //A/R credit
            // 'PartyID' => $request->input('PartyID'),
            // 'InvoiceMasterID' =>$InvoiceMasterID, #7A7A7A
            // 'Narration' => $request->input('Subject'),
            // 'Date' => $request->input('Date'),
            // 'Cr' => $request->input('amountPaid'),
            // 'Trace' => 1234689, // for debugging for reverse engineering
            // );
            // $journal_entry= DB::table('journal')->insertGetId($data_ar_credit);
            // // payment in payment table
            //  $payment_master = array
            //  				  (
            //  				  	'PartyID' => $request->PartyID,
            //  				  	'PaymentAmount' => $request->amountPaid,
            //  				  	'PaymentDate' => $request->Date,
            //  				  	'PaymentMode' => $request->PaymentMode,
            //  				  	'ChartOfAccountID' => 110400,
            //  				  	'ReferenceNo' => $request->ReferenceNo,
            //  				  	'Notes' => $request->Subject,
            //   				  );
            // $PaymentMasterID= DB::table('payment_master')->insertGetId($payment_master);
            //  $payment_detail = array
            // 		(
            // 		'PaymentMasterID' => $PaymentMasterID,
            // 		'PaymentDate' => $request->Date,
            // 		'InvoiceMasterID' => $InvoiceMasterID,
            // 		'Payment' => $request->amountPaid,
            // 		);
            // 	 $payment_detail= DB::table('payment_detail')->insertGetId($payment_detail);
            // // end payment in payment table
            // }
            // end of if statement -> when payment is made
            // end of journal entries
        }
        return redirect('Invoice')->with('error', 'saved sucessfully')->with('class', 'success');
    }



    public  function InvoiceCreateAuto($id)
    {


        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'Update');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////


        $pagetitle = 'Sales Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $invoice_master = DB::table('estimate_master')->where('EstimateMasterID', $id)->get();
        $invoice_detail = DB::table('estimate_detail')->where('EstimateMasterID', $id)->get();
        $item = json_encode($items);

        // dd($item);
        $party = DB::table('party')->get();
        $user = DB::table('user')->get();
        $tax = DB::table('tax')->where('Section', 'Invoice')->get();

        $job = DB::table('job')->get();
        session()->forget('VHNO');
       
        $branch = DB::table('branch')->get();

        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))->where(DB::raw('left(InvoiceNo,3)'), 'INV', 'invoice_type')->get();


         $unit = DB::table('unit')->get();    

        return view('invoice.sale_invoice_auto_create', compact('invoice_type', 'items', 'vhno', 'party', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail', 'tax','unit','job','branch'));
    }


    public function InvoiceApprove($id)
    {
        $pagetitle = 'SaleOrder';
        DB::table('invoice_master')
            ->where('InvoiceMasterID', $id)
            ->update(['Status' => 'Approved']);
        return redirect('Invoice')->with('error', 'Approved Successfully')->with('class', 'success');
    }



      public  function DNCreateAuto($id)
    {


        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'Update');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////


        $pagetitle = 'Sales Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $invoice_master = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();
        $item = json_encode($items);
        
        // dd($item);
        $party = DB::table('party')->get();
        $user = DB::table('user')->get();
        $tax = DB::table('tax')->where('Section', 'Invoice')->get();

        $job = DB::table('job')->get();
        session()->forget('VHNO');
       
        $branch = DB::table('branch')->get();

        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))->where(DB::raw('left(InvoiceNo,3)'), 'INV', 'invoice_type')->get();


         $unit = DB::table('unit')->get();    

        return view('delivery_note.delivery_notice_auto_create', compact('invoice_type', 'items', 'vhno', 'party', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail', 'tax','unit','job','branch'));
    }



    public  function SaleInvoiceEdit($id)
    {


        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'Invoice', 'Update');
        if ($allow == 0) {
            return redirect()->back()->with('error', 'You access is limited')->with('class', 'danger');
        }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////

        
         // Get the invoice details
         $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->first();
        
         // Check if invoice exists
         if (!$invoice_mst) {
             return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
         }
 
         // Check if invoice belongs to a previous year
         $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
          $current_year = date('Y');
 
        //  if ($invoice_year < $current_year) {
        //      return redirect()->back()->with('error', 'You cannot edit an invoice from a previous year.')->with('class', 'danger');
        //  }


        // if ($invoice_year < $current_year) {
        // if (env('ENV_ENABLE_EDIT') !== 'YES') {
        //     return redirect()->back()
        //         ->with('error', 'You cannot edit an invoice from a previous year.')
        //         ->with('class', 'danger');
        //     }
        // }

        $pagetitle = 'Sales Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $invoice_master = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();
        $item = json_encode($items);
        // dd($item);
        $party = DB::table('party')->get();
        $user = DB::table('user')->get();
        $tax = DB::table('tax')->where('Section', 'Invoice')->get();

        $job = DB::table('job')->get();
        session()->forget('VHNO');
        session::put('VHNO', $invoice_master[0]->InvoiceNo);
$branch = DB::table('branch')->get();

        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))->where(DB::raw('left(InvoiceNo,3)'), 'INV', 'invoice_type')->get();


         $unit = DB::table('unit')->get();    

        return view('invoice.sale_invoice_edit', compact('invoice_type', 'items', 'vhno', 'party', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail', 'tax','unit','job','branch'));
    }

    public  function SaleInvoiceUpdate(request $request)
    {
       $invoice_mst = array(
            'InvoiceNo' => $request->input('InvoiceNo'),
            'BranchID' => $request->input('BranchID'),
            'InvoiceType' => 'Invoice',
            'PartyID' => $request->input('PartyID'),
            'JobID' => $request->input('JobID'),
             'ReferenceNo' => $request->input('ReferenceNo'),
          
            'Date' => $request->input('Date'),
            'DueDate' => $request->input('DueDate'),
             'SubTotal' => $request->input('SubTotal'),
            'DiscountAmount' => $request->input('DiscountAmount'),
            'DiscountPer' => $request->input('DiscountPer'),

            'TaxType' => $request->input('TaxType'),
            'TaxPer' => $request->input('grandtotaltax') == 0 ? 0 : 5,
            'Tax' => $request->input('grandtotaltax'),
            'Shipping' => $request->input('Shipping'),
            'Total' => $request->input('Total'),
            'GrandTotal' => $request->input('Grandtotal'),
            'CustomerNotes' => $request->input('CustomerNotes'),
            'DescriptionNotes' => $request->input('DescriptionNotes'),
            'UserID' => Session::get('UserID'),
 
        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);


        $InvoiceMasterID = $request->InvoiceMasterID;

        $InvoiceMst = DB::table('invoice_master')->where('InvoiceMasterID', $request->input('InvoiceMasterID'))->update($invoice_mst);


        $id1 = DB::table('invoice_detail')->where('InvoiceMasterID', $request->InvoiceMasterID)->delete();
        if ($request->InvoiceType != "Proforma Invoice") {
        
            $id3 = DB::table('journal')->where('InvoiceMasterID', $request->InvoiceMasterID)
            ->whereNull('PaymentMasterID')
            ->delete();


  



            // delete from journal too



            // END OF SALE RETURN

            //  start for item array from invoice
            for ($i = 0; $i < count($request->ItemID); $i++) {
            $invoice_det = array(
                'InvoiceMasterID' =>  $request->InvoiceMasterID,
                'InvoiceNo' => $request->input('InvoiceNo'),
                'Date' => $request->input('Date'),
                'ItemID' => $request->ItemID[$i],
                'Description' => $request->Description[$i],
                 'TaxPer' => $request->Tax[$i],
                 'Tax' => $request->TaxVal[$i],
                'LS' => $request->LS[$i],
                'Qty' => $request->Qty[$i],
                'Rate' => $request->Price[$i],
                'Total' => $request->ItemTotal[$i],
                // 'Discount' => $request->Discount[$i],
                // 'DiscountType' => $request->DiscountType[$i],
                'Gross' => $request->Gross[$i],
                // 'DiscountAmountItem' => $request->DiscountAmountItem[$i],

                 'TaxPer' => $request->Tax[$i],
                 'Tax' => $request->TaxVal[$i],


                'UnitName' => $request->UnitName[$i],
                'UnitQty' => $request->UnitQty[$i],
            );

            $id = DB::table('invoice_detail')->insertGetId($invoice_det);
        }

            // end foreach



            // Journal Entries

            // 1. A/R

            // A/R -> Debit
            $data_ar = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110400',   //A/R
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('Grandtotal'),
                'Trace' => 123, // for debugging for reverse engineering

            );

            $journal_entry = DB::table('journal')->insertGetId($data_ar);

            // 2. Sale discount

            // Sales-Discount -> Debit

            if ($request->input('DiscountAmount') > 0) { // if dis is given


                $data_saledis = array(
                    'VHNO' => $request->input('InvoiceNo'),
                    'ChartOfAccountID' => '410155',   //Sales-Discount
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                    'Narration' => $request->input('Subject'),
                    'Date' => $request->input('Date'),
                    'Dr' => $request->input('DiscountAmount'),
                    'Trace' => 1234, // for debugging for reverse engineering

                );


                $journal_entry = DB::table('journal')->insertGetId($data_saledis);
            }
            // 3. sales

            // Sales -> Credit
            $data_sale = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '410100',   //Sales
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('SubTotal'),
                'Trace' => 12345, // for debugging for reverse engineering

            );

            $journal_entry = DB::table('journal')->insertGetId($data_sale);

            // 4. Tax -> VAT-OUTPUT TAX -> tax payable

            // VAT-OUTPUT TAX -> Credit

            if ($request->input('grandtotaltax') > 0) { // if tax item is present in invoice


                $data_vat_out = array(
                    'VHNO' => $request->input('InvoiceNo'),
                    'ChartOfAccountID' => '210300',   //VAT-OUTPUT TAX ->tax payable
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                    'Narration' => $request->input('Subject'),
                    'Date' => $request->input('Date'),
                    'Cr' => $request->input('TaxpercentageAmount'),
                    'Trace' => 12346, // for debugging for reverse engineering


                );

                $journal_entry = DB::table('journal')->insertGetId($data_vat_out);
            }



            // 5. shipping charges

            // SHIPPING -> Credit

            if ($request->input('Shipping') > 0) { // if tax item is present in invoice


                $data_shipping = array(
                    'VHNO' => $request->input('InvoiceNo'),
                    'ChartOfAccountID' => '500100',   //shipping
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                    'Narration' => $request->input('Subject'),
                    'Date' => $request->input('Date'),
                    'Cr' => $request->input('Shipping'),
                    'Trace' => 123467, // for debugging for reverse engineering


                );

                $journal_entry = DB::table('journal')->insertGetId($data_shipping);
            }


            // when payment is made by party
            // if($request->input('amountPaid')>0)
            // {

            // // 5. Cash/Bank ->Debit


            // $data_cash_bank = array(
            // 'VHNO' => $request->input('InvoiceNo'),
            // 'ChartOfAccountID' => '110201',   //bank / cash Debit
            // 'PartyID' => $request->input('PartyID'),
            // 'InvoiceMasterID' =>$InvoiceMasterID, #7A7A7A
            // 'Narration' => $request->input('Subject'),
            // 'Date' => $request->input('Date'),
            // 'Dr' => $request->input('amountPaid'),
            // 'Trace' => 1234678, // for debugging for reverse engineering


            // );

            // $journal_entry= DB::table('journal')->insertGetId($data_cash_bank);


            // // 5. Acc Receivable  ->Credit

            // $data_ar_credit = array(
            // 'VHNO' => $request->input('InvoiceNo'),
            // 'ChartOfAccountID' => '110400',   //A/R credit
            // 'PartyID' => $request->input('PartyID'),
            // 'InvoiceMasterID' =>$InvoiceMasterID, #7A7A7A
            // 'Narration' => $request->input('Subject'),
            // 'Date' => $request->input('Date'),
            // 'Cr' => $request->input('amountPaid'),
            // 'Trace' => 1234689, // for debugging for reverse engineering


            // );

            // $journal_entry= DB::table('journal')->insertGetId($data_ar_credit);

            // }

            // end of if statement -> when payment is made

            // end of journal entries


        }


        return redirect('Invoice')->with('error', 'Invoice Saved')->with('class', 'success');
    }
    public  function SaleInvoiceView($id)
    {
        $pagetitle = 'Sales Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();
        $item = json_encode($items);
        // dd($item);
        $party = DB::table('party')->get();
        $user = DB::table('user')->get();
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
            ->get();


        session()->forget('VHNO');
        session::put('VHNO', $invoice_master[0]->InvoiceNo);

        return view('sale_invoice_view', compact('invoice_type', 'items', 'vhno', 'party', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail', 'company'));
    }


    public  function SaleInvoiceViewPDF($id,$branchid)
    {


        $pagetitle = 'Sales Invoice';


        session::put('menu', 'SalesInvoice');
        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();


 
        $party = DB::table('party')->get();

        $pdf = PDF::loadView('invoice.`sale_invoice_view_pdf`', compact('company', 'pagetitle', 'invoice_master', 'invoice_detail'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();

     }
    public function SaleInvoiceDelete($id)
    {


         // Get the invoice details
         $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->first();
        
         // Check if invoice exists
         if (!$invoice_mst) {
             return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
         }
 
         // Check if invoice belongs to a previous year
         $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
          $current_year = date('Y');
 
        //  if ($invoice_year < $current_year) {
        //      return redirect()->back()->with('error', 'You cannot delete an invoice from a previous year.')->with('class', 'danger');
        //  }

         $idd = DB::table('invoice_master')->where('InvoiceMasterID', $id)->delete();
        $invoice_detail_summary = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->delete();
        $idsss = DB::table('journal')->where('InvoiceMasterID', $id)->delete();


        // delete from journal too

        return redirect('Invoice')->with('error', 'Deleted Successfully')->with('class', 'success');
    }
    public function CreditNote()
    {
        $pagetitle = 'All Credit Notes';


        return view('ebooks.credit_note', compact('pagetitle'));
    }


    public function ajax_creditnote(Request $request)
    {
        session::put('menu', 'CreditNote');
        $pagetitle = 'Delivery Challan';
        if ($request->ajax()) {
            $data = DB::table('v_invoice_master')->orderBy('InvoiceMasterID')->where('InvoiceNo', 'like', 'CN%')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>
                    $btn = '

                       <div class="d-flex align-items-center col-actions">


<a href="' . URL('/CreditNoteView/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/CreditNoteEdit/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-pencil align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/CreditNoteViewPDF/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi mdi-file-pdf-outline align-middle me-1 text-secondary"></i></a>
<a href="' . URL('/CreditNoteDelete/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-trash-can-outline align-middle me-1 text-secondary"></i></a>




                       </div>';

                    //class="edit btn btn-primary btn-sm"
                    // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ebooks.credit_note', 'pagetitle');
    }


    public  function CreditNoteCreate()
    {
        $pagetitle = 'Sales Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $item = json_encode($items);
        // dd($item);
        $party = DB::table('party')->get();
        $user = DB::table('user')->get();
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
            ->where(DB::RAW('LEFT(InvoiceNo,2)'), 'CN')->get();

        session::put('VHNO', 'CN-' . $vhno[0]->VHNO);

        return view('ebooks.credit_note_create', compact('invoice_type', 'items', 'vhno', 'party', 'pagetitle', 'item', 'user'));
    }
    public  function CreditNoteSave(request $request)
    {
        // dd($request->all());
        $invoice_mst = array(
            'InvoiceNo' => $request->InvoiceNo,
            'InvoiceType' => $request->InvoiceType,
            'Date' => $request->Date,
            'DueDate' => $request->DueDate,
            'PartyID' => $request->PartyID,
            'WalkinCustomerName' => $request->WalkinCustomerName,
            'ReferenceNo' => $request->ReferenceNo,
            'PaymentMode' => $request->PaymentMode,
            'PaymentDetails' => $request->PaymentDetails,
            'Subject' => $request->Subject,
            'SubTotal' => $request->SubTotal,
            'DiscountPer' => $request->DiscountPer,
            'DiscountAmount' => $request->DiscountAmount,
            'Total' => $request->Total,
            'TaxPer' => $request->Taxpercentage,
            'Tax' => $request->TaxpercentageAmount,
            'Shipping' => $request->Shipping,
            'GrandTotal' => $request->Grandtotal,
            'Paid' => $request->amountPaid,
            'Balance' => $request->amountDue,
            'CustomerNotes' => $request->CustomerNotes,
            'DescriptionNotes' => $request->DescriptionNotes,
            'UserID' => session::get('UserID'),
        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);

        $InvoiceMasterID = DB::table('invoice_master')->insertGetId($invoice_mst);
        // END OF SALE RETURN

        //  start for item array from invoice
        for ($i = 0; $i < count($request->ItemID); $i++) {
            $invoice_det = array(
                'InvoiceMasterID' =>  $InvoiceMasterID,
                'InvoiceNo' => $request->InvoiceNo,
                'ItemID' => $request->ItemID[$i],
                'PartyID' => $request->input('PartyID'),
                'Qty' => $request->Qty[$i],
                'Description' => $request->Description[$i],

                'Rate' => $request->Price[$i],
                'Total' => $request->ItemTotal[$i],

            );

            $id = DB::table('invoice_detail')->insertGetId($invoice_det);
        }

        // end foreach




        // Journal Entries


        // 3. Sale Return

        // Sale Return -> Debit
        $data_sale_return = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '410175',   //Sale Return
            'PartyID' => $request->input('PartyID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Dr' => $request->input('SubTotal') - $request->input('TaxTotal'),
            'Trace' => 2123, // for debugging for reverse engineering

            // 'SubTotal' => $request->input('SubTotal'),
            // 'TotalTax' => $request->input('TaxTotal'),
            // 'DiscountAmount' => $request->input('DiscountAmount'),
            // 'Total' => $request->input('Total'),
            // 'Paid' => $request->input('amountPaid'),
            // 'Balance' => $request->input('amountDue'),
        );

        $journal_entry = DB::table('journal')->insertGetId($data_sale_return);


        // 4. Tax -> VAT-OUTPUT TAX

        // VAT-OUTPUT TAX -> Debit

        if ($request->input('TaxTotal') > 0) { // if tax item is present in invoice


            $data_vat_out = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '230100',   //VAT-OUTPUT TAX
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('TaxTotal'),
                'Trace' => 212346, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_vat_out);
        }



        // 2. Sale discount

        // Sales-Discount -> credit

        if ($request->input('DiscountAmount') > 0) { // if dis is given


            $data_saledis = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '410155',   //Sales-Discount
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('DiscountAmount'),
                'Trace' => 21234, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );


            $journal_entry = DB::table('journal')->insertGetId($data_saledis);
        }



        // 1. A/R

        // A/R -> Debit
        $data_ar = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '110400',   //A/R
            'PartyID' => $request->input('PartyID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Cr' => $request->input('Total'),
            'Trace' => 2123, // for debugging for reverse engineering

            // 'SubTotal' => $request->input('SubTotal'),
            // 'TotalTax' => $request->input('TaxTotal'),
            // 'DiscountAmount' => $request->input('DiscountAmount'),
            // 'Total' => $request->input('Total'),
            // 'Paid' => $request->input('amountPaid'),
            // 'Balance' => $request->input('amountDue'),
        );

        $journal_entry = DB::table('journal')->insertGetId($data_ar);










        // when payment is made by party
        if ($request->input('amountPaid') > 0) {



            // 5. Acc Receivable  ->debit

            $data_ar_debit = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110400',   //A/R debit
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
                'Trace' => 2123468, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_ar_debit);



            // 6. Cash/Bank ->Debit


            $data_cash_bank = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110201',   //bank / cash Debit
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('amountPaid'),
                'Trace' => 2123467, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_cash_bank);
        }

        // end of if statement -> when payment is made

        // end of journal entries

        return redirect('CreditNote')->with('error', 'Invoice Saved')->with('class', 'success');
    }


    public  function CreditNoteEdit($id)
    {
        $pagetitle = 'Sales Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $invoice_master = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();
        $item = json_encode($items);
        // dd($item);
        $party = DB::table('party')->get();
        $user = DB::table('user')->get();
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
            ->get();


        session::forget('VHNO');
        session::put('VHNO', $invoice_master[0]->InvoiceNo);


        return view('ebooks.credit_note_edit', compact('invoice_type', 'items', 'vhno', 'party', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail'));
    }

    public  function CreditNoteUpdate(request $request)
    {
        // dd($request->all());
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        // $allow= check_role(session::get('UserID'),'Invoice','Create');
        // if($allow[0]->Allow=='N')
        // {
        //   return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
        // }
        ////////////////////////////END SCRIPT ////////////////////////////////////////////////




        // dd($request->all());
        $invoice_mst = array(
            'InvoiceNo' => $request->InvoiceNo,
            'Date' => $request->Date,
            'DueDate' => $request->DueDate,
            'PartyID' => $request->PartyID,
            'WalkinCustomerName' => $request->WalkinCustomerName,
            'ReferenceNo' => $request->ReferenceNo,
            'PaymentMode' => $request->PaymentMode,
            'PaymentDetails' => $request->PaymentDetails,
            'Subject' => $request->Subject,
            'SubTotal' => $request->SubTotal,
            'DiscountPer' => $request->DiscountPer,
            'DiscountAmount' => $request->DiscountAmount,
            'Total' => $request->Total,
            'TaxPer' => $request->Taxpercentage,
            'Tax' => $request->TaxpercentageAmount,
            'Shipping' => $request->Shipping,
            'GrandTotal' => $request->Grandtotal,
            'Paid' => $request->amountPaid,
            'Balance' => $request->amountDue,
            'CustomerNotes' => $request->CustomerNotes,
            'DescriptionNotes' => $request->DescriptionNotes,
            'UserID' => session::get('UserID'),
        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);


        $InvoiceMasterID   = $request->InvoiceMasterID;


        $InvoiceMst = DB::table('invoice_master')->where('InvoiceMasterID', $request->input('InvoiceMasterID'))->update($invoice_mst);


        $id = DB::table('invoice_detail')->where('InvoiceMasterID', $request->input('InvoiceMasterID'))->delete();
        $id = DB::table('journal')->where('InvoiceMasterID', $request->input('InvoiceMasterID'))->delete();
        // delete from journal too



        // END OF SALE RETURN

        //  start for item array from invoice
        for ($i = 0; $i < count($request->ItemID); $i++) {

            $invoice_det = array(
                'InvoiceMasterID' =>  $InvoiceMasterID,
                'InvoiceNo' => $request->InvoiceNo,
                'ItemID' => $request->ItemID[$i],
                'PartyID' => $request->input('PartyID'),
                'Qty' => $request->Qty[$i],
                'Description' => $request->Description[$i],

                'Rate' => $request->Price[$i],
                'Total' => $request->ItemTotal[$i],

            );

            $id = DB::table('invoice_detail')->insertGetId($invoice_det);
        }

        // end foreach



        // Journal Entries


        // 3. Sale Return

        // Sale Return -> Debit
        $data_sale_return = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '410175',   //Sale Return
            'PartyID' => $request->input('PartyID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Dr' => $request->input('SubTotal') - $request->input('TaxTotal'),
            'Trace' => 2123, // for debugging for reverse engineering

            // 'SubTotal' => $request->input('SubTotal'),
            // 'TotalTax' => $request->input('TaxTotal'),
            // 'DiscountAmount' => $request->input('DiscountAmount'),
            // 'Total' => $request->input('Total'),
            // 'Paid' => $request->input('amountPaid'),
            // 'Balance' => $request->input('amountDue'),
        );

        $journal_entry = DB::table('journal')->insertGetId($data_sale_return);


        // 4. Tax -> VAT-OUTPUT TAX

        // VAT-OUTPUT TAX -> Debit

        if ($request->input('TaxTotal') > 0) { // if tax item is present in invoice


            $data_vat_out = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '230100',   //VAT-OUTPUT TAX
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('TaxTotal'),
                'Trace' => 212346, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_vat_out);
        }



        // 2. Sale discount

        // Sales-Discount -> credit

        if ($request->input('DiscountAmount') > 0) { // if dis is given


            $data_saledis = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '410155',   //Sales-Discount
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('DiscountAmount'),
                'Trace' => 21234, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );


            $journal_entry = DB::table('journal')->insertGetId($data_saledis);
        }



        // 1. A/R

        // A/R -> Debit
        $data_ar = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '110400',   //A/R
            'PartyID' => $request->input('PartyID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Cr' => $request->input('Total'),
            'Trace' => 2123, // for debugging for reverse engineering

            // 'SubTotal' => $request->input('SubTotal'),
            // 'TotalTax' => $request->input('TaxTotal'),
            // 'DiscountAmount' => $request->input('DiscountAmount'),
            // 'Total' => $request->input('Total'),
            // 'Paid' => $request->input('amountPaid'),
            // 'Balance' => $request->input('amountDue'),
        );

        $journal_entry = DB::table('journal')->insertGetId($data_ar);










        // when payment is made by party
        if ($request->input('amountPaid') > 0) {



            // 5. Acc Receivable  ->debit

            $data_ar_debit = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110400',   //A/R debit
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
                'Trace' => 2123468, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_ar_debit);



            // 6. Cash/Bank ->Debit


            $data_cash_bank = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110201',   //bank / cash Debit
                'PartyID' => $request->input('PartyID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('amountPaid'),
                'Trace' => 2123467, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_cash_bank);
        }

        // end of if statement -> when payment is made

        // end of journal entries







        return redirect('CreditNote')->with('error', 'Credit Note Saved')->with('class', 'success');
    }


    public function CreditNoteDelete($id)
    {


        $idd = DB::table('invoice_master')->where('InvoiceMasterID', $id)->delete();
        $invoice_detail = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->delete();
        $idsss = DB::table('journal')->where('InvoiceMasterID', $id)->delete();


        // delete from journal too

        return redirect('CreditNote')->with('error', 'Deleted Successfully')->with('class', 'success');
    }



    public  function CreditNoteView($id)
    {
        $pagetitle = 'Sales Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();
        $item = json_encode($items);
        // dd($item);
        $party = DB::table('party')->get();
        $user = DB::table('user')->get();
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
            ->get();


        session::forget('VHNO');
        session::put('VHNO', $invoice_master[0]->InvoiceNo);

        return view('ebooks.credit_note_view', compact('invoice_type', 'items', 'vhno', 'party', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail', 'company'));
    }


    public  function CreditNoteViewPDF($id)
    {
        $pagetitle = 'Credit Note';


        session::put('menu', 'SalesInvoice');
        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();

        $party = DB::table('party')->get();

        $pdf = PDF::loadView('ebooks.credit_note_view_pdf', compact('company', 'pagetitle', 'invoice_master', 'invoice_detail'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();

        // return view ('ebooks.credit_note_view_pdf',compact('invoice_type','items','party','pagetitle','item','user','invoice_master','invoice_detail','company'));
    }





    public function CreditNote111()
    {
        $pagetitle = 'All Credit Notes';




        return view('ebooks.credit_note', compact('pagetitle'));
    }

    public  function BlankReport()
    {

        $pagetitle = 'Report';

        return view('ebooks.reports.blank_report', compact('pagetitle'))->with('error', 'Logout Successfully.')->with('class', 'success');
    }


    public function Bill()
    {


        // dd('here');
        return view('purchase.bill');
    }

    public function Ajax_bill(Request $request)

    {

        session::put('menu', 'Bill');

        $pagetitle = 'Purchase Invoice';

        if ($request->ajax()) {
            $data = DB::table('v_invoice_master_supplier')->orderBy('InvoiceMasterID')->where('InvoiceNo', 'like', 'BILL%')->get();

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>



                    $btn = '





                     <div class="d-flex align-items-center col-actions">


                <a href="' . URL('/BillView/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a>
                <a href="' . URL('/BillEdit/' . $row->InvoiceMasterID) . '"><i class="font-size-18 bx bx-pencil align-middle me-1 text-secondary"></i></a>

                <a href="' . URL('/BillDelete/' . $row->InvoiceMasterID) . '"><i class="font-size-18 bx bx-trash align-middle me-1 text-secondary"></i></a>
                <a href="' . URL('/BillViewPDF/' . $row->InvoiceMasterID) . '"><i class="font-size-18 me-1 mdi mdi-file-pdf-outline align-middle me-1 text-secondary"></i></a>








                     </div>


                     ';

                    //class="edit btn btn-primary btn-sm"
                    // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ebooks.bill', 'pagetitle');
    }



    public function BillCreate()
    {

        $pagetitle = 'Purchase Invoice';

        $supplier = DB::table('supplier')->get();
        $items = DB::table('item')->get();
        $user = DB::table('user')->get();
        $unit = DB::table('unit')->get();
        $job = DB::table('job')->get();
        $po = DB::table('invoice_master')->select('InvoiceNo')->where('InvoiceType','PO')->get();

        $items = DB::table('item')->get();
        $tax = DB::table('tax')->where('Section', 'Invoice')->get();
        $item = json_encode($items);
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(RIGHT(InvoiceNo,5)),0)+1,5,0) as VHNO '))
            ->where(DB::raw('left(InvoiceNo,4)'), 'BILL')
            ->get();

        session::put('VHNO', 'BILL-' . $vhno[0]->VHNO);


        // $items=DB::table('product')->get();
        return view('purchase.bill_create', compact('supplier',  'items', 'user', 'vhno', 'item', 'items', 'pagetitle', 'tax','unit','job','po'));
    }

    public function BillSave(Request $request)
    {

        // dd($request->all());
        $invoice_mst = array(
            'InvoiceNo' => $request->InvoiceNo,
            'JobID' => $request->JobID,
            'PONo' => $request->PONo,
            'InvoiceType' => 'Bill',
            'Date' => $request->Date,
            'DueDate' => $request->DueDate,
            'SupplierID' => $request->SupplierID,
            'WalkinCustomerName' => $request->WalkinCustomerName,
            'ReferenceNo' => $request->ReferenceNo,
            'PaymentMode' => $request->PaymentMode,
            'PaymentDetails' => $request->PaymentDetails,
            'Subject' => $request->Subject,
            'SubTotal' => $request->SubTotal,
            'DiscountPer' => $request->DiscountPer,
            'DiscountAmount' => $request->DiscountAmount,
            'Total' => $request->Total,
            'TaxPer' => $request->grandtotaltax == 0 ? 0 : 5,
            'Tax' => $request->grandtotaltax,
            'Shipping' => $request->Shipping,
            'GrandTotal' => $request->Grandtotal,
            'Paid' => $request->amountPaid,
            'Balance' => $request->amountDue,
            'CustomerNotes' => $request->CustomerNotes,
            'DescriptionNotes' => $request->DescriptionNotes,
            'UserID' => session::get('UserID'),
        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);

        $InvoiceMasterID = DB::table('invoice_master')->insertGetId($invoice_mst);
        // END OF SALE RETURN

        //  start for item array from invoice
        for ($i = 0; $i < count($request->ItemID); $i++) {
            $invoice_det = array(
                'InvoiceMasterID' =>  $InvoiceMasterID, 
                'InvoiceNo' => $request->InvoiceNo, 
                 'ItemID' => $request->ItemID[$i],
                 'SupplierID' => $request->input('SupplierID'), 


                'UnitName' => $request->UnitName[$i],
                'UnitQty' => $request->UnitQty[$i],

                'Qty' => $request->Qty[$i],
                'Description' => $request->Description[$i],
                'TaxPer' => $request->Tax[$i],
                'Tax' => $request->TaxVal[$i],

                'Rate' => $request->Price[$i],
                'Total' => $request->ItemTotal[$i],

            );

            $id = DB::table('invoice_detail')->insertGetId($invoice_det);
        }
        // Journal Entries

        // 1. stock inventory

        // Stock inventory

        $data_stock_inventory = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '510102',   //Stock inventory
            'SupplierID' => $request->input('SupplierID'),
            'JobID' => $request->input('JobID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Dr' => $request->input('SubTotal'),
            'Trace' => 111, // for debugging for reverse engineering

        );

        $journal_entry = DB::table('journal')->insertGetId($data_stock_inventory);



        // 2. Sale discount


        if ($request->input('DiscountAmount') > 0) {
            // if dis is given


            $data_saledis = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '410155',   //Sales-Discount
                'SupplierID' => $request->input('SupplierID'),
                'JobID' => $request->input('JobID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('DiscountAmount'),
                'Trace' => 1111, // for debugging for reverse engineering

            );


            $journal_entry = DB::table('journal')->insertGetId($data_saledis);
        }


        if ($request->input('grandtotaltax') > 0) {
            // 3. TAX ON PURCHASES

            $data_tax_dis = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110800',   // TAX ON PURCHASES
                'SupplierID' => $request->input('SupplierID'),
                'JobID' => $request->input('JobID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('grandtotaltax'),
                'Trace' => 11112, // for debugging for reverse engineering

            );

            $journal_entry = DB::table('journal')->insertGetId($data_tax_dis);
        }

        // 4. Shipping Expenses

        if ($request->input('Shipping') > 0) { // if tax item is present in invoice


            $data_shipping = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '500100',   //Shipping Expenses
                'SupplierID' => $request->input('SupplierID'),
                'JobID' => $request->input('JobID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('Shipping'),
                'Trace' => 11113, // for debugging for reverse engineering

            );

            $journal_entry = DB::table('journal')->insertGetId($data_shipping);
        }


        // 5. Acc Payable  ->credit

        $data_ac_payable = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '210100',   // Acc Payable  ->Credit
            'SupplierID' => $request->input('SupplierID'),
            'JobID' => $request->input('JobID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Cr' => $request->input('Grandtotal'),
            'Trace' => 11114, // for debugging for reverse engineering

        );

        $journal_entry = DB::table('journal')->insertGetId($data_ac_payable);





        // // when payment is made by us
        // if ($request->input('amountPaid') > 0) {




        //   // 6. Acc Payable  ->Debit

        //   $data_ap_credit = array(
        //     'VHNO' => $request->input('InvoiceNo'),
        //     'ChartOfAccountID' => '210100',   //A/Payable credit
        //     'SupplierID' => $request->input('SupplierID'),
        //     'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
        //     'Narration' => $request->input('Subject'),
        //     'Date' => $request->input('Date'),
        //     'Dr' => $request->input('amountPaid'),
        //     'Trace' => 11116, // for debugging for reverse engineering


        //   );

        //   $journal_entry = DB::table('journal')->insertGetId($data_ap_credit);


        //   // 5. Cash/Bank ->Credit




        //   $data_cash_bank = array(
        //     'VHNO' => $request->input('InvoiceNo'),
        //     'ChartOfAccountID' => '110201',   //bank / cash Debit
        //     'SupplierID' => $request->input('SupplierID'),
        //     'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
        //     'Narration' => $request->input('Subject'),
        //     'Date' => $request->input('Date'),
        //     'Cr' => $request->input('amountPaid'),
        //     'Trace' => 11115, // for debugging for reverse engineering


        //   );

        //   $journal_entry = DB::table('journal')->insertGetId($data_cash_bank);



        // }

        return redirect('Bill')->with('error', 'Bill Saved')->with('class', 'success');
    }


    public function BillView($id)
    {
        $pagetitle = 'Purchase Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();

        $items = DB::table('item')->get();

        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master_supplier')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();
        $item = json_encode($items);
        // dd($item);
        $supplier = DB::table('supplier')->get();
        $user = DB::table('user')->get();


        return view('purchase.bill_view', compact('invoice_type', 'items', 'supplier', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail', 'company'));
    }

    public  function BillViewPDF($id)
    {
        $pagetitle = 'Purchase Invoice';


        session::put('menu', 'SalesInvoice');
        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master_supplier')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();

        $party = DB::table('party')->get();

        $pdf = PDF::loadView('purchase.bill_view_pdf', compact('company', 'pagetitle', 'invoice_master', 'invoice_detail'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();

        return view('sale_invoice_view_pdf', compact('invoice_type', 'items', 'party', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail', 'company'));
    }


    public function BillEdit($id)
    {
        $pagetitle = 'Purchase Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();

        $items = DB::table('item')->get();
        $tax = DB::table('tax')->where('Section', 'Invoice')->get();
        $invoice_master = DB::table('v_invoice_master_supplier')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();

        $job = DB::table('job')->get();
        $unit = DB::table('unit')->get();
        $item = json_encode($items);
        // dd($item);
        $supplier = DB::table('supplier')->get();
        $user = DB::table('user')->get();

        $po = DB::table('invoice_master')->select('InvoiceNo')->where('InvoiceType','PO')->get();


        return view('purchase.bill_edit', compact('invoice_type', 'items', 'supplier', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail', 'tax','job','po','unit'));
    }
    public function BillUpdate(Request $request)
    {
        // dd($request->all());
        $invoice_mst = array(
            'InvoiceNo' => $request->InvoiceNo,
            'InvoiceType' => $request->InvoiceType,
            'Date' => $request->Date,
            'DueDate' => $request->DueDate,
            'PartyID' => $request->PartyID,
            'WalkinCustomerName' => $request->WalkinCustomerName,
            'ReferenceNo' => $request->ReferenceNo,
            'PaymentMode' => $request->PaymentMode,
            'PaymentDetails' => $request->PaymentDetails,
            'Subject' => $request->Subject,
            'SubTotal' => $request->SubTotal,
            'DiscountPer' => $request->DiscountPer,
            'DiscountAmount' => $request->DiscountAmount,
            'Total' => $request->Total,
            'TaxPer' => $request->grandtotaltax == 0 ? 0 : 5,
            'Tax' => $request->grandtotaltax,
            'Shipping' => $request->Shipping,
            'GrandTotal' => $request->Grandtotal,
            'Paid' => $request->amountPaid,
            'Balance' => $request->amountDue,
            'CustomerNotes' => $request->CustomerNotes,
            'DescriptionNotes' => $request->DescriptionNotes,
            'UserID' => session::get('UserID'),
        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);


        $InvoiceMasterID = $request->InvoiceMasterID;

        $InvoiceMst = DB::table('invoice_master')->where('InvoiceMasterID', $request->input('InvoiceMasterID'))->update($invoice_mst);


        $id = DB::table('invoice_detail')->where('InvoiceMasterID', $request->InvoiceMasterID)->delete();
        // $id2 = DB::table('journal')->where('InvoiceMasterID', $request->InvoiceMasterID)->delete();

        // delete from journal too



        // END OF SALE RETURN

        //  start for item array from invoice
        for ($i = 0; $i < count($request->ItemID); $i++) {

            $invoice_det = array(
                'InvoiceMasterID' => $request->InvoiceMasterID,
                'InvoiceNo' => $request->InvoiceNo,
                'ItemID' => $request->ItemID[$i],
                'PartyID' => $request->input('PartyID'),
                'Qty' => $request->Qty[$i],
                'Rate' => $request->Price[$i],
                'Description' => $request->Description[$i],
                // 'TaxPer' => $request->Tax[$i],
                // 'Tax' => $request->TaxVal[$i],
                'Total' => $request->ItemTotal[$i],

            );

            $id = DB::table('invoice_detail')->insertGetId($invoice_det);
        }

        DB::table('journal')->where('InvoiceMasterID', $InvoiceMasterID)->delete();
        // Journal Entries

        // 1. purchases

        // purchases

        $data_stock_inventory = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '510102',   //purchases
            'SupplierID' => $request->input('SupplierID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Dr' => $request->input('SubTotal'),
            'Trace' => 111, // for debugging for reverse engineering

        );

        $journal_entry = DB::table('journal')->insertGetId($data_stock_inventory);



        // 2. Sale discount


        if ($request->input('DiscountAmount') > 0) {
            // if dis is given


            $data_saledis = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '410155',   //Sales-Discount
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('DiscountAmount'),
                'Trace' => 1111, // for debugging for reverse engineering

            );


            $journal_entry = DB::table('journal')->insertGetId($data_saledis);
        }


        if ($request->input('grandtotaltax') > 0) {
            // 3. TAX ON PURCHASES

            $data_tax_dis = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110800',   // TAX ON PURCHASES
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('grandtotaltax'),
                'Trace' => 11112, // for debugging for reverse engineering

            );

            $journal_entry = DB::table('journal')->insertGetId($data_tax_dis);
        }

        // 4. Shipping Expenses

        if ($request->input('Shipping') > 0) { // if tax item is present in invoice


            $data_shipping = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '500100',   //Shipping Expenses
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('Shipping'),
                'Trace' => 11113, // for debugging for reverse engineering

            );

            $journal_entry = DB::table('journal')->insertGetId($data_shipping);
        }


        // 5. Acc Payable  ->credit

        $data_ac_payable = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '210100',   // Acc Payable  ->Credit
            'SupplierID' => $request->input('SupplierID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Cr' => $request->input('Grandtotal'),
            'Trace' => 11114, // for debugging for reverse engineering

        );

        $journal_entry = DB::table('journal')->insertGetId($data_ac_payable);





        // // when payment is made by us
        // if ($request->input('amountPaid') > 0) {




        //   // 6. Acc Payable  ->Debit

        //   $data_ap_credit = array(
        //     'VHNO' => $request->input('InvoiceNo'),
        //     'ChartOfAccountID' => '210100',   //A/R credit
        //     'SupplierID' => $request->input('SupplierID'),
        //     'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
        //     'Narration' => $request->input('Subject'),
        //     'Date' => $request->input('Date'),
        //     'Dr' => $request->input('amountPaid'),
        //     'Trace' => 111116, // for debugging for reverse engineering

        //     // 'SubTotal' => $request->input('SubTotal'),
        //     // 'TotalTax' => $request->input('TaxTotal'),
        //     // 'DiscountAmount' => $request->input('DiscountAmount'),
        //     // 'Total' => $request->input('Total'),
        //     // 'Paid' => $request->input('amountPaid'),
        //     // 'Balance' => $request->input('amountDue'),
        //   );

        //   $journal_entry = DB::table('journal')->insertGetId($data_ap_credit);


        //   // 5. Cash/Bank ->Credit


        //   $data_cash_bank = array(
        //     'VHNO' => $request->input('InvoiceNo'),
        //     'ChartOfAccountID' => '110200',   //bank / cash Debit
        //     'SupplierID' => $request->input('SupplierID'),
        //     'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
        //     'Narration' => $request->input('Subject'),
        //     'Date' => $request->input('Date'),
        //     'Cr' => $request->input('amountPaid'),
        //     'Trace' => 111115, // for debugging for reverse engineering

        //     // 'SubTotal' => $request->input('SubTotal'),
        //     // 'TotalTax' => $request->input('TaxTotal'),
        //     // 'DiscountAmount' => $request->input('DiscountAmount'),
        //     // 'Total' => $request->input('Total'),
        //     // 'Paid' => $request->input('amountPaid'),
        //     // 'Balance' => $request->input('amountDue'),
        //   );

        //   $journal_entry1 = DB::table('journal')->insertGetId($data_cash_bank);



        // }



        // end foreach


        return redirect('Bill')->with('error', 'Invoice Saved')->with('class', 'success');
    }
    public function BillDelete($id)
    {
        $pagetitle = 'Purchase Invoice';

        $idd = DB::table('invoice_master')->where('InvoiceMasterID', $id)->delete();
        $id1 = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->delete();
        $id2 = DB::table('journal')->where('InvoiceMasterID', $id)->delete();


        // delete from journal too

        return redirect('Bill')->with('error', 'Deleted Successfully')->with('class', 'danger');
    }

    public function DebitNoteCreate()
    {

        $pagetitle = 'Debit Note';

        $supplier = DB::table('supplier')->get();
        $items = DB::table('item')->get();
        $user = DB::table('user')->get();

        $items = DB::table('item')->get();

        $item = json_encode($items);
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
            ->where(DB::raw('left(InvoiceNo,2)'), 'DN')->get();

        session::put('VHNO', 'DN-' . $vhno[0]->VHNO);

        // $items=DB::table('product')->get();
        return view('purchase.debit_note_create', compact('supplier',  'items', 'user', 'vhno', 'item', 'items', 'pagetitle'));
    }


    public function DebitNoteSave(Request $request)
    {
        // dd($request->all());
        $invoice_mst = array(
            'InvoiceNo' => $request->InvoiceNo,
            'Date' => $request->Date,
            'DueDate' => $request->DueDate,
            'SupplierID' => $request->SupplierID,
            'WalkinCustomerName' => $request->WalkinCustomerName,
            'ReferenceNo' => $request->ReferenceNo,
            'PaymentMode' => $request->PaymentMode,
            'PaymentDetails' => $request->PaymentDetails,
            'Subject' => $request->Subject,
            'SubTotal' => $request->SubTotal,
            'DiscountPer' => $request->DiscountPer,
            'DiscountAmount' => $request->DiscountAmount,
            'Total' => $request->Total,
            'TaxPer' => $request->Taxpercentage,
            'Tax' => $request->TaxpercentageAmount,
            'Shipping' => $request->Shipping,
            'GrandTotal' => $request->Grandtotal,
            'Paid' => $request->amountPaid,
            'Balance' => $request->amountDue,
            'CustomerNotes' => $request->CustomerNotes,
            'DescriptionNotes' => $request->DescriptionNotes,
            'UserID' => session::get('UserID'),
        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);

        $InvoiceMasterID = DB::table('invoice_master')->insertGetId($invoice_mst);
        // END OF SALE RETURN

        //  start for item array from invoice
        for ($i = 0; $i < count($request->ItemID); $i++) {
            $invoice_det = array(
                'InvoiceMasterID' =>  $InvoiceMasterID,
                'InvoiceNo' => $request->InvoiceNo,
                'ItemID' => $request->ItemID[$i],
                'SupplierID' => $request->input('SupplierID'),
                'Qty' => $request->Qty[$i],
                'Description' => $request->Description[$i],

                'Rate' => $request->Price[$i],
                'Total' => $request->ItemTotal[$i],

            );

            $id = DB::table('invoice_detail')->insertGetId($invoice_det);
        }

        // 1.
        // Debit
        $data_ar = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '110400',   //A/R
            'SupplierID' => $request->input('SupplierID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Dr' => $request->input('Total'),
            'Trace' => 22221, // for debugging for reverse engineering

            // 'SubTotal' => $request->input('SubTotal'),
            // 'TotalTax' => $request->input('TaxTotal'),
            // 'DiscountAmount' => $request->input('DiscountAmount'),
            // 'Total' => $request->input('Total'),
            // 'Paid' => $request->input('amountPaid'),
            // 'Balance' => $request->input('amountDue'),
        );

        $journal_entry = DB::table('journal')->insertGetId($data_ar);



        // 2. Purchase discount


        if ($request->input('DiscountAmount') > 0) {
            // if dis is given


            $data_saledis = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '410155',   //Sales-Discount
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('DiscountAmount'),
                'Trace' => 22222, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );


            $journal_entry = DB::table('journal')->insertGetId($data_saledis);
        }

        // 3. Tax -> VAT-INPUT TAX

        if ($request->input('TaxTotal') > 0) { // if tax item is present in invoice


            $data_vat_out = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '230100',   //VAT-OUTPUT TAX
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('TaxTotal'),
                'Trace' => 22224, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_vat_out);
        }

        // 4. total stock in hand

        $data_sale = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '410100',   //Sales
            'SupplierID' => $request->input('SupplierID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Dr' => $request->input('SubTotal') - $request->input('TaxTotal'),
            'Trace' => 22223, // for debugging for reverse engineering

            // 'SubTotal' => $request->input('SubTotal'),
            // 'TotalTax' => $request->input('TaxTotal'),
            // 'DiscountAmount' => $request->input('DiscountAmount'),
            // 'Total' => $request->input('Total'),
            // 'Paid' => $request->input('amountPaid'),
            // 'Balance' => $request->input('amountDue'),
        );

        $journal_entry = DB::table('journal')->insertGetId($data_sale);




        if ($request->input('amountPaid') > 0) {

            // 5. Cash/Bank ->Debit


            $data_cash_bank = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110201',   //bank / cash Debit
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
                'Trace' => 22225, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_cash_bank);


            // 5. Acc Payable  ->Debit

            $data_ar_credit = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110400',   //A/R credit
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
                'Trace' => 22226, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_ar_credit);
        }



        return redirect('DebitNote')->with('error', 'Invoice Saved')->with('class', 'success');
    }



    public function DebitNote()
    {
        return view('purchase.debitnote');
    }
    public function ajax_debitnote(Request $request)

    {

        session::put('menu', 'Bill');
        $pagetitle = 'Bill';
        if ($request->ajax()) {
            $data = DB::table('v_invoice_master_supplier')->orderBy('InvoiceMasterID')->where('InvoiceNo', 'like', 'DN%')->get();;

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>



                    $btn = '





                              <div class="d-flex align-items-center col-actions">


          <a href="' . URL('/DebitNoteView/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a>

          <a href="' . URL('/DebitNoteEdit/' . $row->InvoiceMasterID) . '"><i class="font-size-18 bx bx-pencil align-middle me-1 text-secondary"></i></a>

          <a href="' . URL('/DebitNoteDelete/' . $row->InvoiceMasterID) . '"><i class="font-size-18 bx bx-trash align-middle me-1 text-secondary"></i></a>

          <a href="' . URL('/DebitNoteViewPDF/' . $row->InvoiceMasterID) . '"><i class="font-size-18 me-1 mdi mdi-file-pdf-outline align-middle me-1 text-secondary"></i></a>








                       </div>


                       ';

                    //class="edit btn btn-primary btn-sm"
                    // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ebooks.debitnote', 'pagetitle');
    }


    public function DebitNoteView($id)
    {
        $pagetitle = 'Bill Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();

        $items = DB::table('item')->get();

        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master_supplier')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();

        session()->forget('VHNO');

        session::put('VHNO', $invoice_master[0]->InvoiceNo);
        $item = json_encode($items);
        // dd($item);
        $supplier = DB::table('supplier')->get();
        $user = DB::table('user')->get();
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(InvoiceMasterID),0)+1,5,0) as VHNO '))
            ->get();

        return view('purchase.debit_note_view', compact('invoice_type', 'items', 'vhno', 'supplier', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail', 'company'));
    }


    public  function DebitNoteViewPDF($id)
    {
        $pagetitle = 'Sales Invoice';


        session::put('menu', 'SalesInvoice');
        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master_supplier')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();

        $supplier = DB::table('supplier')->get();

        $pdf = PDF::loadView('purchase.debit_note_view_pdf', compact('company', 'pagetitle', 'invoice_master', 'invoice_detail', 'supplier'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }






    public function DebitNoteEdit($id)
    {
        $pagetitle = 'Debit Note';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();

        $items = DB::table('item')->get();

        $invoice_master = DB::table('v_invoice_master_supplier')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID', $id)->get();

        $item = json_encode($items);
        // dd($item);
        $supplier = DB::table('supplier')->get();
        $user = DB::table('user')->get();
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(InvoiceMasterID),0)+1,5,0) as VHNO '))
            ->get();


        session()->forget('VHNO');

        session::put('VHNO', $invoice_master[0]->InvoiceNo);


        return view('purchase.debit_note_edit', compact('invoice_type', 'items', 'vhno', 'supplier', 'pagetitle', 'item', 'user', 'invoice_master', 'invoice_detail'));
    }



    public function DebitNoteUpdate(Request $request)

    {

        // dd($request->all());
        $invoice_mst = array(
            'InvoiceNo' => $request->InvoiceNo,
            'InvoiceType' => $request->InvoiceType,
            'Date' => $request->Date,
            'DueDate' => $request->DueDate,
            'PartyID' => $request->PartyID,
            'WalkinCustomerName' => $request->WalkinCustomerName,
            'ReferenceNo' => $request->ReferenceNo,
            'PaymentMode' => $request->PaymentMode,
            'PaymentDetails' => $request->PaymentDetails,
            'Subject' => $request->Subject,
            'SubTotal' => $request->SubTotal,
            'DiscountPer' => $request->DiscountPer,
            'DiscountAmount' => $request->DiscountAmount,
            'Total' => $request->Total,
            'TaxPer' => $request->Taxpercentage,
            'Tax' => $request->TaxpercentageAmount,
            'Shipping' => $request->Shipping,
            'GrandTotal' => $request->Grandtotal,
            'Paid' => $request->amountPaid,
            'Balance' => $request->amountDue,
            'CustomerNotes' => $request->CustomerNotes,
            'DescriptionNotes' => $request->DescriptionNotes,
            'UserID' => session::get('UserID'),
        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);


        $InvoiceMasterID = $request->InvoiceMasterID;

        $InvoiceMst = DB::table('invoice_master')->where('InvoiceMasterID', $request->input('InvoiceMasterID'))->update($invoice_mst);


        $id = DB::table('invoice_detail')->where('InvoiceMasterID', $request->InvoiceMasterID)->delete();
        $id = DB::table('journal')->where('InvoiceMasterID', $request->InvoiceMasterID)->delete();

        // delete from journal too



        // END OF SALE RETURN

        //  start for item array from invoice
        for ($i = 0; $i < count($request->ItemID); $i++) {

            $invoice_det = array(
                'InvoiceMasterID' => $request->InvoiceMasterID,
                'InvoiceNo' => $request->InvoiceNo,
                'ItemID' => $request->ItemID[$i],
                'PartyID' => $request->input('PartyID'),
                'Qty' => $request->Qty[$i],
                'Rate' => $request->Price[$i],
                'Description' => $request->Description[$i],

                'Total' => $request->ItemTotal[$i],

            );

            $id = DB::table('invoice_detail')->insertGetId($invoice_det);
        }

        // end foreach
        // 1.
        // Debit
        $data_ar = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '110400',   //A/R
            'SupplierID' => $request->input('SupplierID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Dr' => $request->input('Total'),
            'Trace' => 22221, // for debugging for reverse engineering

            // 'SubTotal' => $request->input('SubTotal'),
            // 'TotalTax' => $request->input('TaxTotal'),
            // 'DiscountAmount' => $request->input('DiscountAmount'),
            // 'Total' => $request->input('Total'),
            // 'Paid' => $request->input('amountPaid'),
            // 'Balance' => $request->input('amountDue'),
        );

        $journal_entry = DB::table('journal')->insertGetId($data_ar);



        // 2. Purchase discount


        if ($request->input('DiscountAmount') > 0) {
            // if dis is given


            $data_saledis = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '410155',   //Sales-Discount
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('DiscountAmount'),
                'Trace' => 22222, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );


            $journal_entry = DB::table('journal')->insertGetId($data_saledis);
        }

        // 3. Tax -> VAT-INPUT TAX

        if ($request->input('TaxTotal') > 0) { // if tax item is present in invoice


            $data_vat_out = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '230100',   //VAT-OUTPUT TAX
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Cr' => $request->input('TaxTotal'),
                'Trace' => 22224, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_vat_out);
        }

        // 4. total stock in hand

        $data_sale = array(
            'VHNO' => $request->input('InvoiceNo'),
            'ChartOfAccountID' => '410100',   //Sales
            'SupplierID' => $request->input('SupplierID'),
            'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
            'Narration' => $request->input('Subject'),
            'Date' => $request->input('Date'),
            'Dr' => $request->input('SubTotal') - $request->input('TaxTotal'),
            'Trace' => 22223, // for debugging for reverse engineering

            // 'SubTotal' => $request->input('SubTotal'),
            // 'TotalTax' => $request->input('TaxTotal'),
            // 'DiscountAmount' => $request->input('DiscountAmount'),
            // 'Total' => $request->input('Total'),
            // 'Paid' => $request->input('amountPaid'),
            // 'Balance' => $request->input('amountDue'),
        );

        $journal_entry = DB::table('journal')->insertGetId($data_sale);




        if ($request->input('amountPaid') > 0) {

            // 5. Cash/Bank ->Debit


            $data_cash_bank = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110201',   //bank / cash Debit
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
                'Trace' => 22225, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_cash_bank);


            // 5. Acc Payable  ->Debit

            $data_ar_credit = array(
                'VHNO' => $request->input('InvoiceNo'),
                'ChartOfAccountID' => '110400',   //A/R credit
                'SupplierID' => $request->input('SupplierID'),
                'InvoiceMasterID' => $InvoiceMasterID, #7A7A7A
                'Narration' => $request->input('Subject'),
                'Date' => $request->input('Date'),
                'Dr' => $request->input('amountPaid'),
                'Trace' => 22226, // for debugging for reverse engineering

                // 'SubTotal' => $request->input('SubTotal'),
                // 'TotalTax' => $request->input('TaxTotal'),
                // 'DiscountAmount' => $request->input('DiscountAmount'),
                // 'Total' => $request->input('Total'),
                // 'Paid' => $request->input('amountPaid'),
                // 'Balance' => $request->input('amountDue'),
            );

            $journal_entry = DB::table('journal')->insertGetId($data_ar_credit);
        }

        return redirect('DebitNote')->with('error', 'Invoice Saved')->with('class', 'success');
    }

    public function DebitNoteDelete($id)
    {


        $idd = DB::table('invoice_master')->where('InvoiceMasterID', $id)->delete();
        $id1 = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->delete();
        $id2 = DB::table('journal')->where('InvoiceMasterID', $id)->delete();


        // delete from journal too

        return redirect('DebitNote')->with('error', 'Deleted Successfully')->with('class', 'danger');
    }

    // purchase payment

    public function PurchasePayment()
    {
        $pagetitle = 'Payment';


        return view('ebooks.purchase_payment', compact('pagetitle'));
    }
    public function ajax_purchasepaymenttable(Request $request)
    // ajax datatable for purchase payment
    {
        session::put('menu', 'Vouchers');
        $pagetitle = 'Purchase Payment';
        if ($request->ajax()) {
            $data = DB::table('v_purchase_payment')->orderBy('PurchasePaymentMasterID')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>
                    $btn = '

                       <div class="d-flex align-items-center col-actions">



<a href="' . URL('/PurchasePaymentView/' . $row->PurchasePaymentMasterID) . '"><i class="font-size-18 mdi mdi-eye align-middle me-1 text-secondary"></i></a>

<a href="' . URL('/PurchasePaymentDelete/' . $row->PurchasePaymentMasterID) . '"><i class="font-size-18 mdi mdi-trash-can-outline align-middle me-1 text-secondary"></i></a>




                       </div>';

                    //class="edit btn btn-primary btn-sm"
                    // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ebooks.payment', 'pagetitle');
    }


    public function PurchasePaymentView($id)
    {
        $pagetitle = 'Bill Payment Made';
        $company = DB::table('company')->get();

        $payment_master = DB::table('v_purchase_payment_master')->where('PurchasePaymentMasterID', $id)->get();
        // $payment_summary = DB::table('v_payment_summary')
        // ->where('PaymentMasterID',$id)->get();

        $payment_detail = DB::table('v_purchase_payment_detail')->where('PurchasePaymentMasterID', $id)->get();

        return view('ebooks.purchase_payment_view_pdf', compact('pagetitle', 'company', 'payment_master', 'payment_detail'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }



    public function PaymentViewPDF($id)
    {
        $pagetitle = 'Payment Made';
        $company = DB::table('company')->get();

        $payment_master = DB::table('v_payment')->where('PaymentMasterID', $id)->get();
        $payment_summary = DB::table('v_payment_summary')
            ->where('PaymentMasterID', $id)->get();

        $v_payment_detail = DB::table('v_payment_detail')->where('PaymentMasterID', $id)->get();

        return view('ebooks.payment_view_pdf', compact('payment_summary', 'pagetitle', 'company', 'payment_master', 'v_payment_detail'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }


    public function PurchasePaymentCreate()
    {






        $pagetitle = 'Create Payment';
        $supplier = DB::table('supplier')->get();

        $chartofacc = DB::table('chartofaccount')->get();
        $payment_mode = DB::table('payment_mode')->get();
        $purchasepayment = DB::table('purchasepayment_master')->select(DB::raw('ifnull(max(PurchasePaymentMasterID)+1,1) as PurchasePaymentMasterID'))->get();

        return view('ebooks.purchase_payment_create', compact('chartofacc', 'supplier', 'pagetitle', 'purchasepayment', 'payment_mode'));
    }

    public  function Ajax_SupplierInvoices(request $request)
    {



        $data = array('SupplierID' => $request->input('SupplierID'));


        $payment_mode = DB::table('payment_mode')->get();

        $invoice_balance = DB::table('v_supplier_outstanding')->where('SupplierID', $request->SupplierID)->where('BALANCE', '>', 0)->get();
        $invoice_party_balance = DB::table('v_supplier_over_balance')->where('SupplierID', $request->SupplierID)->get();


        return view('ebooks.ajax_suppplier_invoice', compact('payment_mode', 'invoice_balance', 'invoice_party_balance'));

        // return response()->json(['success'=>'Got Simple Ajax Request.']);

    }



    public  function km($id)
    {



        // $data = array('SupplierID' => $request->input('SupplierID') );


        $payment_mode = DB::table('payment_mode')->get();

        $invoice_balance = DB::table('v_supplier_outstanding')->where('SupplierID', $id)->where('BALANCE', '>', 0)->get();



        $invoice_party_balance = DB::table('v_supplier_over_balance')->where('SupplierID', $id)->get();


        return view('ebooks.ajax_suppplier_invoice', compact('payment_mode', 'invoice_balance', 'invoice_party_balance'));

        // return response()->json(['success'=>'Got Simple Ajax Request.']);

    }




    public function PurchasePaymentSave(request $request)
    {


        // dd($request->all());
        // $employee = DB::table('employee')->get();
        // return view ('');


        if ($request->hasfile('UploadSlip')) {

            $this->validate($request, [

                // 'file' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,pdf|max:1000',
                'UploadSlip' => 'image|mimes:jpeg,png,jpg,gif,pdf,doc,docx',

            ]);




            $file = $request->file('UploadSlip');
            $input['filename'] = time() . '.' . $file->extension();

            $destinationPath = public_path('/uploads');

            $file->move($destinationPath, $input['filename']);

            // $destinationPath = public_path('/images');
            // $image->move($destinationPath, $input['imagename']);

            // $input['filename']===========is final data in it.


            $payment_master = array(
                'PurchasePaymentMasterID' => $request->PaymentMasterID,
                'SupplierID' => $request->SupplierID,
                'PaymentAmount' => $request->PaymentAmount,
                'PaymentDate' => $request->PaymentDate,
                'PaymentMode' => $request->PaymentMode,
                'ChartOfAccountID' => $request->ChartOfAccountID,
                'ReferenceNo' => $request->ReferenceNo,
                'Notes' => $request->Notes,
                'File' => $input['filename'],
            );
        } else {

            $payment_master = array(
                'PurchasePaymentMasterID' => $request->PaymentMasterID,
                'SupplierID' => $request->SupplierID,
                'PaymentAmount' => $request->PaymentAmount,
                'PaymentDate' => $request->PaymentDate,
                'PaymentMode' => $request->PaymentMode,
                'ChartOfAccountID' => $request->ChartOfAccountID,
                'ReferenceNo' => $request->ReferenceNo,
                'Notes' => $request->Notes,
            );
        }
        // end of if else used for to  check file uploaded or not

        $paymentmasterid = DB::table('purchasepayment_master')->insertGetId($payment_master);



        $paymentmode = DB::table('chartofaccount')->where('ChartOfAccountID', $request->PaymentMode)->get();

        if ($paymentmode[0]->Category == 'CASH') {
            $JournalType = 'CP';
        } elseif ($paymentmode[0]->Category == 'CARD') {
            $JournalType = 'CC';
        } else {
            $JournalType = 'BP';
        }




        //  start for item array from invoice
        for ($i = 0; $i < count($request->InvoiceMasterID); $i++) {


            if ($request->Amount[$i] > 0) {


                $payment_detail = array(
                    'PurchasePaymentMasterID' => $paymentmasterid,
                    'PaymentDate' => $request->PaymentDate,
                    'InvoiceMasterID' => $request->InvoiceMasterID[$i],
                    'Payment' => $request->Amount[$i],


                );



                $id = DB::table('purchasepayment_detail')->insertGetId($payment_detail);


                // payment of invoice

                // payment received


                // A/C PAYABLE
                $ac_payable = array(
                    'VHNO' => 'BILLPAY-' . $paymentmasterid,
                    'ChartOfAccountID' => 210100,  // A/C PAYABLE
                    'JournalType' => $JournalType,
                    'SupplierID' => $request->input('SupplierID'),
                    'InvoiceMasterID' => $request->InvoiceMasterID[$i],
                    'PurchasePaymentMasterID' => $paymentmasterid,
                    'Date' => $request->input('PaymentDate'),
                    'Dr' => $request->Amount[$i],
                    'Narration' => 'Payment made with payment refno ' . $request->PaymentMasterID . '',
                    'Trace' => 515
                );

                $journal_entry = DB::table('journal')->insertGetId($ac_payable);
                // end payment received


                //bank debit
                $bank_cash = array(
                    'VHNO' => 'BILLPAY-' . $paymentmasterid,
                    'ChartOfAccountID' => $request->PaymentMode,   // Cash / bank / credit card
                    'JournalType' => $JournalType,
                    'SupplierID' => $request->input('SupplierID'),
                    'InvoiceMasterID' => $request->InvoiceMasterID[$i],
                    'PurchasePaymentMasterID' => $paymentmasterid,
                    'Date' => $request->input('PaymentDate'),
                    'Cr' => $request->Amount[$i],
                    'Narration' => 'Payment made with payment refno ' . $request->PaymentMasterID . '',
                    'Trace' => 514
                );
                $journal_entry1 = DB::table('journal')->insertGetId($bank_cash);
                // end of invoice payment





            } // end of if; if amount is > 0
        }



        return redirect('PurchasePayment')->with('error', 'Saved Successfully')->with('class', 'success');
    }



    public function PurchasePaymentDelete($id)
    {


        $id1 = DB::table('purchasepayment_master')->where('PurchasePaymentMasterID', $id)->delete();

        $id2 = DB::table('purchasepayment_detail')->where('PurchasePaymentMasterID', $id)->delete();
        $id3 = DB::table('journal')->where('VHNO', 'BILLPAY-' . $id)->delete();

        return redirect('PurchasePayment')->with('error', 'Deleted Successfully')->with('class', 'success');
    }


    public function PurchaseOrder()
    {


$pagetitle='Purchase Order';
        return view('po.purchase_order',compact('pagetitle'));
    }


    public function ajax_purchaseorder(Request $request)

    {

        session::put('menu', 'Purchase Order');
        $pagetitle = 'Purchase Order';
        if ($request->ajax()) {
            $data = DB::table('v_invoice_master_supplier')->where('InvoiceType','PO')->orderBy('Date','Desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>



     

          // <a href="' . URL('/PurchaseOrderView/' . $row->InvoiceMasterID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a>

                    $btn = '





            <div class="d-flex align-items-center col-actions">

          <a href="' . URL('/DNCreateAuto/' . $row->InvoiceMasterID) . '"><span class="font-size-14 text-secondary me-1">DN</span></a>


         
          <a href="' . URL('/PurchaseOrderEdit/' . $row->InvoiceMasterID) . '"><i class="font-size-18 bx bx-pencil align-middle me-1 text-secondary"></i></a>

          <a href="' . URL('/PurchaseOrderDelete/' . $row->InvoiceMasterID) . '"><i class="font-size-18 bx bx-trash align-middle me-1 text-secondary"></i></a>
          <a href="' . URL('/PurchaseOrderViewPDF/' . $row->InvoiceMasterID) .'/'.$row->BranchID. '"><i class="font-size-18 me-1 mdi mdi-file-pdf-outline align-middle me-1 text-secondary"></i></a>

                       </div>


                       ';

                    //class="edit btn btn-primary btn-sm"
                    // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('po.purchaseorder', 'pagetitle');
    }




    public function PurchaseOrderCreate()
    {

        $pagetitle = 'Purchase Order';
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))
            ->where('InvoiceType','PO')->get();

 
 
        $tax = DB::table('tax')->where('Section', 'SaleOrder')->get();
        $supplier = DB::table('supplier')->get();
        $items = DB::table('item')->get();
        $user = DB::table('user')->get();
        $job = DB::table('job')->get();
        $unit = DB::table('unit')->get();
        $branch = DB::table('branch')->get();

        $item = json_encode($items);

        return view('po.purchase_order_create', compact('vhno', 'supplier', 'user', 'items', 'item', 'tax', 'pagetitle','job','unit','branch'));
    }


    public function PurchaseOrderSave(Request $request)
    {

     $invoice_mst = array(
            'InvoiceNo' => $request->input('InvoiceNo'),
            'BranchID' => $request->input('BranchID'),
            'SupplierID' => $request->input('SupplierID'),
            'JobID' => $request->input('JobID'),
             'ReferenceNo' => $request->input('ReferenceNo'),
            'InvoiceType' => 'PO',
            'Date' => $request->input('Date'),
            'DueDate' => $request->input('DueDate'),
             'SubTotal' => $request->input('SubTotal'),
            'DiscountAmount' => $request->input('DiscountAmount'),
            'DiscountPer' => $request->input('DiscountPer'),

            'TaxType' => $request->input('TaxType'),
            'TaxPer' => $request->input('grandtotaltax') == 0 ? 0 : 5,
            'Tax' => $request->input('grandtotaltax'),
            'Shipping' => $request->input('Shipping'),
            'Total' => $request->input('Total'),
            'GrandTotal' => $request->input('Grandtotal'),
            'CustomerNotes' => $request->input('CustomerNotes'),
            'DescriptionNotes' => $request->input('DescriptionNotes'),
            'UserID' => Session::get('UserID'),
 
        );
        $InvoiceMasterID = DB::table('invoice_master')->insertGetId($invoice_mst);
        // END OF SALE RETURN
        //  start for item array from invoice
        for ($i = 0; $i < count($request->ItemID); $i++) {
            $invoice_det = array(
                'InvoiceMasterID' =>  $InvoiceMasterID,
                'InvoiceNo' => $request->input('InvoiceNo'),
                'Date' => $request->input('Date'),
                'ItemID' => $request->ItemID[$i],
                'Description' => $request->Description[$i],
                 'TaxPer' => $request->Tax[$i],
                 'Tax' => $request->TaxVal[$i],
                'LS' => $request->LS[$i],
                'Qty' => $request->Qty[$i],
                'Rate' => $request->Price[$i],
                'Total' => $request->ItemTotal[$i],
                // 'Discount' => $request->Discount[$i],
                // 'DiscountType' => $request->DiscountType[$i],
                'Gross' => $request->Gross[$i],
                // 'DiscountAmountItem' => $request->DiscountAmountItem[$i],

                 'TaxPer' => $request->Tax[$i],
                 'Tax' => $request->TaxVal[$i],


                'UnitName' => $request->UnitName[$i],
                'UnitQty' => $request->UnitQty[$i],
            );

            $id = DB::table('invoice_detail')->insertGetId($invoice_det);
        }

        return redirect('PurchaseOrder')->with('error', 'created success')->with('class', 'success');
    }


    function PurchaseOrderEdit($id)
    {   

        
         // Get the invoice details
         $invoice_mst = DB::table('invoice_master')->where('InvoiceMasterID', $id)->first();
        
         // Check if invoice exists
         if (!$invoice_mst) {
             return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
         }
 
         // Check if invoice belongs to a previous year
         $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
          $current_year = date('Y');
 
         if ($invoice_year < $current_year) {
             return redirect()->back()->with('error', 'You cannot edit an invoice from a previous year.')->with('class', 'danger');
         }


        $pagetitle = 'Purchase Order';
        $supplier = DB::table('supplier')->get();
        $user = DB::table('user')->get();
        $invoice_master = DB::table('invoice_master')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('invoice_detail')->where('InvoiceMasterID', $id)->get();
        $items = DB::table('item')->get();
        $tax = DB::table('tax')->where('Section', 'SaleOrder')->get();
        $item = json_encode($items);
        
        $job = DB::table('job')->get();
        $unit = DB::table('unit')->get();
        $branch = DB::table('branch')->get();





        return view('po.purchase_order_edit', compact('supplier', 'user', 'invoice_master', 'invoice_detail', 'items', 'tax', 'pagetitle', 'item','job','unit','branch'));
    }

    function PurchaseOrderUpdate(Request $request)
    {

        // dd($request->all());
        $po_master = array(
           'InvoiceNo' => $request->input('InvoiceNo'),
            'BranchID' => $request->input('BranchID'),
            'SupplierID' => $request->input('SupplierID'),
            'JobID' => $request->input('JobID'),
             'ReferenceNo' => $request->input('ReferenceNo'),
            'InvoiceType' => 'PO',
            'Date' => $request->input('Date'),
            'DueDate' => $request->input('DueDate'),
             'SubTotal' => $request->input('SubTotal'),
            'DiscountAmount' => $request->input('DiscountAmount'),
            'DiscountPer' => $request->input('DiscountPer'),

            'TaxType' => $request->input('TaxType'),
            'TaxPer' => $request->input('grandtotaltax') == 0 ? 0 : 5,
            'Tax' => $request->input('grandtotaltax'),
            'Shipping' => $request->input('Shipping'),
            'Total' => $request->input('Total'),
            'GrandTotal' => $request->input('Grandtotal'),
            'CustomerNotes' => $request->input('CustomerNotes'),
            'DescriptionNotes' => $request->input('DescriptionNotes'),
            'UserID' => Session::get('UserID'),

        );

        //delete previous


        
        $id22= DB::table('invoice_master')->where('InvoiceMasterID' , $request->InvoiceMasterID)->update($po_master);
        
         $id2 = DB::table('invoice_detail')->where('InvoiceMasterID', $request->InvoiceMasterID)->delete();
        ////////

        

        for ($i = 0; $i < count($request->ItemID); $i++) {
            $purchase_det = array(
           'InvoiceMasterID' =>  $request->InvoiceMasterID,
                'InvoiceNo' => $request->input('InvoiceNo'),
                'Date' => $request->input('Date'),
                'ItemID' => $request->ItemID[$i],
                'Description' => $request->Description[$i],
                 'TaxPer' => $request->Tax[$i],
                 'Tax' => $request->TaxVal[$i],
                'LS' => $request->LS[$i],
                'Qty' => $request->Qty[$i],
                'Rate' => $request->Price[$i],
                'Total' => $request->ItemTotal[$i],
                // 'Discount' => $request->Discount[$i],
                // 'DiscountType' => $request->DiscountType[$i],
                'Gross' => $request->Gross[$i],
                // 'DiscountAmountItem' => $request->DiscountAmountItem[$i],

                 'TaxPer' => $request->Tax[$i],
                 'Tax' => $request->TaxVal[$i],


                'UnitName' => $request->UnitName[$i],
                'UnitQty' => $request->UnitQty[$i],
            );


            $id = DB::table('invoice_detail')->insertGetId($purchase_det);
        }
        return redirect('PurchaseOrder')->with('error', 'P.O Updated')->with('class', 'success');
    }


    function PurchaseOrderView($PurchaseMasterid)
    {
        $user = DB::table('user')->get();
        $supplier = DB::table('supplier')->get();
        $invoice_master = DB::table('v_invoice_master_supplier')->where('InvoiceMasterID', $PurchaseMasterid)->get();
        $invoice_detail = DB::table('v_invoice_detail_supplier')->where('InvoiceMasterID', $PurchaseMasterid)->get();

 
 
        $company = DB::table('company')->get();
        return view('po.purchase_order_view', compact('invoice_master',  'invoice_detail', 'company'));
    }


    public function PurchaseOrderViewPDF($id)
    {
        $pagetitle = 'Purchase Order';

        $invoice_master = DB::table('v_invoice_master_supplier')->where('InvoiceMasterID', $id)->get();
        $invoice_detail = DB::table('v_invoice_detail_supplier')->where('InvoiceMasterID', $id)->get();
        $company = DB::table('company')->get();


 
        $pdf = PDF::loadView('po.purchase_order_view_pdf', compact('invoice_master', 'pagetitle', 'company', 'invoice_detail', 'company'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }





    function PurchaseOrderDelete($PurchaseMasterid)
    {


        // Get the invoice details
        $invoice_mst = DB::table('purchase_order_master')->where('PurchaseOrderMasterID', $PurchaseMasterid)->first();
        
        // Check if invoice exists
        if (!$invoice_mst) {
            return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
        }

        // Check if invoice belongs to a previous year
        $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
         $current_year = date('Y');

        if ($invoice_year < $current_year) {
            return redirect()->back()->with('error', 'You cannot edit an invoice from a previous year.')->with('class', 'danger');
        }


        $id = DB::table('purchase_order_master')->where('PurchaseOrderMasterID', $PurchaseMasterid)->delete();
        $id2 = DB::table('purchase_order_detail')->where('PurchaseOrderMasterID', $PurchaseMasterid)->delete();


        return redirect('PurchaseOrder')->with('error', 'Purchase Order Deleted')->with('class', 'success');
    }





    function Attachment()
    {
        return view('attachment');
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
        return redirect('Attachment')->with('error', 'File Deleted')->with('class', 'success');
    }

    public function DailyIncomeExpense()
    {


        $pagetitle = 'Daily Income / Expense';
        return view('daily_income_expense', compact('pagetitle'));
    }

    public  function DailyIncomeExpense1PDF(request $request)
    {

        $company = DB::table('company')->get();


        $sql = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
            // ->where('SupplierID',$request->SupplierID)
            ->where('Category', 'CASH')
            ->where('Date', '<', $request->StartDate)
            // ->whereBetween('date',array($request->StartDate,$request->EndDate))
            ->get();
        $journal_income = DB::table('v_journal')
            // ->where('SupplierID',$request->SupplierID)
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->where('Category', 'CASH')
            ->where('Dr', '<>', '')
            ->orderBy('Date', 'asc')
            ->get();

        $journal_expense = DB::table('v_journal')
            // ->where('SupplierID',$request->SupplierID)
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->where('Category', 'CASH')
            ->where('Cr', '<>', '')
            ->orderBy('Date', 'asc')
            ->get();

        $journal_summary = DB::table('v_journal')
            ->select(DB::raw('sum(if(ISNULL(Dr),0,Dr)) as Dr'), DB::raw('sum(if(ISNULL(Cr),0,Cr)) as Cr'))
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->where('Category', 'CASH')
            ->get();


        $sql[0]->Balance = ($sql[0]->Balance == null) ? '0' :  $sql[0]->Balance;





        $pdf = PDF::loadView('daily_income_expense1', compact('company', 'sql', 'journal_income', 'journal_expense'));
        $pdf->setpaper('A4', 'portiate');
        // return $pdf->download($filename.'.pdf');
        return $pdf->stream();
    }


    public function kashif()
    {

        return view('kashif');
    }


    public function kupload()
    {

        // Upload directory
        $target_dir = "documents/";

        $request = 2;


        $attachment = DB::table('attachment')->where('InvoiceNo', 'INV-00008')->get();

        $file_list = array();

        // Target directory
        $dir = $target_dir;
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {




                foreach ($attachment as $key => $value) {
                    // File path



                    if ($value->FileName != '' && $value->FileName != '.' && $value->FileName != '..') {


                        $file_path = $target_dir . $value->FileName;

                        if (!is_dir($file_path)) {

                            $size = filesize($file_path);

                            $file_list[] = array('name' => $value->FileName, 'size' => $size, 'path' => $file_path);
                        }
                    }
                }
                echo json_encode($file_list);
                exit;
            }
        }
    }


    public function ReconcileReport()
    {
        session::put('menu', 'GeneralLedger');
        $pagetitle = 'General Ledger';
        $chartofaccount = DB::table('chartofaccount')
            ->whereIn('Category', ['CARD','CASH','BANK'])
            ->get();
        return view('reconcile_report', compact('pagetitle', 'chartofaccount'));
    }
    public function ReconcileReport1(request $request)
    {
        ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
        $allow = check_role(session::get('UserID'), 'General Ledger', 'View');
        if ($allow == 0) {
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


        return view('reconcile_report1', compact('journal', 'pagetitle', 'sql', 'journal_summary'));
    }


    public function ReconcileUpdate($status, $id)
    {


        $data = array('BankReconcile' => $status);



        $id = DB::table('journal')->where('JournalID', $id)->update($data);


        return 'Update Done';
    }


    //usa

    // ===============Expense Section function==================
    public  function ExpenseCreate()
    {
        $pagetitle = 'Expense';

        session::put('menu', 'Expense');
        $chartofaccont = DB::table('chartofaccount')->whereIn('Category', ['CASH', 'CARD', 'BANK'])->get();
        $items = DB::table('chartofaccount')->where('Level', '3')->get();
        // $items = DB::table('chartofaccount')->where(DB::raw('right(L3,3)'),'<>',000)->get();

        $item = json_encode($items);
        // dd($item);
        $supplier = DB::table('supplier')->get();
        $tax = DB::table('tax')->get();
        $job = DB::table('v_job')->get();

        // $tax = DB::table('tax')->get();
        $user = DB::table('user')->get();
        $invoice_type = DB::table('invoice_type')->get();


        // $vhno = DB::table('expense_master')
        //     ->select(DB::raw('LPAD(IFNULL(MAX(right(ExpenseNo,5)),0)+1,5,0) as VHNO '))->whereIn(DB::raw('left(ExpenseNo,3)'), ['EXP'])->get();


        // session::put('VHNO', 'EXP-' . $vhno[0]->VHNO);


                $vhno = DB::table('expense_master')
    ->select(DB::raw("
        IFNULL(MAX(CAST(SUBSTRING(ExpenseNo, 5) AS UNSIGNED)), 0) + 1 AS VHNO
    "))
    ->where('ExpenseNo', 'like', 'EXP-%')
    ->first();


$newExpenseNo = 'EXP-' . str_pad($vhno->VHNO, 5, '0', STR_PAD_LEFT);

Session::put('VHNO', $newExpenseNo);




        return view('expense.expense_create', compact('invoice_type', 'chartofaccont', 'tax', 'items', 'vhno', 'supplier', 'pagetitle', 'item', 'user','job'));
    }


    public function ExpenseSave(Request $request)


    {


        session::put('menu', 'Expense');
        $pagetitle = 'Invoice';

        $expense_mst = array(
            'ExpenseNo' => $request->ExpenseNo,
            'Date' => $request->Date,
            'JobID' => $request->JobID,
            'ChartOfAccountID' => $request->ChartOfAccountID_From,
            'JobID' => $request->JobID,
            'SupplierID' => $request->SupplierID,
            'ReferenceNo' => $request->ReferenceNo,
            'Tax' => $request->grandtotaltax,
            'GrantTotal' => $request->Grandtotal,
            'Paid' => $request->amountPaid,
        );
        // dd($challan_mst);
        // $id= DB::table('')->insertGetId($data);

        $ExpenseMasterID = DB::table('expense_master')->insertGetId($expense_mst);
        // dd($InvoiceMasterID);


        // JOURNAL ENTRY
        //bank debit
        $bank_cash = array(
            'VHNO' => $request->ExpenseNo,
            'ChartOfAccountID' => $request->ChartOfAccountID_From,   // Cash / bank / credit card
            'SupplierID' => $request->input('SupplierID'),
            'ExpenseMasterID' => $ExpenseMasterID,

            'Date' => $request->input('Date'),
            'JobID' => $request->JobID,
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
                'JobID' => $request->JobID,
                'Amount' => $request->ItemTotal[$i],

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
                    'JobID' => $request->JobID,
                    'Dr' => $request->ItemTotal[$i] - $request->TaxVal[$i],
                    'Narration' => $request->Description[$i],
                    'Trace' => 615
                );

                $journal_entry1 = DB::table('journal')->insertGetId($ar_payment);



                //tax grandtotaltax

                // Tax Payable debit
                $ar_payment = array(
                    'VHNO' => $request->ExpenseNo,
                    'ChartOfAccountID' => 210300,  // TAX PAYABLES
                    'SupplierID' => $request->input('SupplierID'),
                    'ExpenseMasterID' => $ExpenseMasterID,
                    'JobID' => $request->JobID,
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
                    'JobID' => $request->JobID,
                    'Date' => $request->input('Date'),
                    'Dr' => $request->ItemTotal[$i],
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
            $data = DB::table('v_expense')->orderBy('Date', 'desc')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // if you want to use direct link instead of dropdown use this line below
                    // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>


//  <a href="' . URL('/ExpenseViewPDF/' . $row->ExpenseMasterID) . '"><i class="font-size-18 me-1 mdi mdi-file-pdf-outline align-middle me-1 text-secondary"></i></a>


                    $btn = '



            <div class="d-flex align-items-center col-actions">


            <a href="' . URL('/ExpenseView/' . $row->ExpenseMasterID) . '"><i class="font-size-18 mdi mdi-eye-outline align-middle me-1 text-secondary"></i></a>
            
            <a href="' . URL('/ExpenseEdit/' . $row->ExpenseMasterID) . '"><i class="font-size-18 bx bx-pencil align-middle me-1 text-secondary"></i></a>
            <a href="' . URL('/ExpenseDelete/' . $row->ExpenseMasterID) . '"><i class="font-size-18 bx bx-trash align-middle me-1 text-danger"></i></a>




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



        // Get the invoice details
        $invoice_mst = DB::table('expense_master')->where('ExpenseMasterID', $id)->first();
        
        // Check if invoice exists
        if (!$invoice_mst) {
            return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
        }

        // Check if invoice belongs to a previous year
        $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
         $current_year = date('Y');

        // if ($invoice_year < $current_year) {
        //     return redirect()->back()->with('error', 'You cannot edit an invoice from a previous year.')->with('class', 'danger');
        // }




        $pagetitle = 'Expense';

        session::put('menu', 'Expense');

        $chartofaccount = DB::table('chartofaccount')->get();
        // $chartofaccount = DB::table('chartofaccount')->where(DB::raw('right(L3,3)'),'<>',000)->get();


        $supplier = DB::table('supplier')->get();
        $tax = DB::table('tax')->get();

        // $tax = DB::table('tax')->get();
        $user = DB::table('user')->get();

        $job = DB::table('v_job')->get();


        $expense_master = DB::table('expense_master')->where('ExpenseMasterID', $id)->get();


        session::put('VHNO', $expense_master[0]->ExpenseNo);


        $expense_detail = DB::table('expense_detail')->where('ExpenseMasterID', $id)->get();



        return view('expense.expense_edit', compact('tax', 'supplier', 'pagetitle', 'expense_master', 'chartofaccount', 'expense_detail','job'));
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
            'JobID' => $request->JobID,
            'ReferenceNo' => $request->ReferenceNo,
            'Tax' => $request->grandtotaltax,
            'GrantTotal' => $request->Grandtotal,
            'GrantTotal' => $request->Grandtotal,
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
            'JobID' => $request->JobID,

            'Date' => $request->input('Date'),

            'Cr' => $request->Grandtotal,
            'Narration' => $request->ReferenceNo,
            'Trace' => 614
        );
        $journal_entry = DB::table('journal')->insertGetId($bank_cash);





        //  start for item array from invoice
        for ($i = 0; $i < count($request->ChartOfAccountID); $i++) {
            $expense_detail = array(
                'ExpenseMasterID' =>  $request->ExpenseMasterID,
                'ChartOfAccountID' => $request->ChartOfAccountID[$i],
                'JobID' => $request->JobID,
                'Notes' => $request->Description[$i],
                'TaxPer' => $request->Tax[$i],
                'Tax' => $request->TaxVal[$i],
                'Amount' => $request->ItemTotal[$i],

            );

            $id = DB::table('expense_detail')->insertGetId($expense_detail);


            if ($request->Tax[$i] > 0) {




                // A/P debit
                $ar_payment = array(
                    'VHNO' => $request->ExpenseNo,
                    'ChartOfAccountID' => $request->ChartOfAccountID[$i],  // chart of account direct debit
                    'SupplierID' => $request->input('SupplierID'),
                    'JobID' => $request->JobID,
                    'ExpenseMasterID' => $request->ExpenseMasterID,
                    'Date' => $request->input('Date'),
                    'Dr' => $request->ItemTotal[$i] - $request->TaxVal[$i],
                    'Narration' => $request->Description[$i],
                    'Trace' => 615
                );

                $journal_entry1 = DB::table('journal')->insertGetId($ar_payment);



                //tax grandtotaltax

                // Tax Payable debit
                $ar_payment = array(
                    'VHNO' => $request->ExpenseNo,
                    'ChartOfAccountID' => 210300,  // TAX PAYABLES
                    'SupplierID' => $request->input('SupplierID'),
                    'JobID' => $request->JobID,
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
                    'JobID' => $request->JobID,
                    'ExpenseMasterID' => $request->ExpenseMasterID,
                    'Date' => $request->input('Date'),
                    'Dr' => $request->ItemTotal[$i],
                    'Narration' => $request->Description[$i],
                    'Trace' => 615
                );

                $journal_entry1 = DB::table('journal')->insertGetId($ar_payment);
            }
        }
        return view('expense.expense', compact('pagetitle'));
    }




    public function ExpenseView($id)
    {

        $pagetitle = 'Expense View ';
        $company = DB::table('company')->get();
        $expense_master = DB::table('v_expense')->where('ExpenseMasterID', $id)->get();
        $expense_detail = DB::table('v_expense_detail')->where('ExpenseMasterID', $id)->get();
        $journal = DB::table('journal')->where('ExpenseMasterID', $id)->get();


        return view('expense.expense_view', compact('expense_master', 'expense_detail', 'pagetitle', 'company'));
    }


    public function ExpenseDelete($id)
    {

         // Get the invoice details
         $invoice_mst = DB::table('expense_master')->where('ExpenseMasterID', $id)->first();
        
         // Check if invoice exists
         if (!$invoice_mst) {
             return redirect()->back()->with('error', 'Invoice not found.')->with('class', 'danger');
         }
 
         // Check if invoice belongs to a previous year
         $invoice_year = date('Y', strtotime($invoice_mst->Date)); // Assuming 'date' is the invoice date field
          $current_year = date('Y');
 
         if ($invoice_year < $current_year) {
             return redirect()->back()->with('error', 'You cannot delete an invoice from a previous year.')->with('class', 'danger');
         }
 


        $id = DB::table('expense_master')->where('ExpenseMasterID', $id)->delete();
        $id2 = DB::table('expense_detail')->where('ExpenseMasterID', $id)->delete();
        $id3 = DB::table('journal')->where('ExpenseMasterID', $id)->delete();


        return redirect('Expense')->with('error', 'Deleted Successfully')->with('class', 'success');
    }


    public function TaxReportSupplier()
    {
        $pagetitle = 'Supplier Tax ';
        return view('tax_report_supplier', compact('pagetitle'));
    }

    public function TaxReportSupplier1(request $request)
    {
        $pagetitle = 'Supplier Tax ';

        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master_supplier')
            ->where('InvoiceNo', 'like', 'BIL%')
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->get();



        return view('tax_report_supplier1', compact('invoice_master', 'pagetitle', 'company'));
    }



    public function TaxReportSupplier1PDF(request $request)
    {
        $pagetitle = 'Supplier Tax ';

        $company = DB::table('company')->get();
        $invoice_master = DB::table('v_invoice_master_supplier')
            ->where('InvoiceNo', 'like', 'BIL%')
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->get();

        $pdf = PDF::loadView('tax_report_supplier1PDF', compact('invoice_master', 'pagetitle', 'company'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }

    // item import
    function ItemImport(Request $request)
    {
        $title = "Import Spreadsheet";
        $template = url('documents/template-users.xlsx');
        if ($_POST) {
            $request->validate([
                'file1' => 'required|mimes:xlsx|max:10000'
            ]);
            $file = $request->file('file1');
            $name = time() . '.xlsx';
            $path = public_path('documents' . DIRECTORY_SEPARATOR);

            if ($file->move($path, $name)) {
                $inputFileName = $path . $name;
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $reader->setReadDataOnly(true);
                $reader->setLoadSheetsOnly(["USER DATA"]);
                $spreadSheet = $reader->load($inputFileName);
                $workSheet = $spreadSheet->getActiveSheet();
                $startRow = 2;
                $max = 3000;
                $columns = [
                    "A" => "id",
                    "B" => "name",
                    "C" => "email",
                    "D" => "address"
                ];

                $data_insert = [];
                $id = $workSheet->getCell("A2")->getValue();
                dd($id);
                for ($i = $startRow; $i < $max; $i++) {
                    $id = $workSheet->getCell("A2")->getValue();

                    if (empty($id) || !is_numeric($id)) continue;

                    $data_row = [];
                    foreach ($columns as $col => $field) {
                        $val = $workSheet->getCell("$col$i")->getValue();
                        $data_row[$field] = $val;
                    }
                    $data_insert[] = $data_row;
                }
                //DB::table('users')->truncate();


                DB::table('users')->insert($data_insert);

                return redirect('Item')->with('success', 'Data imported successfully!');
            }
        }

        return view("spreadsheet.import", compact("title", "template"));
    }


    public function BulkPaymentCreate()
    {
        $pagetitle = 'Bulk Payment';
        $party = DB::table('party')->get();
        return view('bulk_payment_create', compact('party', 'pagetitle'));
    }

    public function BulkPaymentSearch(request $request)
    {
        $pagetitle = 'Bulk Payment Search';
        $party = DB::table('party')->where('PartyID', $request->PartyID)->get();
        $invoice_master = DB::table('v_invoice_master')
            ->whereIn(DB::raw('left(InvoiceNo,3)'), ['TAX', 'INV'])
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->where('PartyID', $request->PartyID)

            ->get();


        $invoice_summary = DB::table('v_invoice_master')
            ->select(DB::raw('sum(Tax) as Tax, sum(GrandTotal) as GrandTotal, sum(Paid), sum(Balance) as Balance '))
            ->whereIn(DB::raw('left(InvoiceNo,3)'), ['TAX', 'INV'])
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->where('PartyID', $request->PartyID)
            ->where(DB::raw('GrandTotal-Paid'), '>', 0)
            ->get();

        $chartofacc = DB::table('v_chartofaccount_mini')->get();
        $payment_mode = DB::table('payment_mode')->get();

        $payment = DB::table('payment_master')
            ->select(DB::raw('ifnull(max(PaymentMasterID)+1,1) as PaymentMasterID'))
            ->get();


        return view('bulk_payment_search', compact('party', 'pagetitle', 'invoice_master', 'invoice_summary', 'chartofacc', 'payment_mode', 'payment'));
    }


    public function BulkPaymentSave(request $request)
    {


        // dd($request->all());
        // $employee = DB::table('employee')->get();
        // return view ('');

        // $request->PaymentDate=dateformatpc($request->PaymentDate);
        $request->PaymentDate = dateformatpc($request->PaymentDate);

        // dd($request->PaymentDate);

        if ($request->hasfile('UploadSlip')) {

            $this->validate($request, [

                // 'file' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,pdf|max:1000',
                'UploadSlip' => 'image|mimes:jpeg,png,jpg,gif,pdf,doc,docx',

            ]);




            $file = $request->file('UploadSlip');
            $input['filename'] = time() . '.' . $file->extension();

            $destinationPath = public_path('/uploads');

            $file->move($destinationPath, $input['filename']);

            // $destinationPath = public_path('/images');
            // $image->move($destinationPath, $input['imagename']);

            // $input['filename']===========is final data in it.


            $payment_master = array(
                'PaymentMasterID' => $request->PaymentMasterID,
                'PartyID' => $request->PartyID,
                'PaymentAmount' => $request->PaymentAmount,
                'PaymentDate' => $request->PaymentDate,
                'PaymentMode' => $request->PaymentMode,
                'ChartOfAccountID' => $request->ChartOfAccountIDIN,
                'ReferenceNo' => $request->ReferenceNo,
                'Notes' => $request->Notes,
                'File' => $input['filename'],
            );
        } else {

            $payment_master = array(
                'PaymentMasterID' => $request->PaymentMasterID,
                'PartyID' => $request->PartyID,
                'PaymentAmount' => $request->PaymentAmount,
                'PaymentDate' => $request->PaymentDate,
                'PaymentMode' => $request->PaymentMode,
                'ChartOfAccountID' => $request->ChartOfAccountIDIN,
                'ReferenceNo' => $request->ReferenceNo,
                'Notes' => $request->Notes,
            );
        }
        // end of if else used for to  check file uploaded or not

        $id = DB::table('payment_master')->insertGetId($payment_master);

        if ($request->input('PaymentMode') == 'Cash') {
            $PaymentMode = '110101'; //Cash in hand
            $JournalType = 'CP';
        } elseif ($request->input('PaymentMode') == 'Credit Card') {

            $PaymentMode = '110250'; //Credit Card ACCOUNT.
            $JournalType = 'CC';
        } else {
            $PaymentMode = '110201'; //ENBD BANK
            $JournalType = 'BP';
        }


        //  start for item array from invoice
        for ($i = 0; $i < count($request->InvoiceMasterID); $i++) {


            if ($request->Amount[$i] > 0) {


                $payment_detail = array(
                    'PaymentMasterID' => $request->PaymentMasterID,
                    'PaymentDate' => $request->PaymentDate,
                    'InvoiceMasterID' => $request->InvoiceMasterID[$i],
                    'Payment' => $request->Amount[$i],


                );



                $id = DB::table('payment_detail')->insertGetId($payment_detail);


                // payment of invoice

                // payment received


                //bank debit
                $bank_cash = array(
                    'VHNO' => 'PAY-' . $request->input('PaymentMasterID'),
                    'ChartOfAccountID' => $request->ChartOfAccountIDIN,   // Cash / bank / credit card
                    'JournalType' => $JournalType,
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $request->InvoiceMasterID[$i],
                    'PaymentMasterID' => $request->PaymentMasterID,
                    'Date' => $request->PaymentDate,

                    'Dr' => $request->Amount[$i],
                    'Narration' => 'Payment made with payment refno ' . $request->PaymentMasterID . ' for invoice ' . $request->InvoiceNo[$i] . '',
                    'Trace' => 6514
                );
                $journal_entry1 = DB::table('journal')->insertGetId($bank_cash);

                // A/R credit
                $ar_payment = array(
                    'VHNO' => 'PAY-' . $request->input('PaymentMasterID'),
                    'ChartOfAccountID' => $request->ChartOfAccountIDFrom,  // AR Customer
                    'JournalType' => $JournalType,
                    'PartyID' => $request->input('PartyID'),
                    'InvoiceMasterID' => $request->InvoiceMasterID[$i],
                    'PaymentMasterID' => $request->PaymentMasterID,
                    'Date' => $request->PaymentDate,
                    'Cr' => $request->Amount[$i],
                    'Narration' => 'Payment made with payment refno ' . $request->PaymentMasterID . ' for invoice ' . $request->InvoiceNo[$i] . '',
                    'Trace' => 6515
                );

                $journal_entry2 = DB::table('journal')->insertGetId($ar_payment);
                // end payment received

                // end of invoice payment



                $data = array(
                    'Paid' => $request->Amount[$i],
                    'Balance' => $request->GrandTotal[$i] - $request->Amount[$i]



                );



                $id = DB::table('invoice_master')->where('InvoiceMasterID', $request->InvoiceMasterID[$i])->update($data);
            }
        }



        return redirect('Payment')->with('error', 'Saved Successfully')->with('class', 'success');
    }


    public  function base64()
    {


        $image = "data:image/jpg;base64," . base64_encode('https://i.pinimg.com/474x/20/4c/9a/204c9afd4e16e2f5b8a54f0e2d547e87.jpg');
        dd($image);
    }


    public  function Inventory()
    {

        $pagetitle = 'Inventory';

        return view('inventory', compact('pagetitle'));
    }

    public  function Inventory1(request $request)
    {

        $pagetitle = 'Inventory';

        $company = DB::table('company')->get();
        $inventory = DB::table('v_inventory_detail')
            ->select('ItemID', 'ItemName', 'UnitName', DB::RAW('sum(SaleReturn) as SaleReturn, sum(QtyIn) as QtyIn, sum(QtyOut) as QtyOut'))
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->groupBy('ItemID', 'ItemName', 'UnitName')
            ->get();

        return View('inventory1', compact('pagetitle', 'company', 'inventory'));
    }




    public  function Inventory1PDF(request $request)
    {

        $pagetitle = 'Inventory';

        $company = DB::table('company')->get();
        $inventory = DB::table('v_inventory_detail')
            ->select('ItemID', 'ItemName', 'UnitName', DB::RAW('sum(SaleReturn) as SaleReturn, sum(QtyIn) as QtyIn, sum(QtyOut) as QtyOut'))
            ->whereBetween('Date', array($request->StartDate, $request->EndDate))
            ->groupBy('ItemID', 'ItemName', 'UnitName')
            ->get();

        $pdf = PDF::loadView('inventory1pdf', compact('pagetitle', 'company', 'inventory'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
        return $pdf->stream();
    }


    public  function lnventoryDetail($itemid, $startdate, $enddate)
    {

        $pagetitle = 'Inventory';


        $company = DB::table('company')->get();

        $inventory = DB::table('v_inventory_detail')
            ->where('ItemID', $itemid)

            ->whereBetween('Date', array($startdate, $enddate))
            ->get();








        return View('inventory_detail', compact('pagetitle', 'inventory', 'company', 'startdate', 'enddate'));
    }


    public  function PartyAgingPDF()
    {

        $pagetitle = 'Party Aging Report';

        $company = DB::table('company')->get();
        $aging = DB::table('v_party_aging')->get();

        $pdf = PDF::loadView('party_aging_pdf', compact('pagetitle', 'company', 'aging'));
        //return $pdf->download('pdfview.pdf');
        $pdf->setpaper('A4', 'landscape');
        return $pdf->stream();
    }


    public  function DBDump()
    {





        $databaseName = env('DB_DATABASE');
        $userName = env('DB_USERNAME');
        $password = env('DB_PASSWORD');


        Spatie\DbDumper\Databases\MySql::create()
            ->setDbName($databaseName)
            ->setUserName($userName)
            ->setPassword($password)
            ->dumpToFile('dump.sql');
    }



    function Excel(request $request)
    {


        return Excel::download(new ExcelLedger($request->StartDate, $request->EndDate), date('d-m-Y') . '-party-balance-saleman-wise.xlsx');
    }


    public function CitywiseReport()
    {
        $pagetitle = 'Citywise';
        $city = DB::table('party')->select('City')->distinct()->whereNotNull('City')->get();


        return view('citywise_report', compact('city', 'pagetitle'));
    }



    function SalesmanExport($city)
    {


        return Excel::download(new SalemanExport($city), $city . '-Parties-Ledger.xlsx');
    }


    function ExpenseReport()
    {
        $pagetitle = 'Expense';
        $job = DB::table('job')->get();
        return view('expense.expense_report', compact('pagetitle','job'));
    }


    public function ExpenseReport1(request $request)
    {

         $pagetitle = 'Expense Report';


        $company = DB::table('company')->get();

        $expense_detail = DB::table('v_expense_detail')
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when($request->JobID, function ($query, $JobID) {
        return $query->where('JobID', $JobID);
    })
    ->orderBy('Date')
    ->get();


       $summary = DB::table('v_expense_detail')
        ->select('ChartOfAccountName',DB::raw('sum(Tax) as Tax, sum(Amount) as TotalAmount'))
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when($request->JobID, function ($query, $JobID) {
        return $query->where('JobID', $JobID);
    })
    ->orderBy('Date')
    ->groupby('ChartOfAccountName')
    ->get();

 
        return View('expense.expense_report1', compact('expense_detail', 'pagetitle', 'company','summary'));
        //return $pdf->download('pdfview.pdf');
        // $pdf->setpaper('A4', 'portiate');
    }
    public function createDeliveryNote($id)
    {
        $pagetitle = 'Create Payment';
        $party = DB::table('party')->get();

        $items = DB::table('item')->get();
        $item = json_encode($items);
        // dd($item);
        $user = DB::table('user')->get();
        $chartofacc = DB::table('chartofaccount')->get();
        $challan_master = DB::table('challan_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(ChallanNo,5)),0)+1,5,0) as ChallanMasterID'))
            ->get();


        session::put('VHNO', 'DC-' . $challan_master[0]->ChallanMasterID);

        $challan_type = DB::table('challan_type')->get();
        $purchaseorder_detail = DB::table('purchase_order_detail')->where('PurchaseOrderMasterID', $id)->get();
        $purchaseorder_master = DB::table('purchase_order_master')->where('PurchaseOrderMasterID', $id)->first();

        return view('saleorder.createDeliveryNote', compact('chartofacc', 'party', 'pagetitle', 'challan_master', 'items', 'item', 'challan_type', 'user', 'purchaseorder_detail', 'purchaseorder_master'));
    }
    public  function createInvoice($id)
    {
        $pagetitle = 'Sales Invoice';

        session::put('menu', 'SalesInvoice');
        $invoice_type = DB::table('invoice_type')->get();
        $items = DB::table('item')->get();
        $item = json_encode($items);
        // dd($item);
        $party = DB::table('party')->get();

        // $tax = DB::table('tax')->get();
        $user = DB::table('user')->get();
        $invoice_type = DB::table('invoice_type')->get();


        $payment_mode = DB::table('payment_mode')->get();

        $tax = DB::table('tax')->where('Section', 'Invoice')->get();
        $vhno = DB::table('invoice_master')
            ->select(DB::raw('LPAD(IFNULL(MAX(right(InvoiceNo,5)),0)+1,5,0) as VHNO '))->whereIn(DB::raw('left(InvoiceNo,3)'), ['TAX'])->get();

        $chartofaccount = DB::table('chartofaccount')->whereNotNull('Category')->get();
        session::put('VHNO', 'TAX-' . $vhno[0]->VHNO);

        $challanData = DB::table('challan_master')->where('ChallanMasterID', $id)->first();
        $challandetailsData = DB::table('challan_detail')->where('ChallanMasterID', $id)->get();



        return view('saleorder.createInvoice', compact('invoice_type', 'items', 'vhno', 'party', 'pagetitle', 'item', 'user', 'tax', 'chartofaccount', 'payment_mode', 'challanData', 'challandetailsData'));
    }
} // end of controller
