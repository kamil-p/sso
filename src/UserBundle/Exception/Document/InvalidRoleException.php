<?php

namespace UserBundle\Exception\Document;

class InvalidRoleException extends \InvalidArgumentException implements DocumentException
{
    public function __construct(String $role)
    {
        parent::__construct('Invalid role "' . $role . '".');
    }
}