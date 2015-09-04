<?php

namespace Collection\Model;

use Zend\Session\Container;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Where;

class CartTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->cartTableGateway = $tableGateway;
    }

    public function insertCart(Cart $collection_item){
//die($collection_item->cart_id.' '.$collection_item->item_id.' '.$collection_item->item_price);
        if(! $this->selectCartItemById($collection_item->cart_id, $collection_item->item_id)->count() == 0){
//die(var_dump($this->selectCart($collection_item->cart_id)->count()));
            $current = $this->cartTableGateway->select(array(
                'cart_id'=>$collection_item->cart_id,
                'item_id'=>$collection_item->item_id,
            ));
            foreach($current as $val){
                $item_quantity_in_cart = $val->item_quantity;
                $item_price_in_cart = $val->item_price;
            }
//die($item_quantity_in_cart.' '.$item_price_in_cart);
            $data = array(
                'item_quantity' => $item_quantity_in_cart + $collection_item->item_quantity,
                'item_price' => $item_price_in_cart + $collection_item->item_price,
            );
            $where_update = array(
                'cart_id' => $collection_item->cart_id,
                'item_id' => $collection_item->item_id,
            );
            $this->cartTableGateway->update($data , $where_update);
        }else{
//die('else');
            $data = array(
                'cart_id' => $collection_item->cart_id,
                'item_id' => $collection_item->item_id,
                'item_quantity' => $collection_item->item_quantity,
                'item_price' => $collection_item->item_price,
            );
            $this->cartTableGateway->insert($data);
        }

    }

    public function selectCartJoinCollection($cart_id){
//die($cart_id);
        $select = new Select();
        $select->from('cart', array('item_quantity', 'item_price'))
            ->join('items', 'items.item_id = cart.item_id', array('item_name'), 'left')
            ->join('images', 'images.img_item_id = cart.item_id', array('img_link'), 'left')
            ->where->equalTo('cart.cart_id', $cart_id);
        $select->group('cart.item_id');
        $resultSet =  $this->cartTableGateway->selectWith($select);
        return $resultSet;
    }

    public  function selectCart($cart_id = null){
//NOTICE:   get all items with cart_id == '$cart_id'
//NOTICE:   if $cart_id don't passed, return num of all items in cart (admin needed)
        if($cart_id){
            $select = new Select();
            $select->from('cart');
            $select->join('images', "images.img_item_id = cart.item_id", array('img_link'), 'left');
            $select->where->equalTo('cart.cart_id', $cart_id);
            $select->group('cart.item_id');
            $resultSet = $this->cartTableGateway->selectWith($select);
        }else{
            $resultSet = $this->cartTableGateway->select()->count();
        }
        return $resultSet;
    }
//NOTICE: Select only if item_id or cart_id (or it both) equal passed $cart_id/$item_id
    public function selectCartItemById($cart_id, $item_id = null){
        (is_null($cart_id)) ? $cart_id = '0' : null ;
        $select = new Select();
        $select->from('cart');
        if(! is_null($item_id)){
            $select->where->equalTo('item_id', $item_id);
        }

        $select->where->equalTo('cart_id', $cart_id);
        return $this->cartTableGateway->selectWith($select);
    }
//NOTICE: Return an array of 'items_id' in cart == '$cart_id'
    public function selectCartItemIdOnly($cart_id){
        $resultSet = $this->cartTableGateway->select('item_id', 'cart_id = '.$cart_id);
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
        $resultSet = $this->cartTableGateway->selectWith($select);
        return $resultSet;
    }

    public function selectSumCartPrice($cart_id){
        $select = new Select();
        $select->from('cart')
            ->columns(array('item_price' => new \Zend\Db\Sql\Expression('SUM(item_price)')))
            ->where->equalTo('cart.cart_id', $cart_id);
        return $this->cartTableGateway->selectWith($select);
    }

    public function deleteCartById($item_id){
        return $this->cartTableGateway->delete('item_id = '.$item_id);
    }

} 