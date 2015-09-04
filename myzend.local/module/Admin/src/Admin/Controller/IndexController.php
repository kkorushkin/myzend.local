<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\AdminViewModel; // custom ViewModel with my sub-layout
use Zend\Session\Container;
use Zend\Authentication\AuthenticationService;

class IndexController extends AbstractActionController {

    public function indexAction(){

        if($this->identifyThis()){
            $session = new Container('admin');
            $user_email = $session->user_email;
            return new AdminViewModel(array(
                'user_email' => $user_email,
                'now_in_carts' => $this->countCarts(),
                'signed_users' => $this->countSignedUsers(),
            ));
        }else{
            $this->flashMessenger()
                ->setNamespace('not_admin')
                ->addMessage('ay caramba');
            return $this->redirect()->toRoute('admin', array(
                'controller' => 'Auth', 'action' => 'auth'
            ));
        }

    }

    private  function identifyThis(){
        $auth = new AuthenticationService();
        //die($auth->getIdentity()->user_role);
        if($auth->getIdentity()->user_role == 'admin'){
            return true;
        }else{
            return false;
        } ;
    }

    protected function countCarts(){
        return $this->getServiceLocator()->get('CartTable')->selectCart();
    }

    protected function countOrders(){

    }

    protected function countSignedUsers(){
        return $this->getServiceLocator()->get('AdminUsersTable')->fetchAll()->count();
    }

} 