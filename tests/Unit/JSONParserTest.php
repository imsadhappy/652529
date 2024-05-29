<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utils\JSONParser;
use App\Exceptions\Parser\InvalidJSONException;
use App\Exceptions\Parser\InvalidRecordException;

class JSONParserTest extends TestCase
{
    private static ?JSONParser $parser;

    public function testAbortOnInvalidJson(): void
    {
        $this->expectException(InvalidJSONException::class);
        self::$parser->parse('foobar');
    }

    public function testAbortOnInvalidRecord(): void
    {
        $this->expectException(InvalidRecordException::class);
        self::$parser->parse('[]');
    }

    public function testAbortOnEmptyRecord(): void
    {
        $this->expectException(InvalidRecordException::class);
        self::$parser->parse('{}');
    }

    public function testParseRecords(): void
    {
        $this->assertIsObject(self::$parser->parse('{"foo":"bar"}'));
    }

    public static function setUpBeforeClass(): void
    {
        self::$parser = new JSONParser();
    }

    public static function tearDownAfterClass(): void
    {
        self::$parser = null;
    }
}
