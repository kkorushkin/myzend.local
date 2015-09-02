<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\AdminViewModel;
use Zend\Session\Container;
use Admin\Model\AdminCollection;
use Zend\Authentication\AuthenticationService;
use Zend\File\Transfer\Adapter\Http;

class AdminCollectionManagerController extends AbstractActionController{

    protected $itemsTable;

    public function indexAction(){
        if($this->identifyThis()){
            $session = new Container('admin');
            $user_email = $session->user_email;
            $vm =  new AdminViewModel(array(
                    'collection' => $this->getAdminCollectionTable()->fetchAll(),
                    'user_email' => $user_email,
                )
            );
            return $vm->setTerminal(true);
        }else{
            return $this->redirect()->toRoute('admin', array(
                'controller' => 'Auth', 'action' => 'auth'
            ));
        }
    }

    protected function identifyThis(){
        $auth = new AuthenticationService();
//die($auth->getIdentity()->user_role);
        if($auth->getIdentity()->user_role == 'admin'){
            return true;
        }else{
            return false;
        }
    }

    protected  function getAdminCollectionTable(){
        if (! $this->itemsTable) {
            $this->itemsTable = $this->getServiceLocator()->get('AdminCollectionTable');
        }
        return $this->itemsTable;
    }
// NOTICE:
    public function addFormViewAction(){
        $form = $this->getServiceLocator()->get('AdminCollectionManagerForm');
        $brands = $this->getBrands();
        $categories = $this->getCategories();
        $sub_cats = $this->getSubCategories();
//die('<h1>L U C K !</h1>');
        $vm = new AdminViewModel(array(
            'form' => $form,
            'brands' => $brands,
            'categories' => $categories,
            'sub_cats' => $sub_cats,
        ));
        return $vm->setTerminal(true);
    }
//NOTICE: RETURN AN ARRAY OF BRANDS NAME
    private function getBrands(){
       return $this->getAdminCollectionTable()->fetchBrands();
    }
//NOTICE: RETURN AB ARRAY OF CATEGORIES NAME
    private function getCategories(){
        return $this->getAdminCollectionTable()->fetchCategories();
    }
//NOTE: RETURN AB ARRAY OF SUB-CATEGORIES NAME
    private function getSubCategories(){
        return $this->getAdminCollectionTable()->fetchSubCategories();
    }
//NOTICE:
    public function addAction(){
//die(var_dump($this->getRequest()->getFiles()->toArray()));
//die(var_dump($this->getRequest()->getPost()));
        $file = $this->getRequest()->getFiles()->toArray();
        $adapter = new Http();
        $root_path = getcwd(); // app root path

        if(! is_dir($root_path.'/public/img/upload')){
            mkdir($root_path.'/public/img/upload');
        }

        $adapter->setDestination($root_path.'/public/img/upload');
/*
        if (!$adapter->receive()) {
            $messages = $adapter->getMessages();
            echo implode("\n", $messages);
        }
*/
        $addFormView = $this->getServiceLocator()->get('AdminCollectionManagerForm');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $addFormView->setData($request->getPost());
        }
        if (! $addFormView->isValid()) {
            $brands = $this->getBrands();
            $categories = $this->getCategories();
            $sub_cats = $this->getSubCategories();
            $vm = new AdminViewModel(array(
                'error' => true,
                'form' => $addFormView,
                'brands' => $brands,
                'categories' => $categories,
                'sub_cats' => $sub_cats,
            ));
            $vm->setTemplate('/admin/admin-collection-manager/add-form-view');
            return $vm->setTerminal(true);
        }
        if ($addFormView->isValid()) {
            $item = new AdminCollection();
            $item->exchangeArray($addFormView->getData());
            $this->getAdminCollectionTable()->saveItem($item);
// Redirect to list of collection
            return $this->redirect()->toRoute(null, array(
                'controller' => 'collection-manager',
                'action' => 'index'
            ));
        }
        //return array('formFromAddActionFromCollectionController' => $form);
    }

    public function editAction(){
        if ($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost();
//die(print_r($post));
            $item = new AdminCollection();
//die(print_r($post).'<br />'.__LINE__);
            $item->exchangeArray($post);
//die($post->item_name);
            $this->getAdminCollectionTable()->saveItem($item);
            return $this->redirect()->toRoute(null, array(
                'controller' => 'collection-manager',
                'action' => 'index'
            ));
        }
    }

    public function editViewAction(){
        $item_id = $this->params()->fromRoute('id');
        $form  = $this->getServiceLocator()->get('AdminCollectionManagerForm');
        $item = $this->getAdminCollectionTable()->getItem($item_id);
        if (!$item_id) {
            return $this->redirect()->toRoute('/admin/collection-manager', array(
                'action' => 'index'
            ));
        }
        //$form->bind($item);
        $item = (array)$item; //
        $form->setData($item);// just becouse form->bind($item) viyobivaetsya
        $form->get('submit')->setAttribute('value', 'Edit');
        $brands = $this->getBrands();
        $categories = $this->getCategories();
        $vm =  new AdminViewModel(array(
            'item_id' => $item_id, // for form action routing
            'form' => $form,
            'brands' => $brands,
            'categories' => $categories
        ));
        return $vm->setTerminal(true);
    }

    public function deleteAction(){
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('collection-manager', array(
                'action' => 'index'
            ));
        }
        $request = $this->getRequest();
        if ($request->isGet()) {
            $this->getAdminCollectionTable()->deleteItem($id);
            }
// Redirect to list of collection
            return $this->redirect()->toRoute('collection-manager', array(
                'action' => 'index'
            ));
        }

    public function formDbAdapterAction(){
        $vm = new ViewModel();
        $vm->setTemplate('collection/collection/add.phtml');
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $formDbSel = new DbAdapterForm($dbAdapter);
        return $vm->setVariables(array(
            'formDbAdapterActionFromCollectionController' => $formDbSel
        ));
    }

    private function toArray($data){
//die(var_dump($data));
        $data_array = array();
        foreach($data as $k=>$v){
            $data_array[$k] = $v;
        }
//die(var_dump($data_array));
        return $data_array;
    }

}
?>