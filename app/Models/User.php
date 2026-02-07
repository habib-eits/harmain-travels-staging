<?php

namespace App\Models;

use session;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;
    protected $table = 'user';
    protected $primaryKey = 'UserID';

    //   protected $fillable = [
    //     'ItemName',
       
    // ];


    
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'BranchID');
    }

 


    static function getUserList($user_type = null)
    {

        
        $user = User::query()
            ->when(session('UserType') != 'SuperAdmin', function ($query) {
                return $query->where('BranchID', session('BranchID'));
            })
            
            ->when(session('UserType') == 'SuperAdmin', function ($query) {
                return $query->where('UserType', '<>', 'SuperAdmin');
            })
            

           ->when(session('UserType')=='Saleman', function ($query) {
                // return $query->whereIn('UserType', ['Saleman']);
                return $query->where('UserID', session('UserID'));
            })
             
            ->get();

            if (session('UserType') == 'SuperAdmin' || session('UserType') == 'Admin') {
                $chooseOption = (object)[
                    'UserID' => '',
                     'FullName' => 'Choose...',
                 ];
                 $user = $user->prepend($chooseOption);
            }
     

        return $user;
    }





}
