@Error
@405
Feature: Ability to see error page 405

    Scenario Outline: As Moustachor, I get a method not allowed error when I use wrong HTTP method
        Given I go to "<uri>"
        Then the response status code should be 405
        And I should see "405 Method not allowed"
        And I should see "This page is only accessible with POST"
        When I follow "Take me home"
        Then I should be on "/login"
    Examples:
        | uri                          |
        | /signup/abc                  |
