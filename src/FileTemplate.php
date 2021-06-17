<?php

namespace Tsquare\FileGenerator;

/**
 * Class FileTemplate
 * @package Tsquare\FileGenerator
 */
class FileTemplate implements Template
{
    /**
     * @var string|null
     */
    protected $fileName = null;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $title = null;

    /**
     * @var string|null
     */
    protected $appBasePath = null;

    /**
     * @var string
     */
    protected $destinationPath;

    /**
     * @var string|null
     */
    protected $fileContent = null;

    /**
     * @var Editor
     */
    protected $editor;

    /**
     * @var TokenAction[]
     */
    protected $customTokens = [];

    /**
     * Initialize FileTemplate, pulling in the specified template file.
     *
     * @param string $templateFile
     *
     * @return Template
     */
    public static function init(string $templateFile): Template
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
     * @return Template
     */
    public function fileName(string $fileName): Template
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get the file name.
     *
     * @return string|null
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set the name.
     *
     * @param string $name
     *
     * @return Template
     */
    public function name(string $name): Template
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
     * @return Template
     */
    public function appBasePath(string $path): Template
    {
        $this->appBasePath = $path;

        return $this;
    }

    /**
     * Get the base application path.
     *
     * @return string|null
     */
    public function getAppBasePath()
    {
        return $this->appBasePath;
    }

    /**
     * Set the destination path for the generated file.
     *
     * @param string $path
     *
     * @return Template
     */
    public function destinationPath(string $path): Template
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
     * @return Template
     */
    public function fileContent(string $fileContent): Template
    {
        $this->fileContent = $fileContent;

        return $this;
    }

    /**
     * Get the file content.
     *
     * @return string|null
     */
    public function getFileContent()
    {
        return $this->fileContent;
    }

    /**
     * Sets an Editor to use, in the event that the file already exists.
     *
     * @param Editor $editor
     *
     * @return Template
     */
    public function fileEditor(Editor $editor): Template
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

    /**
     * Add custom replacement tokens.
     *
     * @return Template
     */
    public function addReplacementToken(TokenAction $tokenAction): Template
    {
        $this->customTokens[] = $tokenAction;

        return $this;
    }

    /**
     * Get the custom replacement tokens.
     *
     * @return array
     */
    public function getReplacementTokens(): array
    {
        return $this->customTokens;
    }
}
