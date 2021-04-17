<?php
namespace Fixtures;

class Foo
{
    public function foo(): Foo
    {
        return $this;
    }
}