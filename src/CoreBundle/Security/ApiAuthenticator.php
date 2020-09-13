<?php

namespace App\CoreBundle\Security;

use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\{AuthenticationException, CustomUserMessageAuthenticationException};
use Symfony\Component\Security\Core\User\{UserInterface, UserProviderInterface};
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class ApiAuthenticator
 *
 * @package App\CoreBundle\Security
 *
 * @author Ali, Muamar
 */
class ApiAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * Called on every request to decide if this authenticator should be used for the request.
     *
     * @param Request $request | handle the request methods.
     *
     * @author Ali, Muamar
     *
     * @return bool|null|string
     */
    public function supports(Request $request)
    {
        return $request->headers->get('PHP_AUTH_USER');
    }

    /**
     * Called on every request. Return whatever credentials you want to be passed to getUser() as $credentials.
     *
     * @param Request $request | handle the request methods.
     *
     * @author Ali, Muamar
     *
     * @return array|mixed
     */
    public function getCredentials(Request $request)
    {
        return [
            'email' => $request->headers->get('PHP_AUTH_USER'),
        ];
    }

    /**
     * Return a UserInterface object based on the credentials and check api token.
     *
     * @param mixed $credentials | passed value from getCredential method.
     * @param UserProviderInterface $userProvider
     *
     * @author Ali, Muamar
     *
     * @return null|object|UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $userProvider->loadUserByUsername($credentials['email']);

        if (empty($user) || empty($user->getApiToken())) {
            throw new CustomUserMessageAuthenticationException(
                'No API Token found.'
            );
        }

        return $user;
    }

    /**
     * Check credential.
     *
     * @param mixed $credentials | passed value from getCredential method.
     * @param UserInterface $user | the user object entity.
     *
     * @author Ali, Muamar
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * If authentication success continue.
     *
     * @param Request $request | handle the request methods.
     * @param TokenInterface $token
     * @param string $providerKey
     *
     * @author Ali, Muamar
     *
     * @return null|Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * Get's error message on authentication.
     *
     * @param Request $request | handle the request methods.
     * @param AuthenticationException $exception
     *
     * @author Ali, Muamar
     *
     * @return null|JsonResponse|Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Option for remember me.
     *
     * @author Ali, Muamar
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * Called when authentication is needed, but it's not sent.
     *
     * @param Request $request | handle the request methods.
     * @param AuthenticationException|null $authException
     *
     * @author Ali, Muamar
     *
     * @return JsonResponse|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}