<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'ml_provincial';
    protected $primaryKey = 'ID';

    protected $fillable = [
        
        'Name',
        'Status',
        
    ];


}


