<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use File;
use App\Models\orders;
use App\Models\orders_status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendingEmailComfirm;
use App\Mail\SendingEmailRenew;
use App\Models\userlog;

class scheduleController extends Controller
{
    public function sendEmailRenew(){
        $endDate = Carbon::now()->addDays(3)->format('Y-m-d');        
        $orders = orders::whereRaw("coverage_end = '".$endDate."' AND status IN ('S0001',  'S0002')")->get('orders_id');
        
        foreach ($orders as $key => $order) {
            $orderId = $order->orders_id;

            $orders = orders::find($orderId);
    
            //   สร้างข้อความอีเมล์
            $orders = DB::select("SELECT ord.*, pack.*, pr.package_rate_id, pr.netpremium, duty, tax, totalpremium, orders_status.orders_status_desc
            FROM orders ord
            LEFT JOIN packages_rate pr ON pr.secondhand_price_low <= ord.second_hand_price AND pr.secondhand_price_high >= ord.second_hand_price
            LEFT JOIN packages pack ON pack.packages_id = pr.package_id 
            LEFT JOIN orders_status ON ord.status = orders_status.orders_status_key
            WHERE orders_id = '$orderId'");
            $ordersArr = json_decode(json_encode($orders[0]), true);

            $recipientName = "คุณ " . $ordersArr['customer_firstname'] . " " . $ordersArr['customer_lastname'];
            $recipientEmail = 'pongtep.p@clickbroker.co.th';

            $mobileDetails = $ordersArr['mobile_brand'] . " " . $ordersArr['mobile_model'] . " " . $ordersArr['mobile_memory'] . " " . $ordersArr['mobile_color'];
            $coverageEnd = $ordersArr['coverage_end'];

            $log = array();
            $log = [ 
                        "log_type" => 'Sent Email Renew',
                        "log_detail" => "Senting Email Renew ID: ".$orderId,
                        "page" => 'Edit Order',
                    
                    ];
            $this->userlog($log);

            $data = [
                'title' => 'TestTest PDF',
                'content' => 'เนื้อหาของ PDF ที่คุณต้องการแปลง',
                'recipientName' => $recipientName,
                'recipientEmail' => $ordersArr['customer_email'],
            ];
            
            $filename =  'renewiphone_'.$ordersArr['customer_phone'].'_'.rand(1, 1000).'.pdf';
            $pdf = PDF::loadView('pdf.renewcustomer', $ordersArr);
            $pdfPath = storage_path('app/pdf/'.$filename); // ตำแหน่งที่บันทึกไฟล์ PDF
            $pdf->save($pdfPath);
            $pdfContent = $pdf->output();
            $pdfPublicPath = 'pdf/'.$filename;
            Storage::disk('public')->put($pdfPublicPath, $pdfContent);

            $bodyEmail = view('emails.emailrenewscustomer', [
                'orderId' => $orderId,
                'recipientName' => $recipientName,
                'recipientEmail' => $ordersArr['customer_email'],
                'mobileDetails' => $mobileDetails,
                'coverageEnd' => $coverageEnd,
            ])->render();
            
            Mail::to($recipientEmail)
            ->bcc('teerakan.s@clickbroker.co.th')
            ->send(new SendingEmailRenew($recipientName, $recipientEmail, $bodyEmail, $mobileDetails, $coverageEnd, $filename, $orderId));

            unlink($pdfPath);

        
            // return "อีเมลถูกส่งไปยัง " . $recipientEmail;
        }
    }

    public function userlog($log){
        $UserLog = userlog::create([
            'log_type' => $log["log_type"],
            'log_detail' => $log["log_detail"],
            'ip_address' => request()->ip(),
            'page' => $log["page"],
            'create_date' => Carbon::now()->toDateTimeString(),
            'user_code' => 'auto',
        ]);
    }
}