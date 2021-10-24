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
    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var array
     */
    protected $replacements;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $prepend = '';

    /**
     * @var array
     */
    protected $customTokens = [];

    /**
     * Specify the file to be edited.
     *
     * @param string $file
     *
     * @return Editor
     */
    public function file(string $file): Editor
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
     * @return Editor
     */
    public function insertBefore(string $insert, string $before, array $or = []): Editor
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
     * @return Editor
     */
    public function insertAfter(string $insert, string $after, array $or = []): Editor
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
     * @return Editor
     */
    public function replace(string $search, string $replace, array $or = []): Editor
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
     * @return Editor
     */
    public function ifNotContaining(string $string): Editor
    {
        $index = count($this->replacements) - 1;
        $this->replacements[$index]['not'] = $string;

        return $this;
    }

    /**
     * Specify that the search string is a regular expression.
     *
     * @return Editor
     */
    public function isRegex(): Editor
    {
        $index = count($this->replacements) - 1;
        $this->replacements[$index]['regex'] = true;

        return $this;
    }

    /**
     * Execute file edits.
     *
     * @param string $name
     * @param array $customTokens
     *
     * @return bool
     */
    public function execute(string $name, array $customTokens = []): bool
    {
        $this->name = $name;

        $this->customTokens = $customTokens;

        foreach ($this->replacements as $replacement) {
            if ($this->matchNot($replacement)) {
                continue;
            }

            $search = Strings::fillPlaceholders($replacement['search'], $name, $this->customTokens);

            $conditionMet = $this->replaceString($search, $search, $replacement['replace'], $replacement['regex']);

            if ($conditionMet === false && !empty($replacement['or'])) {
                foreach ($replacement['or'] as $condition => $text) {
                    if ($conditionMet === true) {
                        break;
                    }

                    $conditionMet = $this->matchCondition($condition, $text, $replacement);
                }
            }
        }

        return file_put_contents($this->fileName, $this->file);
    }

    /**
     * Determine if the not condition string exists.
     *
     * @param array $replacement
     *
     * @return bool
     */
    protected function matchNot(array $replacement): bool
    {
        if (isset($replacement['not'])) {
            $not = Strings::fillPlaceholders($replacement['not'], $this->name);
            if (strpos($this->file, $not)) {
                return true;
            }

            if ($replacement['regex']) {
                $matched = preg_match(
                    $not,
                    $this->file,
                    $match
                );
                if ($matched) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Match and replace a regular expression.
     *
     * @param string $search
     * @param string $replace
     * @param string $replacement
     *
     * @return bool
     */
    protected function matchRegex(string $search, string $replace, string $replacement): bool
    {
        $matched = preg_match(
            Strings::fillPlaceholders($search, $this->name, $this->customTokens),
            $this->file,
            $match
        );
        if ($matched) {
            $replacementText = str_replace($replace, $match[0], $replacement);
            $this->file = str_replace(
                Strings::fillPlaceholders($match[0], $this->name, $this->customTokens),
                Strings::fillPlaceholders($replacementText, $this->name, $this->customTokens),
                $this->file
            );
            return true;
        }

        return false;
    }

    /**
     * Replace a string based on a condition.
     *
     * @param string $condition
     * @param string $text
     * @param array $replacement
     *
     * @return bool
     */
    protected function matchCondition(string $condition, string $text, array $replacement): bool
    {
        $replacementText = null;
        if ($condition === 'before') {
            $replacementText = $replacement['replace'] . $text;
        } elseif ($condition === 'after') {
            $replacementText = $text . $replacement['replace'];
        } elseif ($condition === 'replace') {
            $replacementText = $replacement['replace'];
        }

        if (!$replacementText) {
            return true;
        }

        return $this->replaceString($text, $replacement['search'], $replacementText, $replacement['regex']);
    }

    /**
     * Replace a string.
     *
     * @param string $search
     * @param string $replace
     * @param string $replacementText
     * @param bool $isRegex
     *
     * @return bool
     */
    protected function replaceString(string $search, string $replace, string $replacementText, bool $isRegex): bool
    {
        if ($isRegex) {
            if ($this->matchRegex($search, $replace, $replacementText)) {
                return true;
            }
        } elseif (strpos($this->file, Strings::fillPlaceholders($search, $this->name, $this->customTokens))) {
            $this->file = str_replace(
                Strings::fillPlaceholders($search, $this->name, $this->customTokens),
                Strings::fillPlaceholders($replacementText, $this->name, $this->customTokens),
                $this->file
            );

            return true;
        }

        return false;
    }
}
