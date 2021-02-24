<?php 
    namespace App\Models;
     
    use CodeIgniter\Model;

    class PostModel extends Model{
        protected $table = 'posts';
        
        public function create($data){ 
            $query = $this->db->table($this->table)->insert($data);

            return $query ? true : false;
        }

        public function getPosts(){ 
            $query = $this->db->table($this->table)
                                ->join('users' , 'users.id = posts.user_id')
                                ->join('comments' , 'comments.post_id=posts.id')
                                ->get();  
            return $query->getResult();
        }
        
        public function addLike($post_id){ 
            $query = $this->db->table($this->table)
                                ->set('likes' , 'likes+1' , FALSE)
                                ->where('id', $post_id)
                                ->update();  
            $res = $this->db->table($this->table)
                                ->where('id', $post_id)
                                ->get();  
            return $res->getResult(); 
        }

        public function addDislike($post_id){ 
            $query = $this->db->table($this->table)
                                ->set('dislikes' , 'dislikes+1' , FALSE)
                                ->where('id', $post_id)
                                ->update();  
            $res = $this->db->table($this->table)
                        ->where('id', $post_id)
                        ->get();  
            return $res->getResult();
        }

        public function search($search){
            $query = $this->table($this->table)  
                          ->like('message' , $search)
                          ->countAll();
            if($query>0){
            $has_user = $this->table($this->table) 
                            ->like('message' , $search)
                            ->get();
            }
            else{
                $has_user = array();
            }
            return $has_user->getResult();
        }
    } 