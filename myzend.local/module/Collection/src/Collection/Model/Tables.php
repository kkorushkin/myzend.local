<?php

namespace Collection\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class Tables {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    public function getCatId($item_category){
        $select = new Select();
        $select->from('categories')
            ->columns(array('cat_id'))
            ->where->equalTo('cat_name', $item_category);
        $resultSet = $this->tableGateway->selectWith($select);
        foreach ($resultSet as $k=>$v) {
            $selectData[$k] = $v;
        }
        return $selectData;
    }

} 