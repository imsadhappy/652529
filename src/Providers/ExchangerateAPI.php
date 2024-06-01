<?php

namespace App\Providers;

use App\Providers\HttpServiceProvider;
use App\Interfaces\ExchangeRateProviderInterface;
use App\Exceptions\Parser\InvalidJSONException;
use App\Exceptions\Service\ProviderException;

use Brick\Money\ExchangeRateProvider\ConfigurableProvider;

class ExchangerateAPI extends HttpServiceProvider implements ExchangeRateProviderInterface {

    private $host = 'https://v6.exchangerate-api.com';

    function __construct(private string $apiKey)
    {
        $this->newClient($this->host);
    }

    /**
     * Get Rate
     *
     * @param string $from
     * @param string $to
     *
     * @return ConfigurableProvider
     * @throws InvalidJSONException|ProviderException|UnknownCurrencyException
     */
    public function getRate(string $from, string $to): ConfigurableProvider
    {
        $response = self::$client->request('GET', "/v6/{$this->apiKey}/latest/{$from}");
        $responseObject = json_decode($response->getBody());

        if (is_null($responseObject)) {
            throw new InvalidJSONException(__CLASS__);
        }

        if ($responseObject->result != 'success' || !isset($responseObject->conversion_rates->{$to})) {
            throw new ProviderException(__CLASS__ . " failed retrieving exchange rates from '$from' to '$to'");
        }

        $provider = new ConfigurableProvider();
        $provider->setExchangeRate($from, $to, $responseObject->conversion_rates->{$to});

        return $provider;
    }
}
