Feature:
    In order to create a prestation
    As user
    I want to have a demo scenario
 
 Scenario: Create a prestation
  When I am authenticated as "admi"
  And I add "Content-Type" header equal to "application/ld+json"
  And I add "Accept" header equal to "application/ld+json"
  And I send "Post" request to "/api/prestations" with body:
  """
  {
    "name": "gros jammmm behat"
  }
  """
  Then the response code should be 201
