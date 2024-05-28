<?php

namespace App\Services;

use App\Services\HttpServiceProvider;
use App\Interfaces\ExchangeRateProviderInterface;
use App\Exceptions\Parser\InvalidJSONException;
use App\Exceptions\Service\ProviderException;

class ExchangerateAPI extends HttpServiceProvider implements ExchangeRateProviderInterface {

    private $host = 'https://v6.exchangerate-api.com';

    function __construct(private string $apiKey)
    {
        $this->newClient($this->host);
    }

    public function getRate(string $from, string $to): float
    {
        $response = self::$client->request('GET', "/v6/{$this->apiKey}/latest/{$from}");
        $responseObject = \json_decode($response->getBody());

        if (is_null($responseObject)) {
            throw new InvalidJSONException(__CLASS__);
        }

        if ($responseObject->result != 'success' || !isset($responseObject->conversion_rates->{$to})) {
            throw new ProviderException(__CLASS__ . ' failed retrieving exchange rates for ' . $from);
        }

        return \floatval($responseObject->conversion_rates->{$to});
    }
}
