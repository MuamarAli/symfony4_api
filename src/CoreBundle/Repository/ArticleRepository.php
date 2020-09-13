<?php

namespace App\CoreBundle\Repository;

use App\CoreBundle\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * ArticleRepository constructor.
     * 
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

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
