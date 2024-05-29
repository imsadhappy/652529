<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utils\FileReader;
use App\Exceptions\Reader\FileNotFoundOrEmptyException;

class FileReaderTest extends TestCase
{
    private static ?FileReader $reader;

    public function testAbortIfFileNotFound(): void
    {
        $this->expectException(FileNotFoundOrEmptyException::class);
        $this->tryReadingFromMock();
    }

    public function testAbortIfFileIsEmpty(): void
    {
        $this->expectException(FileNotFoundOrEmptyException::class);
        $this->tryReadingFromMock('');
    }

    public function testReadFile(): void
    {
        $value = 'bar';
        $this->assertEquals($value, $this->tryReadingFromMock($value));
    }

    protected function tryReadingFromMock($value = null): string
    {
        foreach (self::$reader->read($this->mockFile($value)) as $line) {
            return $line;
        }
    }

    protected function mockFile($value = null): string
    {
        $filePath = __DIR__ . '/../../foo.txt';

        if (!is_null($value)) {
            file_put_contents($filePath, $value);
        }

        return $filePath;
    }

    public static function setUpBeforeClass(): void
    {
        self::$reader = new FileReader();
    }

    public static function tearDownAfterClass(): void
    {
        self::$reader = null;
    }

    protected function tearDown(): void
    {
        @unlink($this->mockFile());
    }
}
