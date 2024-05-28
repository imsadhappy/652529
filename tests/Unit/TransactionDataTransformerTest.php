<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utils\JSONParser;
use App\DataTransformers\TransactionDataTransformer;
use App\Dto\Transaction;
use App\Exceptions\DataTransformer\MissingPropertyException;

class TransactionDataTransformerTest extends TestCase
{
    private $validRecord = '{"bin":"123","amount":"100.00","currency":"GBP"}';
    private $invalidRecord = '{"foo":"bar"}';

    public function testAbortOnMissingProperty(): void
    {
        $this->expectException(MissingPropertyException::class);
        $this->tryTransforming($this->invalidRecord);
    }

    public function testTransformRecordIntoTransaction(): void
    {
        $this->assertInstanceOf(Transaction::class, $this->tryTransforming($this->validRecord));
    }

    protected function tryTransforming(string $record): Transaction
    {
        $parser = new JSONParser();
        $transformer = new TransactionDataTransformer();

        return $transformer($parser->parse($record));
    }
}
