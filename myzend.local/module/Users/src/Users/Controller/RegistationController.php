<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\ResultSet\ResultSet;
use Users\Form\RegisterForm;
use Users\Form\RegisterFormFilter;

use Users\Model\User;
use Users\Model\UserTable;

class RegistationController extends AbstractActionController{

    public function registationAction(){
        $form = $this->getServiceLocator()->get('RegisterForm');
        //echo "<pre>"; print_r($form);die("e");
        //$form = new RegisterForm();
        $viewModel = new ViewModel(array(
            'form'=>$form
        ));
        return $viewModel;
    }


    public function processAction(){
        if (! $this->request->isPost()) {
            return $this->redirect()->toRoute(NULL ,
                array( 'controller' => 'Register',
                    'action' => 'index'
                ));
        }
        $post = $this->request->getPost();
        $form = $this->getServiceLocator()->get('RegisterForm');
        //$form = new RegisterForm();
        //$inputFilter = new RegisterFormFilter();
        //$form->setInputFilter($inputFilter);
        $form->setData($post);
        if (! $form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form' => $form,
            ));
            $model->setTemplate('users/register/index');
            return $model;
        }
        // Создание пользователя
        if($this->createUser($form->getData())){
            return $this->redirect()->toRoute(NULL , array(
                'controller' => 'Register',
                'action' => 'confirm'
            ));
        }

    }

    protected function createUser(array $data){
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new \Users\Model\User);
        $tableGateway = new \Zend\Db\TableGateway\TableGateway('user',
            $dbAdapter, null, $resultSetPrototype);
        $user = new User();
        $user->exchangeArray($data);
        $userTable = $this->getServiceLocator()->get('UserTable');
        //$userTable = new UserTable($tableGateway);
        $userTable->saveUser($user);
        return true;
    }

    public function confirmAction(){
        $viewModel = new ViewModel();
        return $viewModel;
    }

}