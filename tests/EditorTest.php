<?php

use PHPUnit\Framework\TestCase;
use Tsquare\FileGenerator\FileEditor;
use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;
use Tsquare\FileGenerator\TokenAction;

class EditorTest extends TestCase
{
    /**
     * @var FileTemplate
     */
    protected $template;

    public function setUp()
    {
        $this->template = FileTemplate::init(__DIR__ . '/Templates/Foo.php');
    }

    public function tearDown()
    {
        unlink(__DIR__ . '/Fixtures/Foo.php');

        $template = FileTemplate::init(__DIR__ . '/Templates/Foo.php');

        $generator = new FileGenerator($template);

        $generator->create();

        unlink(__DIR__ . '/Fixtures/OnlyEdit.php');

        $template = FileTemplate::init(__DIR__ . '/Templates/RebuildOnlyEdit.php');

        $generator = new FileGenerator($template);

        $generator->create();
    }

    /** @test */
    public function can_insert_before()
    {
        $editor = new FileEditor();

        $editor->insertBefore('
    public function get{name}(): {name}
    {
        return $this;
    }

', '    public function {underscore}');

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertContains('
    public function getFoo(): Foo
    {
        return $this;
    }

    public function foo(): Foo
    {
        return $this;
    }
', $fileContents);
    }

    /** @test */
    public function can_insert_after()
    {
        $editor = new FileEditor();

        $editor->insertAfter('
    public function set{name}(): {name}
    {
        return $this;
    }
', 'public function {underscore}(): {name}
    {
        return $this;
    }
');

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertContains('
    public function foo(): Foo
    {
        return $this;
    }

    public function setFoo(): Foo
    {
        return $this;
    }
', $fileContents);
    }

    /** @test */
    public function can_replace_text()
    {
        $editor = new FileEditor();

        $editor->replace('{underscore}', 'bar');

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertContains('public function bar()', $fileContents);
    }

    /** @test */
    public function can_insert_before_text_or_other_text()
    {
        $editor = new FileEditor();

        $editor->insertBefore('
    public function get{name}(): {name}
    {
        return $this;
    }

', 'some non-existent text', [
            'before' => '    public function {underscore}',
        ]);

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertContains('
    public function getFoo(): Foo
    {
        return $this;
    }

    public function foo(): Foo
    {
        return $this;
    }
', $fileContents);
    }

    /** @test */
    public function can_insert_after_text_or_other_text()
    {
        $editor = new FileEditor();

        $editor->insertAfter('
    public function bar{name}(): {name}
    {
        return $this;
    }
', 'public function {underscore}Method(): {name}
    {
        return $this;
    }
', [
    'after' => 'public function {underscore}(): {name}
    {
        return $this;
    }
'
        ]);

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertContains('
    public function foo(): Foo
    {
        return $this;
    }

    public function barFoo(): Foo
    {
        return $this;
    }
', $fileContents);
    }

    /** @test */
    public function can_replace_text_or_other_text()
    {
        $editor = new FileEditor();

        $editor->replace(
            '/{underscore}nonexistent/',
            'bar_test',
            [
                'replace' => '/{underscore}/'
            ]
        )->isRegex();

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertContains('public function bar_test()', $fileContents);
    }

    /** @test */
    public function doesnt_insert_if_contains_string_in_if_not_contains()
    {
        $editor = new FileEditor();

        $editor->replace('{underscore}', 'bar')->ifNotContaining('{underscore}');

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertNotContains('bar', $fileContents);
    }

    /** @test */
    public function doesnt_insert_if_contains_regex_in_if_not_contains()
    {
        $editor = new FileEditor();

        $editor->replace('/{underscore}/', 'bar')->ifNotContaining('/{underscore}/')->isRegex();

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertNotContains('bar', $fileContents);
    }

    /** @test */
    public function does_insert_if_doesnt_contain_regex_in_if_not_contains()
    {
        $editor = new FileEditor();

        $editor->replace('/{underscore}/', 'bar')->ifNotContaining('/nonexistent/')->isRegex();

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertContains('bar', $fileContents);
    }

    /** @test */
    public function can_replace_regex()
    {
        $editor = new FileEditor();

        $editor->replace('/regex_string/', 'isRegex')->isRegex();

        $this->template->fileEditor($editor);

        $generator = new FileGenerator($this->template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/Foo.php');

        $this->assertContains('$isRegex = true;', $fileContents);
    }

    /** @test */
    public function can_use_custom_token()
    {
        $template = FileTemplate::init(__DIR__ . '/Templates/EditorFile.php');

        $template->addReplacementToken(new TokenAction('custom', static function ($name) {
            return 'custom-token';
        }));

        $generator = new FileGenerator($template);

        $generator->create();

        $fileContents = file_get_contents(__DIR__ . '/Fixtures/OnlyEdit.php');

        $this->assertContains('$custom = \'custom-token\';', $fileContents);
    }
}
