<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Session;
use Carbon\Carbon;
use DB;

class BookingController extends Controller
{
    // Retrieve all bookings
    public function index()
    {


         try {
                 DB::beginTransaction();
                   // return Booking::all();
                $pagetitle='Booking';
                $booking= DB::table('v_booking')->get();
         

                return view ('booking.booking',compact('booking','pagetitle'));
                 DB::commit();
              } catch (\Exception $e) {
                 DB::rollBack();
                 return back()->with('error', $e->getMessage())->with('class', 'danger');
             }
       
    }


        public function ajax_booking()
    {
        // return Booking::all();


      if(session::get('Type')=='Admin')
      {

        return DB::table('bookings')->get();
      }
      else
      {

        // return DB::table('bookings')->whereBetween(    DB::raw('DATE_FORMAT(Date,"%Y-%m-%d")'),array(request()->query('start'),request()->query('end'))->get();


    $start = request()->query('start');
    $end = request()->query('end');

    $bookings = DB::table('bookings')
        ->where(DB::raw('DATE_FORMAT(start, "%Y-%m-%d")'), '>=', $start)
        ->where(DB::raw('DATE_FORMAT(start, "%Y-%m-%d")'), '<=',$end)
        ->where('users_id',session::get('UserID'))
        ->get();

    return response()->json($bookings);



      }

    }


     public function calendar()
    {   
        $pagetitle='Booking';
        return view('calendar.index',compact('pagetitle'));
    }

    // Create a new booking
    public function store(Request $request)
    {

       // dd($request->all());

         $data = array(
            'title' => $request->Title, 
            'start' => $request->Start, 
            'end' => $request->End, 
            'Color' => $request->Color, 
        );

        $booking= DB::table('bookings')->insertGetId($data);






        
         return redirect('calendar');

    }

    // Update an existing booking
    public function update(Request $request)
    {
 
            $data = array(
            'title' => $request->Title, 
          'start' => date("Y-m-d H:i:s", strtotime($request->Start)),
              'end' => date("Y-m-d H:i:s", strtotime($request->End)),
             'Color' => $request->Color, 
        );

        
        
        
        $booking= DB::table('bookings')->where('id', $request->id)->update($data);
        
        

        return redirect('calendar')->with('error','Updated successfully')->with('class','success');
    }

    // Delete a booking
    public function BookingDraged(Request $request)
    {   
            

             $data = array(
            // "title" => $request->title, 
           "start" => date("Y-m-d H:i:s", strtotime("+5 hours", strtotime($request->start))),
              "end" => date("Y-m-d H:i:s", strtotime("+5 hours", strtotime($request->end)))

            );

             // dd($data);

        $booking= DB::table('bookings')->where('id' , '=' , $request->id)->update($data);
         
        return redirect('calendar')->with('error','Updated successfully')->with('class','success');
    }
 

    // Delete a booking
    public function destroy($id)
    {
        
        
        $delete = DB::table('bookings')->where('id',$id)->delete();
        
        return redirect()->back()->with('error','Deleted successfully')->with('class','success');


    }


    public function BookingCreate($id)
    {
        $pagetitle='Create Booking';
        $leads = DB::table('leads')->where('id',$id)->get();
        $party = DB::table('party')->get();
        $supplier = DB::table('supplier')->get();
        $user = DB::table('user')->get();
 
 
        return view ('booking.booking_create',compact('leads','pagetitle','party','supplier','user'));


    }


    public  function BookingSave(request $request)
    {
  
 
// echo date("Y-m-d H:i:s", strtotime($request->start)) . "\n";
// echo date("Y-m-d H:i:s", strtotime($request->end)) . "\n";

//  dd($request->all());

              $data = array(
       

              "lead_id" => $request->lead_id,
              "title" => $request->title,
              "start" => date("Y-m-d H:i:s", strtotime($request->start)),
              "end" => date("Y-m-d H:i:s", strtotime($request->end)),
              "color" => $request->color,
              "agent_name" => $request->agent_name,
              "PartyID" => $request->PartyID,
              "client_contact" => $request->client_contact,
              "client_address" => $request->client_address,
              "SupplierID" => $request->SupplierID,
              "vendor_cost" => $request->vendor_cost,
              "input_vat" => $request->input_vat,
              "cnc_cost" => $request->cnc_cost,
              "output_vat" => $request->output_vat,

              "profit" => $request->profit,
              "net_invoice" => $request->net_invoice,
              
              "services" => $request->services,
              "payment_status" => $request->payment_status,
              "collected_by" => $request->collected_by,
              "amount" => $request->amount,
              "remarks" => $request->remarks,

              "users_id" => $request->user_id,
 
        );





        if ($request->hasFile('file')) {

            $this->validate($request, [
    
             // 'file' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,pdf|max:1000',
               'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,docx|max:2048',

                ] );

              $file = $request->file('file');

                $storagePath = 'public/uploads';
                 $path = $file->store($storagePath);
                 $filename_file = basename($path);


            $data = Arr::add($data, 'file',  $filename_file);


        }

         if ($request->hasFile('invoice_file')) {

         $this->validate($request, [
    
         // 'file' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,pdf|max:1000',
           'invoice_file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,docx|max:2048',

            ] );

           $invoice_file = $request->file('invoice_file'); 
           $storagePath = 'public/uploads';
                 $path = $invoice_file->store($storagePath);
                 $filename_invoice = basename($path);


            $data = Arr::add($data, 'invoice_file',  $filename_invoice);

        }
      

      
    

        $booking_id= DB::table('bookings')->insertGetId($data);



       


 
     return redirect ('Booking')->with('error', 'booking saved.')->with('class','success');







        
    }


