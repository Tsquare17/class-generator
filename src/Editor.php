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
     *
     * @return Editor
     */
    public function insertBefore(string $insert, string $before): Editor;

    /**
     * Insert a string on the line after a string.
     *
     * @param string $insert
     * @param string $after
     *
     * @return Editor
     */
    public function insertAfter(string $insert, string $after): Editor;

    /**
     * Replace a string with another string.
     *
     * @param string $search
     * @param string $replace
     *
     * @return Editor
     */
    public function replace(string $search, string $replace): Editor;

    /**
     * Execute file edits.
     *
     * @param string $name
     *
     * @return bool
     */
    public function execute(string $name): bool;
}
