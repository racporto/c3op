<?php

class C3op_Access_Acl extends Zend_Acl
{

public function __construct() {

    $roles = C3op_Access_Roles::AllRoles();
    $previousRole = null;
    while (list($role, $label) = each($roles)) {
        if ($role != C3op_Access_RolesConstants::ROLE_SYSADMIN) {
            if ($previousRole === null) {
                $this->addRole(new Zend_Acl_Role($role));
            } else {
                $this->addRole(new Zend_Acl_Role($role), $previousRole);
            }
            $previousRole = $role;
        }


    }
    $this->addRole(new Zend_Acl_Role(C3op_Access_RolesConstants::ROLE_SYSADMIN));
    $this->allow(C3op_Access_RolesConstants::ROLE_SYSADMIN);

    $this->add(new Zend_Acl_Resource('c3op:auth'));
    $this->add(new Zend_Acl_Resource('c3op:auth.login'));
    $this->add(new Zend_Acl_Resource('c3op:auth.logout'));
    $this->add(new Zend_Acl_Resource('c3op:auth.user'));
    $this->add(new Zend_Acl_Resource('c3op:register'));
    $this->add(new Zend_Acl_Resource('c3op:register.contact'));
    $this->add(new Zend_Acl_Resource('c3op:register.institution'));
    $this->add(new Zend_Acl_Resource('c3op:register.linkage'));
    $this->add(new Zend_Acl_Resource('c3op:projects'));
    $this->add(new Zend_Acl_Resource('c3op:projects.action'));
    $this->add(new Zend_Acl_Resource('c3op:projects.human-resource'));
    $this->add(new Zend_Acl_Resource('c3op:projects.outlay'));
    $this->add(new Zend_Acl_Resource('c3op:projects.project'));
    $this->add(new Zend_Acl_Resource('c3op:projects.receivable'));


    $this->allow(C3op_Access_RolesConstants::ROLE_UNKNOWN,       'c3op:auth.login', 'index');
    $this->allow(C3op_Access_RolesConstants::ROLE_UNKNOWN,       'c3op:auth.logout', 'index');

    $this->allow(C3op_Access_RolesConstants::ROLE_COORDINATOR,   'c3op:projects.action', 'accept-receipt');
    $this->allow(C3op_Access_RolesConstants::ROLE_COORDINATOR,   'c3op:projects.action', 'acknowledge-receipt');
    $this->allow(C3op_Access_RolesConstants::ROLE_CONTROLLER,    'c3op:projects.action', 'acknowledge-start');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.action', 'create');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.action', 'detail');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.action', 'edit');
    $this->allow(C3op_Access_RolesConstants::ROLE_ADMINISTRATOR,     'c3op:projects.action', 'tree');
    $this->allow(C3op_Access_RolesConstants::ROLE_COORDINATOR,   'c3op:projects.action', 'reject-receipt');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.action', 'success-create');

    $this->allow(C3op_Access_RolesConstants::ROLE_COORDINATOR,   'c3op:projects.human-resource');
    $this->allow(C3op_Access_RolesConstants::ROLE_COORDINATOR,   'c3op:projects.human-resource', 'create');
    $this->allow(C3op_Access_RolesConstants::ROLE_COORDINATOR,   'c3op:projects.human-resource', 'contract');
    $this->allow(C3op_Access_RolesConstants::ROLE_COORDINATOR,   'c3op:projects.human-resource', 'dismiss-contact');
    $this->allow(C3op_Access_RolesConstants::ROLE_ADMINISTRATOR, 'c3op:projects.human-resource', 'contract');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.human-resource', 'outlays');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.human-resource', 'success-create');

    $this->allow(C3op_Access_RolesConstants::ROLE_ADMINISTRATOR, 'c3op:projects.outlay', 'create');

    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.project', 'create');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.project', 'detail');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.project', 'edit');
    $this->allow(C3op_Access_RolesConstants::ROLE_ADMINISTRATOR, 'c3op:projects.project', 'payables');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.project', 'receivables');
    $this->allow(C3op_Access_RolesConstants::ROLE_CONTROLLER,    'c3op:projects.project', 'unacknowledged');
    $this->allow(C3op_Access_RolesConstants::ROLE_ADMINISTRATOR, 'c3op:projects.project', 'tree');

    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.receivable', 'create');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:projects.receivable', 'edit');

    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.contact', 'create');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.contact', 'edit');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.contact', 'detail');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.contact', 'index');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.contact', 'success-create');

    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.institution', 'create');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.institution', 'edit');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.institution', 'index');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.institution', 'success-create');

    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.linkage', 'create');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.linkage', 'edit');
    $this->allow(C3op_Access_RolesConstants::ROLE_ASSISTANT,     'c3op:register.linkage', 'success-create');

  }

}
