<?php declare(strict_types=1);

namespace App\Exceptions\Reader;

final class FileNotFoundOrEmptyException extends \Exception
{
    public function __construct(string $filePath)
    {
        parent::__construct("File '$filePath' doesn't exist, not readable or empty");
    }
}
