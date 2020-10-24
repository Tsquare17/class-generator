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
     * @param string      $insert
     * @param string      $before
     * @param string|null $or
     *
     * @return FileEditor
     */
    public function insertBefore(string $insert, string $before, array $or = []): FileEditor
    {
        if (empty($or)) {
            $this->replace($before, ltrim($insert, PHP_EOL) . $before, $or);
        } else {
            $this->replace($before, ltrim($insert, PHP_EOL), $or);
        }

        return $this;
    }

    /**
     * Insert a string on the line after a string.
     *
     * @param string $insert
     * @param string $after
     * @param array  $or
     *
     * @return FileEditor
     */
    public function insertAfter(string $insert, string $after, array $or = []): FileEditor
    {
        if (empty($or)) {
            $this->replace($after, $after . $insert, $or);
        } else {
            $this->replace($after, $insert, $or);
        }

        return $this;
    }

    /**
     * Replace a string with another string.
     *
     * @param string $search
     * @param string $replace
     * @param array  $or
     *
     * @return FileEditor
     */
    public function replace(string $search, string $replace, array $or = []): FileEditor
    {
        $this->replacements[] = [
            'search' => $search,
            'replace' => $replace,
            'or' => $or,
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
            $conditionMet = false;

            if (strpos($this->file, Strings::fillPlaceholders($replacement['search'], $name))) {
                $this->file = str_replace(
                    Strings::fillPlaceholders($replacement['search'], $name),
                    Strings::fillPlaceholders($replacement['replace'], $name),
                    $this->file
                );
                $conditionMet = true;
            }

            if ($conditionMet === false && !empty($replacement['or'])) {
                foreach ($replacement['or'] as $condition => $text) {
                    if ($conditionMet === true) {
                        continue;
                    }

                    if (strpos($this->file, Strings::fillPlaceholders($text, $name))) {
                        $replacementText = null;
                        if ($condition === 'before') {
                            $replacementText = Strings::fillPlaceholders($replacement['replace'], $name)
                                               . Strings::fillPlaceholders($text, $name);
                        } elseif ($condition === 'after') {
                            $replacementText = Strings::fillPlaceholders($text, $name)
                                               . Strings::fillPlaceholders($replacement['replace'], $name);
                        } elseif ($condition === 'replace') {
                            $replacementText = Strings::fillPlaceholders($replacement['replace'], $name);
                        }

                        if (!$replacementText) {
                            continue;
                        }

                        $this->file = str_replace(
                            Strings::fillPlaceholders($text, $name),
                            $replacementText,
                            $this->file
                        );
                        $conditionMet = true;
                    }
                }
            }
        }

        return file_put_contents($this->fileName, $this->file);
    }
}
