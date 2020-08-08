<?php

namespace App\CoreBundle\Utils;

/**
 * This class is used for all save and remove operation in all entity.
 *
 * @package App\CoreBundle\Utils
 *
 * @author Ali, Muamar
 */
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DatabaseUtils
 *
 * @package App\CoreBundle\Utils
 *
 * @author Ali, Muamar
 */
class DatabaseUtils
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DatabaseUtils constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * To save an entity in database.
     *
     * @param object $entity | entity object.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return DatabaseUtils
     */
    public function save($entity): self
    {
        try {
            $this->em->persist($entity);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the insertion of an entity.'
            );
        }

        return $this;
    }

    /**
     * To remove an entity in database.
     *
     * @param object $entity | entity object.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return DatabaseUtils
     */
    public function remove($entity): self
    {
        try {
            $this->em->remove($entity);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the deletion of an entity.'
            );
        }

        return $this;
    }
}