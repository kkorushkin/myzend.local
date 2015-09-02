<?php

namespace Collection\Helper;

use Zend\View\Helper\AbstractHelper;
use Collection\Form\SearchForm;

class CollectionLayoutViewHelperSearchFrom extends AbstractHelper{

    public function __invoke(){
        return $searchForm = new SearchForm();
    }

}