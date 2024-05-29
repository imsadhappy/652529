<?php

namespace App\Commands;

use App\Interfaces\CommandInterface;
use App\Traits\SimpleFileCache;

abstract class CachedCommand implements CommandInterface {

    use SimpleFileCache;

    protected array $cachedVars;

    abstract public function run(): void;

    protected function preloadCachedVars(): void
    {
        foreach($this->cachedVars as $var => $exp) {
            $data = $this->readFromCache(get_called_class().'-'.$var, $exp);
            $this->{$var} = empty($data) ? [] : unserialize($data);
        }
    }

    protected function getCachedOrLoad(string $key, string $from, callable $load): mixed
    {
        if (isset($this->{$from}[$key])) {
            return $this->{$from}[$key];
        }

        $value = $load();
        $this->{$from}[$key] = $value;
        $this->writeToCache(get_called_class().'-'.$from, serialize($this->{$from}));

        return $value;
    }
}
