<?php

class Projects_ActionController extends Zend_Controller_Action
{
    private $actionMapper;
    private $projectMapper;
    private $receivingMapper;
    private $db;

    public function init()
    {
        $this->db = Zend_Registry::get('db');
    }

    public function createAction()
    {
        // cria form
        $form = new C3op_Form_ActionCreate;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            if ($form->isValid($postData)) {
                $form->process($postData);
                $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('The record was successfully updated.');          
                $this->_redirect('/projects/action/success-create');
            } else throw new C3op_Projects_ActionException("An action must have a valid title.");
        } else {
            $data = $this->_request->getParams();
            $projectId = $data['project'];
            $this->populateProjectFields($projectId, $form);
            $this->populateRequirementForReceivingField($projectId, $form);
            $this->populateSubordinatedActionsField($projectId, $form);
        }
    }

    public function editAction()
    {
        $form = new C3op_Form_ActionEdit;
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            if ($form->isValid($postData)) {
                $form->process($postData);
                $this->_helper->getHelper('FlashMessenger')
                    ->addMessage('The record was successfully updated.');          
                $this->_redirect('/projects/project/success-create');
            } else throw new C3op_Projects_ProjectException("A project must have a valid title.");
        } else {
            $data = $this->_request->getParams();
            $filters = array(
                'id' => new Zend_Filter_Alnum(),
            );
            $validators = array(
                'id' => new C3op_Util_ValidId(),
            );
            $input = new Zend_Filter_Input($filters, $validators, $data);
            if ($input->isValid()) {
                $id = $input->id;
                if (!isset($this->actionMapper)) {
                    $this->actionMapper = new C3op_Projects_ActionMapper($this->db);
                }
                $thisAction = $this->actionMapper->findById($id);
                $titleField = $form->getElement('title');
                $titleField->setValue($thisAction->getTitle());
                $idField = $form->getElement('id');
                $idField->setValue($id);
                $milestoneField = $form->getElement('milestone');
                $milestoneField->setValue($thisAction->getMilestone());
                $projectId = $this->populateProjectFields($thisAction->GetProject(), $form);
                $this->populateRequirementForReceivingField($projectId, $form, $thisAction->GetRequirementForReceiving());
                $this->populateSubordinatedActionsField($projectId, $form, $id);
            }

        }
    }

    public function sucessAction()
    {
        if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
            $this->view->messages = $this->_helper
                ->getHelper('FlashMessenger')
                ->getMessages();
        } else {
            $this->_redirect('/');
        }
    }

    public function successCreateAction()
    {
        if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
            $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();    
            $this->getResponse()->setHeader('Refresh', '3; URL=/projects');
        } else {
            $this->_redirect('/projects');    
        } 
    }

    public function errorEditAction()
    {
        $flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $flashMessenger->setNamespace('messages');
        $this->view->messages = $flashMessenger->getMessages();
        $flashMessenger->addMessage('Id Inválido');
    }
    
    private function populateProjectFields($projectId, C3op_Form_ActionCreate $form)
    {
        $validator = new C3op_Util_ValidId();
        if ($validator->isValid($projectId)) {
            $projectField = $form->getElement('project');
            $projectField->setValue($projectId);
            if (!isset($this->projectMapper)) {
                $this->projectMapper = new C3op_Projects_ProjectMapper($this->db);
            }
            $thisProject = $this->projectMapper->findById($projectId);
            $this->view->projectTitle = $thisProject->GetTitle();
            $this->view->linkProjectDetail = "/projects/project/detail/?id=" . $thisProject->GetId();

            return $projectId;
        } else throw new C3op_Projects_ActionException("Action needs a positive integer project id.");
        
   }
     
    private function populateSubordinatedActionsField($projectId, C3op_Form_ActionCreate $form, $actionId = 0)
    {
        $validator = new C3op_Util_ValidId();
        $parentActionId = 0;
        if ($validator->isValid($projectId)) {
            $subordinatedToField = $form->getElement('subordinatedTo');
            if (!isset($this->actionMapper)) {
                $this->actionMapper = new C3op_Projects_ActionMapper($this->db);
            }

            if ($actionId > 0) {
                $thisAction = $this->actionMapper->findById($actionId);
                $parentActionId = $thisAction->GetSubordinatedTo();
                $allOtherActionsInProject = $this->actionMapper->getAllOtherActions($thisAction);
                
            } else {
                if (!isset($this->projectMapper)) {
                    $this->projectMapper = new C3op_Projects_ProjectMapper($this->db);
                }
                $thisProject = $this->projectMapper->findById($projectId);
                $allOtherActionsInProject = $this->projectMapper->getAllActions($thisProject);
            }

            while (list($key, $actionId) = each($allOtherActionsInProject)) {
                $eachAction = $this->actionMapper->findById($actionId);
                $subordinatedToField->addMultiOption($actionId, $eachAction->GetTitle());
            }
            
            $subordinatedToField->setValue($parentActionId);
        
        } else throw new C3op_Projects_ActionException("Action needs a positive integer project id to find other actions.");
   }
     
    private function populateRequirementForReceivingField($projectId, C3op_Form_ActionCreate $form, $setedReceivingId = 0)
    {
        $validator = new C3op_Util_ValidId();
        if ($validator->isValid($projectId)) {
            $requirementForReceivingField = $form->getElement('requirementForReceiving');
            if (!isset($this->projectMapper)) {
                $this->projectMapper = new C3op_Projects_ProjectMapper($this->db);
            }
            if (!isset($this->receivingMapper)) {
                $this->receivingMapper = new C3op_Projects_ReceivingMapper($this->db);
            }
            $thisProject = $this->projectMapper->findById($projectId);
            $allReceivings = $this->projectMapper->getAllReceivings($thisProject);

            while (list($key, $receivingId) = each($allReceivings)) {
                $eachReceiving = $this->receivingMapper->findById($receivingId);
                $requirementForReceivingField->addMultiOption($receivingId, $eachReceiving->GetTitle());
            }
            
            $requirementForReceivingField->setValue($setedReceivingId);
        
        } else throw new C3op_Projects_ActionException("Action needs a positive integer project id to find possible receivings to to be a requirement.");
   }
     
}