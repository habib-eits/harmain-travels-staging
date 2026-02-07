<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Journal;
use App\Models\VisaDetail;
use App\Models\VisaMaster;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VisaDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            if ($request->ajax()) {

                $InvoiceMasterID = $request->InvoiceMasterID;

                $data = VisaDetail::where('InvoiceMasterID', $InvoiceMasterID)->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column


                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a href="#" onclick="editVisaDetailRecord(' . $row->InvoiceDetailID . ')" 
                        class="btn btn-sm btn-primary me-1 d-inline-flex align-items-center">
                            <i class="bx bx-pencil font-size-16 me-1"></i> Edit
                        </a>
                        <a href="#" onclick="deleteVisaDetailRecord(' . $row->InvoiceDetailID . ')" 
                        class="btn btn-sm btn-danger d-inline-flex align-items-center">
                            <i class="bx bx-trash font-size-16 me-1"></i> Delete
                        </a>';

                        return $btn;
                    })


                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }
        } catch (\Exception $e) {
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'InvoiceMasterID'  => 'required',
            'PaxName'          => 'required',
            'PartyID'          => 'required',

        ]);

        try {
            $data = $request->only([
                'InvoiceMasterID',
                'ItemID',
                'SupplierID',
                'VisaType',
                'PaxName',
                'Passport',
                'Nationality',
                'VisaStatus',
                'VisaNo',
                'DOB',
                'Age',
                'PaxType',
                'Gender',
                'IssueDate',
                'ExpiryDate',
                'RelationType',
                'Relation',
                'ShirkaID',
                'PackageName',
                'DepartureDate',
                'VisaSaleRate',
                'ExRateSale',
                'Receivable',
                'VisaPurchaseRate',
                'ExRatePurchase',
                'Payable',
            ]);



            $visaDetail =  VisaDetail::updateOrCreate(
                ['InvoiceDetailID' => $request->InvoiceDetailID],
                $data
            );


             // Delete existing journal entries related to this ticket
            \App\Models\Journal::where('InvoiceMasterID', $visaDetail->InvoiceMasterID)
            ->where('JournalType', 'VISA_SALE')
            ->where('PassportNo', $visaDetail->PassportNo)
            ->delete();


        $this->saveSaleJournal($request);


            $visaMasterTotal = $this->updateVisaMaster($visaDetail->InvoiceMasterID);


 
            return response()->json([
                'success' => true,
                'message' => 'Record added successfully.',
                'InvoiceDetailID' => $visaDetail->InvoiceDetailID,
                'visaMasterTotal' => $visaMasterTotal,
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
            $data = VisaDetail::findOrFail($id);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 
public function destroy(string $id)
{

     try {
        $record = VisaDetail::find($id);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found.',
            ], 404);
        }

        $InvoiceMasterID = $record->InvoiceMasterID;
        $Passport = $record->Passport;
        DB::beginTransaction();

        // 🔹 Delete journal entries for this specific passport under same invoice
        Journal::where('InvoiceMasterID', $InvoiceMasterID)
            ->where('JournalType', 'VISA_SALE')
            ->where('PassportNo', $Passport)
            ->delete();

        // 🔹 Delete the visa detail record itself
        $record->delete();

        // 🔹 Update Visa Master totals after deletion
        $visaMasterTotal = $this->updateVisaMaster($InvoiceMasterID);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Record and related journal entries deleted successfully.',
            'visaMasterTotal' => $visaMasterTotal,
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the record.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}



    public function updateAllRecords(Request $request, $InvoiceMasterID)
    {
        $visaMaster = VisaMaster::find($InvoiceMasterID);


        $validated = $request->validate([
            'VisaSaleRate'          => 'required',
            'ExRateSale'          => 'required',
            'Receivable'          => 'required',
            'VisaPurchaseRate'          => 'required',
            'ExRatePurchase'          => 'required',
            'Payable'          => 'required',

        ]);

        try {
            VisaDetail::where('InvoiceMasterID', $InvoiceMasterID)->update([
                'VisaSaleRate' => $request->VisaSaleRate,
                'ExRateSale' => $request->ExRateSale,
                'Receivable' => $request->Receivable,
                'VisaPurchaseRate' => $request->VisaPurchaseRate,
                'ExRatePurchase' => $request->ExRatePurchase,
                'Payable' => $request->Payable,
            ]);


            $visaMasterTotal = $this->updateVisaMaster($InvoiceMasterID);

            return response()->json([
                'success' => true,
                'message' => 'Record Updated successfully.',
                'visaMasterTotal' => $visaMasterTotal,
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the record.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function updateVisaMaster($InvoiceMasterID)
    {
        $total = VisaDetail::where('InvoiceMasterID', $InvoiceMasterID)->sum('Payable');
        $visaMaster = VisaMaster::find($InvoiceMasterID);

        if ($visaMaster) {
            $visaMaster->update([
                'Total' => $total
            ]);
        }

        return $total;
    }

    public function saveSaleJournal(request  $request)
    {


          // Delete existing journal entries related to this ticket
           Journal::where('InvoiceMasterID', $request->InvoiceMasterID)
           ->where('JournalType', 'VISA_SALE')
           ->where('PassportNo', $request->Passport)
           ->delete();



        // AR Entry - DR
        $vhno = 'VISA-SALE-' . $request->InvoiceMasterID;
        $date = Carbon::parse($request->Date);



        // PURCHASE OF TICKET - > DR    
        Journal::create([
            'BranchID'        => $request->BranchID ?? 1,
            'Date'            => $request->Date,
            'VHNO'            => $vhno,
            'JournalType'     => 'VISA_SALE',
            'InvoiceMasterID' => $request->InvoiceMasterID,
            'ChartOfAccountID' => 510103, // PURCHASE OF TICKET / VISA PURCHASE
            'PartyID'      => $request->SupplierID,
            'PassportNo'      => $request->Passport,
            'Narration' =>
            'Visa Purchase' .
            ' | Pax: ' . $request->PaxName .
            ' | Passport: ' . $request->Passport .
            // ' | Nationality: ' . $request->Nationality .
            ' | Visa Type: ' . $request->VisaType .
            ' | Visa No: ' . $request->VisaNo .
            // ' | Sale Rate: ' . number_format($request->VisaSaleRate, 2) .
            ' | Purchase Rate: ' . number_format($request->Payable, 2),
            // ' | Receivable: ' . number_format($request->Receivable, 2) .
            // ' | Payable: ' . number_format($request->Payable, 2) .
            // ' | Profit: ' . number_format($request->Receivable - $request->Payable, 2),

            'Date'            => $date,
            // 'Currency'        => 'PKR',
            // 'Rate'            => $request->ExRate ?? 1,
            'Dr'              => $request->Payable,
            'Cr'              => 0,
            'UserID'          => Session::get('UserID'),
        ]);
        
        
        
        // AP - > CR    
        Journal::create([
            'BranchID'        => $request->BranchID ?? 1,
            'Date'            => $request->Date,
            'VHNO'            => $vhno,
            'JournalType'     => 'VISA_SALE',
            'InvoiceMasterID' => $request->InvoiceMasterID,
            'ChartOfAccountID' => 210100, // A/P 
            'PartyID'      => $request->SupplierID,
            'PassportNo'      => $request->Passport,
            'Narration' =>
            'Visa Purchase' .
            ' | Pax: ' . $request->PaxName .
            ' | Passport: ' . $request->Passport .
            // ' | Nationality: ' . $request->Nationality .
            ' | Visa Type: ' . $request->VisaType .
            ' | Visa No: ' . $request->VisaNo .
            // ' | Sale Rate: ' . number_format($request->VisaSaleRate, 2) .
            ' | Purchase Rate: ' . number_format($request->Payable, 2),
            // ' | Receivable: ' . number_format($request->Receivable, 2) .
            // ' | Payable: ' . number_format($request->Payable, 2) .
            // ' | Profit: ' . number_format($request->Receivable - $request->Payable, 2),

            'Date'            => $date,
            // 'Currency'        => 'PKR',
            // 'Rate'            => $request->ExRate ?? 1,
            'Dr'              => 0,
            'Cr'              => $request->Payable,
            'UserID'          => Session::get('UserID'),
        ]);




        // AR Entry
        Journal::create([
            'BranchID'        => $request->BranchID ?? 1,
            'Date'            => $request->Date,
            'VHNO'            => $vhno,
            'JournalType'     => 'VISA_SALE',
            'InvoiceMasterID' => $request->InvoiceMasterID,
            'ChartOfAccountID' => 110400, // A/R
            'PartyID'      => $request->PartyID,
            'PassportNo'      => $request->Passport,
            'Narration' =>
            'Visa Sale' .
            ' | Pax: ' . $request->PaxName .
            ' | Passport: ' . $request->Passport .
            // ' | Nationality: ' . $request->Nationality .
            ' | Visa Type: ' . $request->VisaType .
            ' | Visa No: ' . $request->VisaNo .
            ' | Sale Rate: ' . number_format($request->Receivable, 2) ,
            // ' | Purchase Rate: ' . number_format($request->VisaPurchaseRate, 2),
            // ' | Receivable: ' . number_format($request->Receivable, 2) .
            // ' | Payable: ' . number_format($request->Payable, 2) .
            // ' | Profit: ' . number_format($request->Receivable - $request->Payable, 2),

            'Date'            => $date,
            // 'Currency'        => 'PKR',
            // 'Rate'            => $request->ExRate ?? 1,
            'Dr'              => $request->Receivable,
            'Cr'              => 0,
            'UserID'          => Session::get('UserID'),
        ]);
        
        


        // PURCHASE OF VISA -> CR
        Journal::create([
            'BranchID'        => $request->BranchID ?? 1,
            'Date'            => $request->Date,
            'VHNO'            => $vhno,
            'JournalType'     => 'VISA_SALE',
            'InvoiceMasterID' => $request->InvoiceMasterID,
            'ChartOfAccountID' => 510103, // PURCHASE OF VISA
            // 'PartyID'      => $request->PartyID,
            'PassportNo'      => $request->Passport,
            'Narration' =>
            'Visa Purchase' .
            ' | Pax: ' . $request->PaxName .
            ' | Passport: ' . $request->Passport .
            // ' | Nationality: ' . $request->Nationality .
            ' | Visa Type: ' . $request->VisaType .
            ' | Visa No: ' . $request->VisaNo .
            ' | Sale Rate: ' . number_format($request->Receivable, 2) ,
            // ' | Purchase Rate: ' . number_format($request->Payable, 2),
            // ' | Receivable: ' . number_format($request->Receivable, 2) .
            // ' | Payable: ' . number_format($request->Payable, 2) .
            // ' | Profit: ' . number_format($request->Receivable - $request->Payable, 2),

            'Date'            => $date,
            // 'Currency'        => 'PKR',
            // 'Rate'            => $request->ExRate ?? 1,
            'Dr'              => 0,
            'Cr'              => $request->Payable,
            'UserID'          => Session::get('UserID'),
        ]);


        // REVENUE OF VISA -> CR
        Journal::create([
            'BranchID'        => $request->BranchID ?? 1,
            'Date'            => $request->Date,
            'VHNO'            => $vhno,
            'JournalType'     => 'VISA_SALE',
            'InvoiceMasterID' => $request->InvoiceMasterID,
            'ChartOfAccountID' => 410102, // REVENUE OF VISA
            // 'PartyID'      => $request->PartyID,
            'PassportNo'      => $request->Passport,
            'Narration' =>
            'Visa Purchase' .
            ' | Pax: ' . $request->PaxName .
            ' | Passport: ' . $request->Passport .
            // ' | Nationality: ' . $request->Nationality .
            ' | Visa Type: ' . $request->VisaType .
            ' | Visa No: ' . $request->VisaNo .
            ' | Sale Rate: ' . number_format($request->Receivable, 2) ,
            // ' | Purchase Rate: ' . number_format($request->Payable, 2),
            // ' | Receivable: ' . number_format($request->Receivable, 2) .
            // ' | Payable: ' . number_format($request->Payable, 2) .
            // ' | Profit: ' . number_format($request->Receivable - $request->Payable, 2),

            'Date'            => $date,
            // 'Currency'        => 'PKR',
            // 'Rate'            => $request->ExRate ?? 1,
            'Dr'              => 0,
            'Cr'              => $request->Receivable-$request->Payable,
            'UserID'          => Session::get('UserID'),
        ]);


         




    }
}
