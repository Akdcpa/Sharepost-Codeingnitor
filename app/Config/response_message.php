<?php
namespace Config;

use CodeIgniter\Config\BaseConfig; 

class response_message extends BaseConfig{

    public static function messages(){
        return [
            "STATUS" => [
                "SUCCESS" => "success",
                "FAILS" => "fails"
            ],
        
            "MESSAGES" => [
                "LOGIN_SUCCESS" => "Logged in successfully",
                "USER_REGISTERED_SUCCESSFULLY" => "User registered successfully",
                "USER_SEARCH" => "User search",
                "POST_SEARCH" => "Post search",
                "LOAD_CONTACTS_SUCCESSFULLY" => "Load contacts successfully",
                "USER_LOGOUT_SUCCESSFULLY" => "User logout successfully",
                "POST_CREATED_SUCCESSFULLY" => "Post created successfully!",
                "POST_GET_SUCCESSFULLY" => "Post get successfully!",
                "COMMENT_ADDED_SUCCESSFULLY" => "Comment added successfully!",
                "LIKE_ADDED_SUCCESSFULLY" => "Like added successfully!",
                "DISLIKE_ADDED_SUCCESSFULLY" => "DisLike added successfully!",
            ]
        ];
    }

}
