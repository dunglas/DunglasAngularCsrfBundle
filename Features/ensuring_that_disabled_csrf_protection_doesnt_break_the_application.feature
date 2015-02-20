Feature: Ensuring that disabled CSRF protection doesn't break the application
  In order to test functionally my application's forms
  As a developer
  I need to turn off the CSRF protection and things shouldn't break

  Scenario: Sending request on protected route when csrf is disabled
    Given I send POST request to "/resource" when csrf protection is disabled
    Then the response code should be 201

