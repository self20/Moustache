<?php

declare(strict_types=1);

namespace MoustacheBundle\Command;

use MoustacheBundle\Task\CleanDownloadsTask;
use MoustacheBundle\Task\TaskInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanDownloadsCommand extends SymfonyCommand
{
    /**
     * @var TaskInterface
     */
    private $cleanDownloadsTask;

    /**
     * @param TaskInterface $cleanDownloadsTask
     */
    public function __construct(TaskInterface $cleanDownloadsTask)
    {
        $this->cleanDownloadsTask = $cleanDownloadsTask;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('moustache:clean:downloads')
            ->setDescription(sprintf('Clean the download directory by removing files that weren’t accessed for %s seconds.', CleanDownloadsTask::SECONDS_UNTIL_REMOVAL))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cleanDownloadsTask->setup();
        $this->cleanDownloadsTask->run();

        if ($output->isVerbose()) {
            foreach ($this->cleanDownloadsTask->teardown() as $removeFile) {
                $output->writeln(sprintf('Public symlink %s was removed.', $removeFile));
            }
        }
    }
}
