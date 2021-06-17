<?php

use PHPUnit\Framework\TestCase;
use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;

class TemplateTest extends TestCase
{
    protected $fileContents;

    public function setUp()
    {
        $template = FileTemplate::init(__DIR__ . '/Templates/TemplateFile.php');

        $generator = new FileGenerator($template);

        $generator->create();

        $this->fileContents = file_get_contents(__DIR__ . '/Templates/Destination/TestFile.php');
    }

    public function tearDown()
    {
        unlink(__DIR__ . '/Templates/Destination/TestFile.php');
        rmdir(__DIR__ . '/Templates/Destination');
    }

    /** @test */
    public function file_exists()
    {
        $this->assertFileExists(__DIR__ . '/Templates/Destination/TestFile.php');
    }

    /** @test */
    public function name_is_replaced()
    {
        $this->assertContains('$foo = \'TestFile\';', $this->fileContents);
    }

    /** @test */
    public function camel_is_replaced()
    {
        $this->assertContains('$bar = \'testFile\';', $this->fileContents);
    }

    /** @test */
    public function pascal_is_replaced()
    {
        $this->assertContains('$baz = \'TestFile\';', $this->fileContents);
    }

    /** @test */
    public function underscore_is_replaced()
    {
        $this->assertContains('$qux = \'test_file\';', $this->fileContents);
    }

    /** @test */
    public function dash_is_replaced()
    {
        $this->assertContains('$quux = \'test-file\';', $this->fileContents);
    }

    /** @test */
    public function can_use_custom_replacement_tokens()
    {
        $this->assertContains('$customToken = \'foo_value\';', $this->fileContents);
    }

    /** @test */
    public function can_replace_tokens_with_plural()
    {
        $this->assertContains('$quuz = \'TestFiles\';', $this->fileContents);
    }

    /** @test */
    public function can_replace_title_token()
    {
        $this->assertContains('$quuuz = \'Test File\';', $this->fileContents);
    }

    /** @test */
    public function can_use_multiple_token_modifiers()
    {
        $this->assertContains('$quuuuz = \'TEST_FILES\';', $this->fileContents);
    }

    /** @test */
    public function token_actions_execute_in_order()
    {
        $this->assertContains('$order = \'2\';', $this->fileContents);
    }
}
