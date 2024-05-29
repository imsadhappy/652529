<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Utils\JSONParser;
use App\DataTransformers\TransactionDataTransformer;
use App\Dto\Transaction;
use App\Exceptions\DataTransformer\MissingPropertyException;

class TransactionsFromJSONTest extends TestCase
{
    private static ?JSONParser $parser;
    private static ?TransactionDataTransformer $transformer;

    public function testAbortOnMissingProperty(): void
    {
        $this->expectException(MissingPropertyException::class);
        $invalidRecord = '{"foo":"bar"}';
        $data = self::$parser->parse($invalidRecord);
        $tranform = self::$transformer;
        $tranform($data);
    }

    public function testTransformJsonRecordIntoTransaction(): void
    {
        $validRecord = '{"bin":"123","amount":"100.00","currency":"GBP"}';
        $data = self::$parser->parse($validRecord);
        $tranform = self::$transformer;
        $this->assertInstanceOf(Transaction::class, $tranform($data));
    }

    public static function setUpBeforeClass(): void
    {
        self::$parser = new JSONParser();
        self::$transformer = new TransactionDataTransformer();
    }

    public static function tearDownAfterClass(): void
    {
        self::$parser = null;
        self::$transformer = null;
    }
}
