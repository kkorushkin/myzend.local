<?php

namespace Users\Model;

class Users{

    public $user_id;
    public $user_name;
    public $user_email;
    public $user_password;
    public $user_role;

    public function  exchangeArray($data){
        //echo __METHOD__." is reached; test echo in line ".__LINE__;
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->user_name = (isset($data['user_name'])) ? $data['user_name'] : null;
        $this->user_email = (isset($data['user_email'])) ? $data['user_email'] : null;
        if(isset($data['user_password'])){
            $this->setPassword($data['user_password']);
        }
        $this->user_role = (isset($data['user_role'])) ? $data['user_role'] : 'guest';
    }

    public function setPassword($data){
        $this->user_password = md5($data);
    }

}