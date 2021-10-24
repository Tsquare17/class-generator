<?php

namespace Tsquare\FileGenerator\Utils;

use Tsquare\FileGenerator\TokenAction;

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
     * Convert a string to PascalCase.
     *
     * @param string $string
     *
     * @return string
     */
    public static function toPascal(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
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
        $reverse = strrev($string);
        $last = $reverse[0];
        $nextToLast = $reverse[1];
        $lastTwo = $nextToLast . $last;

        if (in_array($lastTwo, ['ss', 'sh', 'ch'])) {
            return $string . 'es';
        }

        if ($last === 'y') {
            if (in_array($nextToLast, ['a', 'e', 'i', 'o', 'u'])) {
                return $string . 's';
            }

            return rtrim($string, 'y') . 'ies';
        }

        if ($last === 's') {
            return $string;
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
     * @param TokenAction[]  $customTokens
     *
     * @return string
     */
    public static function executeTokenAction(array $tokens, string $string, array $customTokens = []): string
    {
        // order tokens...
        $defaultTokens = self::getDefaultTokens();

        $tokenCollection = array_merge($defaultTokens, $customTokens);

        usort($tokenCollection, static function (TokenAction $a, TokenAction $b) {
            return $b->getPriority() <=> $a->getPriority();
        });

        $orderedTokens = [];
        foreach ($tokenCollection as $tokenAction) {
            foreach ($tokens as $token) {
                if ($token === $tokenAction->getName()) {
                    $orderedTokens[] = $token;
                }
            }
        }

        foreach ($orderedTokens as $token) {
            foreach ($tokenCollection as $tokenAction) {
                if ($token === $tokenAction->getName()) {
                    $string = $tokenAction->getAction()($string);
                }
            }
        }

        return $string;
    }

    /**
     * @return TokenAction[]
     */
    protected static function getDefaultTokens()
    {
        return [
            new TokenAction('camel', static function ($token) {
                return lcfirst(self::toPascal($token));
            }),
            new TokenAction('pascal', static function ($token) {
                return self::toPascal($token);
            }),
            new TokenAction('underscore', function ($token) {
                return self::pascalTo($token, '_');
            }),
            new TokenAction('dash', function ($token) {
                return self::pascalTo($token, '-');
            }),
            new TokenAction('plural', function ($token) {
                return self::plural($token);
            }),
            new TokenAction('upper', static function ($token) {
                return strtoupper($token);
            }),
            new TokenAction('lower', static function ($token) {
                return strtolower($token);
            }),
            new TokenAction('title', static function ($token) {
                return ucwords(self::pascalTo($token, ' '));
            })
        ];
    }
}
