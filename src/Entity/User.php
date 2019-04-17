<?php

namespace Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User extends AbstractEntity implements UserInterface
{

    /**
     * @var integer
     */
    private $id;

    private $salt;

    private $roles = array('ROLE_USER');

    private $email;

    private $password;

    private $apikey;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
	if(!is_array($roles)){
	    $roles = json_decode($roles);
	}
        $this->roles = $roles;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
	    $this->password = $password;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    protected static $_tablename = "user";

    protected static $_properties = null;

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
	if(empty($this->salt))
	    $this->salt = uniqid();
        return $this->salt;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
