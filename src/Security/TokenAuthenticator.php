<?php

namespace Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Created by PhpStorm.
 * User: jsimonney
 * Date: 18/09/2017
 * Time: 14:35
 */

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $encoder;

    public static function getTokenName(){
        return 'X-AUTH-TOKEN';
    }

    public function __construct(TokenCoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /*
    public function createAuthenticatedToken(UserInterface $user, $providerKey){
	$token = $user->getEmail().':'.$user->getPassword();
	$data = array(
	    $providerKey => $token
	);
        return new JsonResponse($data, Response::HTTP_ACCEPTED);
    }
    */

    public function getCredentials(Request $request)
    {
        if (!$token = $request->headers->get(self::getTokenName())) {
            // No token?
            $token = null;
        }

        /*
        // What you return here will be passed to getUser() as $credentials
        return array(
            'token' => $token,
        );
        */
        $headerParts = $this->encoder->decode($token);

        //$headerParts = explode(' ', $request->headers->get(self::getTokenName()));


        if (count($headerParts) !== 3 || empty($headerParts['username']) ) {
            throw new \Exception('Malformed Authorization Header');
        }

        return array('email'=>$headerParts['username']);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['email']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
