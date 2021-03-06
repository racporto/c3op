<?php
class C3op_Form_ContactCreate extends Zend_Form
{
    public function init()
    {

        // initialize form
        $this->setName('newContactForm')
            ->setAction('/register/contact/create')
            ->setDecorators(array('FormElements',array('HtmlTag', array('tag' => 'div', 'class' => 'Area')),'Form'))
            ->setMethod('post');
        
        // create text input for name
        $name = new Zend_Form_Element_Text('name');
//        $nameValidator = new Zend_Validate_Regex("/^[0-9a-zA-ZÀ-ú]+[0-9A-Za-zÀ-ú\'\[\]\(\)\-\.\,\:\;\!\? ]{1,50}$/");
        $nameValidator = new C3op_Register_ContactValidName();
        $name->setLabel('Nome:')
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'eleven columns omega')),
                  array('Label', array('tag' => 'div', 'tagClass' => 'three columns alpha Right')),
              ))
            ->setOptions(array('class' => 'eleven columns alpha omega'))
            ->setRequired(true)
            ->addValidator($nameValidator)
//            ->addFilter('HtmlEntities')
            ->addFilter('StringTrim')
                ;
        // attach elements to form
        $this->addElement($name);
        
        $type = new Zend_Form_Element_Select('type');
        $type->setLabel('Tipo')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'eleven columns omega')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'three columns alpha Right')),
                ))
                ->setOptions(array('class' => 'eleven columns alpha omega'))
                ->setRequired(true);
        $titleTypes = C3op_Register_ContactTypes::AllTitles();
        $type->addMultiOption(null, "(escolha um tipo)");
        while (list($key, $title) = each($titleTypes)) {
            $type->addMultiOption($key, $title);
        }        
        $this->addElement($type);
                
        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Gravar')
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                  array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'five columns inset-by-six omega')),
                  array('Label', array('tag' => 'div', 'tagClass' => 'three columns alpha Invisible')),
              ))
            ->setOptions(array('class' => 'submit two columns alpha omega'));
        $this->addElement($submit);

    }
    
    public function process($data) {
        if ($this->isValid($data) !== true) 
        {
            throw new C3op_Form_ContactCreateException('Invalid data!');
        } 
        else
        {
            $db = Zend_Registry::get('db');
            $contactMapper = new C3op_Register_ContactMapper($db);
            $contact = new C3op_Register_Contact();
            $contact->SetName($this->name->GetValue());
            $contact->SetType($this->type->GetValue());
            $contactMapper->insert($contact);
        }
    }
}