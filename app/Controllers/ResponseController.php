<?php 
namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\RESTful\ResourceController; 
use \Firebase\JWT\JWT;
use App\Models\AuthModel; 

class ResponseController extends ResourceController
{ 
    public function __construct() { 
        $this->auth = new AuthModel(); 

    }
    public static function Response($msgs , $res=[]){ 
        return [
            "status"  => \Config\response_message::messages()["STATUS"]["SUCCESS"],
            "message" => $msgs,
            "data"    => $res
        ] ; 
    } 
}
 