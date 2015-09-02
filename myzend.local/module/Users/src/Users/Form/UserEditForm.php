<?php

namespace Users\Form;

use Zend\Form\Form;

class UserEditForm extends Form{

    public function __construct($name = null){
        parent::__construct('Manager');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'user_id',
            'attributes' => array(
                'type' => 'hidden'
            ),
            'options'=> array(
               'label' => 'ID'
            )
        ));
        $this->add(array(
            'name' => 'user_name',
            'attributes' => array(
                'type' => 'text',
                'required' => true
            ),
            'options'=> array(
                'label' => 'NAME'
            )
        ));
        $this->add(array(
            'name' => 'user_email',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'EMAIL',
                'required' => true
            )
        ));
        $this->add(array(
            'name' => 'user_role',
            'attributes' => array(
                'type' => 'text',
                'required' => true
            ),
            'options' => array(
                'label' => 'ROLE'
            )

        ));
/*
        $this->add(array(
            'name' => 'user_password',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'PASSWORD'
            )
        ));
*/
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'menyat'
            ),

        ));
    }

}