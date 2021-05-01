<?php

use PHPUnit\Framework\TestCase;
use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;

class TemplateTest extends TestCase
{
    protected $fileContents;

    public function setUp(): void
    {
        $template = FileTemplate::init(__DIR__ . '/Templates/TemplateFile.php');

        $generator = new FileGenerator($template);

        $generator->create();

        $this->fileContents = file_get_contents(__DIR__ . '/Templates/Destination/TestFile.php');
    }

    public function tearDown(): void
    {
        unlink(__DIR__ . '/Templates/Destination/TestFile.php');
        rmdir(__DIR__ . '/Templates/Destination');
    }

    /** @test */
    public function file_exists(): void
    {
        self::assertFileExists(__DIR__ . '/Templates/Destination/TestFile.php');
    }

    /** @test */
    public function name_is_replaced(): void
    {
        self::assertStringContainsString('$foo = \'TestFile\';', $this->fileContents);
    }

    /** @test */
    public function camel_is_replaced(): void
    {
        self::assertStringContainsString('$bar = \'testFile\';', $this->fileContents);
    }

    /** @test */
    public function pascal_is_replaced(): void
    {
        self::assertStringContainsString('$baz = \'TestFile\';', $this->fileContents);
    }

    /** @test */
    public function underscore_is_replaced(): void
    {
        self::assertStringContainsString('$qux = \'test_file\';', $this->fileContents);
    }

    /** @test */
    public function dash_is_replaced(): void
    {
        self::assertStringContainsString('$quux = \'test-file\';', $this->fileContents);
    }

    /** @test */
    public function can_use_custom_replacement_tokens(): void
    {
        self::assertStringContainsString('$customToken = \'foo_value\';', $this->fileContents);
    }

    /** @test */
    public function can_replace_tokens_with_plural(): void
    {
        self::assertStringContainsString('$quuz = \'TestFiles\';', $this->fileContents);
    }

    /** @test */
    public function can_replace_title_token(): void
    {
        self::assertStringContainsString('$quuuz = \'Test File\';', $this->fileContents);
    }

    /** @test */
    public function can_use_multiple_token_modifiers(): void
    {
        self::assertStringContainsString('$quuuuz = \'TEST_FILES\';', $this->fileContents);
    }
}
