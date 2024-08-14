<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userlog extends Model
{
    protected $table = 'user_log';
    protected $primaryKey = 'user_log_id';
    public $timestamps = false; // If your table doesn't have timestamps

    // Other model properties and methods...
    protected $fillable = [
        'log_type',
        'log_detail',
        'ip_address',
        'page',
        'create_date',
        'user_code', 
    ];
}