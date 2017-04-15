<?php

namespace MoustacheBundle\Command;

use MoustacheBundle\Exception\Permission\SystemPermissionException;
use MoustacheBundle\Task\SymlinkParametersTask;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends SymfonyCommand
{
    const NAME = 'moustache:install';

    /**
     * @var SymlinkParametersTask
     */
    private $symlinkParametersTask;

    /**
     * @param SymlinkParametersTask $symlinkParametersTask
     */
    public function __construct(SymlinkParametersTask $symlinkParametersTask)
    {
        $this->symlinkParametersTask = $symlinkParametersTask;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Install Moustache.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->symlinkParameter($output);
    }

    private function symlinkParameter(OutputInterface $output)
    {
        try {
            $this->symlinkParametersTask->run();
        } catch (SystemPermissionException $ex) {
            $output->writeln('<warning>'.$ex->getMessage().'</warning>');
        }
    }
}
