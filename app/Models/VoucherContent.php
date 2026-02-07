<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherContent extends Model
{
    use HasFactory;

    protected $table = 'voucher_content';
    
    
    protected $fillable = [
      'description',
      'description_urdu',
      'contact1',
      'contact2',
      'contact3',
      'contact4',
      'contact5',
      'contact6',
      'BranchID',
         
      ];
    
    public $timestamps = false;
     
    

}
