<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ResponseProvider extends ServiceProvider
{
        static function returnObject($json, $code){
            return response($json, $code)->header('Content-Type', 'application/json');
        }
}
