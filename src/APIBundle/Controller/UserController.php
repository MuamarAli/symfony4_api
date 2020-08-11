<?php

namespace App\APIBundle\Controller;

use App\CoreBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

/**
 * Class UserController
 *
 * @package App\APIBundle\Controller
 *
 * @author Ali, Muamar
 */
class UserController extends AbstractController
{
    /**
     * Error message in the try catch.
     */
    const CATCH_ERROR_MESSAGE = 'Oops! Something went wrong! Please contact our PHP Development Team.';

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * UsersController constructor.
     *
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * List of Users.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        try {
            $response = $this->response(
                $this->userManager->getAll())
            ;
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => self::CATCH_ERROR_MESSAGE
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $response;
    }

    /**
     * Create User.
     *
     * @param Request $request | handle the request methods.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        try {
            $response = $this->json(
                $this->userManager->create($request->getContent()),
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => self::CATCH_ERROR_MESSAGE
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response;
    }

    /**
     * Get and display single user.
     *
     * @param int $id | user id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return Response
     */
    public function getAction(int $id): Response
    {
        try {
            $response = $this->response(
                $this->userManager->getById($id)
            );
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => self::CATCH_ERROR_MESSAGE
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response;
    }

    /**
     * Update the user information.
     *
     * @param Request $request | handle the request methods.
     * @param int $id | user id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function updateAction(
        Request $request,
        int $id
    ): JsonResponse
    {
        try {
            $updateUser = $this->userManager->update(
                $request->getContent(),
                $id
            );

            $response = $this->json(
                $updateUser,
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => self::CATCH_ERROR_MESSAGE
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response;
    }

    /**
     * Delete a user.
     *
     * @param int $id | user id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function deleteAction(int $id): JsonResponse
    {
        try {
            $this->userManager->delete($id);

            $response = $this->json(Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => self::CATCH_ERROR_MESSAGE
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response;
    }

    /**
     * Return serialize data.
     *
     * @param $data | serialized array or object
     *
     * @return Response
     */
    public function response($data)
    {
        return new Response(
            $data,
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}