<?php
namespace OdmAuth;
use Illuminate\Auth\Guard as Guard;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

Class OdmGuardCustom extends Guard {

	protected $acl;

	public function __construct($provider,$session, \Zend\Permissions\Acl\Acl $acl)
	{
		parent::__construct($provider,$session);
		$this->acl = $acl;
	}

	public function isAllowed($resource = null,$action = null)
	{
		if(is_null($this->user())) return FALSE;
		
		if($role = @$this->user()->role){
			
			return $this->acl->isAllowed($role, $resource, $action) ? TRUE : FALSE;
		}

		return FALSE;
	}
}