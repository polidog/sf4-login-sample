<?php

declare(strict_types=1);

namespace App\Command;

use App\Form\CreateAccountType;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\UseCase\RegisterAccount;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Form\FormFactoryInterface;

class CreateAccountCommand extends Command
{
    /**
     * @var RegisterAccount
     */
    private $registerAccount;

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * CreateAccountCommand constructor.
     *
     * @param RegisterAccount $registerAccount
     */
    public function __construct(RegisterAccount $registerAccount, FormFactoryInterface $factory)
    {
        parent::__construct(null);
        $this->registerAccount = $registerAccount;
        $this->factory = $factory;
    }

    protected function configure(): void
    {
        $this->setName('app:account:create');
        $this->setDescription('Create a new account.');
        $this->addArgument('name', InputArgument::REQUIRED, 'account name');
        $this->addArgument('email', InputArgument::REQUIRED, 'account email address');
        $this->addArgument('password', InputArgument::REQUIRED, 'account password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $form = $this->factory->create(CreateAccountType::class);
        $form->submit([
            'name' => $input->getArgument('name'),
            'email' => $input->getArgument('email'),
            'rawPassword' => [
                'first' => $input->getArgument('password'),
                'second' => $input->getArgument('password'),
            ],
        ]);

        $this->registerAccount->run($form->getData());
        $output->writeln('success');
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->ask('name', $input, $output);
        $this->ask('email', $input, $output);
        $this->ask('password', $input, $output);
    }

    protected function ask(string $name, InputInterface $input, OutputInterface $output): void
    {
        if (!$input->getArgument($name)) {
            $helper = $this->getHelper('question');
            $question = new Question('Please enter your '.$name.': ');
            $question->setValidator(function ($value) {
                if ('' == trim($value)) {
                    throw new \Exception('Cannot be empty');
                }

                return $value;
            });

            $value = $helper->ask($input, $output, $question);
            $input->setArgument($name, $value);
        }
    }
}
