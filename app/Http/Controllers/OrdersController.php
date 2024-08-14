<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use File;
use App\Models\orders;
use App\Models\orders_status;
use Carbon\Carbon;
use App\Mail\SendingEmailComfirm;
use App\Mail\SendingEmailRenew;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Invoices;
use App\Models\userlog;
use App\Models\ordersfile;
use Intervention\Image\Facades\Image;

class OrdersController extends Controller
{
    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getOrderDetailById(Request $request){
        $orders_id = $request->get("orders_id");
        $orders = DB::SELECT("SELECT 	ord.*, 
                                    pack.packages_id,
                                    pack.package_code,
                                    pack.package_name,
                                    pr.package_rate_id, 
                                    pr.netpremium,
                                    duty,
                                    tax,
                                    totalpremium,
                                    orders_status.orders_status_desc
                            FROM orders ord
                            left join packages_rate pr on pr.secondhand_price_low <= ord.second_hand_price and pr.secondhand_price_high >= ord.second_hand_price
                            left join packages pack on pack.packages_id = pr.package_id 
                            left join orders_status on ord.status = orders_status.orders_status_key
                            WHERE orders_id = '$orders_id'");
        
        return response()->json($orders[0]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function DelOrdersFile(Request $request)
    {
        $id = $request->get('orders_file_id');
        $status = ordersfile::where('orders_file_id', $id)->delete();
        $arr = ["status" => "fali"];
        if($status){
            $arr = ["status" => "success"];
        }

        return response()->json($arr);
    }

    public function updateCustomerData(Request $request){
        // dd($request->get("formData")["orders_code"]);
        $orders_id = $request->orders_id;

        $orders = orders::find($orders_id);
        $orders->customer_firstname = $request->get("formData")["customer_firstname"];
        $orders->customer_lastname  = $request->get("formData")["customer_lastname"];
        $orders->customer_phone     = $request->get("formData")["customer_phone"];
        $orders->customer_idcard    = $request->get("formData")["customer_idcard"];
        $orders->customer_address   = trim($request->get("formData")["customer_address"]);
        $orders->customer_email     = $request->get("formData")["customer_email"];
        $orders->mobile_imei        = $request->get("formData")["mobile_imei"];
        
        $orders->save();


        return response()->json(['message' => $orders->orders_id, 'data' => $orders]);
    }


    public function sendEmailComfirm(Request $request) {
        $orderId = $request->orderid;
        // $orders = orders::find($orderId);
        $orders = DB::select("SELECT invoices.* , invoice_status.invoice_status_desc, orders.*
        FROM invoices 
        left join orders on invoices.orders_id = orders.orders_id
        LEFT JOIN  invoice_status on invoices.invoice_status = invoice_status.invoice_status_key 
        where  invoices.invoice_id  =  '$orderId' AND invoices.active = 'Y' ");

        $ordersArr = json_decode(json_encode($orders[0]), true);

// Access the properties as an array
// dd($ordersArr);
          // สร้างข้อความอีเมล์
        $recipientName = "คุณ " . $ordersArr['customer_firstname'] . " " . $ordersArr['customer_lastname'];

        if(env("APP_V") == 'stg'){
            $recipientEmail = 'supaporn.k@clickbroker.co.th';
            $recipientbcc = 'teerakan.s@clickbroker.co.th';
        }else{
            $recipientEmail = $ordersArr['customer_email'];
            $recipientbcc = auth()->user()->agent_email;
        }

        // $recipientEmail = 'pongtep.p@clickbroker.co.th';
        // $recipientEmail = $ordersArr['customer_email'];
        $mobileDetails = $ordersArr['mobile_brand'] . " " . $ordersArr['mobile_model'] . " " . $ordersArr['mobile_memory'] . " " . $ordersArr['mobile_color'];
        $coverageEnd = $ordersArr['coverage_end'];
        // dd($recipientName);
         // สร้าง PDF
        $data = [
            'title' => 'sendEmailComfirm',
            'content' => 'เนื้อหาของ PDF ที่คุณต้องการแปลง',
            'recipientName' => $recipientName,
            'recipientEmail' => $ordersArr['customer_email'],
        ];
        // dd($orders->toarray());
        $filename =  'comfirmiphone_'.$ordersArr['customer_phone'].'_'.rand(1, 1000).'.pdf';
        $pdf = PDF::loadView('pdf.comfirmcustomer', $ordersArr); 
        $pdfPath = storage_path('app/pdf/'.$filename); // ตำแหน่งที่บันทึกไฟล์ PDF
        $pdf->save($pdfPath);
        $pdfContent = $pdf->output();
        $pdfPublicPath = 'pdf/'.$filename;
        Storage::disk('public')->put($pdfPublicPath, $pdfContent);


        // สร้างข้อความอีเมล์
        $bodyEmail = view('emails.emailcomfirmcustomer', [
            'recipientName'     => $recipientName,
            'recipientEmail'    => $ordersArr['customer_email'],
            'mobileDetails'     => $mobileDetails,
            'coverageEnd'       => $coverageEnd,
            'pdfPath'       => $filename,
        ])->render();
        
        Mail::to($recipientEmail)
        ->bcc($recipientbcc)
        ->send(new SendingEmailComfirm($recipientName, $recipientEmail, $bodyEmail, $mobileDetails, $coverageEnd, $filename, $recipientbcc, $filename));
        unlink($pdfPath);
        return "อีเมลถูกส่งไปยัง " . $recipientEmail;
    }

    public function sendEmailRenew(Request $request) {
        $orderId = $request->orderid;
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
        
        if(env("APP_V") == 'stg'){
            $recipientEmail = 'supaporn.k@clickbroker.co.th';
            $recipientbcc = 'teerakan.s@clickbroker.co.th';
        }else{
            $recipientEmail = $ordersArr['customer_email'];
            $recipientbcc = auth()->user()->agent_email;
        }
  
        $mobileDetails = $ordersArr['mobile_brand'] . " " . $ordersArr['mobile_model'] . " " . $ordersArr['mobile_memory'] . " " . $ordersArr['mobile_color'];
        $coverageEnd = $ordersArr['coverage_end'];
        // dd($coverageEnd);

        $log = array();
        $log = [ 
                    "log_type" => 'Sent Email Renew',
                    "log_detail" => "Senting Email Renew ID: ".$orderId,
                    "page" => 'Edit Order',
                
                ];
        $this->userlog($log);

         // สร้าง PDF
        $data = [
            'title' => 'TestTest PDF',
            'content' => 'เนื้อหาของ PDF ที่คุณต้องการแปลง',
            'recipientName' => $recipientName,
            'recipientEmail' => $ordersArr['customer_email'],
        ];
        // dd($ordersArr);
        
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
        ->bcc($recipientbcc)
        ->send(new SendingEmailRenew($recipientName, $recipientEmail, $bodyEmail, $mobileDetails, $coverageEnd, $filename, $orderId, $recipientbcc));
        // ->attach($pdfPath, [
        //     'as' => 'document111.pdf', // ชื่อไฟล์ที่แนบ
        //     'mime' => 'application/pdf', // ประเภทของไฟล์
        // ]);
        unlink($pdfPath);

        $arr = ["message" => "success"];
        
        return $arr;
    }


    public function btnCreateInvoice(Request $request){
        $CountInvoice = DB::SELECT("SELECT  COUNT(invoice_id) As invoice_count
                                    FROM    Invoices
                                    WHERE   EXTRACT(YEAR FROM create_date) = EXTRACT(YEAR FROM CURRENT_DATE)
                                            AND EXTRACT(MONTH FROM create_date) = EXTRACT(MONTH FROM CURRENT_DATE)
                                            AND EXTRACT(DAY FROM create_date) = EXTRACT(DAY FROM CURRENT_DATE)  ");

        $arrData = $request->get("formData");
        $number = intval($CountInvoice[0]->invoice_count)+1;


        $invoice_number = "RN" . date("ymd") . "" . sprintf("%03d", substr($number, 0, 3));

        $certificate_number = "CN" . date("ym") . "" . sprintf("%06d", substr($number, 0, 6));

        $Invoice = Invoices::create([
            'invoice_number'    => $invoice_number,
            'orders_id'         => $arrData["md_order_id"],
            'renews_start_date' => $arrData["md_renews_start_date"],
            'renews_end_date'   => $arrData["md_renews_end_date"],
            'certificate_number' => $certificate_number,
            'email_status'      => "",
            'email_date'        => null,
            'email_by'          =>"",
            'package_id'        => $arrData["package_id"],
            'package_name'      => $arrData["package_name"],
            'package_netpremium' => $arrData["package_netpremium"],
            'package_duty'      => $arrData["package_duty"],
            'package_tax'       => $arrData["package_tax"],
            'package_totalpremium' => $arrData["package_totalpremium"],
            'package_discount'  => $arrData["package_discount"],
            'package_premiumafterdisc' => $arrData["package_premiumafterdisc"],
            'invoice_status'    => "001",
            'invoice_date'      => Carbon::now()->toDateString(),
            'invoice_by'        => auth()->user()->agnet_code,
            'payment_method_id' => "",
            'payment_net'       => 0,
            'patment_vat'       => 0,
            'payment_amount'    => 0,
            'broker_account_id' => "",
            'payment_status_id' => "",
            'active'            => "Y",
            'file_path'         => "",
            'file_name'         => "",
            'remark'            => "",
            'mobile_imei'       => $arrData["md_mobile_imei"],
            'create_date'       => Carbon::now()->toDateTimeString(),
            'payment_expire'    => $arrData["md_exp_paid_date"],
        ]);
  

        if ($Invoice) {
            $orders = orders::find($arrData["md_order_id"]);
    
            if ($orders->status != 'S0005' && $orders->status != 'S0004') {
                $orders->status = "S0003";
                $orders->update_date = Carbon::now()->toDateTimeString();
                $orders->update_by = auth()->user()->agnet_code;
                $orders->save();

            }

            return response()->json(['message' => $Invoice->invoice_id], 201);
        } else {
            return response()->json(['message' => ''], 500);
        }
    }

    public function CancelInvoice(Request $request){
        $invoice_id = $request->invoice_id;
        
        $Invoices = Invoices::find($invoice_id);
        // dd($Invoices->invoice_number);
        $Invoices->invoice_status = "003";
        $Invoices->remark = $Invoices->remark."\n cancel:".Carbon::now()->toDateString()." By:".auth()->user()->agnet_code;
        $Invoices->save();


        return response()->json(['message' => $Invoices->invoice_id, 'code' => 200]);
    }
    

    public function userlog($log){
        $UserLog = userlog::create([
            'log_type' => $log["log_type"],
            'log_detail' => $log["log_detail"],
            'ip_address' => request()->ip(),
            'page' => $log["page"],
            'create_date' => Carbon::now()->toDateTimeString(),
            'user_code' => auth()->user()->agnet_code,
        ]);
    }


    public function uploadFileOrder(Request $request){

        $folderPath = date('Ym');

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        // if (!Storage::exists('public/' .$folderPath)){
        //     Storage::makeDirectory('public/' .$folderPath);
        // }
        
        $file = $request->file('uploadfilename');
        $randomNumber = rand(1000, 9999);
        $fileName = date('Ymd') . "_" .$randomNumber."_".$request->ul_order_id . '.' . $file->getClientOriginalExtension();

        $allowedExtensions = ['pdf', 'png', 'jpg', 'jpeg'];
        if (in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {

            $image = Image::make($file);
            $image->resize(650, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save($folderPath . '/' . $fileName);
            
            $ordersfile = ordersfile::create([
                'orders_id' => $request->ul_order_id,
                'orders_file_path' => $folderPath = date('Ym'),
                'orders_file_name' => $fileName,
                'orders_file_note' => $request->orders_file_note,
                'active' => "Y",
                'create_date' => Carbon::now()->toDateTimeString(),
                'create_by' => auth()->user()->agnet_code,
                'orders_file_type' => $request->orders_file_type,
            ]);
            // dd($ordersfile->toSql());
            if ($ordersfile) {
    
                return response()->json(['message' => "Upload File Complete", 'Code' => 200], 200);
            } else {
                return response()->json(['message' => "Upload File Complete", 'Code' => 500], 200);
            }

        } else {
            return response()->json(['message' => 'File Type: pdf, png, jpg, jpeg', 'Code' => 500], 200);
        }
   


    }


}
