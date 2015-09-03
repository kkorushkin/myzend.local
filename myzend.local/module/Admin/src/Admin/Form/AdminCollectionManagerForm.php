<?php

namespace Admin\Form;

use Zend\Form\Form;


class AdminCollectionManagerForm extends Form{

    public function __construct($name = null){
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
            'name' => 'item_brand',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Brand',
                'value_options' => array(),
                'disable_inarray_validator' => true,
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
            'name' => 'item_category',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
            ),
            'options' => array(
                'label' => 'Category',
                'value_options' => array(),
                'disable_inarray_validator' => true,
            ),
        ));
        $this->add(array(
            'name' => 'item_sub_category',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
            ),
            'options' => array(
                'label' => 'Sub Category',
                'value_options' => array(),
                'disable_inarray_validator' => true,
            ),
        ));
        $this->add(array(
            'name' => 'img',
            'type'  => 'Zend\Form\Element\File',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Image',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'insert',
                'class' => 'btn btn-primary',
            ),
        ));
    }

}
