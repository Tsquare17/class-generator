<?php

use PHPUnit\Framework\TestCase;
use Tsquare\FileGenerator\Utils\Strings;

class StringsTest extends TestCase
{
    /** @test */
    public function can_convert_pascal_to_lower_with_glue(): void
    {
        $dashed = Strings::pascalTo('TestString', '-');

        self::assertEquals('test-string', $dashed);
    }

    /** @test */
    public function can_convert_to_plural(): void
    {
        $test = Strings::plural('test');
        self::assertEquals('tests', $test);

        $endsWithConsonantY = Strings::plural('country');
        self::assertEquals('countries', $endsWithConsonantY);

        $endsWithVowelY = Strings::plural('ray');
        self::assertEquals('rays', $endsWithVowelY);

        $endsWithO = Strings::plural('photo');
        self::assertEquals('photos', $endsWithO);

        $endsWithSH = Strings::plural('brush');
        self::assertEquals('brushes', $endsWithSH);
    }
}
