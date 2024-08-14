<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use File;
use App\Models\orders;
use App\Models\orders_status;
use Carbon\Carbon;
use App\Mail\SendingEmailCustomer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Invoices;


class PdfController extends Controller
{
    public function viewpdf($orderID){
        // $orderId = $request->orderid;
        // $orders = orders::find($orderID);
        // dd($orderID);
        $orders = DB::select("SELECT invoices.* , invoice_status.invoice_status_desc, orders.*
        FROM invoices 
        left join orders on invoices.orders_id = orders.orders_id
        LEFT JOIN  invoice_status on invoices.invoice_status = invoice_status.invoice_status_key 
        where  invoices.invoice_id  =  '$orderID' AND invoices.active = 'Y' ");
        $ordersArr = json_decode(json_encode($orders[0]), true);
// dd($ordersArr);
        $filename =  'renewiphone_'.$ordersArr['customer_phone'].'_'.rand(1, 1000).'.pdf';
        $pdf = PDF::loadView('pdf.comfirmcustomer', $ordersArr);  
    
        return $pdf->stream($filename); // ใช้ stream() เพื่อแสดงตัวอย่าง PDF โดยไม่ต้องบันทึกเป็นไฟล์
        //   $pdfPath = storage_path('app/pdf/'.$filename); // ตำแหน่งที่บันทึกไฟล์ PDF
        //   $pdf->save($pdfPath);
        //   $pdfContent = $pdf->output();
        
        return view('pdf.comfirmcustomer')->with($orders->toarray());

    }

    public function viewpdfrenew($orderID){
        $orders = orders::find($orderID);
        // dd($orderID);

        $orders = DB::select("SELECT ord.*, pack.*, pr.package_rate_id, pr.netpremium, duty, tax, totalpremium, orders_status.orders_status_desc
        FROM orders ord
        LEFT JOIN packages_rate pr ON pr.secondhand_price_low <= ord.second_hand_price AND pr.secondhand_price_high >= ord.second_hand_price
        LEFT JOIN packages pack ON pack.packages_id = pr.package_id 
        LEFT JOIN orders_status ON ord.status = orders_status.orders_status_key
        WHERE orders_id = '$orderID'");
        $ordersArr = json_decode(json_encode($orders[0]), true);
// dd($ordersArr);
        $filename =  'renewiphone_'.$ordersArr['customer_phone'].'_'.rand(1, 1000).'.pdf';
        $pdf = PDF::loadView('pdf.renewcustomer', $ordersArr);
    
        return $pdf->stream($filename); // ใช้ stream() เพื่อแสดงตัวอย่าง PDF โดยไม่ต้องบันทึกเป็นไฟล์
        //   $pdfPath = storage_path('app/pdf/'.$filename); // ตำแหน่งที่บันทึกไฟล์ PDF
        //   $pdf->save($pdfPath);
        //   $pdfContent = $pdf->output();
        
        return view('pdf.comfirmcustomer')->with($orders->toarray());

    }
}