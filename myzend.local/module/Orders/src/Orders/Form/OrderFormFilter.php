<?php

namespace Orders\Form;

use Zend\InputFilter\InputFilter;

class OrderFormFilter extends InputFilter{

    public function __construct(){
        $this->add(array(
            'name' => 'user_name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
            ),
        ));
    }

} 