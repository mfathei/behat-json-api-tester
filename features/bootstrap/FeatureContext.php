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
        }

        $this->apiContext->requestPath("/album", "POST");
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
        $db = 'basic_api';
        $port = 3306;
        $user = 'devuser';
        $pass = 'P@ssw0rd';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbnae=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRORMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, $user, $pass, $opt);

        $pdo->query('TRUNCATE album');
    }
}
