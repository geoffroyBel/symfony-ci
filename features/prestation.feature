Feature:
    In order to create a prestation
    As user
    I want to have a demo scenario
 
 Scenario: Create a prestation
  When I am authenticated as "admin"
  And I add "Content-Type" header equal to "application/ld+json"
  And I add "Accept" header equal to "application/ld+json"
  And I send "Post" request to "/api/prestations" with body:
  """
  {
    "name": "ghlll"
  }
  """
  Then the response code should be 201

  Scenario: Search for prestation
  When I wait 10 seconds
  And I add "Content-Type" header equal to "application/ld+json"
  And I add "Accept" header equal to "application/ld+json"
  And I send "Get" request to "api/prestations?q=ghlll"
  Then the response code should be 200
  And the JSON matches expected template:
  """
  {
    "@context": "@string@",
    "@id": "@string@",
    "@type": "@string@",
    "hydra:member": ["@...@"],
    "hydra:totalItems": "@integer@.greaterThan(0)",
    "hydra:view": {
        "@*@": "@*@"
    },
    "hydra:search": {
        "@*@": "@*@"
    }
  }
  """
