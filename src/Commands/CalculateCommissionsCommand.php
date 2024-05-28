<?php declare(strict_types=1);

namespace App\Commands;

use App\Interfaces\ReaderInterface;
use App\Interfaces\ParserInterface;
use App\DataTransformers\TransactionDataTransformer;

final class CalculateCommissionsCommand {

    function __construct($from, ReaderInterface $reader, ParserInterface $parser)
    {
        try {
            $transformer = new TransactionDataTransformer();
            foreach ($reader->read($from) as $record) {
                if (empty($record)) {
                    continue;
                }
                $transaction = $transformer($parser->parse($record));
                \print_r($transaction);
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
