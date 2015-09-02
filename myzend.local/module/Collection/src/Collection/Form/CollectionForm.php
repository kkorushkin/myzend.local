<?php

namespace Collection\Form;

use Zend\Form\Form;
use Zend\Captcha;


class CollectionForm extends Form{


    public function __construct($name = null){

        parent::__construct('Collection');
        $this->setAttributes(array(
            'method'=>'post'
        ));
        $this->setAttributes(array(
            'enctype'=>'multipart/form-data'
        ));
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'item_name',
            'attributes' => array(
                'type'  => 'text',
                'required' => true
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'item_brand',
            'attributes' => array(
                'type'  => 'text',
                'required' => true
            ),
            'options' => array(
                'label' => 'Brand',
            ),
        ));
        $this->add(array(
            'name' => 'item_description',
            'attributes' => array(
                'type'  => 'text',
                'required' => true
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));
        $this->add(array(
            'name' => 'item_price',
            'attributes' => array(
                'type'  => 'text',
                'required' => true
            ),
            'options' => array(
                'label' => 'Price',
            ),
        ));
        $this->add(array(
            'name' => 'item_quantity',
            'attributes' => array(
                'type'  => 'text',
                'required' => true
            ),
            'options' => array(
                'label' => 'Quantity',
            ),
        ));
        $this->add(array(
            'name' => 'item_category',
            'attributes' => array(
                'type'  => 'text',
                'required' => true
            ),
            'options' => array(
                'label' => 'Category',
            ),
        ));
        $this->add(array(
            'name' => 'item_sub_category',
            'attributes' => array(
                'type'  => 'text',
                'required' => true
            ),
            'options' => array(
                'label' => 'Sub Category',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'добавить',
                'id' => 'submitbutton',
            ),
        ));
    }

}
