<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Party;
use App\Models\Sector;
use App\Models\Airline;
use App\Models\Company;
use App\Models\GroupTicket;
use App\Models\Journal;
use App\Models\Location;
use App\Models\InvoiceHotel;
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
use App\Models\InvoiceTransport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\UmrahInvoicePassenger;
use PHPUnit\TextUI\XmlConfiguration\Group;

class TravelProReportingController extends Controller
{

    public function hotel_hcn_search()
    {
        $pagetitle = "Hotel HCN Search";
        $hotel = Hotel::getHotelList();
        $party = Party::getPartyList();
        $supplier = Party::getSupplierList();
        $location = Location::all();


        return view('travel_pro_reporting.hotel_hcn_search', compact('hotel', 'party', 'supplier', 'location', 'pagetitle'));
    }


    public function hotel_hcn_search1(Request $request)
    {

        $data = InvoiceHotel::with(['hotel_name', 'invoiceMaster', 'party'])
            ->whereHas('invoiceMaster', function ($query) use ($request) {
                $query->whereBetween('Date', [$request->StartDate, $request->EndDate]);
            })
            ->when($request->hotel_id, function ($query) use ($request) {
                $query->where('hotel_id', $request->hotel_id);
            })
            ->when($request->SupplierID, function ($query) use ($request) {
                $query->where('SupplierID', $request->SupplierID);
            })
            ->when($request->PartyID, function ($query) use ($request) {
                $query->where('PartyID', $request->PartyID);
            })
            ->when($request->Location, function ($query) use ($request) {
                $query->where('HotelCity', $request->Location);
            })
            ->when($request->HotelType === 'Hotels Without HCN', function ($query) {
                $query->whereNull('HCN_NO');
            })
            ->get();



        $pagetitle = "Hotel HCN Search";
        $hotel = Hotel::getHotelList();
        return view('travel_pro_reporting.hotel_hcn_search1', compact('data', 'pagetitle', 'hotel'));
    }


    public function update_hotel_hcn(Request $request)
    {


        $invoiceHotel = InvoiceHotel::findOrFail($request->id);

        $invoiceHotel->update([
            'HCN_NO'   => $request->HCN_NO,
            'hotel_id' => $request->hotel_id,
        ]);

        return response()->json(['message' => 'Record updated successfully']);

        return response()->json([
            'success' => true,
            'message' => 'HCN updated successfully.',
        ], 200);
    }


    public function transport_brn_search()
    {
        $pagetitle = "Transport BRN Search";
        $supplier = Party::getSupplierList();


        return view('travel_pro_reporting.transport_brn_search', compact('supplier',  'pagetitle'));
    }


    public function transport_brn_search1(Request $request)
    {

        $data = InvoiceTransport::with(['invoiceMaster', 'party'])
            ->whereHas('invoiceMaster', function ($query) use ($request) {
                $query->whereBetween('Date', [$request->StartDate, $request->EndDate]);
            })

            ->when($request->SupplierID, function ($query) use ($request) {
                $query->where('SupplierID', $request->SupplierID);
            })
            ->when($request->HotelType === 'Transport Without BRN', function ($query) {
                $query->whereNull('TransportBrnCode');
            })
            ->get();



        $pagetitle = "Transport BRN Code";
        $hotel = Hotel::getHotelList();
        return view('travel_pro_reporting.transport_brn_search1', compact('data', 'pagetitle', 'hotel'));
    }


