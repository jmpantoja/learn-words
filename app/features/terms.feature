# features/books.feature
Feature: Books feature

  Scenario: Adding a new book
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/terms" with body:
    """
    {
      "word": {
        "lang": "SPANISH",
        "word": "xxx"
      }
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON nodes should contain:
      | word.lang | es |
      | word.word | xxx |

  Scenario: Adding a new book
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/terms" with body:
    """
    {
      "word": {
        "lang": "SPANISH",
        "word": "xxx"
      }
    }
    """

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/terms" with body:
    """
    {
      "word": {
        "lang": "SPANISH",
        "word": "xxx"
      }
    }
    """
    Then the response status code should be 500
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    And the JSON nodes should contain:
      | title | An error occurred |
