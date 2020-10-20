<?php

namespace Tsquare\FileGenerator;

use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    protected $fileContents;

    public function setUp(): void
    {
        $template = FileTemplate::init(__DIR__ . '/Fixtures/TemplateFile.php');

        $generator = new FileGenerator($template);

        $generator->create();

        $this->fileContents = file_get_contents(__DIR__ . '/Fixtures/Destination/TestFile.php');
    }

    public function tearDown(): void
    {
        unlink(__DIR__ . '/Fixtures/Destination/TestFile.php');
        rmdir(__DIR__ . '/Fixtures/Destination');
    }

    /** @test */
    public function file_exists(): void
    {
        $this->assertFileExists(__DIR__ . '/Fixtures/Destination/TestFile.php');
    }

    /** @test */
    public function name_is_replaced(): void
    {
        $this->assertStringContainsString('$foo = \'TestFile\';', $this->fileContents);
    }

    /** @test */
    public function camel_is_replaced(): void
    {
        $this->assertStringContainsString('$bar = \'testFile\';', $this->fileContents);
    }

    /** @test */
    public function pascal_is_replaced(): void
    {
        $this->assertStringContainsString('$baz = \'TestFile\';', $this->fileContents);
    }

    /** @test */
    public function underscore_is_replaced(): void
    {
        $this->assertStringContainsString('$qux = \'test_file\';', $this->fileContents);
    }

    /** @test */
    public function dash_is_replaced(): void
    {
        $this->assertStringContainsString('$quux = \'test-file\';', $this->fileContents);
    }
}
