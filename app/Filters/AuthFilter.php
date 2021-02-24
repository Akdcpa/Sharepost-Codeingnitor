<?php
namespace App\Filters;
use CodeIgniter\Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\AuthController;
use \Firebase\JWT\JWT; 
use CodeIgniter\RESTful\ResourceController; 
use CodeIgniter\API\ResponseTrait;  
// use Config\Services;
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json;charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type , Access-Control-Allow-Headers , Authorization , X-Requested-With");

class AuthFilter  implements FilterInterface {
    use ResponseTrait;
    public function __construct() {
        $this->protect = new AuthController();
   } 

    public function before(RequestInterface $request) {
       
           try{
                $secret_key = $this->protect->privatekey();
                $token = null;
                $authHeader = $request->getServer('HTTP_AUTHORIZATION');
                $arr = explode(" " , $authHeader); 
                $token = $arr[1]; 
                $status = false;

                if($token){
                    $decoded = JWT::decode($token , $secret_key , array('HS256'));
                if($decoded){ 
                    return null;
                } 
            }
           }
           catch(\Exception $err){ 
               return Services::response()->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                                            ->setHeader('Retry-After', '3600')
                                            ->setBody("Invalid token");
           }
       
       return Services::response()->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                                            ->setHeader('Retry-After', '3600')
                                            ->setBody('Invalid token');
    }

    public function after(RequestInterface $request, ResponseInterface $response) {
    }
} 