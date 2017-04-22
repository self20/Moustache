@Error
@404
Feature: Ability to see error page 404

    Scenario Outline: As a guest, I get a not found error when I hit an unknown page
        When I go to "<uri>"
        Then the response status code should be 404
        And I should see "404 Not Found"
        And I should see "you request a page that "
        When I follow "Take me home"
        Then I should be on "/login"
    Examples:
        | uri                          |
        | /some/improbable/url         |
        | /admin                       |
        | /signup                      |

    Scenario Outline: As Moustachor, I get a not found error when I hit an unknown page or not allowed torrent
        Given I am authenticated as "normal" with password "test"
        When I go to "<uri>"
        Then the response status code should be 404
        And I should see "404 Not Found"
        And I should see "you request a page that "
        When I follow "Take me home"
        Then I should be on "/"
    Examples:
        | uri                          |
        | /status/2                    |
        | /status/25                   |
        | /content/2                   |
        | /content/25                  |
        | /some/improbable/url         |
        | /admin                       |
        | /signup                      |