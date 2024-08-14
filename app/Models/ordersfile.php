<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ordersfile extends Model
{
    protected $table = 'orders_file';
    protected $primaryKey = 'orders_file_id';
    public $timestamps = false; // If your table doesn't have timestamps

    // Other model properties and methods...
    protected $fillable = [
        'orders_id',
        'orders_file_path',
        'orders_file_name',
        'orders_file_note',
        'active',
        'create_date',
        'create_by',
        'orders_file_type',
    ];
}