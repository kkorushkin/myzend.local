<?php

namespace Collection\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Collection implements InputFilterAwareInterface{

    public $item_id;
    public $item_name;
    public $item_brand;
    public $item_description;
    public $item_price;
    public $item_category;
    public $item_sub_category;

    public $b_name;
    public $cat_id;
    public $cat_name;
    public $subcat_name;
    public $item_quantity;
    public $img_link;

    public $color_id;

    const COLL_IMG_PATH = '/img/CollectionImages/';

    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->item_id = (isset($data['item_id'])) ? $data['item_id'] : null;
        $this->item_name = (isset($data['item_name'])) ? $data['item_name'] : null;
        $this->item_brand = (isset($data['item_brand'])) ? $data['item_brand'] : null;
        $this->item_description = (isset($data['item_description'])) ? $data['item_description'] : null;
        $this->item_price = (isset($data['item_price'])) ? $data['item_price'] : null;
        $this->item_category  = (isset($data['item_category'])) ? $data['item_category'] : null;
        $this->item_sub_category  = (isset($data['item_sub_category'])) ? $data['item_sub_category'] : null;
        $this->item_quantity = (isset($data['item_quantity'])) ? $data['item_quantity'] : null;
        $this->img_link = (isset($data['img_link'])) ? $data['img_link'] : 'No_image_available.jpg';
        //
        $this->b_name  = (isset($data['b_name'])) ? $data['b_name'] : null;
        $this->cat_id = (isset($data['cat_id'])) ? $data['cat_id'] : null;
        $this->cat_name = (isset($data['cat_name'])) ? $data['cat_name'] : null;
        $this->subcat_name = (isset($data['subcat_name'])) ? $data['subcat_name'] : null;
        //
        $this->color_id = (isset($data['color_id'])) ? $data['color_id'] : null;

    }

    public function setInputFilter(InputFilterInterface $inputFilter){
        throw new Exception("Not used");
    }

    public function getInputFilter(){
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'item_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'item_name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 50,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'item_brand',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 50,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'item_description',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 250,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'item_price',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }
}
?>