@Connection
Feature: Ability to reach the site

    Scenario: As Moustachor, I reach the application
        Given I go to "/"
        Then the response status code should be 200
