<?php

namespace Users\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersTable{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getUser($id){
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('user_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getUserByEmail($userEmail){
        if(! $userEmail){
            throw new \Exception("email don't pass.");
        }
        $rowset = $this->tableGateway->select(array('user_email' => $userEmail));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $userEmail");
        }
        return $row;
    }

    public function saveUser(Users $fromController){
        $data = array(
            'user_name' => $fromController->user_name,
            'user_email' => $fromController->user_email,
            'user_password' => $fromController->user_password,
            'user_role'=> $fromController->user_role
        );
        $user_id = (int)$fromController->user_id;
        if ($user_id == 0){
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($user_id)) {
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