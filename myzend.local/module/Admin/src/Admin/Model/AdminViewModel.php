<?php

namespace Admin\Model;

use Zend\View\Model\ViewModel;

class AdminViewModel extends ViewModel{

    private $innerTemplate;
    private $myLayout = 'admin/layout/layout';

    public function setTemplate($template) {
        $this->template = $this->myLayout;
        $myTemplate = (string) $template;
        $this->innerTemplate = new ViewModel($this->variables);
        $this->innerTemplate->setTemplate($myTemplate);
        $this->innerTemplate->parent=$this;
        $this->addChild($this->innerTemplate);
        return $this;
    }
    public function setLayout($layout) {
        $this->myLayout = $layout;
        return $this;
    }
    public function setVariable($pName, $pVar){
        if(isset($this->innerTemplate)) $this->innerTemplate->setVariable($pName, $pVar);
        return parent::setVariable($pName, $pVar);
    }
    public function setVariables($variables, $overwrite=NULL){
        if(isset($this->innerTemplate)){
            if(isset($overwrite)) $this->innerTemplate->setVariables($variables, $overwrite);
            else $this->innerTemplate->setVariables($variables);
        }
        if(isset($overwrite)) return parent::setVariables($variables, $overwrite);
        else return parent::setVariables($variables);
    }

}