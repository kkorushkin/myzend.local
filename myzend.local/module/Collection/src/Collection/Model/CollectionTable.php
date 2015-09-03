<?php

namespace Collection\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;


class CollectionTable{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($method = 'asc', $category = null, $price_equal_to = null, $more = null, $less = null, $color = null){
//die(__METHOD__."<br />\n\r".$category."<br />\n\r".$method."<br />\n\r".$price_equal_to."<br />\n\r".$more_then."<br />\n\r".$less_then);
        $select = new Select ;
        $select->from('items');
            if(! is_null($category) && $category != ''){
                $cat_id = $this->getCatIdByName($category);
                $select->where->equalTo('items.item_category', $cat_id);
            }
            if((! is_null($price_equal_to)) && $price_equal_to != 0){
                $select->where->equalTo('items.item_price', $price_equal_to);
            }
            if((! is_null($more)) && (! is_null($less))){
                $select->where->between('items.item_price', $more, $less);
            }
            if(! is_null($color) && $color != 'colored'){
                $color_id = $this->getColorIdByName($color);
                $select->where->like('items.item_color', '%'.$color_id->color_id.'%');
            }
        //$select->columns(array('item_name','item_brand','item_price'));
        $select->join('brands', "brands.b_id = items.item_brand", array('b_name'), 'left');
        $select->join('categories', "categories.cat_id = items.item_category", array('cat_name'), 'left');
        $select->join('sub_categories', "sub_categories.subcat_id = items.item_sub_category", array('subcat_name'), 'left');
        $select->join('images', "images.img_item_id = items.item_id", array('img_link'), 'left');
        $select->group('items.item_id');
        switch ($method):
            case 'asc':
                $select->order(array('item_price ACS'));
                break;
            case 'desc':
                $select->order(array('item_price DESC'));
                break;
        endswitch;
        $select->order(array('item_price '.$method));
//die($select->getSqlString());
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
//NOTICE: SINGLE / GROUP
    public function fetchById($item_id, $param = 'single'){
//die($item_id);
        $select = new Select;
        $select->from('items');
        $select->join('brands', "brands.b_id = items.item_brand", array('b_name'), 'left');
        $select->join('categories', "categories.cat_id = items.item_category", array('cat_name'), 'left');
        $select->join('sub_categories', "sub_categories.subcat_id = items.item_sub_category", array('subcat_name'), 'left');
        $select->join('images', "images.img_item_id = items.item_id", array('img_link'), 'left');
        $select->where->equalTo('items.item_id', $item_id);
        switch ($param):
            case 'single':
                $select->group('items.item_id');
                $rowset = $this->tableGateway->selectWith($select);
                $resultSet = $rowset->current();
                break;
            case 'group':
                $resultSet = $this->tableGateway->selectWith($select);
                break;
        endswitch;
        return $resultSet;
    }
// NOTICE:
    public function getColorIdByName($color){
        $select = new Select;
        $select->from('colors')
            ->where->equalTo('color_name', $color);
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
//die(var_dump($row));
        return $row;
    }

    public function fetchByName($item_name){
            $select = new Select;
            $select->from('items')
                ->join('brands', "brands.b_id = items.item_brand", array('b_name'), 'left')
                ->join('categories', "categories.cat_id = items.item_category", array('cat_name'), 'left')
                ->join('sub_categories', "sub_categories.subcat_id = items.item_sub_category", array('subcat_name'), 'left')
                ->join('images', "images.img_item_id = items.item_id", array('img_link'), 'left')
                ->where->like('items.item_name', "%$item_name%");
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet;
        }
// NOTICE:
    public function fetchBrands(){
        $select = new Select ;
        $select->from('brands');
        $select->columns(array('b_name'));
        $resultSet = $this->tableGateway->selectWith($select);
        foreach ($resultSet as $res) {
            $selectData[$res['id']] = $res['title'];
        }
        return $selectData;

    }
// NOTICE:
    public function getItem($item_id){
        $item_id  = (int) $item_id;
        $rowset = $this->tableGateway->select(array('item_id' => $item_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $item_id");
        }
        return $row;
    }
// NOTICE:
    public function countByCategory($item_category = null){
        (! is_null($item_category)) ? $cat_id = $this->getCatIdByName($item_category) : null;
        return (! is_null($item_category)) ? $this->tableGateway->select('item_category ='.$cat_id)->count() : $this->tableGateway->select()->count();
}
// NOTICE:
    public function getCatIdByName($item_category){
        $select = new Select();
        $select->from('categories')
            ->columns(array('cat_id'))
            ->where->equalTo('cat_name', $item_category);
        $resultSet = $this->tableGateway->selectWith($select)->toArray();
        return $resultSet[0]['cat_id'];
    }
// NOTICE:
    public function updateCollection($item_id, $current_qty){
//die($item_id.$item_qty);
        $result = $this->tableGateway->update(
            array('item_quantity' => $current_qty,),
            array('item_id' => $item_id)
        );
        $this->updateQtyStatus($item_id, $current_qty);
        return $result;
    }
    protected function updateQtyStatus($item_id, $current_qty){
        $current_qty = (int)$current_qty;
        if($current_qty >= 50){
            return $this->tableGateway->update(
                array('item_qty_status' => 2),
                array('item_id' => $item_id)
            );
        }
        if($current_qty < 50 && $current_qty > 0){
            return $this->tableGateway->update(
                array('item_qty_status' => 1),
                array('item_id' => $item_id)
            );
        }
        if($current_qty <= 0){
            return $this->tableGateway->update(
                array('item_qty_status' => 0),
                array('item_id' => $item_id)
            );
        }
    }

}
