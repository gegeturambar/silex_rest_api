<?php

namespace Security;

use Namshi\JOSE\JWS;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Created by PhpStorm.
 * User: jsimonney
 * Date: 18/09/2017
 * Time: 14:35
 */

class TokenCoder
{
    const ALG = 'HS256';
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }
    /**
     * @param array $payload
     * @param int   $ttl
     *
     * @return string
     */
    public function encode(array $payload, $ttl = 86400)
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $ttl;
        $jws = new JWS([
            'typ' => 'JWS',
            'alg' => self::ALG,
        ]);
        $jws->setPayload($payload);
        $jws->sign($this->key);
        return $jws->getTokenString();
    }
    /**
     * @param string $token
     *
     * @return array
     *
     * @throws InvalidJWTException
     */
    public function decode($token)
    {
        $jws = JWS::load($token);
        if (!$jws->verify($this->key, self::ALG)) {
            throw new InvalidJWTException('Invalid JWT');
        }
        if ($this->isExpired($payload = $jws->getPayload())) {
            throw new InvalidJWTException('Expired JWT');
        }
        return $payload;
    }
    /**
     * @param array $payload
     *
     * @return bool
     */
    private function isExpired($payload)
    {
        if (isset($payload['exp']) && is_numeric($payload['exp'])) {
            return (time() - $payload['exp']) > 0;
        }
        return false;
    }
}
