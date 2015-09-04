<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Admin\Form;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Zend\Session\Container;

class AuthController extends AbstractActionController{

    protected $authservice;

    public function authAction(){ // pass LoginForm into auth.phtml (admin/index)
        $form = $this->getServiceLocator()->get('LoginForm');
        $viewModel = new ViewModel(array(
            'form' =>$form,
        ));
        return $viewModel;
    }

    public function processAction(){ // here come the data from LoginForm(admin/index)
        if (! $this->request->isPost()) {
            return $this->redirect()->toRoute(NULL,
                array('controller' => 'Auth',
                    'action' => 'auth'
                ));
        }
        $post = $this->getRequest()->getPost();
//
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
//
        $config = $this->getServiceLocator()->get('Config');
//
        $salt = $config['salt'];
//
        $authAdapter = new CredentialTreatmentAdapter(
            $dbAdapter,
            'users',
            'user_email',
            'user_password',
            "MD5(?) AND user_role = 'admin'"
            //"MD5(CONCAT('$salt', ?, user_password)) AND user_role = 'admin'"
        );
//
        $authAdapter
            ->setIdentity($post->user_email)
            ->setCredential($post->user_password)
        ;
//
        $auth = new AuthenticationService();
        $result = $auth->authenticate($authAdapter);
//
        switch ($result->getCode()):
            case Result::FAILURE_IDENTITY_NOT_FOUND:
                //
                $this->flashMessenger()
                    ->setNamespace('not_admin')
                    ->addMessage('wrong email/pasword');
                //
                return $this->redirect()->toRoute(NULL, array(
                    'controller' => 'Auth', 'action' => 'auth'
                ));
                //
                break;
            case Result::FAILURE_CREDENTIAL_INVALID:
                //
                $this->flashMessenger()
                    ->setNamespace('not_admin')
                    ->addMessage('admin-only allowed');
                //
                return $this->redirect()->toRoute(NULL, array(
                    'controller' => 'Auth', 'action' => 'auth'
                ));
                //
                break;
            case Result::SUCCESS:
                $storage = $auth->getStorage();
                $storage->write($authAdapter->getResultRowObject(
                    null,
                    'user_password'
                ));
                $session = new Container('admin');
                $time = 900;
                if($post->rememberMe){
                    $session -> remember = $time;
                }
                $session -> user_email = $post->user_email;
                return $this->redirect()->toRoute('index' , array( // instead of NULL as usually - route mane !!!
                    'controller' => 'Index',
                    'action' => 'index',
                ));
                break;
            default:
                //
                break;
        endswitch;
    }

   protected function  logoutMeAction(){
       $auth = new AuthenticationService();
       $auth->clearIdentity();
       return $this->redirect()->toRoute(NULL, array(
           'controller' => 'Auth', 'action' => 'auth'
       ));
   }
}