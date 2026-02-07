<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaType extends Model
{
    use HasFactory;

    protected $table = 'visa_type';

    protected $fillable = [
        
        'visa_type',
        'BranchID',
        'UserID',

     ];

    public $timestamps = false;

    
}
