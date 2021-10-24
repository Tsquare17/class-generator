<?php

use Tsquare\FileGenerator\FileEditor;
use Tsquare\FileGenerator\FileTemplate;

/**
 * @var FileTemplate $template
 */


/**
 * Define the application root.
 */
$template->appBasePath(dirname(__DIR__, 1));


/**
 * Define the base path for the file.
 */
$template->destinationPath('/Fixtures');


/**
 * Set the file name.
 */
$template->fileName('OnlyEdit.php');

/**
 * Define the name used to fill placeholders.
 */
$template->name('Testing');

/**
 * Prepend the file contents.
 */
$template->prepend('<?php' . PHP_EOL . PHP_EOL);

/**
 * Define the contents of the file.
 */
$template->fileContent(
    <<<'FILE'
$test = 'test';

FILE
);
