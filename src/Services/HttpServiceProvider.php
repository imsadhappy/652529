<?php

namespace App\Services;

use GuzzleHttp\Client;

class HttpServiceProvider {

    protected static Client $client;

    protected function newClient(string $base_uri): void
    {
        self::$client = new Client([
            'base_uri' => $base_uri
        ]);
    }
}
