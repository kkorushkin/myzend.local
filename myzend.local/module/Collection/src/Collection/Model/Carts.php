<?php

namespace Collection\Model;

class Carts {

    public $cart_id;
    public $user_id;
    public $item_id;
    public $item_quantity;
    public $item_price;
    public $img_link;
    public $item_name;

    public function exchangeArray($data){
        $this->cart_id = (isset($data['cart_id'])) ? $data['cart_id'] : null;
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : 0;
        $this->item_id = (isset($data['item_id'])) ? $data['item_id'] : null;
        $this->item_quantity = (isset($data['item_quantity'])) ? $data['item_quantity'] : null;
        $this->item_price = (isset($data['item_price'])) ? $data['item_price'] : null;
        $this->img_link = (isset($data['img_link'])) ? $data['img_link'] : 'No_image_available.jpg';
        $this->item_name = (isset($data['item_name'])) ? $data['item_name'] : null;
    }

} 