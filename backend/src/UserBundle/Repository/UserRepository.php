<?php

namespace UserBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use UserBundle\Document\User;

abstract class UserRepository extends DocumentRepository
{
    public function save(User $user)
    {
        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush($user);
    }
}