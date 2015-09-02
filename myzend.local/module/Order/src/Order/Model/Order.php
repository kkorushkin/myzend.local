<?php

namespace Order\Model;

class Order {

    public $customer_id;
    public $item_id;
    public $item_quantity;
    public $total_price;
    public $order_date;

    public function exchangeArray($data){
        $this->customer_id = (isset($data['customer_id'])) ? $data['customer_id'] : null;
        $this->item_id = (isset($data['item_id'])) ? $data['item_id'] : null;
        $this->item_quantity = (isset($data['item_quantity'])) ? $data['item_quantity'] : null;
        $this->total_price = (isset($data['total_price'])) ? $data['total_price'] : null;
        $this->order_date = (isset($data['order_date'])) ? $data['order_date'] : null;
    }

} 