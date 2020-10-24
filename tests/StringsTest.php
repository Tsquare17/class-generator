<?php

namespace Tsquare\FileGenerator;

use PHPUnit\Framework\TestCase;
use Tsquare\FileGenerator\Utils\Strings;

class StringsTest extends TestCase
{
    /** @test */
    public function can_convert_pascal_to_dashed(): void
    {
        $dashed = Strings::pascalTo('TestString', '-');

        $this->assertEquals('test-string', $dashed);
    }

    /** @test */
    public function can_convert_to_underscore(): void
    {
        $underscore = Strings::pascalTo('TestString', '_');

        $this->assertEquals('test_string', $underscore);
    }

    /** @test */
    public function can_convert_to_plural(): void
    {
        $test = Strings::plural('test');

        $this->assertEquals('tests', $test);

        $endsWithY = Strings::plural('country');

        $this->assertEquals('countries', $endsWithY);
    }
}
