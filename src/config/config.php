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
			return $acl;

	}
);