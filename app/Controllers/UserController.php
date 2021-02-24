<?php
namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\RESTful\ResourceController; 
use \Firebase\JWT\JWT;
use App\Models\AuthModel; 
use  \Config\response_message;
class UserController extends ResourceController
{ 
    public function __construct() {
        $this->auth = new AuthModel(); 
    }
    
    public function contact() {
        $user_id = AuthController::getuserid(); 
        $data = $this->auth->getContacts($user_id);
        return $this->respond(
            ResponseController::Response(response_message::messages()["MESSAGES"]["USER_SEARCH"]
            ,$data) , 200
        ); 
        
    }

    public function search(){
        $search = $this->request->getPost('search');
        $user_id = AuthController::getuserid(); 
        $data = $this->auth->search($search ,$user_id);
        return $this->respond(
            ResponseController::Response(response_message::messages()["MESSAGES"]["USER_SEARCH"]
            ,$data) , 200
        ); 
    }
}
 