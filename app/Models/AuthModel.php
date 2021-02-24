<?php 
    namespace App\Models;
     
    use CodeIgniter\Model;

    class AuthModel extends Model{
        protected $table = 'users';
        
        public function register($data){
            $count = $this->table($this->table)
                            ->where('email' , $data['email'])
                            ->countAllResults(); 
            if($count<1) {
                $query = $this->db->table($this->table)->insert($data);
                return $query ? true : false; 
            }
            else{
                return false;
            }
        }

        public function login($email){
            $query = $this->table($this->table)
                          ->where('email' , $email)
                          ->countAllResults(); 
            if($query>0){
            $has_user = $this->table($this->table)
                          ->where('email' , $email)
                          ->limit(1)
                          ->get()
                          ->getRowArray();
            }
            else{
                $has_user = array();
            }
            return $has_user;
        }

        public function getContacts($user_id){
            $query = $this->table($this->table) 
                          ->countAll();
            if($query>0){
            $has_user = $this->table($this->table) 
                            ->where('id !=' ,$user_id  )
                            ->get();
            }
            else{
                $has_user = array();
            }
            return $has_user->getResult();
        }

        public function search($search , $user_id){
            $query = $this->table($this->table) 
                          ->where('id !=' ,$user_id  )
                          ->like('name' , $search)
                          ->countAll();
            if($query>0){
            $has_user = $this->table($this->table)
                            ->where('id !=' ,$user_id  )
                            ->like('name' , $search)
                            ->get();
            }
            else{
                $has_user = array();
            }
            return $has_user->getResult();
        }
}
