<?php

namespace App\CoreBundle\Repository;

use App\CoreBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Get all the user from database.
     *
     * @author Ali, Muamar
     *
     * @return array|null
     */
    public function getAll(): ?array
    {
        return $this->findAll();
    }

    /**
     * Get user by it's id.
     *
     * @param int $id | passed id of the user.
     *
     * @author Ali, Muamar
     *
     * @return mixed
     */
    public function getById(int $id): ?User
    {
        return $this->findOneBy(
            ['id' => $id]
        );
    }
}