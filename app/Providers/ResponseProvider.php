<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ResponseProvider extends ServiceProvider
{   
    // function to create return object ;
    // input =   text , httpcode  ;
    // output = object;
    static function returnObject($json, $code){
        return response($json, $code)->header('Content-Type', 'application/json');
    }
}
