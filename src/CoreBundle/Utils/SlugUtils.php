<?php

namespace App\CoreBundle\Utils;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class SlugUtils
 *
 * @package Dtw\SlugBundle\Utils
 *
 * @author Ali, Muamar
 */
class SlugUtils
{
    /**
     * Use to separate the slugify result.
     */
    const SEPERATOR = '-';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SlugUtils constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * This function is for generate the slug for the Team url.
     *
     * @param string $string | The string that is to be slugified.
     *
     * @author  Ali, Muamar
     * @throws \Exception
     *
     * @return  string
     */
    public function slugify(string $string): string
    {
        try {
            // replace non letter or digits by -
            $string = preg_replace('~[^\pL\d]+~u', '-', $string);

            // transliterate
            $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);

            // remove unwanted characters
            $string = preg_replace('~[^-\w]+~', '', $string);

            // trim
            $string = trim($string, '-');

            // remove duplicate -
            $string = preg_replace('~-+~', '-', $string);

            // lowercase
            $string = strtolower($string);

            if (empty($string)) {
                $string = 'n-a';
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the slug utils, in slugifying.'
            );
        }

        return $string;
    }

    /**
     * This function is to generates slug ID.
     *
     * @param int $id | The id that is to be slugified.
     * @param string|NULL $prefix | The prefix of the slug.
     *
     * @author Ali, Muamar
     * @throws \Exception
     *
     * @return string
     */
    public function slugifyId(int $id, string $prefix = ''): string
    {
        try {
            //Adds trailing zeroes and a prefix
            $slug = (string)sprintf('%04s', $id);

            if (!empty($prefix)) {
                $slug = (string)$prefix . $slug;
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the slug utils, in slugifying id.'
            );
        }

        return $slug;
    }

    /**
     * Check if the slug exist.
     *
     * @param string $slug | The slug of the current entity
     * @param object $entity | The class of the entity that is being referred to.
     *
     * @author Ali, Muamar
     *
     * @return bool
     */
    public function isSlugExist(string $slug, $entity): bool
    {
        try {
            $entity = $this->getBySlug($slug, $entity);
            $result = empty($entity) ? false : true;
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Check the slug if exist then generate the slug.
     *
     * @param string $fullName the full name of the current entity.
     * @param int $i iteration for the concatination for the slug.
     * @param $entity is the entity in the database.
     * @param $class class of the entity .
     *
     * @throws \Exception
     * @author Ali, Muamar Soliven
     *
     * @return $this|SlugUtils
     */
    public function checkSlug(string $fullName, $entity, $class, int $i = 0)
    {
        try {
            $slug = $this->slugify($fullName);
            if ($i > 0) {
                if ($this->isSlugExist($slug . $this::SEPERATOR . $i, $class)) {
                    return $this->checkSlug($slug, $entity, $class, $i + 1);
                } else {
                    $entity->setSlug($slug . $this::SEPERATOR . $i);
                    return $this;
                }
            } else {
                if ($this->isSlugExist($slug, $class)) {
                    return $this->checkSlug($slug, $entity, $class, $i + 1);
                } else {
                    $entity->setSlug($slug);
                    return $this;
                }
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the slug utils, in checking slug.'
            );
        }
    }

    /**
     * Get the entity by the slug.
     *
     * @param string $slug | The slug of the entity.
     * @param object $entity | The entity to get the slug from.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return mixed
     */
    public function getBySlug(string $slug, $entity)
    {
        try {
            $entity = $this
                ->em
                ->getRepository($entity)
                ->getBySlug($slug);

        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the slug utils, in getting slug of an entity.'
            );
        }

        return $entity;
    }

    /**
     * Generating slug for the entity.
     *
     * @param string $value | entity value to be slugify.
     * @param $entityObject | entity object.
     * @param string $entityClass | entity class name.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return $this
     */
    public function generateSlug(
        string $value,
        $entityObject,
        string $entityClass
    )
    {
        if (empty($value)) {
            throw new \Exception('The slug can\'t be created, empty value found.');
        } else {
            try {
                $this
                    ->checkSlug(
                        $value,
                        $entityObject,
                        $entityClass
                    );
            } catch (\Exception $e) {
                throw new \Exception('An error occurred at the slug utils, in generating the slug.');
            }
        }

        return $this;
    }

    /**
     * Generating the slug id for the entity.
     *
     * @param $entityObject | passed entity object to create slug id.
     * @param string $entityClass | entity class name.
     * @param string $alias | entity alias.
     *
     * @throws \Exception
     * @author Ali Muamar
     *
     * @return SlugUtils
     */
    public function generateSlugId(
        $entityObject,
        string $entityClass,
        string $alias
    ): self
    {
        $entityId = $this->entityCount($entityClass);

        try {
            if ($entityId <= 0) {
                $entityId++;

                $entityObject
                    ->setSlugId(
                        $this
                            ->slugifyId($entityId, $alias)
                    );
            } else {
                $result = ltrim(
                    $this
                        ->getLastCreated($entityClass)
                        ->getSlugId(),
                    $alias
                );

                $deSlugId = intval(ltrim($result, '0'));
                $deSlugId++;

                $entityObject
                    ->setSlugId(
                        $this
                            ->slugifyId($deSlugId, $alias)
                    );
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the slug utils, in generating the slug id'
            );
        }

        return $this;
    }

    /**
     * Counts the rows.
     *
     * @param string $entityClass | passed entity class.
     *
     * @throws \Exception
     * @author Ali Muamar
     *
     * @return int
     */
    public function entityCount(string $entityClass): int
    {
        try {
            return $this
                ->em
                ->getRepository($entityClass)
                ->getRowCount();
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the slug utils, in retrieving the row count.'
            );
        }
    }

    /**
     * Get the last created entity.
     *
     * @param string $entityClass | passed entity class to get last created.
     *
     * @throws \Exception
     * @author Ali Muamar
     *
     * @return mixed
     */
    public function getLastCreated(string $entityClass)
    {
        try {
            return $this
                ->em
                ->getRepository($entityClass)
                ->getLastCreated();
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the slug utils, in getting the last created entity.'
            );
        }
    }
}