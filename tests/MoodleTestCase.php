<?php

namespace atipics\MoodleClient\Tests;

use atipics\MoodleClient\Connection;
use PHPUnit\Framework\TestCase;

/**
 * Class MoodleTestCase
 * @package atipics\MoodleClient\Tests
 */
class MoodleTestCase extends TestCase
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @var Connection
     */
    private $connection;

    /**
     * Setup TestCase
     */
    public function setUp()
    {
        $this->config = require('config.php');
        $this->connection = new Connection($this->config['connection']['url'], $this->config['connection']['token']);
    }

    /**
     * @return array|mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
