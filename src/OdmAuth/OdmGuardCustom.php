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

	public function Acl()
	{
		return $this->acl;
	}

	//
	//Overwrite acl object with new one from other source such cache
	//
	public function setAcl(\Zend\Permissions\Acl\Acl $acl)
	{
		$this->acl = $acl;
		return true;
	}

	public function isAllowed($resource = null,$action = null,$byUser = false)
	{
		if(is_null($this->user())) return FALSE;
		
		if(!$byUser && $role = @$this->user()->role){		
			return $this->acl->isAllowed($role, $resource, $action) ? TRUE : FALSE;
		}elseif($byUser){
			return $this->acl->isAllowed($this->user()->id, $resource, $action) ? TRUE : FALSE;
		}

		return FALSE;
	}
}