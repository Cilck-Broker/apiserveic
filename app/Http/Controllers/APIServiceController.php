<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyUser;
use App\Models\Redbook; // ชื่อโมเดลที่ต้องการ

class APIServiceController extends Controller
{
    public function getMark(Request $request)
    {
        $make = Redbook::select('redbook_tks_make')
                    ->distinct()
                    ->orderBy('redbook_tks_make', 'asc')
                    ->get();

        return response()->json($make);
    }

    public function getDataModel(Request $request){
        $make = $request->input('make');

        $car_model = Redbook::select('redbook_tks_model')
                        ->where('redbook_tks_make', $make)
                        ->groupBy('redbook_tks_model')
                        ->orderBy('redbook_tks_model', 'asc')
                        ->get();

        return response()->json($car_model);
    }
}
