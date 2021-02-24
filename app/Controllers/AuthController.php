<?php
namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\RESTful\ResourceController; 
use \Firebase\JWT\JWT;
use App\Models\AuthModel; 
use  \Config\response_message;
class AuthController extends ResourceController
{ 
    public function __construct() {
        $this->auth = new AuthModel(); 
    }

    public function privatekey(){
        $privateKey = <<<EOD
            -----BEGIN RSA PRIVATE KEY-----
            MIICXAIBAAKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/Rn
            vuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL9
            5+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQAB
            AoGAb/MXV46XxCFRxNuB8LyAtmLDgi/xRnTAlMHjSACddwkyKem8//8eZtw9fzxz
            bWZ/1/doQOuHBGYZU8aDzzj59FZ78dyzNFoF91hbvZKkg+6wGyd/LrGVEB+Xre0J
            Nil0GReM2AHDNZUYRv+HYJPIOrB0CRczLQsgFJ8K6aAD6F0CQQDzbpjYdx10qgK1
            cP59UHiHjPZYC0loEsk7s+hUmT3QHerAQJMZWC11Qrn2N+ybwwNblDKv+s5qgMQ5
            5tNoQ9IfAkEAxkyffU6ythpg/H0Ixe1I2rd0GbF05biIzO/i77Det3n4YsJVlDck
            ZkcvY3SK2iRIL4c9yY6hlIhs+K9wXTtGWwJBAO9Dskl48mO7woPR9uD22jDpNSwe
            k90OMepTjzSvlhjbfuPN1IdhqvSJTDychRwn1kIJ7LQZgQ8fVz9OCFZ/6qMCQGOb
            qaGwHmUK6xzpUbbacnYrIM6nLSkXgOAwv7XXCojvY614ILTK3iXiLBOxPu5Eu13k
            eUz9sHyD6vkgZzjtxXECQAkp4Xerf5TGfQXGXhxIX52yH+N2LtujCdkQZjXAsGdm
            B2zNzvrlgRmgBrklMTrMYgm1NPcW+bRLGcwgW2PTvNM=
            -----END RSA PRIVATE KEY-----
            EOD;
        return $privateKey;
    }

    public function login()
	{        
        $session = \Config\Services::session();
        $validation =  \Config\Services::validation();
        $validation->setRules([
             'email' => 'required|valid_email', 
             'password' => 'required|min_length[6]', 
             ]
         );
         $val = $validation->withRequest($this->request)->run();  

         if($val){
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $login = $this->auth->login($email); 
            if($login!=null){
                if(password_verify($password , $login['password'])){

                    $secret_key = $this->privatekey();
                    $issuer_claim = "THE_CLAIM";
                    $aud_claim = "THE_AUD";
                    $issued = time();
                    $notbefore_claim = $issued+10;
                    $expire_claim = $issued  +3600;
                    $token = [
                        "iss"   => $issuer_claim,
                        "aud"   => $aud_claim,
                        "iat"   => $issued,
                        "nbf"   => $notbefore_claim,
                        "exp"   => $expire_claim,
                        "data"  => [
                            'id'    =>  $login['id'],
                            'email' =>  $login['email'],
                            'name'  =>  $login['password']
                        ]
                        ];
                    $access_token = JWT::encode($token , $secret_key);
                    $newdata = [
                        'id'  => $login['id'],
                        'name'  => $login['name'],
                        'email'     => $login['email'],
                        'logged_in' => TRUE
                    ];
                    $session->set($newdata);
                    $output=[
                        'status'=>200,
                        'message'=>'success',
                        'token' => $access_token,
                        'expiresAt'=>$this->secondsToTime($expire_claim), 
                    ];
                    return $this->respond(ResponseController::Response(response_message::messages()["MESSAGES"]["LOGIN_SUCCESS"],$output) , 200); 
                    
                }
                else{
                    return $this->respond(
                        ErrorController::Error(422 , ["Password Wrong"]) , 422
                    );
                }
            }
            else{
                return $this->respond(
                    ErrorController::Error(422 , ["Email Wrong"]) , 422
                );
            }
         }
         else{
            $output=[
                'status'=>400,
                'message'=>'failed',
                'errors'=> $validation->getErrors()
            ]; 
            return $this->respond($output , 400);
         }
	}

	public function register()
	{         
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|string', 
             'email' => 'required|valid_email', 
             'password' => 'required|min_length[6]', 
             ]
         );
        $val = $validation->withRequest($this->request)->run();

        if($val){
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password'); 
            $password_hash = password_hash($password , PASSWORD_BCRYPT); 
            $regData = [
                "name" => $name, 
                "email" => $email,
                "password" => $password_hash, 
            ]; 

            $register = $this->auth->register($regData); 
            if($register==true){
                $output=[
                    'status'=>200,
                    'message'=>'success'
                    ];
                    return $this->respond(ResponseController::Response(response_message::messages()["MESSAGES"]["USER_REGISTERED_SUCCESSFULLY"],$output) , 200); 
                }
            else{
                $output=[  
                    'mes' => 'email already registered'
                ];
                return $this->respond(
                    ErrorController::Error(422 , $output) , 422
                );
            }
        }
        else{
            $output=[
                'status'=>400,
                'message'=>'failed',
                'errors'=> $validation->getErrors()
            ]; 
            return $this->respond($output , 400);
         }
    }

	public function logout()
	{        
        $session = \Config\Services::session();

        if($session->get('id')!=null){
            $session->destroy();
            return $this->respond(
                ResponseController::Response(response_message::messages()["MESSAGES"]["USER_LOGOUT_SUCCESSFULLY"]
                ,$session->get()) , 200
            ); 
        }
        else{
            $output=[  
                "error"
            ];
            return $this->respond(
                ErrorController::Error(422 ,$output ) , 400
            );
        }
    }
     
    public function secondsToTime($seconds) {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }

    public static function getuserid(){
        $session = \Config\Services::session();
        return $session->get('id');
    }
}
 