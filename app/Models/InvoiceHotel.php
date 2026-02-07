<?php

namespace App\Models;

use App\Models\Hotel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class InvoiceHotel extends Model
{
    use HasFactory;

    protected $table = 'umrah_invoice_hotel';

    protected $fillable = [
        'InvoiceMasterID',
        'HotelCity',
        'CheckInDate',
        'CheckOutDate',
        'Nights',
        'hotel_id',
        'RoomType',
        'RoomStatus',
        'NoOfRooms',
        'HotelPax',
        'HotelPurchase',
        'HotelSale',
        'HotelPayable',
        'HotelReceivable',
        'Origin',
        'Destination',
        'SupplierID',
        'StockStatus',
        'ExRatePurchaseHotel',
        'ExRateSaleHotel',
        'HCN_NO',
        'BRN',
        'UserID',
        'BranchID',
        'RoomView',
        'MealPlan',
    ];


    public $timestamps = false;

       protected $casts = [
        
        'Nights' => 'integer',
        'HotelPurchase' => 'double',
        'HotelSale' => 'double',
        'HotelPayable' => 'double',
        'HotelReceivable' => 'double',
        'ExRatePurchaseHotel' => 'double',
        'ExRateSaleHotel' => 'double',
    ];

    public function invoiceMaster()
    {
        return $this->belongsTo(InvoiceMaster::class, 'InvoiceMasterID');
    }

    public function getTransportDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function hotel_name()
    {
        return $this->belongsTo(Hotel::class,'hotel_id');
    }
    
    public function party()
    {
        return $this->belongsTo(Party::class,'SupplierID');
    }



}



 