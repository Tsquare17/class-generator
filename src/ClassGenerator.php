<?php

namespace Tsquare\ClassGenerator;

/**
 * Class ClassGenerator
 * @package Tsquare\ClassGenerator
 */
class ClassGenerator
{
    protected ClassTemplate $template;
    protected string $className;
    protected string $classNamespace;
    protected string $classPath;
    protected string $classFileContents;

    protected const DOUBLE_EOL = PHP_EOL . PHP_EOL;

    /**
     * ClassGenerator constructor.
     *
     * @param ClassTemplate $template
     */
    public function __construct(ClassTemplate $template)
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
        // get the name of the class to use
        $this->getClassName();

        // get the path the class should go
        $this->getClassPath();

        // get the namespace
        $this->getClassNamespace();

        // assemble the contents of the class
        $this->assembleClassContents();

        // create the file and any missing directories
        $this->generateClassFile();

        return true;
    }

    /**
     * Get the class name.
     */
    public function getClassName(): void
    {
        if ($rule = $this->template->getNameRule()) {
            $this->className = $this->fillPlaceholders($rule);
            return;
        }

        $this->className = $this->template->getName();
    }

    /**
     * Get the class path.
     *
     * @return bool
     */
    public function getClassPath(): bool
    {
        $path = $this->template->getPath();

        if ($rule = $this->template->getPathRule()) {
            if ($rule['usesClassNameRule']) {
                $name = $this->className;
            } else {
                $name = $this->template->getName();
            }

            $path .= '/' . $this->fillPlaceholders($rule['path']);
        }

        if (!is_dir($path)) {
            $this->createPath($path);
        }

        $this->classPath = $path;

        return true;
    }

    /**
     * Get the class namespace.
     */
    public function getClassNamespace(): void
    {
        if ($namespace = $this->template->getNamespace()) {
            $this->classNamespace = 'namespace ' . $namespace . ';';
            return;
        }

        $path = str_replace($this->template->getAppDir(), '', $this->classPath) . ';';

        $this->classNamespace = 'namespace ' . substr(str_replace('/', '\\', $path), 1);
    }

    /**
     * Put together the contents of the file.
     */
    public function assembleClassContents(): void
    {
        $contents = '<?php' . self::DOUBLE_EOL . $this->classNamespace . self::DOUBLE_EOL;

        $contents .= $this->fillPlaceholders($this->template->getHeader()) . self::DOUBLE_EOL;

        $contents .= 'class ' . $this->className;

        if ($extends = $this->template->getExtends()) {
            $contents .= ' extends ' . $extends;
        }

        if ($implements = $this->template->getImplements()) {
            $contents .= ' implements ' . $implements;
        }

        $contents .= PHP_EOL . '{';

        $contents .= $this->fillPlaceholders($this->template->getBody());

        $contents .= '}' . PHP_EOL;

        $this->classFileContents = $contents;
    }

    /**
     * Write the contents to file.
     *
     * @return bool
     */
    public function generateClassFile(): bool
    {
        return file_put_contents($this->classPath . '/' . $this->className . '.php', $this->classFileContents);
    }

    /**
     * Create non-existent directories.
     *
     * @param string $path
     *
     * @return bool
     */
    public function createPath(string $path): bool
    {
        $basePath = $this->template->getAppDir();
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
     * Get the absolute path of the class file.
     *
     * @return string
     */
    public function getClassPathString(): string
    {
        return $this->classPath . '/' . $this->className . '.php';
    }

    /**
     * Replace placeholders with a value.
     *
     * @param string $string
     *
     * @return string
     */
    public function fillPlaceholders(string $string): string
    {
        $name = $this->template->getName();
        $camel = lcfirst($name);
        $pascal = ucfirst($name);

        return str_replace(
            ['{class}', '{plural}', '{camel}', '{pascal}', '{camels}', '{pascals}'],
            [$name, $name . 's', $camel, $pascal, $camel . 's', $pascal . 's'],
            $string
        );
    }
}
