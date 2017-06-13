<?php

declare(strict_types=1);

namespace MoustacheBundle\Composer;

use Composer\Script\Event;
use MoustacheBundle\Command\InstallCommand;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as SymfonyScriptHandler;

class ScriptHandler extends SymfonyScriptHandler
{
    /**
     * @param Event $event
     */
    public static function install(Event $event)
    {
        $consoleDir = self::getConsoleDir($event, InstallCommand::NAME);

        if (null === $consoleDir) {
            return;
        }

        static::executeCommand($event, $consoleDir, InstallCommand::NAME, self::getOptions($event)['process-timeout']);
    }
}
