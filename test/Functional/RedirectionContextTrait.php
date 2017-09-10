<?php

declare(strict_types=1);
use Behat\Behat\Hook\Scope\AfterScenarioScope;

trait RedirectionContextTrait
{
    /**
     * @AfterScenario
     *
     * @param AfterScenarioScope $scope
     */
    public function afterScenario(AfterScenarioScope $scope)
    {
        $client = $this->getSession()->getDriver()->getClient();
        $client->followRedirects(true);
    }

    /**
     * @Given /^I don(?:'|’)t follow redirection$/
     * @When /^I should not follow redirection$/
     */
    public function iDontFollowTheRedirections()
    {
        $this->getSession()->getDriver()->getClient()->followRedirects(false);
    }

    /**
     * @When /^I follow the redirection$/
     * @Then /^I should be redirected$/
     */
    public function iFollowTheRedirections()
    {
        $client = $this->getSession()->getDriver()->getClient();
        $client->followRedirects(true);
        $client->followRedirect();
    }

    /**
     * @When /^I follow just one redirection$/
     * @Then /^I am redirected once$/
     */
    public function iFollowTheRedirection()
    {
        $client = $this->getSession()->getDriver()->getClient();
        $client->followRedirect();
    }
}
