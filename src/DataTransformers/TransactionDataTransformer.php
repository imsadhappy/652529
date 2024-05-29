<?php declare(strict_types=1);

namespace App\DataTransformers;

use App\Interfaces\DataTransformerInterface;
use App\Dto\Transaction;
use App\Exceptions\DataTransformer\MissingPropertyException;

class TransactionDataTransformer implements DataTransformerInterface {

    /**
     * Transform object into DTO
     *
     * @param  object $data
     * @throws MissingPropertyException
     */
    public function __invoke(object $data): Transaction
    {
        $transaction = new Transaction();

        if (empty($data->bin)) {
            throw new MissingPropertyException('bin');
        }

        $transaction->bin = intval($data->bin);

        if (!isset($data->amount)) {
            throw new MissingPropertyException('amount');
        }

        $transaction->amount = floatval($data->amount);

        if (empty($data->currency)) {
            throw new MissingPropertyException('currency');
        }

        $transaction->currency = strval($data->currency);

        return $transaction;
    }
}
