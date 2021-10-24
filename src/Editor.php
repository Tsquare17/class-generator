<?php

namespace Tsquare\FileGenerator;

/**
 * Interface Editor
 * @package Tsquare\FileGenerator
 */
interface Editor
{
    /**
     * Specify the file to be edited.
     *
     * @param string $file
     *
     * @return Editor
     */
    public function file(string $file): Editor;

    /**
     * Insert a string on the line before another string.
     *
     * @param string $insert
     * @param string $before
     * @param array  $or
     *
     * @return Editor
     */
    public function insertBefore(string $insert, string $before, array $or = []): Editor;

    /**
     * Insert a string on the line after a string.
     *
     * @param string $insert
     * @param string $after
     * @param array  $or
     *
     * @return Editor
     */
    public function insertAfter(string $insert, string $after, array $or = []): Editor;

    /**
     * Replace a string with another string.
     *
     * @param string $search
     * @param string $replace
     * @param array  $or
     *
     * @return Editor
     */
    public function replace(string $search, string $replace, array $or = []): Editor;

    /**
     * Execute file edits.
     *
     * @param string $name
     * @param array $customTokens
     *
     * @return bool
     */
    public function execute(string $name, array $customTokens = []): bool;
}
