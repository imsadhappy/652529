<?php

namespace App\Interfaces;

interface ParserInterface {

    public function parse(string $input): object;
}
