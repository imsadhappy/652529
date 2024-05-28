<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utils\JSONParser;
use App\Exceptions\Parser\InvalidJSONException;
use App\Exceptions\Parser\InvalidRecordException;

class JSONParserTest extends TestCase
{
    public function testAbortOnInvalidJson(): void
    {
        $this->expectException(InvalidJSONException::class);
        $this->tryParsing('foobar');
    }

    public function testAbortOnInvalidRecord(): void
    {
        $this->expectException(InvalidRecordException::class);
        $this->tryParsing('[]');
    }

    public function testAbortOnEmptyRecord(): void
    {
        $this->expectException(InvalidRecordException::class);
        $this->tryParsing('{}');
    }

    public function testParseRecords(): void
    {
        $this->assertIsObject($this->tryParsing('{"foo":"bar"}'));
    }

    private function tryParsing($data): object
    {
        $parser = new JSONParser();

        return $parser->parse($data);
    }
}
