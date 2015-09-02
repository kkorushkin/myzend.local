<?php

namespace Order\Form;

use Zend\InputFilter\InputFilter;

class OrderFormFilter extends InputFilter{

    public function __construct(){
        $this->add(array(
            'name' => 'name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
            ),
        ));
    }

} 