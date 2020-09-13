<?php

namespace App\CoreBundle\Utils;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class SerializerUtils
 *
 * @package App\CoreBundle\Utils
 *
 * @author Ali, Muamar
 */
class SerializerUtils
{
    /**
     * Json format
     */
    const RESPONSE_FORMAT = 'json';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * SerializerUtils constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Conversion of array object into json.
     *
     * @param $data - object data.
     * @param string $group - serialize group.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return string
     */
    public function serialize(
        $data,
        string $group
    )
    {
        try {
            $context = [
                'groups' => [$group],
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ];

            return $this->serializer->serialize(
                $data,
                self::RESPONSE_FORMAT,
                $context
            );
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the serializer utils, serializing of entity.'
            );
        }
    }

    /**
     * Deserialize request content into object.
     *
     * @param string $content | json content.
     * @param string $entityClass | entity class name.
     * @param array $context.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return array|object
     */
    public function deserialize(
        string $content,
        string $entityClass,
        array $context = []
    )
    {
        try {
            return $this
                ->serializer
                ->deserialize(
                    $content,
                    $entityClass,
                    self::RESPONSE_FORMAT,
                    $context
                );
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the serializer utils, deserializing of entity.'
            );
        }
    }
}