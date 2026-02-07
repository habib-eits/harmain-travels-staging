<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaCategory extends Model
{
    use HasFactory;

    protected $table = 'visa_category';

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

    public $timestamps = false;

    
}
