<?php

namespace App\Traits;

trait SimpleFileCache {

    private static function filePath(string $fileName): string
    {
        $dirName = __DIR__ . "/../../cache";
        if (!is_dir($dirName)) {
            mkdir($dirName);
        }
        $fileName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '-', strtolower($fileName));
        return sprintf("%s/%s.cache", $dirName, mb_ereg_replace("([\.]{2,})", '-', $fileName));
    }

    public function readFromCache(string $fileName, int $expirationPeriod): mixed
    {
        $filePath = self::filePath($fileName);

        if (!is_readable($filePath)) {
            return null;
        }

        if (time() - filemtime($filePath) < $expirationPeriod) {
            return file_get_contents($filePath);
        }

        @unlink($filePath); //invalidate stale cache

        return null;
    }

    public function writeToCache(string $fileName, mixed $data): int|false
    {
        $filePath = self::filePath($fileName);

        return file_put_contents($filePath, $data);
    }
}
