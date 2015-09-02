<?php

namespace Collection\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Placeholder;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as DbTableAuthAdapter;

use Zend\Db\Adapter\Adapter;

class CollectionViewHelperAuth extends AbstractHelper implements ServiceLocatorAwareInterface{

    protected $serviceLocator;

    public  function  __invoke(){

        if($this->getAuthService()->hasIdentity()){
            return $this->getAuthService()->getIdentity().'<br /><a href="collection/logoutMe">[ logout ]</a><a href="">[ your profile ]</a>';
        }else{
            return 'welcome&nbsp;guest<br /><a href="users/login">[ login]</a><a href="users/registration">[ registration ]</a>';
        }
    }

    public  function getAuthService(){
        $adapter = new Adapter(array(
            'driver' => 'Pdo',
            'database' => 'estore',
            'username' => 'root',
            'password' => ''
        ));
        //print_r($this->request->getPost());
        if (! $this->authservice) {
            //$this->flashMessenger()->setNamespace('NotLogin')->addMessage('error');
            $dbTableAuthAdapter = new DbTableAuthAdapter(
                $adapter,'users','user_email','user_password', 'MD5(?)');
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;
        }
        return $this->authservice;
    }

    protected function  logoutMeAction(){
        $auth = $this->getAuthService()->clearIdentity();
        return $this->redirect()->toRoute(NULL, array(
            'controller' => 'collection',
            'action' => 'index'
        ));
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

} 