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

    protected $docname;

    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Hashing\HasherInterface  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct($dm, HasherInterface $hasher, $model)
    {
        $this->dm = $dm;
        $this->model = $dm->getRepository($model);
        $this->hasher = $hasher;
        $this->docname = $model;

    }

    public function collectUserData($userDocument)
    {
        $data['id'] = $userDocument->getAuthIdentifier();
         if($fieldnames = $this->dm->getClassMetadata($this->docname)->getFieldNames()){
            foreach ($fieldnames as $var) {
               if(method_exists($userDocument, "get".$var)){
                    $data[$var] = $userDocument->{"get".$var}();
               }
            }
         }
         return $data;
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
            $user = $this->collectUserData($userDocument);
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
            $user = $this->collectUserData($userDocument);
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

 /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $userDocument = $this->model->findOneBy(array('id'=>$identifier,'remember_token' =>$token));


        if ( ! is_null($userDocument))
        {
            $user = $this->collectUserData($userDocument);
            return new OdmGenericUser((array) $user);
        }
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Auth\UserInterface  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(UserInterface $user, $token)
    {
         $this->dm->createQueryBuilder($this->docname)
                   ->update()
                   ->field('remember_token')->set($token)
                   ->field("id")->equals($user->getAuthIdentifier());
    }


}
