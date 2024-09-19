<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Personnel;

class AuthenticateAPI
{
    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader || !preg_match('/Basic\s(\S+)/', $authorizationHeader, $matches)) {
            return response()->json(['message' => 'Authorization header not found or invalid'], 401);
        }

        // ถอดรหัส Base64
        $credentials = base64_decode($matches[1]);

        // แยก username และ password
        list($username, $password) = explode(':', $credentials, 2);

        // ค้นหาผู้ใช้จากตาราง MyUser โดยใช้ username
        $user = Personnel::where('personnel_code', $username)->first();

        // ตรวจสอบว่าพบผู้ใช้และรหัสผ่านตรงกันหรือไม่
        if ($user && $user->personnel_password === $password) {
            // ให้คำร้องขอผ่านไปยังขั้นตอนต่อไป
            return $next($request);
        }

        // ส่ง response กลับถ้าการยืนยันตัวตนล้มเหลว
        return response()->json(['message' => 'Authentication failed.'], 401);
    }
}
