<?php
namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\RESTful\ResourceController; 
use \Firebase\JWT\JWT;
use App\Models\AuthModel; 
use App\Models\PostModel; 
use  \Config\response_message;
use App\Controllers\AuthController;
class PostController extends ResourceController
{ 
    public function __construct() { 
        $this->posts = new PostModel();
        $this->url="";
    }

    public function create(){ 
       $validation =  \Config\Services::validation();
       $validation->setRules([
            'image' => 'uploaded[image]', 
            'message' => 'required|string',
            'type' => 'string'
            ]
        );
        $val = $validation->withRequest($this->request)->run();  
        if($val){
            $type = "TEXT";
                if($this->request->getPost('type')) {
                    if($this->request->getPost('type') == "IMAGE") {
                        $type = "IMAGE";
                    }

                    if($this->request->getPost('type') == "VIDEO") {
                        $type = "VIDEO";
                    } 
                    $user_id = AuthController::getuserid(); 

                    if($this->request->getFile('image')!=null){
                        $file = $this->request->getFile('image'); 
                        $path = $this->request->getFile('image')->store();
                        $data = [
                        'name' =>  $file->getClientName(),
                        'type'  => $file->getClientMimeType()
                    ];
                        $this->url = "posts/$path";  

                    }  

                    $createData = [
                        'user_id'=>$user_id,
                        'message'=>$this->request->getPost('message'),
                        'types'=>$type,
                        'url'=>$this->url,
                        'likes'=>0,
                        'dislikes'=>0
                    ];
                    $create = $this->posts->create($createData);
                    if($create==true){
                        return $this->respond( 
                        ResponseController::Response(response_message::messages()["MESSAGES"]["POST_CREATED_SUCCESSFULLY"] , $createData),200);
                }
            }
            else{
                $output=[
                    'status'=>400,
                    'message'=>'failed'
                ];
                return $this->respond($output , 400);
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
         
        // $allPosts = PostModel::with(['users', 'comments'])->orderBy('id', 'desc')->get();  
        $allPosts = $this->posts->getPosts();  

        return $this->respond($allPosts , 400);
    }

    public function like() {
        $validation =  \Config\Services::validation();
        $validation->setRules([
             'post_id' => 'required',  
             ]
         );
        $val = $validation->withRequest($this->request)->run();
        if($val){
            $post_id = $this->request->getPost('post_id');
            $likes = $this->posts->addLike($post_id);
            return $this->respond($likes , 400);
        }
        else{
            return $this->respond(
                ErrorController::Error(401 , $validation->getErrors()) , 401
            );
        }
    }

    public function dislike() {
        $validation =  \Config\Services::validation();
        $validation->setRules([
             'post_id' => 'required',  
             ]
         );
        $val = $validation->withRequest($this->request)->run();
        if($val){
            $post_id = $this->request->getPost('post_id');
            $dislikes = $this->posts->addDislike($post_id);
            return $this->respond($dislikes , 400);
        }
        else{
            return $this->respond(
                ErrorController::Error(401 , $validation->getErrors()) , 401
            );
        }
    }

    public function search(){
        $search = $this->request->getPost('search'); 
        $data = $this->posts->search($search);
        return $this->respond(
            ResponseController::Response(response_message::messages()["MESSAGES"]["POST_SEARCH"]
            ,$data) , 200
        ); 
    }
}
 