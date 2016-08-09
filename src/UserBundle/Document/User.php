<?php

namespace UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use UserBundle\Exception\Document\InvalidRoleException;

abstract class User
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    public static $ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_USER
    ];

    /**
     * @var string
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var string
     * @MongoDB\Field
     * @MongoDB\Index(unique=true)
     */
    protected $email;

    /**
     * @var string
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

    public function __construct(String $email, String $password, Address $address)
    {
        $this->email = $email;
        $this->password = $password;
        base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = true;
        $this->address = $address->toArray();
        $this->roles = [];
    }

    public function addRole(String $role)
    {
        if (in_array($role, self::$ROLES) && !in_array($role, $this->roles)) {
            $this->roles[] = $role;
        } else {
            throw new InvalidRoleException($role);
        }
    }

    public function hasRole(String $role) : Bool
    {
        $result = false;
        if (in_array($role, $this->roles)) {
            $result = true;
        }
        return $result;
    }

}