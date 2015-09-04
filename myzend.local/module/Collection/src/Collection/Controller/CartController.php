<?php

namespace Collection\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Collection\Model\Orders;
use Collection\Model\OrdersTable;
use Collection\Model\Cart;
use Collection\Model\CartTable;

use Zend\Session\Container;

class CartController  extends AbstractActionController{

    public function indexAction(){
//die('herE !');
        $sessid = new Container();
        $cart_id = $sessid->getDefaultManager()->getId();

        $cart = $this->getCartTable()->selectCart($cart_id);
        $count = $cart->count();
        $is_empty = $this->isEmpty($cart);
        $disable_checkout = $this->disableCheckout($cart);
//die($disable_checkout);
        $viewModel = new ViewModel(array(
            'is_empty' => $is_empty,
            'sessid' => $cart_id,
            'cart' => $cart,
            'count' => $count,
            'disable_checkout' => $disable_checkout
        ));
//die(print_r($cart));
        $viewModel
            ->setTerminal(true);
        return $viewModel;

    }

    private  function disableCheckout($cart){
        return ((bool)$this->isEmpty($cart) == 1) ? 'disabled="disabled"' : '';
    }

   public function  isEmpty($result){
      return ($result->count() == 0) ? '<div class="cart-informer">Your cart is empty.<br /><a>Continue shopping</a></div>' : '' ;
   }

    public function cartIteratorAction(){

    }

    public function cartShowAction(){

    }

    public function cartRemoveAction(){
        $item_id = $this->getRequest()->getPost('item_id');
        return $this->getServiceLocator()->get('CartTable')->deleteCartById($item_id);
    }

    public function cartOrderAction(){

    }

    public function cartRefreshInformerAction(){

    }

    public function getCartTable(){
        $cartTable = $this->getServiceLocator()->get('CartTable');
        return $cartTable;
    }

} 