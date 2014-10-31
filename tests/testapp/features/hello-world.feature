Feature: Hello World
  In order to ensure the extension works
  As an automated test robot
  I need to have a simple hello world test-suite

  Scenario: The webserver should return hello world
    When I open "hello-world.php"
    Then I should see "Hello, World!"