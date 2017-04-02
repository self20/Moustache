@Error
@Signup
Feature: Rejection of unwanted user 403

    Scenario: As a hak3r, I cannot see the signup form with an invalid token.
        Given I am on "/signup/form/abc"
        Then the response status code should be 403
        And I should see "403 Forbidden"
        And I should see "This link has expired or is invalid"
        When I follow "Take me home"
        Then I should be on "/login"

# @HEYLISTEN test POST on /signup/something
