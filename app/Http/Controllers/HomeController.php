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



class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function getCusDataList(Request $request){
        // $request->get("_token");

        $sqlOrder = "";

        if(!empty($request->cus_name)){

            $sqlOrder .= " AND customer_firstname || '' || customer_lastname LIKE '%$request->cus_name%' ";
        }

        if(!empty($request->order_code)){
            $sqlOrder .= " AND orders_code LIKE '%$request->order_code%' ";

        }

        if(!empty($request->phone_no)){
            $sqlOrder .= " AND customer_phone LIKE '%$request->phone_no%' ";
        }

        if(!empty($request->id_card)){
            $sqlOrder .= " AND customer_idcard LIKE '%$request->id_card%' ";
        }

        if(!empty($request->email)){
            $sqlOrder .= " AND customer_email LIKE '%$request->email%' ";
        }

        if(!empty($request->iemi)){
            $sqlOrder .= " AND mobile_imei LIKE '%$request->iemi%' ";
        }

        if(!empty($request->status)){
            $sqlOrder .= " AND status = '$request->status' ";
        } 

        if(!empty($request->s_coverage_end)){
            $sqlOrder .= " AND coverage_end >= '$request->s_coverage_end' ";
        }

        if(!empty($request->e_coverage_end)){
            $sqlOrder .= " AND coverage_end <= '$request->e_coverage_end' ";
        }


        if(!empty($request->agent_code)){
            $sqlOrder .= " AND agent_code = '$request->agent_code' ";
        }

        if(auth()->user()->agent_rule == "Sale"){
            $usercode = auth()->user()->agnet_code; 
            $sqlOrder .= " AND agent_code = '$usercode' ";
        }

        $page = $request->page;

        $data = orders::whereRaw("1=1".$sqlOrder )->orderBy('coverage_end', 'asc')->paginate(50, ['*'], 'page', $page); 
       
        return response()->json($data); 
    }

    public function getAgentCode(Request $request){
        $agents = agents::get();
        return response()->json($agents); 

    }

    public function getStatusOrders(){
        $data = DB::SELECT("SELECT orders_status_key, orders_status_desc, orders_status_color
                            FROM orders_status
                            WHERE orders_status_active = 'Y'"); 
        
        return response()->json($data);
    }

    public function editOrderPage($order_id){
        $orders = DB::select("SELECT ord.*, pack.*, pr.package_rate_id, pr.netpremium, duty, tax, totalpremium, orders_status.orders_status_desc
                        FROM orders ord
                        LEFT JOIN packages_rate pr ON pr.secondhand_price_low <= ord.second_hand_price AND pr.secondhand_price_high >= ord.second_hand_price
                        LEFT JOIN packages pack ON pack.packages_id = pr.package_id 
                        LEFT JOIN orders_status ON ord.status = orders_status.orders_status_key
                        WHERE orders_id = '$order_id'");

        $followup = DB::select("SELECT * FROM follow_up"); 
        $invoice = $this->getInvoiceDetailById($order_id);
        $ordersfile = DB::select("SELECT * FROM orders_file WHERE orders_id = '$order_id' AND active = 'Y' "); 

        $c_invoice = 0;
        foreach ($invoice as $key => $val) {
            if($val->invoice_status == "001"){
                $c_invoice++;
            }
        }

        $data = [
            "orders"        => $orders[0],
            "followup"      => $followup,
            "invoice"       => $invoice,
            "ordersfile"    => $ordersfile,
            "c_invoice"    => $c_invoice,
        ];
// dd($data);
        return view('order/editOrderPage')->with($data);
    }

    public function getInvoiceDetailById($order_id)
    {
        $invoice = DB::select(" SELECT  invoices.* , 
                                        invoice_status.invoice_status_desc 
                                FROM invoices 
                                LEFT JOIN   invoice_status on invoices.invoice_status = invoice_status.invoice_status_key 
                                WHERE       invoices.orders_id = '$order_id' AND active = 'Y' ");
        
        return $invoice;
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
