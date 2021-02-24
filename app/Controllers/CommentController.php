<?php
namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\RESTful\ResourceController; 
use \Firebase\JWT\JWT;
use App\Models\AuthModel; 
use App\Models\CommentModel; 
use  \Config\response_message;
use App\Controllers\AuthController;
class CommentController extends ResourceController
{ 
    public function __construct() { 
        $this->comment = new CommentModel();
        $this->url="";
    }   

    public function create(){ 
       $validation =  \Config\Services::validation();
       $validation->setRules([
            'post_id' => 'required', 
            'comment' => 'required',
            ]
        );
        $val = $validation->withRequest($this->request)->run();  
        if($val){
                $user_id = AuthController::getuserid();
                $createData = [ 
                    'user_id'=>$user_id,
                    'post_id'=>$this->request->getPost('post_id'), 
                    'comment'=>$this->request->getPost('comment'), 
                ];
 
                $create = $this->comment->create($createData);
                if($create==true){
                    return $this->respond( 
                    ResponseController::Response(response_message::messages()["MESSAGES"]["COMMENT_ADDED_SUCCESSFULLY"] , $createData),200);
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


    public function get() {
        $validation =  \Config\Services::validation();
        $validation->setRules([
             'post_id' => 'required',  
             ]
         );
        $val = $validation->withRequest($this->request)->run();
        if($val){
            $post_id = $this->request->getPost('post_id');
            $allComments = $this->comment->getComments($post_id);
            return $this->respond($allComments , 400);
        }
        else{
            return $this->respond(
                ErrorController::Error(401 , $validation->getErrors()) , 401
            );
        }
    } 

}
 