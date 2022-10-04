<?php
namespace App\Tests\Behat;

use App\DataFixtures\AppFixtures;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Coduo\PHPMatcher\PHPMatcher;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Elastica\Request;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;


use PHPUnit\Framework\Assert;

//use PHPUnit\Framework\Assert as Assertions;

Class PrestationContext implements Context {

    const URL = "http://localhost:8741";
    /**
     *  @var App\DataFixtures\AppFixtures
    */
    private $fixtures;
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;
    /**
     * @var Client
     */
    public $client;
    private $headers;
    private $action;
    private $body;
    /** @var Response  */
    private $response;
    /** @var PHPMatcher */
    private $matcher;
    public function __construct(AppFixtures $fixtures, \Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->fixtures = $fixtures;
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => self::URL,
            "verify" => false
            // You can set any number of default request options.

        ]);
        $this->matcher = new PHPMatcher();
    }
    /**
     * @Given I am authenticated as :arg1
     */
    public function iAmAuthenticatedAs($arg1)
    {
        $res = $this->client->request("POST", "/api/login_check", [
            RequestOptions::JSON => json_decode('{
                "username": "admin",
                "password": "Secret123#"
            }', true),
            RequestOptions::HEADERS => [
                "Content-Type" => "application/ld+json",
                "Accept"=> "application/ld+json"
            ]
        ]);
        $body = $res->getBody();
        $json = json_decode($body->getContents(), true);
        //var_dump($json["token"]);
      
       // Assertions::assertTrue(isset($json["token"]), "token is present");
        $this->headers["Authorization"] = "Bearer ". $json["token"];

    }
    /**
     * @When I add :name header equal to :value
     */
    public function iAddHeaderEqualTo($name, $value)
    {
        $this->headers[$name] = $value;
    }
    /**
     * @When I send :arg1 request to :arg2 with body:
     */
    public function iSendRequestToWithBody($arg1, $arg2, $body)
    {

            $this->response = $this->client->request($arg1, $arg2, [
                RequestOptions::JSON => json_decode($body, true),
                RequestOptions::HEADERS => $this->headers
            ]);
    }
    /**
     * @Given I send :arg1 request to :arg2
     */
    public function iSendRequestTo($arg1, $arg2)
    {
        $this->response = $this->client->request($arg1, $arg2, [
            RequestOptions::HEADERS => $this->headers
        ]);
    }
    /**
     * @When I wait :seconds seconds
     */
    public function iWaitSeconds($seconds)
    {
        sleep($seconds);
    }
    /**
     * @Then the JSON matches expected template:
     */
    public function theJsonMatchesExpectedTemplate(PyStringNode $json)
    {
       
        $body = (string) $this->response->getBody();
        var_dump($body);
         Assert::assertTrue($this->matcher->match($body,
             json_encode(json_decode($json))
        ));
 
    }
    /**
     * @Then the response code should be :arg1
     */
    public function theResponseCodeShouldBe($code)
    {
       return  $this->response->getStatusCode() === $code;
    }
    /**
     * @BeforeScenario @createSchema
     */
    public function createSchema() 
    {
        $classes = $this->em->getMetadataFactory()->getAllMetadata();

        $schemaTools = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $schemaTools->dropSchema($classes);
        $schemaTools->createSchema($classes);

        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
        $fixtureExecutor = new ORMExecutor($this->em, $purger);
        $fixtureExecutor->execute([$this->fixtures]);
    }

}
