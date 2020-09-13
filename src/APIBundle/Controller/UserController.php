<?php

namespace App\APIBundle\Controller;

use App\CoreBundle\Entity\User;
use App\CoreBundle\Manager\UserManager;
use App\CoreBundle\Utils\{ResponseUtils, SerializerUtils};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
     * @var ResponseUtils
     */
    private $responseUtils;

    /**
     * @var SerializerUtils
     */
    private $serializerUtils;

    /**
     * UsersController constructor.
     *
     * @param UserManager $userManager
     * @param ResponseUtils $responseUtils
     * @param SerializerUtils $serializerUtils
     */
    public function __construct(
        UserManager $userManager,
        ResponseUtils $responseUtils,
        SerializerUtils $serializerUtils
    )
    {
        $this->userManager = $userManager;
        $this->responseUtils = $responseUtils;
        $this->serializerUtils = $serializerUtils;
    }

    /**
     * List of Users.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function indexAction(): JsonResponse
    {
        try {
            $response = $this->responseUtils->json(
                $this->userManager->getAll(),
                User::ADMIN_GROUP
            );
        } catch (\Exception $e) {
            $response = $this->responseUtils->json(
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
     * @param Request $request - handle the request methods.
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
                $this->userManager->create(
                    $this->serializerUtils->deserialize(
                        $request->getContent(),
                        User::class
                    )
                ),
                User::ADMIN_GROUP,
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            $response = $this->responseUtils->json(
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
     * @param int $id - user id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function getAction(int $id): JsonResponse
    {
        try {
            $response = $this->responseUtils->json(
                $this->userManager->getById($id),
                User::ADMIN_GROUP
            );
        } catch (\Exception $e) {
            $response = $this->responseUtils->json(
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
     * @param Request $request - handle the request methods.
     * @param int $id - user id.
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
            $user = $this->userManager->getById($id);

            $oldName = sprintf(
                '%s %s %s',
                $user->getFirstName(),
                $user->getMiddleName(),
                $user->getLastName()
            );

            $response = $this->responseUtils->json(
                $this->userManager->update(
                    $this->serializerUtils->deserialize(
                        $request->getContent(),
                        User::class,
                        [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
                    ),
                    $oldName
                ),
                User::ADMIN_GROUP,
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $response = $this->responseUtils->json(
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
     * Delete an user.
     *
     * @param int $id - user id.
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

            $response = $this->responseUtils->json(
                User::ADMIN_GROUP,
                Response::HTTP_NO_CONTENT
            );
        } catch (\Exception $e) {
            $response = $this->responseUtils->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => self::CATCH_ERROR_MESSAGE
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response;
    }
}