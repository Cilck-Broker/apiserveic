<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyUser extends Model
{
    // กำหนดชื่อตาราง
    protected $table = 'My_User';

    // กำหนด Primary Key
    protected $primaryKey = 'User_ID';

    protected $connection = 'Conn_mssql';

    // ปิดการใช้ Timestamps (created_at, updated_at)
    public $timestamps = false;

    // กำหนดฟิลด์ที่สามารถใส่ค่าได้ (fillable)
    protected $fillable = [
        'User_ID',
        'User_Title',
        'User_FName',
        'User_LName',
        'User_Position_ID',
        'User_Dept_ID',
        'User_Phone',
        'User_Email',
        'User_Password',
        'User_Group_ID',
        'User_Line_ID',
        'User_Extension',
        'User_License_No',
        'User_License_Epr_Date',
        'User_Note_I',
        'User_Note_II',
        'Remark',
        'Active',
        'Create_Date',
        'Create_By',
        'Update_Date',
        'Update_By',
    ];

    // กำหนดฟิลด์ที่เป็นวันที่
    protected $dates = [
        'User_License_Epr_Date',
        'Create_Date',
        'Update_Date',
    ];

    // ถ้าคุณต้องการแปลงข้อมูลบางอย่างอัตโนมัติ เช่น การเข้ารหัสรหัสผ่าน
    public function setUserPasswordAttribute($value)
    {
        $this->attributes['User_Password'] = bcrypt($value);
    }
}
