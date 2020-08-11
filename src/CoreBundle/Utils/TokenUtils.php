<?php

namespace App\CoreBundle\Utils;

/**
 * Class TokenUtils
 *
 * @package App\CoreBundle\Utils
 */
class TokenUtils
{
    /**
     * Generate the token.
     *
     * @author Ali, Muamar
     * @throws \Exception
     *
     * @return string
     *
     */
    public function generateToken()
    {
        try {
            $token = bin2hex(openssl_random_pseudo_bytes(32));

            return $token;
        } catch (\Exception $e) {
            throw new \Exception(
                'There is a problem in generating a token'
            );
        }
    }
}