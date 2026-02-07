<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmrahInvoicePassenger extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'umrah_invoice_master_id',
        'pnr',
        'visa_no',
        'visa_date',
        'visa_days',
        'passenger_name',
        'passport_no',
        'type',
        'gender',


        'dob',
        'contact',
        'relation',
        'visa_type',
        'nationality',


        'relation_type', //head or relation
        'shirka_id',
        'visa_sale',
        'ticket_sale',
        'visa_purchase',
        'ticket_purchase',
        'forex_purchase',
        'forex_sale',

        'UserID',
        'BranchID',


        'RoomView',
        'MealPlan',
    ];

    public $timestamps = false;




    public function invoiceMaster()
    {
              return $this->belongsTo('App\Models\InvoiceMaster', 'umrah_invoice_master_id');
  
    }
    
    
    public function shirka()
    {
              return $this->belongsTo('App\Models\Shirka', 'shirka_id');
  
    }

  


}
