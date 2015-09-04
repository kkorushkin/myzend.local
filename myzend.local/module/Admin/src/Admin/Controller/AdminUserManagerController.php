<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Model\AdminViewModel;
use Admin\Model\AdminUsers;
use Admin\Model\AdminUsersTable;
use Zend\Session\Container;
use Zend\Authentication\AuthenticationService;

class AdminUserManagerController extends AbstractActionController{

    public function indexAction(){
        if($this->identifyThis()) {
            $session = new Container('admin');
            $user_email = $session->user_email;
            $userTable = $this->getServiceLocator()->get('AdminUsersTable');
            $viewModel = new AdminViewModel(array(
                'users' => $userTable->fetchAll(),
                'user_email' => $user_email,
            ));
            return $viewModel;
        }else{
            return $this->redirect()->toRoute('admin', array(
                'controller' => 'Auth',
                'action' => 'auth',
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

    public function editAction(){
        // echo __METHOD__." is reached; test echo in line ".__LINE__;
        $userTable = $this->getServiceLocator()->get('AdminUsersTable'); // to Users/Module.php :: getServiceConfig()
        $user = $userTable->getUserById($this->params()->fromRoute('id'));
        $form = $this->getServiceLocator()->get('UserEditForm');
        $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty()); // !!!
        $form->bind($user);
        $viewModel = new AdminViewModel(array(
            'form'    => $form,
            'user_id' => $this->params()->fromRoute('id')
        ));
        return $viewModel;
    }
/*
 * processAction(). Действие processAction применяется при отправке
пользовательской формы edit; processAction сохраняет обновленную запись
и возвращает управление методу indexAction.
 */
    public function processAction(){
// Получение идентификатора пользователя из POST
        $post = $this->request->getPost(); // $post got POST from edit.phtml
//die(print_r($post));
        $userTable = $this->getServiceLocator()->get('AdminUsersTable'); // $userTable got AdminUsersTable() from Module::getServiceConfig()
// Загрузка сущности Users
        $user = $userTable->getUserById($post->user_id);// $user got $row from AdminUsersTable()::getUser($id)
//die(print_r($user));
// Привязка сущности Users к Form {commented}
        $form = $this->getServiceLocator()->get('UserEditForm');
        //$form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty()); // !!!
        //$form->bind($user);
        $form->setData($post);
//die(print_r($post));
// Сохранение пользователя
        $this->getServiceLocator()->get('AdminUsersTable')->saveUser($post);
//
        return $this->redirect()->toRoute(NULL , array(
            'controller' => 'user-manager',
            'action' => 'index'
        ));
    }

    public function addAction(){
        //$form = new UserAddForm();
        //$viewModel = new ViewModel(array('form'=>$form));
        //echo __METHOD__." is reached; test echo in line ".__LINE__;
        $form = $this->getServiceLocator()->get('UserAddForm');
        $viewModel = new AdminViewModel(array(
            'form' => $form
        ));
        return $viewModel;
    }

    public function  processAddAction(){
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(NULL, array(
                'controller' => 'AdminUserManager',
                'action' => 'add'
                ));
        }
        $post = $this->request->getPost();
        $form = $this->getServiceLocator()->get('UserAddForm');
        print($post->user_mail);
        //$form = new UserAddForm();
        //$inputFilter = new UserAddFormFilter();
        //$form->setInputFilter($inputFilter);
        $form->setData($post);
        if (!$form->isValid()) {
            $model = new AdminViewModel(array(
                'error' => true,
                'form' => $form,
            ));
            $model->setTemplate('/admin/user-manager/add');
            return $model;
        }
// Создание пользователя
        if($this->createUser($form->getData())){
            return $this->redirect()->toRoute(NULL, array(
                'controller' => 'user-manager',
                'action' => 'index'
            ));
        }else{
            $this->flashMessenger()
                ->setNamespace('duplicate')
                ->addMessage('user with this email already exist.');
            return $this->redirect()->toRoute(NULL, array(
                'controller' => 'user-manager',
                'action' => 'add'
            ));
        }

    }

    protected function createUser(array $data){
        /*
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new \Admin\Model\AdminUsers);
        $tableGateway = new \Zend\Db\TableGateway\TableGateway('users',
            $dbAdapter, null, $resultSetPrototype);
        */
        $tableGateway = $this->getServiceLocator()->get('AdminUsersTable');
        //
        $user = new AdminUsers();
        $user->exchangeArray($data);
        //print($user->user_email);
        //$userTable = new AdminUsersTable($tableGateway);
        if($tableGateway->isMailExsist($user->user_email)){
            //var_dump($userTable->isMailExsist($user->user_email));
            return false;
        }else{
            $tableGateway->saveUser($user);
            return true;
        }

    }

    public function deleteAction(){
        $this->getServiceLocator()->get('AdminUsersTable')
            ->deleteUser($this->params()->fromRoute('id'));
        return $this->redirect()->toRoute(NULL , array(
            'controller' => 'user-manager',
            'action' => 'index'
        ));
    }

}