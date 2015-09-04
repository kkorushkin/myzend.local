<?php

namespace Order\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController{

    public function indexAction(){
        $form = $this->getServiceLocator()->get('OrderForm');
        $cart_id = $this->getCartId();
        $order_collection = $this->getItemIdList($cart_id);
        $total_price = $this->getCartTotalPrice($cart_id);
        return new ViewModel(array(
            'order_form' => $form,
            'order_collection' => $order_collection,
            'total_price' => $total_price,
        ));
    }

// NOTICE: RETURN AN ARRAY OF SPECIFIC PROPERTIES OF OBJECT PASSED IN $PARAM
// now it's return array of 'item_id'
/*
    protected  function toArrayGetSingle($obj, $param = null){
        foreach($obj as $k=>$v){
            $i++;
            $myArray[$i] = $v->$param;
        }
        return $myArray;
    }
*/
    protected function getCartId(){
        $user_session = new Container();
        return $user_session->getDefaultManager()->getId();
    }

    protected function getItemIdList($cart_id){
        $data =  $this->getServiceLocator()->get('CartTable')->selectCartJoinCollection($cart_id);
        return $this->toArray($data);
    }

    protected function getCartTotalPrice($cart_id){
        $data = $this->getServiceLocator()->get('CartTable')->selectSumCartPrice($cart_id);
        $data =  $this->toArray($data);
//die(var_dump($data->item_price));
        return $data[0]->item_price;
    }

    protected function toArray($obj){
        foreach($obj as $k=>$v){
            $myArray[$k] = $v;
        }
        return $myArray;
    }

/*
        protected function getCollectionItemById(array $item_ids){
            $collection= $this->getServiceLocator()->get('CollectionTable');
            foreach($item_ids as $val){
    //die(var_dump($val));
                $i++;
                $coll = $collection->fetchById($val, 'single');
                foreach($coll as $k=>$v){
                    $temp[$k] = $v;
                }
                $cart_collection[$i] = $temp[0];
            }
    //die(var_dump($cart_collection));
            return $cart_collection;
        }
*/
}