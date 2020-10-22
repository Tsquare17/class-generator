<?php

namespace Tsquare\FileGenerator;

/**
 * Class FileTemplate
 * @package Tsquare\FileGenerator
 */
class FileTemplate implements Template
{
    protected ?string $fileName = null;
    protected string $name;
    protected string $appBasePath;
    protected string $destinationPath;
    protected string $fileContent;
    protected Editor $editor;

    /**
     * Initialize FileTemplate, pulling in the specified template file.
     *
     * @param string $templateFile
     *
     * @return FileTemplate
     */
    public static function init(string $templateFile): FileTemplate
    {
        $template = new static();

        require $templateFile;

        return $template;
    }

    /**
     * Set the file name.
     *
     * @param string $fileName
     *
     * @return FileTemplate
     */
    public function fileName(string $fileName): FileTemplate
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get the file name.
     *
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * Set the name.
     *
     * @param string $name
     *
     * @return FileTemplate
     */
    public function name(string $name): FileTemplate
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the base application path.
     *
     * @param string $path
     *
     * @return FileTemplate
     */
    public function appBasePath(string $path): FileTemplate
    {
        $this->appBasePath = $path;

        return $this;
    }

    /**
     * Get the base application path.
     *
     * @return string
     */
    public function getAppBasePath(): string
    {
        return $this->appBasePath;
    }

    /**
     * Set the destination path for the generated file.
     *
     * @param string $path
     *
     * @return FileTemplate
     */
    public function destinationPath(string $path): FileTemplate
    {
        $this->destinationPath = $path;

        return $this;
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    public function getDestinationPath(): string
    {
        return $this->destinationPath;
    }

    /**
     * Set the file content.
     *
     * @param string $fileContent
     *
     * @return FileTemplate
     */
    public function fileContent(string $fileContent): FileTemplate
    {
        $this->fileContent = $fileContent;

        return $this;
    }

    /**
     * Get the file content.
     *
     * @return string|null
     */
    public function getFileContent(): ?string
    {
        return $this->fileContent;
    }

    /**
     * Sets an Editor to use, in the event that the file already exists.
     *
     * @param Editor $editor
     *
     * @return FileTemplate
     */
    public function ifFileExists(Editor $editor): FileTemplate
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Run the Editor's file modifications.
     *
     * @param string $file
     *
     * @return bool
     */
    public function executeFileEdits(string $file): bool
    {
        if (!isset($this->editor)) {
            return false;
        }

        $this->editor->file($file);


        return $this->editor->execute($this->name);
    }
}
