<?php
namespace ASPTest\App\Commands;

use ASPTest\App\Models\User;
use ASPTest\App\Repositories\UserRepository;
use ASPTest\App\Utils\ValidationUtils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class UserCreateCommand extends Command
{
    /**
     * @var UserRepository $userRepository.
     * User repository to manage database persistence.
     */
    private UserRepository $userRepository;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('ASP-TEST USER:CREATE')
            ->setDescription('Command for creating users through information of a name, surname, e-mail and age. being the age optional.')
            ->addArgument('name', InputArgument::REQUIRED, 'Pass the username.')
            ->addArgument('surname', InputArgument::REQUIRED, 'Pass the surname.')
            ->addArgument('email', InputArgument::REQUIRED, 'Pass the e-mail.')
            ->addArgument('age', InputArgument::OPTIONAL, 'Pass the age.');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $validator = new ValidationUtils();

        $validator->name('name')
            ->value($input->getArgument('name'))
            ->min(2)
            ->max(35)
            ->pattern('name');

        $validator->name('surname')
            ->value($input->getArgument('surname'))
            ->min(2)
            ->max(35)
            ->pattern('name');

        $validator
            ->name('email')
            ->value($input->getArgument('email'))
            ->pattern('email');

        $age = $input->getArgument('age');
        if ($age != '') {
            $validator->name('age')->value($age)->min(1)->max(150)->pattern('int');
        }

        if (!$validator->isSuccess()){
            $output->writeln('There is some errors on the informed parameters:');
            $output->writeln('');
            foreach ($validator->getErrors() as $error) {
                $output->writeln($error);
            }
            die;
        }

        $this->userRepository = new UserRepository();

    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $user = new User();
        $user->name = $input->getArgument('name');
        $user->surname = $input->getArgument('surname');
        $user->email = $input->getArgument('email');
        $user->age = $input->getArgument('age') ?? null;

        $user = $this->userRepository->create($user);

        if (!$user instanceof User) {
            return Command::INVALID;
        }

        $output->writeln(json_encode([
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
            'age' => $user->age,
        ]));
        return Command::SUCCESS;
    }

}
