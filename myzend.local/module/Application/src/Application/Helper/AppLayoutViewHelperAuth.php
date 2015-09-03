<?php

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Placeholder;
use Zend\Session\Container;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as DbTableAuthAdapter;
use Zend\Db\Adapter\Adapter;

class AppLayoutViewHelperAuth extends AbstractHelper implements ServiceLocatorAwareInterface{

    protected $serviceLocator;

    public  function  __invoke(){
        if($this->getAuthService()->hasIdentity()){
            $identity = $this->getAuthService()->getIdentity();
            if(! is_string($identity)){
                foreach($identity as $k => $v){
                    $auth[$k] = $v;
                }
            }else{
                $auth['user_email'] = $identity;
            }
//die(var_dump($auth));
            return $auth['user_email'].'<br /><a href="/collection/logoutMe">[ logout ]</a><a href=""><strike>[ your profile ]</strike></a>';
        }else{
            return 'welcome&nbsp;guest<br /><a href="/users/login">[ login]</a><a href="/users/registration">[ registration ]</a>';
        }
    }

    public  function getAuthService(){

        $adapter = new Adapter(array(
            'driver' => 'Pdo',
            'database' => 'estore',
            'username' => 'root',
            'password' => ''
        ));

        if (! $this->authservice) {
            //$this->flashMessenger()->setNamespace('NotLogin')->addMessage('error');
            $dbTableAuthAdapter = new DbTableAuthAdapter(
                $adapter,'users', 'user_id','user_name', 'user_email','user_password', 'MD5(?)');
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