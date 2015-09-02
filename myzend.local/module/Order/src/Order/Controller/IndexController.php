<?php

namespace Order\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;

class IndexController extends AbstractActionController{

    public function indexAction(){

        $session_user = new Container('user');
        $cart_id = $session_user->getDefaultManager()->getId();

        $form = $this->getServiceLocator()->get('OrderForm');
        $cart_id = $this->getCartId();
        $order_collection = $this->getItemIdList($cart_id);
        $total_price = $this->getCartTotalPrice($cart_id);
        //$who_is = $this->whoIs();
        return new ViewModel(array(
            'order_form' => $form,
            'order_collection' => $order_collection,
            'total_price' => $total_price,
            //'who_is' => $who_is,
        ));
    }

    public function processAction(){
        if (! $this->request->isPost()) {
            return $this->redirect()->toRoute(NULL ,
                array( 'controller' => 'Index',
                    'action' => 'index'
                ));
        }
        $post = $this->getRequest()->getPost();
        $form = $this->getServiceLocator()->get('OrderForm');
        $form->setData($post);
        if (! $form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form' => $form,
            ));
            $model->setTemplate('order');
            return $model;
        }
    }

    protected function getCartId(){
        $user_session = new Container();
        return $user_session->getDefaultManager()->getId();
    }

    protected function getItemIdList($cart_id){
        $data =  $this->getServiceLocator()->get('CartsTable')->selectCartJoinCollection($cart_id);
        return $this->toArray($data);
    }

    protected function getCartTotalPrice($cart_id){
        $data = $this->getServiceLocator()->get('CartsTable')->selectSumCartPrice($cart_id);
        $data =  $this->toArray($data);
//die(var_dump($data->item_price));
        return $data[0]->item_price;
    }

    protected function toArray($obj){
        foreach($obj as $k=>$v){
            $myArray[$k] = $v;
        }
        return $myArray;
    }

    public function bindUser($id){
//die( __METHOD__." is reached; test echo in line ".__LINE__);
        $userTable = $this->getServiceLocator()->get('UsersTable'); // to Users/Module.php :: getServiceConfig()
        $user = $userTable->getUserById($this->params()->fromRoute('id'));
        $form = $this->getServiceLocator()->get('OrderForm');
        $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty()); // !!!
        $form->bind($user);
        return $form;
    }
/*
    public  function  whoIs(){
        if($this->getAuthService()->hasIdentity()){
            return $this->getAuthService()->getIdentity().'&nbsp;<a href="collection/logoutMe">logout</a>';
        }else{
            return 'welcome&nbsp;guest<br /><a href="users/login">[ login]</a><a href="users/registration">[ registration ]</a>';
        }
    }

    protected   function getAuthService(){
        //print_r($this->request->getPost());
        if (! $this->authservice) {
            //$this->flashMessenger()->setNamespace('NotLogin')->addMessage('error');
            $dbAdapter = $this->getServiceLocator()->get(
                'Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter = new DbTableAuthAdapter(
                $dbAdapter,'users','user_email','user_password', 'MD5(?)');
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;
        }
        return $this->authservice;
    }

    protected function  logoutMeAction(){
        $auth = $this->getAuthService()->clearIdentity();
        return $this->redirect()->toRoute(null, array(
            'controller' => 'Index',
            'action' => 'index'
        ));
    }
*/
}