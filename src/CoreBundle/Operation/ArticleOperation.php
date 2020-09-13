<?php

namespace App\CoreBundle\Operation;

use App\CoreBundle\Entity\{Article, User};
use App\CoreBundle\Utils\DatabaseUtils;
use App\CoreBundle\Utils\SlugUtils;
use App\CoreBundle\Utils\ValidationUtils;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ArticleOperation
 *
 * @package App\CoreBundle\Operation
 *
 * @author Ali, Muamar
 */
class ArticleOperation
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Article
     */
    private $article;

    /**
     * @var DatabaseUtils
     */
    private $databaseUtils;

    /**
     * @var SlugUtils
     */
    private $slugUtils;

    /**
     * @var ValidationUtils
     */
    private $validatorUtils;

    /**
     * ArticleOperation constructor.
     *
     * @param EntityManagerInterface $em
     * @param DatabaseUtils $databaseUtils
     * @param SlugUtils $slugUtils
     * @param ValidationUtils $validatorUtils
     */
    public function __construct(
        EntityManagerInterface $em,
        DatabaseUtils $databaseUtils,
        SlugUtils $slugUtils,
        ValidationUtils $validatorUtils
    )
    {
        $this->em = $em;
        $this->databaseUtils = $databaseUtils;
        $this->slugUtils = $slugUtils;
        $this->validatorUtils = $validatorUtils;
    }

    /**
     * Get article.
     *
     * @author Ali, Muamar
     *
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * Set article.
     *
     * @param Article $article | article entity.
     *
     * @author Ali, Muamar
     *
     * @return ArticleOperation
     */
    public function setArticle(Article $article): ArticleOperation
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Assigning of author.
     *
     * @param User $author - article author.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return ArticleOperation
     */
    public function create(User $author): ArticleOperation
    {
        try {
            $this->article->setAuthor($author);
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the operation, create article.');
        }

        return $this;
    }

    /**
     * Update user data.
     *
     * @param string $oldTitle - old title.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return ArticleOperation
     */
    public function update(string $oldTitle): ArticleOperation
    {
        try {
            if ($oldTitle != $this->article->getTitle()) {
                $this->generateSlug();
            }
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the operation, update user.');
        }

        return $this;
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
            return $this
                ->em
                ->getRepository(Article::class)
                ->getAll();
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the operation, getting all the articles.');
        }
    }

    /**
     * Retrieve article.
     *
     * @param int $id - article id.
     *
     * @author Ali, Muamar
     *
     * @return Article|null
     */
    public function getById(int $id): ?Article
    {
        try {
            $article = $this
                ->em
                ->getRepository(Article::class)
                ->getById($id);
        } catch (\Exception $e) {
            $article = null;
        }

        return $article;
    }

    /**
     * Generating the slug title of the article.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return ArticleOperation
     */
    public function generateSlug(): ArticleOperation
    {
        if (empty($title = $this->article->getTitle())) {
            throw new \Exception('The slug can\'t be created, no title found.');
        } else {
            try {
                $this
                    ->slugUtils
                    ->checkSlug(
                        $title,
                        $this->article,
                        Article::class
                    );
            } catch (\Exception $e) {
                throw new \Exception('An error occurred at the operation, in creating the slug.');
            }
        }

        return $this;
    }

    /**
     * Validate the article entity attributes.
     *
     * @param $article
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return array|null
     */
    public function validate($article)
    {
        try {
            if ($validate = $this->validatorUtils->validate($article)) {
                return $validate;
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the operation, checking validation of article.'
            );
        }
    }

    /**
     * Save article.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return $this
     */
    public function save()
    {
        try {
            $this->databaseUtils->save($this->article);
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the operation, saving of article.'
            );
        }

        return $this;
    }

    /**
     * Delete article.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return $this
     */
    public function delete()
    {
        try {
            $this->databaseUtils->remove($this->article);
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the operation, deletion of article.'
            );
        }

        return $this;
    }
}