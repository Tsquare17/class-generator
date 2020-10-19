<?php

namespace Tsquare\FileGenerator;

/**
 * Class FileGenerator
 * @package Tsquare\FileGenerator
 */
class FileGenerator
{
    protected Template $template;
    protected ?string $fileName;
    protected string $className;
    protected string $namespace;
    protected string $path;
    protected string $fileContents;

    protected const DOUBLE_EOL = PHP_EOL . PHP_EOL;

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
        // Get the name of the file.
        $this->getFileName();

        // Get the name of the class to use.
        $this->getClassName();

        // Get the path the file should go.
        $this->getPath();

        // Get the namespace.
        $this->getNamespace();

        // Assemble the contents of the file.
        $this->assembleContents();

        // Create the file and any missing directories.
        $this->generateFile();

        return true;
    }

    public function getFileName(): void
    {
        $this->fileName = $this->template->getFileName();
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

        $this->className = $this->template->getClassName();
    }

    /**
     * Get the file path.
     *
     * @return bool
     */
    public function getPath(): bool
    {
        $path = $this->template->getPath();

        if ($rule = $this->template->getPathRule()) {
            $path .= '/' . $this->fillPlaceholders($rule['path']);
        }

        if (!is_dir($path)) {
            $this->createPath($path);
        }

        $this->path = $path;

        return true;
    }

    /**
     * Get the namespace.
     */
    public function getNamespace(): void
    {
        if ($namespace = $this->template->getNamespace()) {
            $this->namespace = 'namespace ' . $namespace . ';';
            return;
        }

        $path = str_replace($this->template->getAppDir(), '', $this->path) . ';';

        $this->namespace = 'namespace ' . substr(str_replace('/', '\\', $path), 1);
    }

    /**
     * Put together the contents of the file.
     */
    public function assembleContents(): void
    {
        if ($content = $this->template->getFileContent()) {
            $this->fileContents = $this->fillPlaceholders($content);
            return;
        }

        $contents = '<?php' . self::DOUBLE_EOL . $this->namespace . self::DOUBLE_EOL;

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

        $this->fileContents = $contents;
    }

    /**
     * Write the contents to file.
     *
     * @return bool
     */
    public function generateFile(): bool
    {
        $fileName = $this->fileName ?: $this->className;

        return file_put_contents($this->path . '/' . $fileName . '.php', $this->fileContents);
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
     * Get the absolute path of the file.
     *
     * @return string
     */
    public function getPathString(): string
    {
        return $this->path . '/' . ($this->fileName ?: $this->className) . '.php';
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
        $name = $this->template->getClassName();
        $camel = lcfirst($name);
        $pascal = ucfirst($name);
        $underscore = self::pascalTo($name, '_');
        $dashed = self::pascalTo($name, '-');

        return str_replace(
            ['{name}', '{camel}', '{pascal}', '{underscore}', '{dash}'],
            [$name, $camel, $pascal, $underscore, $dashed],
            $string
        );
    }

    /**
     * Take a string in PascalCase and return it in lower case, split with the provided glue.
     *
     * @param string $string
     * @param string $glue
     *
     * @return string
     */
    public static function pascalTo(string $string, string $glue): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        foreach ($matches[0] as &$match) {
            $match = ($match === strtoupper($match)) ? strtolower($match) : lcfirst($match);
        }

        return implode($glue, $matches[0]);
    }
}
