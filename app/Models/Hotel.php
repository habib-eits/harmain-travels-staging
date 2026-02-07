<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    // Specify the table name
    protected $table = 'hotels';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'hotel_name',
        'location',
        'BranchID',
        'is_active'
    ];

    // Optionally, specify the primary key if not 'id'
    protected $primaryKey = 'id';

    // Timestamps are enabled by default (created_at, updated_at)


     static function getHotelList()
        {
            $hotel = Hotel::query()             
                ->orderby('hotel_name', 'asc')
                ->get();

        

            return $hotel;
        }



        
}