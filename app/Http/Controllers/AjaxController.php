<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Service;
use App\Models\SubService;
use Illuminate\Http\Request;


class AjaxController extends Controller
{
    public function ajaxGetAgents($id = null)
    {
        try {



 



            if ($id != null && session::get('Type')!='Admin' )  {
                $agents = DB::table('user')->where('UserType', '!=', 'Admin')
                    ->where('branch_id', $id)->where('UserID',session::get('UserID'))
                    ->get();
            } else {
                $agents = DB::table('user')->where('UserType', '!=', 'Admin')
                    ->get();
            }






            return response()->json(['data' => $agents]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function ajaxGetServices($id = null)
    {
        try {
            if ($id != null) {
                $services = Service::where('branch_id', $id)
                    ->get();
            } else {
                $services = Service::all();
            }
            return response()->json(['data' => $services]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function ajaxGetSubservices($id = null)
    {
        try {
            if ($id != null) {
                $subServices = SubService::where('service_id', $id)
                    ->get();
            } else {
                $subServices = SubService::all();
            }
            return response()->json(['data' => $subServices]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }


   public function ajaxGetLeads()
    {
        try {
             
        $leads = DB::table('leads')->whereNull('agent_id')->get();


        if ($leads->isEmpty()) {
            return response()->json(['status' => 'empty' , 'total' =>0]);
        } else {
            return response()->json(['status' => 'not empty','total' => count($leads),'data' =>$leads]);
        }      
            


        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }


   public function ajaxGetBookingPayment()
    {
        try {
             
            $booking = DB::table('v_booking')->where('end','<',Carbon::now())->where('status','Pending')->get();



        if ($booking->isEmpty()) {
            return response()->json(['status' => 'empty' , 'total' =>0]);
        } else {
            return response()->json(['status' => 'not empty','total' => count($booking),'data' =>$booking]);
        }      
            


        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }



public function getPartyRefNo($party_id)
    {
         
             
            $party = DB::table('party')->where('PartyID',$party_id)->first();
            $invoice_count = DB::table('invoice_master')->where('PartyID',$party_id)->count();
            $ref_no=getInitials($party->PartyName).'-'.($invoice_count+1);

            return response()->json(['ref_no' => $ref_no]);

    }


public function getTransportRates($start_date,$end_date,$sector,$vehicle_type,$vehicle_status,$supplier_id,$party_id=null)
    {
         
        $rate = DB::table('transport_tariff')
                ->where('date_from','<=',$start_date)
                ->where('date_to','>=',$end_date)
                ->where('sector',$sector)
                ->where('vehicle_type',$vehicle_type)
                ->where('status',$vehicle_status)
                ->where('SupplierID',$supplier_id)
                // ->where('PartyID',$party_id)
                ->where('is_active',1)
                ->first();

            if($rate){
                return response()->json(['status' => 'found' , 'data' => $rate]);
            }else{
                return response()->json(['status' => 'not found' ]);
            }
             
            

    }
    
    
 
public function getHotelRates($start_date, $end_date, $location, $room_type, $room_status, $hotel_id, $supplier_id, $party_id = null)
{

    // dd($start_date, $end_date, $location, $room_type, $room_status, $hotel_id, $supplier_id, $party_id);
     $rate = \DB::table('hotel_tariffs')
        ->where('date_from', '<=', $start_date)
        ->where('date_to', '>=', $end_date)
        ->where('location', $location)
        // ->where('room_type', $room_type)
        // ->where('room_status', $room_status)
        ->where('is_active', 1)
        ->where('PartyID', $supplier_id)
        
        ->first();

    if (!$rate) {
        return response()->json(['status' => 'not found']);
    }

    // Normalize room type text for flexible matching
    $roomType = strtoupper(trim($room_type));
    $purchase = $rate->purchase_price;
    $sale = $rate->sale_price;

    if (preg_match('/TRIPLE/', $roomType)) {
        $purchase = $rate->triple_purchase ?? $rate->purchase_price;
        $sale = $rate->triple_sale ?? $rate->sale_price;
    } elseif (preg_match('/DOUBLE/', $roomType)) {
        $purchase = $rate->double_purchase ?? $rate->purchase_price;
        $sale = $rate->double_sale ?? $rate->sale_price;
    } elseif (preg_match('/QUAD/', $roomType)) {
        $purchase = $rate->quad_purchase ?? $rate->purchase_price;
        $sale = $rate->quad_sale ?? $rate->sale_price;
    } elseif (preg_match('/QUINT/', $roomType)) {
        $purchase = $rate->quint_purchase ?? $rate->purchase_price;
        $sale = $rate->quint_sale ?? $rate->sale_price;
    } elseif (preg_match('/SHAR/', $roomType)) {
        // for SHARING type if you have it
        $purchase = $rate->purchase_price;
        $sale = $rate->sale_price;
    } else {
        // fallback if not matched
        $purchase = $rate->purchase_price;
        $sale = $rate->sale_price;
    }

    return response()->json([
        'status' => 'found',
        'purchase_price' => $purchase,
        'sale_price' => $sale,
    ]);
}


public function getAirlineCode($ticket_no)
{
    
    $airline_code = substr($ticket_no, 0, 3);
    $airline= DB::table('airlines')->where('code',$airline_code)->first();

     if($airline){

    return response()->json(['airline_code' => $airline->country]);
    }
    else{
        return response()->json(['airline_code' => '']);
    }

    
}


// public function getBalanceOfGroupticket($pnr)
// {
    
    
//     $groupticket_purchase= DB::table('group_tickets')->where('VoucherType','GTP')->first();
//     $groupticket_sale= DB::table('group_tickets')->select('Quantity')->where('VoucherType','GTS')->count();

     

//     return response()->json(['groupticket' => $groupticket_purchase, 'balance'=>$groupticket_purchase->Quantity - $groupticket_sale]);
   

    
// }


public function getBalanceOfGroupticket($pnr)

{

  
 
    // Fetch purchase info
    $purchase = DB::table('group_tickets')
        ->select(
            'SupplierID',
            'PNR',
            'Sector',
            'DateOfDep',
            'DateOfArr',
            'AirlineName',
            'FlightNo',
            'FlightNo',
            'Fare',
            DB::raw('SUM(Quantity) as TotalPurchased')
        )
        ->where('VoucherType', 'GTP')
        ->where('PNR', $pnr)
        ->groupBy('SupplierID', 'PNR', 'Sector', 'DateOfDep', 'DateOfArr', 'AirlineName', 'FlightNo')
        ->first();

    // Get total sold quantity
    $totalSold = DB::table('group_tickets')
        ->where('VoucherType', 'GTS')
        ->where('PNR', $pnr)
        ->sum('Quantity');

    // Calculate balance safely (handle null purchase)
    $balance = $purchase ? ($purchase->TotalPurchased - $totalSold) : null;

    // Return all fields — even if null
    return response()->json([
        'SupplierID'  => $purchase->SupplierID  ?? null,
        'PNR'         => $purchase->PNR         ?? $pnr,
        'Sector'      => $purchase->Sector      ?? null,
        'DateOfDep'   => $purchase->DateOfDep   ?? null,
        'DateOfArr'   => $purchase->DateOfArr   ?? null,
        'AirlineName' => $purchase->AirlineName ?? null,
        'FlightNo'    => $purchase->FlightNo    ?? null,
        'Balance'     => $balance,
        'TicketPrice'     => $purchase->Fare     ?? null,
    ]);
}




}
 
