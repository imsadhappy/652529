<?php

namespace App\Interfaces;

interface BinToCountryCodeConverterInterface {

    public function getCountryCode(int $bin): string;
}
