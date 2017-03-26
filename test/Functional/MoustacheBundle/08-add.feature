@Scenario
Feature: Ability to add a new torrent

    Background:
        Given I am authenticated as "normal" with password "test"

    Scenario: As Moustachor, I see one card per torrent I own
        Given I am on "/"
        And I should see 9 ".card" elements

    Scenario: As Moustachor, I upload a file using the file input in the menu form
        When I attach the file "fake.torrent" to "torrent_menu[uploadedFile]"
        And I press "Add torrent!"
        Then I should not see a "#content .alert" element

    Scenario: As Moustachor, I see my newly uploaded torrent in the list
        And I should see 10 ".card" elements
        And I should see "torrent-13"

    Scenario: As Moustachor, as I reload the page, I always see my new torrent
        When I reload the page
        And I should see 10 ".card" elements
        And I should see "torrent-13"

#    Scenario: As Moustachor, I upload a torrent magnet using the text input in the menu form
#    Scenario: As Moustachor, I see my newly uploaded torrent in the list
#    Scenario: As Moustachor, as I reload the page, I always see my new torrent
