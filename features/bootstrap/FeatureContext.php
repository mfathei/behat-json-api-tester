<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given there are Albums with the following details:
     */
    public function thereAreAlbumsWithTheFollowingDetails(TableNode $albums)
    {
        foreach ($albums->getColumnsHash() as $album) {
            $this->apiContext->setRequestBody(json_encode($album));

            $this->apiContext->requestPath("/album", "POST");
        }
    }

    /**
     * @BeforeScenario
     */
    public function gatherContext(
        \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
    ) {
        $this->apiContext = $scope->getEnvironment()->getContext(
            Imbo\BehatApiExtension\Context\ApiContext::class
        );
    }

    /**
     * @BeforeScenario
     */
    public function cleanUpDatabase()
    {
        $host = '127.0.0.1';
        // $db = 'basic_api';// for Symfony4-json-api project
        $db = 'fos_rest_api';// for Symfony4-fos-rest-api project
        $port = 13306;
        $user = 'root';
        $pass = 'P@ssw0rd';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, $user, $pass, $opt);

        $pdo->query('TRUNCATE album');
    }
}
