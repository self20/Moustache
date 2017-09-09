@Scenario
@Add
Feature: Ability to add a new torrent

    Background:
        Given I am authenticated as "normal" with password "test"

    Scenario: As Moustachor, I see one card per torrent I own
        When I am on "/"
        And I should see 9 ".card" elements

    Scenario: As Moustachor, I cannot upload with an empty form
        When I press "Add torrent!"
        Then I should see "Please upload a valid torrent file or type a torrent URL/magnet" in the "#content .alert" element
        And I should see 9 ".card" elements

    Scenario: As Moustachor, I cannot download a big torrent if there is not enough space
        When I attach the file "big.torrent" to "torrent_menu[uploadedFile]"
        And I press "Add torrent!"
        Then I should see "The torrent has been added but it cannot be started" in the "#content .alert" element
        Then I should see "needed: 80TB" in the "#content .alert" element

    Scenario: As Moustachor, I see that my the big torrent has been stopped
        Then I should not see a "#torrent-13 .card-header div a[title=Start].disabled" element
        Then I should see a "#torrent-13 .card-header div a[title=Pause].disabled" element

    Scenario: As Moustachor, I cannot start such a big torrent without freeing some space
        When I go to "/start/13"
        Then I should see "The torrent cannot be started" in the "#content .alert" element
        Then I should see "needed: 80TB" in the "#content .alert" element
        Then I should not see a "#torrent-13 .card-header div a[title=Start].disabled" element
        Then I should see a "#torrent-13 .card-header div a[title=Pause].disabled" element

    Scenario: As Moustachor, I upload a file using the file input in the menu form
        When I attach the file "fake.torrent" to "torrent_menu[uploadedFile]"
        And I press "Add torrent!"
        Then I should not see a "#content .alert" element

    Scenario: As Moustachor, I see my newly uploaded torrent in the list
        And I should see 11 ".card" elements
        And I should see "torrent-14"

    Scenario: As Moustachor, as I reload the page, I always see my new torrent
        When I reload the page
        And I should see 11 ".card" elements
        And I should see "torrent-14"

    Scenario: As Moustachor, I cannot upload an empty file
        When I attach the file "fake_invalid.torrent" to "torrent_menu[uploadedFile]"
        And I press "Add torrent!"
        Then I should see "Your file was not recognize as a .torrent file" in the "#content .alert" element
        And I should see 11 ".card" elements

    Scenario: As Moustachor, I cannot upload a torrent with a fancy URL
        When I fill in "torrent_menu[uploadedFileByUrl]" with "this:isnot/an/url"
        And I press "Add torrent!"
        Then I should see "Please upload a valid torrent file or type a torrent URL/magnet" in the "#content .alert" element
        And I should see 11 ".card" elements

    Scenario: As Moustachor, I upload a magnet link
        Then I fill in "torrent_menu[uploadedFileByUrl]" with "magnet:?xt=urn:btih:14eac61a533282b4ac3d8fcd62086e78608ec235&dn=file"
        And I press "Add torrent!"
        Then I should not see a "#content .alert" element

    Scenario: As Moustachor, I see my newly uploaded torrent in the list
        And I should see 12 ".card" elements
        And I should see "torrent-15"
