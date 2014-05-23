<?php namespace OdmAuth\Reminders;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Reminders\PasswordBroker;
use OdmAuth\Reminders\OdmReminderRepository;


class ReminderServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['config']->package('kh411d/laravel-odm-auth', __DIR__.'/../../config');
		
		$this->registerPasswordBroker();

		$this->registerReminderRepository();
	}

	/**
	 * Register the password broker instance.
	 *
	 * @return void
	 */
	protected function registerPasswordBroker()
	{
		$this->app['auth.reminder'] = $this->app->share(function($app)
		{
			// The reminder repository is responsible for storing the user e-mail addresses
			// and password reset tokens. It will be used to verify the tokens are valid
			// for the given e-mail addresses. We will resolve an implementation here.
			$reminders = $app['auth.reminder.repository'];

			$users = $app['auth']->driver()->getProvider();

			$config = $app['config']->get('laravel-odm-auth::reminder');

			$view = $config['email'];

			// The password broker uses the reminder repository to validate tokens and send
			// reminder e-mails, as well as validating that password reset process as an
			// aggregate service of sorts providing a convenient interface for resets.
			return new PasswordBroker(

				$reminders, $users, $app['mailer'], $view

			);
		});
	}

	/**
	 * Register the reminder repository implementation.
	 *
	 * @return void
	 */
	protected function registerReminderRepository()
	{
		$app = $this->app;

		$app['auth.reminder.repository'] = $app->share(function($app)
		{
			$dm = $app['odm.documentmanager'];

			$config = $app['config']->get('laravel-odm-auth::reminder');

			// The database reminder repository is an implementation of the reminder repo
			// interface, and is responsible for the actual storing of auth tokens and
			// their e-mail addresses. We will inject this table and hash key to it.
			$document = $config['document'];

			$key = $app['config']['app.key'];

			return new OdmReminderRepository($dm, $document, $key);

		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('auth.reminder', 'auth.reminder.repository');
	}

}