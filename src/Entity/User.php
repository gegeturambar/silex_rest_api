<?php

namespace Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User extends AbstractEntity implements UserInterface
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    private $username;

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
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
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

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    protected static $_tablename = "user";

    protected static $_properties = null;

    public function getUsername()
    {
        return $this->username;
        // TODO: Implement getUsername() method.
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
