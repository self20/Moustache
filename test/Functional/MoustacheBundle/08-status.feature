@Scenario
@Status
Feature: Ability to get the status of one or more torrents

    Background:
        Given I am authenticated as "normal" with password "test"

    Scenario: As Moustachor, I get the status of one of my torrent
        When I go to "/status/6"
        Then the response status code should be 200
        And the response should be in JSON
        Then print last response
        And the JSON nodes should be equal to:
            | id                   | 6                                           |
            | hash                 | d40470ee21ffd7d2e5f6875944a0d4166150c114    |
            | name                 | [Group]_Suite_Precure_(1920x1080_Blu-Ray)   |
            | user                 | 1                                           |
            | status               | 4                                           |
            | downloadRate         | 112000                                      |
            | downloadHumanRate    | 109KB                                       |
            | downloadedByteSize   | 43424392768                                 |
            | downloadedHumanSize  | 40.4GB                                      |
            | uploadRate           | 0                                           |
            | uploadHumanRate      | 0B                                          |
            | totalByteSize        | 71012907225                                 |
            | totalHumanSize       | 66.1GB                                      |
            | percentDone          | 61.149999999876783                          |
        And the JSON node "isDone" should be false
        And the JSON node "isStopped" should be false
        And the JSON node "isDownloading" should be true
        And the JSON node "isUploading" should be false

    Scenario: As Moustachor, I get the status of all of my torrents
        When I go to "/status"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root" should have 9 elements
