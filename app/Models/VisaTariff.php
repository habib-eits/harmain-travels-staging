<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaTariff extends Model
{
    use HasFactory;

    protected $table = 'visa_tariff';

    protected $fillable = [
        
        'date_from',
        'date_to',
        
        'visa_type',
        'status',
        'purchase_price',
        'sale_price',
        'PartyID',
        'SupplierID',
        'visa_category',
        'BranchID',
        'UserID',

        'is_active',
    ];

     public function party()
    {
        return $this->belongsTo('App\Models\Party', 'PartyID');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Party', 'SupplierID');
    }
    

   
    
}
