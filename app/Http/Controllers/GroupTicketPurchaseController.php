<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Party;
use App\Models\Journal;
use App\Models\GroupTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GroupTicketPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('group_tickets_purchase.index');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        $groupTickets = GroupTicket::select([
            'GroupTicketID',
            'VoucherType',
            'VoucherNo',
            'Date',
            'SupplierID',
            'PNR',
            'Sector',
            'DateOfDep',
            'DateOfArr',
            'AirlineName',
            'FlightNo',
            'Fare',
            'Quantity',
            'Payable',
            'PaymentDueDate',
            'PartyID',
            'CareOf',
            'Remarks'
        ])->where('VoucherType', 'GTP');

        return DataTables::of($groupTickets)
            ->addColumn('action', function ($groupTicket) {
                return '
                    <div class="btn-group" role="group">
                        <a href="' . route('group-ticket-purchase.show', $groupTicket->GroupTicketID) . '" class="btn btn-info btn-sm" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="' . route('group-ticket-purchase.edit', $groupTicket->GroupTicketID) . '" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteGroupTicket(' . $groupTicket->GroupTicketID . ')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->addColumn('total_amount', function ($groupTicket) {
                return number_format($groupTicket->Fare * $groupTicket->Quantity, 2);
            })
            ->editColumn('Date', function ($groupTicket) {
                return $groupTicket->Date ? date('M d, Y', strtotime($groupTicket->Date)) : 'N/A';
            })
            ->editColumn('DateOfDep', function ($groupTicket) {
                return $groupTicket->DateOfDep ? date('M d, Y', strtotime($groupTicket->DateOfDep)) : 'N/A';
            })
            ->editColumn('DateOfArr', function ($groupTicket) {
                return $groupTicket->DateOfArr ? date('M d, Y', strtotime($groupTicket->DateOfArr)) : 'N/A';
            })
            ->editColumn('PaymentDueDate', function ($groupTicket) {
                return $groupTicket->PaymentDueDate ? date('M d, Y', strtotime($groupTicket->PaymentDueDate)) : 'N/A';
            })
            ->editColumn('Fare', function ($groupTicket) {
                return number_format($groupTicket->Fare, 2);
            })
            ->editColumn('Payable', function ($groupTicket) {
                return number_format($groupTicket->Payable, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get suppliers and parties for dropdowns
        $suppliers = Party::getSupplierList();
        $parties = Party::getPartyList();
        $sectors = DB::table('sector')->get();
        $airline = DB::table('airlines')->get();
        $group_tick = GroupTicket::count();
        $group_tick_no = $group_tick + 1;
        return view('group_tickets_purchase.create', compact('suppliers', 'parties', 'sectors','airline','group_tick_no'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'VoucherType' => 'required|string|max:255',
            'VoucherNo' => 'required|integer',
            'Date' => 'required|date',
            'SupplierID' => 'required|string',
            'PNR' => 'required|string|max:255',
            'Sector' => 'required|string|max:255',
            'DateOfDep' => 'required|date',
            'DateOfArr' => 'required|date',
            'AirlineName' => 'required|string|max:255',
            'FlightNo' => 'required|string|max:255',
            'Fare' => 'required|numeric|min:0',
            'Quantity' => 'required|numeric|min:1',
            'Payable' => 'required|numeric|min:0',
            'PaymentDueDate' => 'nullable|date',
            'PartyID' => 'nullable|string',
            'ExRate' => 'nullable|numeric|min:0',
            'CareOf' => 'nullable|string|max:255',
            'Remarks' => 'nullable|string'
        ]);

        $groupTicket = GroupTicket::create($request->all());
        // $group_ticket_id = $groupTicket->GroupTicketID; // or your custom primary key name

        $this->savePurchaseJournal($request);

        // $delete = Journal::where('GroupTicketID', $request->VoucherNo)->delete();

        return redirect()->route('group-ticket-purchase.index')
            ->with('success', 'Group ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupTicket $groupTicket)
    {
        return view('group_tickets_purchase.show', compact('groupTicket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupTicket $groupTicket)
    {
        // Get suppliers and parties for dropdowns
        $suppliers = Party::getSupplierList();
        $parties = Party::getPartyList();
        $sectors = DB::table('sector')->get();
        $airline = DB::table('airlines')->get();

     
        return view('group_tickets_purchase.edit', compact('groupTicket', 'suppliers', 'parties', 'sectors', 'airline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupTicket $groupTicket)
    {

         $request->validate([
            'VoucherType' => 'required|string|max:255',
            'VoucherNo' => 'required|integer',
            'Date' => 'required|date',
            'SupplierID' => 'required|string',
            'PNR' => 'required|string|max:255',
            'Sector' => 'required|string|max:255',
            'DateOfDep' => 'required|date',
            'DateOfArr' => 'required|date',
            'AirlineName' => 'required|string|max:255',
            'FlightNo' => 'required|string|max:255',
            'Fare' => 'required|numeric|min:0',
            'Quantity' => 'required|numeric|min:1',
            'Payable' => 'required|numeric|min:0',
            'PaymentDueDate' => 'nullable|date',
            'PartyID' => 'nullable|string',
            'ExRate' => 'nullable|numeric|min:0',
            'CareOf' => 'nullable|string|max:255',
            'Remarks' => 'nullable|string'
        ]);

        $groupTicket->update($request->all());

        
           

            // Recreate new journal entries
            // Make sure savePurchaseJournal expects either $request or $groupTicket
            $request['GroupTicketID'] = $groupTicket->GroupTicketID;
            $this->savePurchaseJournal($request);




        


        return redirect()->route('group-ticket-purchase.index')
            ->with('success', 'Group ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupTicket $groupTicket)
    {
        $groupTicket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Group ticket deleted successfully.'
        ]);
    }



 

public function savePurchaseJournal(request  $request)
{


     

            // Delete existing journal entries related to this ticket
            Journal::where('GroupTicketID', $request->VoucherNo)
            ->where('JournalType', 'PURCHASE')
            ->delete();

  
    // Common values
    $vhno = 'GTP-' . $request->VoucherNo;
    $date = Carbon::parse($request->Date);

    // ---- Debit Entry (Expense / Purchase Account) ----
    Journal::create([
        'BranchID'        => $request->BranchID ?? 1,
        'Date'            => $request->Date,
        'VHNO'            => $vhno,
        'JournalType'     => 'PURCHASE',
        'ChartOfAccountID'=> 510111, // purchase of ticket from supplier
         'PartyID'      => $request->SupplierID,
        'GroupTicketID' => $request->VoucherNo ?? null,
        
        'Narration' => 
        'Ticket Purchase for ' . 
        '| PNR: ' . $request->PNR .
        ' | Sector: ' . $request->Sector .
        ' | Airline: ' . $request->AirlineName .
        ' | Flight: ' . $request->FlightNo .
        // ' | Ticket #: ' . $request->TicketNo .
        ' | No of Seats : ' . $request->Quantity .
        ' | Fare: ' . number_format($request->Fare, 2) .
        ' | Payable: ' . number_format($request->Payable, 2) .
        ' | Date of Departure: ' . $request->DateOfDep .
        ' | Date of Arrival: ' . $request->DateOfArr,

        'Date'            => $date,
        // 'Currency'        => 'PKR',
        // 'Rate'            => $request->ExRate ?? 1,
        'Dr'              => $request->Payable,
        'Cr'              => 0,
        'UserID'          => Session::get('UserID'),
    ]);

    // ---- Credit Entry (Supplier Account) ----
    Journal::create([
        'BranchID'        => $request->BranchID ?? 1,
        'VHNO'            => $vhno,
        'Date'            => $request->Date,
        'JournalType'     => 'PURCHASE',
        'ChartOfAccountID'=> 210100, // payable
        'PartyID'      => $request->SupplierID,
        'GroupTicketID' => $request->VoucherNo ?? null,
        
         'Narration' => 
        'Ticket Purchase for ' . 
        '| PNR: ' . $request->PNR .
        ' | Sector: ' . $request->Sector .
        ' | Airline: ' . $request->AirlineName .
        ' | Flight: ' . $request->FlightNo .
        // ' | Ticket #: ' . $request->TicketNo .
        ' | No of Seats : ' . $request->Quantity .
        ' | Fare: ' . number_format($request->Fare, 2) .
        ' | Payable: ' . number_format($request->Payable, 2) .
        ' | Date of Departure: ' . $request->DateOfDep .
        ' | Date of Arrival: ' . $request->DateOfArr,

        'Date'            => $date,
        // 'Currency'        => 'PKR',
        // 'Rate'            => $request->ExRate ?? 1,
        'Dr'              => 0,
        'Cr'              => $request->Payable,
        'UserID'          => Session::get('UserID'),
    ]);
}



}
