<?php

namespace App\CoreBundle\Utils;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ValidationUtils
 *
 * @package App\CoreBundle\Utils
 *
 * @author Ali, Muamar
 */
class ValidationUtils
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * ValidatorUtils constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Check entity if there's a error.
     *
     * @param $entity
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return array|null
     */
    public function validate($entity): ?array
    {
        try {
            $entityErrors = $this->validator->validate($entity);
            $errors = [];

            foreach ($entityErrors as $error) {
                $errors[$error->getPropertyPath()] = $error->getMessage();
            }

            return $errors;
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the validation utils, checking validation of entity.'
            );
        }
    }
}