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
     * @param array $or
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
            'regex' => false,
        ];

        return $this;
    }

    /**
     * Add a condition to insert only if the file contains a string.
     *
     * @param string $string
     *
     * @return FileEditor
     */
    public function ifNotContaining(string $string): FileEditor
    {
        $index = count($this->replacements) - 1;
        $this->replacements[$index]['not'] = $string;

        return $this;
    }

    /**
     * Specify that the search string is a regular expression.
     *
     * @return FileEditor
     */
    public function isRegex(): FileEditor
    {
        $index = count($this->replacements) - 1;
        $this->replacements[$index]['regex'] = true;

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

            if (
                isset($replacement['not'])
                && strpos($this->file, Strings::fillPlaceholders($replacement['not'], $name))
            ) {
                continue;
            }

            if ($replacement['regex']) {
                $matched = preg_match($replacement['search'], $this->file, $match);
                if ($matched) {
                    $this->file = str_replace(
                        Strings::fillPlaceholders($match[0], $name),
                        Strings::fillPlaceholders($replacement['replace'], $name),
                        $this->file
                    );
                    $conditionMet = true;
                }
            } elseif (strpos($this->file, Strings::fillPlaceholders($replacement['search'], $name))) {
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

                    $replacementText = null;
                    if ($condition === 'before') {
                        $replacementText = $replacement['replace'] . $text;
                    } elseif ($condition === 'after') {
                        $replacementText = $text . $replacement['replace'];
                    } elseif ($condition === 'replace') {
                        $replacementText = $replacement['replace'];
                    }

                    if (!$replacementText) {
                        continue;
                    }

                    if ($replacement['regex']) {
                        $matched = preg_match(Strings::fillPlaceholders($text, $name), $this->file, $match);
                        if ($matched) {
                            $this->file = str_replace(
                                Strings::fillPlaceholders($match[0], $name),
                                Strings::fillPlaceholders($replacementText, $name),
                                $this->file
                            );

                            $conditionMet = true;
                        }
                    } elseif (strpos($this->file, Strings::fillPlaceholders($text, $name))) {
                        $this->file = str_replace(
                            Strings::fillPlaceholders($text, $name),
                            Strings::fillPlaceholders($replacementText, $name),
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
