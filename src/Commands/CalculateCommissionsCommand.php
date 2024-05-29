<?php declare(strict_types=1);

namespace App\Commands;

use App\Commands\CachedCommand;
use App\Interfaces\ReaderInterface;
use App\Interfaces\ParserInterface;
use App\DataTransformers\TransactionDataTransformer;
use App\Interfaces\BinToCountryCodeConverterInterface;
use App\Interfaces\CommissionCalculatorInterface;
use App\Interfaces\ExchangeRateProviderInterface;

final class CalculateCommissionsCommand extends CachedCommand {

    private string $readFrom;
    private string $baseCurrency;
    private ReaderInterface $reader;
    private ParserInterface $parser;
    private BinToCountryCodeConverterInterface $binConverter;
    private ExchangeRateProviderInterface $exchangeRateProvider;
    private CommissionCalculatorInterface $commissionCalculator;

    function __construct(array $env)
    {
        $this->readFrom = $env['READ_FROM'];
        $this->baseCurrency = $env['BASE_CURRENCY'];
        $this->reader = new $env['COMMISION_READER']();
        $this->parser = new $env['COMMISION_PARSER']();
        $this->binConverter = new $env['BIN_CONVERTER_PROVIDER']($env['BIN_CONVERTER_PROVIDER_KEY']);
        $this->exchangeRateProvider = new $env['EXCHANGE_RATE_PROVIDER']($env['EXCHANGE_RATE_PROVIDER_KEY']);
        $this->commissionCalculator = new $env['COMMISION_CALCULATOR']();
        $this->cachedVars = [
            'ExchangeRates' => $env['EXCHANGE_RATE_CACHE_EXPIRATION'] ?? strtotime('+1 hour') - time(),
            'BinCountryCodes' => $env['BIN_CONVERTER_CACHE_EXPIRATION'] ?? strtotime('+1 hour') - time()
        ];

        $this->run();
    }

    public function run(): void
    {
        try {
            $this->preloadCachedVars();
            $transformer = new TransactionDataTransformer();
            $commissionCalculator = $this->commissionCalculator;
            foreach ($this->reader->read($this->readFrom) as $record) {
                if (empty($record)) {
                    continue;
                }
                $transaction = $transformer($this->parser->parse($record));
                $exchangeRate = $transaction->currency === $this->baseCurrency ? 1 : $this->getExchangeRateFor($transaction->currency);
                $countryCode = $this->getCountryCodeFor($transaction->bin);
                $commission = $commissionCalculator($transaction->amount, $exchangeRate, $countryCode);
                echo number_format($commission, 2, '.', '') . \PHP_EOL;
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
