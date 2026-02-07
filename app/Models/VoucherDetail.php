<?php

namespace App\Models;

use session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherDetail extends Model
{
    use HasFactory;

    protected $table = 'voucher_detail';

    protected $primaryKey = 'VoucherDetID';


    protected $fillable = [
        
        'VoucherMstID',
        'BranchID',
        'Date',
        'Voucher',
        'ChOfAcc',
        'PartyID',
        
        'Narration',
        'InvoiceNo',
        'RefNo',
        'Debit',
        'Credit',
        
        
        
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
        return $this->belongsTo('App\Models\InvoiceMaster', 'InvoiceNo','InvoiceMasterID' );
    }
    
    

}



