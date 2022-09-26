<?php
namespace App\Tests\Behat;

use App\DataFixtures\AppFixtures;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Elastica\Request;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use PhpParser\Node\Stmt\TryCatch;

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
        $this->headers["Authorization"] = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NjM5MzU5NTUsImV4cCI6MTY2MzkzOTU1NSwicm9sZXMiOlsiUk9MRV9TVVBFUkFETUlOIiwiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiYWRtaW4ifQ.VZ5mASCmYnhvuSV6it8Wdpe9tPFfoYtTv55Yt7GbMcOrACODlZzh9b0wXR7uh04RIHi63rNYnKwPdVyfGEoFCFQfaGKh6inqiv1t7KLQq68xsLdwhfSZKaK475DIHFTrZWbJ-WFK6fLUq50GthFrrEUq474ulty_7SArPsoBa0k-ULQU87j2hx8etLMTXRV3U0qt5APLEDygjaGuV2PlzPfJKTMsT-VOXnYlRs8A2DoVM3lKnf0k2PurQ9mWE_4AfjiaxY4ZhzTjNdxn9ZwEK25P5Q_-2JSK_pv0K3ClbNePgdLIE_AbZcLTzL5eW9PJht-rEFysPlJzb0xyDitGKQ". $json["token"];

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
