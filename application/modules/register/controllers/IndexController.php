<?php

class Register_IndexController extends Zend_Controller_Action
{
    private $db;
    private $contactMapper;

    public function init()
    {

        $this->db = Zend_Registry::get('db');
        $this->contactMapper = new C3op_Register_ContactMapper($this->db);

   }

    public function indexAction()
    {
//        $form = new C3op_Form_Decorators;
//        $this->view->form = $form;
    }
}