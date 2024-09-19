<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyUser;
use App\Models\Redbook;
use DB;
use Log;

class APIServiceController extends Controller
{
    public function getInsurer(Request $request){
        $ck_insurer = DB::connection("Conn_mysql")
                                ->table('ck_insurer')
                                ->select('insurer_name as insurer')
                                
                                ->get();

        // จัดกลุ่มข้อมูลเป็น array ของ makename
        $insurer = [];
        foreach ($ck_insurer as $item) {
            $insurer[] = ['insurer' => $item->insurer];
        }

        // สร้าง response
        $response = [
            'code' => 200,
            'message' => 'success',
            'data' => $insurer
        ];

        return response()->json($response);
    }

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
        foreach ($car_cc as $item) {
            $ccValue = preg_replace('/\D/', '', $item->cc);
            $modelData[] = ['cc' => $ccValue];
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
        $deduct     = $request->input('deduct');

        if(!empty($year)){
            $minyear = (now()->year - $year) + 1;
        }

        Log::info('Request data:', [
            'make' => $make,
            'model' => $model,
            'year' => $year,
            'cc' => $cc,
            'nameclass' => $nameclass,
            'car_repair' => $car_repair,
            'insurer' => $insurer,
            'deduct' => $deduct,
        ]);

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
        $txtSql =  "select
                        CONCAT(ck_insurance.insurance_Number, '-', ck_insurance_cost.inscost_id) as package_id,
                        `ck_insurance`.`insurance_Number` as `package_code`,
                        `ck_insurance`.`insurance_Name` as `package_name`,
                        `ck_insurance`.`insurance_insurer` as `package_insurer`,
                        `ck_insurance`.`insurance_repair` as `package_repair`,
                        `ck_insurance`.`insurance_cc_types` as `package_cc_type`,
                        `ck_insurance`.`insurance_license_types` as `package_license_type`,
                        `ck_insurance`.`insurance_deductible` as `package_deductible`,
                        `ck_insurance`.`insurance_end` as `package_end`,
                        `ck_insurance`.`insurance_code` as `package_car_code`,
                        `ck_insurance`.`insurance_type` as `package_insurance_type`,
                        `ck_insurance_cost`.`inscost_minamount` as `package_min_insured`,
                        `ck_insurance_cost`.`inscost_maxamount` as `package_max_insured`,
                        `ck_insurance_cost`.`inscost_premamount` as `package_net_premium`,
                        `ck_insurance_cost`.`inscost_taxamount` as `package_total_premium`,
                        `ck_insurance_cost`.`inscost_minyear` as `package_min_caryear`,
                        `ck_insurance_cost`.`inscost_maxyear` as `package_max_caryear`,
                        `ck_insurance`.`insurance_id`,
                        `ck_insurance`.`status`,
                        `ck_insurance`.`no_protect`,
                        `ck_insurance`.`insurance_desc`
                    from
                        `ck_insurance`
                    left join `ck_insurance_cost` 	on `ck_insurance`.`insurance_id` 		= `ck_insurance_cost`.`insurance_id`
                    left join `ck_insurer_roadside` on `ck_insurance`.`insurance_insurer` 	= `ck_insurer_roadside`.`insurance_Name` 
                                                    and`ck_insurance`.`insurance_type` 		= `ck_insurer_roadside`.`insurance_type`
                    left join `conditionpay` 		on `ck_insurance`.`insurance_insurer` 	= `conditionpay`.`insurance_Name`
                    where 	`ck_insurance`.`status` = '1' 
                            and `ck_insurance_cost`.`inscost_brand` = '".$make."'
                            and `ck_insurance`.`insurance_type` = '".$nameclass."'
                            and `ck_insurance_cost`.`inscost_gen` = '".$model."'
                            and ck_insurance.status = '1'";
        if(!empty($insurer)){
            $txtSql .= "    and `ck_insurance`.`insurance_insurer` LIKE '%".$insurer."%'";
        }     

        if($cc > 2000){
            $txtSql .= "    and `ck_insurance`.`insurance_cc_types` in (0, 2)";
        }else{
            $txtSql .= "    and `ck_insurance`.`insurance_cc_types` in (0, 1)";
        }

        if(empty($deduct)){
            $txtSql .= "    and ck_insurance.insurance_deductible = 0";
        }else{
            $txtSql .= "    and ck_insurance.insurance_deductible <> 0";
        }

        if(isset($minyear)){
            $txtSql .= "    and ".$minyear." BETWEEN ck_insurance_cost.inscost_minyear AND ck_insurance_cost.inscost_maxyear";
        }
        
        $txtSql .= "        and `ck_insurance`.`insurance_end` >= '".date('Y-m-d')."'";
        
        $txtSql .= "        and (
                                case
                                        when (ck_insurance.insurance_type = '1'
                                            and ck_insurance.insurance_deductible = 0)
                                then ".$redbook_tks_goodretail." between ck_insurance_cost.inscost_minamount and ck_insurance_cost.inscost_maxamount
                                        else ck_insurance_cost.inscost_minamount >= 0
                                    end
                                )
                    order by `ck_insurance_cost`.`inscost_taxamount` asc";

        $results = DB::connection("Conn_mysql")->select($txtSql);
        
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
                "status"                    => $row->status,
                "insurance_id"              => $row->insurance_id,
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
                "package_min_insured"       => $row->package_min_insured,
                "package_max_insured"       => $row->package_max_insured,
                "package_net_premium"       => $row->package_net_premium,
                "package_total_premium"     => $row->package_total_premium,
                "package_min_caryear"       => $row->package_min_caryear,
                "package_max_caryear"       => $row->package_max_caryear,
                "peopleCoverage"            => $row->no_protect,
                "remark"                    => $row->insurance_desc,                
            ];

            if($row->package_min_insured > 0 && $row->package_max_insured > 0){
                $arr[$key]["package_sum_insured"] = $redbook_tks_goodretail;
            
            }else if($row->package_min_insured > 0 && $row->package_max_insured == 0){
                $arr[$key]["package_sum_insured"] = $row->package_min_insured;

            }

            foreach ($protect1 as $key1 => $prot1) {
                $protect_code = $prot1->protect_code;
                switch ($protect_code) {
                    case 'C502': $aCode = "propertyCoverage";break;
                    case 'C503': $aCode = "deathPerPersonCoverage";break;
                    case 'C504': $aCode = "maxDeathCoverage";break;
                }
                $arr[$key][$aCode] = $prot1->protect_cost;
            }

            foreach ($protect2 as $key2 => $prot2) {
                $protect_code = $prot2->protect_code;
                switch ($protect_code) {
                    case 'C536': $aCode = "vehiclesumInsuredAmount";break;
                    case 'C507': $aCode = "isFireNTheft";break;
                    case 'C508': $aCode = "isFlood";break;
                }
                if($aCode == 'C508'){
                    $arr[$key][$aCode] = $prot2->protect_cost == 1 ? true : false;
                }else{
                    $arr[$key][$aCode] = $redbook_tks_goodretail;
                }
            }

            foreach ($protect3 as $key3 => $prot3) {
                $protect_code = $prot3->protect_code;
                switch ($protect_code) {
                    case 'C517': $aCode = "paCoverage";break;
                    case 'C525': $aCode = "medicalCoverage";break;
                    case 'C532': $aCode = "bailBondCoverage";break;
                }
                $arr[$key][$aCode] = $prot3->protect_cost;
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
