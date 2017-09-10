<?php

declare(strict_types=1);

namespace MoustacheBundle\Task;

use MoustacheBundle\Exception\Permission\SystemPermissionException;
use Symfony\Component\Filesystem\Filesystem;

class SymlinkParametersTask implements TaskInterface
{
    const CONF_FILE_NAME = '/moustache.yml';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @var string
     */
    private $systemConfDir;

    /**
     * @param Filesystem $filesystem
     * @param string     $rootDirectory
     * @param string     $systemConfDir
     */
    public function __construct(Filesystem $filesystem, string $rootDirectory, string $systemConfDir)
    {
        $this->filesystem = $filesystem;
        $this->rootDirectory = $rootDirectory;
        $this->systemConfDir = $systemConfDir;
    }

    /**
     * {@inheritdoc}
     */
    public function setup()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @throws SystemPermissionException
     */
    public function teardown()
    {
        $this->checkSystemConfDirIsWritable('Permission error when deleting a file. Do “rm %s” manually.', $this->systemConfDir, $this->getSystemConfFile());

        $this->filesystem->remove($this->getSystemConfFile());
    }

    /**
     * {@inheritdoc}
     *
     * @throws SystemPermissionException
     */
    public function run(): int
    {
        if (file_exists($this->getSystemConfFile())) {
            return 0;
        }

        $this->checkSystemConfDirIsWritable('“%s” directory is not writable. Do “ln -s %s %s” manually.', $this->systemConfDir, $this->rootDirectory.'/config/parameters.yml', $this->getSystemConfFile());

        $this->filesystem->symlink($this->rootDirectory.'/config/parameters.yml', $this->getSystemConfFile());

        return 0;
    }

    private function checkSystemConfDirIsWritable(string $message, ...$parameters)
    {
        if (!is_writable($this->systemConfDir)) {
            throw new SystemPermissionException(
                sprintf($message, ...$parameters)
            );
        }
    }

    private function getSystemConfFile(): string
    {
        return $this->systemConfDir.self::CONF_FILE_NAME;
    }
}
