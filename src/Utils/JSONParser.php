<?php declare(strict_types=1);

namespace App\Utils;

use App\Interfaces\ParserInterface;
use App\Exceptions\Parser\InvalidJSONException;
use App\Exceptions\Parser\InvalidRecordException;

class JSONParser implements ParserInterface {

    /**
     * @throws InvalidJSONException|InvalidRecordException
     */
    public function parse(string $input): array
    {
        $output = json_decode($input, true);

        if (is_null($output)) {
            throw new InvalidJSONException(__CLASS__);
        }

        if (!is_array($output) || empty($output)) {
            throw new InvalidRecordException($input);
        }

        return $output;
    }
}
