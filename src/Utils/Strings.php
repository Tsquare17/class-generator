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
        $camel = lcfirst($name);
        $pascal = ucfirst($name);
        $underscore = self::pascalTo($name, '_');
        $dashed = self::pascalTo($name, '-');

        $tokens = ['{name}', '{camel}', '{pascal}', '{underscore}', '{dash}'];
        $replacements = [$name, $camel, $pascal, $underscore, $dashed];
        foreach ($customTokens as $token => $value) {
            $tokens[] = $token;
            $replacements[] = $value;
        }

        return str_replace(
            $tokens,
            $replacements,
            $string
        );
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
}
