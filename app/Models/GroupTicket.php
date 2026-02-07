<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTicket extends Model
{
    use HasFactory;

    protected $table = 'group_tickets'; // Change if your table name differs

    protected $primaryKey = 'GroupTicketID'; // Change according to your DB primary key

    public $timestamps = false; // Set true if you have created_at and updated_at columns
    
    public $incrementing = true;

    protected $fillable = [
        
        'VoucherType',
        'VoucherNo',
        
        'Date',
        'SupplierID',
        
        'PNR',
        'Sector',
        'DateOfDep',
        'DateOfArr',
        'AirlineName',
        'FlightNo',
        'TicketNo',
        'AirlineCode',
        'PaxName',
        'TicketPrice',
        'Fare',
        'Discount',
        'Quantity',
        'Payable',
        'Receivable',
        
        
        'PaymentDueDate',
        'PartyID',
        
        'ExRate',
        'CareOf',
        'Remarks'
    ];


//     protected $casts = [
//     'Date' => 'datetime',
// ];

    public function party()
    {
        return $this->belongsTo('App\Models\Party', 'PartyID');
    }


    public function supplier()
    {
        return $this->belongsTo('App\Models\Party', 'SupplierID');
    }

  

}
