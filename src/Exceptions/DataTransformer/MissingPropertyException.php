<?php declare(strict_types=1);

namespace App\Exceptions\DataTransformer;

final class MissingPropertyException extends \Exception
{
    public function __construct(string $propertyName, string $dto)
    {
        parent::__construct("Missing property '$propertyName' for $dto");
    }
}
