<?php

namespace App\CoreBundle\Repository;

use App\CoreBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 *
 * @package App\CoreBundle\Repository
 *
 * @author Ali, Muamar
 */
class UserRepository extends EntityRepository
{
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