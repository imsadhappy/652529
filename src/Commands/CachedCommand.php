<?php

namespace App\Commands;

use App\Traits\SimpleFileCache;

abstract class CachedCommand {

    use SimpleFileCache;

    protected function preloadCacheFor(array $vars)
    {
        foreach($vars as $var => $exp) {
            $data = $this->readFromCache(get_called_class().'-'.$var, $exp);
            $this->{$var} = empty($data) ? [] : unserialize($data);
        }
    }

    protected function getCachedOrLoad(string $key, string $from, callable $load)
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
