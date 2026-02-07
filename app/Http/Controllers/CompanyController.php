<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
class CompanyController extends Controller
{
    public  function Company()
    {
        $pagetitle = 'Company';

        $company = DB::table('company')->get();
        //   dd($company);
        return view('company.company', compact('company'));
    }
    public  function SaveCompany(request $request)
    {
        // dd($request);
$pagetitle = 'Company';
        $this->validate(
            $request,
            [

                'Name' => 'required',
                // 'Logo' => 'required|mimes:jpeg,png,jpg,gif,doc,docx,bmp,pdf|max:20000'


            ]
        );

        $logo = $request->file('Logo');
        $signature = $request->file('Signature');

        // dd($logo);

        $input['logo'] = time() . '.' . $logo->extension();
        $input['signature'] = time() . '.' . $signature->extension();

        $destinationPath = public_path('/documents');


        $logo->move($destinationPath, $input['logo']);
        $signature->move($destinationPath, $input['signature']);





        $data = array(

            'Name' => $request->input('Name'),
            'Name2' => $request->input('Name2'),
            'TRN' => $request->input('TRN'),
            'Email' => $request->input('Email'),
            'Mobile' => $request->input('Mobile'),
            'Contact' => $request->input('Contact'),
            'Mobile' => $request->input('Mobile'),
            'Address' => $request->input('Address'),
            'Website' => $request->input('Website'),
            'Logo' => $input['logo'],
            'BackgroundLogo' => $input['BackgroundLogo'],
            'Signature' => $input['signature'],
            'DigitalSignature' => $request->input('DigitalSignature'),
            'EstimateInvoiceTitle' => $request->input('EstimateInvoiceTitle'),
            'SaleInvoiceTitle' => $request->input('SaleInvoiceTitle'),
            'DeliveryChallanTitle' => $request->input('DeliveryChallanTitle'),
            'CreditNoteTitle' => $request->input('CreditNoteTitle'),
            'PurchaseInvoiceTitle' => $request->input('PurchaseInvoiceTitle'),
            'DebitNoteTitle' => $request->input('DebitNoteTitle'),



        );

        $id = DB::table('company')->insertGetId($data);

 
        return redirect('Company' )->with('error', 'Save Successfully.')->with('class', 'success');
    }
    public  function CompanyEdit($id)
    {


        session::put('menu', 'Company');
        $pagetitle = 'Company';

        $company = DB::table('company')->where('CompanyID', $id)->get();
        // dd($company);
        return view('company.company_edit', compact('pagetitle', 'company'));
    }
    public  function CompanyUpdate(request $request)
    {
        $data = array(

            'Name' => $request->Name,
            'Name2' => $request->Name2,
            'TRN' => $request->TRN,
            'Email' => $request->Email,
            'Mobile' => $request->Mobile,
            'Contact' => $request->Contact,
            'Mobile' => $request->input('Mobile'),
            'Address' => $request->Address,
            'Website' => $request->Website,
           
            'DigitalSignature' => $request->input('DigitalSignature'),
            'EstimateInvoiceTitle' => $request->input('EstimateInvoiceTitle'),
            'SaleInvoiceTitle' => $request->input('SaleInvoiceTitle'),
            'DeliveryChallanTitle' => $request->input('DeliveryChallanTitle'),
            'CreditNoteTitle' => $request->input('CreditNoteTitle'),
            'PurchaseInvoiceTitle' => $request->input('PurchaseInvoiceTitle'),
            'DebitNoteTitle' => $request->input('DebitNoteTitle'),
 
        );
        $destinationPath = public_path('/documents');

         if ($request->hasFile('Logo')) {

            $logo = $request->file('Logo');
            $fileName_logo = time().'.'.$logo->extension();
            $data = Arr::add($data, 'Logo',  $fileName_logo);
            $logo->move($destinationPath,  $fileName_logo);
        }

         if ($request->hasFile('BackgroundLogo')) {

            $BackgroundLogo = $request->file('BackgroundLogo');
            $fileName_bg = time().'.'.$BackgroundLogo->extension();
            $data = Arr::add($data, 'BackgroundLogo',  $fileName_bg);
            $BackgroundLogo->move($destinationPath,  $fileName_bg);
        }
        if ($request->hasFile('Signature')) {
            $signature = $request->file('Signature');
            $fileName2 = time().'.'.$signature->extension();
            $data = Arr::add($data, 'Signature', $fileName2);

            $signature->move($destinationPath, $fileName2);
        }
       

        $id = DB::table('company')->where('CompanyID', $request->input('CompanyID'))->update($data);
        $pagetitle = 'Company';

        $company = DB::table('company')->get();
        return redirect('Company')->with('error', 'Save Successfully.')->with('class', 'success');
    }
    public  function CompanyDelete($id)
    {
        $pagetitle = 'Company';

        $company = DB::table('company')->get();

        // $id = DB::table('company')->where('CompanyID', $id)->delete();
        return redirect('Company')->with('error', 'Dont delete this record.')->with('class', 'danger');
    }
}
