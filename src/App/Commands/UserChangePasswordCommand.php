<?php
namespace ASPTest\App\Commands;

use ASPTest\App\Repositories\UserRepository;
use ASPTest\App\Utils\ValidationUtils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class UserChangePasswordCommand extends Command
{
    /**
     * @var UserRepository $userRepository.
     * User repository to manage database persistences.
     */
    private UserRepository $userRepository;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('ASP-TEST USER:CREATE-PWD')
            ->setDescription('Command to create a password for previously registered users.')
            ->addArgument('id', InputArgument::REQUIRED, 'Pass the username.')
            ->addArgument('password', InputArgument::REQUIRED, 'Pass the surname.')
            ->addArgument('passwordConfirmation', InputArgument::REQUIRED, 'Pass the e-mail.');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->userRepository->find($input->getArgument('id'));
        $password = $input->getArgument('password');
        $passwordConfirmation = $input->getArgument('passwordConfirmation');

        $validator = new ValidationUtils();

        $validator->name('password')
            ->value($password)
            ->pattern('password');

        $validator->name('passwordConfirmation')
            ->value($passwordConfirmation)
            ->pattern('password');

        if (!$validator->isSuccess() || $password != $passwordConfirmation){
            throw new \Exception('The informed password is not valid.');
        }

        $this->userRepository->updatePassword($user, $password);

        return Command::SUCCESS;
    }

}
