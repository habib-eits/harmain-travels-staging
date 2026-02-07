<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Journal;
use App\Models\GroupTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use PHPUnit\TextUI\XmlConfiguration\Group;

class GroupTicketSaleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('group_ticket_sale.index');
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
            'AirlineCode',
            'PaxName',
            'TicketNo',
            'FlightNo',
            'Fare',
            'Quantity',
            'Payable',
            'Receivable',
            'PaymentDueDate',
            'PartyID',
            'CareOf',
            'Remarks'
        ])->where('VoucherType', 'GTS');

        return DataTables::of($groupTickets)
            ->addColumn('action', function ($groupTicket) {
                return '
                    <div class="btn-group" role="group">
                        <a href="' . route('group-ticket-sale.show', $groupTicket->GroupTicketID) . '" class="btn btn-info btn-sm" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="' . route('group-ticket-sale.edit', $groupTicket->GroupTicketID) . '" class="btn btn-warning btn-sm" title="Edit">
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
            ->editColumn('Receivable', function ($groupTicket) {
                return number_format($groupTicket->Receivable, 2);
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
        $pnr = GroupTicket::select('PNR')->where('VoucherType','GTP')->get();
        return view('group_ticket_sale.create', compact('suppliers', 'parties', 'sectors','airline','group_tick_no','pnr'));
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
            'AirlineCode' => 'required|string|max:2',
            'PaxName' => 'required|string|max:255',
            'TicketNo' => 'required|string|max:16',
            'FlightNo' => 'required|string|max:255',
             'Fare' => 'required|numeric|min:0',
            'Quantity' => 'required|numeric|min:1',
            'Receivable' => 'required|numeric|min:0',
            'PaymentDueDate' => 'nullable|date',
            'PartyID' => 'nullable|string',
            'ExRate' => 'nullable|numeric|min:0',
            'CareOf' => 'nullable|string|max:255',
            'Remarks' => 'nullable|string'
        ]);

        GroupTicket::create($request->all());
        $this->saveSaleJournal($request);

        return redirect()->route('group-ticket-sale.index')
            ->with('success', 'Group ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupTicket $groupTicket)
    {
        return view('group_ticket_sale.show', compact('groupTicket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupTicket $groupTicket)
    {
        // Get suppliers and parties for dropdowns
        $suppliers = Party::getSupplierList();
        $parties = Party::getPartyList();
        $airline = DB::table('airlines')->get();
        $pnr = GroupTicket::select('PNR')->where('VoucherType','GTP')->get();


 
        
        return view('group_ticket_sale.edit', compact('groupTicket', 'suppliers','airline','parties','pnr'));
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
            'AirlineCode' => 'required|string|max:2',
            'PaxName' => 'required|string|max:255',
            'TicketNo' => 'required|string|max:16',
            'FlightNo' => 'required|string|max:255',
            'Fare' => 'required|numeric|min:0',
            'Quantity' => 'required|numeric|min:1',
            'Receivable' => 'required|numeric|min:0',
            'PaymentDueDate' => 'nullable|date',
            'PartyID' => 'nullable|string',
            'ExRate' => 'nullable|numeric|min:0',
            'CareOf' => 'nullable|string|max:255',
            'Remarks' => 'nullable|string'
        ]);

        $groupTicket->update($request->all());

         


        $this->saveSaleJournal($request);

        return redirect()->route('group-ticket-sale.index')
            ->with('success', 'Group ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupTicket $groupTicket)
    {
        $groupTicket->delete();

          // Delete existing journal entries related to this ticket
            \App\Models\Journal::where('GroupTicketID', $groupTicket->GroupTicketID)
            ->where('JournalType', 'SALE')
            ->delete();

            

        return response()->json([
            'success' => true,
            'message' => 'Group ticket deleted successfully.'
        ]);
    }


    public function saveSaleJournal(request  $request)
{


    // Delete existing journal entries related to this ticket
    Journal::where('GroupTicketID', $request->VoucherNo)
    ->where('JournalType', 'SALE')
    ->delete();
 
    // Common values
    $vhno = 'GTS-' . $request->VoucherNo;
    $date = Carbon::parse($request->Date);

    // purchase of ticket from supplier (debit)
    Journal::create([
        'BranchID'        => $request->BranchID ?? 1,
        'Date'            => $request->Date,
        'VHNO'            => $vhno,
        'JournalType'     => 'SALE',
        'ChartOfAccountID'=> 110400, // A/reivable
        'PartyID'      => $request->PartyID,
        'GroupTicketID' => $request->VoucherNo ?? null,
        
        'Narration' => 
        'Ticket Sale ' . 
        '| Ticket No: ' . $request->TicketNo .
        '| PNR: ' . $request->PNR .
        ' | Sector: ' . $request->Sector .
        // ' | Airline: ' . $request->AirlineName .
        ' | Flight: ' . $request->FlightNo .
        // ' | Ticket #: ' . $request->TicketNo .
        // ' | No of Seats : ' . $request->Quantity .
        ' | Fare: ' . number_format($request->Fare, 2) .
        ' | Receivable: ' . number_format($request->Receivable, 2) .
        ' | Date of Departure: ' . $request->DateOfDep .
        ' | Date of Arrival: ' . $request->DateOfArr,

        'Date'            => $date,
        // 'Currency'        => 'PKR',
        // 'Rate'            => $request->ExRate ?? 1,
        'Dr'              => $request->Receivable,
        'Cr'              => 0,
        'UserID'          => Session::get('UserID'),
    ]);

    // purchases of ticket
    Journal::create([
        'BranchID'        => $request->BranchID ?? 1,
        'VHNO'            => $vhno,
        'Date'            => $request->Date,
        'JournalType'     => 'SALE',
        'ChartOfAccountID'=> 510111, // PURCHASE OF TICKET
        'PartyID'      => $request->PartyID,
        'GroupTicketID' => $request->VoucherNo ?? null,
        
         'Narration' => 
        'Ticket Sale ' . 
        '| Ticket No: ' . $request->TicketNo .

        '| PNR: ' . $request->PNR .
        ' | Sector: ' . $request->Sector .
        // ' | Airline: ' . $request->AirlineName .
        ' | Flight: ' . $request->FlightNo .
        // ' | Ticket #: ' . $request->TicketNo .
        // ' | No of Seats : ' . $request->Quantity .
        ' | Fare: ' . number_format($request->Fare, 2) .
        ' | Receivable: ' . number_format($request->Receivable, 2) .
        ' | Date of Departure: ' . $request->DateOfDep .
        ' | Date of Arrival: ' . $request->DateOfArr,

        'Date'            => $date,
        // 'Currency'        => 'PKR',
        // 'Rate'            => $request->ExRate ?? 1,
        'Cr'              => $request->TicketPrice,
        'Dr'              => 0,
        'UserID'          => Session::get('UserID'),
    ]);


// purchases of ticket
    Journal::create([
        'BranchID'        => $request->BranchID ?? 1,
        'VHNO'            => $vhno,
        'Date'            => $request->Date,
        'JournalType'     => 'SALE',
        'ChartOfAccountID'=> 410150, //    REVENUE OF TICKET
        'PartyID'      => $request->PartyID,
        'GroupTicketID' => $request->VoucherNo ?? null,
        
         'Narration' => 
        'Ticket Sale ' . 
        '| Ticket No: ' . $request->TicketNo .

        '| PNR: ' . $request->PNR .
        ' | Sector: ' . $request->Sector .
        // ' | Airline: ' . $request->AirlineName .
        ' | Flight: ' . $request->FlightNo .
        // ' | Ticket #: ' . $request->TicketNo .
        // ' | No of Seats : ' . $request->Quantity .
        ' | Fare: ' . number_format($request->Fare, 2) .
        ' | Receivable: ' . number_format($request->Receivable, 2) .
        ' | Date of Departure: ' . $request->DateOfDep .
        ' | Date of Arrival: ' . $request->DateOfArr,

        'Date'            => $date,
        // 'Currency'        => 'PKR',
        // 'Rate'            => $request->ExRate ?? 1,
        'Cr'              => $request->Receivable-$request->TicketPrice,
        'Dr'              => 0,
        'UserID'          => Session::get('UserID'),
    ]);


}
}
