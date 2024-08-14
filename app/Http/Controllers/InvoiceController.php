<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\orders;
use App\Models\orders_status;
use Session;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Session as FacadesSession;
use App\Models\Invoices;
use App\Models\ordersfile;
use App\Models\agents;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class InvoiceController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('invoice');
    }
    
    public function getDataInvoicePaid(Request $request){

        return view('invoice');
    }

    public function getInvoiceDataList(Request $request){

        $query = DB::table('payments_log')
                ->leftJoin('payments', 'payments_log.payment_code', '=', 'payments.payment_code')
                ->leftJoin('invoices', 'payments.invoice_id', '=', 'invoices.invoice_id')
                ->leftJoin('orders', 'invoices.orders_id', '=', 'orders.orders_id')
                ->select(   'payments_log.charge_id',
                        'invoices.invoice_number',
                        'invoices.mobile_imei',
                        'invoices.renews_start_date',
                        'invoices.renews_end_date',
                        DB::raw("COALESCE(invoices.package_totalpremium, 0) as package_totalpremium"),
                        DB::raw("COALESCE(invoices.payment_net, 0) as payment_net"),
                        DB::raw("COALESCE(invoices.patment_vat, 0) as patment_vat"),
                        DB::raw("COALESCE(invoices.package_premiumafterdisc, 0) as package_premiumafterdisc"),

                        DB::raw("COALESCE(orders.customer_firstname, '') as customer_firstname"),
                        DB::raw("COALESCE(orders.customer_lastname, '') as customer_lastname"),
                        DB::raw("COALESCE(orders.customer_idcard, '') as customer_idcard"),
                        DB::raw("COALESCE(orders.customer_address, '') as customer_address"),
                        DB::raw("COALESCE(orders.mobile_brand, '') as mobile_brand"),
                        DB::raw("COALESCE(orders.mobile_model, '') as mobile_model"),
                        DB::raw("COALESCE(orders.mobile_memory, '') as mobile_memory"),
                        DB::raw("COALESCE(orders.mobile_color, '') as mobile_color"),
                        DB::raw("COALESCE(orders.second_hand_price, 0) as second_hand_price"),
                        DB::raw("to_char(payments_log.created_at, 'YYYY-MM-DD') AS paid_date"),
                        DB::raw("to_char(orders.coverage_end, 'YYYY-MM-DD') AS coverage_end")
                        )
                ->where('invoices.invoice_status', '002')
                ->where('payments_log.transactions', '!=', null)
                ->where('payments_log.status', '=', 'successful');
        if(!empty($request->invoice_code)){
            $query->where('invoices.invoice_number', 'LIKE', "%{$request->invoice_code}%");
        }
    
        if(!empty($request->s_paid_date)){
            $query->whereRaw("to_char(payments_log.created_at, 'YYYY-MM-DD') >= ?", [$request->s_paid_date]);
        }
    
        if(!empty($request->e_paid_date)){
            $query->whereRaw("to_char(payments_log.created_at, 'YYYY-MM-DD') <= ?", [$request->e_paid_date]);
        }

        $query->orderBy('payments_log.created_at');
    
        // กำหนดจำนวนแถวต่อหน้า
        $perPage = $request->input('per_page', 20); // กำหนดค่าเริ่มต้นถ้าไม่ได้ส่งมา
    
        // ใช้ pagination
        $invoices = $query->paginate($perPage);
        return response()->json($invoices);
    }

    public function exportExcelInvoice(Request $request)
    {
        $query = DB::table('payments_log')
        ->leftJoin('payments', 'payments_log.payment_code', '=', 'payments.payment_code')
        ->leftJoin('invoices', 'payments.invoice_id', '=', 'invoices.invoice_id')
        ->leftJoin('orders', 'invoices.orders_id', '=', 'orders.orders_id')
        ->select(   'payments_log.charge_id',
                'invoices.invoice_number',
                'invoices.mobile_imei',
                'invoices.renews_start_date',
                'invoices.renews_end_date',
                DB::raw("COALESCE(invoices.package_totalpremium, 0) as package_totalpremium"),
                DB::raw("COALESCE(invoices.payment_net, 0) as payment_net"),
                DB::raw("COALESCE(invoices.patment_vat, 0) as patment_vat"),
                DB::raw("COALESCE(invoices.package_premiumafterdisc, 0) as package_premiumafterdisc"),

                DB::raw("COALESCE(orders.customer_firstname, '') as customer_firstname"),
                DB::raw("COALESCE(orders.customer_lastname, '') as customer_lastname"),
                DB::raw("COALESCE(orders.customer_idcard, '') as customer_idcard"),
                DB::raw("COALESCE(orders.customer_address, '') as customer_address"),
                DB::raw("COALESCE(orders.mobile_brand, '') as mobile_brand"),
                DB::raw("COALESCE(orders.mobile_model, '') as mobile_model"),
                DB::raw("COALESCE(orders.mobile_memory, '') as mobile_memory"),
                DB::raw("COALESCE(orders.mobile_color, '') as mobile_color"),
                DB::raw("COALESCE(orders.second_hand_price, 0) as second_hand_price"),
                DB::raw("to_char(payments_log.created_at, 'YYYY-MM-DD') AS paid_date"),
                DB::raw("to_char(orders.coverage_end, 'YYYY-MM-DD') AS coverage_end")
                )
        ->where('invoices.invoice_status', '002')
        ->where('payments_log.transactions', '!=', null)
        ->where('payments_log.status', '=', 'successful');

        if(!empty($request->invoice_code)){
            $query->where('invoices.invoice_number', 'LIKE', "%{$request->invoice_code}%");
        }

        if(!empty($request->s_paid_date)){
            $query->whereRaw("to_char(payments_log.created_at, 'YYYY-MM-DD') >= ?", [$request->s_paid_date]);
        }

        if(!empty($request->e_paid_date)){
            $query->whereRaw("to_char(payments_log.created_at, 'YYYY-MM-DD') <= ?", [$request->e_paid_date]);
        }

        $query->orderBy('payments_log.created_at');

        $invoices = $query->get();

        // 1. สร้าง spreadsheet ใหม่
        $spreadsheet = new Spreadsheet();

        // 2. สร้าง sheet ใหม่
        $sheet = $spreadsheet->getActiveSheet();

        // 3. ใส่ข้อมูลลงใน sheet
        
        $sheet->setCellValue('A1', 'IDENTIFIER');
        $sheet->setCellValue('B1', 'FIRST NAME');
        $sheet->setCellValue('C1', 'LAST NAME');
        $sheet->setCellValue('D1', 'Identifier_ID');
        $sheet->setCellValue('E1', 'Address');
        $sheet->setCellValue('F1', 'BRAND');
        $sheet->setCellValue('G1', 'PRODUCT');
        $sheet->setCellValue('H1', 'MODEL');
        $sheet->setCellValue('I1', 'RAM');
        $sheet->setCellValue('J1', 'IMEI');
        $sheet->setCellValue('K1', 'SN');
        $sheet->setCellValue('L1', 'EFF_DATE');
        $sheet->setCellValue('M1', 'EXP_DATE');
        $sheet->setCellValue('N1', 'DEVICE_PRICE');
        $sheet->setCellValue('O1', 'PREMIUM');
        $sheet->setCellValue('P1', 'STAMP');
        $sheet->setCellValue('Q1', 'VAT');
        $sheet->setCellValue('R1', 'GROSSPREMIUM');
        $sheet->setCellValue('S1', 'INVOICE_CODE');
        $sheet->setCellValue('T1', 'CHANNEL');

        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => 'center'],
        ]);

        $row = 2;
        foreach ($invoices as $key => $item) {
            $sheet->setCellValue('A'.$row, $item->invoice_number);
            $sheet->setCellValue('B'.$row, $item->customer_firstname);
            $sheet->setCellValue('C'.$row, $item->customer_lastname);
            $sheet->setCellValue('D'.$row, $item->customer_idcard);
            $sheet->setCellValue('E'.$row, $item->customer_address);
            $sheet->setCellValue('F'.$row, $item->mobile_brand);
            $sheet->setCellValue('G'.$row, $item->mobile_model);
            $sheet->setCellValue('H'.$row, $item->mobile_model);
            $sheet->setCellValue('I'.$row, $item->mobile_memory);
            $sheet->setCellValue('J'.$row, $item->mobile_imei);
            $sheet->setCellValue('K'.$row, '');
            $sheet->setCellValue('L'.$row, $item->renews_start_date);
            $sheet->setCellValue('M'.$row, $item->renews_end_date);
            $sheet->setCellValue('N'.$row, number_format($item->second_hand_price));
            $sheet->setCellValue('O'.$row, number_format($item->package_totalpremium));
            $sheet->setCellValue('P'.$row, number_format($item->payment_net));
            $sheet->setCellValue('Q'.$row, number_format($item->patment_vat));
            $sheet->setCellValue('R'.$row, number_format($item->package_premiumafterdisc));
            $sheet->setCellValue('S'.$row, $item->charge_id);
            $sheet->setCellValue('T'.$row, 'Renew');

            $row++;
        }

        $currentDate    = date('Ymd');
        $randomNumber   = rand(1000, 9999);
        $filename       = 'invoice_'.$currentDate . '_' . $randomNumber.'.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('export_excel/'.$filename));

        $arr =  [   "status"    =>  "success",
                    "filename"  =>  $filename,
                    "url" => url('export_excel/' . $filename) 
                ];
        return response()->json($arr);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
