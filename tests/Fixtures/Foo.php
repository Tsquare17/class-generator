<?php

namespace Fixtures;

class Foo
{
    protected $regex_string = true;

    public function foo(): Foo
    {
        return $this;
    }
}