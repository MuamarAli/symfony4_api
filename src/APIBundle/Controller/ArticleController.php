<?php

namespace App\APIBundle\Controller;

use App\CoreBundle\Manager\ArticleManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

/**
 * Class ArticleController
 *
 * @package App\APIBundle\Controller
 *
 * @author Ali, Muamar
 */
class ArticleController extends AbstractController
{
    /**
     * @var ArticleManager
     */
    private $articleManager;

    /**
     * UsersController constructor.
     *
     * @param ArticleManager $articleManager
     */
    public function __construct(ArticleManager $articleManager)
    {
        $this->articleManager = $articleManager;
    }

    /**
     * List of Articles.
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
                $this->articleManager->getAll()
            );
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $response;
    }

    /**
     * Create Article.
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
                $this->articleManager->create(
                    $request->getContent(),
                    $this->getUser()
                ),
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response;
    }

    /**
     * Get and display single article.
     *
     * @param string $id | article id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return Response
     */
    public function getAction(string $id): Response
    {
        try {
            $response = $this->response(
                $this->articleManager->getById($id)
            );
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response;
    }

    /**
     * Update the article information.
     *
     * @param Request $request | handle the request methods.
     * @param string $id | article slug id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function updateAction(
        Request $request,
        string $id
    ): JsonResponse
    {
        try {

            $updateUser = $this->articleManager->update(
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
                    'message' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response;
    }

    /**
     * Delete an article.
     *
     * @param string $id | article slug id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function deleteAction(string $id): JsonResponse
    {
        try {
            $this->articleManager->delete($id);

            $response = $this->json(Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'errors' => $e->getMessage()
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