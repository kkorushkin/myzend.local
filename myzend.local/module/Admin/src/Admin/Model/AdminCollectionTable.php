<?php

namespace Admin\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class AdminCollectionTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        $select = new Select ;
        $select->from('items');
        //$select->columns(array('item_name','item_brand','item_price'));
        $select->join('brands', "brands.b_id = items.item_brand", array('b_name'), 'left');
        $select->join('categories', "categories.cat_id = items.item_category", array('cat_name'), 'left');
        $select->join('sub_categories', "sub_categories.subcat_id = items.item_sub_category", array('subcat_name'), 'left');
        $select->join('images', "images.img_item_id = items.item_id", array('img_link'), 'left');
        $select->group('items.item_id');
        //echo $select->getSqlString();
        $resultSet = $this->tableGateway->selectWith($select);
        //$resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function fetchBrands(){
        $select = new Select ;
        $select->from('brands');
        $select->columns(array('b_name'));
        $resultSet = $this->tableGateway->selectWith($select);
//die(var_dump($resultSet));
        $selectData = array();
        foreach ($resultSet as $brands) {
            $i++;
            $selectData[$i] = $brands->b_name;
        }
//die(var_dump($selectData));
        return $selectData;

    }

    public function fetchCategories(){
        $select = new Select ;
        $select->from('categories');
        $resultSet = $this->tableGateway->selectWith($select);
        $selectData = array();
        foreach ($resultSet as $category) {
            $i++;
            $selectData[$i] = $category->cat_name;
        }
        return $selectData;
    }

    public function fetchSubCategories(){
        $select = new Select ;
        $select->from('sub_categories');
        $resultSet = $this->tableGateway->selectWith($select);
        $selectData = array();
        foreach ($resultSet as $category) {
            $i++;
            $selectData[$i] = $category->subcat_name;
        }
        return $selectData;
    }

    public function getItem($item_id){
//die($item_id.__METHOD__);
        $rowset = $this->tableGateway->select(array('item_id' => $item_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $item_id");
        }
        return $row;
    }

    public function saveItem(AdminCollection $item){
//die(var_dump($item));

        $item_qty_status = $this->updateQtyStatusOnAdd($item->item_quantity);

        $data = array(
            'item_name' => $item->item_name,
            'item_brand' => $item->item_brand,
            'item_description' => $item->item_description,
            'item_price' => $item->item_price,
            'item_category' => $item->item_category,
            'item_sub_category' => $item->item_sub_category,
            'item_quantity' => $item->item_quantity,
            'item_qty_status' => $item_qty_status
        );
//die(var_dump($data));

        $item_id = $item->item_id;
        //die($item_id.__METHOD__);
        if ($item_id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
//die(var_dump($item));
            $adapter = $this->tableGateway->getAdapter();
            $otherTable = new TableGateway('images', $adapter);
            return $otherTable->insert(array(
                'img_item_id' => $id,
                'img_link' => $item->img_link
            ));
        } else {
            if ($this->getItem($item_id)) {
                $this->tableGateway->update($data, array('item_id' => $item_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    protected function updateQtyStatusOnAdd($current_qty){
        $current_qty = (int)$current_qty;
        if($current_qty >= 50){
            return 2;
        }
        if($current_qty < 50 && $current_qty > 0){
            return 1;
        }
        if($current_qty <= 0){
            return 0;
        }
    }

    public function deleteItem($id){
        $this->tableGateway->delete(array('item_id' => $id));
    }

} 