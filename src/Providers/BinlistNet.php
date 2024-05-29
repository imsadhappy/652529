<?php

namespace App\Providers;

use App\Providers\HttpServiceProvider;
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
