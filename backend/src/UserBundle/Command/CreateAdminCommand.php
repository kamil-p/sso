<?php

namespace UserBundle\Command;

use AppBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\QuestionHelper;
use UserBundle\Document\Address;
use UserBundle\Repository\UserRepository;


abstract class CreateAdminCommand extends ContainerAwareCommand
{
    /* @var InputInterface */
    protected $input;

    /* @var OutputInterface */
    protected $output;

    /* @var QuestionHelper */
    protected $questionHelper;

    /* @var UserRepository */
    protected $userRepository;

    const ADMIN_EMAIL_QUESTION = 'Please provide admin email address:';
    const ADMIN_PASSWORD_QUESTION = 'Please provide admin password (min 5 characters):';
    const ADMIN_PASSWORD_PHONE_NUMBER_QUESTION = 'Please provide admin phone number (between 9 and 20 characters):';

    const OPTION_EMAIL = 'email';
    const OPTION_PASSWORD = 'password';
    const OPTION_PHONE_NUMBER = 'password';

    protected function configure()
    {
        $this->setName('create:admin')
            ->setDescription('Creates admin inside application')
            ->setDescription([
                new InputOption(self::OPTION_EMAIL, '', InputOption::VALUE_REQUIRED, self::ADMIN_EMAIL_QUESTION),
                new InputOption(self::OPTION_PASSWORD, '', InputOption::VALUE_REQUIRED, self::ADMIN_PASSWORD_QUESTION),
                new InputOption(self::OPTION_PHONE_NUMBER, '', InputOption::VALUE_REQUIRED,
                    self::ADMIN_PASSWORD_PHONE_NUMBER_QUESTION),
            ])
            ->setHelp('This command allows you to create admin.');
    }

    protected function parentExecute(
        InputInterface $input,
        OutputInterface $output,
        String $companyName,
        String $street,
        String $postCode,
        String $countryCode
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->questionHelper = $this->getHelper('question');


        $address = new Address($companyName, $street, $postCode, $this->getPhoneNumber(), $countryCode);

        $admin = new User(
            $this->getEmail(),
            $address
        );

        $encoder = $this->getContainer()->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($admin, $this->getPassword());

        $admin->setPassword($encodedPassword);
        foreach (User::$ROLES as $role) {
            $admin->addRole($role);
        }

        $this->userRepository->save($admin);

        $this->output->writeln('<info>New admin user has been created!</info>');
    }

    protected function getEmail(): String
    {
        $question = new Question('<info>' . self::ADMIN_EMAIL_QUESTION . '</info>');

        do {
            $email = $this->questionHelper->ask($this->input, $this->output, $question);
        } while (!filter_var($email, FILTER_VALIDATE_EMAIL));

        return $email;
    }

    protected function getPassword(): string
    {
        $question = new Question('<info>' . self::ADMIN_PASSWORD_QUESTION . '</info>');

        do {
            $password = $this->questionHelper->ask($this->input, $this->output, $question);
        } while (!strlen($password) > 5);

        return $password;
    }

    protected function getPhoneNumber()
    {
        $question = new Question('<info>' . self::ADMIN_PASSWORD_PHONE_NUMBER_QUESTION . '</info>');

        do {
            $phoneNumber = $this->questionHelper->ask($this->input, $this->output, $question);
        } while (!(strlen($phoneNumber) < 20 && strlen($phoneNumber) > 9));

        return $phoneNumber;
    }

}