<?php

namespace Admin\Form;

use Zend\Form\Form;

class LoginForm extends Form{

    public function __construct($name = null){
        parent::__construct('Login');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name'       => 'user_email',
            'attributes' => array(
                'type'   => 'email',
                'required' => true,
                'class' => 'form-control'
            ),
            'options'    => array(
                'label' => 'Email',
            ),
            'filters'    => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid'
                        )
                    )
                )
            )
        ));
        // <input type="email" name="email" label="Email" />
        $this->add(array(
            'name'       => 'user_password',
            'attributes' => array(
                'type'   => 'password',
                'required' => true,
                'class' => 'form-control'
            ),
            'options'    => array(
                'label'  => 'Password',
            )
        ));
        // <input type="password" name="password" label="Password" />
        $this->add(array(
            'name'       => 'login',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'login',
                'class' => 'btn btn-primary'
            ),
        ));
        // <input type="submit" name="submit" value="Login" />
    }
}