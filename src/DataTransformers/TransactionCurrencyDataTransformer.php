<?php declare(strict_types=1);

namespace App\DataTransformers;

use App\Dto\Transaction;
use App\Exceptions\DataTransformer\MissingPropertyException;
use App\Interfaces\ExchangeRateProviderInterface;

use Brick\Math\RoundingMode;
use Brick\Money\CurrencyConverter;
use Brick\Money\Exception\UnknownCurrencyException;

class TransactionCurrencyDataTransformer {

    /**
     * Convert Transaction currency
     *
     * @param  Transaction|array $transaction
     * @throws MissingPropertyException|InvalidArgumentException|UnknownCurrencyException
     */
    public function __invoke(
        Transaction $transaction,
        string $toCurrency,
        ExchangeRateProviderInterface $exchangeRateProvider,
        RoundingMode $roundingMode = RoundingMode::UP): Transaction
    {
        if ($toCurrency != $transaction->currency) {
            $exchangeRate = $exchangeRateProvider->getRate($transaction->currency, $toCurrency);
            $converter = new CurrencyConverter($exchangeRate);
            $transaction->amount = $converter->convert($transaction->amount, $toCurrency, roundingMode: $roundingMode);
        }

        return $transaction;
    }
}
