<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function subService(){
        return $this->hasMany(SubService::class, 'service_id');
    }

}
