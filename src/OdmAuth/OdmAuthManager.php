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
        $acl = $this->app['config']->get('laravel-odm-auth::acl');
        return new OdmGuardCustom($provider, $this->app['session.store'],$acl());
    }

    /**
     * Create an instance of the database user provider.
     *
     * @return OdmAuth\OdmUserProvider
     */
    protected function createOdmProvider()
    {
        $dm = $this->app['odm.documentmanager'];
        $document = $this->app['config']->get('laravel-odm-auth::document');
       
        return new OdmUserProvider($dm,$this->app['hash'], $document);
    }
}