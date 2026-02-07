<?php

namespace App\Models;

use session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'location',
        'email',
        'tel',
        'logo',
        'is_active',
    ];


    public static function getBranchList()
    {
        // session::get('BranchID');
        if(session::get('UserType') == 'SuperAdmin'){
            return self::all();
        }
        else{
            return self::where('id', session::get('BranchID'))->get();
        }
        
    }
}



