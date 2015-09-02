<?php

namespace Admin\Form;

use Zend\InputFilter\InputFilter;

class LoginFormFilter extends InputFilter{

    public function __construct(){
        $this->add(array(
            'name' => 'user_email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'domain' => true,
                    ),
                ),
            ),
        ));


        $this->add(array(
            'name' => 'user_password',
            'required' => true,
        ));

    }

}