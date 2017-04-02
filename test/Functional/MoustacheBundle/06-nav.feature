@Content
Feature: Ability to see the navbar

    Scenario: As anonymous, I see an branded navbar
        Given I am on "/"
        Then I should be on "/login"
        Then I should see a ".navbar" element
        Then I should not see a ".navbar form[name='torrent_menu']" element
        When I follow "moustache logo"

    Scenario Outline: As Moustachor, I see the navbar and I can return to home by clicking the logo
        Given I am authenticated as "normal" with password "test"
        Given I am on "<uri>"
        Then I should see a ".navbar" element
        Then I should see a ".navbar form[name='torrent_menu']" element
        Then I should see a ".navbar form[name='torrent_menu'] input[type='file']" element
        Then I should see a ".navbar form[name='torrent_menu'] input[type='text']" element
        Then I should see a ".navbar form[name='torrent_menu'] button" element
        When I follow "moustache logo"
        Then I should be on "/"
    Examples:
        | uri                          |
        | /                            |
        | /content/1                   |
