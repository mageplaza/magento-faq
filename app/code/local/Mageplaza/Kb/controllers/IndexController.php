<?php

class Mageplaza_Kb_IndexController extends Mage_Core_Controller_Front_Action{

    public function indexAction(){
        $this->_redirect('kb/category/index');
    }
}