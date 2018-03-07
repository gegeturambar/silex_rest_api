<?php
/**
 * Created by PhpStorm.
 * User: jsimonney
 * Date: 18/09/2017
 * Time: 18:01
 */

namespace Security;


use Doctrine\DBAL\Connection;
use Entity\User;
use Repository\Repository;
use Silex\Application;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /** @var  Repository */
    private $_userRepository;

    public function __construct($userRepository ){
        $this->_userRepository = $userRepository;
    }

    public function loadUserByUsername($username)
    {
        //$langId = $app['repository.langue']->findBy(array("code" => $lang, "name"   =>  $lang ), false );

        $user = $this->_userRepository->findBy(array('email' =>  $username));
        $user = array_pop($user);
        if(!$user){
            throw new UsernameNotFoundException("Email $username does not exists");
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if(!$user instanceof User){
            throw new UnsupportedUserException("Instances of '".get_class($user)."' are not (currently) supported ");
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === User::class;
    }
}
