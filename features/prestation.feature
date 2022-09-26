Feature:
    In order to create a prestation
    As user
    I want to have a demo scenario
 @createSchema
 Scenario: Create a prestation
  When I add "Content-Type" header equal to "application/ld+json"
  And I add "Accept" header equal to "application/ld+json"
  And I add "Authorization" header equal to "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NjM5MzU5NTUsImV4cCI6MTY2MzkzOTU1NSwicm9sZXMiOlsiUk9MRV9TVVBFUkFETUlOIiwiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiYWRtaW4ifQ.VZ5mASCmYnhvuSV6it8Wdpe9tPFfoYtTv55Yt7GbMcOrACODlZzh9b0wXR7uh04RIHi63rNYnKwPdVyfGEoFCFQfaGKh6inqiv1t7KLQq68xsLdwhfSZKaK475DIHFTrZWbJ-WFK6fLUq50GthFrrEUq474ulty_7SArPsoBa0k-ULQU87j2hx8etLMTXRV3U0qt5APLEDygjaGuV2PlzPfJKTMsT-VOXnYlRs8A2DoVM3lKnf0k2PurQ9mWE_4AfjiaxY4ZhzTjNdxn9ZwEK25P5Q_-2JSK_pv0K3ClbNePgdLIE_AbZcLTzL5eW9PJht-rEFysPlJzb0xyDitGKQ"
  And I send "Post" request to "/api/prestations" with body:
  """
  {
    "name": "gros jammmm behat"
  }
  """
  Then the response code should be 201
