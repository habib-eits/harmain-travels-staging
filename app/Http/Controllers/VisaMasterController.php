<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Party;
use App\Models\Shirka;
use App\Models\Company;
use App\Models\Journal;
use App\Models\Packages;
use App\Models\VisaDetail;
use App\Models\VisaMaster;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VisaMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Visa";

         try{
            if ($request->ajax()) {
                        $data = VisaMaster::with(['party','detail'])->whereNull('SectorReturn')->orderBy('Date','desc')->get();

                return Datatables::of($data)

                    ->addColumn('PaxName', function ($row) {
                        // Combine all Pax names from details
                        return $row->detail->pluck('PaxName')->implode(', ');
                    })
                    ->addColumn('PartyName', function ($row) {
                        return $row->party->PartyName ?? '';
                    })
                   

                    ->addColumn('action', function ($row) {
                        $btn = '
                            <div class="d-flex align-items-center col-actions">
                                <div class="dropdown">
                                    <a class="action-set show" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="'.route('visa-master.print',$row->InvoiceMasterID).'" class="dropdown-item">
                                                <i class="mdi  mdi-printer font-size-16 text-secondary me-1"></i> Print
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a href="'.route('visa-master.edit',$row->InvoiceMasterID).'" class="dropdown-item">
                                                <i class="bx bx-pencil font-size-16 text-secondary me-1"></i> Edit
                                            </a>
                                        </li>
                                         <li>
                                            <a href="javascript:void(0)" onclick="deleteRecord(' . $row->InvoiceMasterID . ')" class="dropdown-item">
                                                <i class="bx bx-trash font-size-16 text-danger me-1"></i> Delete
                                            </a>
                                        </li>
                                       
                                       
                                    </ul>
                                </div>
                            </div>';
    
                   
                    return $btn;
                   
                    })
                    
                    
                    ->rawColumns(['action']) // Mark these columns as raw HTML
                    ->make(true);
            }

        
        
       
    
            return view('visa.index',compact('title'));

        }catch (\Exception $e){
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
        $party = Party::getPartyList();    
        $supplier = Party::getSupplierList();  
        $visaMaster = new VisaMaster;  
        $shirka = Shirka::all();
        $package=Packages::all();
         $user = User::getUserList();

        return view('visa.create',compact('party','supplier','visaMaster','shirka','package','user'));
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
            'Date'           => 'required|date',
           
        ], [
            'Date.required'   => 'Please enter the start date.',
            'Date.date'       => 'The start date must be a valid date.',
          
        ]);

        try {
            $data = $request->only([
                'Date',
                'PartyID',
                'UserID',
                'GroupNo',
                
            ]);

            $data['InvoiceTypeID'] = 1;

            
            $visaMaster =  VisaMaster::updateOrCreate(
                ['InvoiceMasterID' => $request->InvoiceMasterID],
                $data
            );
    
            return response()->json([
                'success' => true,
                'message' => 'Record added successfully.',
                'InvoiceMasterID' => $visaMaster->InvoiceMasterID
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
         
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $party = Party::getPartyList();    
        $supplier = Party::getSupplierList();  
        $visaMaster = VisaMaster::find($id);  
        $shirka = Shirka::all();
        $package=Packages::all();
        $user = User::getUserList();
        
        return view('visa.create',compact('party','supplier','visaMaster','shirka','package','user'));
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
    
    
    public function print($id)
    {
        $party = Party::getPartyList();    
        $supplier = Party::getSupplierList();  
        $invoice_master=VisaMaster::with(['party','detail'])->where('InvoiceMasterID',$id)->first();
        $invoice_detail=VisaDetail::where('InvoiceMasterID',$id)->get();

        $shirka = Shirka::all();
        $package=Packages::all();
        $user = User::getUserList();
        $company=Company::first();
        return view('visa.print',compact('party','supplier','shirka','package','user','company','invoice_master','invoice_detail'));
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            
            $visaMaster = VisaMaster::findOrFail($id);
            $visaMaster->delete();

            $visadetail = VisaDetail::where('InvoiceMasterID',$id)->delete();
            $visaJournal = Journal::where('InvoiceMasterID',$id)->delete();

            

            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the record.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


   


}
