<?php

namespace Orders\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class OrdersTable {

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
//  @param
//  @return
    public function updateStatus($cart_id){
        $data = array('status'=> 1);
        return ($this->tableGateway->update($data, array('cart_id'=>$cart_id))) ? true : false;
    }

}