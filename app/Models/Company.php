<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // Specify the table name
    protected $table = 'company';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'Name',
        'Name2',
        'TRN',
        'Currency',
        'Mobile',
        'Contact',
        'Email',
        'Website',
        'Address',
        'Logo',
        'BackgroundLogo',
        'Signature',
        'BankDetail',
        
    ];

    // Optionally, specify the primary key if not 'id'
    protected $primaryKey = 'CompanyID';

    // Timestamps are enabled by default (created_at, updated_at)


    


}