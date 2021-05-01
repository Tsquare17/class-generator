<?php

namespace Tsquare\FileGenerator;

use PHPUnit\Framework\TestCase;
use Tsquare\FileGenerator\Utils\Strings;

class StringsTest extends TestCase
{
    /** @test */
    public function can_convert_pascal_to_lower_with_glue(): void
    {
        $dashed = Strings::pascalTo('TestString', '-');

        $this->assertEquals('test-string', $dashed);
    }

    /** @test */
    public function can_convert_to_plural(): void
    {
        $test = Strings::plural('test');
        $this->assertEquals('tests', $test);

        $endsWithConsonantY = Strings::plural('country');
        $this->assertEquals('countries', $endsWithConsonantY);

        $endsWithVowelY = Strings::plural('ray');
        $this->assertEquals('rays', $endsWithVowelY);

        $endsWithO = Strings::plural('photo');
        $this->assertEquals('photos', $endsWithO);

        $endsWithSH = Strings::plural('brush');
        $this->assertEquals('brushes', $endsWithSH);
    }
}
