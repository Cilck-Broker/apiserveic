<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyUser;
use App\Models\Redbook;
use DB;

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
                                ->when($cc, function ($query, $cc) {
                                    return $query->where('redbook_tks_cc', $cc."cc");
                                })
                                ->first();
        
        $redbook_tks_goodretail = "";        
        if($ck_redbook){
            $redbook_tks_goodretail = $ck_redbook->redbook_tks_goodretail;
        }

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
                                    'ck_insurance_cost.inscost_maxyear as package_max_caryear',
                                    'ck_insurance.insurance_id'
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
        $arr = [];
        foreach ($results as $key => $row) {
            $protect1 = DB::connection("Conn_mysql")->table('ck_insurance_protect')
                            ->join('ck_protect', 'ck_insurance_protect.protect_name', '=', 'ck_protect.protect_id')
                            ->where('ck_insurance_protect.insurance_id', $row->insurance_id)
                            ->where('ck_protect.protect_type', 'ความรับผิดชอบต่อบุคคลภายนอก')
                            ->select('*')
                            ->get();

            $protect2 = DB::connection("Conn_mysql")->table('ck_insurance_protect')
                            ->join('ck_protect', 'ck_insurance_protect.protect_name', '=', 'ck_protect.protect_id')
                            ->where('ck_insurance_protect.insurance_id', $row->insurance_id)
                            ->where('ck_protect.protect_type', 'ความรับผิดต่อตัวรถยนต์')
                            ->select('*')
                            ->get();

            $protect3 = DB::connection("Conn_mysql")->table('ck_insurance_protect')
                            ->join('ck_protect', 'ck_insurance_protect.protect_name', '=', 'ck_protect.protect_id')
                            ->where('ck_insurance_protect.insurance_id', $row->insurance_id)
                            ->where('ck_protect.protect_type', 'ความคุ้มครองตามเอกสารแนบท้าย')
                            ->select('*')
                            ->get();
            
            $arr[$key] = [
                "package_id"                => $row->package_id,
                "package_code"              => $row->package_code,
                "package_name"              => $row->package_name,
                "package_insurer"           => $row->package_insurer,
                "package_repair"            => $row->package_repair,
                "package_cc_type"           => $row->package_cc_type,
                "package_license_type"      => $row->package_license_type,
                "package_deductible"        => $row->package_deductible,
                "package_end"               => $row->package_end,
                "package_car_code"          => $row->package_car_code,
                "package_insurance_type"    => $row->package_insurance_type,
                "package_sum_insured"       => $redbook_tks_goodretail,
                "package_min_insured"       => $row->package_min_insured,
                "package_max_insured"       => $row->package_max_insured,
                "package_net_premium"       => $row->package_net_premium,
                "package_total_premium"     => $row->package_total_premium,
                "package_min_caryear"       => $row->package_min_caryear,
                "package_max_caryear"       => $row->package_max_caryear,
            ];

            foreach ($protect1 as $key => $prot1) {
                $protect_code = $prot1->protect_code;
                switch ($protect_code) {
                    case 'C502': $aCode = "propertyCoverage";break;
                    case 'C503': $aCode = "deathPerPersonCoverage";break;
                    case 'C504': $aCode = "maxDeathCoverage";break;
                }
                $arr[$key][$aCode] = $prot1->protect_cost;
            }

            foreach ($protect2 as $key => $prot2) {
                $protect_code = $prot2->protect_code;
                switch ($protect_code) {
                    case 'C536': $aCode = "vehiclesumInsuredAmount";break;
                    case 'C507': $aCode = "isFireNTheft";break;
                    case 'C508': $aCode = "isFlood";break;
                }
                if($aCode == 'C508'){
                    $arr[$key][$aCode] = $prot1->protect_cost == 1 ? true : false;
                }else{
                    $arr[$key][$aCode] = $prot1->protect_cost;
                }
            }

            foreach ($protect2 as $key => $prot2) {
                $protect_code = $prot2->protect_code;
                switch ($protect_code) {
                    case 'C517': $aCode = "paCoverage";break;
                    case 'C525': $aCode = "medicalCoverage";break;
                    case 'C532': $aCode = "bailBondCoverage";break;
                }
                $arr[$key][$aCode] = $prot1->protect_cost;
            }
        }

        $response = [
            'code' => 200,
            'message' => 'success',
            'data' => $arr
        ];

        return response()->json($response);

    }
}
