<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyUser;
use App\Models\Redbook; // ชื่อโมเดลที่ต้องการ

class APIServiceController extends Controller
{
    public function getMark(Request $request)
    {
        $make = Redbook::select('redbook_tks_make AS makename')
                    ->distinct()
                    ->orderBy('redbook_tks_make', 'asc')
                    ->get();

        // จัดกลุ่มข้อมูลเป็น array ของ makename
        $makeData = [];
        foreach ($make as $item) {
            $makeData[] = ['makename' => $item->makename];
        }

        // สร้าง response
        $response = [
            'code' => 200,
            'message' => 'success',
            'data' => $makeData
        ];

        return response()->json($response);
    }

    public function getDataModel(Request $request){
        $make = $request->input('make');

        $car_model = Redbook::select('redbook_tks_model as model')
                        ->where('redbook_tks_make', $make)
                        ->groupBy('redbook_tks_model')
                        ->orderBy('redbook_tks_model', 'asc')
                        ->get();

        // จัดกลุ่มข้อมูลเป็น array ของ makename
        $modelData = [];
        foreach ($car_model as $item) {
            $modelData[] = ['model' => $item->model];
        }

        // สร้าง response
        $response = [
            'code' => 200,
            'message' => 'success',
            'data' => $modelData
        ];

        return response()->json($response);
    }

    public function getCC(Request $request){
        $make   = $request->input('make');
        $model  = $request->input('model');
        $year   = $request->input('year');

        $car_cc = Redbook::select('redbook_tks_cc as cc')
                        ->where('redbook_tks_make', $make)
                        ->where('redbook_tks_model', $model)
                        ->where('redbook_tks_yeargroup', $year)
                        ->groupBy('redbook_tks_cc')
                        ->orderBy('redbook_tks_cc', 'asc')
                        ->get();

        // จัดกลุ่มข้อมูลเป็น array ของ makename
        $modelData = [];
        foreach ($car_model as $item) {
            $modelData[] = ['cc' => $item->cc];
        }

        // สร้าง response
        $response = [
            'code' => 200,
            'message' => 'success',
            'data' => $modelData
        ];

        return response()->json($response);
    }

    public function getPrice(Request $request){
        $make   = $request->input('make');
        $model  = $request->input('model');
        $year   = $request->input('year');
        $cc     = $request->input('cc');

        $nameclass  = $request->input('nameclass');
        $car_repair = $request->input('car_repair');
        $insurer    = $request->input('insurer');

        // Get balance cost from redbook
        $ck_redbook = DB::connection("Conn_mysql")
                                ->table('ck_redbook')
                                ->where('redbook_tks_make', $make)
                                ->where('redbook_tks_model', $model)
                                ->where('redbook_tks_yeargroup', $year)
                                ->when($cc, function ($query) {
                                    return $query->where('redbook_tks_cc', $cc);
                                })
                                ->first();
        dd($ck_redbook);
        $balanceCost = isset($ck_redbook->redbook_tks_goodretail) ? $ck_redbook->redbook_tks_goodretail : 0;

        // Build the main query
        $query = DB::connection("Conn_mysql")
                                ->table('ck_insurance')
                                ->leftJoin('ck_insurance_cost', 'ck_insurance.insurance_id', '=', 'ck_insurance_cost.insurance_id')
                                ->leftJoin('ck_insurer_roadside', function ($join) {
                                    $join->on('ck_insurance.insurance_insurer', '=', 'ck_insurer_roadside.insurance_Name')
                                        ->on('ck_insurance.insurance_type', '=', 'ck_insurer_roadside.insurance_type');
                                })
                                ->leftJoin('conditionpay', 'ck_insurance.insurance_insurer', '=', 'conditionpay.insurance_Name')
                                ->select(
                                    'ck_insurance.*',
                                    'ck_insurance_cost.*',
                                    'ck_insurer_roadside.contact',
                                    'ck_insurer_roadside.description',
                                    'conditionpay.ConditionPay_id AS condiPayID',
                                    'conditionpay.Status AS condiPayStatus'
                                )
                                ->where('ck_insurance_cost.inscost_brand', $_REQUEST['brand'])
                                ->when(isset($_REQUEST['user']) && $_REQUEST['user'] !== "gobear", function ($query) {
                                    if ($_REQUEST['class'] !== "all") {
                                        $query->where('ck_insurance.insurance_type', $_REQUEST['class']);
                                    }
                                })
                                ->where('ck_insurance_cost.inscost_gen', $_REQUEST['generation'])
                                ->when($_REQUEST['insurers'] != '0', function ($query) {
                                    $query->where('ck_insurance.insurance_insurer', $_REQUEST['insurers']);
                                })
                                ->when($rs_repair, function ($query) use ($rs_repair) {
                                    $query->where('ck_insurance.insurance_repair', $rs_repair);
                                })
                                ->when($whereCC > 2000, function ($query) {
                                    $query->whereIn('ck_insurance.insurance_cc_types', [0, 2]);
                                }, function ($query) {
                                    $query->whereIn('ck_insurance.insurance_cc_types', [0, 1]);
                                })
                                ->when($_REQUEST['deduct'] == 0 && $_REQUEST['deductmile'] == 0, function ($query) {
                                    $query->where('ck_insurance.insurance_deductible', 0);
                                })
                                ->when($_REQUEST['deduct'] == 1 && $_REQUEST['deductmile'] == 0, function ($query) {
                                    $query->where('ck_insurance.insurance_deductible', '>', 100);
                                })
                                ->when($_REQUEST['deduct'] == 0 && $_REQUEST['deductmile'] == 1, function ($query) {
                                    $query->where('ck_insurance.insurance_deductible', 100);
                                })
                                ->when($_REQUEST['deduct'] == 1 && $_REQUEST['deductmile'] == 1, function ($query) {
                                    $query->where('ck_insurance.insurance_deductible', '!=', 0);
                                })
                                ->where('ck_insurance.insurance_end', '>=', date("Y-m-d"))
                                ->whereBetween($minyear, ['ck_insurance_cost.inscost_minyear', 'ck_insurance_cost.inscost_maxyear'])
                                ->where('ck_insurance.status_internal', 1)
                                ->orderBy('ck_insurance_cost.inscost_taxamount', 'asc');

        // Get the results
        $results = $query->get();
    }
}
