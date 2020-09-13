<?php

namespace App\APIBundle\Controller;

use App\CoreBundle\Entity\Article;
use App\CoreBundle\Manager\ArticleManager;
use App\CoreBundle\Utils\{ResponseUtils, SerializerUtils};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
     * Error message in the try catch.
     */
    const CATCH_ERROR_MESSAGE = 'Oops! Something went wrong! Please contact our PHP Development Team.';
    
    /**
     * @var ArticleManager
     */
    private $articleManager;

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
     * @param ArticleManager $articleManager
     * @param ResponseUtils $responseUtils
     * @param SerializerUtils $serializerUtils
     */
    public function __construct(
        ArticleManager $articleManager,
        ResponseUtils $responseUtils,
        SerializerUtils $serializerUtils
    )
    {
        $this->articleManager = $articleManager;
        $this->responseUtils = $responseUtils;
        $this->serializerUtils = $serializerUtils;
    }

    /**
     * List of Articles.
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
                $this->articleManager->getAll()
            );
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
     * Create Article.
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
            $response = $this->responseUtils->json(
                $this->articleManager->create(
                    $this->serializerUtils->deserialize(
                        $request->getContent(),
                        Article::class
                    ),
                    $this->getUser()
                ),
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
     * Get and display single article.
     *
     * @param int $id - article id.
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
                $this->articleManager->getById($id)
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
     * Update the article information.
     *
     * @param Request $request - handle the request methods.
     * @param string $id - article slug id.
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
            $article = $this->articleManager->getById($id);

            $oldTitle = $article->getTitle();

            $response = $this->responseUtils->json(
                $this->articleManager->update(
                    $this->serializerUtils->deserialize(
                        $request->getContent(),
                        Article::class,
                        [AbstractNormalizer::OBJECT_TO_POPULATE => $article]
                    ),
                    $oldTitle
                ),
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
     * Delete an article.
     *
     * @param int $id - article id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function deleteAction(int $id): JsonResponse
    {
        try {
            $this->articleManager->delete($id);

            $response = $this->responseUtils->json(Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'errors' => self::CATCH_ERROR_MESSAGE
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response;
    }
}