<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    public $timestamps = false; // If your table doesn't have timestamps

    // Other model properties and methods...
    protected $fillable = [
        'invoice_number',
        'orders_id',
        'renews_start_date',
        'renews_end_date',
        'certificate_number',
        'email_status',
        'email_date',
        'email_by',
        'package_id',
        'package_name',
        'package_netpremium',
        'package_duty',
        'package_tax',
        'package_totalpremium',
        'package_discount',
        'package_premiumafterdisc',
        'invoice_status',
        'invoice_date',
        'invoice_by',
        'payment_method_id',
        'payment_net',
        'patment_vat',
        'payment_amount',
        'broker_account_id',
        'payment_status_id',
        'active',
        'file_path',
        'file_name',
        'remark',
        'mobile_imei',
        'create_date',
        'payment_id',
        'payment_expire',
    ];
}   