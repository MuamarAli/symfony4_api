<?php

namespace App\CoreBundle\Manager;

use App\CoreBundle\Entity\User;
use App\CoreBundle\Operation\UserOperation;
use App\CoreBundle\Utils\SerializerUtils;
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
     * @var SerializerUtils
     */
    private $serializerUtils;

    /**
     * UserManager constructor.
     *
     * @param UserOperation $userOperation
     * @param SerializerUtils $serializerUtils
     */
    public function __construct(
        UserOperation $userOperation,
        SerializerUtils $serializerUtils
    )
    {
        $this->userOperation = $userOperation;
        $this->serializerUtils = $serializerUtils;
    }

    /**
     * Retrieve all users.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return array|null
     */
    public function getAll(): ?array
    {
        try {
            return $this->userOperation->getAll();
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the manager, getting all the user.');
        }
    }

    /**
     * Retrieve user.
     *
     * @param int $id - user id.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        try {
            if (empty($user = $this->userOperation->getById($id))) {
                throw new \Exception ('User does not exist.');
            } else {
                $result = $user;
            }
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the manager, getting user.');
        }

        return $result;
    }

    /**
     * Create user.
     *
     * @param User $user - entity.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return User|array|null
     */
    public function create(User $user)
    {
        try {
            if ($errors = $this->userOperation->validate($user)) {
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
     * @param User $updateUser - entity.
     * @param string $oldName - old name.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return User|array|null
     */
    public function update(
        User $updateUser,
        string $oldName
    )
    {
        try {
            if ($errors = $this->userOperation->validate($updateUser)) {
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
     * @param int $id - user id.
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
}