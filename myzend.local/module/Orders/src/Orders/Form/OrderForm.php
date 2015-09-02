<?php

namespace Orders\Form;

use Zend\Form\Form;



class OrderForm extends Form{

    public function __construct($name = null){
        parent::__construct('order');
        $this->setAttributes(array(
            'method'=>'post'
        ));
        $this->setAttributes(array(
            'enctype'=>'multipart/form-data'
        ));
        $this->add(array(
            'name' => 'user_name',
            'attributes' => array(
                'type'  => 'text',
                'size' => '100',
                'class' => 'form-control',
                'required' => true,
                'placeholder' => 'how it to our managers call to you'
            ),
            'options'    => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'user_address',
            'attributes' => array(
                'type'  => 'text',
                'size' => '100',
                'class' => 'form-control',
                'required' => true,
                'placeholder' => 'where is your purchases can reach you'
            ),
            'options'    => array(
                'label' => 'Address',
            ),
        ));
        $this->add(array(
            'name' => 'user_phone',
            'attributes' => array(
                'type'  => 'Zend\Form\Element\Number',
                'size' => '100',
                'class' => 'form-control',
                'required' => true,
                'placeholder' => '+x-xxx-xxx-xx-xx'
            ),
            'options'    => array(
                'label' => 'Phone no.',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'checkbox',
            'options' => array(
                'label' => '',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'checkout',
                'class' => 'btn btn-primary',
                'required' => true
            ),
            'options' => array(
            ),
        ));
    }

} 