@Error
@404
Feature: Ability to see error page 404

    Scenario Outline: As Moustachor, I get a not found error when I hit an unknown page
        Given I go to "<uri>"
        Then the response status code should be 404
        And I should see "404 Not Found"
        And I should see "you request a page that "
        When I follow "Take me home"
        Then I should be on "/login"
    Examples:
        | uri                          |
        | /some/improbable/url         |
        | /admin                       |
