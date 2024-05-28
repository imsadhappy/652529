<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Traits\SimpleFileCache;

class SimpleFileCacheTest extends TestCase
{
    use SimpleFileCache;

    public function testWriteToCache(): void
    {
        $contents = md5(__CLASS__);
        $bites = $this->writeToCache(__CLASS__, $contents);
        $this->assertEquals(strlen($contents), $bites);
    }

    public function testReadFromCache(): void
    {
        $contents = $this->readFromCache(__CLASS__, 999);
        $this->assertEquals(md5(__CLASS__), $contents);
    }

    public function testInvalidateCache(): void
    {
        $contents = $this->readFromCache(__CLASS__, -999);
        $this->assertNull($contents);
    }
}
