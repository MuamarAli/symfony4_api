<?php

namespace App\CoreBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class EntitiesListener
 *
 * @package App\CoreBundle\EventListener
 *
 * @author Ali, Muamar
 */
class EntitiesListener
{
    /**
     * Set createdAt every insertion.
     *
     * @param LifecycleEventArgs $args
     *
     * @author Ali, Muamar
     *
     * @return object
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $entity->setCreatedAt(new \DateTime());

        return $entity;
    }

    /**
     * Set updatedAt every modification.
     *
     * @param LifecycleEventArgs $args
     *
     * @author Ali, Muamar
     *
     * @return object
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $entity->setUpdatedAt(new \DateTime());

        return $entity;
    }
}