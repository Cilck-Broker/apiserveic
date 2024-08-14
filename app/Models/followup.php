<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class followup extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $table = 'orders_followup';
    protected $primaryKey = 'orders_followup_id';

    protected $fillable = [
        // Add other fields as needed in the same format
        'orders_followup_id',
        'orders_followup_key',
        'orders_id',
        'orders_followup_date',
        'orders_followup_comment',
        'orders_followup_active',
        'orders_followup_by',
        'orders_followup_remind_date',
        'orders_followup_remind_note',
        'create_date'
    ];
}
