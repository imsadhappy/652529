<?php

namespace Tests\Integration;

use Tests\EnvTestCase;
use App\Interfaces\BinToCountryCodeConverterInterface;
use GuzzleHttp\Exception\ClientException;
use App\Providers\RapidAPIBinChecker;

class RapidAPIBinCheckerTest extends EnvTestCase
{
    private static ?BinToCountryCodeConverterInterface $binConverterProvider;

    public function testAbortOnInvalidBin(): void
    {
        $this->expectException(ClientException::class);
        self::$binConverterProvider->getCountryCode('999');
    }

    public function testGetCountryCode(): void
    {
        $countryCode = self::$binConverterProvider->getCountryCode('41417360');
        $this->assertEquals('US', $countryCode);
    }

    protected function assertPreConditions(): void
    {
        if (empty(self::$binConverterProvider)) {
            $this->markTestSkipped('Using other provider or API key not set');
        }
    }

    protected function setUp(): void
    {
        if (empty(self::$binConverterProvider) &&
            isset(self::$env['BIN_CONVERTER_PROVIDER']) &&
            self::$env['BIN_CONVERTER_PROVIDER'] == 'App\Providers\RapidAPIBinChecker' &&
            isset(self::$env['BIN_CONVERTER_PROVIDER_KEY']) &&
            !empty(self::$env['BIN_CONVERTER_PROVIDER_KEY'])) {
                self::$binConverterProvider = new RapidAPIBinChecker(self::$env['BIN_CONVERTER_PROVIDER_KEY']);
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::$binConverterProvider = null;
    }
}
