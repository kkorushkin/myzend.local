<?php

namespace Users\Form;

use Zend\Form\Form;

class RegisterForm extends Form{

    public function __construct($name = null){
        parent::__construct('Register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name'       => 'user_name',
            'attributes' => array(
                'type'   => 'text',
                'required' => true,
            ),
            'options'    => array(
                'label'  => 'Name',
            ),
        ));
        // <input type="text" name="name" label="Full Name" />
        $this->add(array(
            'name'       => 'user_email',
            'attributes' => array(
                'type'   => 'email',
                'required' => true,
            ),
            'options'    => array('label' => 'Email',
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
                'required' => true
            ),
            'options'    => array(
                'label'  => 'Password',
            ),
        ));
        // <input type="password" name="password" label="Password" />
        $this->add(array(
            'name'       => 'confirm_password',
            'attributes' => array(
                'type'   => 'password',
                'required' => true,
            ),
            'options'    => array(
                'label'  => 'Confirm Password',
            ),
        ));
        // <input type="password" name="confirm_password" label="Confirm Password" />
        $this->add(array(
            'name'       => 'user_role',
            'attributes' => array(
                'type'   => 'hidden',
                'value'  => 'user'
            ),
        ));
        $this->add(array(
            'name'       => 'submit',
            'attributes' => array(
            'type'       => 'submit',
            'value'      => 'Register',
            ),
        ));
        // <input type="submit" name="submit" value="Register" />
    }

}