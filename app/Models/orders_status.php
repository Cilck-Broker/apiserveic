<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders_status extends Model
{
    use HasFactory;

    protected $table = 'orders_status';
    protected $primaryKey = 'orders_status_id';
}
