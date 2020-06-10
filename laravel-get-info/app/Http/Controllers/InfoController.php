<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;

class InfoController extends Controller
{
    //
    public function ip(){
        // $ips = []
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return view('ip', compact('ip'));
                    }
                    return view('ip', compact('ip'));
                    // $ips[$key] = $ip 
                }
            }
        }
        // return view('ip', compact('ips'));
    }

    public function anything(Request $request){

        $headers = $request;
        $url = $request->url();
        $method = $request->method();
        $userAgent = $request->userAgent();
        $origin = $request->ip();

        return view('anything', compact('headers', 'url', 'method', 'userAgent', 'origin'));
        // return view('anything', compact('request'));
        // dd($request);
    }
}
