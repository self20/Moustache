@Content
Feature: Ability to see a torrent list

    Scenario: As Admin, I see an empty list of torrents
        Given I am authenticated as "admin" with password "test"
        When I am on "/"
        Then the response status code should be 200
        And I should be on "/"

    Scenario: As Admin, I do not see a list of torrent files
        And I should not see a ".torrent-section .card" element

    Scenario: As Admin, I see a big torrent file form inside the page
        Then I should see "no torrent yet" in the ".torrent-section" element
        Then I should see "Pick .torrent" in the ".torrent-section" element
        Then I should see "Start downloading" in the ".torrent-section button" element

    Scenario: As Moustachor, I reach the torrent list page
        Given I am authenticated as "normal" with password "test"
        When I am on "/"
        Then the response status code should be 200
        And I should be on "/"

    Scenario: As Moustachor, I see a list of torrent files
        Then I should see a ".torrent-section .card" element

    Scenario: As Moustachor, I do not see a big torrent file form inside the page
        Then I should not see "no torrent yet" in the ".torrent-section" element
        Then I should not see "Pick .torrent" in the ".torrent-section" element
        Then I should not see "Start downloading" in the ".torrent-section button" element
