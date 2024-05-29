<?php

namespace Tests\Integration;

use Tests\EnvTestCase;
use App\Interfaces\ExchangeRateProviderInterface;
use App\Providers\ExchangerateAPI;
use GuzzleHttp\Exception\ClientException;

class ExchangerateAPITest extends EnvTestCase
{
    private static ?ExchangeRateProviderInterface $exchangeRateProvider;

    public function testAbortOnInvalidCurrencyCode(): void
    {
        $this->expectException(ClientException::class);
        self::$exchangeRateProvider->getRate('FOO', 'BAR');
    }

    public function testGetRate(): void
    {
        $exchangeRate = self::$exchangeRateProvider->getRate('USD', 'USD');
        $this->assertEquals(1, $exchangeRate);
    }

    protected function assertPreConditions(): void
    {
        if (empty(self::$exchangeRateProvider)) {
            $this->markTestSkipped('Using other provider or API key not set');
        }
    }

    protected function setUp(): void
    {
        if (empty(self::$exchangeRateProvider) &&
            isset(self::$env['EXCHANGE_RATE_PROVIDER']) &&
            self::$env['EXCHANGE_RATE_PROVIDER'] == 'App\Providers\ExchangerateAPI' &&
            isset(self::$env['EXCHANGE_RATE_PROVIDER_KEY']) &&
            !empty(self::$env['EXCHANGE_RATE_PROVIDER_KEY'])) {
                self::$exchangeRateProvider = new ExchangerateAPI(self::$env['EXCHANGE_RATE_PROVIDER_KEY']);
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::$exchangeRateProvider = null;
    }
}
