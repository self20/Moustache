<?php

declare(strict_types=1);

namespace MoustacheBundle\Task;

use MoustacheBundle\Exception\Permission\SystemPermissionException;
use Symfony\Component\Filesystem\Filesystem;

class SymlinkParametersTask implements TaskInterface
{
    const SYSTEM_CONFIG_DIR = '/etc';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @param Filesystem $filesystem
     * @param string     $rootDirectory
     */
    public function __construct(Filesystem $filesystem, string $rootDirectory)
    {
        $this->filesystem = $filesystem;
        $this->rootDirectory = $rootDirectory;
    }

    /**
     * {@inheritdoc}
     *
     * @throws SystemPermissionException
     */
    public function run(): int
    {
        if (file_exists(self::SYSTEM_CONFIG_DIR.'/moustache.yml')) {
            return 0;
        }

        if (!is_writable(self::SYSTEM_CONFIG_DIR)) {
            throw new SystemPermissionException(
                sprintf('“%s” directory is not writable. Do “ln -s %s %s” manually.', self::SYSTEM_CONFIG_DIR, $this->rootDirectory.'/config/parameters.yml', self::SYSTEM_CONFIG_DIR.'/moustache.yml')
            );
        }

        $this->filesystem->symlink($this->rootDirectory.'/config/parameters.yml', self::SYSTEM_CONFIG_DIR.'/moustache.yml');
        $this->filesystem->chown(self::SYSTEM_CONFIG_DIR.'/moustache.yml', 'root');
        $this->filesystem->chgrp(self::SYSTEM_CONFIG_DIR.'/moustache.yml', 'root');

        return 0;
    }

    public function setup()
    {
    }

    public function teardown()
    {
        if (!is_writable(self::SYSTEM_CONFIG_DIR)) {
            throw new SystemPermissionException(
                sprintf('Permission error when deleting a file. Do “rm %s” manually.', self::SYSTEM_CONFIG_DIR, self::SYSTEM_CONFIG_DIR.'/moustache.yml')
            );
        }

        $this->filesystem->remove(self::SYSTEM_CONFIG_DIR.'/moustache.yml');
    }
}
