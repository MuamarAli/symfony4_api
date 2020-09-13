<?php

namespace App\CoreBundle\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ResponseUtils
 *
 * @package App\CoreBundle\Utils
 */
class ResponseUtils
{
    /**
     * @var SerializerUtils
     */
    private $serializerUtils;

    /**
     * ResponseUtils constructor.
     *
     * @param SerializerUtils $serializerUtils
     */
    public function __construct(SerializerUtils $serializerUtils)
    {
        $this->serializerUtils = $serializerUtils;
    }

    /**
     * Return json serialize object.
     *
     * @param $data | object.
     * @param int $status | status code.
     * @param array $headers | optional headers.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return JsonResponse
     */
    public function json(
        $data,
        int $status = 200,
        array $headers = []
    ): JsonResponse
    {
        return new JsonResponse(
            $this->serializerUtils->serialize($data),
            $status,
            $headers,
            true
        );
    }
}