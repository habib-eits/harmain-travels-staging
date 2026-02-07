<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelTariff extends Model
{
    use HasFactory;

    protected $table = 'hotel_tariffs';

    protected $fillable = [
        'location',
        'sr_no',
        'room_type',
        'date_from',
        'date_to',
        'room_status',
        'purchase_price',
        'sale_price',
        'triple_purchase',
        'triple_sale',
        'double_purchase',
        'double_sale',
        'quint_purchase',
        'quint_sale',
        'quad_purchase',
        'quad_sale',
        'package_name',
        'hotel_name',
        'PartyID',
        'BranchID',
        'is_active',
    ];

    
}
