@Message
Feature: Ability to see messages

    Scenario: As Moustachor, I am greeted when I first connect to the site
        Given I am authenticated as "normal" with password "test"
        Then the response status code should be 200
        And I should be on "/"
        And I should see "Hi folks!" in the "#content .alert" element
        And I should see "Here you can upload your .torrent files" in the "#content .alert" element

    Scenario: As Moustachor, I do not see the greeting anymore once I reload the page
        When I go to "/"
        Then the response status code should be 200
        And I should be on "/"
        Then I should not see a "#content .alert" element
