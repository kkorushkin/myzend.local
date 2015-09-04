<?php

namespace Collection\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;


class CollectionTable{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($method='asc'){
        $select = new Select ;
        $select->from('items');
            if(is_numeric($method) AND $method != 0){
                $select->where->equalTo('items.item_category', $method);
            }
        //$select->columns(array('item_name','item_brand','item_price'));
        $select->join('brands', "brands.b_id = items.item_brand", array('b_name'), 'left');
        $select->join('categories', "categories.cat_id = items.item_category", array('cat_name'), 'left');
        $select->join('sub_categories', "sub_categories.subcat_id = items.item_sub_category", array('subcat_name'), 'left');
        $select->join('images', "images.img_item_id = items.item_id", array('img_link'), 'left');
        $select->group('items.item_id');
        switch ($method):
            case 'asc':
                $select->order(array('item_price ACS'));break;
            case 'desc':
                $select->order(array('item_price DESC'));break;

        endswitch;
        $select->order(array('item_price '.$method));
        //echo $select->getSqlString();
        $resultSet = $this->tableGateway->selectWith($select);
        //$resultSet = $this->tableGateway->select();
        return $resultSet;
    }
//NOTICE:   SINGLE / GROUP
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
                $select->group('items.item_id');break;
            case 'group':
                break;
        endswitch;
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
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

    public function getItem($item_id){
        $item_id  = (int) $item_id;
        $rowset = $this->tableGateway->select(array('item_id' => $item_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $item_id");
        }
        return $row;
    }

    public function countByCategory($item_category = null){
        (! is_null($item_category)) ? $cat_id = $this->getCatIdByName($item_category) : null;
        return (! is_null($item_category)) ? $this->tableGateway->select('item_category ='.$cat_id)->count() : $this->tableGateway->select()->count();
}

    public function getCatIdByName($item_category){
        $select = new Select();
        $select->from('categories')
            ->columns(array('cat_id'))
            ->where->equalTo('cat_name', $item_category);
        $resultSet = $this->tableGateway->selectWith($select)->toArray();
        return $resultSet[0]['cat_id'];
    }

}
