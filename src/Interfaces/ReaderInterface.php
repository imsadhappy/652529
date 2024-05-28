<?php

namespace App\Interfaces;

interface ReaderInterface {

    public function read(string $from): \Generator;
}
