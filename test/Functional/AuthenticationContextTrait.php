<?php

use Symfony\Component\BrowserKit\Cookie;

trait AuthenticationContextTrait
{
    private $authCookies;

    /**
     * @Given /^I(?:’m|'m| am) authenticated as "(?P<username>[^"]+)" with password "(?P<password>[^"]+)"$/
     *
     * @param string $username
     * @param string $password
     */
    public function iAmAuthenticatedAs($username, $password)
    {
        $client = $this->getSession()->getDriver()->getClient();

        $cookieName = $this->getAuthCookieName($username, $password);
        $authCookie = $this->getAuthCookie($cookieName);

        if (empty($authCookie)) {
            $cookieString = $this->login($username, $password);
            $this->authCookies[$cookieName] = Cookie::fromString($cookieString);
        }

        $client->getCookieJar()->set($this->authCookies[$cookieName]);
    }

    private function getAuthCookieName(string $username, string $password): string
    {
        return sprintf('%s|%s', $username, $password);
    }

    private function getAuthCookie(string $cookieName)
    {
        if (isset($this->authCookies[$cookieName])) {
            return $this->authCookies[$cookieName];
        }
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
