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
            $vm = new AdminViewModel(array(
                'user_email' => $user_email,
                'carts' => $this->countCarts(),
                'carts_sum' => $this->countCartsSum(),
                'orders' => $this->countOrders(),
                'orders_sum' => $this->countOrdersSum(),
                'signed_users' => $this->countSignedUsers(),

            ));
            return $vm->setTerminal(true);
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
        }
    }

    protected function countCarts(){
        return $this->getServiceLocator()->get('CartsTable')->countCartByStatus();
    }
    protected function countCartsSum(){
        $data = $this->getServiceLocator()->get('CartsTable')->selectSumCartPrice(null, 0);
        foreach($data as $v){
            $sum = $v;
        }
        return $sum->item_price;
    }

    protected function countOrders(){
        return $this->getServiceLocator()->get('CartsTable')->countCartByStatus(null, 1);
    }
    protected function countOrdersSum(){
        $data = $this->getServiceLocator()->get('CartsTable')->selectSumCartPrice(null, 1);
        foreach($data as $v){
            $sum = $v;
        }
        return $sum->item_price;
    }

    protected function countSignedUsers(){
        return $this->getServiceLocator()->get('AdminUsersTable')->fetchAll()->count();
    }

} 