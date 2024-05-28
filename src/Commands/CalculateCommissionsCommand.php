<?php declare(strict_types=1);

namespace App\Commands;

use App\Commands\CachedCommand;
use App\Interfaces\ReaderInterface;
use App\Interfaces\ParserInterface;
use App\DataTransformers\TransactionDataTransformer;
use App\Interfaces\BinToCountryCodeConverterInterface;
use App\Interfaces\ExchangeRateProviderInterface;

final class CalculateCommissionsCommand extends CachedCommand {

    function __construct($from,
                        private string $baseCurrency,
                        ReaderInterface $reader,
                        ParserInterface $parser,
                        private BinToCountryCodeConverterInterface $binConverter,
                        private ExchangeRateProviderInterface $exchangeRateProvider,
                        callable $CommissionCalculation)
    {
        try {
            $this->preloadCacheFor([
                'ExchangeRates' => strtotime('tomorrow') - time(),
                'BinCountryCodes' => 100500
            ]);
            $transformer = new TransactionDataTransformer();
            foreach ($reader->read($from) as $record) {
                if (empty($record)) {
                    continue;
                }
                $transaction = $transformer($parser->parse($record));
                $exchangeRate = $transaction->currency === $this->baseCurrency ? 1 :
                                $this->getExchangeRateFor($transaction->currency);
                $countryCode = $this->getCountryCodeFor($transaction->bin);
                $ammountInBaseCurrency = $transaction->amount / $exchangeRate;
                $CommissionCalculation($ammountInBaseCurrency, $countryCode);
            }
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    private function getCountryCodeFor(int $bin)
    {
        return $this->getCachedOrLoad(strval($bin), 'BinCountryCodes', fn() => $this->binConverter->getCountryCode($bin));
    }

    private function getExchangeRateFor(string $currency)
    {
        return $this->getCachedOrLoad($currency, 'ExchangeRates', fn() => $this->exchangeRateProvider->getRate($currency, $this->baseCurrency));
    }
}
