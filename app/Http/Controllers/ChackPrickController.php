<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ChackPrickController extends Controller
{
    public function getMark(){
        $results = DB::connection('Conn_mysql')->table('ck_redbook')
                    ->select('redbook_tks_make')
                    ->distinct()
                    ->orderBy('redbook_tks_make', 'asc')
                    ->get();
    }
}
