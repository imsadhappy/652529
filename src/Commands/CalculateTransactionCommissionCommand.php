<?php declare(strict_types=1);

namespace App\Commands;

use App\Commands\CachedCommand;
use App\Interfaces\ReaderInterface;
use App\Interfaces\ParserInterface;
use App\DataTransformers\TransactionDataTransformer;
use App\DataTransformers\TransactionCurrencyDataTransformer;
use App\Interfaces\BinToCountryCodeConverterInterface;
use App\Interfaces\CommissionCalculatorInterface;
use App\Interfaces\ExchangeRateProviderInterface;

use Brick\Math\RoundingMode;

final class CalculateTransactionCommissionCommand extends CachedCommand {

    private string $readFrom;
    private string $baseCurrency;
    private ReaderInterface $reader;
    private ParserInterface $parser;
    private BinToCountryCodeConverterInterface $binConverter;
    private ExchangeRateProviderInterface $exchangeRateProvider;
    private CommissionCalculatorInterface $commissionCalculator;
    private RoundingMode $roundingMode;

    function __construct(array $env)
    {
        $this->readFrom = $env['READ_FROM'];
        $this->baseCurrency = $env['BASE_CURRENCY'];
        $this->reader = new $env['COMMISION_READER']();
        $this->parser = new $env['COMMISION_PARSER']();
        $this->binConverter = new $env['BIN_CONVERTER_PROVIDER']($env['BIN_CONVERTER_PROVIDER_KEY']);
        $this->exchangeRateProvider = new $env['EXCHANGE_RATE_PROVIDER']($env['EXCHANGE_RATE_PROVIDER_KEY']);
        $this->commissionCalculator = new $env['COMMISION_CALCULATOR']();
        $this->roundingMode = constant('Brick\Math\RoundingMode::'.$env['ROUNDING_MODE']);
        $this->cachedVars = [
            'BinCountryCodes' => $env['BIN_CONVERTER_CACHE_EXPIRATION'] ?? strtotime('+1 hour') - time()
        ];

        $this->run();
    }

    public function run(): void
    {
        try {
            $this->preloadCachedVars();
            $transactionTransformer = new TransactionDataTransformer();
            $currencyTransformer = new TransactionCurrencyDataTransformer();
            $commissionCalculator = $this->commissionCalculator;
            foreach ($this->reader->read($this->readFrom) as $record) {
                if (empty($record)) {
                    continue;
                }
                $transaction = $transactionTransformer($this->parser->parse($record), $this->baseCurrency, $this->exchangeRateProvider, $this->roundingMode);
                if ($transaction->currency !== $this->baseCurrency) {
                    $transaction = $currencyTransformer($transaction, $this->baseCurrency, $this->exchangeRateProvider, $this->roundingMode);
                }
                $countryCode = $this->getCountryCodeFor($transaction->bin);
                $commission = $commissionCalculator($transaction->amount, $countryCode);
                echo $commission->to($transaction->amount->getContext(), $this->roundingMode) . \PHP_EOL;
            }
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    private function getCountryCodeFor(int $bin)
    {
        return $this->getCachedOrLoad(strval($bin), 'BinCountryCodes', fn() => $this->binConverter->getCountryCode($bin));
    }
}
