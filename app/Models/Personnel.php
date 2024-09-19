<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    protected $table = 'ck_personnel'; // ชื่อตารางในฐานข้อมูล
    protected $primaryKey = 'personnel_id'; // กำหนด primaryKey ของตาราง
    protected $connection = 'Conn_mysql';
    
    protected $fillable = [
        'personnel_code', 'personnel_password', 'personnel_firstname', 'personnel_lastname',
        'personnel_extension', 'personnel_phone', 'personnel_email', 'personnel_line',
        'personnel_type', 'personnel_branch', 'personnel_status', 'personnel_workin', 'personnel_supervisor'
    ];

    public $timestamps = false; // ไม่ใช้ timestamps เช่น created_at และ updated_at
}
