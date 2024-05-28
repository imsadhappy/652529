<?php

namespace App\Services;

use App\Services\HttpServiceProvider;
use App\Interfaces\BinToCountryCodeConverterInterface;

class BinlistNet extends HttpServiceProvider implements BinToCountryCodeConverterInterface {

    function __construct()
    {
        $this->newClient('https://lookup.binlist.net');
    }

    public function getCountryCode(int $bin): string
    {
        return '';
    }
}
