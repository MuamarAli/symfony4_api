<?php

namespace App\CoreBundle\Manager;

use App\CoreBundle\Entity\User;
use App\CoreBundle\Operation\UserOperation;
use App\CoreBundle\Utils\{SerializerUtils, ValidationUtils};
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class UserManager
 *
 * @package App\CoreBundle\Manager
 *
 * @author Ali, Muamar
 */
class UserManager
{
    /**
     * @var UserOperation
     */
    private $userOperation;

    /**
     * @var ValidationUtils
     */
    private $validatorUtils;

    /**
     * @var SerializerUtils
     */
    private $serializerUtils;

    /**
     * UserManager constructor.
     *
     * @param UserOperation $userOperation
     * @param ValidationUtils $validatorUtils
     * @param SerializerUtils $serializerUtils
     */
    public function __construct(
        UserOperation $userOperation,
        ValidationUtils $validatorUtils,
        SerializerUtils $serializerUtils
    )
    {
        $this->userOperation = $userOperation;
        $this->validatorUtils = $validatorUtils;
        $this->serializerUtils = $serializerUtils;
    }

    /**
     * Retrieve all users.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return
     */
    public function getAll()
    {
        try {
            return $this->serializerUtils->serialize(
                $this->userOperation->getAll()
            );
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the manager, getting all the user.');
        }
    }

    /**
     * Retrieve user.
     *
     * @param int $id | user id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return
     */
    public function getById(int $id)
    {
        try {
            if (empty($user = $this->userOperation->getById($id))) {
                throw new \Exception ('User does not exist.');
            } else {
                $result = $this->serializerUtils->serialize($user);
            }
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the manager, getting user.');
        }

        return $result;
    }

    /**
     * Create user.
     *
     * @param string $content | content.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return User|null
     */
    public function create(string $content)
    {
        try {
            $user = $this->serializerUtils->deserialize(
                $content,
                User::class
            );

            if ($errors = $this->validate($user)) {
                $result = $errors;
            } else {
                $result = $this
                    ->userOperation
                    ->setUser($user)
                    ->create()
                    ->encryptPassword()
                    ->setToken()
                    ->generateSlug()
                    ->save()
                    ->getUser();
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, creation of user.'
            );
        }

        return $result;
    }

    /**
     * Update user.
     *
     * @param string $content | request method.
     * @param int $id | user id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return User|null
     */
    public function update(
        string $content,
        int $id
    )
    {
        try {
            $user = $this->getById($id);

            $oldName = sprintf(
                '%s %s %s',
                $user->getFirstName(),
                $user->getMiddleName(),
                $user->getLastName()
            );

            $updateUser = $this->serializerUtils->deserialize(
                $content,
                User::class,
                [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
            );

            if ($errors = $this->validate($user)) {
                $result = $errors;
            } else {
                $result = $this
                    ->userOperation
                    ->setUser($updateUser)
                    ->update($oldName)
                    ->save()
                    ->getUser();
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, updating of user.'
            );
        }

        return $result;
    }

    /**
     * Delete user.
     *
     * @param int $id | user id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return UserOperation
     */
    public function delete(int $id): UserOperation
    {
        try {
            return $this
                ->userOperation
                ->setUser($this->getById($id))
                ->delete();
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, deletion of user.'
            );
        }
    }

    /**
     * Validate the user entity attributes,
     *
     * @param User $user | user entity.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return null
     */
    public function validate(User $user)
    {
        try {
            if ($validate = $this->validatorUtils->validate($user)) {
                return $validate;
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the manager, checking validation of user.'
            );
        }
    }
}