<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCheckpriceApi extends Model
{
    use HasFactory;

    protected $table = 'log_checkprice_api';
    protected $primaryKey = 'log_id';
    protected $connection = 'Conn_mysql';
    
    protected $fillable = [
        'dates', 
        'page', 
        'package', 
        'insurercode', 
        'class', 
        'repairs', 
        'make', 
        'model', 
        'year', 
        'cc', 
        'register', 
        'ipaddress', 
        'agent'
    ];

    public $timestamps = false;
}
