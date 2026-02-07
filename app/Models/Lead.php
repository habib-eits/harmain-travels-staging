<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    
    use HasFactory;
    protected $guarded = [];

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function agent(){
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function leadDetails() {
        return $this->hasMany(LeadDetails::class, 'lead_id');
    }
    public function branchService(){
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function subService(){
        return $this->belongsTo(SubService::class, 'sub_service_id');
    }
    public function campaign(){
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
