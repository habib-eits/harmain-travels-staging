<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    protected $table = 'item';
    protected $primaryKey = 'ItemID';

      protected $fillable = [
        'ItemName',
       
    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'BranchID');
    }

}
