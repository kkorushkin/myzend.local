<?php

namespace Admin\Form;

use Zend\Form\Form;

class UserAddForm extends Form{

   public function __construct($name = null){

       parent::__construct('UserAdd');
       $this->setAttributes(array(
           'method'=>'post'
       ));
       $this->setAttributes(array(
           'enctype'=>'multipart/form-data'
       ));
       $this->add(array(
           'name' => 'user_name',
           'attributes' => array(
               'type'=>'text',
               'required' => true,
           ),
           'options'    => array(
               'label' => 'Name',
           ),
       ));
       $this->add(array(
           'name'=>'user_email',
           'attributes'=>array(
               'type'=>'text',
               'required' => true,
           ),
           'options'=>array(
               'label'=>'Email'
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
       $this->add(array(
           'name'=>'user_password',
           'attributes'=>array(
               'type'=>'password',
               'required' => true,
           ),
           'options'=>array(
               'label'=>'Password'
           )
       ));
       $this->add(array(
           'type' => 'Zend\Form\Element\Select',
           'name' => 'user_role',
           'options' => array(
               'label' => 'Role',
               'value_options' => array(
                   'user' => 'user',
                   'admin' => 'admin',
               ),
           ),
           'attributes' => array(
               'required' => true,
           )
       ));
       $this->add(array(
           'name'       => 'add',
           'attributes' => array(
               'type'       => 'submit',
               'value'      => 'dobavit',
           ),
       ));
   }

}