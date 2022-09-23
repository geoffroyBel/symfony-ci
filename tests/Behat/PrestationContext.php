<?php
namespace App\Tests\Behat;

use App\DataFixtures\AppFixtures;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Elastica\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;

Class PrestationContext implements Context {

    const URL = "http://127.0.0.1:8741";
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