<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as DbTableAuthAdapter;

class IndexController extends AbstractActionController{

    public function indexAction(){
        $who_is = $this->whoIs();
        return new ViewModel(array(
            'who_is' => $who_is,
        ));
    }

    public function  whoIs(){
        if($this->getAuthService()->hasIdentity()){
//die(var_dump($this->getAuthService()->getIdentity()->user_name));
            return $this->getAuthService()->getIdentity()->user_name.'<br /><a href="collection/logoutMe">[ logout ]</a><a href="">[ your profile ]</a>';
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
        return $this->redirect()->toRoute(NULL, array(
            'controller' => 'collection',
            'action' => 'index'
        ));
    }

    protected function jsVerify(){

    }

}
