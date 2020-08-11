<?php

namespace App\CoreBundle\Manager;

use App\CoreBundle\Entity\{Article, User};
use App\CoreBundle\Operation\ArticleOperation;
use App\CoreBundle\Utils\{SerializerUtils, ValidationUtils};
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class ArticleManager
 *
 * @package App\CoreBundle\
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
     * @author Ali, Muamar
     *
     * @return
     */
    public function getAll()
    {
        try {
            return $this->serializerUtils->serialize(
                $this->articleOperation->getAll()
            );
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the manager, getting all the articles.');
        }
    }

    /**
     * Retrieve article.
     *
     * @param string $id | article slug id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return
     */
    public function getById(string $id)
    {
        try {
            if (empty($article = $this->articleOperation->getById($id))) {
                throw new \Exception ('Article does not exist.');
            } else {
                $result = $this->serializerUtils->serialize($article);
            }
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the manager, getting article.');
        }

        return $result;
    }

    /**
     * Create article.
     *
     * @param string $content | content.
     * @param User $author | The user logged in
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return
     */
    public function create(
        string $content,
        User $author
    )
    {
        try {
            $article = $this->serializerUtils->deserialize(
                $content,
                Article::class
            );

            if ($errors = $this->validate($article)) {
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
     * @param string $content | content.
     * @param int $id | article id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return
     */
    public function update($content, $id)
    {
        try {
            $article = $this->getById($id);

            $oldTitle = $article->getTitle();

            $updateArticle = $this->serializerUtils->deserialize(
                $content,
                Article::class,
                [AbstractNormalizer::OBJECT_TO_POPULATE => $article]
            );

            if ($errors = $this->validate($article)) {
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
     * @param int $id | article id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return ArticleOperation
     */
    public function delete($id): ArticleOperation
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
            if ($validate = $this->validatorUtils->validate($article)) {
                return $validate;
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, checking validation of article.'
            );
        }
    }
}