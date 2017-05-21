@Scenario
@Download
Feature: Ability to download a torrent

    Background:
        Given I am authenticated as "normal" with password "test"

#    Scenario: As Moustachor, I cannot see a download button for directories
#        When I am on "/"torrent-3
#        And I should see 9 ".card" elements

    Scenario: As Moustachor, I see an error if I try to download a directory directly
        When I go to "/ddl/3"
        Then the response status code should be 400
        Then I should see "torrent cannot be downloaded"
