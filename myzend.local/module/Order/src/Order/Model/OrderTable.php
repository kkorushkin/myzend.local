<?php

namespace Order\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class OrderTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function selectCartJoinCollection($cart_id){
        $select = new Select();
        $select->from('cart', 'cart_id = '.$cart_id, array('item_quantity', 'item_price'))
            ->join('items', 'items.item_id = cart.item_id', array('item_name'), 'left')
            ->join('images', 'images.img_item_id = cart.item_id', array('img_link'), 'left')
            ->group('cart.item_id');
        return $this->tableGateway->selectWith($select);
    }

}