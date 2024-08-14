<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Support\Facades\Session as FacadesSession;
use App\Models\agents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\userlog;
use Carbon\Carbon;

class AuthController extends Controller
{

    public function login(Request $request)
        {            
            $agnetCode = $request->input('agnet_code');
            $agentPassword = $request->input('agent_password');


            $agents = agents::where('agnet_code', $agnetCode)
                        ->where('agent_status', 'Y')
                        ->first();
            
            if (Auth::attempt(['agnet_code' => $agnetCode, 'password' => $agentPassword, 'agent_status' => 'Y' ])) {

                $this->setUserSession($request, $agents);

                // $UserLog = userlog::create([
                //     'log_type' => "Login Page",
                //     'log_detail' => $agents->agnet_code,
                //     'ip_address' => $request->getClientIp(),
                //     'page' => "Login",
                //     'create_date' => Carbon::now()->toDateTimeString(),
                // ]);
                $log = array();
                $log = [ 
                    "log_type" => 'Login Page',
                    "log_detail" => $request->agnet_code." Login",
                    "page" => 'Login'
                
                ];
                $this->userlog($log);

                
                return response()->json(['message' => 'Login successful', 'code' => 200]);
            } else {
                session()->flash('message', 'ไม่สามารถเข้าสู่ระบบได้ กรุณาลองใหม่อีกครั้ง!!!');
                return response()->json(['message' => 'Invalid credentials', 'code' => 401]);
            }            
        }

        private function setUserSession($request , $user) : void 
        {

            $data = null;
            $data['agent_id'] = $user->agent_id;
            $data['agnet_code'] = $user->agnet_code;
            $data['agent_password'] = $user->agent_password;
            $data['agent_firstname'] = $user->agent_firstname;
            $data['agent_lastname'] = $user->agent_lastname;
            $data['agent_rule'] = $user->agent_rule;
            $data['agent_email'] = $user->agent_email; 


            //set session
            session(['User' => $data]);
    
        }

        public function logout(Request $request): RedirectResponse 
        {
            $log = array();
            $log = [ 
                    "log_type" => 'Login Out',
                    "log_detail" => auth()->user()->agnet_code." LogOut",
                    "page" => 'Login',
                
                ];
            $this->userlog($log);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        }
        
        public function userlog($log){
            // dd(request()->ip());
            $UserLog = userlog::create([
                'log_type' => $log["log_type"],
                'log_detail' => $log["log_detail"],
                'ip_address' => request()->ip(),
                'page' => $log["page"],
                'create_date' => Carbon::now()->toDateTimeString(),
                'user_code' => auth()->user()->agnet_code,
            ]);
        }

}

