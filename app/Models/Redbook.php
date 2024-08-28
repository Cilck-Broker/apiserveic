<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redbook extends Model
{
    // กำหนดชื่อตาราง
    protected $table = 'ck_redbook';

    // กำหนด Primary Key
    protected $primaryKey = 'redbookid';

    protected $connection = 'Conn_mysql';

    // ปิดการใช้ Timestamps (created_at, updated_at)
    public $timestamps = false;

    // กำหนดฟิลด์ที่สามารถใส่ค่าได้ (fillable)
    protected $fillable = [
        'redbook_tks_yeargroup',
        'redbook_tks_make',
        'redbook_tks_model',
        'redbook_tks_cc',
        'redbook_tks_longdesc',
        'redbook_tks_goodretail',
        'redbook_tks_age',
        'redbook_tks_group'
    ];
}
