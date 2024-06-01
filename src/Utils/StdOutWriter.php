<?php declare(strict_types=1);

namespace App\Utils;

use App\Interfaces\WriterInterface;

class StdOutWriter implements WriterInterface {

    public function write(mixed $value): void
    {
        echo strval($value) . \PHP_EOL;
    }
}
