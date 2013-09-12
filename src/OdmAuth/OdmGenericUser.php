<?php
namespace OdmAuth;
use Illuminate\Auth\GenericUser;
use Illuminate\Auth\Reminders\RemindableInterface;

class OdmGenericUser extends GenericUser implements RemindableInterface {

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
}