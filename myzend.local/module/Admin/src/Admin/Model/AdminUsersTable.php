<?php

namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;

class AdminUsersTable{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getRole($user_email){
        $rowset = $this->tableGateway->select(array('user_email' => $user_email));
        $row = $rowset->current()->user_role;
        if (!$row) {
            throw new \Exception("Could not find row $user_email");
        }
        return $row;
    }

    public function getName($user_email){
        $rowset = $this->tableGateway->select(array('user_email' => $user_email));
        $row = $rowset->current()->user_name;
        if (!$row) {
            throw new \Exception("Could not find row $user_email");
        }
        return $row;
    }

    public function isMailExsist($user_email){
        $rowset = $this->tableGateway->select(array('user_email' => $user_email));
        $row = $rowset->current()->user_email;
        return (! empty($row)) ? true : false;
    }

    public function getUserById($user_id){
//die($user_id);
        $rowset = $this->tableGateway->select(array('user_id' => $user_id));// equal "select * from users where user_id = $user_id;"
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $user_id");
        }
        return $row;
    }

    public function getUserByEmail($userEmail){
        $rowset = $this->tableGateway->select(array('user_email' => $userEmail));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $userEmail");
        }
        return $rowset;
    }

    public function saveUser($user){
        $data = array(
            'user_name' => $user->user_name,
            'user_email' => $user->user_email,
            'user_password' => $user->user_password,
            'user_role'=> $user->user_role
        );
        $user_id = $user->user_id;
//die(var_dump($data));
        if ($user_id == 0){
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUserById($user_id)) {
//die(var_dump($data));
                $this->tableGateway->update($data, array('user_id' => $user_id));
            } else {
                throw new \Exception('User ID does not exist');
            }
        }
    }

    public function deleteUser($userId){
        $userId = (int)$userId;
        $this->tableGateway->delete(array('user_id' => $userId));
    }

}