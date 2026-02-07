<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportTariff extends Model
{
    use HasFactory;

    protected $table = 'transport_tariff';

    protected $fillable = [
        
        'date_from',
        'date_to',
        'sector', //JED-MAK-JED, 
        'vehicle_type', // BUS, CAR, H-1, HIACE
        'status',  // SHARING , PRIVATE
        'purchase_price',
        'sale_price',
        'PartyID', // TARIFF FOR 
        'SupplierID', // SUPPLIER
        'transport_category', // TRANSPORT, TOUR
        'BranchID',
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
