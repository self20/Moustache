@Content
@List
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

    Scenario: As Moustachor I see the progress of my torrents
        Then I should see "100%" in the "#torrent-progress-1" element
        Then I should see "0.01%" in the "#torrent-progress-4" element
        Then I should see "0%" in the "#torrent-progress-5" element
        Then I should see "61.15%" in the "#torrent-progress-6" element

    Scenario: As Moustachor I see the upload/download rate of my torrents
        Then I should see "5.42KB" in the "#torrent-down-value-4" element
        Then I should see "112KB" in the "#torrent-down-value-6" element
        Then I should see "534B" in the "#torrent-up-value-10" element
