<?php declare(strict_types=1);

namespace App\Utils;

use App\Interfaces\ReaderInterface;
use App\Exceptions\Reader\FileNotFoundOrEmptyException;

class FileReader implements ReaderInterface {

    /**
     * Reads file from filesystem
     *
     * @param  string $filePath
     * @return \Generator line(s) from file
     * @throws FileNotFoundOrEmptyException
     */
    public function read(string $filePath): \Generator
    {
        if (!is_readable($filePath) || filesize($filePath) < 1) {
            throw new FileNotFoundOrEmptyException($filePath);
        }

        $handle = fopen($filePath, "r");

        while (!feof($handle)) {
            yield fgets($handle);
        }

        fclose($handle);
    }
}
