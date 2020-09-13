<?php

namespace App\CoreBundle\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController
 *
 * @package App\CoreBundle\Security
 *
 * @author Ali, Muamar
 */
class SecurityController extends AbstractController
{
    /**
     * Logged-in user.
     *
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function loginAction()
    {
        $user = $this->getUser();

        return new JsonResponse(
            [
                'apiToken' => $user->getApiToken(),
                'roles' => $user->getRoles(),
                'email' => $user->getEmail(),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Unvalidate session.
     *
     * @param Request $request | handle the request methods.
     *
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function logoutAction(Request $request)
    {
        $request->getSession()->invalidate();

        return $this->json(
            [
                'success' => true,
                'status' => Response::HTTP_OK,
                'message' => "Successfully logout."
            ],
            Response::HTTP_OK
        );
    }
}