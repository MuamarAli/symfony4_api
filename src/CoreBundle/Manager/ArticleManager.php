<?php

namespace App\CoreBundle\Manager;

use App\CoreBundle\Entity\Article;
use App\CoreBundle\Operation\ArticleOperation;
use App\CoreBundle\Utils\{SerializerUtils, ValidationUtils};

/**
 * Class ArticleManager
 *
 * @package App\CoreBundle\
 *
 * @author Anima, Renz
 */
class ArticleManager
{
    /**
     * @var ArticleOperation
     */
    private $articleOperation;

    /**
     * @var ValidationUtils
     */
    private $validatorUtils;

    /**
     * @var SerializerUtils
     */
    private $serializerUtils;

    /**
     * ArticleManager constructor.
     *
     * @param ArticleOperation $articleOperation
     * @param ValidationUtils $validatorUtils
     * @param SerializerUtils $serializerUtils
     */
    public function __construct(
        ArticleOperation $articleOperation,
        ValidationUtils $validatorUtils,
        SerializerUtils $serializerUtils
    )
    {
        $this->articleOperation = $articleOperation;
        $this->validatorUtils = $validatorUtils;
        $this->serializerUtils = $serializerUtils;
    }

    /**
     * Retrieve all articles.
     *
     * @throws \Exception
     * @author Anima, Renz
     *
     * @return array|null
     */
    public function getAll(): ?array
    {
        try {
            return $this
                ->articleOperation
                ->getAll();
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the manager, getting all the articles.');
        }
    }

    /**
     * Retrieve article.
     *
     * @param string $id | article slug id.
     *
     * @author Anima, Renz
     *
     * @return Article|null
     */
    public function getById(string $id): ?Article
    {
        try {
            $article = $this
                ->articleOperation
                ->getById($id);
        } catch (\Exception $e) {
            $article = null;
        }

        return $article;
    }

    /**
     * Create article.
     *
     * @param Article $article | The article data
     *
     * @throws \Exception
     * @author Anima, Renz
     *
     * @return ArticleOperation
     */
    public function create(Article $article): ArticleOperation
    {
        try {
            return $this
                ->articleOperation
                ->setArticle($article)
                ->generateSlug()
                ->save();
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, creation of article.'
            );
        }
    }

    /**
     * Update article.
     *
     * @param Article $article | article entity.
     *
     * @throws \Exception
     * @author Anima, Renz
     *
     * @return ArticleOperation
     */
    public function update(Article $article): ArticleOperation
    {
        try {
            return $this
                ->articleOperation
                ->setArticle($article)
                ->generateSlug()
                ->save();
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, updating of article.'
            );
        }
    }

    /**
     * Delete article.
     *
     * @param Article $article | article entity.
     *
     * @throws \Exception
     * @author Anima, Renz
     *
     * @return ArticleOperation
     */
    public function delete(Article $article): ArticleOperation
    {
        try {
            return $this
                ->articleOperation
                ->setArticle($article)
                ->delete();
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, deletion of article.'
            );
        }
    }

    /**
     * Validate the article entity attributes.
     *
     * @param $article
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return null
     */
    public function validate($article)
    {
        try {
            return $this->validatorUtils->validate($article);
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, checking validation of article.'
            );
        }
    }

    /**
     * Serialize content array object into json.
     *
     * @param $data | article data.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return string
     */
    public function serialize($data)
    {
        try {
            return $this->serializerUtils->serialize($data);
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, serializing of article.'
            );
        }
    }

    /**
     * Deserialize content format into object.
     *
     * @param string $content | json content.
     * @param string $entityClass | entity class name.
     * @param array $context.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return array|object
     */
    public function deserialize
    (
        string $content,
        string $entityClass,
        array $context = []
    )
    {
        try {
            return $this
                ->serializerUtils
                ->deserialize(
                    $content,
                    $entityClass,
                    $context
                );
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, deserializing of article request content.'
            );
        }
    }
}