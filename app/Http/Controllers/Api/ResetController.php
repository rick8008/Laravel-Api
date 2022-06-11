<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\ResponseProvider;
use Illuminate\Support\Facades\Storage;

class ResetController extends Controller
{
    
    // function to delete all acconts ;
    // input =   null  ;
    // output = 200 OK;
    public function index(){
        $files =   Storage::disk('local')->allFiles();
        Storage::disk('local')->delete($files);
        return ResponseProvider::returnObject('OK', 200);
    }
}
