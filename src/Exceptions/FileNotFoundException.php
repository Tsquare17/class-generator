<?php

namespace Tsquare\FileGenerator\Exceptions;

/**
 * Class FileNotFoundException
 * @package Tsquare\FileGenerator\Exceptions
 */
class FileNotFoundException extends \RuntimeException
{
    /**
     * FileNotFoundException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct(sprintf('The file "%s" does not exist', $path));
    }
}
