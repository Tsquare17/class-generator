<?php

namespace Tsquare\FileGenerator;

use PHPUnit\Framework\TestCase;

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
        $this->assertFileExists(__DIR__ . '/Templates/Destination/TestFile.php');
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

    /** @test */
    public function can_use_custom_replacement_tokens(): void
    {
        $this->assertStringContainsString('$customToken = \'foo_value\';', $this->fileContents);
    }

    /** @test */
    public function can_replace_tokens_with_plural(): void
    {
        $this->assertStringContainsString('$quuz = \'TestFiles\';', $this->fileContents);
    }
    
    /** @test */
    public function can_replace_title_token(): void
    {
        $this->assertStringContainsString('$quuuz = \'A Title\';', $this->fileContents);
    }

    /** @test */
    public function can_use_multiple_token_modifiers(): void
    {
        $this->assertStringContainsString('$quuuuz = \'TEST_FILES\';', $this->fileContents);
    }
}
