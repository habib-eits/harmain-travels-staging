<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    // Specify the table name
    protected $table = 'room_type';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'room_type',
        
     ];

    // Optionally, specify the primary key if not 'id'
    protected $primaryKey = 'id';

    // Timestamps are enabled by default (created_at, updated_at)


    public $timestamp = false;



}