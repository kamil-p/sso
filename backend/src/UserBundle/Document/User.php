<?php

namespace UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use UserBundle\Exception\Document\InvalidRoleException;

abstract class User implements UserInterface, EquatableInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    public static $ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_USER
    ];

    /**
     * @var String
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var String
     * @MongoDB\Field
     * @MongoDB\Index(unique=true)
     */
    protected $email;

    /**
     * @var String
     * @MongoDB\Field
     */
    protected $password;

    /**
     * @var bool
     * @MongoDB\Field(type="bool")
     */
    protected $enabled;

    /**
     * @var Address
     * @MongoDB\Field(type="collection")
     */
    protected $address;

    /**
     * @var array
     * @MongoDB\Field(type="collection")
     */
    protected $roles = [];

    public function __construct(String $email, Address $address)
    {
        $this->email = $email;
        $this->enabled = true;
        $this->address = $address->toArray();
        $this->roles = [];
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(String $role)
    {
        if (in_array($role, self::$ROLES) && !in_array($role, $this->roles)) {
            $this->roles[] = $role;
        } else {
            throw new InvalidRoleException($role);
        }
    }

    public function hasRole(String $role): Bool
    {
        $result = false;
        if (in_array($role, $this->roles)) {
            $result = true;
        }
        return $result;
    }

    public function isEqualTo(UserInterface $user): Bool
    {

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->email !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    public function getPassword(): String
    {
        return $this->password;
    }

    public function getUsername(): String
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function getEmail(): String
    {
        return $this->email;
    }

    public function getSalt()
    {
        /* bcrypt (security.yml) doesn't need to have salt it is doing it internally */
        return null;
    }

    public function setPassword(String $password)
    {
        $this->password = $password;
    }


}