<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\ResponseProvider;
use Illuminate\Support\Facades\Storage;


class BalanceController extends Controller
{
    
    // function to return account balance ;
    // input =   GET account_id  ;
    // output = 200 value or 404 0;

    public function index(Request $request){
        
        $account_id = $request->input('account_id');
        if(Storage::disk('local')->exists($account_id.'.json')){
            $accountInfo = json_decode(Storage::disk('local')->get($account_id.'.json') , true) ;
            $returnJson =$accountInfo["balance"];
            $returnStatus = 200;
        }else{
            $returnJson = 0;
            $returnStatus = 404;
        }
     
        return ResponseProvider::returnObject($returnJson, $returnStatus);
    }
}
