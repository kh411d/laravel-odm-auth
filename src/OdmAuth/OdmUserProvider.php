<?php 
namespace OdmAuth;

use Illuminate\Auth\GenericUser;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Hashing\HasherInterface;

class OdmUserProvider implements UserProviderInterface {

    /**
     * The hasher implementation.
     *
     * @var \Illuminate\Hashing\HasherInterface
     */
    protected $hasher;

    /**
     * The Document user model.
     *
     * @var string
     */
    protected $model;

    /**
     * The Document Manager
     */
    protected $dm;

    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Hashing\HasherInterface  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(HasherInterface $hasher, $model)
    {
        $this->model = $model;
        $this->hasher = $hasher;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByID($identifier)
    {
        $userDocument = $this->model->findOneBy(array('id'=>$identifier));
        if ( ! is_null($userDocument))
        {

            
            $user = $userDocument->getData();
            return new OdmGenericUser((array) $user);
        }
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
       $where = array();
        foreach ($credentials as $key => $value)
        {
            if ( ! str_contains($key, 'password')){
                $where[$key] = $value;
            }
        }

        $userDocument = $this->model->findOneBy($where);
        
        if ( ! is_null($userDocument))
        {
            $user = $userDocument->getData();
            return new OdmGenericUser((array) $user);
        }
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Auth\UserInterface  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserInterface $user, array $credentials)
    {
        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }


}
