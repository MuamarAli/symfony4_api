<?php

namespace App\CoreBundle\Manager;

use App\CoreBundle\Entity\{Article, User};
use App\CoreBundle\Operation\ArticleOperation;
use App\CoreBundle\Utils\SerializerUtils;

/**
 * Class ArticleManager
 *
 * @package App\CoreBundle\ArticleManager
 *
 * @author Ali, Muamar
 */
class ArticleManager
{
    /**
     * @var ArticleOperation
     */
    private $articleOperation;

    /**
     * @var SerializerUtils
     */
    private $serializerUtils;

    /**
     * ArticleManager constructor.
     *
     * @param ArticleOperation $articleOperation
     * @param SerializerUtils $serializerUtils
     */
    public function __construct(
        ArticleOperation $articleOperation,
        SerializerUtils $serializerUtils
    )
    {
        $this->articleOperation = $articleOperation;
        $this->serializerUtils = $serializerUtils;
    }

    /**
     * Retrieve all articles.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return array|null
     */
    public function getAll(): ?array
    {
        try {
            return $this->articleOperation->getAll();
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the manager, getting all the articles.');
        }
    }

    /**
     * Retrieve article.
     *
     * @param int $id - article slug id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return Article|null
     */
    public function getById(int $id): ?Article
    {
        try {
            if (empty($article = $this->articleOperation->getById($id))) {
                throw new \Exception ('Article does not exist.');
            } else {
                $result = $article;
            }
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the manager, getting article.');
        }

        return $result;
    }

    /**
     * Create article.
     *
     * @param Article $article - entity.
     * @param User $author - the logged-in user
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return Article|array|null
     */
    public function create(
        Article $article,
        User $author
    )
    {
        try {
            if ($errors = $this->articleOperation->validate($article)) {
                $result = $errors;
            } else {
                $result = $this
                    ->articleOperation
                    ->setArticle($article)
                    ->create($author)
                    ->generateSlug()
                    ->save()
                    ->getArticle();
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, creation of article.'
            );
        }

        return $result;
    }

    /**
     * Update article.
     *
     * @param Article $updateArticle - entity.
     * @param string $oldTitle - old title.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return Article|array|null
     */
    public function update(
        Article $updateArticle,
        string $oldTitle
    )
    {
        try {
            if ($errors = $this->articleOperation->validate($updateArticle)) {
                $result = $errors;
            } else {
                $result = $this
                    ->articleOperation
                    ->setArticle($updateArticle)
                    ->update($oldTitle)
                    ->save()
                    ->getArticle();
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, updating of article.'
            );
        }

        return $result;
    }

    /**
     * Delete article.
     *
     * @param int $id - article id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return ArticleOperation
     */
    public function delete(int $id): ArticleOperation
    {
        try {
            return $this
                ->articleOperation
                ->setArticle($this->getById($id))
                ->delete();
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, deletion of article.'
            );
        }
    }
}