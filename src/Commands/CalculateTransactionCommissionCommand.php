<?php declare(strict_types=1);

namespace App\Commands;

use App\Commands\CachedCommand;
use App\Interfaces\ReaderInterface;
use App\Interfaces\ParserInterface;
use App\Interfaces\WriterInterface;
use App\DataTransformers\TransactionDataTransformer;
use App\DataTransformers\TransactionCurrencyDataTransformer;
use App\Interfaces\BinToCountryCodeConverterInterface;
use App\Interfaces\CommissionCalculatorInterface;
use App\Interfaces\ExchangeRateProviderInterface;
use Brick\Math\RoundingMode;

final class CalculateTransactionCommissionCommand extends CachedCommand {

    private string $readFrom;
    private string $baseCurrency;
    private RoundingMode $roundingMode;
    private ReaderInterface $reader;
    private ParserInterface $parser;
    private WriterInterface $writer;
    private BinToCountryCodeConverterInterface $binConverter;
    private ExchangeRateProviderInterface $exchangeRateProvider;
    private CommissionCalculatorInterface $commissionCalculator;
    private TransactionDataTransformer $transactionTransformer;
    private TransactionCurrencyDataTransformer $currencyTransformer;

    function __construct(array $env)
    {
        $this->readFrom = $env['READ_FROM'];
        $this->baseCurrency = $env['BASE_CURRENCY'];
        $this->roundingMode = constant('Brick\Math\RoundingMode::'.$env['ROUNDING_MODE']);
        $this->reader = new $env['COMMISION_READER']();
        $this->parser = new $env['COMMISION_PARSER']();
        $this->writer = new $env['COMMISION_WRITER']();
        $this->binConverter = new $env['BIN_CONVERTER_PROVIDER']($env['BIN_CONVERTER_PROVIDER_KEY']);
        $this->exchangeRateProvider = new $env['EXCHANGE_RATE_PROVIDER']($env['EXCHANGE_RATE_PROVIDER_KEY']);
        $this->commissionCalculator = new $env['COMMISION_CALCULATOR']();
        $this->cachedVars = ['BinCountryCodes' => $env['BIN_CONVERTER_CACHE_EXPIRATION'] ?? strtotime('+1 year') - time()];
        $this->transactionTransformer = new TransactionDataTransformer();
        $this->currencyTransformer = new TransactionCurrencyDataTransformer();
        $this->preloadCachedVars();
        $this->run();
    }

    public function run(): void
    {
        try {
            foreach ($this->reader->read($this->readFrom) as $record) {
                if (empty($record)) {
                    continue;
                }
                $result = $this->calculate($this->parser->parse($record));
                $this->writer->write($result);
            }
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    private function calculate(array $parsedRecord)
    {
        $transform = $this->transactionTransformer;
        $exchange = $this->currencyTransformer;
        $calculate = $this->commissionCalculator;
        $transaction = $transform($parsedRecord);
        if ($transaction->currency !== $this->baseCurrency) {
            $transaction = $exchange($transaction, $this->baseCurrency, $this->exchangeRateProvider, $this->roundingMode);
        }
        $commission = $calculate($transaction->amount, $this->getCountryCodeFor($transaction->bin));

        return $commission->to($transaction->amount->getContext(), $this->roundingMode);
    }

    private function getCountryCodeFor(int $bin)
    {
        return $this->getCachedOrLoad(strval($bin), 'BinCountryCodes', fn() => $this->binConverter->getCountryCode($bin));
    }
}
