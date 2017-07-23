@Scenario
@Download
Feature: Ability to download a torrent

    Background:
        Given I am authenticated as "normal" with password "test"

    Scenario: As Moustachor, I cannot see a download button for directories
        When I am on "/"
        Then I should not see a "#torrent-1 .card-header div .btn-group a[title='Direct download']" element

    Scenario: As Moustachor, I cannot see a download button for unfinished files
        When I am on "/"
        Then I should not see a "#torrent-3 .card-header div .btn-group a[title='Direct download']" element

    Scenario: As Moustachor, I see a download button for finished files
        When I am on "/"
        Then I should see a "#torrent-8 .card-header div .btn-group a[title='Direct download']" element
        Then I should see a "#torrent-12 .card-header div .btn-group a[title='Direct download']" element

    Scenario: As Moustachor, I see an error if I try to download a directory directly
        When I go to "/ddl/3"
        Then the response status code should be 400
        Then I should see "torrent cannot be downloaded"

        # @HEYLISTEN Test that script is not downloadable