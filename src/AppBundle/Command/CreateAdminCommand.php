<?php

namespace AppBundle\Command;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UserBundle\Command\CreateAdminCommand as BaseCreateAdminCommand;

class CreateAdminCommand extends BaseCreateAdminCommand
{

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->userRepository = $this->getContainer()->get('user_repository');
        $this->parentExecute($input, $output, 'Stalpłot', 'Przemysłowa', '42-714', 'pl');
    }
}