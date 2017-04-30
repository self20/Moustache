@Scenario
@Share
Feature: Ability to share (download/upload) a torrent

    Background:
        Given I am authenticated as "normal" with password "test"

    Scenario: As Moustachor, I pause a torrent in the list
        When follow "Pause"
        Then the response status code should be 200
        Then I should see " has been suspended." in the "#content .alert" element
        Then I should not see a ".torrent-section div div:nth-of-type(1) div .card-header div a[title=Start].disabled" element
        Then I should see a ".torrent-section div div:nth-of-type(1) div .card-header div a[title=Pause].disabled" element
        Then I should see a ".torrent-section div div:nth-of-type(1) div.card-inverse" element

    Scenario: As Moustachor, I start a torrent in the list
        When follow "Start"
        Then the response status code should be 200
        Then I should see " has been started." in the "#content .alert" element
        Then I should see a ".torrent-section div div:nth-of-type(1) div .card-header div a[title=Start].disabled" element
        Then I should not see a ".torrent-section div div:nth-of-type(1) div .card-header div a[title=Pause].disabled" element
        Then I should not see a ".torrent-section div div:nth-of-type(1) div.card-inverse" element
