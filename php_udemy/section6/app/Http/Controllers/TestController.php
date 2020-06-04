<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Test;

class TestController extends Controller
{
    //
    public function index(){
        $values = Test::all();

        $tests = DB::table('tests')
        ->select('text')
        ->get();

        // die + var_dump
        // dd($values);
        // dd($tests);

        return view('tests.test', compact('values'));
    }
}
