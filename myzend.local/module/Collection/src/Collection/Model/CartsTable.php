<?php

namespace Collection\Model;

use Zend\Session\Container;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select,
    Zend\Db\Sql\Insert,
    Zend\Db\Sql\Update,
    Zend\Db\Sql\Where;

class CartsTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->cartsTableGateway = $tableGateway;
    }

    public function insertCart(Carts $collection_item){
//die(var_dump($collection_item));
        $cart_id = $collection_item->cart_id;
        $item_id =  $collection_item->item_id;
        $user_id = $collection_item->user_id;
        $item_quantity = $collection_item->item_quantity;
        $item_price = $collection_item->item_price;
// if cart/item/status exist
        if($this->selectCartItemById($cart_id, $item_id)->count() != 0){
//die($cart_id.' '.$item_id);
            $current = $this->cartsTableGateway->select(array(
                'cart_id' => $cart_id,
                'item_id' => $item_id,
                'status'  => 0
            ));
            foreach($current as $val){
                $item_quantity_in_cart = $val->item_quantity;
                $item_price_in_cart = $val->item_price;
            }
            $sum_qty = $item_quantity_in_cart + $item_quantity;
            $sum_price = $item_price_in_cart + $item_price;
            $data_update = array(
                'item_quantity' => $sum_qty,
                'item_price'    => $sum_price,
            );
            $where_update = array(
                'cart_id' => $cart_id,
                'user_id' => $user_id,
                'item_id' => $item_id,
                'status'  => 0
            );
            $this->cartsTableGateway->update($data_update , $where_update);
        }else{
            $data = array(
                'cart_id'       => $cart_id,
                'user_id'       => $user_id,
                'item_id'       => $item_id,
                'item_quantity' => $item_quantity,
                'item_price'    => $item_price,
                'status'        => 0
            );
            $this->cartsTableGateway->insert($data);
        }

    }

    public function selectCartJoinCollection($cart_id){
        $select = new Select();
        $select->from('carts', array('item_quantity', 'item_price'))
            ->join('items', 'items.item_id = carts.item_id', array('item_name'), 'left')
            ->join('images', 'images.img_item_id = carts.item_id', array('img_link'), 'left')
            ->where->equalTo('carts.cart_id', $cart_id)
            ->where->equalTo('carts.status', 0);
        $select->group('carts.item_id');
        $resultSet =  $this->cartsTableGateway->selectWith($select);
        return $resultSet;
    }

    public  function selectCart($cart_id){
//NOTICE:   get all items with carts.cart_id == '$cart_id' & $user_id == 'carts.user_id'
        if($cart_id){
            $select = new Select();
            $select->from('carts');
            $select->join('images', "images.img_item_id = carts.item_id", array('img_link'), 'left');
            $select->where->equalTo('carts.cart_id', $cart_id);
            $select->where->equalTo('carts.status', 0);
            $select->group('carts.item_id');
            $resultSet = $this->cartsTableGateway->selectWith($select);
        return $resultSet;
        }
    }
    public  function countCartByStatus($cart_id = null, $status = 0){
//NOTICE:
        if($cart_id){
            $select = new Select();
            $select->from('carts');
            $select->join('images', "images.img_item_id = carts.item_id", array('img_link'), 'left');
            $select->where->equalTo('carts.cart_id', $cart_id);
            $select->group('carts.item_id');
            $resultSet = $this->cartsTableGateway->selectWith($select);
        }elseif($status == 0 ){
            $resultSet = $this->cartsTableGateway->select('status = 0')->count();
        }else{
            $resultSet = $this->cartsTableGateway->select('status = 1')->count();
        }
        return $resultSet;
    }
//NOTICE: Select only if item_id or cart_id (or it both) equal passed $cart_id/$item_id
    public function selectCartItemById($cart_id, $item_id){
        (is_null($cart_id)) ? $cart_id = '0' : null ;
        $select = new Select();
        $select->from('carts')
            ->where->equalTo('item_id', $item_id)
            ->where->equalTo('cart_id', $cart_id)
            ->where->equalTo('status', 0);
        return $this->cartsTableGateway->selectWith($select);
    }
//NOTICE: Return an array of 'items_id' in cart == '$cart_id'
    public function selectCartItemIdOnly($cart_id){
        $resultSet = $this->cartsTableGateway->select('item_id', 'cart_id = '.$cart_id);
        return $resultSet;
    }

    public function fetchCartOnItemId($item_id){
        $select = new Select ;
        $select->from('items');
        $select->join('brands', "brands.b_id = items.item_brand", array('b_name'), 'left');
        $select->join('categories', "categories.cat_id = items.item_category", array('cat_name'), 'left');
        $select->join('sub_categories', "sub_categories.subcat_id = items.item_sub_category", array('subcat_name'), 'left');
        $select->join('images', "images.img_item_id = items.item_id", array('img_link'), 'left');
        $select->where("items.item_id = $item_id");
        $resultSet = $this->cartsTableGateway->selectWith($select);
        return $resultSet;
    }

    public function selectSumCartPrice($cart_id = null, $status = 0){
        $select = new Select();
        $select->from('carts')
            ->columns(array('item_price' => new \Zend\Db\Sql\Expression('SUM(item_price)')));
        if(! is_null($cart_id)){
            $select->where->equalTo('carts.cart_id', $cart_id);
        }
        if($status == 1){
            $select->where->equalTo('status', $status);
        }else{
            $select->where->equalTo('status', 0);
        }
        return $this->cartsTableGateway->selectWith($select);
    }

    public function deleteCartById($item_id){
        return $this->cartsTableGateway->delete(array(
            'item_id' => $item_id,
            'status' =>0
        ));
    }

} 