<?php

namespace App\APIBundle\Controller;

use App\CoreBundle\Manager\ArticleManager;
use App\CoreBundle\Entity\Article;
use App\CoreBundle\Utils\APIUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class ArticleController
 *
 * @package App\ApiBundle\Controller
 *
 * @author Ali, Muamar
 */
class ArticleController extends AbstractController
{
    /**
     * Error message in the try catch.
     */
    const CATCH_ERROR_MESSAGE = 'Oops! Something went wrong! Please contact the Developer.';

    /**
     * @var ArticleManager
     */
    private $articleManager;

    private $APIUtils;

    /**
     * ArticleController constructor.
     *
     * @param ArticleManager $articleManager
     */
    public function __construct(ArticleManager $articleManager, APIUtils $APIUtils)
    {
        $this->articleManager = $articleManager;
        $this->APIUtils = $APIUtils;
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
            $response = $this->APIUtils->respond(
                $this->articleManager->serialize($this->articleManager->getAll())
            );
        } catch (\Exception $e) {
            $response = $this->apiResponse(
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
            if (empty($article = $this->articleManager->getById($id))) {
                $response = $this->json(
                    [
                        'status' => Response::HTTP_NOT_FOUND,
                        'message' => sprintf('Sorry, article with ID: %s, does not exist.', $id)
                    ],
                    Response::HTTP_NOT_FOUND
                );
            } else {
                $response = $this->apiResponse($this->articleManager->serialize($article));
            }
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
            $article = $this
                ->articleManager
                ->deserialize(
                    $request->getContent(),
                    Article::class
                );

            if ($error = $this->articleManager->validate($article)) {
                $response = $this->json($error);
            } else {
                $this
                    ->articleManager
                    ->create($article);

                $response = $this->json(
                    [
                        'status' => Response::HTTP_CREATED,
                        'message' => sprintf(
                            'You\'ve successfully added %s.',
                            $article->getTitle()
                        )
                    ]
                );
            }
        } catch (\Exception $e) {
            $response = $this->json(
                [
                    'success' => false,
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
            if (empty($article = $this->articleManager->getById($id))) {
                $response = $this->json(
                    [
                        'status' => Response::HTTP_BAD_REQUEST,
                        'message' => sprintf('Sorry, article with ID: %s, does not exist.', $id)
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            } else {
                $updateArticle = $this
                    ->articleManager
                    ->deserialize(
                        $request->getContent(),
                        Article::class,
                        [AbstractNormalizer::OBJECT_TO_POPULATE => $article]
                    );

                if ($error = $this->articleManager->validate($updateArticle)) {
                    $response = $this->json($error);
                } else {
                    $this
                        ->articleManager
                        ->update($updateArticle);

                    $response = $this->json(
                        [
                            'status' => Response::HTTP_OK,
                            'message' => sprintf(
                                'You\'ve successfully modified %s - %s.',
                                $updateArticle->getId(),
                                $updateArticle->getTitle()
                            )
                        ]
                    );
                }
            }
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
            if (empty($article = $this->articleManager->getById($id))) {
                $response = $this->json(
                    [
                        'status' => Response::HTTP_NOT_FOUND,
                        'message' => sprintf('Sorry, article with ID: %s, does not exist.', $id)
                    ],
                    Response::HTTP_NOT_FOUND
                );
            } else {
                $this
                    ->articleManager
                    ->delete($article);

                $response = $this->json(
                    [
                        'status' => Response::HTTP_OK,
                        'message' => sprintf(
                            'You\'ve successfully deleted %s - %s.',
                            $article->getId(),
                            $article->getTitle()
                        )
                    ],
                    Response::HTTP_OK
                );
            }
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
     * @param $data | serialized array or object.
     * @param $code | status code.
     *
     * @return Response
     */
    public function apiResponse($data, $code = Response::HTTP_OK)
    {
        return $this->json(
            $data,
            $code,
            ['Content-type' => 'application/json']
        );
    }
}