<?php

use Behat\Behat\Hook\Scope\AfterFeatureScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Symfony\Component\BrowserKit\Cookie;

trait AuthenticationContextTrait
{
    static public $authCookies;

    static public $mustLogout = false;

    /**
     * @Given /^I(?:’m|'m| am) authenticated as "(?P<username>[^"]+)" with password "(?P<password>[^"]+)"$/
     *
     * @param string $username
     * @param string $password
     */
    public function iAmAuthenticatedAs($username, $password)
    {
        $client = $this->getSession()->getDriver()->getClient();

        $cookieString = $this->login($username, $password);
        self::$authCookies['auth'] = Cookie::fromString($cookieString);

        $client->getCookieJar()->set(self::$authCookies['auth']);
    }

    /**
     * @Then /^I save the response cookies$/
     *
     * @param string $username
     * @param string $password
     */
    public function iSaveTheReturnedCookie()
    {
        $client = $this->getSession()->getDriver()->getClient();

        self::$authCookies['auth'] = $client->getCookieJar()->get('PHPSESSID');
    }

    /**
     * @Then /^I show the cookies$/
     *
     * @param string $username
     * @param string $password
     */
    public function iShowTheCookies()
    {
        $client = $this->getSession()->getDriver()->getClient();

        dump($client->getCookieJar());
        dump(self::$authCookies);
    }

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function autoKeepCookies(BeforeScenarioScope $scope)
    {
        if (!empty(self::$authCookies['auth'])) {
            $client = $this->getSession()->getDriver()->getClient();
            $client->getCookieJar()->set(self::$authCookies['auth']);
        }
    }

    /**
     * @AfterScenario
     *
     * @param AfterScenarioScope $scope
     */
    public function autoSaveResponseCookies(AfterScenarioScope $scope)
    {
        if ($scope->getScenario()->hasTag('saveCookies')) {
            $this->iSaveTheReturnedCookie();
        }
    }

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function autoCleanClientCookies(BeforeScenarioScope $scope)
    {
        if (self::$mustLogout) {
            $client = $this->getSession()->getDriver()->getClient();

            $this->visit('/logout');
            $client->restart();
            self::$authCookies = null;

            self::$mustLogout = false;
        }
    }

    /**
     * @AfterFeature
     *
     * @param AfterFeatureScope $scope
     */
    public static function teardownCookies(AfterFeatureScope $scope)
    {
        self::$authCookies = null;
        self::$mustLogout = true;
    }

    protected function login(string $username, string $password)
    {
        $this->visit('/login');
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('_submit');

        return $this->getSession()->getResponseHeader('Set-Cookie');
    }
}
