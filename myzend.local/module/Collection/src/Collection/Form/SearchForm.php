<?php

namespace Collection\Form;

use Zend\Form\Form;

class SearchForm extends Form{

    public function __construct($name = null){
        parent::__construct();
        $this->setAttributes(array(
            'method'=>'post'
        ));
        $this->setAttributes(array(
            'enctype'=>'multipart/form-data'
        ));
        $this->add(array(
            'name' => 'search_target',
            'attributes' => array(
                'type'  => 'text',
                'size' => '40'
            ),
        ));
        $this->add(array(
            'name'=>'submit',
            'attributes' => array(
                'type'  => 'submit',
                'id' => 'search-form-btn',
                'value' => ''
            ),
        ));
    }

} 