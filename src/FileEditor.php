<?php

namespace Tsquare\FileGenerator;

use Tsquare\FileGenerator\Exceptions\FileNotFoundException;
use Tsquare\FileGenerator\Utils\Strings;

/**
 * Class FileEditor
 * @package Tsquare\FileGenerator
 */
class FileEditor implements Editor
{
    protected string $file;
    protected string $fileName;
    protected array $replacements;

    /**
     * Specify the file to be edited.
     *
     * @param string $file
     *
     * @return FileEditor
     */
    public function file(string $file): FileEditor
    {
        if (!is_file($file)) {
            throw new FileNotFoundException($file);
        }

        $this->fileName = $file;
        $this->file = file_get_contents($file);

        return $this;
    }

    /**
     * Insert a string on the line before another string.
     *
     * @param string $insert
     * @param string $before
     *
     * @return FileEditor
     */
    public function insertBefore(string $insert, string $before): FileEditor
    {
        $this->replace($before, ltrim($insert, PHP_EOL) . $before);

        return $this;
    }

    /**
     * Insert a string on the line after a string.
     *
     * @param string $insert
     * @param string $after
     *
     * @return FileEditor
     */
    public function insertAfter(string $insert, string $after): FileEditor
    {
        $this->replace($after, $after . $insert);

        return $this;
    }

    /**
     * Replace a string with another string.
     *
     * @param string $search
     * @param string $replace
     *
     * @return FileEditor
     */
    public function replace(string $search, string $replace): FileEditor
    {
        $this->replacements[] = [
            'search' => $search,
            'replace' => $replace,
        ];

        return $this;
    }

    /**
     * Execute file edits.
     *
     * @param string|null $name
     *
     * @return bool
     */
    public function execute(string $name): bool
    {
        foreach ($this->replacements as $replacement) {
            $this->file = str_replace(
                Strings::fillPlaceholders($replacement['search'], $name),
                Strings::fillPlaceholders($replacement['replace'], $name),
                $this->file
            );
        }

        return file_put_contents($this->fileName, $this->file);
    }
}
