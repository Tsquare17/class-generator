<?php

namespace Tsquare\FileGenerator;

/**
 * Class FileTemplate
 * @package Tsquare\FileGenerator
 */
class FileTemplate implements Template
{
    protected ?string $fileName;
    protected ?string $fileContent;
    protected string $className;
    protected ?string $classNameRule = null;
    protected ?string $classNamespace = null;
    protected ?string $extends = null;
    protected ?string $implements = null;
    protected string $appDir;
    protected string $path;
    protected ?array $pathRule;
    protected string $header;
    protected string $body;

    /**
     * Initialize FileTemplate, pulling in the specified template file.
     *
     * @param string $file
     *
     * @return FileTemplate
     */
    public static function init(string $file): FileTemplate
    {
        $template = new static();

        $template->className = str_replace('.php', '', strrev(explode('/', strrev($file))[0]));

        require $file;

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
     * Set the class name.
     *
     * @param string $className
     *
     * @return FileTemplate
     */
    public function className(string $className): FileTemplate
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get the class name.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Set a modification rule for the generated class name.
     *
     * @param string $nameRule
     *
     * @return FileTemplate
     */
    public function nameRule(string $nameRule): FileTemplate
    {
        $this->classNameRule = $nameRule;

        return $this;
    }

    /**
     * Get the name modification rule.
     *
     * @return string|null
     */
    public function getNameRule(): ?string
    {
        return $this->classNameRule;
    }

    /**
     * Set the class namespace.
     *
     * @param string $namespace
     *
     * @return FileTemplate
     */
    public function namespace(string $namespace): FileTemplate
    {
        $this->classNamespace = $namespace;

        return $this;
    }

    /**
     * Get the class namespace.
     *
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->classNamespace;
    }

    /**
     * Set a parent class to extend.
     *
     * @param string $extendedClass
     *
     * @return FileTemplate
     */
    public function extends(string $extendedClass): FileTemplate
    {
        $this->extends = $extendedClass;

        return $this;
    }

    /**
     * Get the extended parent class.
     *
     * @return string|null
     */
    public function getExtends(): ?string
    {
        return $this->extends;
    }

    /**
     * Set an implementation class.
     *
     * @param string $implementsClass
     *
     * @return FileTemplate
     */
    public function implements(string $implementsClass): FileTemplate
    {
        $this->implements = $implementsClass;

        return $this;
    }

    /**
     * Get the implementation class.
     *
     * @return string|null
     */
    public function getImplements(): ?string
    {
        return $this->implements;
    }

    /**
     * Set the base application path.
     *
     * @param string $path
     *
     * @return FileTemplate
     */
    public function appDir(string $path): FileTemplate
    {
        $this->appDir = $path;

        return $this;
    }

    /**
     * Get the base application path.
     *
     * @return string
     */
    public function getAppDir(): string
    {
        return $this->appDir;
    }

    /**
     * Set the path for the generated class file.
     *
     * @param string $path
     *
     * @return FileTemplate
     */
    public function path(string $path): FileTemplate
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the class file path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set a modification rule for the class path.
     *
     * @param string $pathRule
     * @param false  $usesClassNameRule
     *
     * @return FileTemplate
     */
    public function pathRule(string $pathRule, $usesClassNameRule = false): FileTemplate
    {
        $this->pathRule = [
            'path' => $pathRule,
            'usesClassNameRule' => $usesClassNameRule,
        ];

        return $this;
    }

    /**
     * Get the class path modification rule.
     *
     * @return array|null
     */
    public function getPathRule(): ?array
    {
        return isset($this->pathRule['path']) ? $this->pathRule : null;
    }

    /**
     * Set the class header content.
     *
     * @param string $classHeader
     *
     * @return FileTemplate
     */
    public function header(string $classHeader): FileTemplate
    {
        $this->header = $classHeader;

        return $this;
    }

    /**
     * Get the class header content.
     *
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * Set the class body content.
     *
     * @param string $classBody
     *
     * @return FileTemplate
     */
    public function body(string $classBody): FileTemplate
    {
        $this->body = $classBody;

        return $this;
    }

    /**
     * Get the class body content.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
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
}
