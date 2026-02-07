<?php

namespace App\Models;

use session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceMaster extends Model
{
    use HasFactory;

    protected $table = 'invoice_master';

    protected $primaryKey = 'InvoiceMasterID';


    protected $fillable = [
        'InvoiceTypeID',
        'BranchID',
        'LeadID',
        'Date',
        'PartyID',
        'GroupNo',
        'UserID',
        'Note',
        'Total',
        'Paid',
        'Balance',

        // new additional fields
        'RefNo',
        'package_id',
        'sub_agent',
        'FlightPNR',
        
        'FlightNoDeparture',
        'SectorDeparture',
        'FlightDateDeparture',
        'FlightTimeDeparture',
        'FlightDateArrivalDeparture',
        'FlightTimeArrivalDeparture',
        
        'FlightNoReturn',
        'SectorReturn',
        'FlightDateReturn',
        'FlightDepartureTimeReturn',
        'FlightArrivalDateReturn',
        'FlightArrivalTimeReturn',
       
        'ExSaleRate',
        'SaleCurrency',
        'TicketCurrency',
        'ExPurchaseRate',
        'PurchaseCurrency',
        'FlightNights',

        
        'Validity',
        'CareOf',
        'VisaSupplierID',
        'TicketSupplierID',
        'InvoiceStatus',
        'Remarks',
        'RevisedDate',
        'RevisedRemarks',

        'is_active',
    ];


    public $timestamps = false;

    public static function getBranchList()
    {
        // session::get('BranchID');
        if(session::get('UserType') == 'SuperAdmin'){
            return self::all();
        }
        else{
            return self::where('id', session::get('BranchID'))->get();
        }
        
    }

    public function package()
    {
        return $this->belongsTo('App\Models\Packages', 'package_id');
    }

    public function party()
    {
        return $this->belongsTo('App\Models\Party', 'PartyID');
    }

    public function passanger()
    {
        return $this->hasMany('App\Models\UmrahInvoicePassenger','umrah_invoice_master_id');
    }

    public function headPassenger()
{
    return $this->hasOne('App\Models\UmrahInvoicePassenger', 'umrah_invoice_master_id', 'InvoiceMasterID')
                ->where('relation_type', 'Head');
}
    
    public function hotel()
    {
        return $this->hasMany('App\Models\InvoiceHotel','InvoiceMasterID');
    }
    
    public function transport()
    {
        return $this->hasMany('App\Models\InvoiceTransport','InvoiceMasterID');
    }
    


}
