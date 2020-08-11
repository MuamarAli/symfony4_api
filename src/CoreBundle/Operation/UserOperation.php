<?php

namespace App\CoreBundle\Operation;

use App\CoreBundle\Entity\User;
use App\CoreBundle\Utils\DatabaseUtils;
use App\CoreBundle\Utils\SlugUtils;
use App\CoreBundle\Utils\TokenUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserOperation
 *
 * @package App\CoreBundle\Operation
 *
 * @author Ali, Muamar
 */
class UserOperation
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var User
     */
    private $user;

    /**
     * @var DatabaseUtils
     */
    private $databaseUtils;

    /**
     * @var SlugUtils
     */
    private $slugUtils;

    /**
     * @var TokenUtils
     */
    private $tokenUtils;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserOperation constructor.
     *
     * @param EntityManagerInterface $em
     * @param DatabaseUtils $databaseUtils
     * @param SlugUtils $slugUtils
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenUtils $tokenUtils
     */
    public function __construct(
        EntityManagerInterface $em,
        DatabaseUtils $databaseUtils,
        SlugUtils $slugUtils,
        UserPasswordEncoderInterface $passwordEncoder,
        TokenUtils $tokenUtils
    )
    {
        $this->em = $em;
        $this->databaseUtils = $databaseUtils;
        $this->slugUtils = $slugUtils;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenUtils = $tokenUtils;
    }

    /**
     * Get user.
     *
     * @author Ali, Muamar
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set user.
     *
     * @param User $user | user entity.
     *
     * @author Ali, Muamar
     *
     * @return UserOperation
     */
    public function setUser(User $user): UserOperation
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Assigning email and role.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return UserOperation
     */
    public function create(): UserOperation
    {
        try {
            $this
                ->user
                ->setUsername($this->user->getEmail())
                ->setRoles(['ROLE_API']);
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the operation, create user.');
        }

        return $this;
    }

    /**
     * Update user data.
     *
     * @param string $oldName | old full name.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return UserOperation
     */
    public function update(string $oldName): UserOperation
    {
        try {
            if ($this->user->getEmail() != $this->user->getUsername()) {
                $this->user->setUsername($this->user->getEmail());
            }

            if ($oldName != $this->getFullName()) {
                $this->generateSlug();
            }
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the operation, update user.');
        }

        return $this;
    }

    /**
     * Retrieve all user.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return array|null
     */
    public function getAll(): ?array
    {
        try {
            return $this
                ->em
                ->getRepository(User::class)
                ->getAll();
        } catch (\Exception $e) {
            throw new \Exception('An error occurred at the operation, getting all the user.');
        }
    }

    /**
     * Retrieve user.
     *
     * @param int $id | user id.
     *
     * @author Ali, Muamar
     *
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        try {
            $user = $this
                ->em
                ->getRepository(User::class)
                ->getById($id);
        } catch (\Exception $e) {
            $user = null;
        }

        return $user;
    }

    /**
     * Generating user slug.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return UserOperation
     */
    public function generateSlug(): UserOperation
    {
        if (empty($fullName = $this->getFullName())) {
            throw new \Exception('The slug can\'t be created, no name found.');
        } else {
            try {
                $this
                    ->slugUtils
                    ->checkSlug(
                        $fullName,
                        $this->user,
                        User::class
                    );
            } catch (\Exception $e) {
                throw new \Exception('An error occurred at the operation, in creating the slug.');
            }
        }

        return $this;
    }

    /**
     * Return user full name.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return string
     */
    public function getFullName()
    {
        try {
            return sprintf(
                '%s %s %s',
                $this->user->getFirstName(),
                $this->user->getMiddleName(),
                $this->user->getLastName()
            );
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the operation, in getting full name.'
            );
        }
    }

    /**
     * Generating the encrypted password.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return UserOperation
     */
    public function encryptPassword(): UserOperation
    {
        try {
            $userPassword = $this->user->getPassword();

            if (empty($userPassword)) {
                throw new \Exception('Password is empty.');
            } else {
                $password = $this->passwordEncoder->encodePassword($this->user, $userPassword, null);
                $this->user->setPassword($password);
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the operation, in encrypting password.'
            );
        }

        return $this;
    }

    /**
     * Set the new token for the user.
     *
     * @throws \Exception
     * @author Ali, Muamar
     *
     * @return UserOperation
     */
    public function setToken(): UserOperation
    {
        try {
            $this->user
                ->setApiToken(
                    $this
                        ->tokenUtils
                        ->generateToken()
                );
        } catch (\Exception $e) {
            throw new \Exception(
                'Can\'t generate token.'
            );
        }

        return $this;
    }

    /**
     * Save user.
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function save()
    {
        try {
            $this->databaseUtils->save($this->user);
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the operation, saving of user.'
            );
        }

        return $this;
    }

    /**
     * Delete user.
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function delete()
    {
        try {
            $this->databaseUtils->remove($this->user);
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at the operation, deletion of user.'
            );
        }

        return $this;
    }
}