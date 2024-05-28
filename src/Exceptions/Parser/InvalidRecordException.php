<?php declare(strict_types=1);

namespace App\Exceptions\Parser;

final class InvalidRecordException extends \Exception
{
    public function __construct(string $rawRecord)
    {
        parent::__construct("Record is not valid object: " . $rawRecord);
    }
}
