<?php

use Behat\Behat\Hook\Scope\AfterFeatureScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

trait DatabaseContextTrait
{
    /**
     * Resets database after each tag @resetDb.
     *
     * @BeforeScenario
     *
     * @param AfterScenarioScope $scope
     */
    public function resetDatabase(BeforeScenarioScope $scope)
    {
        if ($scope->getScenario()->hasTag('resetDb')) {
            self::dropDatabase();
            self::initDatabase();
        }
    }

    /**
     * @BeforeFeature
     *
     * @param BeforeFeatureScope $scope
     */
    public static function setup(BeforeFeatureScope $scope)
    {
        self::dropDatabase();
        self::initDatabase();
    }

    /**
     * @AfterFeature
     *
     * @param AfterFeatureScope $scope
     */
    public static function teardown(AfterFeatureScope $scope)
    {
        self::dropDatabase();
    }

    /**
     * Init database with fixtures.
     */
    private static function initDatabase()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $app = new Application($kernel);
        $app->setAutoExit(false);

        self::runCommand($app, 'doctrine:database:create', ['-e' => 'test']);
        self::runCommand($app, 'doctrine:schema:create', ['-e' => 'test']);
        self::runCommand($app, 'doctrine:fixtures:load', ['-n' => true, '-e' => 'test']);
    }

    /**
     * Drop database.
     */
    private static function dropDatabase()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $app = new Application($kernel);
        $app->setAutoExit(false);

        self::runCommand($app, 'doctrine:database:drop', ['--force' => true, '-e' => 'test']);
    }

    /**
     * Run console command.
     *
     * @param Application $app
     * @param array       $command
     * @param array       $options
     *
     * @return int 0 if everything went fine, or an error code
     */
    protected static function runCommand($app, $command, $options = [])
    {
        $options['--env'] = 'test';
        $options['--quiet'] = null;
        $options['--no-interaction'] = true;
        $options = array_merge($options, ['command' => $command]);

        return $app->run(new ArrayInput($options));
    }
}
