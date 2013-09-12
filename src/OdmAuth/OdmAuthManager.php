<?php 
namespace OdmAuth;

use Illuminate\Auth as Auth;

class OdmAuthManager extends Auth\AuthManager {

    /**
     * Create an instance of the database driver.
     *
     * @return Illuminate\Auth\Guard
     */
    protected function createOdmDriver()
    {
        $provider = $this->createOdmProvider();

        return new OdmGuardCustom($provider, $this->app['session'],$this->app['config']['auth.acl']());
    }

    /**
     * Create an instance of the database user provider.
     *
     * @return OdmAuth\OdmUserProvider
     */
    protected function createOdmProvider()
    {
        $DocumentManager = $this->app['doctrine.mongodb'];
        $model = $DocumentManager->getRepository($this->app['config']['auth.model']);

        return new OdmUserProvider($this->app['hash'], $model);
    }
}