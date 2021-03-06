<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as DbTableAuthAdapter;

class LoginController extends AbstractActionController{

    public function loginAction(){
        $form = $this->getServiceLocator()->get('LoginForm');// Auth also
        //$form = new LoginForm();
        $viewModel = new ViewModel(array(
            'form' =>$form,
        ));
        return $viewModel;
    }

    public function processAction(){
        if (! $this->request->isPost()) {
            return $this->redirect()->toRoute(NULL ,
                array( 'controller' => 'Login',
                    'action' => 'login'
                ));
        }
        $post = $this->request->getPost();
        $form = $this->getServiceLocator()->get('LoginForm');
        //$form = new LoginForm();
        //$inputFilter = new LoginFormFilter();
        //$form->setInputFilter($inputFilter);
        $form->setData($post);
        if (! $form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form' => $form,
            ));
            $model->setTemplate('users/login/login');
            return $model;
        }
        $this->getAuthService()->getAdapter()->setIdentity(
            $this->request->getPost('user_email'))->setCredential(
                $this->request->getPost('user_password')
            );
        $result = $this->getAuthService()->authenticate();
        if ($result->isValid()) {
            $this->getAuthService()->getStorage()->write(
                $this->request->getPost('user_email'));
            return $this->redirect()->toRoute(NULL , array(
                'controller' => 'Login',
                'action' => 'confirm'
            ));
        }else{
            $this->flashMessenger()->setNamespace('NotLogin')->addMessage('Login data not valid');
            return $this->redirect()->toRoute(NULL , array(
                'controller' => 'Login',
                'action' => 'login',
            ));
        }
        //
        /*
        return $this->redirect()->toRoute(NULL , array(
           'controller' => 'Login',
          'action' => 'confirm'
         ));
        */
    }

    public function getAuthService(){
        //print_r($this->request->getPost());
        if (! $this->authservice) {
            //$this->flashMessenger()->setNamespace('NotLogin')->addMessage('error');
            $dbAdapter = $this->getServiceLocator()->get(
                'Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter = new DbTableAuthAdapter(
                $dbAdapter,'user','user_email','user_password', 'MD5(?)');
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;
        }
        return $this->authservice;
    }

    public function confirmAction(){
        $user_email = $this->getAuthService()->getStorage()->read();
        $viewModel = new ViewModel(array(
            'user_email' => $user_email
        ));
        return $viewModel;
    }

}