    public function update_transport_brn(Request $request)
    {


        $invoiceHotel = InvoiceTransport::findOrFail($request->id);

        $invoiceHotel->update([
            'TransportBrnCode'   => $request->TransportBrnCode,
            'TCN'   => $request->TCN,
        ]);

        return response()->json(['message' => 'Record updated successfully']);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully.',
        ], 200);
    }


    public function view_hotel_voucher() 
    {

        $pagetitle = "View Hotel Voucher";
        $party = Party::getPartyList();
        $package = \App\Models\Packages::all();
        return view('travel_pro_reporting.view_hotel_voucher', compact( 'pagetitle', 'party', 'package'));

    }

    public function ajax_umrah_report_index(Request $request)
    {








        //  dd($data->invoiceMaster->Date);

        try {
            if ($request->ajax()) {
                $query = UmrahInvoicePassenger::with([
        'invoiceMaster' => function ($query) {
            $query->select('InvoiceMasterID', 'Date', 'PartyID', 'package_id')
                ->with([
                    'party:PartyID,PartyName',
                    'package:id,name' // adjust these column names if different
                ]);
        }
    ])->where('relation_type', 'Head');

    // Apply filters if present
    if ($request->has('StartDate') && !empty($request->StartDate)) {
        $query->whereHas('invoiceMaster', function ($q) use ($request) {
            $q->whereDate('Date', '>=', $request->StartDate);
        });
    }

    if ($request->has('EndDate') && !empty($request->EndDate)) {
        $query->whereHas('invoiceMaster', function ($q) use ($request) {
            $q->whereDate('Date', '<=', $request->EndDate);
        });
    }

    if ($request->has('PartyID') && !empty($request->PartyID)) {
        $query->whereHas('invoiceMaster', function ($q) use ($request) {
            $q->where('PartyID', $request->PartyID);
        });
    }

    if ($request->has('package_id') && !empty($request->package_id)) {
        $query->whereHas('invoiceMaster', function ($q) use ($request) {
            $q->where('package_id', $request->package_id);
        });
    }

 

    $data = $query->orderBy('id', 'desc')->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column

                    ->addColumn('Date', function ($row) {
                        return ('INV-' . $row->invoiceMaster->InvoiceMasterID ?? 'N/A') . '<br>' . (dateformatman2($row->invoiceMaster->Date) ?? '');
                    })


                    ->addColumn('package_name', function ($row) {
                        return $row->invoiceMaster->package->name ?? '';
                    })


                    ->addColumn('PartyName', function ($row) {
                        return $row->invoiceMaster->Party->PartyName ?? '';
                    })
             ->addColumn('action', function ($row) {
          // if you want to use direct link instead of dropdown use this line below
          // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>

          $btn = ' 
 
                       <div class="d-flex align-items-center col-actions">
                     
 
<a href="' . route('umrah.voucher.view', [$row->umrah_invoice_master_id, 'English']) . '"><i class="font-size-14 fa fa-print p-1
-outline align-middle me-1 text-secondary"></i></a> 


<a href="' . route('umrah.voucher.view', [$row->umrah_invoice_master_id, 'Urdu']) . '"><i class="font-size-14 fa fa-fax p-1 align-middle me-1 text-secondary"></i></a> 

<a href="' . route('umrah.voucher.view', [$row->umrah_invoice_master_id, 'English']) . '"><i class="font-size-14 fa fa-search-plus p-1 align-middle me-1 text-secondary"></i></a> 

<a href="' . URL('/VoucherEdit/' . $row->VoucherMstID) . '"><i class="font-size-14 fa fa-building  p-1 align-middle me-1 text-secondary"></i></a> 


                       </div>';

          //class="edit btn btn-primary btn-sm"
          // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          return $btn;
        })
                    ->rawColumns(['Date', 'action'])
                    ->make(true);
            }


            $data = UmrahInvoicePassenger::with(['invoiceMaster.Party'])->get();

            // return view('umrah.invoice_masters.index');

        } catch (\Exception $e) {

            return back()->with('flash-danger', $e->getMessage());
        }
    }


     public function view_hotel_invoice() 
    {

        $pagetitle = "View Hotel Voucher";
        $party = Party::getPartyList();
        $package = \App\Models\Packages::all();
        return view('travel_pro_reporting.view_hotel_invoice', compact( 'pagetitle', 'party', 'package'));

    }

    public function ajax_umrah_report_index2(Request $request)
    {








        //  dd($data->invoiceMaster->Date);

        try {
            if ($request->ajax()) {
                $query = UmrahInvoicePassenger::with([
        'invoiceMaster' => function ($query) {
            $query->select('InvoiceMasterID', 'Date', 'PartyID', 'package_id')
                ->with([
                    'party:PartyID,PartyName',
                    'package:id,name' // adjust these column names if different
                ]);
        }
    ])->where('relation_type', 'Head');

    // Apply filters if present
    if ($request->has('StartDate') && !empty($request->StartDate)) {
        $query->whereHas('invoiceMaster', function ($q) use ($request) {
            $q->whereDate('Date', '>=', $request->StartDate);
        });
    }

    if ($request->has('EndDate') && !empty($request->EndDate)) {
        $query->whereHas('invoiceMaster', function ($q) use ($request) {
            $q->whereDate('Date', '<=', $request->EndDate);
        });
    }

    if ($request->has('PartyID') && !empty($request->PartyID)) {
        $query->whereHas('invoiceMaster', function ($q) use ($request) {
            $q->where('PartyID', $request->PartyID);
        });
    }

    if ($request->has('package_id') && !empty($request->package_id)) {
        $query->whereHas('invoiceMaster', function ($q) use ($request) {
            $q->where('package_id', $request->package_id);
        });
    }

 

    $data = $query->orderBy('id', 'desc')->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    // Status toggle column

                    ->addColumn('Date', function ($row) {
                        return ('INV-' . $row->invoiceMaster->InvoiceMasterID ?? 'N/A') . '<br>' . (dateformatman2($row->invoiceMaster->Date) ?? '');
                    })


                    ->addColumn('package_name', function ($row) {
                        return $row->invoiceMaster->package->name ?? '';
                    })


                    ->addColumn('PartyName', function ($row) {
                        return $row->invoiceMaster->Party->PartyName ?? '';
                    })
             ->addColumn('action', function ($row) {
          // if you want to use direct link instead of dropdown use this line below
          // <a href="javascript:void(0)"  onclick="edit_data('.$row->customer_id.')" >Edit</a> | <a href="javascript:void(0)"  onclick="del_data('.$row->customer_id.')"  >Delete</a>

          $btn = ' 
 
                       <div class="d-flex align-items-center col-actions">
                     
 
<a href="' . route('view.hotel.voucher.invoice', ['id'=>$row->umrah_invoice_master_id,'type'=>1]) . '" target="_blank"><i class="font-size-18 bx bxs-printer p-1
-outline align-middle me-1 text-secondary"></i></a> 


<a href="' . route('view.hotel.voucher.invoice', ['id'=>$row->umrah_invoice_master_id,'type'=>2]) . '" target="_blank"><i class="font-size-18 fas fa-fax p-1 align-middle me-1 text-secondary"></i></a> 

<a href="' . route('view.hotel.voucher.invoice', ['id'=>$row->umrah_invoice_master_id,'type'=>3]) . '" target="_blank"><i class="font-size-18 bx bx-receipt p-1 align-middle me-1 text-secondary"></i></a> 

<a href="' . route('view.hotel.voucher.invoice', ['id'=>$row->umrah_invoice_master_id,'type'=>4]) . '" target="_blank"><i class="font-size-18 mdi mdi-file-settings-outline p-1 align-middle me-1 text-secondary"></i></a> 


<a href="' . route('view.hotel.voucher.invoice', ['id'=>$row->umrah_invoice_master_id,'type'=>5]) . '" target="_blank"><i class="font-size-18 mdi mdi-file-table p-1 align-middle me-1 text-secondary"></i></a> 

 

                       </div>';

          //class="edit btn btn-primary btn-sm"
          // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          return $btn;
        })
                    ->rawColumns(['Date', 'action'])
                    ->make(true);
            }


            $data = UmrahInvoicePassenger::with(['invoiceMaster.Party'])->get();

            // return view('umrah.invoice_masters.index');

        } catch (\Exception $e) {

            return back()->with('flash-danger', $e->getMessage());
        }
    }


        public function view_hotel_voucher_invoice($id,$type)
    {
        $company = Company::first();
        $invoice_master = InvoiceMaster::find($id);
        $invoice_passanger = UmrahInvoicePassenger::with(['shirka'])->where('umrah_invoice_master_id',$id)->get();
        $invoice_hotel = InvoiceHotel::with(['hotel_name'])->where('InvoiceMasterID',$id)->get();
        $invoice_transport = InvoiceTransport::where('InvoiceMasterID',$id)->get();
        $summary = $this->getPassengerSummary($id);
        $journal = Journal::where('InvoiceMasterID',$id)->where('ChartOfAccountID',110400)->Sum('Cr');
        $company = Company::first();


        $balance = Journal::where('InvoiceMasterID', $id)
    ->where('ChartOfAccountID', 110400)
    ->select(DB::raw('SUM(Dr) - Sum(Cr) as balance'))
    ->value('balance') ?? 0;


        $summary = $this->getPassengerSummary($id);
 
         return view('travel_pro_reporting.view_hotel_voucher_invoice',compact('company','invoice_master','invoice_passanger','invoice_hotel','invoice_transport','summary','journal','balance','company'));
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

public function hotel_voucher_sale_register()
    {
        $pagetitle = "Hotel Voucher Sale Register";
        $party = Party::getPartyList();
        $package = \App\Models\Packages::all();
        return view('travel_pro_reporting.hotel_voucher_sale_register', compact( 'pagetitle', 'party', 'package'));
        
        
    }
        
        public function hotel_voucher_sale_register2(request $request)
        {
            $pagetitle = "Hotel Voucher Sale Register";
            $party = Party::getPartyList();
            $package = \App\Models\Packages::all();
            $company=Company::first();
            
            // $invoice_master = InvoiceMaster::with('hotel')->where('InvoiceMasterID',4421)->get();
            
            $invoice_dates = InvoiceMaster::select('Date')
            ->whereBetween('Date', [$request->StartDate, $request->EndDate])
            ->when($request->PartyID, function ($query) use ($request) {
                $query->where('PartyID', $request->PartyID);
    })
    ->when($request->package_id, function ($query) use ($request) {
        $query->where('package_id', $request->package_id);
    })
    // ->where('InvoiceTypeID',1)
    ->where('InvoiceStatus','APPROVED')
    ->whereNotNull('package_id')
    ->distinct()
    ->orderBy('Date', 'asc')
    ->pluck('Date');
    
 
    return view('travel_pro_reporting.hotel_voucher_sale_register2', compact( 'pagetitle', 'party', 'package','company','invoice_dates'));
    
}


public function hotel_wise_payment_report()
{
    $pagetitle = "Hotel Wise Payment Report";
    $party = Party::getPartyList();
    $supplier = Party::getSupplierList();
    $hotel = Hotel::getHotelList();
    $location = Location::getLocationList();
    
    return view('travel_pro_reporting.hotel_wise_payment_report', compact( 'pagetitle', 'party', 'supplier', 'hotel', 'location'));
    
}






public function hotel_wise_payment_report2(request $request)
{


     $pagetitle = "Hotel Wise Payment Report";
    
    $company=Company::first();
    
    // $invoice_master = InvoiceMaster::with('hotel')->where('InvoiceMasterID',4421)->get();
    
$invoice_hotel = InvoiceHotel::with(['party','hotel_name','invoiceMaster'])
 ->whereHas('invoiceMaster', function ($q) use ($request) {
        $q->whereBetween('Date', [$request->StartDate, $request->EndDate]);
    })
    ->when($request->PartyID, function ($query) use ($request) {
        $query->where('PartyID', $request->PartyID);
    })
    ->when($request->SupplierID, function ($query) use ($request) {
        $query->where('SupplierID', $request->SupplierID);
    })
    ->when($request->location_id, function ($query) use ($request) {
        $query->where('HotelCity', $request->location_id);
    })
    

    ->get();




    $invoice_hotel1 = InvoiceHotel::select(
        'HotelCity',
        'SupplierID',
        'hotel_id',
        DB::raw('SUM(HotelPayable) as TotalPayable'),
        DB::raw('SUM(HotelReceivable) as TotalReceivable'),
    )
    ->whereHas('invoiceMaster', function ($q) use ($request) {
        $q->whereBetween('Date', [$request->StartDate, $request->EndDate]);
    })
    ->when($request->PartyID, fn($q) => $q->where('PartyID', $request->PartyID))
    ->when($request->SupplierID, fn($q) => $q->where('SupplierID', $request->SupplierID))
    ->when($request->location_id, fn($q) => $q->where('HotelCity', $request->location_id))
    ->groupBy('HotelCity', 'SupplierID', 'hotel_id')
    ->with(['party', 'hotel_name'])
    ->get();



 return view('travel_pro_reporting.hotel_wise_payment_report2', compact( 'pagetitle','company','invoice_hotel', 'invoice_hotel1'));

}



public function hotel_wise_profit_report()
{
    $pagetitle = "Hotel Wise Profit Report";
    $party = Party::getPartyList();
    $supplier = Party::getSupplierList();
    $hotel = Hotel::getHotelList();
    $location = Location::getLocationList();
    
    return view('travel_pro_reporting.hotel_wise_profit_report', compact( 'pagetitle', 'party', 'supplier', 'hotel', 'location'));
    
}


public function transport_wise_payment_report()
{
    $pagetitle = "Transport Wise Payment Report";
    $party = Party::getPartyList();
    $supplier = Party::getSupplierList();
    $sector = Sector::get();
    $location = Location::getLocationList();
    
    return view('travel_pro_reporting.transport_wise_payment_report', compact( 'pagetitle', 'party', 'supplier', 'sector', 'location'));
    
}


public function transport_wise_payment_report2(request $request)
{


     $pagetitle = "Transport Wise Payment Report";
    
    $company=Company::first();
    
    // $invoice_master = InvoiceMaster::with('hotel')->where('InvoiceMasterID',4421)->get();
    
$invoice_transport = InvoiceTransport::with(['party','invoiceMaster'])
 ->whereHas('invoiceMaster', function ($q) use ($request) {
        $q->whereBetween('Date', [$request->StartDate, $request->EndDate]);
    })
    ->when($request->PartyID, function ($query) use ($request) {
        $query->where('PartyID', $request->PartyID);
    })
    ->when($request->SupplierID, function ($query) use ($request) {
        $query->where('SupplierID', $request->SupplierID);
    })
    ->when($request->location_id, function ($query) use ($request) {
        $query->where('HotelCity', $request->location_id);
    })

    ->get();


 return view('travel_pro_reporting.transport_wise_payment_report2', compact( 'pagetitle','company','invoice_transport'));

}


public function group_ticket_register()
{
    $pagetitle = "Group Ticket Register";
    $party = Party::getPartyList();
    
    
    $airline = Airline::get();
    
    return view('travel_pro_reporting.group_ticket_register', compact( 'pagetitle', 'party', 'airline'));
    
}



public function group_ticket_register2(request $request)
{
    $pagetitle = "Group Ticket Purchase Search";
  
    $company=Company::first();

    if($request->ReportType == 'Group Ticket Purchase Search' || $request->ReportType == 'Group Ticket Purchase Register')
    {
$voucher_type='GTP';
    }
    else
    {
$voucher_type='GTS';
    }


    $group_ticket = GroupTicket::with('supplier')
        ->whereBetween('Date', [$request->StartDate, $request->EndDate])
        ->where('VoucherType', $voucher_type)
        ->when($request->PartyID, function ($query) use ($request) {
            $query->where('PartyID', $request->PartyID);
        })
        ->when($request->airline, function ($query) use ($request) {
            $query->where('AirlineName', $request->airline);
        }) 
        ->when($request->PartyID, function ($query) use ($request) {
            $query->where('PartyID', $request->PartyID);
        })
        ->get();


        $group_ticket_purchase = GroupTicket::with('supplier')
        ->whereBetween('Date', [$request->StartDate, $request->EndDate])
        ->where('VoucherType', 'GTP')
         
        ->when($request->airline, function ($query) use ($request) {
            $query->where('AirlineName', $request->airline);
        }) 
        ->get();

 
    
    // $invoice_master = InvoiceMaster::with('hotel')->where('InvoiceMasterID',4421)->get();
    return view('travel_pro_reporting.group_ticket_register2', compact( 'pagetitle','company','group_ticket', 'group_ticket_purchase'));    

    }


    public function VoucherSalemanReport(Request $request)
    {   
    $pagetitle = 'Party wise sale';
    
    if ($request->isMethod('get')) {
        // Show profile form
       

    $sale = DB::table('v_umrah_invoice_master')
    ->whereBetween('Date', [date('Y-m-01'), date('Y-m-d')])
    ->get();


    } elseif ($request->isMethod('post')) {
        

 
    $sale = DB::table('v_umrah_invoice_master')
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when($request->UserID > 0, function ($query) use ($request) {
        return $query->where('UserID', $request->UserID);
    })
    ->get();


        // Handle form submission
    }

    $users= DB::table('user')->where('Active', 'Yes')->get();

    return view('travel_pro_reporting.voucher_saleman_report', compact('pagetitle', 'sale','users'));


    }


    public function VoucherSalemanSummaryReport(Request $request)
    {   
    $pagetitle = 'Party wise sale';
    
    if ($request->isMethod('get')) {
        // Show profile form
       

    $sale = DB::table('v_umrah_invoice_master')
      ->select(
        'FullName',
        DB::raw('SUM(visa_sale) as total_visa_sale'),
        DB::raw('SUM(visa_purchase) as total_visa_purchase'),
        DB::raw('SUM(visa_profit) as total_visa_profit'),
        DB::raw('SUM(ticket_sale) as total_ticket_sale'),
        DB::raw('SUM(ticket_purchase) as total_ticket_purchase'),
        DB::raw('SUM(ticket_profit) as total_ticket_profit'),
        DB::raw('SUM(HotelPayable) as total_hotel_payable'),
        DB::raw('SUM(HotelReceivable) as total_hotel_receivable'),
        DB::raw('SUM(hotel_profit) as total_hotel_profit'),
        DB::raw('SUM(transport_sale) as total_transport_sale'),
        DB::raw('SUM(transport_purchase) as total_transport_purchase'),
        DB::raw('SUM(transport_profit) as total_transport_profit')
    )
    ->groupBy('FullName')
    ->whereBetween('Date', [date('Y-m-01'), date('Y-m-d')])
    ->get();


    } elseif ($request->isMethod('post')) {
        
 
 
    $sale = DB::table('v_umrah_invoice_master')
    ->select(
        'FullName',
        DB::raw('SUM(visa_sale) as total_visa_sale'),
        DB::raw('SUM(visa_purchase) as total_visa_purchase'),
        DB::raw('SUM(visa_profit) as total_visa_profit'),
        DB::raw('SUM(ticket_sale) as total_ticket_sale'),
        DB::raw('SUM(ticket_purchase) as total_ticket_purchase'),
        DB::raw('SUM(ticket_profit) as total_ticket_profit'),
        DB::raw('SUM(HotelPayable) as total_hotel_payable'),
        DB::raw('SUM(HotelReceivable) as total_hotel_receivable'),
        DB::raw('SUM(hotel_profit) as total_hotel_profit'),
        DB::raw('SUM(transport_sale) as total_transport_sale'),
        DB::raw('SUM(transport_purchase) as total_transport_purchase'),
        DB::raw('SUM(transport_profit) as total_transport_profit')
    )
    ->groupBy('FullName')
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when($request->UserID > 0, function ($query) use ($request) {
        return $query->where('UserID', $request->UserID);
    })
    ->get();


        // Handle form submission
    }

    $users= DB::table('user')->where('Active', 'Yes')->get();

    return view('travel_pro_reporting.voucher_saleman_summary_report', compact('pagetitle', 'sale','users'));


    }


    public function OutstandingVoucher(Request $request)
    {   
    $pagetitle = 'Outstanding Voucher';
    
    if ($request->isMethod('get')) {
        // Show profile form
       

    $sale = DB::table('v_umrah_invoice_master')
      ->select(
        'FullName',
        DB::raw('SUM(visa_sale) as total_visa_sale'),
        DB::raw('SUM(visa_purchase) as total_visa_purchase'),
        DB::raw('SUM(visa_profit) as total_visa_profit'),
        DB::raw('SUM(ticket_sale) as total_ticket_sale'),
        DB::raw('SUM(ticket_purchase) as total_ticket_purchase'),
        DB::raw('SUM(ticket_profit) as total_ticket_profit'),
        DB::raw('SUM(HotelPayable) as total_hotel_payable'),
        DB::raw('SUM(HotelReceivable) as total_hotel_receivable'),
        DB::raw('SUM(hotel_profit) as total_hotel_profit'),
        DB::raw('SUM(transport_sale) as total_transport_sale'),
        DB::raw('SUM(transport_purchase) as total_transport_purchase'),
        DB::raw('SUM(transport_profit) as total_transport_profit')
    )
    ->groupBy('FullName')
    ->whereBetween('Date', [date('Y-m-01'), date('Y-m-d')])
    ->get();


    } elseif ($request->isMethod('post')) {
        
 
 
    $sale = DB::table('v_umrah_invoice_master')
    ->select(
        'FullName',
        DB::raw('SUM(visa_sale) as total_visa_sale'),
        DB::raw('SUM(visa_purchase) as total_visa_purchase'),
        DB::raw('SUM(visa_profit) as total_visa_profit'),
        DB::raw('SUM(ticket_sale) as total_ticket_sale'),
        DB::raw('SUM(ticket_purchase) as total_ticket_purchase'),
        DB::raw('SUM(ticket_profit) as total_ticket_profit'),
        DB::raw('SUM(HotelPayable) as total_hotel_payable'),
        DB::raw('SUM(HotelReceivable) as total_hotel_receivable'),
        DB::raw('SUM(hotel_profit) as total_hotel_profit'),
        DB::raw('SUM(transport_sale) as total_transport_sale'),
        DB::raw('SUM(transport_purchase) as total_transport_purchase'),
        DB::raw('SUM(transport_profit) as total_transport_profit')
    )
    ->groupBy('FullName')
    ->whereBetween('Date', [$request->StartDate, $request->EndDate])
    ->when($request->UserID > 0, function ($query) use ($request) {
        return $query->where('UserID', $request->UserID);
    })
    ->get();


        // Handle form submission
    }

    $users= DB::table('user')->where('Active', 'Yes')->get();

    return view('travel_pro_reporting.voucher_saleman_summary_report', compact('pagetitle', 'sale','users'));


    }








}


 