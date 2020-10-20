<?php

namespace Tsquare\FileGenerator;

/**
 * Class FileGenerator
 * @package Tsquare\FileGenerator
 */
class FileGenerator
{
    protected Template $template;
    protected ?string $fileName = null;
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
        if ($fileName = $this->template->getFileName()) {
            $this->fileName = $this->fillPlaceholders($this->template->getFileName());
        }
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
        $path = $this->fillPlaceholders($this->template->getDestinationPath());

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
        $this->fileContents = '<?php' . PHP_EOL . $this->fillPlaceholders($this->template->getFileContent());
    }

    /**
     * Write the contents to file.
     *
     * @return bool
     */
    protected function generateFile(): bool
    {
        $fileName = $this->fileName ?: $this->name;
        $filePath = $this->path . '/' . $fileName . '.php';

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
     * Replace placeholders with a value.
     *
     * @param string $string
     *
     * @return string
     */
    protected function fillPlaceholders(string $string): string
    {
        $name = $this->template->getName();
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
    protected static function pascalTo(string $string, string $glue): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        foreach ($matches[0] as &$match) {
            $match = ($match === strtoupper($match)) ? strtolower($match) : lcfirst($match);
        }

        return implode($glue, $matches[0]);
    }
}
