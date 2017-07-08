<?php

declare(strict_types=1);

namespace MoustacheBundle\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;
use TorrentBundle\Manager\UserManager;
use TorrentBundle\Repository\UserRepository;

class GenerateSignupCommand extends SymfonyCommand
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param UserRepository  $userRepository
     * @param UserManager     $userManager
     * @param RouterInterface $router
     */
    public function __construct(UserRepository $userRepository, UserManager $userManager, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->router = $router;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('moustache:generate:signup')
            ->setDescription('Generate a signup link with the given username.')
            ->addArgument('username', InputArgument::REQUIRED, 'Name of the new user to create.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->userRepository->findOneBy(['username' => $input->getArgument('username')]);

        if (!$this->shouldProcess($user)) {
            $output->writeln('<error>This user already is registred.</error>');

            return;
        }

        if (empty($user)) {
            $user = $this->userManager->create($input->getArgument('username'));
            $output->writeln('<info>A new user named “'.$input->getArgument('username').'” has been created.</info>');
        }

        $this->userManager->generateConfirmationToken($user);
        $this->userManager->flush();

        $output->writeln('<info>Give this URL to this user so they can set a password and log in:</info>');
        $output->writeln('');
        $output->writeln('<options=bold>http://change.with.your.url'.$this->router->generate('moustache_signup_form', ['confirmationToken' => $user->getConfirmationToken()]).'</>');
        $output->writeln('');
    }

    private function shouldProcess($user): bool
    {
        return
            empty($user) ||
            (
                !empty($user) &&
                !empty($user->getConfirmationToken())
            )
        ;
    }
}
