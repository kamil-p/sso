<?php

namespace AppBundle\Document;

use UserBundle\Document\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    use TimestampableTrait;

}