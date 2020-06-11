<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;

class InfoController extends Controller
{
    //
    public function ip(Request $request){
        
        return response()->json(["origin" => $request->ip()]);
    }

    public function anything(Request $request){

        $host = $request->header('host');
        $url = $request->fullUrl();
        $method = $request->method();
        $userAgent = $request->userAgent();
        $origin = $request->ip();
        $args = $request->query();

        return response()->json(["args" => $args, "headers" => ["Host" => $host, "User-Agent" => $userAgent ], "method" => $method, "origin" => $origin, "url" => $url ]);
    }
}
