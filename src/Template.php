<?php

namespace Tsquare\FileGenerator;

/**
 * Class Template
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
     * Set the class name.
     *
     * @param string $className
     *
     * @return Template
     */
    public function className(string $className): Template;

    /**
     * Get the class name.
     *
     * @return string
     */
    public function getClassName(): string;

    /**
     * Set a modification rule for the generated class name.
     *
     * @param string $nameRule
     *
     * @return Template
     */
    public function nameRule(string $nameRule): Template;

    /**
     * Get the name modification rule.
     *
     * @return string|null
     */
    public function getNameRule(): ?string;

    /**
     * Set the class namespace.
     *
     * @param string $namespace
     *
     * @return Template
     */
    public function namespace(string $namespace): Template;

    /**
     * Get the class namespace.
     *
     * @return string|null
     */
    public function getNamespace(): ?string;

    /**
     * Set a parent class to extend.
     *
     * @param string $extendedClass
     *
     * @return Template
     */
    public function extends(string $extendedClass): Template;

    /**
     * Get the extended parent class.
     *
     * @return string|null
     */
    public function getExtends(): ?string;

    /**
     * Set an implementation class.
     *
     * @param string $implementsClass
     *
     * @return Template
     */
    public function implements(string $implementsClass): Template;

    /**
     * Get the implementation class.
     *
     * @return string|null
     */
    public function getImplements(): ?string;

    /**
     * Set the base application path.
     *
     * @param string $path
     *
     * @return Template
     */
    public function appDir(string $path): Template;

    /**
     * Get the base application path.
     *
     * @return string
     */
    public function getAppDir(): string;

    /**
     * Set the path for the generated class file.
     *
     * @param string $path
     *
     * @return Template
     */
    public function path(string $path): Template;

    /**
     * Get the class file path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Set a modification rule for the class path.
     *
     * @param string $pathRule
     * @param false  $usesClassNameRule
     *
     * @return Template
     */
    public function pathRule(string $pathRule, $usesClassNameRule = false): Template;

    /**
     * Get the class path modification rule.
     *
     * @return array|null
     */
    public function getPathRule(): ?array;

    /**
     * Set the class header content.
     *
     * @param string $classHeader
     *
     * @return Template
     */
    public function header(string $classHeader): Template;

    /**
     * Get the class header content.
     *
     * @return string
     */
    public function getHeader(): string;

    /**
     * Set the class body content.
     *
     * @param string $classBody
     *
     * @return Template
     */
    public function body(string $classBody): Template;

    /**
     * Get the class body content.
     *
     * @return string
     */
    public function getBody(): string;

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
