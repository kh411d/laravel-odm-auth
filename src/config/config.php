<?php
return array(

/*
	|-----------------------------------------------------------------------------
	| User Document 
	|-----------------------------------------------------------------------------
	|
	| User document class name that will be use for Authentication
	| 
 	*/
 
	'document' => 'User',	

/*
	|--------------------------------------------------------------------------
	| Password Reminder Settings
	|--------------------------------------------------------------------------
	|
	| Here you may set the settings for password reminders, including a view
	| that should be used as your password reminder e-mail. You will also
	| be able to set the name of the document that holds the reset tokens.
	|
	*/

	'reminder' => array(

		'email' => 'emails.auth.reminder', 
		'document' => 'PasswordReminders',

	),
/*
	|-----------------------------------------------------------------------------
	| Access Control List configuration.
	|-----------------------------------------------------------------------------
	|
	| ZendACl Permission setup.
	| 
 	*/	
	'acl' => function() {

		 	$acl = new Zend\Permissions\Acl\Acl();

			$roleGuest = new Zend\Permissions\Acl\Role\GenericRole('guest');

			$acl->addRole($roleGuest);
			$acl->addRole(new Zend\Permissions\Acl\Role\GenericRole('staff'), $roleGuest);
			$acl->addRole(new Zend\Permissions\Acl\Role\GenericRole('editor'), 'staff');
			$acl->addRole(new Zend\Permissions\Acl\Role\GenericRole('administrator'));

			$acl->addResource(new Zend\Permissions\Acl\Resource\GenericResource('campaign'));
			$acl->addResource(new Zend\Permissions\Acl\Resource\GenericResource('customer'));
			$acl->addResource(new Zend\Permissions\Acl\Resource\GenericResource('subscription'));
			$acl->addResource(new Zend\Permissions\Acl\Resource\GenericResource('submission'));
			$acl->addResource(new Zend\Permissions\Acl\Resource\GenericResource('customerattrs'));
			$acl->addResource(new Zend\Permissions\Acl\Resource\GenericResource('promotion'));
			$acl->addResource(new Zend\Permissions\Acl\Resource\GenericResource('webendpoint'));
			$acl->addResource(new Zend\Permissions\Acl\Resource\GenericResource('user'));
			$acl->addResource(new Zend\Permissions\Acl\Resource\GenericResource('dashboard'));

			//index, show, entries, create, store, ,remove, edit, update
			//
			// Guest may only view content
			$acl->allow('guest', array('dashboard'), array('index'));

			// Staff inherits view privilege from guest, but also needs additional
			// privileges
			$acl->allow('staff', array('dashboard','campaign','customer','promotion','subscription','submission'), array('show','index','entries'));

			// Editor inherits view, edit, submit, and revise privileges from
			// staff, but also needs additional privileges
			$acl->allow('editor', array('dashboard','campaign','customer','customerattrs','promotion','webendpoint'), array('show','index','entries','create','store','remove','edit','update'));

			// Administrator inherits nothing, but is allowed all privileges
			$acl->allow('administrator');
			return $acl;

	}
);