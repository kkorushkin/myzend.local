<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\AdminViewModel;
use Zend\Session\Container;
use Admin\Model\AdminCollection;
use Zend\Authentication\AuthenticationService;

class AdminCollectionManagerController extends AbstractActionController{

    protected $itemsTable;

    public function indexAction(){
        if($this->identifyThis()){
            $session = new Container('admin');
            $user_email = $session->user_email;
            return new AdminViewModel(array(
                    'collection' => $this->getAdminCollectionTable()->fetchAll(),
                    'user_email' => $user_email,
                )
            );
        }else{
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

    public function getAdminCollectionTable(){
        if (! $this->itemsTable) {
            $this->itemsTable = $this->getServiceLocator()->get('AdminCollectionTable');
        }
        return $this->itemsTable;
    }
// NOTICE:
    public function addFormViewAction(){
        $brands = $this->getBrands();
        $categories = $this->getCategories();
        $sub_cats = $this->getSubCategories();
        $addFormView = $this->getServiceLocator()->get('AdminCollectionManagerForm');
        return new AdminViewModel(array(
            'addFormView' => $addFormView,
            'brands' => $brands,
            'categories' => $categories,
            'subcats' => $sub_cats,
        ));
    }

    private function getBrands(){
       return $this->getAdminCollectionTable()->fetchBrands();
    }

    private function getCategories(){
        return $this->getAdminCollectionTable()->fetchCategories();
    }

    private function getSubCategories(){
        return $this->getAdminCollectionTable()->fetchSubCategories();
    }

    public function addAction(){
        $addFormView = $this->getServiceLocator()->get('AdminCollectionManagerForm');
        //$form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $addFormView->setData($request->getPost());
        }
        if (! $addFormView->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'addFormView' => $addFormView,
            ));
            $model->setTemplate('/admin/admin-collection-manager/add-form-view');
            return $model;
        }
        if ($addFormView->isValid()) {
            $item = new AdminCollection();
            $item->exchangeArray($addFormView->getData());
            $this->getAdminCollectionTable()->saveItem($item);

            // Redirect to list of collection
            return $this->redirect()->toRoute('/admin/collection-manager');
        }
        //eturn array('formFromAddActionFromCollectionController' => $form);
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
//die('enter editAction');
        $item_id = (int) $this->params()->fromRoute('id');
        //
        $form  = $this->getServiceLocator()->get('AdminCollectionManagerForm');
        //
        $item = $this->getAdminCollectionTable()->getItem($item_id);
        //
        $request = $this->getRequest();
//die($request.'<br />'.__LINE__);
        if (!$item_id) {
            return $this->redirect()->toRoute('/admin/collection-manager', array(
                'action' => 'index'
            ));
        }
        //$form->bind($item);
        $item = (array)$item; //
        $form->setData($item);// just becouse form->bind($item) viyobivaetsya
        $form->get('submit')->setAttribute('value', 'Edit');
        //
        return new AdminViewModel(array(
            'item_id' => $item_id,
            'form' => $form,
        ));
    }

    public function deleteAction(){
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('collection-manager');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = $this->getRequest()->getPost('id');
//die($id);
                $this->getAdminCollectionTable()->deleteItem($id);
            }
            // Redirect to list of collection
            return $this->redirect()->toRoute('collection-manager', array(
                'action' => 'index'
            ));
        }

        return array(
            'id'    => $id,
            'item' => $this->getAdminCollectionTable()->getItem($id)
        );
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