<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shirka extends Model
{
    use HasFactory;

    protected $table = 'shirka';
    // protected $primaryKey = 'InvoiceMasterID';
    
    protected $fillable = [
      'shirka_name',
      'logo',
         
      ];
    
    public $timestamps = false;
     
    
}
