<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\ResponseProvider;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{

    // function to recive the object 
    // input =   json request ;
    // output = json object or 404 0;

    public function index(Request $request){

        
        $eventObject = $request->all();

        if(!$this->objectVerify($eventObject))
        return ResponseProvider::returnObject('0', 404);
    
        $returnInfo = $this->eventSwitcher($eventObject);
        $statusReturn = $returnInfo["status"];
        unset($returnInfo["status"]);
        if($statusReturn == 404){
            $jsonReturn = 0;
        }
        else{
            $jsonReturn = json_encode($returnInfo);
        }

        return ResponseProvider::returnObject($jsonReturn, $statusReturn);
    }




    // function to redirec to right event
    // input =   valid array ;
    // output = array [*origin,*destination,status];
    public function eventSwitcher($object){
        switch ($object['type']) {
            case 'withdraw':
                $returnInfo = $this->eventWithdraw($object['amount'],$object['origin']);
                break;

            case 'transfer':
                $returnInfo = $this->eventTransfer($object['amount'],$object['origin'],$object['destination']);
                break;

            case 'deposit':
                $returnInfo = $this->eventDeposit($object['amount'],$object['destination']);
                break;
        }
        return $returnInfo;
    }

    // function to withdraw from existing account
    // input =   float, int ;
    // output = array [origin,status];
    public function eventWithdraw($amount,$origin){
        $newBalance = $this->accountManeger('withdraw '.$amount.' at : '.date('Y-m-d H:i:s'),$origin,$amount*-1 );

        if($newBalance["status"] == false){
            $arrayReturn['status'] = 404;
        }else{
            $arrayReturn['origin']["id"] = $origin;
            $arrayReturn['origin']["balance"] = $newBalance["balance"];
            $arrayReturn['status'] = 201;
        }
        

        return $arrayReturn; 

    }


    // function to transfer from existing account
    // input =   float, int, int ;
    // output = array [origin,destination,status];
    public function eventTransfer($amount, $origin, $destination){

       $originAccount =  $this->accountManeger('transfer'.$amount.' from '.$origin.' at : '.date('Y-m-d H:i:s'),$origin,$amount *-1);
       if( $originAccount["status"] ){
            $destinationAccount = $this->accountManeger('transfer'.$amount.' from '.$origin.' at : '.date('Y-m-d H:i:s'),$destination,$amount);

            $arrayReturn['origin']["id"] = $origin;
            $arrayReturn['origin']["balance"] = $originAccount["balance"];

            $arrayReturn['destination']["id"] = $destination;
            $arrayReturn['destination']["balance"] = $destinationAccount["balance"];
         
            $arrayReturn['status'] = 201;
       }else{
        $arrayReturn['status'] = 404;
       }
       return $arrayReturn;
    }

    // function to deposit in existing or new account
    // input =   float, int ;
    // output = array [destination,status];
    public function eventDeposit($amount,$destination){

        $newBalance = $this->accountManeger('deposit '.$amount.' at : '.date('Y-m-d H:i:s'),$destination,$amount );

        $arrayReturn['destination']["id"] = $destination;
        $arrayReturn['destination']["balance"] = $newBalance["balance"];
        $arrayReturn['status'] = 201;

        return $arrayReturn; 
    }



    // function add or subtract in a account
    // input = string , int, float ;
    // output = array [balance,status];
    public function accountManeger($logText,$id,$amount){
        $arrayReturn = ["balance" => null , "status" => true] ;

        if(Storage::disk('local')->exists($id.'.json')){

            $accountInfo =json_decode(Storage::disk('local')->get($id.'.json'),true);
            $accountInfo["log"][] = $logText;
            $accountInfo["balance"] += $amount;
            
            Storage::disk('local')->put($id.'.json', json_encode($accountInfo));

            $arrayReturn["balance"] = $accountInfo["balance"];

        }else{
            if($amount > 0 ){
                $accountInfo =[
                    "log" => [$logText ] ,
                    "balance"=> $amount
                ];
    
                Storage::disk('local')->put($id.'.json', json_encode($accountInfo)); 
                $arrayReturn["balance"] = $accountInfo["balance"];
            }
            else{
                $arrayReturn["status"] = false;
            }
        }

        return $arrayReturn;
    }





    // function to vierify if the object recived is valid
    // input = array;
    // output = bool;
    public function objectVerify($object){
        $objectValid = true;
        if(array_key_exists('type',$object) && array_key_exists('amount',$object)){
            switch ($object['type']) {
                case 'withdraw':
                    if(!array_key_exists('origin',$object))
                        $objectValid = false;
                    
                    break;

                case 'transfer':
                    if(!array_key_exists('origin',$object) || !array_key_exists('destination',$object) )
                    $objectValid = false;
                    
                    break;

                case 'deposit':
                    if(!array_key_exists('destination',$object))
                        $objectValid = false;
                    
                    
                    break;
               
               default:
                    $objectValid = false;
                   break;
           }

        }else{
            $objectValid = false;
        }
        return $objectValid;
    }


    
}
