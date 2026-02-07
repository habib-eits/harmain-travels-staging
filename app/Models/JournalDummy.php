<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class JournalDummy extends Model
{
    use HasFactory;

    protected $table = 'journal_dummy';

    protected $primaryKey = 'JournalID';


    protected $fillable = [
        
        'BranchID',
        'JournalType',
        'VHNO',
        'Date',
        'ChartOfAccountID',
        'PartyID',
        'VoucherMstID',
        'ExpenseMasterID',
        'InvoiceMasterID',
        'Narration',
        'UserID',
        'Dr',
        'Cr',
        'Trace',
        'BankReconsile',

        'PaxName',
        'PassportNo',
        'Type',
        'VisaNo',
        'PNR',


        'PaxNumber',
        'City',

        'CheckIn',
        'CheckOut',
        'Nights',
        'HotelName',
        'RoomType',
        'HCN',

        'TransportDate',
        'Sector',
        'Vehicle',
        'Quantity',
        'Pickup',
        'PickFrom',
        'Destination',
        'BRN',
        'TCN',


        
        'Currency',
        'Rate',
        'ForeignAmount',
        'ForeignDebit',
        'ForeignCredit',
        
    ];



 



    public $timestamps = false;

    public static function getBranchList()
    {
        // session::get('BranchID');
        if(session()->get('UserType') == 'SuperAdmin'){
            return self::all();
        }
        else{
            return self::where('id', session()->get('BranchID'))->get();
        }
        
    }

    public function party()
    {
        return $this->belongsTo('App\Models\Party', 'PartyID');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'BranchID');
    }
    
    public function invoiceMaster()
    {
        return $this->belongsTo('App\Models\InvoiceMaster', 'InvoiceMasterID');
    }
    

}



