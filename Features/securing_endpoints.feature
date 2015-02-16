Feature: CSRF protection
  In order to protect against CSRF attacks
  As a developer
  I need verify request origin via CSRF tokens

  Scenario: Sending valid token on protected route
    Given I send POST request to "/resource" with valid csrf header
    Then the response code should be 201
    And I should see "Success"

  Scenario: Sending invalid token on protected route
    Given I send POST request to "/resource" with invalid csrf header
    Then the response code should be 403

  Scenario: The form csrf token is disabled in favor of header one
    Given I send POST request to "/protected-resource" with valid csrf header
    Then the response code should be 200

  Scenario: The form csrf token is not disabled in favor of header one if its invalid
    Given I send POST request to "/protected-resource" with invalid csrf header
    Then the response code should be 403
