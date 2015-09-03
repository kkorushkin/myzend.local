<?php

namespace Admin\Model;

class AdminCollection {

    public $item_id;
    public $item_name;
    public $item_brand;
    public $item_description;
    public $item_price;
    public $item_category;
    public $item_sub_category;
    public $b_name;
    public $cat_name;
    public $subcat_name;
    public $item_quantity;
    public $item_qty_status;
    public $img_link;

    const COLL_IMG_PATH = '/img/CollectionImages/';

    public function exchangeArray($data)
    {
        $this->item_id = (isset($data['item_id'])) ? $data['item_id'] : null;
        $this->item_name = (isset($data['item_name'])) ? $data['item_name'] : null;
        $this->item_brand = (isset($data['item_brand'])) ? $data['item_brand'] : null;
        $this->item_description = (isset($data['item_description'])) ? $data['item_description'] : null;
        $this->item_price = (isset($data['item_price'])) ? $data['item_price'] : null;
        $this->item_category  = (isset($data['item_category'])) ? $data['item_category'] : null;
        $this->item_sub_category  = (isset($data['item_sub_category'])) ? $data['item_sub_category'] : null;
        $this->b_name  = (isset($data['b_name'])) ? $data['b_name'] : null;
        $this->cat_name = (isset($data['cat_name'])) ? $data['cat_name'] : null;
        $this->subcat_name = (isset($data['subcat_name'])) ? $data['subcat_name'] : null;
        $this->item_quantity = (isset($data['item_quantity'])) ? $data['item_quantity'] : null;
        $this->item_qty_status = (isset($data['item_qty_status'])) ? $data['item_qty_status'] : null;
        $this->img_link = (isset($data['img_link'])) ? $data['img_link'] : 'No_image_available.jpg';

    }

} 