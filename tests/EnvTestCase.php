<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class EnvTestCase extends TestCase
{
    protected static $env;

    public static function setUpBeforeClass(): void
    {
        self::$env = array_merge($_ENV, parse_ini_file(__DIR__.'/../.env'));
    }

    public static function tearDownAfterClass(): void
    {
        self::$env = null;
    }
}
