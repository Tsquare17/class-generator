<?php

namespace Tsquare\FileGenerator\Utils;

/**
 * Class Strings
 * @package Tsquare\FileGenerator\Utils
 */
class Strings
{
    /**
     * Replace placeholders with a value.
     *
     * @param string $string
     * @param string $name
     * @param array  $customTokens
     *
     * @return string
     */
    public static function fillPlaceholders(string $string, string $name, array $customTokens = []): string
    {
        $fileTokens = self::getTokens($string);
        foreach ($fileTokens[1] as $fileToken) {
            $tokenActions = explode(':', $fileToken);

            $replacementString = self::executeTokenAction($tokenActions, $name, $customTokens);
            $string = str_replace('{' . $fileToken . '}', $replacementString, $string);
        }

        return $string;
    }

    /**
     * Take a string in PascalCase and return it in lower case, split with the provided glue.
     *
     * @param string $string
     * @param string $glue
     *
     * @return string
     */
    public static function pascalTo(string $string, string $glue): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        foreach ($matches[0] as &$match) {
            $match = ($match === strtoupper($match)) ? strtolower($match) : lcfirst($match);
        }

        return implode($glue, $matches[0]);
    }

    /**
     * Get the plural form of a word.
     *
     * @param string $string
     *
     * @return string
     */
    public static function plural(string $string): string
    {
        if (strpos(strrev($string), 'y') === 0) {
            return rtrim($string, 'y') . 'ies';
        }

        return $string . 's';
    }

    /**
     * Get all tokens in a string.
     *
     * @param string $string
     *
     * @return array
     */
    public static function getTokens(string $string): array
    {
        preg_match_all('/{(.[a-z:-_]+?)}/', $string, $matches);

        return $matches;
    }

    /**
     * Perform the associated token actions.
     *
     * @param array  $tokens
     * @param string $string
     * @param array  $customTokens
     *
     * @return string
     */
    public static function executeTokenAction(array $tokens, string $string, array $customTokens = []): string
    {
        foreach ($tokens as $token) {
            if ($token === 'camel') {
                $string = lcfirst($string);
            }

            if ($token === 'pascal') {
                $string = ucfirst($string);
            }

            if ($token === 'underscore') {
                $string = self::pascalTo($string, '_');
            }

            if ($token === 'dash') {
                $string = self::pascalTo($string, '-');
            }

            if ($token === 'plural') {
                $string = self::plural($string);
            }

            if ($token === 'upper') {
                $string = strtoupper($string);
            }

            if ($token === 'lower') {
                $string = strtolower($string);
            }

            if (isset($customTokens[$token])) {
                $string = $customTokens[$token]($string);
            }
        }

        return $string;
    }
}
