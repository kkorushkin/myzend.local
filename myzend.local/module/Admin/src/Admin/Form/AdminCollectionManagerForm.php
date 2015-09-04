<?php

namespace Admin\Form;

use Zend\Form\Form;

class AdminCollectionManagerForm extends Form{

    public function __construct(){

        parent::__construct('collection');
        $this->setAttributes(array(
            'method'=>'post'
        ));
        $this->setAttributes(array(
            'enctype'=>'multipart/form-data'
        ));
        $this->add(array(
            'name' => 'item_id',
            'attributes' => array(
                'type'  => 'hidden',
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'item_name',
            'attributes' => array(
                'type'  => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'type'  => 'Zend\Form\Element\Select',
            'name' => 'item_brand',
            'attributes' => array(
                'required' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Brand',
                'value_options' => array(),
                'class' => 'form-control',
            )
        ));
        $this->add(array(
           'name' => 'new_brand',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'New brand',
            ),
        ));
        $this->add(array(
            'name' => 'item_description',
            'attributes' => array(
                'type'  => 'textarea',
                'required' => false,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));
        $this->add(array(
            'name' => 'item_price',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
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
                'class' => 'form-control',
                'required' => true,
            ),
            'options' => array(
                'label' => 'Quantity',
            ),
        ));
        $this->add(array(
            'type'  => 'Zend\Form\Element\Select',
            'name' => 'item_category',
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
            ),
            'options' => array(
                'label' => 'Category',
                'value_options' => array(),
            ),
        ));
        $this->add(array(
            'type'  => 'Zend\Form\Element\Select',
            'name' => 'item_sub_category',
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
            ),
            'options' => array(
                'label' => 'Sub Category',
                'value_options' => array(),
            ),
        ));
        $this->add(array(
            'name' => 'img_link',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'Image',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'добавить',
                'class' => 'btn btn-primary',
            ),
        ));

    }
}
