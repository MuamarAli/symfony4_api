<?php

namespace App\BlogBundle\Controller;

use App\CoreBundle\Entity\Article;
use App\CoreBundle\Manager\ArticleManager;
use App\CoreBundle\Utils\{ResponseUtils, SerializerUtils};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Response};

/**
 * Class BlogController
 *
 * @package App\BlogBundle\Controller
 *
 * @author Ali, Muamar
 */
class BlogController extends AbstractController
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
                $this->articleManager->getAll(),
                Article::PUBLIC_GROUP
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
}