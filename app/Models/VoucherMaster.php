<?php

namespace App\Models;

use session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherMaster extends Model
{
    use HasFactory;

    protected $table = 'voucher_master';

    protected $primaryKey = 'VoucherMstID';


    protected $fillable = [
        
        'VoucherCodeID',
        'BranchID',
        'Voucher',
        'Date',
        'Narration',
        'Amount',
        
        
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
        return $this->belongsTo('App\Models\InvoiceMaster', 'InvoiceMasterID');
    }
    

}



