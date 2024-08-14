<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\orders;
use App\Models\orders_status;
use App\Models\followup;
use SESSION;
use Carbon\Carbon;

class FollowupController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
     
        $orders_followup_key = $request->get('orders_followup_key');

        $followup =followup::create([
            //'orders_followup_id' => 0,
            'orders_followup_key' => $request->get("orders_followup_key"),
            'orders_id' => $request->get("orders_id"),
            'orders_followup_date' => $request->get("orders_followup_date"),
            'orders_followup_comment' => $request->get("orders_followup_comment"),
            'orders_followup_active' => 'Y',
            'orders_followup_by' => auth()->user()->agnet_code,
            'orders_followup_remind_date' => $request->get("orders_followup_remind_date"),
            'orders_followup_remind_note' => $request->get("orders_followup_remind_note"),
            'create_date' => Carbon::now()->toDateTimeString(),
        ]);
        if ($followup) {
            $orders = orders::find($request->get("orders_id"));
            $followupData = DB::SELECT("SELECT * FROM follow_up WHERE follow_up_key = '$orders_followup_key'  ");
            $followupArray = json_decode(json_encode($followupData), true);
            
            if($followupArray[0]["follow_up_type"] == "Cancel"){
                $orders->status = "S0005";
                $orders->update_date = Carbon::now()->toDateTimeString();
                $orders->update_by = auth()->user()->agnet_code;
                $orders->save();
            }else{ 
                if($orders->status == 'S0001'){
                    $orders->status = "S0002";
                }
                $orders->update_date = Carbon::now()->toDateTimeString();
                $orders->update_by = auth()->user()->agnet_code;
                $orders->save();
            }

            return response()->json(['message' => $followup->orders_followup_id], 201);
        } else {
            return response()->json(['message' => ''], 500);
        }

        // dd($followup);

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
    public function show(Request $request)
    {
        // dd($request);
        $ordersFollowupData = followup::where('orders_id', $request->orders_id)
        ->get();

        // Return the data as a JSON response
        return response()->json($ordersFollowupData);
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


    public function getFollowupByOrderID(Request $request){

        $data = DB::SELECT("SELECT orders_followup.*, follow_up.follow_up_desc 
        FROM orders_followup 
        left join follow_up on orders_followup.orders_followup_key  = follow_up.follow_up_key 
        WHERE orders_id = '$request->orders_id' and orders_followup.orders_followup_active = 'Y'
        order by orders_followup.create_date  DESC ");

        return response()->json($data);
    }


}
