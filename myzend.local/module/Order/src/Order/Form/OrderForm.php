<?php

namespace Order\Form;

use Zend\Form\Form;

class OrderForm extends Form{

    public function __construct($name = null){
        parent::__construct();
        $this->setAttributes(array(
            'method'=>'post'
        ));
        $this->setAttributes(array(
            'enctype'=>'multipart/form-data'
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'size' => '20',
                'class' => 'form-control',
                'required' => true
            ),
            'options'    => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'address',
            'attributes' => array(
                'type'  => 'text',
                'size' => '40',
                'class' => 'form-control',
                'required' => true
            ),
            'options'    => array(
                'label' => 'Address',
            ),
        ));
        $this->add(array(
            'name' => 'phone',
            'attributes' => array(
                'type'  => 'text',
                'size' => '40',
                'class' => 'form-control',
                'required' => true
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
                'type' => 'button',
                'value' => 'order',
                'class' => 'btn btn-primary'
            ),
            'options' => array(
            ),
        ));
    }

} 