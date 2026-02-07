<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class InvoiceTransport extends Model
{
    use HasFactory;

    protected $table = 'umrah_invoice_transport';

    protected $fillable = [
        'InvoiceMasterID',
        'TransportDate',
        'TransportCity',
        'Sector',
        'VehicleType',
        'VehicleStatus',
        'Quantity',
        'TransportPax',
        'TransportPurchase',
        'TransportSale',
        'TransportPayable',
        'TransportReceivable',
        'Flight',
        'PickupTime',
        'PickFrom',
        'DestinationTo',
        'TransportBrnCode',
        'TCN',
        'SupplierID',
        'ExRatePurchaseTransport',
        'ExRateSaleTransport',
        'UserID',
        'BranchID',
    ];


    public $timestamps = false;

    protected $casts = [
         'TransportPurchase' => 'double',
        'TransportSale' => 'double',
        'TransportPayable' => 'double',
        'TransportReceivable' => 'double',
        'ExRatePurchaseTransport' => 'double',
        'ExRateSaleTransport' => 'double',
    ];

    public function invoiceMaster()
    {
        return $this->belongsTo(InvoiceMaster::class, 'InvoiceMasterID');
    }

    public function getTransportDateAttribute($value)
    {
    return Carbon::parse($value)->format('d-m-Y');
    }

     public function party()
    {
        return $this->hasMany(Party::class, 'PartyID', 'SupplierID');
    }




}



 