        public  function BookingEdit($id)
        {
             $pagetitle='Create Booking';
            $booking = DB::table('bookings')->where('id',$id)->get();
              $party = DB::table('party')->get();
        $supplier = DB::table('supplier')->get();
        $user = DB::table('users')->get();
              
            
        return view ('booking.booking_edit',compact('booking','pagetitle','party','supplier','user'));
        }






    public  function BookingUpdate1(request $request)
    {
     
                  


      $data = array(
       

              // "lead_id" => $request->lead_id,
              "title" => $request->title,
              "start" => date("Y-m-d H:i:s", strtotime($request->start)),
              "end" => date("Y-m-d H:i:s", strtotime($request->end)),
              "color" => $request->Color,
              "agent_name" => $request->agent_name,
              "PartyID" => $request->PartyID,
              "client_contact" => $request->client_contact,
              "client_address" => $request->client_address,
              "SupplierID" => $request->SupplierID,
              "vendor_cost" => $request->vendor_cost,
              "input_vat" => $request->input_vat,
              "cnc_cost" => $request->cnc_cost,
              "output_vat" => $request->output_vat,

              "profit" => $request->profit,
              "net_invoice" => $request->net_invoice,

              
              "services" => $request->services,
              "payment_status" => $request->payment_status,
              "collected_by" => $request->collected_by,
              "amount" => $request->amount,
              "remarks" => $request->remarks,

              "users_id" => $request->user_id,
 
        );





        if ($request->hasFile('file')) {

            $this->validate($request, [
    
             // 'file' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,pdf|max:1000',
               'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,docx|max:2048',

                ] );

              $file = $request->file('file');

                $storagePath = 'public/uploads';
                 $path = $file->store($storagePath);
                 $filename_file = basename($path);


            $data = Arr::add($data, 'file',  $filename_file);


        }

         if ($request->hasFile('invoice_file')) {

         $this->validate($request, [
    
         // 'file' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,pdf|max:1000',
           'invoice_file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,docx|max:2048',

            ] );

           $invoice_file = $request->file('invoice_file'); 
           $storagePath = 'public/uploads';
                 $path = $invoice_file->store($storagePath);
                 $filename_invoice = basename($path);


            $data = Arr::add($data, 'invoice_file',  $filename_invoice);

        }
      

      
    

        
        $del = DB::table('journal')->where('BookingID',$request->id)->delete();
        
 

        
        $booking= DB::table('bookings')->where('id', $request->id)->update($data);
        



       



                 return redirect ('calendar')->with('error', 'updates saved.')->with('class','success');




    }



      public  function BookingPayment ()
        {
             
          $pagetitle='Booking Payments';

$datetime=Carbon::now();
       
// PHP program to compare dates 
  
// Declare two dates in different 
// format 
$current_date_time = now();
 
  // echo $current_date_time;
// Use strtotime() function to convert 
// date into dateTimestamp 
$dateTimestamp1 = strtotime($current_date_time); 

  
 // 1716883614
 // 1716898680

          // $booking = DB::table('v_booking')->select('id','title','start','end','collected_by','PartyName','services','SupplierName','name','file','invoice_file',DB::raw('UNIX_TIMESTAMP(end1)'))
          // ->where(DB::raw('UNIX_TIMESTAMP(end1)'),'<',$dateTimestamp1)
          // ->get();


              $booking = DB::table('v_booking')->where('end','<',Carbon::now())->where('status','Pending')->get();
              // $booking = v_booking::where('created_at', '<', Carbon::now())->get();



 
//where('end','>', now())
          //-

          return view ('booking.booking_payment',compact('pagetitle','booking','datetime'));

         }


     public  function BookingStatus ($id)
        {
             
           

          $data = array('status' => 'Completed' );
           
           $id= DB::table('bookings')->where('id',$id)->update($data);
           

          return redirect ('BookingPayment');

         }


      public  function BookingView ($id)
        {
             
           
          $pagetitle='Booking View';
            
           $booking= DB::table('v_bookings_admin')->where('id',$id)->get();
 
          return view ('booking.booking_view',compact('booking','pagetitle'));

         }






}