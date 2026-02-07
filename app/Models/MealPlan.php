<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    protected $table = 'meal_plan';
    
    
    protected $fillable = [
      'meal_plan',
    
         
      ];
    
    public $timestamps = false;
     
    
}
