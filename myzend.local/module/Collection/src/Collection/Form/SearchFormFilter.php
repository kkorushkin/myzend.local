<?php

namespace Collection\Form;

use Zend\InputFilter\InputFilter;

class SearchFormFilter extends InputFilter{

    public function __construct()
    {
        $this->add(array(
            'name' => 'search_target',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
            ),
        ));
    }
} 