<?php

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

trait DebugContextTrait
{
    private $memoryBefore = 0;

    /**
     * Saves the total memory usage before a scenario.
     *
     * @BeforeScenario
     */
    public function getMemoryUsageBefore(BeforeScenarioScope $scope)
    {
        if ($scope->getSuite()->hasSetting('memoryDump')) {
            $this->memoryBefore = memory_get_usage(true);
        }
    }

    /**
     * Displays the consumed memory of the current scenario.
     *
     * @AfterScenario
     */
    public function getMemoryUsageAfter(AfterScenarioScope $scope)
    {
        if ($scope->getSuite()->hasSetting('memoryDump')) {
            var_dump('This scenario consumed: '.((memory_get_usage(true) - $this->memoryBefore) / 1000).'ko');
            var_dump('Total PHP memory: '.((memory_get_usage(true)) / 1000).'ko');
        }
    }

    /**
     * Displays the current page.
     *
     * @AfterScenario
     */
    public function displayPage(AfterScenarioScope $scope)
    {
        if ($scope->getScenario()->hasTag('dump')) {
            var_dump($this->getSession()->getPage()->getContent());
        }
    }
}
