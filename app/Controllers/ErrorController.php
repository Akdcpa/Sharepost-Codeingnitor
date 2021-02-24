<?php
namespace App\Controllers;
 
use CodeIgniter\Controller; 
use CodeIgniter\RESTful\ResourceController; 

class ErrorController extends ResourceController
{ 
    public static function Error($code , $msgs=null){
        $msg = ErrorController::ErrorMessage($msgs);

        return [
            "status" => "fails",
            "data" => $msgs != null ? $msg : "Request fails",
            "errorcode"=>$code,
        ]; 

    }


    public static function ErrorMessage($messages){
        $errorMsg = "" ;
        foreach($messages as $msg) {
            $errorMsg = $errorMsg . $msg . " " ; 
        }

        return $errorMsg ;
    }
}
 