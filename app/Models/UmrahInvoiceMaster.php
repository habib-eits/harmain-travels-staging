<?php

namespace App\Models;

use App\Http\Controllers\UmrahInvoicePassengerController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmrahInvoiceMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_date',
        'client_name',
        'ref_no',
        'sub_agent',
        'package_name',
        'email',
        
        'dep_flight_no',
        'dep_sector',
        'dep_date',
        'dep_time',
        'dep_arr_date',
        'dep_arr_time',
        
        'ret_flight_no',
        'ret_sector',
        'ret_date',
        'ret_dep_time',
        'ret_arr_date',
        'ret_arr_time',
        
        'sale_rate',
        'sale_cur',
        'ticket_cur',
        'purchase_rate',
        'purchase_cur',
        'flight_nights',  
    ];

    // public function passengers()
    // {
    //     return $this->hasMany(UmrahInvoicePassenger::class);
    // }
}
