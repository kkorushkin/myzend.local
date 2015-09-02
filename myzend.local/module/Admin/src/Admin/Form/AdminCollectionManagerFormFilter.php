<?php

namespace Admin\Form;

use Zend\InputFilter\InputFilter;

class AdminCollectionManagerFormFilter extends InputFilter{

    public function __construct(){
        $this->add(array(
            'name' => 'item_name',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags',
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 50,
                    ),
                ),
            ),
        ));
        $this->add(array(
            'name' => 'item_description',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags',
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 1024,
                    ),
                ),
            ),
        ));
        /*
        $this->add(array(
            'name' => 'item_price',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'Int',
                    'options' => array(
                        'min' => 1,
                        'max' => 6,
                    ),
                ),
            ),
        ));
        */
    }
} 