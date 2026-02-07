<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaMaster extends Model
{
    use HasFactory;

    protected $table = 'invoice_master';

    protected $primaryKey = "InvoiceMasterID";

    public $timestamps = false;


    protected $fillable = [
        'InvoiceTypeID',
        'Date',
        'DueDate',
        'PartyID',
        'UserID',
        'GroupNo',
        'Total',
    ];


    public function detail()
    {
        return $this->hasMany(VisaDetail::class, 'InvoiceMasterID', 'InvoiceMasterID');
    }


     public function party()
    {
        return $this->belongsTo('App\Models\Party', 'PartyID');
    }

}
