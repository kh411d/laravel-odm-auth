<?php namespace OdmAuth;

use Illuminate\Auth as Auth;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;

class OdmAuthServiceProvider extends Auth\AuthServiceProvider {

    public function register()
	{
		// Register the package configuration with the loader.
		$this->app['config']->package('kh411d/laravel-odm-auth', __DIR__.'/../config');

		$this->app['auth'] = $this->app->share(function($app)
		{
			// Once the authentication service has actually been requested by the developer
			// we will set a variable in the application indicating such. This helps us
			// know that we need to set any queued cookies in the after event later.
			$app['auth.loaded'] = true;
			return new OdmAuthManager($app);
		});

		
	}
}