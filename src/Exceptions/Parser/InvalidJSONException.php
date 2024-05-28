<?php declare(strict_types=1);

namespace App\Exceptions\Parser;

final class InvalidJSONException extends \Exception
{
    public function __construct($thrownIn)
    {

        $message = "Couldn't parse JSON in $thrownIn: ";

        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                $message .= 'Maximum stack depth exceeded';
            break;
            case JSON_ERROR_STATE_MISMATCH:
                $message .= 'Underflow or the modes mismatch';
            break;
            case JSON_ERROR_CTRL_CHAR:
                $message .= 'Unexpected control character found';
            break;
            case JSON_ERROR_SYNTAX:
                $message .= 'Syntax error, malformed JSON';
            break;
            case JSON_ERROR_UTF8:
                $message .= 'Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
            default:
                $message .= 'Unknown error';
            break;
        }

        parent::__construct($message);
    }
}
