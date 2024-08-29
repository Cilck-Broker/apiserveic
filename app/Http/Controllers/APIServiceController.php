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
        $redbook_tks_goodretail = $ck_redbook->redbook_tks_goodretail;
        // $balanceCost = isset($ck_redbook->redbook_tks_goodretail) ? $ck_redbook->redbook_tks_goodretail : 0;

        // Build the main query
        $results = DB::connection("Conn_mysql")
                                ->table('ck_insurance')
                                ->leftJoin('ck_insurance_cost', 'ck_insurance.insurance_id', '=', 'ck_insurance_cost.insurance_id')
                                ->leftJoin('ck_insurer_roadside', function ($join) {
                                    $join->on('ck_insurance.insurance_insurer', '=', 'ck_insurer_roadside.insurance_Name')
                                        ->on('ck_insurance.insurance_type', '=', 'ck_insurer_roadside.insurance_type');
                                })
                                ->leftJoin('conditionpay', 'ck_insurance.insurance_insurer', '=', 'conditionpay.insurance_Name')
                                ->select([
                                    DB::raw("CONCAT(ck_insurance.insurance_Number, '-', ck_insurance_cost.inscost_id) as package_id"),
                                    'ck_insurance.insurance_Number as package_code',
                                    'ck_insurance.insurance_Name as package_name',
                                    'ck_insurance.insurance_insurer as package_insurer',
                                    'ck_insurance.insurance_repair as package_repair',
                                    'ck_insurance.insurance_cc_types as package_cc_type',
                                    'ck_insurance.insurance_license_types as package_license_type',
                                    'ck_insurance.insurance_deductible as package_deductible',
                                    'ck_insurance.insurance_end as package_end',
                                    'ck_insurance.insurance_code as package_car_code',
                                    'ck_insurance.insurance_type as package_insurance_type',
                                    'ck_insurance_cost.inscost_minamount as package_min_insured',
                                    'ck_insurance_cost.inscost_maxamount as package_max_insured',
                                    'ck_insurance_cost.inscost_premamount as package_net_premium',
                                    'ck_insurance_cost.inscost_taxamount as package_total_premium',
                                    'ck_insurance_cost.inscost_minyear as package_min_caryear',
                                    'ck_insurance_cost.inscost_maxyear as package_max_caryear'
                                ])
                                ->where('ck_insurance_cost.inscost_brand', $make)
                                ->when($nameclass, function ($query, $nameclass) {
                                    $query->where('ck_insurance.insurance_type', $nameclass);
                                })
                                ->where('ck_insurance_cost.inscost_gen', $model)
                                ->when($insurer != '', function ($query, $insurer) {
                                    $query->where('ck_insurance.insurance_insurer', $insurer);
                                })
                                ->when($car_repair, function ($query) use ($car_repair) {
                                    $query->where('ck_insurance.insurance_repair', $car_repair);
                                })
                                ->when($cc > 2000, function ($query) {
                                    $query->whereIn('ck_insurance.insurance_cc_types', [0, 2]);
                                }, function ($query) {
                                    $query->whereIn('ck_insurance.insurance_cc_types', [0, 1]);
                                })
                                ->where('ck_insurance.status_internal', 1)
                                ->orderBy('ck_insurance_cost.inscost_taxamount', 'asc')
                                ->get();

        dd($results);
    }
}
