<?php declare(strict_types=1);

namespace App\DataTransformers;

use App\Dto\Transaction;
use App\Exceptions\DataTransformer\MissingPropertyException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

class TransactionDataTransformer {

    /**
     * Transform array into Transaction DTO
     *
     * @param  array $data
     * @throws MissingPropertyException|UnknownCurrencyException
     */
    public function __invoke(array $data): Transaction
    {
        $transaction = new Transaction();

        if (empty($data['bin'])) {
            throw new MissingPropertyException('bin', Transaction::class);
        }

        $transaction->bin = intval($data['bin']);

        if (empty($data['currency'])) {
            throw new MissingPropertyException('currency', Transaction::class);
        }

        $transaction->currency = strval($data['currency']);

        if (!isset($data['amount'])) {
            throw new MissingPropertyException('amount', Transaction::class);
        }

        $transaction->amount = Money::of($data['amount'], $transaction->currency);

        return $transaction;
    }
}
