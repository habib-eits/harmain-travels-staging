<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pnr extends Model
{
    use HasFactory;

    protected $table = 'pnr';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'branch_id',
        'UserID',
        'pnr',
        'FlightNoDeparture',
        'SectorDeparture',
        'FlightDateDeparture',
        'FlightTimeDeparture',
        'FlightDateArrivalDeparture',
        'FlightTimeArrivalDeparture',
        'FlightNoReturn',
        'SectorReturn',
        'FlightDateReturn',
        'FlightDepartureTimeReturn',
        'FlightArrivalDateReturn',
        'FlightArrivalTimeReturn',
    ];

    // Example relationship: if a PNR belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    // Example: if branch info is stored in a branches table
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
