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

class AdminDashboard extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {



        
            session::put('menu', 'Dashboard');
    
    
    
            $total_booking = DB::table('bookings')
            ->where(DB::raw('DATE_FORMAT(start,"%Y-%m-%d")'), date('Y-m-d'))
            ->count();
    
    
            $total_leads = DB::table('leads')
        
            ->count();
    
              $leads_reject = DB::table('leads')
        
            ->where('status','Rejected')
            ->count();
    
              $leads_pending = DB::table('leads')
        
            ->where('status','Pending')
            ->count();
    
    
             $leads_won = DB::table('leads')
        
            ->where('approved_status','Closed Won')
            ->count();
           
            $leads_lost = DB::table('leads')
        
            ->where('approved_status','Closed Lost')
            ->count();
    
            $leads_not_assigned = DB::table('leads')
            ->whereNull('agent_id')
            ->count();


            $leads_not_assigned = DB::table('leads')
            ->whereNull('agent_id')
            ->count();

            $fourDaysAgo = Carbon::now()->subDays(4);
            
            $leadsNotUpdatedIn4Days = DB::table('leads')
            ->where('status','Pending')
            ->where('updated_at', '<', $fourDaysAgo)->count();
           
   
            $booking_payment = DB::table('v_bookings_admin')->where('amount','>',0)->where('status','Pending')->count();
            
            $agents = DB::table('user')->where('UserType' ,'!=', 'Admin')->where('Active','Yes')->get();
        

            $today = Carbon::today();
        
            $leads_created_today = DB::table('leads')
          
            ->whereDate('created_at', $today)
            ->count();
    
            $leads_updated_today =  DB::table('leads')
           
            ->whereDate('updated_at', $today)
            ->count();
          
            $pagetitle = 'Dashboard';
    


             $followup = DB::table('lead_details')->whereDate('follow_up_date', $today)->get();

     


 
$today_lead_summary = DB::table('leads')
    ->select(
        DB::raw('count(leads.id) AS Total'),
        'user.FullName',
        DB::raw('sum(IF(service_id = 1, 1, 0)) AS Ticket'),
        DB::raw('sum(IF(service_id = 3, 1, 0)) AS UmrahByBus'),
        DB::raw('sum(IF(service_id = 4, 1, 0)) AS HotelBooking'),
        DB::raw('sum(IF(service_id = 5, 1, 0)) AS VisitVisa'),
        DB::raw('sum(IF(service_id = 6, 1, 0)) AS MultiVisa'),
        DB::raw('sum(IF(service_id = 7, 1, 0)) AS A2A'),
        DB::raw('sum(IF(service_id = 8, 1, 0)) AS GT'),
        DB::raw('sum(IF(service_id = 9, 1, 0)) AS UmrahByAir'),
        DB::raw('sum(IF(service_id = 10, 1, 0)) AS DesertSafari')
    )
    ->join('user', 'leads.agent_id', '=', 'user.UserID')
    ->whereDate('created_at', $today)
    ->groupBy('user.FullName')
    ->get();


$yesterday = Carbon::now()->subDays(1);



$yesterday_lead_summary = DB::table('leads')
    ->select(
        DB::raw('count(service_id) AS Total'),
 'user.FullName',
        DB::raw('sum(IF(service_id = 1, 1, 0)) AS Ticket'),
        DB::raw('sum(IF(service_id = 3, 1, 0)) AS UmrahByBus'),
        DB::raw('sum(IF(service_id = 4, 1, 0)) AS HotelBooking'),
        DB::raw('sum(IF(service_id = 5, 1, 0)) AS VisitVisa'),
        DB::raw('sum(IF(service_id = 6, 1, 0)) AS MultiVisa'),
        DB::raw('sum(IF(service_id = 7, 1, 0)) AS A2A'),
        DB::raw('sum(IF(service_id = 8, 1, 0)) AS GT'),
        DB::raw('sum(IF(service_id = 9, 1, 0)) AS UmrahByAir'),
        DB::raw('sum(IF(service_id = 10, 1, 0)) AS DesertSafari')
    )
    ->join('user', 'leads.agent_id', '=', 'user.UserID')
    ->whereDate('created_at', $yesterday)
    ->groupBy('user.FullName')
    ->get();

$week = Carbon::now()->subDays(8);

$week_lead_summary = DB::table('leads')
    ->select(
        DB::raw('count(service_id) AS Total'),
      'user.FullName',
        DB::raw('sum(IF(service_id = 1, 1, 0)) AS Ticket'),
        DB::raw('sum(IF(service_id = 3, 1, 0)) AS UmrahByBus'),
        DB::raw('sum(IF(service_id = 4, 1, 0)) AS HotelBooking'),
        DB::raw('sum(IF(service_id = 5, 1, 0)) AS VisitVisa'),
        DB::raw('sum(IF(service_id = 6, 1, 0)) AS MultiVisa'),
        DB::raw('sum(IF(service_id = 7, 1, 0)) AS A2A'),
        DB::raw('sum(IF(service_id = 8, 1, 0)) AS GT'),
        DB::raw('sum(IF(service_id = 9, 1, 0)) AS UmrahByAir'),
        DB::raw('sum(IF(service_id = 10, 1, 0)) AS DesertSafari')
    )
    ->join('user', 'leads.agent_id', '=', 'user.UserID')
    ->whereBetween('created_at', [$week,$today])
    ->groupBy('user.FullName')
    ->get();



$month = Carbon::now()->subDays(30);

$month_lead_summary = DB::table('leads')
    ->select(
        DB::raw('count(service_id) AS Total'),
       'user.FullName',
        DB::raw('sum(IF(service_id = 1, 1, 0)) AS Ticket'),
        DB::raw('sum(IF(service_id = 3, 1, 0)) AS UmrahByBus'),
        DB::raw('sum(IF(service_id = 4, 1, 0)) AS HotelBooking'),
        DB::raw('sum(IF(service_id = 5, 1, 0)) AS VisitVisa'),
        DB::raw('sum(IF(service_id = 6, 1, 0)) AS MultiVisa'),
        DB::raw('sum(IF(service_id = 7, 1, 0)) AS A2A'),
        DB::raw('sum(IF(service_id = 8, 1, 0)) AS GT'),
        DB::raw('sum(IF(service_id = 9, 1, 0)) AS UmrahByAir'),
        DB::raw('sum(IF(service_id = 10, 1, 0)) AS DesertSafari')
    )
    ->join('user', 'leads.agent_id', '=', 'user.UserID')
    ->whereBetween('created_at', [$month,$today])
    ->groupBy('user.FullName')
    ->get();





            // 'exp_chart'
            return view('admin_dashboard', compact('pagetitle', 
            'total_booking','total_leads','leads_won','leads_lost',
            'leads_pending','leads_not_assigned','leads_reject',
            'booking_payment','agents','leadsNotUpdatedIn4Days',
            'leads_created_today','leads_updated_today','followup','today_lead_summary','yesterday_lead_summary','week_lead_summary','month_lead_summary','today'
        
        ));
        
    
    
    }
}