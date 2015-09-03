<?php

namespace Orders\Model;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Cache\Storage\Adapter\Session;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select,
    Zend\Db\Sql\Update;

class OrdersTable implements ServiceLocatorAwareInterface{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
//  @param string $parameter
//  @return string int number of field in 'carts'
    public function selectCartJoinCollection($cart_id){
        $select = new Select();
        $select->from('carts', 'cart_id = '.$cart_id, array('item_quantity', 'item_price'))
            ->join('items', 'items.item_id = carts.item_id', array('item_name'), 'left')
            ->join('images', 'images.img_item_id = carts.item_id', array('img_link'), 'left')
            ->group('carts.item_id');
        return $this->tableGateway->selectWith($select)->count();
    }
    public  function selectCartItemStatusOne($cart_id, $item_id){
//NOTICE:
        if($cart_id){
            $select = new Select();
            $select->from('carts')
                ->where->equalTo('carts.cart_id', $cart_id)
                ->where->equalTo('carts.item_id', $item_id)
                ->where->equalTo('carts.status', 1);
            $select->group('carts.item_id');
            $result = $this->tableGateway->selectWith($select)->count();
            return $result;
        }
    }
//  @param
//  @return
    public function updateStatus($cart_id){
        $this->updateCollectionItemQty($cart_id);
        $data = array('status'=> 1);
        return ($this->tableGateway->update($data, array('cart_id'=>$cart_id))) ? true : false;
    }
    protected function updateCollectionItemQty($cart_id){
        $cart_item = $this->tableGateway->select(array(
            'cart_id' => $cart_id,
            'status' => 0
        ));
        foreach($cart_item as $value){
            $select = new Select();
            $select->from('items')
                ->where->equalTo('items.item_id', $value->item_id);
            $result = $this->tableGateway->selectWith($select)->current();
//die(var_dump($result));
            $item_qty = $result->item_quantity;
            $item_qty = (int)$item_qty;
//die(var_dump($item_qty));
            $item_id = $result->item_id;
            $item_id = (int)$item_id;
//die(var_dump($item_id));
            $current_qty = $item_qty - (int)$value->item_quantity;
//die(var_dump($item_id));
            $table = $this->getServiceLocator()->get('CollectionTable')->updateCollection($item_id, $current_qty);
//die(var_dump($table));
        }
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }
}