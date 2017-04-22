@Error
@403
Feature: Rejection of unwanted user 403

    Scenario: As a hak3r, I cannot see the signup form with an invalid token.
        When I go to "/signup/form/abc"
        Then the response status code should be 403
        And I should see "403 Forbidden"
        And I should see "This link has expired or is invalid"
        When I follow "Take me home"
        Then I should be on "/login"

# @HEYLISTEN This must display a more friendly error
    Scenario: As Moustachor, I cannot access the signup page.
        Given I am authenticated as "normal" with password "test"
        When I go to "/signup/form/01234567abcdef"
        Then the response status code should be 403
        And I should see "403 Forbidden"
        And I should see "Access Denied"
        When I follow "Take me home"
        Then I should be on "/"
