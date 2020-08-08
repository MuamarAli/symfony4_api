<?php

namespace App\CoreBundle\Repository;

use App\CoreBundle\Entity\Article;
use Doctrine\ORM\EntityRepository;

/**
 * Class ArticleRepository
 *
 * @package App\CoreBundle\Repository
 *
 * @author Ali, Muamar
 */
class ArticleRepository extends EntityRepository
{
    /**
     * Get all the article from database.
     *
     * @return array|null
     */
    public function getAll(): ?array
    {
        return $this->findAll();
    }

    /**
     * Get article by it's id.
     *
     * @param string $id | passed id of the article.
     *
     * @author Ali, Muamar
     *
     * @return mixed
     */
    public function getById(string $id): ?Article
    {
        return $this->findOneBy(
            ['id' => $id]
        );
    }
}