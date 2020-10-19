<?php

namespace Tsquare\FileGenerator;

/**
 * Interface Template
 * @package Tsquare\FileGenerator
 */
interface Template
{
    /**
     * Initialize Template, pulling in the specified template file.
     *
     * @param string $file
     *
     * @return Template
     */
    public static function init(string $file): Template;

    /**
     * Set the file name.
     *
     * @param string $fileName
     *
     * @return Template
     */
    public function fileName(string $fileName): Template;

    /**
     * Get the file name.
     *
     * @return string|null
     */
    public function getFileName(): ?string;

    /**
     * Set the name.
     *
     * @param string $name
     *
     * @return Template
     */
    public function name(string $name): Template;

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the base application path.
     *
     * @param string $path
     *
     * @return Template
     */
    public function appBasePath(string $path): Template;

    /**
     * Get the base application path.
     *
     * @return string
     */
    public function getAppBasePath(): string;

    /**
     * Set the destination path for the generated file.
     *
     * @param string $path
     *
     * @return Template
     */
    public function destinationPath(string $path): Template;

    /**
     * Get the destination file path.
     *
     * @return string
     */
    public function getDestinationPath(): string;

    /**
     * Set the file content.
     *
     * @param string $fileContent
     *
     * @return Template
     */
    public function fileContent(string $fileContent): Template;

    /**
     * Get the file content.
     *
     * @return string|null
     */
    public function getFileContent(): ?string;
}
