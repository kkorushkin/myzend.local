<?php

namespace Admin\Form;

use Zend\InputFilter\InputFilter;

class UserAddFormFilter extends  InputFilter{

    public function __construct(){
//  для поля Email Address мы добавим валидатор, который проверяет,
//  является ли введенное значение корректным адресом электронной почты
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
//  для поля Name мы добавим валидатор, который ограничивает размер
//  вводимых данных от 2 до 140 символов, и фильтр, вырезающий HTML-теги
        $this->add(array(
            'name' => 'user_name',
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
                        'min' => 2,
                        'max' => 140,
                    ),
                ),
            ),
        ));
//  для полей Password и Confirm Password мы не будем использовать какие-либо
//  валидаторы, однако сделаем эти поля обязательными
        $this->add(array(
            'name' => 'user_password',
            'required' => true,
        ));
        $this->add(array(
            'name' => 'user_role',
            'required' => true,
        ));
    }
}