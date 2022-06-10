<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\ResponseProvider;
class EventController extends Controller
{
    public function index(Request $request){
        $account_id = $request->input('account_id');
     
        return ResponseProvider::returnObject('{"msg":"aaaaaa"}', 200);
    }
}
