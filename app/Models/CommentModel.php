<?php 
    namespace App\Models;
     
    use CodeIgniter\Model;

    class CommentModel extends Model{
        protected $table = 'comments'; 

        public function create($data){ 
            $query = $this->db->table($this->table)->insert($data);

            return $query ? true : false;
        }

        public function getComments($post_id){ 
            $query = $this->db->table($this->table)
                                ->where('post_id' , $post_id)
                                ->join('users' , 'users.id = comments.user_id' , 'RIGHT') 
                                ->get();  
            return $query->getResult();
        }
    }