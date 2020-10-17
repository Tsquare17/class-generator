<?php

namespace Tsquare\ClassGenerator;

/**
 * Class ClassTemplate
 * @package Tsquare\ClassGenerator
 */
class ClassTemplate
{
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
     * Initialize ClassTemplate, pulling in the specified template file.
     *
     * @param string $file
     *
     * @return ClassTemplate
     */
    public static function init(string $file): ClassTemplate
    {
        $template = new static();

        $template->className = str_replace('.php', '', strrev(explode('/', strrev($file))[0]));

        require $file;

        return $template;
    }

    /**
     * Set the class name.
     *
     * @param string $className
     *
     * @return $this
     */
    public function name(string $className): ClassTemplate
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get the class name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->className;
    }

    /**
     * Set a modification rule for the generated class name.
     *
     * @param string $nameRule
     *
     * @return $this
     */
    public function nameRule(string $nameRule): ClassTemplate
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
     * @return $this
     */
    public function namespace(string $namespace): ClassTemplate
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
     * @return $this
     */
    public function extends(string $extendedClass): ClassTemplate
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
     * @return $this
     */
    public function implements(string $implementsClass): ClassTemplate
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
     * @return $this
     */
    public function appDir(string $path): ClassTemplate
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
     * @return $this
     */
    public function path(string $path): ClassTemplate
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
     * @return $this
     */
    public function pathRule(string $pathRule, $usesClassNameRule = false): ClassTemplate
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
     * @return $this
     */
    public function header(string $classHeader): ClassTemplate
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
     * @return $this
     */
    public function body(string $classBody): ClassTemplate
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
}
