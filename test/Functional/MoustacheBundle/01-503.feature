@Error
@503
Feature: Ability to see error page 503 maintenance page

    Scenario Outline: As Moustachor, I get a maintenance error when the token file is set
        Given The maintenance lock is present
        When I send a GET request to "<uri>"
        Then the response status code should be 503
        And I should see "The servers are currently turning into better, faster, stronger servers"
        And I should see "Take a break, smoke a pipe and reload the page"
    Examples:
        | uri                          |
        | /                            |
        | /content/1                   |

    Scenario Outline: As Moustachor, I get a maintenance error when the token file is set
        Given The maintenance lock is absent
        Given I am authenticated as "normal" with password "test"
        Given The maintenance lock is present
        When I go to "<uri>"
        Then the response status code should be 503
        And I should see "The servers are currently turning into better, faster, stronger servers"
        And I should see "Take a break, smoke a pipe and reload the page"
    Examples:
        | uri                          |
        | /                            |
        | /login                       |
        | /content/1                   |
        | /ddl/1                       |
        | /start/1                     |

    Scenario: As Moustachor, I can access the app when maintenance mode is over
        Given The maintenance lock is absent
        When I go to "/"
        Then the response status code should be 200
