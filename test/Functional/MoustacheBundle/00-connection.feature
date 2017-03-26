@Connection
Feature: Ability to reach the site

    Scenario: As Moustachor, I reach the application
        Given I go to "/"
        Then the response status code should be 200

    Scenario: As Moustachor, I get a not found error when I hit an unknown page
        Given I go to "/some/improbable/url"
        Then the response status code should be 404
