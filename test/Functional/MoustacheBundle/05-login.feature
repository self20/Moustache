@Authentication
@Login
Feature: Ability to login

    Scenario Outline: As Moustachor, I am redirected to login page when I am not authenticated
        Given I don't follow redirection
        Given I go to "<uri>"
        Then the response status code should be 302
        When I follow the redirection
        Then the response status code should be 200
        And I should be on "/login"
    Examples:
        | uri                          |
        | /content/1                   |
        | /                            |

    Scenario: As Moustachor, I see the login form
        Given I am on "/login"
        Then I should see a "input#username" element
        And I should see a "input#password" element
        And I should see a "#_submit" element

    Scenario: As Moustachor, I fail to log in once with a typo in my username
        Given I am on "/login"
        Then I fill in "username" with "narmol"
        Then I fill in "password" with "test"
        When I press "_submit"
        Then I should be on "/login"
        Then I should see "Invalid credentials"

    Scenario: As Moustachor, I fail to log in once with a wrong password
        Given I am on "/login"
        Then I fill in "username" with "normal"
        Then I fill in "password" with "badpass"
        When I press "_submit"
        Then I should be on "/login"
        Then I should see "Invalid credentials"

    @saveCookies
    Scenario: As Moustachor, I log in
        Given I am on "/login"
        Then I fill in "username" with "normal"
        Then I fill in "password" with "test"
        When I press "_submit"
        Then the response status code should be 200
        Then I should be on "/"
        Then I should not see "Invalid credentials"

    Scenario: As moustachor, I cannot see the login page again
        When I go to "/login"
        Then the response status code should be 403
