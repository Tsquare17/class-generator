<?php

namespace Tsquare\FileGenerator;

use Tsquare\FileGenerator\Utils\Strings;

/**
 * Class FileGenerator
 * @package Tsquare\FileGenerator
 */
class FileGenerator
{
    protected Template $template;
    protected ?string $fileName = null;
    protected string $fileExtension;
    protected string $name;
    protected string $path;
    protected string $fileContents;

    /**
     * FileGenerator constructor.
     *
     * @param Template $template
     */
    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    /**
     * Create the class file.
     *
     * @return bool
     */
    public function create(): bool
    {
        // Get the file extension.
        $this->getFileExtension();

        // Get the name of the file.
        $this->getFileName();

        // Get the name to use.
        $this->getName();

        // Get the path the file should go.
        $this->getPath();

        // Set the contents of the file.
        $this->setContents();

        // Create the file.
        return $this->generateFile();
    }

    /**
     * Get the name of the file.
     */
    public function getFileName(): void
    {
        if ($fileName = str_replace($this->fileExtension, '', $this->template->getFileName())) {
            $this->fileName = $this->fillPlaceholders($fileName, $this->template->getName());
        }
    }

    public function getFileExtension(): void
    {
        $offset = strrpos($this->template->getFileName(), ".");
        $this->fileExtension = substr($this->template->getFileName(), $offset);
    }

    /**
     * Get the name.
     */
    public function getName(): void
    {
        $this->name = $this->template->getName();
    }

    /**
     * Get the file path.
     *
     * @return bool
     */
    public function getPath(): bool
    {
        $path = $this->template->getAppBasePath()
                . $this->fillPlaceholders($this->template->getDestinationPath(), $this->template->getName());

        if (!is_dir($path)) {
            $this->createPath($path);
        }

        $this->path = $path;

        return true;
    }

    /**
     * Set the contents of the file.
     */
    protected function setContents(): void
    {
        if (!$this->template->getFileContent()) {
            return;
        }

        $this->fileContents = $this->fillPlaceholders(
            $this->template->getFileContent(),
            $this->template->getName()
        );
    }

    /**
     * Write the contents to file.
     *
     * @return bool
     */
    protected function generateFile(): bool
    {
        $fileName = $this->fileName ?: $this->name;
        $filePath = $this->path . '/' . $fileName . $this->fileExtension;

        if ($edited = $this->template->executeFileEdits($this->getPathString())) {
            return $edited;
        }

        if (is_file($filePath)) {
            return false;
        }

        return file_put_contents($filePath, $this->fileContents);
    }

    /**
     * Create non-existent directories.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function createPath(string $path): bool
    {
        $basePath = $this->template->getAppBasePath();
        $createPath = str_replace($basePath . '/', '', $path);
        $pathArray = explode('/', $createPath);

        $currentPath = $basePath;
        foreach ($pathArray as $dir) {
            if (
                !is_dir($currentPath . '/' . $dir)
                 && ! mkdir($concurrentDirectory = $currentPath . '/' . $dir)
                 && ! is_dir($concurrentDirectory)
            ) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }

            $currentPath .= '/' . $dir;
        }

        return true;
    }

    /**
     * Get the absolute path of the file.
     *
     * @return string
     */
    public function getPathString(): string
    {
        return $this->path . '/' . ($this->fileName ?: $this->name) . '.php';
    }

    /**
     * Replace the placeholders in a string.
     *
     * @param string $content
     * @param string $replacement
     *
     * @return string
     */
    public function fillPlaceholders(string $content, string $replacement): string
    {
        return Strings::fillPlaceholders($content, $replacement, $this->template->getReplacementTokens());
    }
}
